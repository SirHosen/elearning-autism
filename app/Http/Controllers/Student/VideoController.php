<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(): View
    {
        $videos = Video::active()->with('category')->get();

        return view('student.video.index', compact('videos'));
    }

    public function show(Video $video): View
    {
        if (! $video->is_active) {
            abort(404);
        }

        return view('student.video.show', compact('video'));
    }
}
