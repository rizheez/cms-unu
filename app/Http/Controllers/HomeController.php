<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AcademicCalendar;
use App\Models\Announcement;
use App\Models\Faculty;
use App\Models\Gallery;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Slider;
use App\Models\StudyProgram;
use App\Models\Testimonial;
use App\Services\SeoService;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(SeoService $seo): View
    {
        $seo->setDefault();

        return view('home', [
            'sliders' => Slider::query()->where('is_active', true)->orderBy('order')->get(),
            'featuredPosts' => Post::query()->with(['category', 'author'])->published()->featured()->latest('published_at')->limit(3)->get(),
            'announcements' => Announcement::query()->active()->latest()->limit(5)->get(),
            'facultiesCount' => Faculty::query()->where('is_active', true)->count(),
            'studyPrograms' => StudyProgram::query()->with('faculty')->where('is_active', true)->orderBy('order')->limit(6)->get(),
            'academicCalendars' => AcademicCalendar::query()->where('start_date', '>=', now()->toDateString())->orderBy('start_date')->limit(5)->get(),
            'galleries' => Gallery::query()->with('items')->where('is_active', true)->latest()->limit(6)->get(),
            'partners' => Partner::query()->where('is_active', true)->orderBy('order')->get(),
            'testimonials' => Testimonial::query()->where('is_active', true)->latest()->limit(6)->get(),
        ]);
    }
}
