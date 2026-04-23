<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\ContactMessage;
use App\Models\Download;
use App\Models\Faq;
use App\Models\Gallery;
use App\Models\Partner;
use App\Models\Slider;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAnnouncements();
        $this->seedGalleries();
        $this->seedSliders();
        $this->seedPartners();
        $this->seedTestimonials();
        $this->seedFaqs();
        $this->seedDownloads();
        $this->seedContactMessages();
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
