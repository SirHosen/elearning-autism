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
            $summariesByStudent = $this->buildBulkStudentSummaries($students);
            $classOverview = $students->map(fn ($s) => [
                'student' => $s,
                'summary' => $summariesByStudent[$s->id],
            ]);
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
            $summariesByStudent = $this->buildBulkStudentSummaries($students);

            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['No', 'Nama Siswa', 'Username', 'Total Kuis', 'Rata-rata Skor Kuis (%)', 'Total Permainan', 'Total Bintang', 'Hari Hadir']);

            foreach ($students as $index => $s) {
                $summary = $summariesByStudent[$s->id];

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
     * Menghitung ringkasan hasil belajar seluruh siswa sekaligus secara batch (bebas N+1).
     *
     * @param  Collection<int, User>  $students
     * @return array<int, array<string, int>>
     */
    private function buildBulkStudentSummaries(Collection $students): array
    {
        $studentIds = $students->pluck('id')->all();
        if (empty($studentIds)) {
            return [];
        }

        $attemptsByUser = QuizAttempt::whereIn('user_id', $studentIds)
            ->select(['user_id', 'total_questions', 'correct_first_try'])
            ->get()
            ->groupBy('user_id');

        $gamesByUser = GameSession::whereIn('user_id', $studentIds)
            ->selectRaw('user_id, COUNT(*) as total_games, COALESCE(SUM(stars_earned), 0) as total_stars')
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        $attendanceByUser = Attendance::whereIn('user_id', $studentIds)
            ->where('status', 'hadir')
            ->selectRaw('user_id, COUNT(*) as attendance_days')
            ->groupBy('user_id')
            ->pluck('attendance_days', 'user_id');

        $summaries = [];
        foreach ($students as $student) {
            $attempts = $attemptsByUser->get($student->id, collect());
            $gameStats = $gamesByUser->get($student->id);

            $quizStars = $attempts->sum(fn ($a) => $a->calculateStars());
            $gameStars = (int) ($gameStats?->total_stars ?? 0);

            $avgScore = 0;
            if ($attempts->isNotEmpty()) {
                $totalPercentage = $attempts->sum(function ($a) {
                    return $a->total_questions > 0 ? ($a->correct_first_try / $a->total_questions) * 100 : 0;
                });
                $avgScore = (int) round($totalPercentage / $attempts->count());
            }

            $summaries[$student->id] = [
                'total_quizzes' => $attempts->count(),
                'total_games' => (int) ($gameStats?->total_games ?? 0),
                'avg_quiz_score' => $avgScore,
                'total_stars' => (int) ($quizStars + $gameStars),
                'attendance_days' => (int) ($attendanceByUser->get($student->id) ?? 0),
            ];
        }

        return $summaries;
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
