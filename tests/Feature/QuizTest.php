<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\User;
use App\Services\QuizBuilder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $student1;

    private User $student2;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@slb.test',
        ]);

        $this->student1 = User::factory()->create([
            'role' => 'siswa',
            'username' => 'siswa_satu',
        ]);

        $this->student2 = User::factory()->create([
            'role' => 'siswa',
            'username' => 'siswa_dua',
        ]);

        $this->category = Category::create([
            'name' => 'Warna',
            'slug' => 'warna',
            'color' => '#E056FD',
            'is_active' => true,
        ]);

        for ($i = 1; $i <= 6; $i++) {
            Material::create([
                'category_id' => $this->category->id,
                'label' => "Warna {$i}",
                'image_path' => "materials/warna_{$i}.svg",
                'is_active' => true,
            ]);
        }
    }

    public function test_auto_generator_creates_quiz_with_questions_and_options(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.quizzes.auto-generate'), [
            'category_id' => $this->category->id,
            'question_count' => 5,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('quizzes', [
            'category_id' => $this->category->id,
            'title' => 'Kuis Warna Ceria',
        ]);

        $quiz = Quiz::where('category_id', $this->category->id)->first();
        $this->assertCount(5, $quiz->questions);

        foreach ($quiz->questions as $q) {
            $this->assertCount(3, $q->options);
            $this->assertTrue($q->options->pluck('material_id')->contains($q->correct_material_id));
        }
    }

    public function test_auto_generator_fails_if_category_lacks_materials(): void
    {
        $smallCat = Category::create([
            'name' => 'Sedikit',
            'slug' => 'sedikit',
            'color' => '#CCCCCC',
            'is_active' => true,
        ]);

        Material::create([
            'category_id' => $smallCat->id,
            'label' => 'Satu',
            'image_path' => 'materials/satu.svg',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.quizzes.auto-generate'), [
            'category_id' => $smallCat->id,
            'question_count' => 5,
        ]);

        $response->assertSessionHasErrors('count');
    }

    public function test_student_can_see_active_quiz_list(): void
    {
        $quizBuilder = app(QuizBuilder::class);
        $activeQuiz = $quizBuilder->autoGenerate($this->category, 3, 'Kuis Aktif');

        $inactiveQuiz = Quiz::create([
            'category_id' => $this->category->id,
            'title' => 'Kuis Nonaktif',
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->student1)->get(route('student.quizzes.index'));

        $response->assertStatus(200);
        $response->assertSee('Kuis Aktif');
        $response->assertDontSee('Kuis Nonaktif');
    }

    public function test_student_can_start_quiz_and_fetch_visual_options(): void
    {
        $quizBuilder = app(QuizBuilder::class);
        $quiz = $quizBuilder->autoGenerate($this->category, 3);

        $response = $this->actingAs($this->student1)->postJson(route('student.quizzes.start', $quiz));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'attempt_id',
            'questions' => [
                '*' => [
                    'id',
                    'number',
                    'prompt_text',
                    'options' => [
                        '*' => [
                            'material_id',
                            'image_url',
                        ],
                    ],
                ],
            ],
        ]);

        $attemptId = $response->json('attempt_id');
        $this->assertDatabaseHas('quiz_attempts', [
            'id' => $attemptId,
            'user_id' => $this->student1->id,
            'quiz_id' => $quiz->id,
        ]);
    }

    public function test_student_cannot_play_inactive_quiz(): void
    {
        $quiz = Quiz::create([
            'category_id' => $this->category->id,
            'title' => 'Kuis Nonaktif',
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->student1)->get(route('student.quizzes.play', $quiz));

        $response->assertStatus(404);
    }

    public function test_student_can_submit_answers_and_finish_with_positive_feedback(): void
    {
        $quizBuilder = app(QuizBuilder::class);
        $quiz = $quizBuilder->autoGenerate($this->category, 3);

        $startRes = $this->actingAs($this->student1)->postJson(route('student.quizzes.start', $quiz));
        $attemptId = $startRes->json('attempt_id');
        $questions = $startRes->json('questions');

        // Submit answer for each question
        foreach ($questions as $q) {
            $questionModel = QuizQuestion::find($q['id']);

            $ansRes = $this->actingAs($this->student1)->postJson(route('student.quizzes.answer', $attemptId), [
                'question_id' => $q['id'],
                'selected_material_id' => $questionModel->correct_material_id,
                'is_first_try' => true,
            ]);
            $ansRes->assertStatus(200);
            $ansRes->assertJson(['success' => true, 'is_correct' => true]);
        }

        // Finish
        $finishRes = $this->actingAs($this->student1)->postJson(route('student.quizzes.finish', $attemptId));
        $finishRes->assertStatus(200);
        $finishRes->assertJson([
            'success' => true,
            'stars' => 3,
            'percentage' => 100,
            'feedback' => 'Luar Biasa!',
        ]);

        $attempt = QuizAttempt::find($attemptId);
        $this->assertNotNull($attempt->finished_at);
        $this->assertEquals(3, $attempt->correct_first_try);
    }

    public function test_security_policy_blocks_access_to_another_students_attempt(): void
    {
        $quizBuilder = app(QuizBuilder::class);
        $quiz = $quizBuilder->autoGenerate($this->category, 3);

        // Student 1 starts attempt
        $startRes = $this->actingAs($this->student1)->postJson(route('student.quizzes.start', $quiz));
        $attemptId = $startRes->json('attempt_id');
        $questionId = $startRes->json('questions.0.id');

        // Student 2 tries to submit answer to Student 1's attempt
        $fakeAnswerRes = $this->actingAs($this->student2)->postJson(route('student.quizzes.answer', $attemptId), [
            'question_id' => $questionId,
            'selected_material_id' => 1,
        ]);
        $fakeAnswerRes->assertStatus(403);

        // Student 2 tries to finish Student 1's attempt
        $fakeFinishRes = $this->actingAs($this->student2)->postJson(route('student.quizzes.finish', $attemptId));
        $fakeFinishRes->assertStatus(403);
    }

    public function test_admin_can_manage_quiz_and_manual_questions(): void
    {
        // 1. Create quiz
        $createRes = $this->actingAs($this->admin)->post(route('admin.quizzes.store'), [
            'title' => 'Kuis Manual',
            'category_id' => $this->category->id,
            'is_active' => 1,
        ]);
        $quiz = Quiz::where('title', 'Kuis Manual')->first();
        $createRes->assertRedirect(route('admin.quizzes.questions', $quiz));

        // 2. Add question
        $materials = $this->category->materials()->take(3)->get();
        $questionRes = $this->actingAs($this->admin)->post(route('admin.quizzes.questions.store', $quiz), [
            'prompt_text' => 'Mana warna merah?',
            'correct_material_id' => $materials[0]->id,
            'option_ids' => $materials->pluck('id')->all(),
        ]);
        $questionRes->assertRedirect();
        $this->assertCount(1, $quiz->fresh()->questions);

        // 3. Delete question
        $question = $quiz->questions()->first();
        $delQRes = $this->actingAs($this->admin)->delete(route('admin.quizzes.questions.destroy', $question));
        $delQRes->assertRedirect();
        $this->assertCount(0, $quiz->fresh()->questions);

        // 4. Toggle quiz
        $toggleRes = $this->actingAs($this->admin)->post(route('admin.quizzes.toggle', $quiz));
        $toggleRes->assertRedirect();
        $this->assertFalse($quiz->fresh()->is_active);

        // 5. Delete quiz
        $delRes = $this->actingAs($this->admin)->delete(route('admin.quizzes.destroy', $quiz));
        $delRes->assertRedirect(route('admin.quizzes.index'));
        $this->assertDatabaseMissing('quizzes', ['id' => $quiz->id]);
    }
}
