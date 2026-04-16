<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\SeoService;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function show(Page $page, SeoService $seo): View
    {
        abort_unless($page->status === 'published', 404);

        $seo->setModel($page, $page->title, $page->content);

        return view('pages.show', compact('page'));
    }
}
