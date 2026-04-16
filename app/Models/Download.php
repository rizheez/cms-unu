<?php

declare(strict_types=1);

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    use Sluggable;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'file',
        'category',
        'download_count',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'download_count' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function sluggable(): array
    {
        return [
            'slug' => ['source' => 'title'],
        ];
    }

    public function incrementDownloadCount(): void
    {
        $this->increment('download_count');
    }
}
