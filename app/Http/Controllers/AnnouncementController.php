<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Services\SeoService;
use Illuminate\Contracts\View\View;

class AnnouncementController extends Controller
{
    public function index(SeoService $seo): View
    {
        $seo->setDefault();

        return view('announcements.index', [
            'announcements' => Announcement::query()->active()->latest()->paginate(12),
        ]);
    }
}
