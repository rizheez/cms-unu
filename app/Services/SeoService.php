<?php

declare(strict_types=1);

namespace App\Services;

use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Database\Eloquent\Model;

class SeoService
{
    public function setDefault(): void
    {
        $siteName = setting('site_name', 'Universitas Nahdlatul Ulama');
        $description = setting('meta_description', 'Website resmi Universitas Nahdlatul Ulama.');

        SEOTools::setTitle((string) setting('meta_title', $siteName));
        SEOTools::setDescription((string) $description);
        OpenGraph::setSiteName((string) $siteName);
        OpenGraph::setDescription((string) $description);
        TwitterCard::setSite((string) $siteName);
        JsonLd::setTitle((string) $siteName);
        JsonLd::setDescription((string) $description);
    }

    public function setModel(Model $model, string $fallbackTitle, ?string $fallbackDescription = null): void
    {
        $title = (string) ($model->getAttribute('meta_title') ?: $fallbackTitle);
        $description = (string) ($model->getAttribute('meta_description') ?: $fallbackDescription ?: setting('meta_description', 'Website resmi Universitas Nahdlatul Ulama.'));
        $image = $model->getAttribute('og_image') ?: $model->getAttribute('featured_image') ?: $model->getAttribute('image');

        SEOTools::setTitle($title);
        SEOTools::setDescription($description);
        OpenGraph::setTitle($title);
        OpenGraph::setDescription($description);
        TwitterCard::setTitle($title);
        JsonLd::setTitle($title);
        JsonLd::setDescription($description);

        if (is_string($image) && $image !== '') {
            OpenGraph::addImage(asset('storage/'.$image));
            JsonLd::addImage(asset('storage/'.$image));
        }
    }
}
