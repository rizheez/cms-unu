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
    public function setDefault(): void
    {
        $siteName = (string) setting('site_name', 'Universitas Nahdlatul Ulama Kalimantan Timur');
        $title = (string) setting('meta_title', $siteName.' | Kampus di Samarinda');
        $description = $this->normalizeDescription((string) setting('meta_description', 'Website resmi Universitas Nahdlatul Ulama Kalimantan Timur, kampus di Samarinda yang menyediakan informasi akademik, berita kampus, pendaftaran mahasiswa baru, dan layanan mahasiswa.'));

        $this->apply($title, $description, 'website');
    }

    public function setModel(Model $model, string $fallbackTitle, ?string $fallbackDescription = null): void
    {
        $title = (string) ($model->getAttribute('meta_title') ?: $fallbackTitle);
        $description = $this->normalizeDescription((string) ($model->getAttribute('meta_description') ?: $fallbackDescription ?: setting('meta_description', 'Website resmi Universitas Nahdlatul Ulama.')));
        $image = $model->getAttribute('og_image') ?: $model->getAttribute('featured_image') ?: $model->getAttribute('image');
        $type = $model instanceof Post ? 'article' : 'website';

        $this->apply($title, $description, $type);

        if (is_string($image) && $image !== '') {
            $imageUrl = Str::startsWith($image, ['http://', 'https://'])
                ? $image
                : asset('storage/'.$image);

            OpenGraph::addImage($imageUrl);
            TwitterCard::addImage($imageUrl);
            JsonLd::addImage($imageUrl);
        }
    }

    private function apply(string $title, string $description, string $type): void
    {
        $siteName = (string) setting('site_name', 'Universitas Nahdlatul Ulama Kalimantan Timur');
        $canonicalUrl = $this->canonicalUrl();
        $googleVerification = trim((string) setting('google_site_verification', ''));

        SEOTools::setTitle($title);
        SEOTools::setDescription($description);
        SEOTools::setCanonical($canonicalUrl);
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
    }

    private function normalizeDescription(string $description): string
    {
        return Str::limit(preg_replace('/\s+/', ' ', trim(strip_tags($description))) ?: 'Website resmi Universitas Nahdlatul Ulama Kalimantan Timur.', 160, '');
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
