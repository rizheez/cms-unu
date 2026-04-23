<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Post;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SeoService
{
    public function __construct(private readonly EditorJsContentRenderer $editorJsContentRenderer) {}

    public function setDefault(): void
    {
        $siteName = (string) setting('site_name', 'Universitas Nahdlatul Ulama Kalimantan Timur');
        $title = (string) setting('meta_title', $siteName);
        $description = $this->normalizeDescription((string) setting('meta_description', 'Website resmi Universitas Nahdlatul Ulama Kalimantan Timur, kampus di Samarinda yang menyediakan informasi akademik, berita kampus, pendaftaran mahasiswa baru, dan layanan mahasiswa.'));
        $keywords = $this->normalizeKeywords((string) setting('meta_keywords', ''), $title, $description);
        $imageUrl = $this->storedFileUrl((string) setting('site_logo', ''));

        $this->apply($title, $description, 'website', $keywords, $imageUrl);
    }

    public function setModel(Model $model, string $fallbackTitle, ?string $fallbackDescription = null): void
    {
        $title = $this->resolvedModelTitle($model, $fallbackTitle);
        $description = $this->resolvedModelDescription($model, $fallbackDescription);
        $keywords = $this->normalizeKeywords($this->cleanText((string) $model->getAttribute('meta_keywords')), $title, $description);
        $imageUrl = $this->modelImageUrl($model);
        $type = $model instanceof Post ? 'article' : 'website';

        $this->apply($title, $description, $type, $keywords, $imageUrl);
    }

    private function resolvedModelTitle(Model $model, string $fallbackTitle): string
    {
        return $this->cleanText((string) $model->getAttribute('meta_title'))
            ?: $this->cleanText($fallbackTitle)
            ?: (string) setting('site_name', 'Universitas Nahdlatul Ulama Kalimantan Timur');
    }

    private function resolvedModelDescription(Model $model, ?string $fallbackDescription = null): string
    {
        $description = $this->cleanText((string) $model->getAttribute('meta_description'))
            ?: $this->cleanText((string) $fallbackDescription)
            ?: $this->contentDescription($model)
            ?: (string) setting('meta_description', 'Website resmi Universitas Nahdlatul Ulama Kalimantan Timur.');

        return $this->normalizeDescription($description);
    }

    /**
     * @param  array<int, string>  $keywords
     */
    private function apply(string $title, string $description, string $type, array $keywords = [], ?string $imageUrl = null): void
    {
        $siteName = (string) setting('site_name', 'Universitas Nahdlatul Ulama Kalimantan Timur');
        $canonicalUrl = $this->canonicalUrl();
        $googleVerification = trim((string) setting('google_site_verification', ''));

        SEOTools::setTitle($title);
        SEOTools::setDescription($description);
        SEOTools::setCanonical($canonicalUrl);
        SEOMeta::setKeywords($keywords);
        OpenGraph::setTitle($title);
        OpenGraph::setDescription($description);
        OpenGraph::setSiteName($siteName);
        OpenGraph::setType($type);
        OpenGraph::setUrl($canonicalUrl);
        TwitterCard::setTitle($title);
        TwitterCard::setDescription($description);
        TwitterCard::setType('summary_large_image');
        TwitterCard::setUrl($canonicalUrl);
        JsonLd::setTitle($title);
        JsonLd::setDescription($description);
        JsonLd::setType($type === 'article' ? 'Article' : 'WebPage');
        JsonLd::setUrl($canonicalUrl);

        if ($googleVerification !== '') {
            SEOMeta::addMeta('google-site-verification', $googleVerification);
        }

        if ($imageUrl !== null) {
            OpenGraph::addImage($imageUrl);
            TwitterCard::addImage($imageUrl);
            JsonLd::addImage($imageUrl);
        }
    }

    private function normalizeDescription(string $description): string
    {
        return Str::limit(preg_replace('/\s+/', ' ', trim(strip_tags($description))) ?: 'Website resmi Universitas Nahdlatul Ulama Kalimantan Timur.', 160, '');
    }

    /**
     * @return array<int, string>
     */
    private function normalizeKeywords(string $keywords, string $title, string $description): array
    {
        $items = collect(explode(',', $keywords))
            ->map(fn (string $keyword): string => trim(strip_tags($keyword)))
            ->filter();

        if ($items->isNotEmpty()) {
            return $items->unique(fn (string $keyword): string => Str::lower($keyword))->values()->all();
        }

        $stopWords = [
            'agar',
            'akan',
            'atau',
            'dan',
            'dari',
            'dengan',
            'di',
            'ini',
            'itu',
            'ke',
            'pada',
            'untuk',
            'yang',
        ];

        return collect(explode(' ', Str::lower($title.' '.$description)))
            ->map(fn (string $word): string => trim($word, " \t\n\r\0\x0B.,;:!?()[]{}\"'"))
            ->filter(fn (string $word): bool => mb_strlen($word) >= 4 && ! in_array($word, $stopWords, true))
            ->unique()
            ->take(10)
            ->values()
            ->all();
    }

    private function modelImageUrl(Model $model): ?string
    {
        $image = $model instanceof Post
            ? $model->getAttribute('featured_image')
            : ($model->getAttribute('og_image') ?: $model->getAttribute('image'));

        return $this->storedFileUrl(is_string($image) ? $image : null)
            ?? $this->storedFileUrl((string) setting('site_logo', ''));
    }

    private function storedFileUrl(?string $path): ?string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return asset('storage/'.$path);
    }

    private function contentDescription(Model $model): ?string
    {
        if (! $model->offsetExists('content')) {
            return null;
        }

        return $this->cleanText($this->editorJsContentRenderer->plainText($model->getAttribute('content')));
    }

    private function cleanText(string $value): string
    {
        return trim(preg_replace('/\s+/u', ' ', strip_tags($value)) ?? '');
    }

    private function canonicalUrl(): string
    {
        return request()->fullUrlWithoutQuery([
            'fbclid',
            'gclid',
            'utm_campaign',
            'utm_content',
            'utm_medium',
            'utm_source',
            'utm_term',
        ]);
    }
}
