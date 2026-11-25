<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\GameSession;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
        $recentAttendances = collect();
        $classOverview = collect();
        $summary = [
            'total_quizzes' => 0,
            'total_games' => 0,
            'avg_quiz_score' => 0,
            'total_stars' => 0,
            'attendance_days' => 0,
        ];

        if ($request->filled('student_id')) {
            $selectedStudent = User::where('role', 'siswa')->find($request->student_id);

            if ($selectedStudent) {
                $data = $this->buildStudentReportData($selectedStudent);
                $quizAttempts = $data['quizAttempts'];
                $gameSessions = $data['gameSessions'];
                $recentAttendances = Attendance::where('user_id', $selectedStudent->id)
                    ->latest('attendance_date')
                    ->take(10)
                    ->get();
                $summary = $data['summary'];
            }
        }

        if (! $selectedStudent) {
            $classOverview = $students->map(function ($s) {
                $data = $this->buildStudentReportData($s);

                return [
                    'student' => $s,
                    'summary' => $data['summary'],
                ];
            });
        }

        return view('admin.reports.index', compact(
            'students',
            'selectedStudent',
            'quizAttempts',
            'gameSessions',
            'recentAttendances',
            'classOverview',
            'summary'
        ));
    }

    public function printReport(User $student): View
    {
        if ($student->role !== 'siswa') {
            abort(404);
        }

        $data = $this->buildStudentReportData($student, 15);
        $quizAttempts = $data['quizAttempts'];
        $gameSessions = $data['gameSessions'];
        $summary = $data['summary'];

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

            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['No', 'Nama Siswa', 'Username', 'Total Kuis', 'Rata-rata Skor Kuis (%)', 'Total Permainan', 'Total Bintang', 'Hari Hadir']);

            foreach ($students as $index => $s) {
                $summary = $this->buildStudentReportData($s)['summary'];

                fputcsv($handle, [
                    $index + 1,
                    $s->name,
                    $s->username,
                    $summary['total_quizzes'],
                    $summary['avg_quiz_score'].'%',
                    $summary['total_games'],
                    $summary['total_stars'],
                    $summary['attendance_days'],
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Menghitung ringkasan hasil belajar terpadu untuk satu siswa.
     *
     * @return array{quizAttempts: Collection, gameSessions: Collection, summary: array<string, int>}
     */
    private function buildStudentReportData(User $student, ?int $limit = null): array
    {
        $quizQuery = QuizAttempt::where('user_id', $student->id)
            ->with('quiz')
            ->latest();

        $gameQuery = GameSession::where('user_id', $student->id)
            ->with('game.category')
            ->latest();

        if ($limit !== null) {
            $quizQuery->take($limit);
            $gameQuery->take($limit);
        }

        $quizAttempts = $quizQuery->get();
        $gameSessions = $gameQuery->get();

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
            $avgScore = (int) round($totalPercentage / $quizAttempts->count());
        }

        return [
            'quizAttempts' => $quizAttempts,
            'gameSessions' => $gameSessions,
            'summary' => [
                'total_quizzes' => $quizAttempts->count(),
                'total_games' => $gameSessions->count(),
                'avg_quiz_score' => $avgScore,
                'total_stars' => (int) ($quizStars + $gameStars),
                'attendance_days' => $attendanceCount,
            ],
        ];
    }
}
