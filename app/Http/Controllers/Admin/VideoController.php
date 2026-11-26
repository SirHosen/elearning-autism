<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Video;
use App\Services\MediaStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function __construct(
        private readonly MediaStorage $mediaStorage
    ) {}

    public function index(Request $request): View
    {
        $query = Video::with('category')->latest();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $videos = $query->paginate(12)->withQueryString();
        $categories = Category::active()->orderBy('name')->get();

        return view('admin.videos.index', compact('videos', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::active()->orderBy('name')->get();

        return view('admin.videos.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'source_type' => 'required|in:upload,youtube',
            'video_file' => 'required_if:source_type,upload|nullable|file|mimes:mp4|max:51200',
            'youtube_url' => 'required_if:source_type,youtube|nullable|string',
            'description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ], [
            'title.required' => 'Judul video wajib diisi.',
            'video_file.required_if' => 'File video wajib diunggah untuk tipe unggah.',
            'video_file.mimes' => 'Format video harus berupa MP4.',
            'video_file.max' => 'Ukuran video maksimal 50MB.',
            'youtube_url.required_if' => 'URL YouTube wajib diisi untuk tipe YouTube.',
        ]);

        $videoPath = null;
        $youtubeId = null;

        if ($validated['source_type'] === 'upload') {
            if (! $request->hasFile('video_file')) {
                throw ValidationException::withMessages([
                    'video_file' => 'File video MP4 wajib diunggah.',
                ]);
            }
            $videoPath = $this->mediaStorage->storeVideo($request->file('video_file'));
        } else {
            $youtubeId = MediaStorage::extractYouTubeId($validated['youtube_url'] ?? null);
            if (! $youtubeId) {
                throw ValidationException::withMessages([
                    'youtube_url' => 'URL YouTube tidak valid atau ID video tidak dapat ditemukan.',
                ]);
            }
        }

        Video::create([
            'title' => $validated['title'],
            'category_id' => $validated['category_id'] ?? null,
            'source_type' => $validated['source_type'],
            'video_path' => $videoPath,
            'youtube_id' => $youtubeId,
            'description' => $validated['description'] ?? null,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
        ]);

        return redirect()->route('admin.videos.index')->with('success', 'Video berhasil ditambahkan.');
    }

    public function edit(Video $video): View
    {
        $categories = Category::where('is_active', true)
            ->when($video->category_id, fn ($q) => $q->orWhere('id', $video->category_id))
            ->orderBy('name')
            ->get();

        return view('admin.videos.edit', compact('video', 'categories'));
    }

    public function update(Request $request, Video $video): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'source_type' => 'required|in:upload,youtube',
            'video_file' => 'nullable|file|mimes:mp4|max:51200',
            'youtube_url' => 'required_if:source_type,youtube|nullable|string',
            'description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ], [
            'title.required' => 'Judul video wajib diisi.',
            'video_file.mimes' => 'Format video harus berupa MP4.',
            'video_file.max' => 'Ukuran video maksimal 50MB.',
            'youtube_url.required_if' => 'URL YouTube wajib diisi untuk tipe YouTube.',
        ]);

        $videoPath = $video->video_path;
        $youtubeId = $video->youtube_id;

        if ($validated['source_type'] === 'upload') {
            if ($request->hasFile('video_file')) {
                $this->mediaStorage->deleteIfExists($video->video_path);
                $videoPath = $this->mediaStorage->storeVideo($request->file('video_file'));
            }
            $youtubeId = null;
        } else {
            $extractedId = MediaStorage::extractYouTubeId($validated['youtube_url'] ?? null);
            if (! $extractedId) {
                throw ValidationException::withMessages([
                    'youtube_url' => 'URL YouTube tidak valid atau ID video tidak dapat ditemukan.',
                ]);
            }
            $youtubeId = $extractedId;
            if ($video->video_path) {
                $this->mediaStorage->deleteIfExists($video->video_path);
                $videoPath = null;
            }
        }

        $video->update([
            'title' => $validated['title'],
            'category_id' => $validated['category_id'] ?? null,
            'source_type' => $validated['source_type'],
            'video_path' => $videoPath,
            'youtube_id' => $youtubeId,
            'description' => $validated['description'] ?? null,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : $video->is_active,
        ]);

        return redirect()->route('admin.videos.index')->with('success', 'Video berhasil diperbarui.');
    }

    public function toggleActive(Video $video): RedirectResponse
    {
        $video->update(['is_active' => ! $video->is_active]);
        $statusText = $video->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Video '{$video->title}' berhasil {$statusText}.");
    }

    public function destroy(Video $video): RedirectResponse
    {
        $this->mediaStorage->deleteIfExists($video->video_path);
        $this->mediaStorage->deleteIfExists($video->thumbnail_path);
        $video->delete();

        return redirect()->route('admin.videos.index')->with('success', 'Video berhasil dihapus.');
    }
}
