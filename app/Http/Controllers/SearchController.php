<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use App\Models\StudyProgram;
use App\Services\SeoService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request, SeoService $seo): View
    {
        $seo->setDefault();
        $query = $request->string('q')->trim()->toString();

        return view('search.index', [
            'query' => $query,
            'posts' => $query === '' ? collect() : Post::query()->with('category')->published()->where('title', 'like', "%{$query}%")->limit(10)->get(),
            'pages' => $query === '' ? collect() : Page::query()->published()->where('title', 'like', "%{$query}%")->limit(10)->get(),
            'studyPrograms' => $query === '' ? collect() : StudyProgram::query()->with('faculty')->where('is_active', true)->where('name', 'like', "%{$query}%")->limit(10)->get(),
        ]);
    }
}
