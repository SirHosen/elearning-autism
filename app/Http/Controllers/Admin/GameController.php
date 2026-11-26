<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Game;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class GameController extends Controller
{
    public function index(Request $request): View
    {
        $query = Game::with('category')->withCount('sessions')->latest();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('game_type')) {
            $query->where('game_type', $request->game_type);
        }

        $games = $query->paginate(12)->withQueryString();
        $categories = Category::active()->orderBy('name')->get();

        return view('admin.games.index', compact('games', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::active()->withCount('activeMaterials')->orderBy('name')->get();

        return view('admin.games.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'game_type' => 'required|in:pilih_benar,cocokkan,seret_tempatkan',
            'option_count' => 'required|integer|min:2|max:4',
            'round_count' => 'required|integer|min:1|max:10',
            'instruction_text' => 'nullable|string|max:60',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ], [
            'title.required' => 'Judul permainan wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'game_type.required' => 'Jenis permainan wajib dipilih.',
            'option_count.required' => 'Jumlah opsi per ronde wajib diisi.',
            'round_count.required' => 'Jumlah ronde wajib diisi.',
        ]);

        $category = Category::findOrFail($validated['category_id']);
        $activeMaterialsCount = $category->activeMaterials()->count();
        $isActive = $request->has('is_active') ? $request->boolean('is_active') : true;

        if ($isActive) {
            $this->validateMinimumMaterials($category, $validated['game_type'], (int) $validated['option_count'], (int) $validated['round_count'], $activeMaterialsCount);
        }

        Game::create([
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'game_type' => $validated['game_type'],
            'option_count' => $validated['option_count'],
            'round_count' => $validated['round_count'],
            'instruction_text' => ! empty($validated['instruction_text']) ? $validated['instruction_text'] : $this->defaultInstruction($validated['game_type']),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $isActive,
        ]);

        return redirect()->route('admin.games.index')->with('success', 'Permainan berhasil ditambahkan.');
    }

    public function edit(Game $game): View
    {
        $categories = Category::where('is_active', true)
            ->orWhere('id', $game->category_id)
            ->withCount('activeMaterials')
            ->orderBy('name')
            ->get();

        return view('admin.games.edit', compact('game', 'categories'));
    }

    public function update(Request $request, Game $game): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'game_type' => 'required|in:pilih_benar,cocokkan,seret_tempatkan',
            'option_count' => 'required|integer|min:2|max:4',
            'round_count' => 'required|integer|min:1|max:10',
            'instruction_text' => 'nullable|string|max:60',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ], [
            'title.required' => 'Judul permainan wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'game_type.required' => 'Jenis permainan wajib dipilih.',
        ]);

        $category = Category::findOrFail($validated['category_id']);
        $activeMaterialsCount = $category->activeMaterials()->count();
        $isActive = $request->has('is_active') ? $request->boolean('is_active') : $game->is_active;

        if ($isActive) {
            $this->validateMinimumMaterials($category, $validated['game_type'], (int) $validated['option_count'], (int) $validated['round_count'], $activeMaterialsCount);
        }

        $game->update([
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'game_type' => $validated['game_type'],
            'option_count' => $validated['option_count'],
            'round_count' => $validated['round_count'],
            'instruction_text' => ! empty($validated['instruction_text']) ? $validated['instruction_text'] : $this->defaultInstruction($validated['game_type']),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $isActive,
        ]);

        return redirect()->route('admin.games.index')->with('success', 'Permainan berhasil diperbarui.');
    }

    public function toggleActive(Game $game): RedirectResponse
    {
        if (! $game->is_active && ! $game->hasEnoughMaterials()) {
            $activeCount = $game->category->activeMaterials()->count();

            return back()->with('error', "Permainan tidak dapat diaktifkan karena kategori '{$game->category->name}' hanya memiliki {$activeCount} materi aktif.");
        }

        $game->update(['is_active' => ! $game->is_active]);
        $statusText = $game->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Permainan '{$game->title}' berhasil {$statusText}.");
    }

    public function destroy(Game $game): RedirectResponse
    {
        $game->delete();

        return redirect()->route('admin.games.index')->with('success', 'Permainan berhasil dihapus.');
    }

    private function validateMinimumMaterials(Category $category, string $gameType, int $optionCount, int $roundCount, int $activeCount): void
    {
        $needed = match ($gameType) {
            'pilih_benar', 'seret_tempatkan' => $optionCount,
            'cocokkan' => $roundCount,
            default => 2,
        };

        if ($activeCount < $needed) {
            throw ValidationException::withMessages([
                'is_active' => "Kategori '{$category->name}' hanya memiliki {$activeCount} materi aktif. Diperlukan minimal {$needed} materi aktif untuk mengaktifkan permainan ini.",
            ]);
        }
    }

    private function defaultInstruction(string $gameType): string
    {
        return match ($gameType) {
            'pilih_benar' => 'Pilih gambar yang benar',
            'cocokkan' => 'Cocokkan gambar dengan namanya',
            'seret_tempatkan' => 'Tempatkan gambar pada kotak yang tepat',
            default => 'Selesaikan permainan',
        };
    }
}
