<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\GameSession;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $students = User::where('role', 'siswa')->orderBy('name')->get();

        $selectedStudent = null;
        $quizAttempts = collect();
        $gameSessions = collect();
        $summary = [
            'total_quizzes' => 0,
            'avg_quiz_score' => 0,
            'total_stars' => 0,
            'attendance_days' => 0,
        ];

        if ($request->filled('student_id')) {
            $selectedStudent = User::where('role', 'siswa')->find($request->student_id);

            if ($selectedStudent) {
                $quizAttempts = QuizAttempt::where('user_id', $selectedStudent->id)
                    ->with('quiz')
                    ->latest()
                    ->get();

                $gameSessions = GameSession::where('user_id', $selectedStudent->id)
                    ->with('game.category')
                    ->latest()
                    ->get();

                $attendanceCount = Attendance::where('user_id', $selectedStudent->id)
                    ->where('status', 'hadir')
                    ->count();

                $quizStars = $quizAttempts->sum(fn ($a) => $a->calculateStars());
                $gameStars = $gameSessions->sum('stars_earned');

                $avgScore = 0;
                if ($quizAttempts->isNotEmpty()) {
                    $totalPercentage = $quizAttempts->sum(function ($a) {
                        return $a->total_questions > 0 ? ($a->correct_first_try / $a->total_questions) * 100 : 0;
                    });
                    $avgScore = round($totalPercentage / $quizAttempts->count());
                }

                $summary = [
                    'total_quizzes' => $quizAttempts->count(),
                    'avg_quiz_score' => $avgScore,
                    'total_stars' => $quizStars + $gameStars,
                    'attendance_days' => $attendanceCount,
                ];
            }
        }

        return view('admin.reports.index', compact('students', 'selectedStudent', 'quizAttempts', 'gameSessions', 'summary'));
    }

    public function printReport(User $student): View
    {
        if ($student->role !== 'siswa') {
            abort(404);
        }

        $quizAttempts = QuizAttempt::where('user_id', $student->id)
            ->with('quiz')
            ->latest()
            ->take(15)
            ->get();

        $gameSessions = GameSession::where('user_id', $student->id)
            ->with('game')
            ->latest()
            ->take(15)
            ->get();

        $attendanceCount = Attendance::where('user_id', $student->id)
            ->where('status', 'hadir')
            ->count();

        $quizStars = $quizAttempts->sum(fn ($a) => $a->calculateStars());
        $gameStars = $gameSessions->sum('stars_earned');

        $avgScore = 0;
        if ($quizAttempts->isNotEmpty()) {
            $totalPercentage = $quizAttempts->sum(function ($a) {
                return $a->total_questions > 0 ? ($a->correct_first_try / $a->total_questions) * 100 : 0;
            });
            $avgScore = round($totalPercentage / $quizAttempts->count());
        }

        $summary = [
            'total_quizzes' => $quizAttempts->count(),
            'avg_quiz_score' => $avgScore,
            'total_stars' => $quizStars + $gameStars,
            'attendance_days' => $attendanceCount,
        ];

        return view('admin.reports.print', compact('student', 'quizAttempts', 'gameSessions', 'summary'));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $students = User::where('role', 'siswa')->orderBy('name')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="rekap-nilai-siswa-'.now()->format('Y-m-d').'.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($students) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['No', 'Nama Siswa', 'Username', 'Total Kuis Selesai', 'Rata-rata Skor Kuis (%)', 'Total Bintang Terkumpul', 'Hari Hadir']);

            foreach ($students as $index => $student) {
                $attempts = QuizAttempt::where('user_id', $student->id)->get();
                $sessions = GameSession::where('user_id', $student->id)->get();
                $attendanceCount = Attendance::where('user_id', $student->id)->where('status', 'hadir')->count();

                $quizStars = $attempts->sum(fn ($a) => $a->calculateStars());
                $gameStars = $sessions->sum('stars_earned');

                $avg = 0;
                if ($attempts->isNotEmpty()) {
                    $totalScore = $attempts->sum(fn ($a) => $a->total_questions > 0 ? ($a->correct_first_try / $a->total_questions) * 100 : 0);
                    $avg = round($totalScore / $attempts->count());
                }

                fputcsv($handle, [
                    $index + 1,
                    $student->name,
                    $student->username,
                    $attempts->count(),
                    $avg.'%',
                    $quizStars + $gameStars,
                    $attendanceCount,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
