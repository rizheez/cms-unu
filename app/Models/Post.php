<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\EditorJsContent;
use App\Models\Concerns\LogsCmsActivity;
use App\Services\EditorJsContentRenderer;
use Athphane\FilamentEditorjs\Traits\ModelHasEditorJsComponent;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    use InteractsWithMedia, LogsCmsActivity, ModelHasEditorJsComponent, Sluggable, SoftDeletes;

    protected static function booted(): void
    {
        static::saved(function (self $post): void {
            if (! $post->is_featured) {
                return;
            }

            $post->enforceFeaturedLimit();
        });
    }

    protected $fillable = [
        'post_category_id',
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'status',
        'is_featured',
        'views',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'is_in_sitemap',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_in_sitemap' => 'boolean',
            'content' => EditorJsContent::class,
            'views' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    public function sluggable(): array
    {
        return [
            'slug' => ['source' => 'title'],
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class);
    }

    public function registerMediaCollections(): void
    {
        $this->registerEditorJsMediaCollections();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->registerEditorJsMediaConversions($media);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function getReadingTimeAttribute(): int
    {
        $words = app(EditorJsContentRenderer::class)->wordCount($this->content);

        return max(1, (int) ceil($words / 200));
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        $featuredImage = trim((string) $this->featured_image);

        if ($featuredImage !== '') {
            if (Str::startsWith($featuredImage, ['http://', 'https://'])) {
                return $featuredImage;
            }

            if (Storage::disk('public')->exists($featuredImage)) {
                return Storage::disk('public')->url($featuredImage);
            }
        }

        $content = $this->content;

        if (is_array($content)) {
            $imageUrl = $this->firstEditorJsImageUrl($content);

            if ($imageUrl !== null) {
                return $imageUrl;
            }
        }

        if (is_string($content) && preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $content, $matches) === 1) {
            return html_entity_decode($matches[1]);
        }

        return null;
    }

    public function findAndDeleteRemovedEditorJsMedia(): void
    {
        $content = $this->{$this->editorJsContentFieldName()};

        if (! is_array($content) || ! isset($content['blocks']) || ! is_array($content['blocks'])) {
            return;
        }

        $mediaCurrentlyUsedInContent = collect($content['blocks'])
            ->filter(fn (mixed $block): bool => is_array($block) && data_get($block, 'type') === 'image')
            ->map(fn (array $block): mixed => data_get($block, 'data.file.media_id'))
            ->filter()
            ->all();

        $this->media()
            ->where('collection_name', $this->editorjsMediaCollectionName())
            ->whereNotIn('id', $mediaCurrentlyUsedInContent)
            ->delete();
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }

    public function enforceFeaturedLimit(int $limit = 3): void
    {
        if ($limit < 1) {
            return;
        }

        /** @var Collection<int, int> $postIdsToDemote */
        $postIdsToDemote = static::query()
            ->where('is_featured', true)
            ->whereKeyNot($this->getKey())
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->skip($limit - 1)
            ->pluck($this->getKeyName());

        if ($postIdsToDemote->isEmpty()) {
            return;
        }

        static::query()
            ->whereKey($postIdsToDemote->all())
            ->update(['is_featured' => false]);
    }

    /**
     * @param  array<string, mixed>  $content
     */
    private function firstEditorJsImageUrl(array $content): ?string
    {
        foreach ($content['blocks'] ?? [] as $block) {
            if (! is_array($block) || data_get($block, 'type') !== 'image') {
                continue;
            }

            $url = data_get($block, 'data.file.url');

            if (is_string($url) && $url !== '') {
                return $url;
            }
        }

        return null;
    }
}
