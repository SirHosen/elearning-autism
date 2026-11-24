<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function index(): View
    {
        $quizzes = Quiz::where('is_active', true)
            ->whereHas('questions')
            ->with('category')
            ->withCount('questions')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        return view('student.quiz.index', compact('quizzes'));
    }

    public function play(Quiz $quiz): View
    {
        if (! $quiz->is_active || $quiz->questions()->count() === 0) {
            abort(404);
        }

        return view('student.quiz.play', compact('quiz'));
    }

    public function startAttempt(Quiz $quiz): JsonResponse
    {
        if (! $quiz->is_active || $quiz->questions()->count() === 0) {
            return response()->json(['error' => 'Kuis tidak aktif atau belum memiliki soal.'], 404);
        }

        $questions = $quiz->questions()
            ->with(['correctMaterial', 'options.material'])
            ->orderBy('sort_order')
            ->get();

        $attempt = QuizAttempt::create([
            'user_id' => auth()->id(),
            'quiz_id' => $quiz->id,
            'total_questions' => $questions->count(),
            'correct_first_try' => 0,
            'started_at' => now(),
        ]);

        $formattedQuestions = $questions->map(function ($q, $index) {
            $shuffledOptions = $q->options->shuffle()->map(function ($opt) {
                return [
                    'material_id' => $opt->material_id,
                    'image_url' => asset('storage/'.$opt->material->image_path),
                ];
            })->values()->all();

            return [
                'id' => $q->id,
                'number' => $index + 1,
                'prompt_text' => $q->prompt_text,
                'options' => $shuffledOptions,
            ];
        });

        return response()->json([
            'success' => true,
            'attempt_id' => $attempt->id,
            'questions' => $formattedQuestions,
        ]);
    }

    public function submitAnswer(Request $request, QuizAttempt $attempt): JsonResponse
    {
        // Strict Security Policy: Student can only submit answers to their own attempt
        if ($attempt->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki izin mengakses percobaan kuis ini.');
        }

        $validated = $request->validate([
            'question_id' => 'required|exists:quiz_questions,id',
            'selected_material_id' => 'required|exists:materials,id',
            'is_first_try' => 'nullable|boolean',
        ]);

        $question = QuizQuestion::where('id', $validated['question_id'])
            ->where('quiz_id', $attempt->quiz_id)
            ->firstOrFail();

        $isCorrect = (int) $question->correct_material_id === (int) $validated['selected_material_id'];

        QuizAnswer::create([
            'attempt_id' => $attempt->id,
            'question_id' => $question->id,
            'selected_material_id' => $validated['selected_material_id'],
            'is_correct' => $isCorrect,
            'attempt_no' => $request->boolean('is_first_try', true) ? 1 : 2,
        ]);

        if ($isCorrect && $request->boolean('is_first_try', true)) {
            $attempt->increment('correct_first_try');
        }

        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
        ]);
    }

    public function finishAttempt(QuizAttempt $attempt): JsonResponse
    {
        // Strict Security Policy
        if ($attempt->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki izin menyelesaikan percobaan kuis ini.');
        }

        $attempt->update(['finished_at' => now()]);

        $stars = $attempt->calculateStars();
        $total = $attempt->total_questions;
        $correct = $attempt->correct_first_try;
        $percentage = $total > 0 ? round(($correct / $total) * 100) : 0;

        $feedback = match (true) {
            $percentage >= 80 => 'Luar Biasa!',
            $percentage >= 50 => 'Bagus Sekali!',
            default => 'Terus Berlatih, Kamu Hebat!',
        };

        return response()->json([
            'success' => true,
            'stars' => $stars,
            'percentage' => $percentage,
            'feedback' => $feedback,
            'correct_count' => $correct,
            'total_questions' => $total,
        ]);
    }
}
