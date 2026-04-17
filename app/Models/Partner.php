<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasAutoOrder;
use App\Models\Concerns\LogsCmsActivity;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasAutoOrder, LogsCmsActivity;

    protected $fillable = [
        'name',
        'logo',
        'website',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
