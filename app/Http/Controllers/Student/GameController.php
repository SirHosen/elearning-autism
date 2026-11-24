<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameSession;
use App\Services\GameRoundBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GameController extends Controller
{
    public function __construct(
        private readonly GameRoundBuilder $roundBuilder
    ) {}

    public function index(): View
    {
        $games = Game::where('is_active', true)
            ->with('category')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        return view('student.game.index', compact('games'));
    }

    public function play(Game $game): View
    {
        if (! $game->is_active) {
            abort(404);
        }

        return view('student.game.play', compact('game'));
    }

    public function getRounds(Game $game): JsonResponse
    {
        if (! $game->is_active) {
            return response()->json(['error' => 'Permainan tidak aktif.'], 404);
        }

        $rounds = $this->roundBuilder->build($game);

        return response()->json([
            'success' => true,
            'rounds' => $rounds,
        ]);
    }

    public function storeSession(Request $request, Game $game): JsonResponse
    {
        $validated = $request->validate([
            'total_rounds' => 'required|integer|min:1',
            'correct_first_try' => 'required|integer|min:0',
            'duration_seconds' => 'nullable|integer|min:0',
        ]);

        $stars = GameSession::calculateStars($validated['total_rounds'], $validated['correct_first_try']);

        $session = GameSession::create([
            'user_id' => auth()->id(),
            'game_id' => $game->id,
            'rounds_total' => $validated['total_rounds'],
            'rounds_correct_first_try' => $validated['correct_first_try'],
            'stars_earned' => $stars,
            'duration_seconds' => (int) ($validated['duration_seconds'] ?? 0),
            'completed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'session_id' => $session->id,
            'stars_earned' => $stars,
        ]);
    }
}
