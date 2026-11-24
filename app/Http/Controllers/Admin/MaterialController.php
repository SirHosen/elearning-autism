<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MaterialRequest;
use App\Models\Category;
use App\Models\Material;
use App\Services\ImageProcessor;
use App\Services\MediaStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaterialController extends Controller
{
    public function __construct(
        protected ImageProcessor $imageProcessor,
        protected MediaStorage $mediaStorage
    ) {}

    public function index(Request $request): View
    {
        $query = Material::with('category')->orderBy('category_id')->orderBy('sort_order');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('q')) {
            $query->where('label', 'like', '%'.$request->input('q').'%');
        }

        $materials = $query->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.materials.index', compact('materials', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.materials.create', compact('categories'));
    }

    public function store(MaterialRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $imagePath = $this->imageProcessor->processAndStore($request->file('image'), 'materials');
        $audioPath = null;

        if ($request->hasFile('audio')) {
            $audioPath = $this->mediaStorage->storeAudio($request->file('audio'), 'audio');
        }

        Material::create([
            'category_id' => $validated['category_id'],
            'label' => $validated['label'],
            'short_sentence' => $validated['short_sentence'] ?? null,
            'image_path' => $imagePath,
            'audio_path' => $audioPath,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.materials.index')
            ->with('success', 'Materi pembelajaran baru berhasil ditambahkan.');
    }

    public function edit(Material $material): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.materials.edit', compact('material', 'categories'));
    }

    public function update(MaterialRequest $request, Material $material): RedirectResponse
    {
        $validated = $request->validated();

        $imagePath = $material->image_path;
        if ($request->hasFile('image')) {
            $this->mediaStorage->deleteIfExists($material->image_path);
            $imagePath = $this->imageProcessor->processAndStore($request->file('image'), 'materials');
        }

        $audioPath = $material->audio_path;
        if ($request->hasFile('audio')) {
            $this->mediaStorage->deleteIfExists($material->audio_path);
            $audioPath = $this->mediaStorage->storeAudio($request->file('audio'), 'audio');
        }

        $material->update([
            'category_id' => $validated['category_id'],
            'label' => $validated['label'],
            'short_sentence' => $validated['short_sentence'] ?? null,
            'image_path' => $imagePath,
            'audio_path' => $audioPath,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.materials.index')
            ->with('success', 'Materi pembelajaran berhasil diperbarui.');
    }

    public function destroy(Material $material): RedirectResponse
    {
        if ($material->isUsedInQuizzes()) {
            return back()->with('error', 'Materi ini dipakai di pertanyaan kuis. Nonaktifkan saja daripada menghapusnya.');
        }

        $this->mediaStorage->deleteIfExists($material->image_path);
        $this->mediaStorage->deleteIfExists($material->audio_path);

        $material->delete();

        return redirect()->route('admin.materials.index')
            ->with('success', 'Materi pembelajaran berhasil dihapus.');
    }
}
