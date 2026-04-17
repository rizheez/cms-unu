<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\LogsCmsActivity;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gallery extends Model
{
    use LogsCmsActivity, Sluggable;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_image',
        'type',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function sluggable(): array
    {
        return [
            'slug' => ['source' => 'title'],
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(GalleryItem::class)->orderBy('order');
    }
}
