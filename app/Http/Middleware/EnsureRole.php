<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->role !== $role) {
            if ($user->isStudent()) {
                return response()->view('errors.403', [
                    'message' => 'Halaman ini khusus untuk Bapak/Ibu Guru.',
                ], 403);
            }

            abort(403, 'Akses tidak diizinkan untuk peran ini.');
        }

        if (! $user->is_active) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'auth' => 'Akun belum aktif atau telah dinonaktifkan. Silakan hubungi guru ya.',
            ]);
        }

        return $next($request);
    }
}
