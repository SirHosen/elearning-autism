<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Category;
use App\Models\Game;
use App\Models\GameSession;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use App\Models\Video;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = now()->toDateString();

        $activeStudents = User::where('role', 'siswa')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $classCount = $activeStudents->pluck('class_name')->filter()->unique()->count();

        $todayAttendances = Attendance::whereDate('attendance_date', $today)->get();
        $attendedUserIds = $todayAttendances->pluck('user_id')->toArray();

        $todayAttendedCount = $todayAttendances->where('status', 'hadir')->count();
        $unattendedStudents = $activeStudents->whereNotIn('id', $attendedUserIds)->values();

        $todayBreakdown = [
            'hadir' => $todayAttendedCount,
            'izin' => $todayAttendances->where('status', 'izin')->count(),
            'sakit' => $todayAttendances->where('status', 'sakit')->count(),
            'alpa' => $todayAttendances->where('status', 'alpa')->count(),
            'belum' => $unattendedStudents->count(),
        ];

        $totalStudents = $activeStudents->count();
        $attendanceRate = $totalStudents > 0 ? (int) round(($todayAttendedCount / $totalStudents) * 100) : 0;

        $activeMaterialsCount = Material::where('is_active', true)->count();
        $categoriesCount = Category::where('is_active', true)->count();

        $contentTotals = [
            'categories' => Category::count(),
            'materials' => $activeMaterialsCount,
            'videos' => Video::where('is_active', true)->count(),
            'games' => Game::where('is_active', true)->count(),
            'quizzes' => Quiz::where('is_active', true)->count(),
        ];

        $curriculumMatrix = Category::withCount([
            'materials',
            'activeMaterials',
            'videos',
            'games',
            'quizzes',
        ])->orderBy('sort_order')->get();

        $since7Days = now()->subDays(6)->startOfDay();
        $recentAttempts7d = QuizAttempt::whereNotNull('finished_at')
            ->where('finished_at', '>=', $since7Days)
            ->get();
        $recentGames7d = GameSession::where('created_at', '>=', $since7Days)->get();
        $recentAtt7d = Attendance::where('attendance_date', '>=', now()->subDays(6)->toDateString())
            ->where('status', 'hadir')
            ->get();

        $quizzesLast7Days = $recentAttempts7d->count();
        $gamesLast7Days = $recentGames7d->count();

        $avgQuizScore7Days = 0;
        if ($recentAttempts7d->isNotEmpty()) {
            $totalPct = $recentAttempts7d->sum(function ($a) {
                return $a->total_questions > 0 ? ($a->correct_first_try / $a->total_questions) * 100 : 0;
            });
            $avgQuizScore7Days = (int) round($totalPct / $recentAttempts7d->count());
        }

        $weeklyTrend = [];
        $maxChartVal = 1;
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $dateStr = $day->toDateString();

            $dayAttended = $recentAtt7d->filter(function ($a) use ($dateStr) {
                $d = $a->attendance_date instanceof \DateTimeInterface
                    ? $a->attendance_date->format('Y-m-d')
                    : substr((string) $a->attendance_date, 0, 10);

                return $d === $dateStr;
            })->count();

            $dayQuizzes = $recentAttempts7d->filter(fn ($q) => $q->finished_at->toDateString() === $dateStr)->count();
            $dayGames = $recentGames7d->filter(fn ($g) => $g->created_at->toDateString() === $dateStr)->count();
            $dayActivities = $dayQuizzes + $dayGames;

            $maxChartVal = max($maxChartVal, $dayAttended, $dayActivities);

            $weeklyTrend[] = [
                'label' => $day->translatedFormat('D'),
                'date_short' => $day->format('d/m'),
                'attended' => $dayAttended,
                'activities' => $dayActivities,
            ];
        }

        $recentQuizAttempts = QuizAttempt::with(['user', 'quiz'])
            ->whereNotNull('finished_at')
            ->latest('finished_at')
            ->take(6)
            ->get();

        $recentGameSessions = GameSession::with(['user', 'game'])
            ->latest('created_at')
            ->take(6)
            ->get();

        $recentEvaluations = collect();

        foreach ($recentQuizAttempts as $attempt) {
            if (! $attempt->user) {
                continue;
            }
            $pct = $attempt->total_questions > 0
                ? round(($attempt->correct_first_try / $attempt->total_questions) * 100)
                : 0;

            $recentEvaluations->push([
                'type' => 'kuis',
                'student_id' => $attempt->user->id,
                'student_name' => $attempt->user->name,
                'student_class' => $attempt->user->class_name ?? 'Umum',
                'title' => $attempt->quiz?->title ?? 'Kuis',
                'score_label' => "{$attempt->correct_first_try}/{$attempt->total_questions} ({$pct}%)",
                'stars' => $attempt->calculateStars(),
                'timestamp' => $attempt->finished_at ?? $attempt->created_at,
            ]);
        }

        foreach ($recentGameSessions as $session) {
            if (! $session->user) {
                continue;
            }

            $recentEvaluations->push([
                'type' => 'permainan',
                'student_id' => $session->user->id,
                'student_name' => $session->user->name,
                'student_class' => $session->user->class_name ?? 'Umum',
                'title' => $session->game?->title ?? 'Permainan',
                'score_label' => "{$session->rounds_correct_first_try}/{$session->rounds_total} ronde",
                'stars' => $session->stars_earned,
                'timestamp' => $session->created_at,
            ]);
        }

        $recentEvaluations = $recentEvaluations->sortByDesc('timestamp')->take(8)->values();

        return view('admin.dashboard', [
            'todayFormatted' => now()->translatedFormat('l, d F Y'),
            'totalStudents' => $totalStudents,
            'classCount' => $classCount,
            'todayAttendedCount' => $todayAttendedCount,
            'attendanceRate' => $attendanceRate,
            'todayBreakdown' => $todayBreakdown,
            'unattendedCount' => $unattendedStudents->count(),
            'unattendedStudents' => $unattendedStudents,
            'activeMaterialsCount' => $activeMaterialsCount,
            'categoriesCount' => $categoriesCount,
            'contentTotals' => $contentTotals,
            'curriculumMatrix' => $curriculumMatrix,
            'quizzesLast7Days' => $quizzesLast7Days,
            'gamesLast7Days' => $gamesLast7Days,
            'avgQuizScore7Days' => $avgQuizScore7Days,
            'weeklyTrend' => $weeklyTrend,
            'maxChartVal' => $maxChartVal,
            'recentQuizAttempts' => $recentQuizAttempts,
            'recentEvaluations' => $recentEvaluations,
        ]);
    }
}
