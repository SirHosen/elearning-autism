<?php

namespace App\Services;

use App\Models\Game;
use Illuminate\Support\Collection;

class GameRoundBuilder
{
    /**
     * Build rounds structure for the given game.
     */
    public function build(Game $game): array
    {
        $materials = $game->category?->activeMaterials()->get() ?? collect();

        if ($materials->isEmpty()) {
            return [];
        }

        return match ($game->game_type) {
            'pilih_benar' => $this->buildPilihBenar($game, $materials),
            'cocokkan' => $this->buildCocokkan($game, $materials),
            'seret_tempatkan' => $this->buildSeretTempatkan($game, $materials),
            default => [],
        };
    }

    private function buildPilihBenar(Game $game, Collection $materials): array
    {
        $roundCount = $game->round_count ?: 5;
        $optionCount = $game->option_count ?: 3;
        $shuffled = $materials->shuffle();

        $rounds = [];
        for ($i = 0; $i < $roundCount; $i++) {
            $target = $shuffled[$i % $shuffled->count()];
            $distractors = $materials->where('id', '!=', $target->id)->shuffle()->take($optionCount - 1);
            $options = $distractors->push($target)->shuffle()->map(function ($item) use ($target) {
                return [
                    'id' => $item->id,
                    'label' => $item->label,
                    'image_url' => '/storage/'.$item->image_path,
                    'audio_url' => $item->audio_path ? '/storage/'.$item->audio_path : null,
                    'is_correct' => $item->id === $target->id,
                ];
            })->values()->all();

            $rounds[] = [
                'round_number' => $i + 1,
                'target_id' => $target->id,
                'target_label' => $target->label,
                'instruction' => $game->instruction_text ?: "Pilih {$target->label}",
                'options' => $options,
            ];
        }

        return $rounds;
    }

    private function buildCocokkan(Game $game, Collection $materials): array
    {
        $roundCount = $game->round_count ?: 5;
        $pairsPerRound = min(3, $materials->count());
        $rounds = [];

        for ($i = 0; $i < $roundCount; $i++) {
            $selected = $materials->shuffle()->take($pairsPerRound);

            $leftItems = $selected->shuffle()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'label' => $item->label,
                    'image_url' => '/storage/'.$item->image_path,
                ];
            })->values()->all();

            $rightItems = $selected->shuffle()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'label' => $item->label,
                ];
            })->values()->all();

            $rounds[] = [
                'round_number' => $i + 1,
                'total_pairs' => $pairsPerRound,
                'instruction' => $game->instruction_text ?: 'Cocokkan gambar dengan namanya',
                'left_items' => $leftItems,
                'right_items' => $rightItems,
            ];
        }

        return $rounds;
    }

    private function buildSeretTempatkan(Game $game, Collection $materials): array
    {
        $roundCount = $game->round_count ?: 5;
        $optionCount = $game->option_count ?: 3;
        $shuffled = $materials->shuffle();

        $rounds = [];
        for ($i = 0; $i < $roundCount; $i++) {
            $target = $shuffled[$i % $shuffled->count()];
            $distractors = $materials->where('id', '!=', $target->id)->shuffle()->take($optionCount - 1);
            $targets = $distractors->push($target)->shuffle()->map(function ($item) use ($target) {
                return [
                    'id' => $item->id,
                    'label' => $item->label,
                    'is_correct' => $item->id === $target->id,
                ];
            })->values()->all();

            $rounds[] = [
                'round_number' => $i + 1,
                'target' => [
                    'id' => $target->id,
                    'label' => $target->label,
                    'image_url' => '/storage/'.$target->image_path,
                    'audio_url' => $target->audio_path ? '/storage/'.$target->audio_path : null,
                ],
                'instruction' => $game->instruction_text ?: "Tempatkan gambar pada kotak {$target->label}",
                'targets' => $targets,
            ];
        }

        return $rounds;
    }
}
