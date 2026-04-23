<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoMenusSeeder extends Seeder
{
    public function run(): void
    {
        $header = Menu::query()->updateOrCreate(
            ['location' => 'header'],
            ['name' => 'Menu Utama', 'is_active' => true],
        );

        $footer = Menu::query()->updateOrCreate(
            ['location' => 'footer'],
            ['name' => 'Menu Footer', 'is_active' => true],
        );

        MenuItem::query()->where('menu_id', $header->id)->delete();
        MenuItem::query()->where('menu_id', $footer->id)->delete();

        $this->syncMenuItems($header, [
            ['label' => 'Beranda', 'url' => '/', 'order' => 1],
            ['label' => 'Profile', 'order' => 2, 'children' => [
                ['label' => 'Visi, Misi Dan Tujuan UNU Kaltim', 'order' => 1],
                ['label' => 'Struktur Organisasi', 'order' => 2],
                ['label' => 'SAMBUTAN REKTOR', 'order' => 3],
                ['label' => 'PDDikti UNU KALTIM', 'order' => 4],
            ]],
            ['label' => 'Akademik', 'order' => 3, 'children' => [
                ['label' => 'Fakultas', 'order' => 1, 'children' => [
                    ['label' => 'Fakultas Ekonomi dan Bisnis', 'order' => 1, 'children' => [
                        ['label' => 'Program Studi Akuntansi', 'order' => 1],
                    ]],
                    ['label' => 'Fakultas Farmasi', 'order' => 2, 'children' => [
                        ['label' => 'Program Studi Farmasi', 'order' => 1],
                    ]],
                    ['label' => 'Fakultas Ilmu Sosial dan Kependidikan', 'order' => 3, 'children' => [
                        ['label' => 'Program Studi Ilmu Komunikasi', 'order' => 1],
                        ['label' => 'Program Studi Hubungan Internasional', 'order' => 2],
                        ['label' => 'Program Studi Pendidikan Anak Usia Dini', 'order' => 3],
                    ]],
                    ['label' => 'Fakultas Teknik', 'order' => 4, 'children' => [
                        ['label' => 'Program Studi Arsitektur', 'order' => 1],
                        ['label' => 'Program Studi Desain Interior', 'order' => 2],
                        ['label' => 'Program Studi Teknik Industri', 'order' => 3],
                        ['label' => 'Program Studi Teknologi Industri Pertanian', 'order' => 4, 'children' => [
                            ['label' => 'Laboratorium Agroindustri', 'order' => 1],
                        ]],
                        ['label' => 'Program Studi Teknik Informatika', 'order' => 5],
                    ]],
                ]],
                ['label' => 'KRS Siakad Online', 'order' => 2],
                ['label' => 'Aturan / Kebijakan', 'order' => 3],
                ['label' => 'Surat Aktif Kuliah', 'order' => 4],
                ['label' => 'Surat Bebas Pustaka', 'order' => 5],
                ['label' => 'Surat Bebas Administrasi Keuangan', 'order' => 6, 'children' => [
                    ['label' => 'Surat Pernyataan Kehilangan SLIP SPP', 'order' => 1],
                    ['label' => 'Form Rekapitulasi SPP Mahasiswa', 'order' => 2],
                    ['label' => 'Surat Form Sumbangan Buku', 'order' => 3],
                    ['label' => 'Format Formulir Permohonan Angsuran SPP', 'order' => 4],
                ]],
                ['label' => 'Kalender Pendidikan Tahun Akademik 2022/2023', 'order' => 7],
                ['label' => 'Cara Cek Data Mahasiswa di Pangkalan Data LLDIKTI', 'order' => 8],
            ]],
            ['label' => 'Layanan', 'order' => 4, 'children' => [
                ['label' => 'Pendaftaran Mahasiswa Baru', 'order' => 1, 'children' => [
                    ['label' => 'Pendaftaran Mahasiswa Baru', 'order' => 1],
                    ['label' => 'Syarat Pendaftaran', 'order' => 2],
                    ['label' => 'Pengumuman Hasil Seleksi', 'order' => 3, 'children' => [
                        ['label' => 'Gelombang I', 'order' => 1],
                        ['label' => 'Gelombang II', 'order' => 2],
                        ['label' => 'Gelombang III', 'order' => 3],
                    ]],
                    ['label' => 'Jadwal Tes PMB', 'order' => 4],
                ]],
                ['label' => 'Kartu Rencana Studi', 'order' => 2],
            ]],
            ['label' => 'Lembaga', 'order' => 5, 'children' => [
                ['label' => 'Lembaga Penjaminan Mutu (LPM)', 'order' => 1, 'children' => [
                    ['label' => 'BAN - PT dan LAM', 'order' => 1],
                    ['label' => 'SPMI KEMENDIKBUD', 'order' => 2],
                    ['label' => 'Dokumen Generik Peraturan dan Kebijakan Terkait Penjaminan Mutu', 'order' => 3],
                    ['label' => 'SPMI', 'order' => 4, 'children' => [
                        ['label' => 'Persuratan', 'order' => 1],
                        ['label' => 'Pedoman', 'order' => 2],
                        ['label' => 'SOP', 'order' => 3],
                        ['label' => 'Buku SPMI', 'order' => 4],
                        ['label' => 'Buku AMI', 'order' => 5],
                        ['label' => 'Dokumen AMI', 'order' => 6],
                        ['label' => 'Luaran AMI', 'order' => 7],
                    ]],
                    ['label' => 'Survey', 'order' => 5, 'children' => [
                        ['label' => 'Visi, Misi, Tujuan dan Sasaran', 'order' => 1],
                        ['label' => 'Lulusan (Tracer Study)', 'order' => 2],
                        ['label' => 'Kepuasan Mahasiswa Terhadap Layanan Akademik', 'order' => 3],
                        ['label' => 'Kepuasan Dosen Terhadap Layanan Akademik', 'order' => 4],
                        ['label' => 'Kepuasan Penelitian dan PKM', 'order' => 5],
                        ['label' => 'Kepuasan Pengguna Lulusan', 'order' => 6],
                        ['label' => 'Kepuasan Layanan LPM', 'order' => 7],
                    ]],
                    ['label' => 'Arsip AMI RTM', 'order' => 6, 'children' => [
                        ['label' => 'Fakultas Farmasi', 'order' => 1, 'children' => [
                            ['label' => 'Farmasi', 'order' => 1],
                        ]],
                        ['label' => 'Fakultas Ekonomi dan Bisnis', 'order' => 2, 'children' => [
                            ['label' => 'Akuntansi', 'order' => 1],
                        ]],
                        ['label' => 'Fakultas Teknik', 'order' => 3, 'children' => [
                            ['label' => 'Arsitektur', 'order' => 1],
                            ['label' => 'Desain Interior', 'order' => 2],
                            ['label' => 'Teknik Industri', 'order' => 3],
                            ['label' => 'Teknologi Industri Pertanian', 'order' => 4],
                            ['label' => 'Teknik Informatika', 'order' => 5],
                        ]],
                        ['label' => 'Fakultas Ilmu Sosial dan Kependidikan', 'order' => 4, 'children' => [
                            ['label' => 'Ilmu Komunikasi', 'order' => 1],
                            ['label' => 'Paud', 'order' => 2],
                            ['label' => 'Hubungan Internasional', 'order' => 3],
                        ]],
                        ['label' => 'Informasi dan Dokumentasi Kegiatan SPMI UNU Kaltim', 'order' => 5, 'children' => [
                            ['label' => 'Umum', 'order' => 1],
                            ['label' => 'Farmasi', 'order' => 2],
                            ['label' => 'Akuntansi', 'order' => 3],
                            ['label' => 'Desain Interior', 'order' => 4],
                            ['label' => 'Teknik Industri', 'order' => 5],
                            ['label' => 'Teknik Informatika', 'order' => 6],
                            ['label' => 'Teknologi Industri Pertanian', 'order' => 7],
                            ['label' => 'Arsitektur', 'order' => 8],
                            ['label' => 'Ilmu Komunikasi', 'order' => 9],
                            ['label' => 'Paud', 'order' => 10],
                            ['label' => 'Hubungan Internasional', 'order' => 11],
                        ]],
                        ['label' => 'Eksternal Benchmarking', 'order' => 6],
                    ]],
                ]],
                ['label' => 'Lembaga Penelitian dan Pengabdian Masyarakat (LPPM)', 'order' => 2, 'children' => [
                    ['label' => 'Visi dan Misi LPPM', 'order' => 1],
                    ['label' => 'STRUKTUR ORGANISASI LPPM', 'order' => 2],
                    ['label' => 'Dokumen LPPM', 'order' => 3],
                    ['label' => 'Data Penelitian data Pengabdian kepada Masyarakat', 'order' => 4],
                    ['label' => 'Publikasi', 'order' => 5],
                    ['label' => 'Jurnal', 'order' => 6],
                    ['label' => 'HKI', 'order' => 7],
                    ['label' => 'Layanan', 'order' => 8, 'children' => [
                        ['label' => 'Pembuatan Surat Tugas', 'order' => 1],
                        ['label' => 'Pengecekkan Plagiasi', 'order' => 2],
                        ['label' => 'Pengajuan HKI', 'order' => 3],
                    ]],
                ]],
            ]],
            ['label' => 'Karir', 'order' => 6],
            ['label' => 'Berita', 'url' => '/berita', 'order' => 7],
            ['label' => 'Kontak', 'url' => '/kontak', 'order' => 8],
            ['label' => 'Hotline SATGAS PPKPT', 'order' => 9],
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
     * @param  array<int, string>  $ancestorLabels
     */
    private function syncMenuItems(Menu $menu, array $items, ?int $parentId = null, array $ancestorLabels = []): void
    {
        foreach ($items as $item) {
            $label = (string) $item['label'];
            $labels = [...$ancestorLabels, $label];
            $hasChildren = isset($item['children']) && is_array($item['children']) && $item['children'] !== [];

            $menuItem = $menu->items()->updateOrCreate(
                ['label' => $label, 'parent_id' => $parentId],
                [
                    'url' => $item['url'] ?? $this->buildMenuItemUrl($labels, $hasChildren),
                    'target' => '_self',
                    'order' => $item['order'],
                ],
            );

            if ($hasChildren) {
                $this->syncMenuItems($menu, $item['children'], $menuItem->id, $labels);
            }
        }
    }

    /**
     * @param  array<int, string>  $labels
     */
    private function buildMenuItemUrl(array $labels, bool $hasChildren): string
    {
        if ($hasChildren) {
            return '#';
        }

        return '/sementara/'.collect($labels)
            ->map(fn (string $label): string => Str::slug($label))
            ->implode('/');
    }
}
