<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\LogsCmsActivity;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use LogsCmsActivity;

    protected $fillable = [
        'name',
        'position',
        'photo',
        'content',
        'rating',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
