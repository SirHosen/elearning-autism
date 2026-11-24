<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'game_type',
        'option_count',
        'round_count',
        'instruction_text',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'option_count' => 'integer',
        'round_count' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(GameSession::class, 'game_id');
    }

    public function hasEnoughMaterials(): bool
    {
        $count = $this->category->activeMaterials()->count();
        if ($this->game_type === 'pilih_benar' || $this->game_type === 'seret_tempatkan') {
            return $count >= $this->option_count;
        }
        if ($this->game_type === 'cocokkan') {
            return $count >= $this->round_count;
        }

        return false;
    }
}
