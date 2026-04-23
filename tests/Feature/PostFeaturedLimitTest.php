<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PostFeaturedLimitTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_latest_featured_post_demotes_the_oldest_featured_post(): void
    {
        $category = PostCategory::query()->create([
            'name' => 'Berita',
            'slug' => 'berita',
        ]);

        $user = User::factory()->create();

        $oldestFeaturedPost = $this->createPost($category->id, $user->id, 'Berita A', now()->subMinutes(4), true);
        $secondFeaturedPost = $this->createPost($category->id, $user->id, 'Berita B', now()->subMinutes(3), true);
        $thirdFeaturedPost = $this->createPost($category->id, $user->id, 'Berita C', now()->subMinutes(2), true);
        $latestFeaturedPost = $this->createPost($category->id, $user->id, 'Berita D', now()->subMinute(), false);

        $latestFeaturedPost->update(['is_featured' => true]);

        $this->assertFalse($oldestFeaturedPost->fresh()->is_featured);
        $this->assertNull($oldestFeaturedPost->fresh()->featured_at);
        $this->assertTrue($secondFeaturedPost->fresh()->is_featured);
        $this->assertTrue($thirdFeaturedPost->fresh()->is_featured);
        $this->assertTrue($latestFeaturedPost->fresh()->is_featured);
        $this->assertNotNull($latestFeaturedPost->fresh()->featured_at);
        $this->assertSame(3, Post::query()->where('is_featured', true)->count());
    }

    public function test_featuring_an_old_post_moves_it_to_the_top_featured_order(): void
    {
        $category = PostCategory::query()->create([
            'name' => 'Berita',
            'slug' => 'berita',
        ]);

        $user = User::factory()->create();

        $postA = $this->createPost($category->id, $user->id, 'Berita A', now()->subDays(3), true);
        $postB = $this->createPost($category->id, $user->id, 'Berita B', now()->subDays(2), true);
        $postC = $this->createPost($category->id, $user->id, 'Berita C', now()->subDay(), true);

        $postA->update(['is_featured' => false]);
        $postA->update(['is_featured' => true]);

        $orderedIds = Post::query()
            ->featured()
            ->orderByDesc('featured_at')
            ->orderByDesc('id')
            ->pluck('id')
            ->all();

        $this->assertSame([$postA->id, $postC->id, $postB->id], $orderedIds);
    }

    private function createPost(int $categoryId, int $userId, string $title, Carbon $updatedAt, bool $isFeatured): Post
    {
        return Post::query()->create([
            'post_category_id' => $categoryId,
            'user_id' => $userId,
            'title' => $title,
            'slug' => str($title)->slug()->value(),
            'excerpt' => 'Ringkasan berita.',
            'content' => '<p>Isi berita.</p>',
            'status' => 'published',
            'is_featured' => $isFeatured,
            'featured_at' => $isFeatured ? $updatedAt : null,
            'views' => 0,
            'is_in_sitemap' => true,
            'published_at' => now(),
            'created_at' => $updatedAt,
            'updated_at' => $updatedAt,
        ]);
    }
}
