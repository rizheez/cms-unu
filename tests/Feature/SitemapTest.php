<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use DatabaseTransactions;

    public function test_sitemap_endpoint_returns_xml_with_public_content(): void
    {
        Page::query()->create([
            'title' => 'Profil Universitas',
            'slug' => 'profil-universitas-test-sitemap',
            'content' => '<p>Profil.</p>',
            'status' => 'published',
            'is_in_sitemap' => true,
            'published_at' => now(),
        ]);

        $category = PostCategory::query()->create([
            'name' => 'Berita',
            'slug' => 'berita-test-sitemap',
        ]);

        Post::query()->create([
            'post_category_id' => $category->id,
            'user_id' => User::factory()->create()->id,
            'title' => 'Kabar Kampus',
            'slug' => 'kabar-kampus-test-sitemap',
            'content' => '<p>Isi berita.</p>',
            'status' => 'published',
            'is_in_sitemap' => true,
            'published_at' => now(),
        ]);

        $response = $this->get(route('sitemap'));

        $response
            ->assertOk()
            ->assertHeader('content-type', 'text/xml; charset=UTF-8')
            ->assertSee(route('home'), false)
            ->assertSee(route('pages.show', 'profil-universitas-test-sitemap'), false)
            ->assertSee(route('news.show', 'kabar-kampus-test-sitemap'), false);
    }

    public function test_robots_endpoint_points_to_sitemap(): void
    {
        $this->get(route('robots'))
            ->assertOk()
            ->assertSee('Sitemap: '.url('/sitemap.xml'), false);
    }
}
