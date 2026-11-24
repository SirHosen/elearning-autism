<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizQuestionOption;
use App\Services\QuizBuilder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function __construct(
        private readonly QuizBuilder $quizBuilder
    ) {}

    public function index(Request $request): View
    {
        $query = Quiz::with('category')->withCount(['questions', 'attempts'])->latest();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $quizzes = $query->paginate(12)->withQueryString();
        $categories = Category::active()->withCount('activeMaterials')->orderBy('name')->get();

        return view('admin.quizzes.index', compact('quizzes', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::active()->withCount('activeMaterials')->orderBy('name')->get();

        return view('admin.quizzes.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ], [
            'title.required' => 'Judul kuis wajib diisi.',
            'category_id.required' => 'Kategori kuis wajib dipilih.',
        ]);

        $quiz = Quiz::create([
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => (bool) $request->input('is_active', 1),
        ]);

        return redirect()->route('admin.quizzes.questions', $quiz)->with('success', 'Kuis berhasil dibuat. Silakan tambahkan soal atau gunakan generator otomatis.');
    }

    public function edit(Quiz $quiz): View
    {
        $categories = Category::active()->withCount('activeMaterials')->orderBy('name')->get();

        return view('admin.quizzes.edit', compact('quiz', 'categories'));
    }

    public function update(Request $request, Quiz $quiz): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $quiz->update([
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => (bool) $request->input('is_active', 1),
        ]);

        return redirect()->route('admin.quizzes.index')->with('success', 'Kuis berhasil diperbarui.');
    }

    public function toggleActive(Quiz $quiz): RedirectResponse
    {
        $quiz->update(['is_active' => ! $quiz->is_active]);
        $statusText = $quiz->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Kuis '{$quiz->title}' berhasil {$statusText}.");
    }

    public function destroy(Quiz $quiz): RedirectResponse
    {
        $quiz->delete();

        return redirect()->route('admin.quizzes.index')->with('success', 'Kuis berhasil dihapus.');
    }

    public function questions(Quiz $quiz): View
    {
        $quiz->load(['category.materials' => function ($q) {
            $q->where('is_active', true)->orderBy('label');
        }, 'questions.correctMaterial', 'questions.options.material']);

        return view('admin.quizzes.questions', compact('quiz'));
    }

    public function storeQuestion(Request $request, Quiz $quiz): RedirectResponse
    {
        $validated = $request->validate([
            'prompt_text' => 'required|string|max:150',
            'correct_material_id' => 'required|exists:materials,id',
            'option_ids' => 'required|array|min:2|max:4',
            'option_ids.*' => 'exists:materials,id',
        ], [
            'prompt_text.required' => 'Pertanyaan kuis wajib diisi.',
            'correct_material_id.required' => 'Materi jawaban benar wajib dipilih.',
            'option_ids.required' => 'Pilihan materi wajib dipilih minimal 2.',
            'option_ids.min' => 'Pilihan materi minimal 2 gambar.',
        ]);

        if (! in_array($validated['correct_material_id'], $validated['option_ids'])) {
            throw ValidationException::withMessages([
                'option_ids' => 'Jawaban benar harus termasuk dalam opsi pilihan.',
            ]);
        }

        DB::transaction(function () use ($quiz, $validated) {
            $sortOrder = $quiz->questions()->count() + 1;

            $question = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'correct_material_id' => $validated['correct_material_id'],
                'prompt_text' => $validated['prompt_text'],
                'sort_order' => $sortOrder,
            ]);

            foreach ($validated['option_ids'] as $matId) {
                QuizQuestionOption::create([
                    'question_id' => $question->id,
                    'material_id' => $matId,
                ]);
            }
        });

        return back()->with('success', 'Soal kuis berhasil ditambahkan.');
    }

    public function destroyQuestion(QuizQuestion $question): RedirectResponse
    {
        $quiz = $question->quiz;
        $question->delete();

        return back()->with('success', 'Soal berhasil dihapus.');
    }

    public function autoGenerate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'question_count' => 'required|integer|min:2|max:20',
        ]);

        $category = Category::findOrFail($validated['category_id']);
        $count = (int) $validated['question_count'];

        $quiz = $this->quizBuilder->autoGenerate($category, $count);

        return redirect()->route('admin.quizzes.questions', $quiz)->with('success', "Kuis otomatis berhasil dibuat dengan {$count} soal dari kategori {$category->name}.");
    }
}
