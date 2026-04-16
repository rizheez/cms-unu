<?php

declare(strict_types=1);

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    use Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'short_name',
        'dean_name',
        'email',
        'phone',
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

    public function studyPrograms(): HasMany
    {
        return $this->hasMany(StudyProgram::class)->orderBy('order');
    }

    public function lecturers(): HasMany
    {
        return $this->hasMany(Lecturer::class)->orderBy('order');
    }
}
