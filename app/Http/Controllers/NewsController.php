<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StorePostCommentRequest;
use App\Models\Post;
use App\Models\PostCategory;
use App\Services\SeoService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request, SeoService $seo): View
    {
        $seo->setDefault();

        return view('news.index', [
            'posts' => Post::query()
                ->with(['category', 'author'])
                ->published()
                ->when($request->integer('category_id'), fn ($query, int $categoryId) => $query->where('post_category_id', $categoryId))
                ->latest('published_at')
                ->paginate(9),
            'categories' => PostCategory::query()->orderBy('name')->get(),
            'latestPosts' => Post::query()
                ->with('category')
                ->published()
                ->latest('published_at')
                ->limit(5)
                ->get(),
        ]);
    }

    public function show(Post $post, SeoService $seo): View
    {
        abort_unless($post->status === 'published', 404);

        $post->load(['category', 'author']);
        $post->incrementViews();
        $seo->setModel($post, $post->title, $post->excerpt);

        return view('news.show', [
            'post' => $post,
            'relatedPosts' => Post::query()
                ->with('category')
                ->published()
                ->whereKeyNot($post->getKey())
                ->where('post_category_id', $post->post_category_id)
                ->latest('published_at')
                ->limit(3)
                ->get(),
            'latestPosts' => Post::query()
                ->with('category')
                ->published()
                ->whereKeyNot($post->getKey())
                ->latest('published_at')
                ->limit(5)
                ->get(),
            'postComments' => $post->comments()
                ->approved()
                ->latest()
                ->get(),
        ]);
    }

    public function storeComment(StorePostCommentRequest $request, Post $post): RedirectResponse
    {
        abort_unless($post->status === 'published', 404);

        $validated = $request->validated();
        $body = $this->sanitizeCommentBody((string) $validated['body']);

        if ($body === '') {
            return back()
                ->withErrors(['body' => 'Komentar wajib diisi.'])
                ->withInput();
        }

        $post->comments()->create([
            'name' => $this->sanitizeCommentName($validated['name'] ?? null),
            'body' => $body,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 500, ''),
            'is_approved' => true,
        ]);

        return redirect(route('news.show', $post).'#comments')
            ->with('comment_success', 'Komentar berhasil dikirim.');
    }

    public function category(PostCategory $category, SeoService $seo): View
    {
        $seo->setDefault();

        return view('news.index', [
            'posts' => Post::query()->with(['category', 'author'])->published()->whereBelongsTo($category, 'category')->latest('published_at')->paginate(9),
            'categories' => PostCategory::query()->orderBy('name')->get(),
            'activeCategory' => $category,
            'latestPosts' => Post::query()
                ->with('category')
                ->published()
                ->latest('published_at')
                ->limit(5)
                ->get(),
        ]);
    }

    private function sanitizeCommentName(?string $name): ?string
    {
        $sanitized = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $name)) ?? '');

        return $sanitized !== '' ? $sanitized : null;
    }

    private function sanitizeCommentBody(string $body): string
    {
        $sanitized = str_replace(["\r\n", "\r"], "\n", strip_tags($body));
        $sanitized = preg_replace('/[ \t]+/u', ' ', $sanitized) ?? $sanitized;
        $sanitized = preg_replace('/\n{3,}/u', "\n\n", $sanitized) ?? $sanitized;

        return trim($sanitized);
    }
}
