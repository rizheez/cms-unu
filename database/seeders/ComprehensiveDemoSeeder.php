<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AcademicCalendar;
use App\Models\Announcement;
use App\Models\ContactMessage;
use App\Models\Download;
use App\Models\Faculty;
use App\Models\Faq;
use App\Models\Gallery;
use App\Models\Lecturer;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\StudyProgram;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class ComprehensiveDemoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = $this->seedUsers();
        $this->seedSettings();
        $this->seedMenus();
        $this->seedPages();

        $categories = $this->seedPostCategories();
        $this->seedPosts($admin, $categories);
        $this->seedAcademics();
        $this->seedAcademicCalendars();
        $this->seedAnnouncements();
        $this->seedGalleries();
        $this->seedSliders();
        $this->seedPartners();
        $this->seedTestimonials();
        $this->seedFaqs();
        $this->seedDownloads();
        $this->seedContactMessages();
    }

    private function seedUsers(): User
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@unu.test'],
            [
                'name' => 'Administrator UNU',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );

        $role = Role::query()->firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        $admin->assignRole($role);

        return $admin;
    }

    private function seedSettings(): void
    {
        collect([
            ['key' => 'site_name', 'value' => 'Universitas Nahdlatul Ulama', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'Kampus Unggul, Berkarakter, dan Berdampak', 'group' => 'general'],
            ['key' => 'site_address', 'value' => 'Jl. Pendidikan No. 1, Samarinda, Kalimantan Timur', 'group' => 'contact'],
            ['key' => 'site_phone', 'value' => '+62 812 4000 2026', 'group' => 'contact'],
            ['key' => 'site_email', 'value' => 'info@unu.ac.id', 'group' => 'contact'],
            ['key' => 'site_logo', 'value' => 'seed/brand/logo-unu.png', 'group' => 'appearance'],
            ['key' => 'site_favicon', 'value' => 'seed/brand/favicon.png', 'group' => 'appearance'],
            ['key' => 'social_facebook', 'value' => 'https://facebook.com/unu', 'group' => 'social'],
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/unu', 'group' => 'social'],
            ['key' => 'social_youtube', 'value' => 'https://youtube.com/@unu', 'group' => 'social'],
            ['key' => 'social_tiktok', 'value' => 'https://tiktok.com/@unu', 'group' => 'social'],
            ['key' => 'vision', 'value' => 'Menjadi universitas unggul yang melahirkan generasi profesional, berakhlak, dan berdaya saing global berlandaskan nilai Ahlussunnah wal Jamaah.', 'group' => 'profile'],
            ['key' => 'mission', 'value' => 'Menyelenggarakan pendidikan transformatif, riset aplikatif, dan pengabdian masyarakat yang menjawab kebutuhan zaman.', 'group' => 'profile'],
            ['key' => 'accreditation', 'value' => 'Baik Sekali', 'group' => 'profile'],
            ['key' => 'meta_title', 'value' => 'Universitas Nahdlatul Ulama | Kampus Unggul dan Berakhlak', 'group' => 'seo'],
            ['key' => 'meta_description', 'value' => 'Website resmi Universitas Nahdlatul Ulama: informasi akademik, berita kampus, pendaftaran, layanan mahasiswa, dan kolaborasi mitra.', 'group' => 'seo'],
            ['key' => 'meta_keywords', 'value' => 'UNU, Universitas Nahdlatul Ulama, Kampus NU, Perguruan Tinggi Islam, Kampus Kalimantan', 'group' => 'seo'],
        ])->each(fn (array $setting): Setting => Setting::query()->updateOrCreate(
            ['key' => $setting['key']],
            ['value' => $setting['value'], 'group' => $setting['group']],
        ));
    }

    private function seedMenus(): void
    {
        $header = Menu::query()->updateOrCreate(
            ['location' => 'header'],
            ['name' => 'Menu Utama', 'is_active' => true],
        );

        $footer = Menu::query()->updateOrCreate(
            ['location' => 'footer'],
            ['name' => 'Menu Footer', 'is_active' => true],
        );

        $this->syncMenuItems($header, [
            ['label' => 'Beranda', 'url' => '/', 'order' => 1],
            ['label' => 'Tentang UNU', 'url' => '/tentang/profil', 'order' => 2, 'children' => [
                ['label' => 'Profil', 'url' => '/tentang/profil', 'order' => 1],
                ['label' => 'Visi & Misi', 'url' => '/tentang/visi-misi', 'order' => 2],
                ['label' => 'Sejarah', 'url' => '/tentang/sejarah', 'order' => 3],
            ]],
            ['label' => 'Akademik', 'url' => '/akademik', 'order' => 3, 'children' => [
                ['label' => 'Fakultas', 'url' => '/akademik/fakultas', 'order' => 1],
                ['label' => 'Direktori Dosen', 'url' => '/akademik/dosen', 'order' => 2],
                ['label' => 'Kalender Akademik', 'url' => '/akademik/kalender', 'order' => 3],
            ]],
            ['label' => 'Berita', 'url' => '/berita', 'order' => 4],
            ['label' => 'Galeri', 'url' => '/galeri', 'order' => 5],
            ['label' => 'Kontak', 'url' => '/kontak', 'order' => 6],
        ]);

        $this->syncMenuItems($footer, [
            ['label' => 'Unduhan', 'url' => '/unduhan', 'order' => 1],
            ['label' => 'FAQ', 'url' => '/faq', 'order' => 2],
            ['label' => 'Pengumuman', 'url' => '/pengumuman', 'order' => 3],
            ['label' => 'Pencarian', 'url' => '/cari', 'order' => 4],
        ]);
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    private function syncMenuItems(Menu $menu, array $items, ?int $parentId = null): void
    {
        foreach ($items as $item) {
            $menuItem = $menu->items()->updateOrCreate(
                ['label' => $item['label'], 'parent_id' => $parentId],
                [
                    'url' => $item['url'],
                    'target' => '_self',
                    'order' => $item['order'],
                ],
            );

            if (isset($item['children']) && is_array($item['children'])) {
                $this->syncMenuItems($menu, $item['children'], $menuItem->id);
            }
        }
    }

    private function seedPages(): void
    {
        collect([
            [
                'title' => 'Profil Universitas',
                'slug' => 'profil-universitas',
                'content' => '<p>Universitas Nahdlatul Ulama adalah kampus yang menggabungkan tradisi keilmuan, nilai keislaman, dan kebutuhan industri modern.</p><p>Mahasiswa belajar melalui kelas, riset, komunitas, dan proyek berdampak untuk masyarakat.</p>',
            ],
            [
                'title' => 'Penerimaan Mahasiswa Baru',
                'slug' => 'penerimaan-mahasiswa-baru',
                'content' => '<p>Pendaftaran mahasiswa baru dibuka untuk jalur prestasi, reguler, dan kemitraan pesantren.</p><p>Calon mahasiswa dapat memilih program studi sesuai minat dan potensi masa depan.</p>',
            ],
            [
                'title' => 'Layanan Mahasiswa',
                'slug' => 'layanan-mahasiswa',
                'content' => '<p>Layanan mahasiswa meliputi akademik, beasiswa, konseling, karier, organisasi, dan kegiatan pengembangan diri.</p>',
            ],
        ])->each(fn (array $page): Page => Page::query()->updateOrCreate(
            ['slug' => $page['slug']],
            [
                ...$page,
                'template' => 'default',
                'status' => 'published',
                'meta_title' => $page['title'],
                'meta_description' => Str::limit(strip_tags($page['content']), 150),
                'is_in_sitemap' => true,
                'published_at' => now()->subDays(20),
            ],
        ));
    }

    /**
     * @return array<string, PostCategory>
     */
    private function seedPostCategories(): array
    {
        return collect([
            ['name' => 'Kampus', 'slug' => 'kampus', 'color' => '#00a9b7'],
            ['name' => 'Prestasi', 'slug' => 'prestasi', 'color' => '#ffc928'],
            ['name' => 'Riset', 'slug' => 'riset', 'color' => '#31d4b4'],
            ['name' => 'Kemahasiswaan', 'slug' => 'kemahasiswaan', 'color' => '#ff9f1c'],
        ])->mapWithKeys(fn (array $category): array => [
            $category['slug'] => PostCategory::query()->updateOrCreate(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'description' => 'Kumpulan artikel kategori '.$category['name'].'.',
                    'color' => $category['color'],
                ],
            ),
        ])->all();
    }

    /**
     * @param  array<string, PostCategory>  $categories
     */
    private function seedPosts(User $admin, array $categories): void
    {
        collect([
            ['category' => 'kampus', 'title' => 'UNU Perkuat Transformasi Digital Layanan Akademik', 'excerpt' => 'Portal akademik baru memudahkan mahasiswa mengakses jadwal, nilai, dan layanan kampus.', 'featured_image' => 'seed/posts/transformasi-digital.jpg', 'published_at' => now()->subDays(2)],
            ['category' => 'prestasi', 'title' => 'Mahasiswa UNU Raih Juara Inovasi Sosial Tingkat Nasional', 'excerpt' => 'Tim mahasiswa mengembangkan platform pendampingan belajar untuk komunitas pesisir.', 'featured_image' => 'seed/posts/inovasi-sosial.jpg', 'published_at' => now()->subDays(5)],
            ['category' => 'riset', 'title' => 'Dosen UNU Kembangkan Riset Energi Terbarukan Berbasis Komunitas', 'excerpt' => 'Riset kolaboratif ini menargetkan kemandirian energi desa melalui teknologi tepat guna.', 'featured_image' => 'seed/posts/riset-energi.jpg', 'published_at' => now()->subDays(8)],
            ['category' => 'kemahasiswaan', 'title' => 'Pekan Ta’aruf Mahasiswa Baru Hadir dengan Konsep Kreatif dan Inklusif', 'excerpt' => 'Mahasiswa baru mengenal budaya akademik, organisasi, dan nilai ke-NU-an secara interaktif.', 'featured_image' => 'seed/posts/pekan-taaruf.jpg', 'published_at' => now()->subDays(10)],
            ['category' => 'kampus', 'title' => 'UNU Buka Klinik Karier untuk Persiapan Magang dan Dunia Kerja', 'excerpt' => 'Klinik karier membantu mahasiswa menyusun CV, portofolio, dan strategi wawancara.', 'featured_image' => 'seed/posts/klinik-karier.jpg', 'published_at' => now()->subDays(14)],
        ])->each(function (array $post, int $index) use ($admin, $categories): void {
            $content = '<p>'.$post['excerpt'].'</p><p>Kegiatan ini menjadi bagian dari komitmen kampus untuk menghadirkan pendidikan yang dekat dengan kebutuhan mahasiswa, masyarakat, dan dunia profesional.</p><p>Kolaborasi lintas fakultas terus diperkuat agar inovasi kampus terasa manfaatnya secara nyata.</p>';

            Post::query()->updateOrCreate(
                ['slug' => Str::slug($post['title'])],
                [
                    'post_category_id' => $categories[$post['category']]->id,
                    'user_id' => $admin->id,
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'content' => $content,
                    'featured_image' => $post['featured_image'],
                    'status' => 'published',
                    'is_featured' => $index < 3,
                    'views' => 120 + ($index * 37),
                    'meta_title' => $post['title'],
                    'meta_description' => $post['excerpt'],
                    'is_in_sitemap' => true,
                    'published_at' => $post['published_at'],
                ],
            );
        });
    }

    private function seedAcademics(): void
    {
        collect([
            ['slug' => 'fakultas-teknologi-dan-sains', 'name' => 'Fakultas Teknologi dan Sains', 'short_name' => 'FTS', 'dean_name' => 'Dr. Ahmad Fauzi, M.Kom.', 'email' => 'fts@unu.ac.id', 'image' => 'seed/faculties/teknologi-sains.jpg', 'programs' => [
                ['name' => 'Informatika', 'degree_level' => 'S1', 'head_name' => 'Nur Aisyah, M.Kom.', 'accreditation' => 'Baik Sekali'],
                ['name' => 'Sistem Informasi', 'degree_level' => 'S1', 'head_name' => 'Rizky Maulana, M.MSI.', 'accreditation' => 'Baik'],
                ['name' => 'Teknologi Pangan', 'degree_level' => 'S1', 'head_name' => 'Dr. Laila Rahma, M.Si.', 'accreditation' => 'Baik Sekali'],
            ]],
            ['slug' => 'fakultas-ekonomi-dan-bisnis', 'name' => 'Fakultas Ekonomi dan Bisnis', 'short_name' => 'FEB', 'dean_name' => 'Dr. Siti Mardiah, M.M.', 'email' => 'feb@unu.ac.id', 'image' => 'seed/faculties/ekonomi-bisnis.jpg', 'programs' => [
                ['name' => 'Manajemen', 'degree_level' => 'S1', 'head_name' => 'Hendra Wijaya, M.M.', 'accreditation' => 'Baik Sekali'],
                ['name' => 'Akuntansi', 'degree_level' => 'S1', 'head_name' => 'Dewi Kartika, M.Ak.', 'accreditation' => 'Baik'],
            ]],
            ['slug' => 'fakultas-keguruan-dan-ilmu-pendidikan', 'name' => 'Fakultas Keguruan dan Ilmu Pendidikan', 'short_name' => 'FKIP', 'dean_name' => 'Dr. M. Hasyim, M.Pd.', 'email' => 'fkip@unu.ac.id', 'image' => 'seed/faculties/fkip.jpg', 'programs' => [
                ['name' => 'Pendidikan Guru Sekolah Dasar', 'degree_level' => 'S1', 'head_name' => 'Sri Wahyuni, M.Pd.', 'accreditation' => 'Baik Sekali'],
                ['name' => 'Pendidikan Bahasa Inggris', 'degree_level' => 'S1', 'head_name' => 'Fajar Ramadhan, M.Pd.', 'accreditation' => 'Baik'],
            ]],
        ])->each(function (array $faculty, int $index): void {
            $model = Faculty::query()->updateOrCreate(
                ['slug' => $faculty['slug']],
                [
                    'name' => $faculty['name'],
                    'short_name' => $faculty['short_name'],
                    'dean_name' => $faculty['dean_name'],
                    'email' => $faculty['email'],
                    'phone' => '+62 812 4000 20'.($index + 10),
                    'description' => $faculty['name'].' menaungi pembelajaran, riset, dan kolaborasi yang relevan dengan kebutuhan daerah dan industri.',
                    'image' => $faculty['image'],
                    'accreditation' => 'Baik Sekali',
                    'order' => $index + 1,
                    'is_active' => true,
                ],
            );

            foreach ($faculty['programs'] as $programIndex => $program) {
                $studyProgram = StudyProgram::query()->updateOrCreate(
                    ['slug' => Str::slug($program['name'])],
                    [
                        'faculty_id' => $model->id,
                        'name' => $program['name'],
                        'degree_level' => $program['degree_level'],
                        'head_name' => $program['head_name'],
                        'description' => 'Program studi '.$program['name'].' membekali mahasiswa dengan fondasi keilmuan, praktik lapangan, dan portofolio proyek.',
                        'image' => 'seed/programs/'.Str::slug($program['name']).'.jpg',
                        'accreditation' => $program['accreditation'],
                        'order' => $programIndex + 1,
                        'is_active' => true,
                    ],
                );

                $this->seedLecturers($model, $studyProgram, $programIndex);
            }
        });
    }

    private function seedLecturers(Faculty $faculty, StudyProgram $studyProgram, int $offset): void
    {
        collect([
            ['name' => (string) $studyProgram->head_name, 'position' => 'Ketua Program Studi', 'education_level' => 'S3'],
            ['name' => 'Dosen '.$studyProgram->name.' '.($offset + 1), 'position' => 'Dosen Tetap', 'education_level' => 'S2'],
        ])->each(function (array $lecturer, int $index) use ($faculty, $studyProgram): void {
            Lecturer::query()->updateOrCreate(
                ['slug' => Str::slug($lecturer['name'])],
                [
                    'faculty_id' => $faculty->id,
                    'study_program_id' => $studyProgram->id,
                    'name' => $lecturer['name'],
                    'nidn' => '11'.str_pad((string) ($studyProgram->id * 10 + $index), 8, '0', STR_PAD_LEFT),
                    'email' => Str::slug($lecturer['name'], '.').'@unu.ac.id',
                    'position' => $lecturer['position'],
                    'education_level' => $lecturer['education_level'],
                    'bio' => $lecturer['name'].' aktif mengajar, membimbing mahasiswa, dan mengembangkan riset terapan di bidang '.$studyProgram->name.'.',
                    'photo' => 'seed/lecturers/'.Str::slug($lecturer['name']).'.jpg',
                    'expertise' => $studyProgram->name.', Pembelajaran Berbasis Proyek, Riset Terapan',
                    'order' => $index + 1,
                    'is_active' => true,
                ],
            );
        });
    }

    private function seedAcademicCalendars(): void
    {
        collect([
            ['title' => 'Pendaftaran Mahasiswa Baru Gelombang 1', 'category' => 'penerimaan', 'start_date' => now()->addDays(10), 'end_date' => now()->addDays(45), 'color' => '#00a9b7'],
            ['title' => 'Pengisian KRS Semester Ganjil', 'category' => 'perkuliahan', 'start_date' => now()->addDays(35), 'end_date' => now()->addDays(42), 'color' => '#31d4b4'],
            ['title' => 'Awal Perkuliahan Semester Ganjil', 'category' => 'perkuliahan', 'start_date' => now()->addDays(50), 'end_date' => null, 'color' => '#ffc928'],
            ['title' => 'Ujian Tengah Semester', 'category' => 'ujian', 'start_date' => now()->addDays(110), 'end_date' => now()->addDays(118), 'color' => '#ff9f1c'],
            ['title' => 'Wisuda Sarjana dan Diploma', 'category' => 'wisuda', 'start_date' => now()->addDays(160), 'end_date' => null, 'color' => '#005f69'],
        ])->each(fn (array $event): AcademicCalendar => AcademicCalendar::query()->updateOrCreate(
            ['title' => $event['title']],
            [
                'description' => 'Agenda '.$event['title'].' untuk sivitas akademika UNU.',
                'start_date' => Carbon::parse($event['start_date'])->toDateString(),
                'end_date' => $event['end_date'] ? Carbon::parse($event['end_date'])->toDateString() : null,
                'category' => $event['category'],
                'color' => $event['color'],
            ],
        ));
    }

    private function seedAnnouncements(): void
    {
        collect([
            ['title' => 'Pendaftaran Beasiswa Prestasi Dibuka', 'type' => 'success', 'is_popup' => true, 'content' => 'Mahasiswa aktif dapat mendaftar beasiswa prestasi sampai akhir bulan ini.'],
            ['title' => 'Pemeliharaan Sistem Akademik', 'type' => 'warning', 'is_popup' => false, 'content' => 'Sistem akademik tidak dapat diakses pada Sabtu pukul 22.00 sampai 02.00 WITA.'],
            ['title' => 'Kuliah Umum Transformasi Digital Kampus', 'type' => 'info', 'is_popup' => false, 'content' => 'Kuliah umum terbuka untuk seluruh mahasiswa dan dosen di aula utama.'],
        ])->each(fn (array $announcement): Announcement => Announcement::query()->updateOrCreate(
            ['slug' => Str::slug($announcement['title'])],
            [
                'title' => $announcement['title'],
                'content' => $announcement['content'],
                'type' => $announcement['type'],
                'is_popup' => $announcement['is_popup'],
                'is_active' => true,
                'start_at' => now()->subDay(),
                'end_at' => now()->addDays(30),
            ],
        ));
    }

    private function seedGalleries(): void
    {
        collect([
            ['title' => 'Kegiatan Mahasiswa', 'type' => 'photo'],
            ['title' => 'Kampus dan Fasilitas', 'type' => 'photo'],
            ['title' => 'Kolaborasi Mitra', 'type' => 'photo'],
        ])->each(function (array $gallery): void {
            $model = Gallery::query()->updateOrCreate(
                ['slug' => Str::slug($gallery['title'])],
                [
                    'title' => $gallery['title'],
                    'description' => 'Dokumentasi '.$gallery['title'].' Universitas Nahdlatul Ulama.',
                    'cover_image' => 'seed/galleries/'.Str::slug($gallery['title']).'-cover.jpg',
                    'type' => $gallery['type'],
                    'is_active' => true,
                ],
            );

            foreach (range(1, 4) as $order) {
                $model->items()->updateOrCreate(
                    ['order' => $order],
                    [
                        'image' => 'seed/galleries/'.Str::slug($gallery['title']).'-'.$order.'.jpg',
                        'video_url' => null,
                        'caption' => 'Dokumentasi '.$gallery['title'].' #'.$order,
                    ],
                );
            }
        });
    }

    private function seedSliders(): void
    {
        collect([
            ['title' => 'Cetak Generasi Unggul & Berakhlak', 'subtitle' => 'Belajar, bertumbuh, dan berdampak bersama kampus Nahdlatul Ulama.', 'image' => 'seed/sliders/hero-kampus.jpg', 'button_text' => 'Daftar Sekarang', 'button_url' => '/penerimaan-mahasiswa-baru'],
            ['title' => 'Akademik yang Dekat dengan Industri', 'subtitle' => 'Kurikulum adaptif, dosen praktisi, dan proyek nyata untuk masa depan.', 'image' => 'seed/sliders/hero-akademik.jpg', 'button_text' => 'Lihat Program Studi', 'button_url' => '/akademik/fakultas'],
            ['title' => 'Ruang Kreatif untuk Mahasiswa', 'subtitle' => 'Organisasi, riset, kompetisi, dan komunitas tumbuh dalam satu ekosistem.', 'image' => 'seed/sliders/hero-mahasiswa.jpg', 'button_text' => 'Baca Berita', 'button_url' => '/berita'],
        ])->each(fn (array $slider, int $index): Slider => Slider::query()->updateOrCreate(
            ['title' => $slider['title']],
            [
                ...$slider,
                'order' => $index + 1,
                'is_active' => true,
            ],
        ));
    }

    private function seedPartners(): void
    {
        collect([
            ['name' => 'Nahdlatul Ulama', 'logo' => 'seed/partners/nu.png', 'website' => 'https://nu.or.id'],
            ['name' => 'Bank Syariah Indonesia', 'logo' => 'seed/partners/bsi.png', 'website' => 'https://bankbsi.co.id'],
            ['name' => 'Pemerintah Provinsi Kalimantan Timur', 'logo' => 'seed/partners/kaltim.png', 'website' => 'https://kaltimprov.go.id'],
            ['name' => 'Google Developer Student Clubs', 'logo' => 'seed/partners/gdsc.png', 'website' => 'https://developers.google.com/community/gdsc'],
        ])->each(fn (array $partner, int $index): Partner => Partner::query()->updateOrCreate(
            ['name' => $partner['name']],
            [
                ...$partner,
                'order' => $index + 1,
                'is_active' => true,
            ],
        ));
    }

    private function seedTestimonials(): void
    {
        collect([
            ['name' => 'Aulia Rahman', 'position' => 'Alumni Informatika', 'content' => 'UNU memberi saya ruang membangun portofolio sejak semester awal. Pengalaman proyeknya sangat membantu saat masuk dunia kerja.'],
            ['name' => 'Nadia Putri', 'position' => 'Mahasiswa Manajemen', 'content' => 'Dosen mudah ditemui, komunitas aktif, dan banyak kegiatan yang bikin saya percaya diri.'],
            ['name' => 'H. Mahmud', 'position' => 'Orang Tua Mahasiswa', 'content' => 'Kami merasa tenang karena kampus memperhatikan akhlak, akademik, dan masa depan anak.'],
        ])->each(fn (array $testimonial): Testimonial => Testimonial::query()->updateOrCreate(
            ['name' => $testimonial['name']],
            [
                ...$testimonial,
                'photo' => 'seed/testimonials/'.Str::slug($testimonial['name']).'.jpg',
                'rating' => 5,
                'is_active' => true,
            ],
        ));
    }

    private function seedFaqs(): void
    {
        collect([
            ['category' => 'Pendaftaran', 'question' => 'Kapan pendaftaran mahasiswa baru dibuka?', 'answer' => 'Pendaftaran dibuka dalam beberapa gelombang. Jadwal terbaru dapat dilihat pada halaman penerimaan mahasiswa baru.'],
            ['category' => 'Pendaftaran', 'question' => 'Apakah tersedia jalur beasiswa?', 'answer' => 'Ya, tersedia jalur beasiswa prestasi, tahfidz, dan kemitraan sesuai ketentuan kampus.'],
            ['category' => 'Akademik', 'question' => 'Apakah perkuliahan tersedia kelas malam?', 'answer' => 'Beberapa program studi menyediakan kelas fleksibel sesuai kebijakan fakultas.'],
            ['category' => 'Layanan', 'question' => 'Bagaimana cara menghubungi layanan akademik?', 'answer' => 'Mahasiswa dapat menghubungi fakultas, layanan akademik pusat, atau mengirim pesan melalui halaman kontak.'],
        ])->each(fn (array $faq, int $index): Faq => Faq::query()->updateOrCreate(
            ['question' => $faq['question']],
            [
                ...$faq,
                'order' => $index + 1,
                'is_active' => true,
            ],
        ));
    }

    private function seedDownloads(): void
    {
        collect([
            ['title' => 'Brosur Penerimaan Mahasiswa Baru', 'category' => 'PMB', 'file' => 'seed/downloads/brosur-pmb.pdf'],
            ['title' => 'Panduan Akademik Mahasiswa', 'category' => 'Akademik', 'file' => 'seed/downloads/panduan-akademik.pdf'],
            ['title' => 'Kalender Akademik Tahun Berjalan', 'category' => 'Akademik', 'file' => 'seed/downloads/kalender-akademik.pdf'],
            ['title' => 'Formulir Pengajuan Beasiswa', 'category' => 'Kemahasiswaan', 'file' => 'seed/downloads/formulir-beasiswa.pdf'],
        ])->each(fn (array $download): Download => Download::query()->updateOrCreate(
            ['slug' => Str::slug($download['title'])],
            [
                ...$download,
                'description' => 'Dokumen '.$download['title'].' dapat digunakan sebagai referensi awal.',
                'download_count' => 0,
                'is_active' => true,
            ],
        ));
    }

    private function seedContactMessages(): void
    {
        collect([
            ['name' => 'Raka Pratama', 'email' => 'raka@example.com', 'phone' => '081240001111', 'subject' => 'Informasi PMB', 'message' => 'Saya ingin bertanya tentang jalur pendaftaran dan beasiswa.', 'status' => 'unread'],
            ['name' => 'Siti Aminah', 'email' => 'siti@example.com', 'phone' => '081240002222', 'subject' => 'Kerja Sama Sekolah', 'message' => 'Sekolah kami ingin menjajaki kerja sama sosialisasi kampus.', 'status' => 'read', 'read_at' => now()->subDay()],
        ])->each(fn (array $message): ContactMessage => ContactMessage::query()->updateOrCreate(
            ['email' => $message['email'], 'subject' => $message['subject']],
            $message,
        ));
    }
}
