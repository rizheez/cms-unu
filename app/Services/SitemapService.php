<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Faculty;
use App\Models\Gallery;
use App\Models\Page;
use App\Models\Post;
use App\Models\StudyProgram;
use Illuminate\Support\Collection;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapService
{
    public function build(): Sitemap
    {
        $sitemap = Sitemap::create();

        $this->addStaticRoutes($sitemap);
        $this->addPages($sitemap);
        $this->addPosts($sitemap);
        $this->addAcademics($sitemap);
        $this->addGalleries($sitemap);

        return $sitemap;
    }

    public function writeToPublicFile(): void
    {
        file_put_contents(public_path('sitemap.xml'), $this->render());
    }

    public function render(): string
    {
        /** @var Collection<int, Url> $tags */
        $tags = collect($this->build()->getTags())
            ->filter(fn (mixed $tag): bool => $tag instanceof Url)
            ->unique('url')
            ->values();

        $urls = $tags
            ->map(fn (Url $tag): string => $this->renderUrlTag($tag))
            ->implode("\n");

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{$urls}
</urlset>
XML;
    }

    private function addStaticRoutes(Sitemap $sitemap): void
    {
        collect([
            ['route' => 'home', 'priority' => 1.0, 'frequency' => Url::CHANGE_FREQUENCY_DAILY],
            ['route' => 'news.index', 'priority' => 0.8, 'frequency' => Url::CHANGE_FREQUENCY_DAILY],
            ['route' => 'academics.index', 'priority' => 0.8, 'frequency' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['route' => 'academics.faculties', 'priority' => 0.7, 'frequency' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['route' => 'academics.lecturers', 'priority' => 0.6, 'frequency' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['route' => 'academics.calendar', 'priority' => 0.6, 'frequency' => Url::CHANGE_FREQUENCY_WEEKLY],
            ['route' => 'gallery.index', 'priority' => 0.6, 'frequency' => Url::CHANGE_FREQUENCY_WEEKLY],
            ['route' => 'announcements.index', 'priority' => 0.6, 'frequency' => Url::CHANGE_FREQUENCY_WEEKLY],
            ['route' => 'downloads.index', 'priority' => 0.5, 'frequency' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['route' => 'faq.index', 'priority' => 0.5, 'frequency' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['route' => 'contact.create', 'priority' => 0.5, 'frequency' => Url::CHANGE_FREQUENCY_YEARLY],
        ])->each(fn (array $item): Sitemap => $sitemap->add(
            Url::create(route($item['route']))
                ->setPriority($item['priority'])
                ->setChangeFrequency($item['frequency']),
        ));
    }

    private function addPages(Sitemap $sitemap): void
    {
        Page::query()
            ->published()
            ->where('is_in_sitemap', true)
            ->select(['id', 'slug', 'updated_at'])
            ->latest('updated_at')
            ->get()
            ->each(fn (Page $page): Sitemap => $sitemap->add(
                Url::create(route('pages.show', $page->slug))
                    ->setLastModificationDate($page->updated_at)
                    ->setPriority(0.7)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY),
            ));
    }

    private function addPosts(Sitemap $sitemap): void
    {
        Post::query()
            ->published()
            ->where('is_in_sitemap', true)
            ->select(['id', 'slug', 'updated_at'])
            ->latest('updated_at')
            ->get()
            ->each(fn (Post $post): Sitemap => $sitemap->add(
                Url::create(route('news.show', $post->slug))
                    ->setLastModificationDate($post->updated_at)
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY),
            ));
    }

    private function addAcademics(Sitemap $sitemap): void
    {
        Faculty::query()
            ->where('is_active', true)
            ->select(['id', 'slug', 'updated_at'])
            ->orderBy('order')
            ->get()
            ->each(fn (Faculty $faculty): Sitemap => $sitemap->add(
                Url::create(route('academics.faculty', $faculty->slug))
                    ->setLastModificationDate($faculty->updated_at)
                    ->setPriority(0.7)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY),
            ));

        StudyProgram::query()
            ->where('is_active', true)
            ->select(['id', 'slug', 'updated_at'])
            ->orderBy('order')
            ->get()
            ->each(fn (StudyProgram $studyProgram): Sitemap => $sitemap->add(
                Url::create(route('academics.study-program', $studyProgram->slug))
                    ->setLastModificationDate($studyProgram->updated_at)
                    ->setPriority(0.7)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY),
            ));
    }

    private function addGalleries(Sitemap $sitemap): void
    {
        Gallery::query()
            ->where('is_active', true)
            ->select(['id', 'slug', 'updated_at'])
            ->latest('updated_at')
            ->get()
            ->each(fn (Gallery $gallery): Sitemap => $sitemap->add(
                Url::create(route('gallery.show', $gallery->slug))
                    ->setLastModificationDate($gallery->updated_at)
                    ->setPriority(0.5)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY),
            ));
    }

    private function renderUrlTag(Url $tag): string
    {
        $lines = [
            '    <loc>'.$this->escape($tag->url).'</loc>',
        ];

        if ($tag->lastModificationDate !== null) {
            $lines[] = '    <lastmod>'.$tag->lastModificationDate->toAtomString().'</lastmod>';
        }

        if ($tag->changeFrequency !== null) {
            $lines[] = '    <changefreq>'.$this->escape($tag->changeFrequency).'</changefreq>';
        }

        if ($tag->priority !== null) {
            $lines[] = '    <priority>'.number_format($tag->priority, 1).'</priority>';
        }

        return "  <url>\n".implode("\n", $lines)."\n  </url>";
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
    }
}
