<?php

declare(strict_types=1);

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Post extends Model
{
    use Sluggable, SoftDeletes;

    protected $fillable = [
        'post_category_id',
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'status',
        'is_featured',
        'views',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'is_in_sitemap',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_in_sitemap' => 'boolean',
            'views' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    public function sluggable(): array
    {
        return [
            'slug' => ['source' => 'title'],
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function getReadingTimeAttribute(): int
    {
        $words = Str::wordCount(strip_tags((string) $this->content));

        return max(1, (int) ceil($words / 200));
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        $featuredImage = trim((string) $this->featured_image);

        if ($featuredImage !== '') {
            if (Str::startsWith($featuredImage, ['http://', 'https://'])) {
                return $featuredImage;
            }

            if (Storage::disk('public')->exists($featuredImage)) {
                return Storage::disk('public')->url($featuredImage);
            }
        }

        if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', (string) $this->content, $matches) === 1) {
            return html_entity_decode($matches[1]);
        }

        return null;
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }
}
