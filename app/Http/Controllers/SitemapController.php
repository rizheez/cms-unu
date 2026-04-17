<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\SitemapService;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function sitemap(SitemapService $sitemap): Response
    {
        return response($sitemap->render(), 200, [
            'Content-Type' => 'text/xml',
        ]);
    }

    public function robots(): Response
    {
        $content = trim((string) Setting::get('robots_txt', ''));

        if ($content === '') {
            $content = implode("\n", [
                'User-agent: *',
                'Disallow: /admin',
                'Disallow: /livewire',
                'Disallow: /storage/livewire-tmp',
                'Allow: /',
                '',
                'Sitemap: '.url('/sitemap.xml'),
            ]);
        }

        return response($content."\n", 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
