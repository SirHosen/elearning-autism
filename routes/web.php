<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GameController as AdminGameController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Student\GameController as StudentGameController;
use App\Http\Controllers\Student\HomeController;
use App\Http\Controllers\Student\LearnController;
use App\Http\Controllers\Student\SettingController;
use App\Http\Controllers\Student\VideoController as StudentVideoController;
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
Route::middleware(['auth', 'role:siswa'])->name('student.')->group(function () {
    Route::get('/beranda', [HomeController::class, 'index'])->name('home');

    // Modul Belajar
    Route::get('/belajar', [LearnController::class, 'index'])->name('learn.index');
    Route::get('/belajar/{category:slug}', [LearnController::class, 'showCategory'])->name('learn.category');
    Route::get('/belajar/{category:slug}/{material}', [LearnController::class, 'showMaterial'])->name('learn.material');

    // Pengaturan Aksesibilitas
    Route::get('/pengaturan', [SettingController::class, 'index'])->name('settings');
    Route::put('/pengaturan', [SettingController::class, 'update'])->name('settings.update');

    // Modul Permainan
    Route::get('/bermain', [StudentGameController::class, 'index'])->name('games.index');
    Route::get('/bermain/{game}', [StudentGameController::class, 'play'])->name('games.play');
    Route::get('/api/games/{game}/rounds', [StudentGameController::class, 'getRounds'])->name('games.rounds');
    Route::post('/api/games/{game}/sessions', [StudentGameController::class, 'storeSession'])->name('games.session');

    // Placeholder rute untuk fase 2.6 - 2.7
    Route::get('/kuis', fn () => redirect()->route('student.home'))->name('quizzes.index');
    Route::get('/kuis/{quiz}', fn () => redirect()->route('student.home'))->name('quizzes.play');
    // Modul Video
    Route::get('/video', [StudentVideoController::class, 'index'])->name('videos.index');
    Route::get('/video/{video}', [StudentVideoController::class, 'show'])->name('videos.show');
    Route::get('/absen', fn () => redirect()->route('student.home'))->name('attendance.index');
    Route::post('/absen', fn () => redirect()->route('student.home'))->name('attendance.submit');
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
    // CRUD Video
    Route::resource('video', AdminVideoController::class)
        ->names('videos')
        ->parameters(['video' => 'video']);
    Route::post('video/{video}/toggle', [AdminVideoController::class, 'toggleActive'])->name('videos.toggle');
    // CRUD Permainan
    Route::resource('permainan', AdminGameController::class)
        ->names('games')
        ->parameters(['permainan' => 'game']);
    Route::post('permainan/{game}/toggle', [AdminGameController::class, 'toggleActive'])->name('games.toggle');
    Route::get('/kuis', fn () => redirect()->route('admin.dashboard'))->name('quizzes.index');
    Route::get('/absensi', fn () => redirect()->route('admin.dashboard'))->name('attendances.index');
    Route::get('/hasil', fn () => redirect()->route('admin.dashboard'))->name('reports.index');
    Route::get('/profil', fn () => redirect()->route('admin.dashboard'))->name('profile');
});

if (app()->environment('local')) {
    Route::get('/dev/styleguide', fn () => view('dev.styleguide'));
}
