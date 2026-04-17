<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\LogsCmsActivity;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use LogsCmsActivity, Sluggable, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'template',
        'status',
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
            'is_in_sitemap' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function sluggable(): array
    {
        return [
            'slug' => ['source' => 'title'],
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }
}
