<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Services\SeoService;
use Illuminate\Contracts\View\View;

class FaqController extends Controller
{
    public function index(SeoService $seo): View
    {
        $seo->setDefault();

        return view('faq', [
            'faqs' => Faq::query()->where('is_active', true)->orderBy('order')->get()->groupBy('category'),
        ]);
    }
}
