<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color_key',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class, 'category_id')->orderBy('sort_order');
    }

    public function activeMaterials(): HasMany
    {
        return $this->materials()->where('is_active', true);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class, 'category_id')->orderBy('sort_order');
    }

    public function games(): HasMany
    {
        return $this->hasMany(Game::class, 'category_id')->orderBy('sort_order');
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class, 'category_id')->orderBy('sort_order');
    }
}
