<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Category;
use App\Models\Material;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = now()->format('Y-m-d');

        $activeStudents = User::where('role', 'siswa')
            ->where('is_active', true)
            ->get();

        $todayAttendances = Attendance::where('attendance_date', $today)->get();
        $attendedUserIds = $todayAttendances->pluck('user_id')->toArray();

        $todayAttendedCount = $todayAttendances->where('status', 'hadir')->count();
        $unattendedStudents = $activeStudents->whereNotIn('id', $attendedUserIds);

        $activeMaterialsCount = Material::where('is_active', true)->count();
        $categoriesCount = Category::where('is_active', true)->count();

        $quizzesLast7Days = QuizAttempt::whereNotNull('finished_at')
            ->where('started_at', '>=', now()->subDays(7))
            ->count();

        $recentQuizAttempts = QuizAttempt::with(['user', 'quiz'])
            ->whereNotNull('finished_at')
            ->latest('finished_at')
            ->take(10)
            ->get();

        return view('admin.dashboard', [
            'totalStudents' => $activeStudents->count(),
            'todayAttendedCount' => $todayAttendedCount,
            'unattendedCount' => $unattendedStudents->count(),
            'unattendedStudents' => $unattendedStudents,
            'activeMaterialsCount' => $activeMaterialsCount,
            'categoriesCount' => $categoriesCount,
            'quizzesLast7Days' => $quizzesLast7Days,
            'recentQuizAttempts' => $recentQuizAttempts,
        ]);
    }
}
