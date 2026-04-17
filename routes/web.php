<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AcademicsController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('robots.txt', [SitemapController::class, 'robots'])->name('robots');
Route::get('sitemap.xml', [SitemapController::class, 'sitemap'])->name('sitemap');

Route::prefix('tentang')->name('about.')->controller(AboutController::class)->group(function (): void {
    Route::get('profil', 'profile')->name('profile');
    Route::get('visi-misi', 'visionMission')->name('vision-mission');
    Route::get('struktur-organisasi', 'structure')->name('structure');
    Route::get('sejarah', 'history')->name('history');
});

Route::prefix('akademik')->name('academics.')->controller(AcademicsController::class)->group(function (): void {
    Route::get('/', 'index')->name('index');
    Route::get('fakultas', 'faculties')->name('faculties');
    Route::get('fakultas/{faculty:slug}', 'faculty')->name('faculty');
    Route::get('prodi/{studyProgram:slug}', 'studyProgram')->name('study-program');
    Route::get('dosen', 'lecturers')->name('lecturers');
    Route::get('dosen/{lecturer:slug}', 'lecturer')->name('lecturer');
    Route::get('kalender', 'calendar')->name('calendar');
});

Route::prefix('berita')->name('news.')->controller(NewsController::class)->group(function (): void {
    Route::get('/', 'index')->name('index');
    Route::get('kategori/{category:slug}', 'category')->name('category');
    Route::post('{post:slug}/komentar', 'storeComment')->name('comments.store');
    Route::get('{post:slug}', 'show')->name('show');
});

Route::get('galeri', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('galeri/{gallery:slug}', [GalleryController::class, 'show'])->name('gallery.show');
Route::get('kontak', [ContactController::class, 'create'])->name('contact.create');
Route::post('kontak', [ContactController::class, 'store'])->name('contact.store');
Route::get('pengumuman', [AnnouncementController::class, 'index'])->name('announcements.index');
Route::get('unduhan', [DownloadController::class, 'index'])->name('downloads.index');
Route::get('unduhan/{download:slug}/download', [DownloadController::class, 'download'])->name('downloads.download');
Route::get('faq', [FaqController::class, 'index'])->name('faq.index');
Route::get('cari', [SearchController::class, 'index'])->name('search.index');
Route::get('{page:slug}', [PageController::class, 'show'])->name('pages.show');
