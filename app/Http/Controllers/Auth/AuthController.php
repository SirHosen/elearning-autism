<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showRoleSelect(): View|RedirectResponse
    {
        if (Auth::check()) {
            return Auth::user()->isStudent()
                ? redirect()->route('student.home')
                : redirect()->route('admin.dashboard');
        }

        return view('auth.role-select');
    }

    public function showStudentLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return Auth::user()->isStudent()
                ? redirect()->route('student.home')
                : redirect()->route('admin.dashboard');
        }

        return view('auth.login-student');
    }

    public function studentLogin(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => 'required|string',
            'pin' => 'required|string|min:4|max:6',
        ], [
            'username.required' => 'Nama pengguna wajib diisi.',
            'pin.required' => 'PIN wajib diisi.',
            'pin.min' => 'PIN minimal 4 angka.',
            'pin.max' => 'PIN maksimal 6 angka.',
        ]);

        $throttleKey = Str::lower($request->input('username')).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withInput()->withErrors([
                'auth' => __('ui.throttle_wait').' ('.$seconds.' detik)',
            ]);
        }

        $user = User::where('username', $request->input('username'))
            ->where('role', 'siswa')
            ->first();

        if (! $user || ! Hash::check($request->input('pin'), $user->password)) {
            RateLimiter::hit($throttleKey, 60);

            return back()->withInput()->withErrors([
                'auth' => __('ui.login_failed'),
            ]);
        }

        if (! $user->is_active) {
            RateLimiter::hit($throttleKey, 60);

            return back()->withInput()->withErrors([
                'auth' => 'Akun kamu belum aktif. Minta bantuan guru ya.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        Auth::login($user);
        $user->update(['last_login_at' => now()]);
        $request->session()->regenerate();

        return redirect()->intended(route('student.home'));
    }

    public function showTeacherLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return Auth::user()->isStudent()
                ? redirect()->route('student.home')
                : redirect()->route('admin.dashboard');
        }

        return view('auth.login-teacher');
    }

    public function teacherLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email belum benar.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $throttleKey = Str::lower($request->input('email')).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withInput()->withErrors([
                'auth' => 'Terlalu banyak percobaan masuk. Tunggu '.$seconds.' detik lalu coba lagi.',
            ]);
        }

        $user = User::where('email', $request->input('email'))
            ->where('role', 'admin')
            ->first();

        if (! $user || ! Hash::check($request->input('password'), $user->password) || ! $user->is_active) {
            RateLimiter::hit($throttleKey, 60);

            return back()->withInput()->withErrors([
                'auth' => 'Email atau kata sandi guru belum sesuai.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        Auth::login($user, $request->boolean('remember'));
        $user->update(['last_login_at' => now()]);
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
