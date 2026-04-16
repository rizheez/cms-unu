<?php

declare(strict_types=1);

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostCategory extends Model
{
    use Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => ['source' => 'name'],
        ];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
