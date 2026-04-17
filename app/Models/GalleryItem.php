<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasAutoOrder;
use App\Models\Concerns\LogsCmsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class GalleryItem extends Model
{
    use HasAutoOrder, LogsCmsActivity;

    protected $fillable = [
        'gallery_id',
        'image',
        'video_url',
        'caption',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }

    protected function autoOrderGroupColumns(): array
    {
        return ['gallery_id'];
    }

    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }

    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if (blank($this->video_url)) {
            return null;
        }

        $host = Str::of((string) parse_url($this->video_url, PHP_URL_HOST))
            ->lower()
            ->replace('www.', '')
            ->toString();

        $path = trim((string) parse_url($this->video_url, PHP_URL_PATH), '/');
        $videoId = null;

        if (in_array($host, ['youtube.com', 'm.youtube.com'], true)) {
            parse_str((string) parse_url($this->video_url, PHP_URL_QUERY), $query);
            $videoId = is_string($query['v'] ?? null) ? $query['v'] : null;

            if ($videoId === null && Str::startsWith($path, 'embed/')) {
                $videoId = Str::after($path, 'embed/');
            }

            if ($videoId === null && Str::startsWith($path, 'shorts/')) {
                $videoId = Str::after($path, 'shorts/');
            }
        }

        if ($host === 'youtu.be') {
            $videoId = Str::before($path, '/');
        }

        $videoId = is_string($videoId) ? Str::before($videoId, '&') : null;

        if (! is_string($videoId) || preg_match('/^[A-Za-z0-9_-]{6,}$/', $videoId) !== 1) {
            return null;
        }

        return "https://www.youtube.com/embed/{$videoId}";
    }
}
