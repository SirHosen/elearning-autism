<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'source_type',
        'video_path',
        'youtube_id',
        'thumbnail_path',
        'description',
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

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('title');
    }

    public function getStreamUrlAttribute(): ?string
    {
        if ($this->source_type === 'upload' && $this->video_path) {
            return asset('storage/'.$this->video_path);
        }

        return null;
    }

    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if ($this->source_type === 'youtube' && $this->youtube_id) {
            return 'https://www.youtube-nocookie.com/embed/'.$this->youtube_id.'?rel=0&modestbranding=1&playsinline=1';
        }

        return null;
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail_path) {
            return asset('storage/'.$this->thumbnail_path);
        }

        if ($this->source_type === 'youtube' && $this->youtube_id) {
            return 'https://img.youtube.com/vi/'.$this->youtube_id.'/hqdefault.jpg';
        }

        return asset('images/icons/video-placeholder.svg');
    }
}
