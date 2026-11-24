<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'label',
        'image_path',
        'audio_path',
        'short_sentence',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function questionAnswers(): HasMany
    {
        return $this->hasMany(QuizQuestion::class, 'correct_material_id');
    }

    public function questionOptions(): HasMany
    {
        return $this->hasMany(QuizQuestionOption::class, 'material_id');
    }

    public function isUsedInQuizzes(): bool
    {
        return $this->questionAnswers()->exists() || $this->questionOptions()->exists();
    }
}
