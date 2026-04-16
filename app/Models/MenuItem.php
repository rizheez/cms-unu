<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_id',
        'parent_id',
        'page_id',
        'label',
        'url',
        'target',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('order');
    }

    public function childrenRecursive(): HasMany
    {
        return $this->children()->with(['page', 'childrenRecursive']);
    }

    public function isExternal(): bool
    {
        return Str::startsWith((string) $this->url, ['http://', 'https://', 'mailto:', 'tel:']);
    }

    public function resolvedUrl(): string
    {
        if ($this->page !== null) {
            return route('pages.show', $this->page->slug);
        }

        if ($this->url === null || $this->url === '') {
            return '#';
        }

        if ($this->isExternal()) {
            return $this->url;
        }

        return url($this->url);
    }
}
