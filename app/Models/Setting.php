<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("settings.{$key}", now()->addHour(), function () use ($key, $default): mixed {
            return self::query()->where('key', $key)->value('value') ?? $default;
        });
    }

    public static function set(string $key, mixed $value): void
    {
        self::query()->updateOrCreate(
            ['key' => $key],
            [
                'value' => is_scalar($value) || $value === null ? $value : json_encode($value),
                'group' => 'general',
            ],
        );

        Cache::forget("settings.{$key}");
    }
}
