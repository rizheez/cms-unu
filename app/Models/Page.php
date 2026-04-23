<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\EditorJsContent;
use App\Models\Concerns\LogsCmsActivity;
use Athphane\FilamentEditorjs\Traits\ModelHasEditorJsComponent;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Page extends Model implements HasMedia
{
    use InteractsWithMedia, LogsCmsActivity, ModelHasEditorJsComponent, Sluggable, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'template',
        'status',
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
            'content' => EditorJsContent::class,
            'is_in_sitemap' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function sluggable(): array
    {
        return [
            'slug' => ['source' => 'title'],
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }

    public function registerMediaCollections(): void
    {
        $this->registerEditorJsMediaCollections();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->registerEditorJsMediaConversions($media);
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
}
