<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Category;
use App\Models\Game;
use App\Models\GameSession;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceAndReportTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'guru@slb.test',
        ]);

        $this->student = User::factory()->create([
            'role' => 'siswa',
            'username' => 'siswa_ani',
            'name' => 'Ani Wijaya',
        ]);
    }

    public function test_student_can_attend_today_and_see_confirmation(): void
    {
        // 1. Visit /absen before attending
        $beforeRes = $this->actingAs($this->student)->get(route('student.attendance.index'));
        $beforeRes->assertStatus(200);
        $beforeRes->assertSee('Saya Hadir!');

        // 2. Submit attendance
        $postRes = $this->actingAs($this->student)->post(route('student.attendance.submit'));
        $postRes->assertRedirect(route('student.attendance.index'));

        // 3. Database check
        $this->assertDatabaseHas('attendances', [
            'user_id' => $this->student->id,
            'status' => 'hadir',
            'source' => 'siswa',
        ]);

        // 4. Visit /absen after attending
        $afterRes = $this->actingAs($this->student)->get(route('student.attendance.index'));
        $afterRes->assertStatus(200);
        $afterRes->assertSee('Kehadiranmu Tercatat!');

        // 5. Home page shows checkmark badge
        $homeRes = $this->actingAs($this->student)->get(route('student.home'));
        $homeRes->assertStatus(200);
        $homeRes->assertSee('Sudah Hadir');
    }

    public function test_attendance_submission_is_idempotent(): void
    {
        // First submission
        $this->actingAs($this->student)->post(route('student.attendance.submit'));
        // Second submission on same day
        $res = $this->actingAs($this->student)->post(route('student.attendance.submit'));

        $res->assertRedirect(route('student.attendance.index'));
        $this->assertCount(1, Attendance::where('user_id', $this->student->id)->get());
    }

    public function test_admin_can_view_attendances_and_filter_date(): void
    {
        Attendance::create([
            'user_id' => $this->student->id,
            'attendance_date' => now()->format('Y-m-d'),
            'status' => 'hadir',
            'checked_in_at' => now(),
            'source' => 'siswa',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.attendances.index', [
            'date' => now()->format('Y-m-d'),
        ]));

        $response->assertStatus(200);
        $response->assertSee('Ani Wijaya');
        $response->assertSee('Hadir');
    }

    public function test_admin_can_manually_update_student_attendance(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.attendances.update', $this->student), [
            'date' => now()->format('Y-m-d'),
            'status' => 'sakit',
            'note' => 'Izin via WhatsApp orang tua',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('attendances', [
            'user_id' => $this->student->id,
            'status' => 'sakit',
            'source' => 'admin',
            'note' => 'Izin via WhatsApp orang tua',
        ]);
    }

    public function test_admin_can_export_attendance_csv_with_utf8_bom(): void
    {
        Attendance::create([
            'user_id' => $this->student->id,
            'attendance_date' => now()->format('Y-m-d'),
            'status' => 'hadir',
            'checked_in_at' => now(),
            'source' => 'siswa',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.attendances.export', [
            'date' => now()->format('Y-m-d'),
        ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $content = $response->streamedContent();
        $this->assertStringStartsWith("\xEF\xBB\xBF", $content);
        $this->assertStringContainsString('Ani Wijaya', $content);
        $this->assertStringContainsString('Hadir', $content);
    }

    public function test_admin_can_view_student_report_summary(): void
    {
        $category = Category::create([
            'name' => 'Benda',
            'slug' => 'benda',
            'color' => '#10B981',
            'is_active' => true,
        ]);

        $quiz = Quiz::create([
            'category_id' => $category->id,
            'title' => 'Kuis Benda',
            'is_active' => true,
        ]);

        QuizAttempt::create([
            'user_id' => $this->student->id,
            'quiz_id' => $quiz->id,
            'total_questions' => 5,
            'correct_first_try' => 4,
            'finished_at' => now(),
        ]);

        $game = Game::create([
            'category_id' => $category->id,
            'title' => 'Permainan Benda',
            'game_type' => 'pilih_benar',
            'instruction_text' => 'Tebak benda yang benar',
            'is_active' => true,
        ]);

        GameSession::create([
            'user_id' => $this->student->id,
            'game_id' => $game->id,
            'rounds_total' => 5,
            'rounds_correct_first_try' => 5,
            'stars_earned' => 3,
            'duration_seconds' => 40,
            'completed_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.reports.index', [
            'student_id' => $this->student->id,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Kuis Benda');
        $response->assertSee('Permainan Benda');
        $response->assertSee('80%'); // Quiz avg
    }

    public function test_admin_can_view_printable_report(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.reports.print', $this->student));

        $response->assertStatus(200);
        $response->assertSee('SLB B-C WIJAYAKUSUMAH');
        $response->assertSee('Ani Wijaya');
        $response->assertSee('Laporan Hasil Belajar Siswa');
    }

    public function test_admin_can_export_grades_csv_with_utf8_bom(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.reports.export'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $content = $response->streamedContent();
        $this->assertStringStartsWith("\xEF\xBB\xBF", $content);
        $this->assertStringContainsString('Ani Wijaya', $content);
    }
}
