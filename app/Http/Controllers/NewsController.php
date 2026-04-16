<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use App\Services\SeoService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

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
        ]);
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
}
