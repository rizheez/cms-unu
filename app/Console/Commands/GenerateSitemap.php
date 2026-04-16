<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Page;
use App\Models\Post;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

#[Signature('sitemap:generate')]
#[Description('Generate public XML sitemap')]
class GenerateSitemap extends Command
{
    public function handle(): int
    {
        $sitemap = Sitemap::create()
            ->add(Url::create(route('home')));

        Page::query()->published()->where('is_in_sitemap', true)->get()
            ->each(fn (Page $page) => $sitemap->add(Url::create(route('pages.show', $page))));

        Post::query()->published()->where('is_in_sitemap', true)->get()
            ->each(fn (Post $post) => $sitemap->add(Url::create(route('news.show', $post))));

        $sitemap->writeToFile(public_path('sitemap.xml'));
        $this->info('Sitemap berhasil dibuat.');

        return self::SUCCESS;
    }
}
