<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizQuestionOption;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class QuizBuilder
{
    /**
     * Auto generate a quiz from category materials.
     */
    public function autoGenerate(Category $category, int $count = 5, ?string $quizTitle = null): Quiz
    {
        $materials = $category->activeMaterials()->get();

        if ($materials->count() < $count) {
            throw ValidationException::withMessages([
                'count' => "Kategori '{$category->name}' hanya memiliki {$materials->count()} materi aktif. Tidak mencukupi untuk membuat {$count} soal.",
            ]);
        }

        if ($materials->count() < 3) {
            throw ValidationException::withMessages([
                'count' => 'Diperlukan minimal 3 materi aktif dalam kategori untuk membuat pilihan pengecoh.',
            ]);
        }

        return DB::transaction(function () use ($category, $materials, $count, $quizTitle) {
            $title = $quizTitle ?: "Kuis {$category->name} Ceria";

            $quiz = Quiz::create([
                'category_id' => $category->id,
                'title' => $title,
                'sort_order' => 0,
                'is_active' => true,
            ]);

            $shuffledMaterials = $materials->shuffle();

            for ($i = 0; $i < $count; $i++) {
                $correctMaterial = $shuffledMaterials[$i];

                $question = QuizQuestion::create([
                    'quiz_id' => $quiz->id,
                    'correct_material_id' => $correctMaterial->id,
                    'prompt_text' => "Mana yang merupakan {$correctMaterial->label}?",
                    'sort_order' => $i + 1,
                ]);

                // 2 distractors from other materials in category
                $distractors = $materials->where('id', '!=', $correctMaterial->id)
                    ->shuffle()
                    ->take(2);

                $optionMaterials = $distractors->push($correctMaterial)->shuffle();

                foreach ($optionMaterials as $optMaterial) {
                    QuizQuestionOption::create([
                        'question_id' => $question->id,
                        'material_id' => $optMaterial->id,
                    ]);
                }
            }

            return $quiz;
        });
    }
}
