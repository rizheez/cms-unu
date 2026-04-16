<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\SeoService;
use Illuminate\Contracts\View\View;

class AboutController extends Controller
{
    public function profile(SeoService $seo): View
    {
        return $this->show($seo, 'Profil Universitas', 'Mengenal '.setting('site_name', 'Universitas Nahdlatul Ulama'));
    }

    public function visionMission(SeoService $seo): View
    {
        return $this->show($seo, 'Visi & Misi', (string) setting('vision', ''));
    }

    public function structure(SeoService $seo): View
    {
        return $this->show($seo, 'Struktur Organisasi', 'Struktur organisasi universitas.');
    }

    public function history(SeoService $seo): View
    {
        return $this->show($seo, 'Sejarah UNU', 'Perjalanan dan tonggak sejarah universitas.');
    }

    private function show(SeoService $seo, string $title, string $body): View
    {
        $seo->setDefault();

        return view('pages.simple', compact('title', 'body'));
    }
}
