<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasAutoOrder;
use App\Models\Concerns\LogsCmsActivity;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Lecturer extends Model
{
    use HasAutoOrder, LogsCmsActivity, Sluggable;

    protected $fillable = [
        'faculty_id',
        'study_program_id',
        'name',
        'slug',
        'nidn',
        'email',
        'position',
        'education_level',
        'bio',
        'photo',
        'expertise',
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

    /**
     * @return Attribute<?string, never>
     */
    protected function photoUrl(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes): ?string {
                $photo = trim((string) ($attributes['photo'] ?? ''));

                if ($photo === '') {
                    return null;
                }

                if (Str::startsWith($photo, ['http://', 'https://'])) {
                    return $photo;
                }

                if (! Storage::disk('public')->exists($photo)) {
                    return null;
                }

                return Storage::disk('public')->url($photo);
            },
        );
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }
}
