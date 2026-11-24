<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_id',
        'rounds_total',
        'rounds_correct_first_try',
        'stars_earned',
        'duration_seconds',
        'completed_at',
    ];

    protected $casts = [
        'rounds_total' => 'integer',
        'rounds_correct_first_try' => 'integer',
        'stars_earned' => 'integer',
        'duration_seconds' => 'integer',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public static function calculateStars(int $totalRounds, int $correctFirstTry): int
    {
        if ($totalRounds <= 0) {
            return 1;
        }

        $percentage = ($correctFirstTry / $totalRounds) * 100;

        if ($percentage >= 100) {
            return 3;
        }

        if ($percentage >= 70) {
            return 2;
        }

        return 1;
    }
}
