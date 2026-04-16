# 🤖 AGENT WORKFLOW — Aplikasi Web Universitas Nahdlatul Ulama

> **Stack**: Laravel 13 · MySQL · Filament CMS · SEO Ready  
> **Target**: Sistem web universitas lengkap, modern, dan production-ready

---

## 📋 OVERVIEW MISI

Bangun aplikasi web resmi **Universitas Nahdlatul Ulama (UNU)** yang:

- Memiliki tampilan frontend publik yang profesional dan responsif
- Dilengkapi CMS berbasis Filament v5 untuk manajemen konten
- SEO-ready dengan meta tags dinamis, sitemap XML, Open Graph, dan schema.org
- Menggunakan Laravel 13 + MySQL sebagai fondasi utama

---

## 🗂️ FASE EKSEKUSI

Ikuti fase berikut **secara berurutan**. Jangan melanjutkan ke fase berikutnya sebelum fase sebelumnya selesai dan berjalan tanpa error.

---

## FASE 1 — Setup Project & Konfigurasi Dasar

### 1.1 Inisialisasi Project Laravel 13

```bash
composer create-project laravel/laravel unu-web
cd unu-web
```

### 1.2 Konfigurasi `.env`

Set variabel berikut di `.env`:

```env
APP_NAME="Universitas Nahdlatul Ulama"
APP_URL=http://localhost
APP_LOCALE=id
APP_TIMEZONE=Asia/Makassar

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=unu_web
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public
```

### 1.3 Install Package Utama

```bash
# Filament CMS v5
composer require filament/filament:"^5.0" -W

# SEO Package
composer require artesaos/seotools
composer require spatie/laravel-sitemap

# Image Processing
composer require intervention/image-laravel

# Slug otomatis
composer require cviebrock/eloquent-sluggable

# Activity Log
composer require spatie/laravel-activitylog

# Spatie Media Library (untuk upload file)
composer require spatie/laravel-medialibrary

# Backup
composer require spatie/laravel-backup
```

```bash
# Install Filament panels
php artisan filament:install --panels

# Publish config SEO
php artisan vendor:publish --provider="Artesaos\SEOTools\Providers\SEOToolsServiceProvider"

# Publish config sitemap
php artisan vendor:publish --provider="Spatie\Sitemap\SitemapServiceProvider" --tag=sitemap-config

# Storage link
php artisan storage:link
```

---

## FASE 2 — Desain Database & Migrations

Buat semua migration berikut dengan `php artisan make:migration`:

### 2.1 Tabel `settings`

```php
Schema::create('settings', function (Blueprint $table) {
    $table->id();
    $table->string('key')->unique();
    $table->text('value')->nullable();
    $table->string('group')->default('general');
    $table->timestamps();
});
```

**Data awal (seeder)**: nama universitas, alamat, telepon, email, logo, favicon, sosial media, visi, misi, akreditasi.

### 2.2 Tabel `menus` & `menu_items`

```php
// menus
$table->id();
$table->string('name');
$table->string('location'); // header, footer, sidebar
$table->boolean('is_active')->default(true);
$table->timestamps();

// menu_items
$table->id();
$table->foreignId('menu_id')->constrained()->cascadeOnDelete();
$table->foreignId('parent_id')->nullable()->constrained('menu_items')->nullOnDelete();
$table->string('label');
$table->string('url')->nullable();
$table->string('target')->default('_self');
$table->integer('order')->default(0);
$table->timestamps();
```

### 2.3 Tabel `pages` (Halaman Statis)

```php
$table->id();
$table->string('title');
$table->string('slug')->unique();
$table->longText('content')->nullable();
$table->string('template')->default('default'); // default, full-width, sidebar
$table->string('status')->default('draft'); // draft, published
$table->string('meta_title')->nullable();
$table->text('meta_description')->nullable();
$table->string('meta_keywords')->nullable();
$table->string('og_image')->nullable();
$table->boolean('is_in_sitemap')->default(true);
$table->timestamp('published_at')->nullable();
$table->timestamps();
$table->softDeletes();
```

### 2.4 Tabel `post_categories`

```php
$table->id();
$table->string('name');
$table->string('slug')->unique();
$table->text('description')->nullable();
$table->string('color')->nullable();
$table->timestamps();
```

### 2.5 Tabel `posts` (Berita & Artikel)

```php
$table->id();
$table->foreignId('post_category_id')->nullable()->constrained()->nullOnDelete();
$table->foreignId('user_id')->constrained()->cascadeOnDelete();
$table->string('title');
$table->string('slug')->unique();
$table->text('excerpt')->nullable();
$table->longText('content')->nullable();
$table->string('featured_image')->nullable();
$table->string('status')->default('draft'); // draft, published, archived
$table->boolean('is_featured')->default(false);
$table->integer('views')->default(0);
$table->string('meta_title')->nullable();
$table->text('meta_description')->nullable();
$table->string('meta_keywords')->nullable();
$table->string('og_image')->nullable();
$table->boolean('is_in_sitemap')->default(true);
$table->timestamp('published_at')->nullable();
$table->timestamps();
$table->softDeletes();
```

### 2.6 Tabel `faculties` (Fakultas)

```php
$table->id();
$table->string('name');
$table->string('slug')->unique();
$table->string('short_name')->nullable();
$table->string('dean_name')->nullable();
$table->string('email')->nullable();
$table->string('phone')->nullable();
$table->text('description')->nullable();
$table->string('image')->nullable();
$table->string('accreditation')->nullable();
$table->integer('order')->default(0);
$table->boolean('is_active')->default(true);
$table->timestamps();
```

### 2.7 Tabel `study_programs` (Program Studi)

```php
$table->id();
$table->foreignId('faculty_id')->constrained()->cascadeOnDelete();
$table->string('name');
$table->string('slug')->unique();
$table->string('degree_level'); // D3, S1, S2, S3, Profesi
$table->string('head_name')->nullable();
$table->text('description')->nullable();
$table->string('image')->nullable();
$table->string('accreditation')->nullable();
$table->integer('order')->default(0);
$table->boolean('is_active')->default(true);
$table->timestamps();
```

### 2.8 Tabel `academic_calendars` (Kalender Akademik)

```php
$table->id();
$table->string('title');
$table->text('description')->nullable();
$table->date('start_date');
$table->date('end_date')->nullable();
$table->string('category'); // penerimaan, perkuliahan, ujian, wisuda, libur
$table->string('color')->default('#10b981');
$table->timestamps();
```

### 2.9 Tabel `announcements` (Pengumuman)

```php
$table->id();
$table->string('title');
$table->string('slug')->unique();
$table->text('content');
$table->string('type')->default('info'); // info, warning, danger, success
$table->boolean('is_popup')->default(false);
$table->boolean('is_active')->default(true);
$table->timestamp('start_at')->nullable();
$table->timestamp('end_at')->nullable();
$table->timestamps();
```

### 2.10 Tabel `lecturers` (Dosen)

```php
$table->id();
$table->foreignId('faculty_id')->nullable()->constrained()->nullOnDelete();
$table->foreignId('study_program_id')->nullable()->constrained()->nullOnDelete();
$table->string('name');
$table->string('slug')->unique();
$table->string('nidn')->nullable();
$table->string('email')->nullable();
$table->string('position')->nullable();
$table->string('education_level')->nullable(); // S2, S3
$table->text('bio')->nullable();
$table->string('photo')->nullable();
$table->string('expertise')->nullable();
$table->integer('order')->default(0);
$table->boolean('is_active')->default(true);
$table->timestamps();
```

### 2.11 Tabel `galleries`

```php
$table->id();
$table->string('title');
$table->string('slug')->unique();
$table->text('description')->nullable();
$table->string('cover_image')->nullable();
$table->string('type')->default('photo'); // photo, video
$table->boolean('is_active')->default(true);
$table->timestamps();
```

### 2.12 Tabel `gallery_items`

```php
$table->id();
$table->foreignId('gallery_id')->constrained()->cascadeOnDelete();
$table->string('image')->nullable();
$table->string('video_url')->nullable();
$table->string('caption')->nullable();
$table->integer('order')->default(0);
$table->timestamps();
```

### 2.13 Tabel `sliders`

```php
$table->id();
$table->string('title');
$table->string('subtitle')->nullable();
$table->string('image');
$table->string('button_text')->nullable();
$table->string('button_url')->nullable();
$table->integer('order')->default(0);
$table->boolean('is_active')->default(true);
$table->timestamps();
```

### 2.14 Tabel `partners` (Mitra/Kerjasama)

```php
$table->id();
$table->string('name');
$table->string('logo');
$table->string('website')->nullable();
$table->integer('order')->default(0);
$table->boolean('is_active')->default(true);
$table->timestamps();
```

### 2.15 Tabel `testimonials`

```php
$table->id();
$table->string('name');
$table->string('position')->nullable();
$table->string('photo')->nullable();
$table->text('content');
$table->integer('rating')->default(5);
$table->boolean('is_active')->default(true);
$table->timestamps();
```

### 2.16 Tabel `contact_messages` (Pesan Kontak)

```php
$table->id();
$table->string('name');
$table->string('email');
$table->string('phone')->nullable();
$table->string('subject');
$table->text('message');
$table->string('status')->default('unread'); // unread, read, replied
$table->timestamp('read_at')->nullable();
$table->timestamps();
```

### 2.17 Tabel `faqs`

```php
$table->id();
$table->string('question');
$table->text('answer');
$table->string('category')->nullable();
$table->integer('order')->default(0);
$table->boolean('is_active')->default(true);
$table->timestamps();
```

### 2.18 Tabel `downloads` (Dokumen Unduhan)

```php
$table->id();
$table->string('title');
$table->string('slug')->unique();
$table->text('description')->nullable();
$table->string('file');
$table->string('category')->nullable();
$table->integer('download_count')->default(0);
$table->boolean('is_active')->default(true);
$table->timestamps();
```

---

## FASE 3 — Models & Eloquent Relationships

Buat semua model dengan `php artisan make:model [NamaModel]`.

### Aturan Umum Semua Model:

- Gunakan trait `HasSlug` dari `cviebrock/eloquent-sluggable` untuk field `slug`
- Gunakan `SoftDeletes` untuk model yang ada `softDeletes()` di migration
- Definisikan semua `$fillable` secara eksplisit
- Definisikan `$casts` untuk field boolean, date, dan timestamp
- Buat semua relasi Eloquent (hasMany, belongsTo, dll.)

### Model `Setting`:

```php
// Method statis untuk get/set setting
public static function get(string $key, $default = null)
public static function set(string $key, $value): void
```

### Model `Post`:

```php
// Scope
public function scopePublished($query)
public function scopeFeatured($query)

// Accessor
public function getReadingTimeAttribute(): int // estimasi menit baca

// Increment view
public function incrementViews(): void
```

### Model `Page`, `Faculty`, `StudyProgram`, `Lecturer`, `Download`:

- Semua gunakan `HasSlug`
- Definisikan `sluggable()` method yang return config slug dari field `name` atau `title`

---

## FASE 4 — CMS dengan Filament v5

### 4.1 Setup Admin Panel

```bash
php artisan make:filament-user
```

Panel path: `/admin`

### 4.2 Buat Filament Resources

Jalankan command berikut untuk setiap resource:

```bash
php artisan make:filament-resource [NamaModel] --generate
```

**List Resource yang wajib dibuat:**

- `SliderResource` — dengan form upload gambar, order drag-drop
- `PageResource` — dengan rich text editor (TipTap/Quill), tab SEO
- `PostResource` — dengan tab konten & tab SEO, preview gambar, status toggle
- `PostCategoryResource` — form sederhana
- `FacultyResource` — dengan relasi ke study programs
- `StudyProgramResource` — dengan select faculty
- `LecturerResource` — dengan filter by faculty
- `AnnouncementResource` — dengan date range aktif, toggle popup
- `AcademicCalendarResource` — dengan calendar view widget
- `GalleryResource` — dengan repeater untuk gallery items
- `PartnerResource` — dengan upload logo dan order
- `TestimonialResource` — dengan rating input
- `FaqResource` — dengan reorder
- `DownloadResource` — dengan upload file
- `ContactMessageResource` — READ ONLY, dengan badge status, bulk delete
- `MenuResource` — dengan nested menu builder (gunakan tree/repeater)
- `SettingResource` — bukan resource biasa, buat sebagai **Filament Pages** dengan grouping (General, Contact, Social Media, SEO, Appearance)

### 4.3 Konfigurasi Setiap Resource

**Untuk setiap Resource yang memiliki `meta_title`, `meta_description`, `meta_keywords`, `og_image`**, tambahkan **Tab "SEO"** di form:

```php
Forms\Components\Tabs\Tab::make('SEO')
    ->schema([
        Forms\Components\TextInput::make('meta_title')
            ->label('Meta Title')
            ->maxLength(70)
            ->helperText('Optimal: 50-70 karakter')
            ->suffixAction(/* character counter */),
        Forms\Components\Textarea::make('meta_description')
            ->label('Meta Description')
            ->maxLength(160)
            ->helperText('Optimal: 120-160 karakter'),
        Forms\Components\TextInput::make('meta_keywords')
            ->label('Meta Keywords'),
        Forms\Components\FileUpload::make('og_image')
            ->label('OG Image (1200x630px)')
            ->image(),
        Forms\Components\Toggle::make('is_in_sitemap')
            ->label('Masukkan ke Sitemap'),
    ]),
```

### 4.4 Filament Widgets untuk Dashboard

Buat widget berikut di Dashboard:

- `StatsOverviewWidget` — total posts, total mahasiswa (statis), total dosen, total pesan masuk
- `LatestPostsWidget` — 5 artikel terbaru
- `UnreadMessagesWidget` — pesan kontak belum dibaca
- `RecentActivityWidget` — log aktivitas terbaru

### 4.5 Filament Shield (Roles & Permissions)

```bash
composer require bezhansalleh/filament-shield
php artisan shield:install --fresh
```

Gunakan **Filament Shield** sebagai layer otorisasi utama untuk panel admin

---

## FASE 5 — Frontend Public

### 5.1 Layout Utama

Buat layout Blade `resources/views/layouts/app.blade.php` dengan:

- `<head>` dengan SEO meta tags dinamis (dari SEOTools)
- Header responsif dengan logo UNU + navigasi dinamis dari database
- Breadcrumb otomatis
- Footer dengan kolom: tentang, tautan cepat, kontak, sosial media
- Mobile-first dengan hamburger menu
- Back to top button
- Cookie consent banner
- Google Analytics / GA4 placeholder

**Warna tema UNU**: Toska/turquoise (`#00a9b7`) + teal gelap (`#005f69`) + kuning-oranye (`#ffc928`, `#ff9f1c`) + putih segar (`#f4fffc`). Base visual mengikuti referensi poster kampus: latar turquoise terang, aksen kuning besar, dan nuansa muda/ceria tanpa kehilangan identitas NU.

### 5.2 Halaman-Halaman Frontend

Buat **Controller** dan **View** untuk setiap halaman berikut:

#### `HomeController`

**Route**: `GET /`  
**View**: `home.blade.php`  
Komponen:

- Hero slider (load dari tabel `sliders`)
- Stats counter: jumlah mahasiswa, dosen, program studi, alumni
- Berita terbaru (3 post featured)
- Pengumuman aktif (marquee atau card)
- Tentang singkat UNU + CTA
- Program studi unggulan (grid 6 prodi)
- Kalender akademik mendatang
- Galeri foto terbaru
- Mitra/partner logo strip
- Testimonial slider

#### `AboutController`

**Routes**:

- `GET /tentang/profil` — Profil universitas
- `GET /tentang/visi-misi` — Visi & Misi
- `GET /tentang/struktur-organisasi` — Struktur organisasi (image + deskripsi)
- `GET /tentang/sejarah` — Sejarah UNU

#### `AcademicsController`

**Routes**:

- `GET /akademik` — Overview akademik
- `GET /akademik/fakultas` — Daftar semua fakultas
- `GET /akademik/fakultas/{slug}` — Detail fakultas + list prodi
- `GET /akademik/prodi/{slug}` — Detail program studi
- `GET /akademik/dosen` — Direktori dosen (filter by faculty/prodi)
- `GET /akademik/dosen/{slug}` — Profil dosen
- `GET /akademik/kalender` — Kalender akademik (view bulan/list)

#### `NewsController`

**Routes**:

- `GET /berita` — List berita (pagination, filter by category)
- `GET /berita/{slug}` — Detail berita + related posts
- `GET /berita/kategori/{slug}` — Berita by kategori

#### `GalleryController`

**Routes**:

- `GET /galeri` — Grid galeri albums
- `GET /galeri/{slug}` — Detail album + lightbox

#### `PageController`

**Route**: `GET /{slug}` — Render halaman statis dari database  
Gunakan template yang sesuai (`default`, `full-width`, `sidebar`)

#### `ContactController`

**Routes**:

- `GET /kontak` — Halaman kontak (form + peta + info)
- `POST /kontak` — Submit pesan (validasi + simpan ke DB + kirim email notif)

#### `AnnouncementController`

**Route**: `GET /pengumuman` — List & detail pengumuman

#### `DownloadController`

**Routes**:

- `GET /unduhan` — Daftar dokumen (filter by category)
- `GET /unduhan/{slug}/download` — Increment count & redirect ke file

#### `FaqController`

**Route**: `GET /faq` — Halaman FAQ dengan accordion

#### `SearchController`

**Route**: `GET /cari` — Full-text search di posts, pages, dan program studi

### 5.3 Desain Frontend (Panduan Visual)

Gunakan **Tailwind CSS** + **Alpine.js** (sudah include di Laravel):

```bash
npm install
npm install @tailwindcss/typography @tailwindcss/forms alpinejs
```

**Palet warna** (set di `tailwind.config.js`):

```js
colors: {
  unu: {
    teal: '#005f69',
    turquoise: '#00a9b7',
    mint: '#31d4b4',
    yellow: '#ffc928',
    orange: '#ff9f1c',
    cream:    '#f4fffc',
  }
}
```

**Typography**: Gunakan Google Fonts — `Plus Jakarta Sans` untuk body, `Playfair Display` untuk heading utama.

**Komponen UI yang wajib dibuat** (`resources/views/components/`):

- `hero-slider.blade.php` — Slider fullscreen dengan autoplay
- `news-card.blade.php` — Kartu berita dengan gambar, kategori, tanggal, excerpt
- `faculty-card.blade.php` — Kartu fakultas dengan hover effect
- `prodi-card.blade.php` — Kartu program studi dengan badge akreditasi
- `lecturer-card.blade.php` — Kartu dosen dengan foto dan info singkat
- `announcement-ticker.blade.php` — Ticker berjalan untuk pengumuman
- `breadcrumb.blade.php` — Breadcrumb otomatis
- `pagination.blade.php` — Pagination custom styled
- `social-share.blade.php` — Tombol share sosial media
- `whatsapp-float.blade.php` — Tombol WhatsApp floating

---

## FASE 6 — SEO Implementation

### 6.1 SEO Service Class

Buat `app/Services/SeoService.php`:

```php
<?php
namespace App\Services;

use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;

class SeoService
{
    public function setDefault(): void
    {
        // Load dari setting database
        $siteName = setting('site_name', 'Universitas Nahdlatul Ulama');
        $defaultDesc = setting('meta_description');
        $defaultKeywords = setting('meta_keywords');

        SEOTools::setTitle(setting('meta_title', $siteName));
        SEOTools::setDescription($defaultDesc);
        SEOTools::metatags()->setKeywords(explode(',', $defaultKeywords ?? ''));
        SEOTools::metatags()->addMeta('robots', 'index, follow');
        SEOTools::metatags()->addMeta('author', $siteName);

        OpenGraph::setType('website');
        OpenGraph::setSiteName($siteName);
    }

    public function setForPost(\App\Models\Post $post): void
    {
        $title = $post->meta_title ?: $post->title;
        $desc = $post->meta_description ?: $post->excerpt;
        $image = $post->og_image ?: $post->featured_image;

        SEOTools::setTitle($title);
        SEOTools::setDescription($desc);
        SEOTools::metatags()->setKeywords(explode(',', $post->meta_keywords ?? ''));

        OpenGraph::setTitle($title);
        OpenGraph::setDescription($desc);
        OpenGraph::setType('article');
        OpenGraph::setUrl(request()->url());
        if ($image) OpenGraph::addImage(asset('storage/' . $image));
        OpenGraph::setArticle([
            'published_time' => $post->published_at,
            'author' => $post->user->name ?? '',
        ]);

        TwitterCard::setTitle($title);
        TwitterCard::setDescription($desc);
        TwitterCard::setType('summary_large_image');
        if ($image) TwitterCard::setImage(asset('storage/' . $image));

        JsonLd::setType('NewsArticle');
        JsonLd::setTitle($title);
        JsonLd::setDescription($desc);
        JsonLd::addValue('datePublished', $post->published_at);
        JsonLd::addValue('author', ['@type' => 'Person', 'name' => $post->user->name ?? '']);
        JsonLd::addValue('publisher', [
            '@type' => 'Organization',
            'name' => setting('site_name'),
            'logo' => ['@type' => 'ImageObject', 'url' => asset('storage/' . setting('logo'))],
        ]);
    }

    public function setForPage(\App\Models\Page $page): void { /* sama seperti setForPost */ }
    public function setForFaculty(\App\Models\Faculty $faculty): void { /* ... */ }
    public function setForStudyProgram(\App\Models\StudyProgram $prodi): void { /* ... */ }
}
```

### 6.2 JSON-LD Schema untuk Homepage

Di `HomeController`, tambahkan schema.org `EducationalOrganization`:

```php
JsonLd::setType('EducationalOrganization');
JsonLd::setTitle(setting('site_name'));
JsonLd::addValue('url', config('app.url'));
JsonLd::addValue('address', [
    '@type' => 'PostalAddress',
    'streetAddress' => setting('address'),
    'addressLocality' => setting('city'),
    'addressCountry' => 'ID',
]);
JsonLd::addValue('contactPoint', [
    '@type' => 'ContactPoint',
    'telephone' => setting('phone'),
    'contactType' => 'customer service',
]);
```

### 6.3 Sitemap XML Dinamis

Buat `app/Console/Commands/GenerateSitemap.php`:

```php
php artisan make:command GenerateSitemap
```

Isi command untuk generate sitemap dari: pages, posts, faculties, study programs.

Tambahkan ke `routes/web.php`:

```php
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index']);
```

Buat `SitemapController` yang generate sitemap on-the-fly menggunakan `spatie/laravel-sitemap`.

### 6.4 Robots.txt

Buat `public/robots.txt`:

```
User-agent: *
Allow: /
Disallow: /admin
Disallow: /api
Sitemap: https://unu.ac.id/sitemap.xml
```

Atau buat route dinamis:

```php
Route::get('/robots.txt', function() {
    return response()->view('robots')->header('Content-Type', 'text/plain');
});
```

### 6.5 Canonical URL & Pagination SEO

Di layout `app.blade.php`:

```html
<link rel="canonical" href="{{ url()->current() }}" />
@if(isset($posts) && $posts->currentPage() > 1)
<meta name="robots" content="noindex, follow" />
@endif
```

### 6.6 Breadcrumb Schema

Di komponen `breadcrumb.blade.php`, tambahkan JSON-LD `BreadcrumbList`:

```html
<script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
      /* dynamic breadcrumbs */
    ]
  }
</script>
```

### 6.7 Image SEO

- Semua gambar yang diupload harus memiliki `alt` text yang diinput via CMS
- Gunakan `loading="lazy"` untuk semua gambar non-above-the-fold
- Buat middleware atau observer untuk auto-compress gambar saat upload menggunakan `intervention/image`

### 6.8 Page Speed Optimization

- Enable `COMPRESS_IMAGES=true` di env, proses di background job
- Tambahkan `@vite(['resources/css/app.css', 'resources/js/app.js'])` dengan defer
- Set cache headers untuk asset statis di `config/filesystems.php`

---

## FASE 7 — Routes & Middleware

### 7.1 File `routes/web.php`

```php
<?php
use App\Http\Controllers\{
    HomeController, AboutController, AcademicsController,
    NewsController, GalleryController, PageController,
    ContactController, AnnouncementController, DownloadController,
    FaqController, SearchController, SitemapController
};

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Robots & Sitemap
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');
Route::get('/sitemap.xml', [SitemapController::class, 'sitemap'])->name('sitemap');

// Tentang
Route::prefix('tentang')->name('about.')->group(function () {
    Route::get('/profil', [AboutController::class, 'profile'])->name('profile');
    Route::get('/visi-misi', [AboutController::class, 'visionMission'])->name('vision');
    Route::get('/struktur-organisasi', [AboutController::class, 'structure'])->name('structure');
    Route::get('/sejarah', [AboutController::class, 'history'])->name('history');
});

// Akademik
Route::prefix('akademik')->name('academic.')->group(function () {
    Route::get('/', [AcademicsController::class, 'index'])->name('index');
    Route::get('/fakultas', [AcademicsController::class, 'faculties'])->name('faculties');
    Route::get('/fakultas/{slug}', [AcademicsController::class, 'facultyDetail'])->name('faculty');
    Route::get('/prodi/{slug}', [AcademicsController::class, 'prodiDetail'])->name('prodi');
    Route::get('/dosen', [AcademicsController::class, 'lecturers'])->name('lecturers');
    Route::get('/dosen/{slug}', [AcademicsController::class, 'lecturerDetail'])->name('lecturer');
    Route::get('/kalender', [AcademicsController::class, 'calendar'])->name('calendar');
});

// Berita
Route::prefix('berita')->name('news.')->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('index');
    Route::get('/kategori/{slug}', [NewsController::class, 'byCategory'])->name('category');
    Route::get('/{slug}', [NewsController::class, 'show'])->name('show');
});

// Galeri
Route::prefix('galeri')->name('gallery.')->group(function () {
    Route::get('/', [GalleryController::class, 'index'])->name('index');
    Route::get('/{slug}', [GalleryController::class, 'show'])->name('show');
});

// Kontak
Route::get('/kontak', [ContactController::class, 'index'])->name('contact');
Route::post('/kontak', [ContactController::class, 'store'])->name('contact.store');

// Pengumuman
Route::get('/pengumuman', [AnnouncementController::class, 'index'])->name('announcement.index');

// Unduhan
Route::get('/unduhan', [DownloadController::class, 'index'])->name('download.index');
Route::get('/unduhan/{slug}/download', [DownloadController::class, 'download'])->name('download.file');

// FAQ
Route::get('/faq', [FaqController::class, 'index'])->name('faq');

// Search
Route::get('/cari', [SearchController::class, 'index'])->name('search');

// Halaman statis (harus paling bawah)
Route::get('/{slug}', [PageController::class, 'show'])->name('page.show');
```

### 7.2 Middleware

Buat `app/Http/Middleware/TrackPostView.php` — auto increment view count saat halaman post dibuka.

---

## FASE 8 — Helper Functions & Global Config

### 8.1 Helper `setting()`

Buat `app/Helpers/helpers.php`:

```php
if (!function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        return \App\Models\Setting::get($key, $default);
    }
}
```

Register di `composer.json`:

```json
"autoload": {
    "files": ["app/Helpers/helpers.php"]
}
```

### 8.2 View Composer Global

Buat `app/Http/View/Composers/GlobalComposer.php` yang share ke semua view:

- `$settings` — semua setting dari DB
- `$headerMenu` — menu header dari DB
- `$footerMenu` — menu footer dari DB
- `$activeAnnouncements` — pengumuman aktif saat ini
- `$socialLinks` — link sosial media

Register di `AppServiceProvider::boot()`.

---

## FASE 9 — Email Notifications

### 9.1 Mail untuk Pesan Kontak

```bash
php artisan make:mail ContactFormReceived
```

- Kirim ke email admin saat ada pesan masuk
- Template Blade yang rapi

### 9.2 Konfigurasi Mail

Set di `.env`:

```env
MAIL_MAILER=smtp
MAIL_FROM_ADDRESS="noreply@unu.ac.id"
MAIL_FROM_NAME="Universitas Nahdlatul Ulama"
```

---

## FASE 10 — Artisan Commands & Cron

### 10.1 Commands

```bash
php artisan make:command GenerateSitemap    # Generate sitemap.xml
php artisan make:command ClearExpiredAnnouncements  # Hapus pengumuman expired
```

### 10.2 Schedule (`app/Console/Kernel.php` atau `routes/console.php` di Laravel 13):

```php
Schedule::command('sitemap:generate')->daily();
Schedule::command('announcements:clear-expired')->hourly();
Schedule::command('backup:run')->daily()->at('02:00');
```

---

## FASE 11 — Seeder & Factory

Buat seeders untuk:

```bash
php artisan make:seeder DatabaseSeeder
php artisan make:seeder SettingSeeder
php artisan make:seeder MenuSeeder
php artisan make:seeder FacultySeeder
php artisan make:seeder StudyProgramSeeder
php artisan make:seeder PostCategorySeeder
php artisan make:seeder PostSeeder          # 20 sample posts
php artisan make:seeder SliderSeeder
php artisan make:seeder FaqSeeder
php artisan make:seeder LecturerSeeder
php artisan make:seeder AdminUserSeeder
php artisan make:seeder RolePermissionSeeder
```

**SettingSeeder** harus mengisi semua key setting penting:
`site_name`, `site_tagline`, `logo`, `favicon`, `address`, `phone`, `email`, `whatsapp`, `facebook`, `instagram`, `twitter`, `youtube`, `meta_title`, `meta_description`, `meta_keywords`, `google_analytics_id`, `google_maps_embed`, `footer_text`

---

## FASE 12 — Testing & Quality Assurance

### 12.1 Jalankan semua migration dan seeder:

```bash
php artisan migrate:fresh --seed
```

### 12.2 Checklist Frontend:

- [ ] Semua halaman render tanpa error
- [ ] Responsive di mobile (320px), tablet (768px), desktop (1280px)
- [ ] Navigasi header dan footer berfungsi
- [ ] Form kontak berhasil submit dan simpan ke DB
- [ ] Pagination berfungsi di halaman berita dan dosen
- [ ] Search menampilkan hasil yang relevan
- [ ] Download file increment counter dan redirect benar
- [ ] Gambar semua tampil dengan benar via `asset('storage/...')`

### 12.3 Checklist CMS (Filament):

- [ ] Login admin berhasil di `/admin`
- [ ] Semua CRUD resource berfungsi
- [ ] Upload gambar berfungsi dan tersimpan
- [ ] Setting tersimpan dan langsung terlihat di frontend
- [ ] Menu builder mengubah navigasi frontend
- [ ] Filament Shield terpasang dan permission berhasil digenerate dengan `php artisan shield:generate --all`
- [ ] Role `super_admin`, `admin`, `editor`, dan `author` tersedia dan bisa di-assign ke user
- [ ] `super_admin` bisa mengakses Role/Permission management
- [ ] `admin` bisa mengelola setting, menu, konten, dan pesan kontak tanpa akses Role/Permission management
- [ ] `editor` bisa mengelola konten, tetapi tidak bisa mengakses setting, menu, user, role, dan permission
- [ ] `author` hanya bisa membuat/mengedit konten milik sendiri dengan status awal `draft`

### 12.4 Checklist SEO:

- [ ] `<title>` dinamis di setiap halaman
- [ ] `<meta description>` ada di setiap halaman
- [ ] Open Graph tags ada di posts dan pages
- [ ] `/sitemap.xml` accessible dan berisi semua URL
- [ ] `/robots.txt` mengarah ke sitemap yang benar
- [ ] Canonical URL ada di `<head>`
- [ ] JSON-LD schema valid (test di https://validator.schema.org)
- [ ] Semua gambar punya `alt` text
- [ ] `loading="lazy"` ada di gambar non-critical

### 12.5 Final Commands:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
npm run build
```

---

## 📁 STRUKTUR DIREKTORI AKHIR (Ringkasan)

```
unu-web/
├── app/
│   ├── Console/Commands/
│   │   ├── GenerateSitemap.php
│   │   └── ClearExpiredAnnouncements.php
│   ├── Filament/
│   │   ├── Resources/          ← Semua Filament Resources
│   │   ├── Pages/              ← Settings page, dll
│   │   └── Widgets/            ← Dashboard widgets
│   ├── Helpers/
│   │   └── helpers.php
│   ├── Http/
│   │   ├── Controllers/        ← Semua public controllers
│   │   ├── Middleware/
│   │   └── View/Composers/
│   ├── Models/                 ← Semua Eloquent models
│   ├── Mail/
│   └── Services/
│       └── SeoService.php
├── database/
│   ├── migrations/             ← Semua migration files
│   └── seeders/                ← Semua seeders
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php
│   │   ├── components/         ← Blade components
│   │   ├── home.blade.php
│   │   ├── about/
│   │   ├── academic/
│   │   ├── news/
│   │   ├── gallery/
│   │   ├── contact.blade.php
│   │   └── ...
│   ├── css/app.css
│   └── js/app.js
├── routes/
│   └── web.php
└── public/
    ├── robots.txt
    └── ...
```

---

## ⚠️ ATURAN PENTING UNTUK AI AGENT

1. **Jangan skip fase** — Eksekusi berurutan, pastikan setiap fase sukses sebelum lanjut
2. **Error handling** — Setiap query DB wrappping dengan `try-catch` atau `optional()`
3. **Gunakan helper `setting()`** di semua view dan controller, jangan hardcode nama universitas
4. **Upload gambar** — Selalu simpan relative path ke DB (`$table->string('image')`) bukan absolute URL
5. **Keamanan** — Semua input dari user (terutama form kontak) wajib validasi dengan `FormRequest`
6. **Responsif** — Semua view wajib mobile-first menggunakan Tailwind CSS
7. **Tidak boleh ada N+1 query** — Gunakan `with()` eager loading di semua relasi
8. **Cache setting** — Setting dari DB di-cache menggunakan `Cache::remember()` dengan TTL 1 jam
9. **Gunakan queue** — Pengiriman email menggunakan `dispatch(new ContactFormReceived())->onQueue('emails')`
10. **Konsistensi bahasa** — Semua label UI dan pesan sistem menggunakan Bahasa Indonesia

---

## FASE 13 — Desain Frontend Gen Z (Modern · Bold · Immersive)

> **Filosofi Desain**: "Heritage meets hype" — identitas NU yang kuat dibungkus estetika yang relevan untuk Gen Z. Bukan sekadar modern, tapi _memorable_. Kampus yang legacy tapi gak ketinggalan zaman.

### 13.1 Design System

#### Font

Install via Google Fonts CDN di `<head>` layout:

```html
<link
  href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;1,300&display=swap"
  rel="stylesheet"
/>
```

- **Display / Heading**: `Syne` — geometric, futuristik, karakter kuat
- **Body / UI**: `Plus Jakarta Sans` — clean, readable, modern

#### Palet Warna

Set di `tailwind.config.js` dan juga sebagai CSS Variables:

```css
:root {
  --unu-forest: #005f69; /* Teal gelap untuk header/footer dan teks di aksen kuning */
  --unu-emerald: #00a9b7; /* Turquoise utama untuk base visual */
  --unu-lime: #ffc928; /* Kuning cerah untuk CTA, highlight, dan shape besar */
  --unu-gold: #ff9f1c; /* Oranye hangat untuk aksen kedua */
  --unu-cream: #f4fffc; /* Putih segar untuk latar terang */
  --unu-charcoal: #123136; /* Teal-charcoal untuk teks */
  --unu-mist: #d8f7f2; /* Mint mist untuk section background */
  --unu-mint: #31d4b4; /* Mint blazer/accent visual */
  --unu-teal-soft: #08c1cb; /* Turquoise terang untuk gradient */
}
```

```js
// tailwind.config.js
colors: {
  unu: {
    forest:   '#005f69',
    emerald:  '#00a9b7',
    lime:     '#ffc928',
    gold:     '#ff9f1c',
    cream:    '#f4fffc',
    charcoal: '#123136',
    mist:     '#d8f7f2',
    mint:     '#31d4b4',
    'teal-soft': '#08c1cb',
  }
}
```

#### Spacing & Border Radius

```css
/* Pakai border radius besar — Gen Z suka pill dan rounded */
--radius-pill: 9999px;
--radius-card: 20px;
--radius-section: 32px;
```

---

### 13.2 Layout & Prinsip Desain

**Aturan wajib untuk setiap halaman:**

1. **Turquoise hero, light body** - Section hero memakai base turquoise terang (`--unu-emerald`/`--unu-teal-soft`) dengan teks putih dan aksen kuning. Body konten light (`--unu-cream` atau putih).
2. **Oversized typography** — Heading utama minimal `text-6xl` di desktop, `text-4xl` di mobile. Jangan takut teks besar.
3. **Asymmetric grid** — Jangan pakai grid simetris sempurna. Gunakan offset, overlap elemen, dan kolom yang tidak sama lebar.
4. **Grid/noise texture overlay** - Hero dan section gelap boleh memakai grid halus atau subtle grain overlay dengan CSS pseudo-element.
5. **Accent yellow sparingly** - Warna `--unu-lime` sekarang adalah kuning cerah dan hanya untuk: CTA button utama, highlight teks penting, badge, shape besar, dan underline judul. Jangan overuse.
6. **Cards dengan border** — Semua card punya `border: 1px solid rgba(0,0,0,0.08)` dan subtle `box-shadow`. Hover effect: naik + shadow lebih dalam.
7. **Smooth scroll & scroll-triggered animations** — Gunakan `Intersection Observer API` untuk reveal animasi saat elemen masuk viewport.

---

### 13.3 Komponen Detail per Halaman

#### 🏠 Homepage (`home.blade.php`)

**Hero Section:**

```
Background: gradient turquoise (#09c3cd -> #00aebb -> #0495a2) dengan grid halus
Shape aksen: semicircle/ellipse kuning-oranye besar di area bawah/visual
Layout: 2 kolom — kiri teks, kanan gambar kampus dengan clip-path diagonal
Heading: "Cetak Generasi
          Unggul &
          Berakhlak."
          (font Syne, 80px, warna putih, kata "Unggul" highlight kuning)
Sub-heading: font Plus Jakarta Sans, 18px, warna putih/78%
Badge di atas heading: pill shape, border putih/70, dot kuning, teks "Akreditasi Unggul"
CTA Buttons:
  - Primary: bg kuning, teks teal gelap, rounded-full, px-8 py-4, font semibold
  - Secondary: border putih/40, teks putih, rounded-full, hover fill putih/10
Scroll indicator: animated bouncing arrow di bawah
```

**Stats Counter Strip:**

```
Background: gradient kuning ke oranye (`--unu-lime` -> `--unu-gold`)
Layout: 4 kolom horizontal, full width
Teks: warna teal gelap, font Syne bold
Angka besar + label kecil (animasi count-up saat scroll masuk)
Contoh: "12.400+ Mahasiswa", "340+ Dosen", "24 Program Studi", "30+ Tahun"
```

**Berita Terbaru:**

```
Layout: 1 featured card besar (kiri, 60%) + 2 card kecil vertikal (kanan, 40%)
Featured card: gambar full-cover, overlay gradient bawah, teks putih
Card kecil: gambar thumbnail + teks, border radius 20px
Category badge: pill, warna dari database (setiap kategori punya warna)
"Lihat Semua Berita" button: outlined, hover fill emerald
```

**Program Studi Section:**

```
Background: --unu-mist
Heading: besar, dengan garis bawah animasi dari kiri ke kanan (CSS underline animation)
Layout: horizontal scroll carousel di mobile, 3-column grid di desktop
Card prodi:
  - Atas: icon/emoji representatif + warna aksen per fakultas
  - Tengah: nama prodi, nama fakultas kecil
  - Bawah: badge akreditasi + badge jenjang (S1, S2)
  - Hover: card lift + border warna emerald
```

**Tentang UNU Section:**

```
Layout: Split - kiri gambar kampus dengan frame geometric (border kuning-oranye),
        kanan teks + stat mini + CTA
Background kiri: gradient turquoise dengan shape kuning-oranye dan pattern grid halus
Quote besar dari Hadratussyaikh dengan tanda kutip berukuran jumbo (dekoratif)
```

**Galeri Preview:**

```
Masonry grid 3-4 kolom, gambar dengan rounded-2xl
Hover: overlay turquoise semi-transparan + icon zoom
"Lihat Galeri" button
```

**Mitra Section:**

```
Background: putih
Logo strip animasi marquee (infinite scroll horizontal)
Filter: Semua | Industri | Pemerintah | Internasional
```

---

#### 📰 Halaman Berita (`news/index.blade.php`)

```
Hero kecil: background turquoise/teal, heading "Berita & Artikel", breadcrumb
Filter bar: sticky di bawah header, pill buttons per kategori (warna berbeda tiap kategori)
Grid: 3 kolom desktop, 2 tablet, 1 mobile
Card layout:
  - Gambar 16:9 ratio, rounded-t-2xl
  - Category badge (pill, warna kategori)
  - Judul: font Syne, 2 baris max, clamp ellipsis
  - Tanggal + estimasi baca
  - Author avatar kecil + nama
Pagination: angka dengan style pill, hover/active bg kuning
```

#### 📄 Halaman Detail Berita (`news/show.blade.php`)

```
Hero: judul artikel BESAR (Syne, 52px), metadata di bawah, gambar featured full-width di bawah hero
Layout artikel: max-width 720px centered, font Plus Jakarta Sans 18px, line-height 1.8
Typography artikel (Tailwind Typography prose):
  - h2: Syne, border-left 4px kuning, pl-4
  - blockquote: bg mist, border-l emerald, italic
  - img: rounded-2xl, shadow
Sidebar (sticky): daftar isi otomatis, artikel terkait, share buttons
Share buttons: Instagram-style pill buttons dengan ikon
```

#### 🎓 Halaman Akademik / Prodi (`academic/prodi.blade.php`)

```
Hero: background pattern khas + nama prodi BESAR
Info bar: jenjang, akreditasi, ketua prodi — horizontal strip
Tab navigation: Tentang | Kurikulum | Dosen | Fasilitas | Alumni
  (Pill tabs, active: bg kuning text teal gelap)
Dosen section: card grid dengan foto bulat, nama, jabatan
CTA pendaftaran: sticky bottom bar di mobile
```

#### 📬 Halaman Kontak (`contact.blade.php`)

```
Layout: 2 kolom — kiri form, kanan info kontak + peta
Form style:
  - Label melayang (floating label animation)
  - Border bawah saja (underline style), bukan border semua sisi
  - Focus: border kuning, label naik dengan warna kuning
  - Submit button: bg kuning, text teal gelap, rounded-full, full-width, icon send
Info kontak: icon circle bg kuning/20 + teks
Peta: embed Google Maps dengan rounded-2xl
```

---

### 13.4 Header & Navigasi

```
Position: sticky top, backdrop-blur-md, bg teal-gelap/90
Logo: kiri — icon NU + teks nama universitas (Syne)
Nav links: tengah - font Plus Jakarta Sans medium, warna putih/80, hover putih
  Dropdown: glassmorphism card (backdrop-blur, bg teal-gelap/70, border putih/10, rounded-2xl)
CTA: kanan - tombol "Daftar Sekarang" pill bg kuning text teal gelap
Mobile hamburger: animated 3-bar → X morph
Mobile menu: full-screen overlay, bg teal gelap, links besar Syne font
```

---

### 13.5 Footer

```
Background: --unu-charcoal (hampir hitam)
Layout: 4 kolom — Logo+desc | Tautan | Akademik | Kontak
Logo NU besar di kiri dengan tagline
Sosial media: icon circle outline, hover fill kuning
Newsletter mini: input pill + button "Subscribe" inline
Bottom bar: copyright + link kebijakan privasi
Warna teks: putih/60%, heading putih/100%
Motif arabesque NU sangat subtle di pojok (SVG, opacity 3%)
```

---

### 13.6 Micro-interactions & Animasi

Implementasikan menggunakan vanilla JS + CSS (tidak perlu library berat):

```javascript
// resources/js/app.js — tambahkan semua ini:

// 1. Scroll reveal dengan Intersection Observer
const revealObserver = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("revealed");
      }
    });
  },
  { threshold: 0.1 },
);
document.querySelectorAll("[data-reveal]").forEach((el) => revealObserver.observe(el));

// 2. Count-up animasi untuk stats
function animateCount(el) {
  const target = parseInt(el.dataset.count);
  const duration = 2000;
  const step = target / (duration / 16);
  let current = 0;
  const timer = setInterval(() => {
    current += step;
    if (current >= target) {
      current = target;
      clearInterval(timer);
    }
    el.textContent = Math.floor(current).toLocaleString("id-ID") + (el.dataset.suffix || "");
  }, 16);
}

// 3. Header scroll behavior
window.addEventListener("scroll", () => {
  const header = document.getElementById("main-header");
  header.classList.toggle("scrolled", window.scrollY > 50);
});

// 4. Smooth marquee mitra
// CSS animation infinite (tidak perlu JS)

// 5. Active nav link berdasarkan current URL
document.querySelectorAll("nav a").forEach((link) => {
  if (link.href === window.location.href) link.classList.add("active");
});
```

```css
/* resources/css/app.css — animasi reveal */
[data-reveal] {
  opacity: 0;
  transform: translateY(24px);
  transition:
    opacity 0.6s ease,
    transform 0.6s ease;
}
[data-reveal].revealed {
  opacity: 1;
  transform: translateY(0);
}
[data-reveal-delay="1"] {
  transition-delay: 0.1s;
}
[data-reveal-delay="2"] {
  transition-delay: 0.2s;
}
[data-reveal-delay="3"] {
  transition-delay: 0.3s;
}

/* Noise texture overlay */
.noise-overlay::after {
  content: "";
  position: absolute;
  inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='1'/%3E%3C/svg%3E");
  opacity: 0.04;
  pointer-events: none;
  mix-blend-mode: overlay;
}

/* Header scrolled state */
#main-header.scrolled {
  box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
}

/* Marquee mitra */
.marquee-track {
  display: flex;
  animation: marquee 30s linear infinite;
  width: max-content;
}
@keyframes marquee {
  from {
    transform: translateX(0);
  }
  to {
    transform: translateX(-50%);
  }
}

/* Card hover lift */
.card-lift {
  transition:
    transform 0.3s ease,
    box-shadow 0.3s ease;
}
.card-lift:hover {
  transform: translateY(-6px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
}

/* Floating label */
.float-label-group {
  position: relative;
}
.float-label-group input:focus ~ label,
.float-label-group input:not(:placeholder-shown) ~ label {
  transform: translateY(-1.5rem) scale(0.8);
  color: var(--unu-lime);
}
```

---

### 13.7 Checklist Desain Gen Z

- [ ] Font `Syne` tampil di semua heading (inspect di browser)
- [ ] Warna kuning `#ffc928` muncul di: hero badge, CTA button, stats strip, hover state, dan shape dekoratif
- [ ] Hero section memakai base turquoise terang dengan teks putih dan aksen kuning-oranye
- [ ] Noise texture terlihat subtle di section gelap
- [ ] Scroll reveal berjalan smooth (tidak kedip/flash)
- [ ] Stats counter animasi saat pertama kali terlihat di viewport
- [ ] Header sticky dengan backdrop blur saat scroll
- [ ] Mobile hamburger menu berfungsi dengan animasi
- [ ] Cards punya hover lift effect
- [ ] Form kontak pakai floating label animation
- [ ] Marquee mitra berjalan infinite tanpa jeda
- [ ] Semua gambar pakai `object-cover` dalam container fixed ratio
- [ ] Tidak ada font Arial/Roboto/Inter muncul di inspect browser
- [ ] Turquoise hero + light body konsisten di semua halaman
- [ ] Responsive sempurna di 375px (iPhone SE) dan 1440px (desktop)

---

_Fase 13 ini adalah panduan visual dan implementasi desain. Eksekusi bersamaan atau setelah Fase 5 (Frontend Public). Gunakan preview HTML yang disertakan sebagai referensi visual utama._
