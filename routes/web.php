<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\StudentController;
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
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Kategori
    Route::resource('kategori', CategoryController::class)
        ->names('categories')
        ->parameters(['kategori' => 'category']);

    // CRUD Materi
    Route::resource('materi', MaterialController::class)
        ->names('materials')
        ->parameters(['materi' => 'material']);

    // CRUD Siswa
    Route::resource('siswa', StudentController::class)
        ->names('students')
        ->parameters(['siswa' => 'student']);
    Route::post('siswa/{student}/reset-pin', [StudentController::class, 'resetPin'])->name('students.reset-pin');
    Route::get('siswa/{student}/kartu', [StudentController::class, 'printCard'])->name('students.card');

    // Placeholder untuk modul berikutnya
    Route::get('/video', fn () => redirect()->route('admin.dashboard'))->name('videos.index');
    Route::get('/permainan', fn () => redirect()->route('admin.dashboard'))->name('games.index');
    Route::get('/kuis', fn () => redirect()->route('admin.dashboard'))->name('quizzes.index');
    Route::get('/absensi', fn () => redirect()->route('admin.dashboard'))->name('attendances.index');
    Route::get('/hasil', fn () => redirect()->route('admin.dashboard'))->name('reports.index');
    Route::get('/profil', fn () => redirect()->route('admin.dashboard'))->name('profile');
});

if (app()->environment('local')) {
    Route::get('/dev/styleguide', fn () => view('dev.styleguide'));
}
