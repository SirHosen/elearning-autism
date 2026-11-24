<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $setting = $user->setting ?: $user->setting()->create([
            'volume' => 70,
            'animation_level' => 'minimal',
            'font_scale' => 'normal',
            'theme' => 'calm',
            'sfx_enabled' => false,
        ]);

        return view('student.settings', compact('user', 'setting'));
    }

    public function update(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'volume' => ['required', 'integer', 'min:0', 'max:100'],
            'animation_level' => ['required', 'in:off,minimal,normal'],
            'font_scale' => ['required', 'in:normal,large,xlarge'],
            'theme' => ['required', 'in:calm,contrast'],
            'sfx_enabled' => ['nullable', 'boolean'],
        ]);

        $user = auth()->user();
        $setting = $user->setting()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'volume' => $validated['volume'],
                'animation_level' => $validated['animation_level'],
                'font_scale' => $validated['font_scale'],
                'theme' => $validated['theme'],
                'sfx_enabled' => $request->boolean('sfx_enabled', false),
            ]
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengaturan berhasil disimpan.',
                'settings' => $setting,
            ]);
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
