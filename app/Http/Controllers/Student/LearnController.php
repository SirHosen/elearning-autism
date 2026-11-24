<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Material;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LearnController extends Controller
{
    public function index(): View
    {
        $categories = Category::where('is_active', true)
            ->withCount(['materials' => function ($q) {
                $q->where('is_active', true);
            }])
            ->orderBy('sort_order')
            ->get();

        return view('student.learn.index', compact('categories'));
    }

    public function showCategory(Category $category): RedirectResponse|View
    {
        if (! $category->is_active) {
            abort(404);
        }

        $materials = $category->activeMaterials()->orderBy('sort_order')->get();

        if ($materials->isEmpty()) {
            return view('student.learn.empty', compact('category'));
        }

        return redirect()->route('student.learn.material', [
            'category' => $category->slug,
            'material' => $materials->first()->id,
        ]);
    }

    public function showMaterial(Category $category, Material $material): View
    {
        if (! $category->is_active || ! $material->is_active || $material->category_id !== $category->id) {
            abort(404);
        }

        $materials = $category->activeMaterials()->orderBy('sort_order')->get();
        $currentIndex = $materials->search(fn ($m) => $m->id === $material->id);

        if ($currentIndex === false) {
            abort(404);
        }

        $prevMaterial = $currentIndex > 0 ? $materials[$currentIndex - 1] : null;
        $nextMaterial = $currentIndex < $materials->count() - 1 ? $materials[$currentIndex + 1] : null;

        return view('student.learn.viewer', [
            'category' => $category,
            'material' => $material,
            'currentIndex' => $currentIndex + 1,
            'totalMaterials' => $materials->count(),
            'prevMaterial' => $prevMaterial,
            'nextMaterial' => $nextMaterial,
        ]);
    }
}
