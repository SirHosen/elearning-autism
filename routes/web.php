<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isStudent()
            ? redirect()->route('student.home')
            : redirect()->route('admin.dashboard');
    }

    return redirect()->route('login');
});

// Autentikasi Pengguna (Tamu)
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'showRoleSelect'])->name('login');
    Route::get('/masuk/siswa', [AuthController::class, 'showStudentLogin'])->name('login.student');
    Route::post('/masuk/siswa', [AuthController::class, 'studentLogin'])->name('login.student.submit');
    Route::get('/masuk/guru', [AuthController::class, 'showTeacherLogin'])->name('login.teacher');
    Route::post('/masuk/guru', [AuthController::class, 'teacherLogin'])->name('login.teacher.submit');
});

Route::post('/keluar', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Area Siswa (Role: Siswa)
Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/beranda', function () {
        return view('student.home');
    })->name('student.home');

    Route::get('/pengaturan', function () {
        return view('student.settings');
    })->name('student.settings');
});

// Area Guru / Admin (Role: Admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/kategori', fn () => view('admin.dashboard'))->name('categories.index');
    Route::get('/materi', fn () => view('admin.dashboard'))->name('materials.index');
    Route::get('/video', fn () => view('admin.dashboard'))->name('videos.index');
    Route::get('/permainan', fn () => view('admin.dashboard'))->name('games.index');
    Route::get('/kuis', fn () => view('admin.dashboard'))->name('quizzes.index');
    Route::get('/siswa', fn () => view('admin.dashboard'))->name('students.index');
    Route::get('/absensi', fn () => view('admin.dashboard'))->name('attendances.index');
    Route::get('/hasil', fn () => view('admin.dashboard'))->name('reports.index');
    Route::get('/profil', fn () => view('admin.dashboard'))->name('profile');
});

if (app()->environment('local')) {
    Route::get('/dev/styleguide', fn () => view('dev.styleguide'));
}
