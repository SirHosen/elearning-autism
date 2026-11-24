<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'total_questions',
        'correct_first_try',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'total_questions' => 'integer',
        'correct_first_try' => 'integer',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class, 'attempt_id');
    }

    public function calculateStars(): int
    {
        if ($this->total_questions === 0) {
            return 1;
        }
        $ratio = $this->correct_first_try / $this->total_questions;
        if ($ratio >= 0.8) {
            return 3;
        }
        if ($ratio >= 0.5) {
            return 2;
        }

        return 1;
    }
}
