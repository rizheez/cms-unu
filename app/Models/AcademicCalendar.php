<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\LogsCmsActivity;
use Illuminate\Database\Eloquent\Model;

class AcademicCalendar extends Model
{
    use LogsCmsActivity;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'category',
        'color',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }
}
