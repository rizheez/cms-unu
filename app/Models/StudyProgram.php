<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasAutoOrder;
use App\Models\Concerns\LogsCmsActivity;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudyProgram extends Model
{
    use HasAutoOrder, LogsCmsActivity, Sluggable;

    protected $fillable = [
        'faculty_id',
        'name',
        'slug',
        'degree_level',
        'head_name',
        'description',
        'image',
        'accreditation',
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

    public function sluggable(): array
    {
        return [
            'slug' => ['source' => 'name'],
        ];
    }

    protected function autoOrderGroupColumns(): array
    {
        return ['faculty_id'];
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function lecturers(): HasMany
    {
        return $this->hasMany(Lecturer::class)->orderBy('order');
    }
}
