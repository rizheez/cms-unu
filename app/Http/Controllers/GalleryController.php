<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Services\SeoService;
use Illuminate\Contracts\View\View;

class GalleryController extends Controller
{
    public function index(SeoService $seo): View
    {
        $seo->setDefault();

        return view('gallery.index', [
            'galleries' => Gallery::query()->with('items')->where('is_active', true)->latest()->paginate(12),
        ]);
    }

    public function show(Gallery $gallery, SeoService $seo): View
    {
        $gallery->load('items');
        $seo->setModel($gallery, $gallery->title, $gallery->description);

        return view('gallery.show', compact('gallery'));
    }
}
