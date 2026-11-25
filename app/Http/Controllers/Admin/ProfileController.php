<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        $teacher = auth()->user();

        $summary = [
            'students' => User::where('role', 'siswa')->where('is_active', true)->count(),
            'categories' => Category::count(),
            'materials' => Material::count(),
            'quizzes' => Quiz::count(),
        ];

        return view('admin.profile', compact('teacher', 'summary'));
    }

    public function update(Request $request): RedirectResponse
    {
        $teacher = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                'max:120',
                Rule::unique('users', 'email')->ignore($teacher->id),
            ],
            'current_password' => 'nullable|required_with:password|current_password',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama pendidik wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan akun lain.',
            'current_password.required_with' => 'Kata sandi saat ini wajib diisi untuk mengganti sandi.',
            'current_password.current_password' => 'Kata sandi saat ini tidak cocok.',
            'password.min' => 'Kata sandi baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak sama.',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $teacher->update($data);

        return redirect()->route('admin.profile')
            ->with('success', 'Profil pendidik berhasil diperbarui.');
    }
}
