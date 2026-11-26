<?php

use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GameController as AdminGameController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\QuizController as AdminQuizController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Student\AttendanceController as StudentAttendanceController;
use App\Http\Controllers\Student\GameController as StudentGameController;
use App\Http\Controllers\Student\HomeController;
use App\Http\Controllers\Student\LearnController;
use App\Http\Controllers\Student\QuizController as StudentQuizController;
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

    // Modul Kuis
    Route::get('/kuis', [StudentQuizController::class, 'index'])->name('quizzes.index');
    Route::get('/kuis/{quiz}', [StudentQuizController::class, 'play'])->name('quizzes.play');
    Route::post('/api/quizzes/{quiz}/attempts', [StudentQuizController::class, 'startAttempt'])->name('quizzes.start');
    Route::post('/api/quiz-attempts/{attempt}/answers', [StudentQuizController::class, 'submitAnswer'])->name('quizzes.answer');
    Route::post('/api/quiz-attempts/{attempt}/finish', [StudentQuizController::class, 'finishAttempt'])->name('quizzes.finish');
    // Modul Video
    Route::get('/video', [StudentVideoController::class, 'index'])->name('videos.index');
    Route::get('/video/{video}', [StudentVideoController::class, 'show'])->name('videos.show');
    // Presensi Kehadiran Siswa
    Route::get('/absen', [StudentAttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/absen', [StudentAttendanceController::class, 'submit'])->name('attendance.submit');
});

// Area Guru / Admin (Role: Admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Kategori
    Route::resource('kategori', CategoryController::class)
        ->names('categories')
        ->parameters(['kategori' => 'category'])
        ->except(['show']);

    // CRUD Materi
    Route::resource('materi', MaterialController::class)
        ->names('materials')
        ->parameters(['materi' => 'material'])
        ->except(['show']);

    // CRUD Siswa
    Route::resource('siswa', StudentController::class)
        ->names('students')
        ->parameters(['siswa' => 'student'])
        ->except(['show']);
    Route::post('siswa/{student}/reset-pin', [StudentController::class, 'resetPin'])->name('students.reset-pin');
    Route::get('siswa/{student}/kartu', [StudentController::class, 'printCard'])->name('students.card');

    // CRUD Video
    Route::resource('video', AdminVideoController::class)
        ->names('videos')
        ->parameters(['video' => 'video'])
        ->except(['show']);
    Route::post('video/{video}/toggle', [AdminVideoController::class, 'toggleActive'])->name('videos.toggle');

    // CRUD Permainan
    Route::resource('permainan', AdminGameController::class)
        ->names('games')
        ->parameters(['permainan' => 'game'])
        ->except(['show']);
    Route::post('permainan/{game}/toggle', [AdminGameController::class, 'toggleActive'])->name('games.toggle');

    // CRUD Kuis & Soal
    Route::resource('kuis', AdminQuizController::class)
        ->names('quizzes')
        ->parameters(['kuis' => 'quiz'])
        ->except(['show']);
    Route::post('kuis/{quiz}/toggle', [AdminQuizController::class, 'toggleActive'])->name('quizzes.toggle');
    Route::get('kuis/{quiz}/soal', [AdminQuizController::class, 'questions'])->name('quizzes.questions');
    Route::post('kuis/{quiz}/soal', [AdminQuizController::class, 'storeQuestion'])->name('quizzes.questions.store');
    Route::delete('kuis/soal/{question}', [AdminQuizController::class, 'destroyQuestion'])->name('quizzes.questions.destroy');
    Route::post('kuis/generator-otomatis', [AdminQuizController::class, 'autoGenerate'])->name('quizzes.auto-generate');
    // Rekap Absensi
    Route::get('/absensi', [AdminAttendanceController::class, 'index'])->name('attendances.index');
    Route::post('/absensi/{student}', [AdminAttendanceController::class, 'update'])->name('attendances.update');
    Route::get('/absensi-export', [AdminAttendanceController::class, 'exportCsv'])->name('attendances.export');

    // Laporan Hasil Belajar
    Route::get('/hasil', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/hasil-export', [AdminReportController::class, 'exportCsv'])->name('reports.export');
    Route::get('/hasil/{student}/cetak', [AdminReportController::class, 'printReport'])->name('reports.print');
    Route::get('/profil', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
});

if (app()->environment('local')) {
    Route::get('/dev/styleguide', fn () => view('dev.styleguide'));
}
