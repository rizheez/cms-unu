<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class PostCommentTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_can_comment_on_a_published_post_as_plain_text(): void
    {
        $post = $this->createPublishedPost();

        $response = $this->post(route('news.comments.store', $post), [
            'name' => '<b>Ani</b>  Pengunjung',
            'body' => "Komentar <script>alert('x')</script>\n\nTetap pakai baris baru.",
        ]);

        $response
            ->assertRedirect(route('news.show', $post).'#comments')
            ->assertSessionHas('comment_success');

        $this->assertDatabaseHas('post_comments', [
            'post_id' => $post->id,
            'name' => 'Ani Pengunjung',
            'body' => "Komentar alert('x')\n\nTetap pakai baris baru.",
            'is_approved' => true,
        ]);
    }

    public function test_comment_body_is_required(): void
    {
        $post = $this->createPublishedPost();

        $response = $this->from(route('news.show', $post))
            ->post(route('news.comments.store', $post), [
                'name' => 'Ani',
                'body' => '',
            ]);

        $response
            ->assertRedirect(route('news.show', $post))
            ->assertSessionHasErrors('body');
    }

    private function createPublishedPost(): Post
    {
        $category = PostCategory::query()->create([
            'name' => 'Berita',
            'slug' => 'berita',
        ]);

        $user = User::factory()->create();

        return Post::query()->create([
            'post_category_id' => $category->id,
            'user_id' => $user->id,
            'title' => 'Berita Komentar',
            'slug' => 'berita-komentar',
            'excerpt' => 'Ringkasan berita komentar.',
            'content' => '<p>Isi berita.</p>',
            'status' => 'published',
            'is_featured' => false,
            'views' => 0,
            'is_in_sitemap' => true,
            'published_at' => now(),
        ]);
    }
}
