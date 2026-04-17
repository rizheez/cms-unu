<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\SitemapService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('sitemap:generate')]
#[Description('Generate public XML sitemap')]
class GenerateSitemap extends Command
{
    public function handle(SitemapService $sitemap): int
    {
        $sitemap->writeToPublicFile();
        $this->info('Sitemap berhasil dibuat.');

        return self::SUCCESS;
    }
}
