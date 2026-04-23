<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class DemoSettingsSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            ['key' => 'site_name', 'value' => 'Universitas Nahdlatul Ulama', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'Kampus Unggul, Berkarakter, dan Berdampak', 'group' => 'general'],
            ['key' => 'site_address', 'value' => 'Jl. Pendidikan No. 1, Samarinda, Kalimantan Timur', 'group' => 'contact'],
            ['key' => 'site_phone', 'value' => '+62 812 4000 2026', 'group' => 'contact'],
            ['key' => 'site_email', 'value' => 'info@unu.ac.id', 'group' => 'contact'],
            ['key' => 'site_logo', 'value' => 'seed/brand/logo-unu.png', 'group' => 'appearance'],
            ['key' => 'site_favicon', 'value' => 'seed/brand/favicon.png', 'group' => 'appearance'],
            ['key' => 'ticker_text', 'value' => 'Pendaftaran Mahasiswa Baru telah dibuka - Daftarkan dirimu sekarang! - Wisuda Sarjana akan diselenggarakan semester ini - UNU raih akreditasi Baik Sekali - Seminar nasional terbuka untuk umum', 'group' => 'appearance'],
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
            ['key' => 'robots_txt', 'value' => "User-agent: *\nAllow: /\n\nSitemap: ".url('/sitemap.xml'), 'group' => 'seo'],
        ])->each(fn (array $setting): Setting => Setting::query()->updateOrCreate(
            ['key' => $setting['key']],
            ['value' => $setting['value'], 'group' => $setting['group']],
        ));
    }
}
