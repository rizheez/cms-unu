<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoPagesSeeder extends Seeder
{
    public function run(): void
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
}
