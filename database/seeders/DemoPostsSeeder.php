<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoPostsSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->where('email', 'admin@unu.test')->firstOrFail();
        $categories = $this->seedPostCategories();

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
}
