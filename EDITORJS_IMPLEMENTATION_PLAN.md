# Athphane EditorJs Implementation Plan

## Latar Belakang

User kampus sudah terbiasa menulis konten dengan pola seperti WordPress. Karena itu, editor berbasis blok lebih mudah diadopsi dibanding textarea biasa atau rich text editor HTML klasik.

Setelah dibandingkan, plugin yang dipilih untuk rencana implementasi ini adalah `athphane/filament-editorjs` karena lebih cocok untuk kebutuhan CMS terstruktur di project ini.

## Alasan Memilih `athphane/filament-editorjs`

Alasan utama pemilihan:

- terintegrasi langsung dengan Filament
- sudah mendukung Filament `v5`
- memakai Spatie Media Library untuk upload gambar
- menyimpan konten dalam format JSON block
- sudah menyediakan helper render konten di frontend
- bisa ditambah custom block renderer jika nanti dibutuhkan
- lebih cocok untuk konten `page` dan `berita` yang perlu struktur rapi

## Kesesuaian Dengan Project Saat Ini

Berdasarkan kondisi project saat ini:

- Laravel: `13.x`
- Filament: `5.x`
- Livewire: `4.x`
- Spatie Media Library: sudah terpasang
- panel Filament admin sudah tersedia

Artinya, fondasi untuk memakai plugin ini sudah cukup siap dan tidak perlu membangun integrasi `Editor.js` dari nol.

## Tujuan

- memberi pengalaman menulis yang lebih familiar untuk user kampus
- memakai satu editor blok yang konsisten untuk `page` dan `berita`
- menjaga struktur konten tetap rapi, aman, dan mudah dirender
- mempercepat implementasi karena memanfaatkan plugin Filament yang sudah jadi

## Scope Awal

Implementasi awal difokuskan untuk:

- halaman statis
- berita atau artikel

Tool editor yang direkomendasikan untuk fase awal:

- `header`
- `paragraph`
- `list`
- `quote`
- `image`
- `delimiter`

Tool opsional yang bisa diaktifkan setelah user terbiasa:

- `table`
- `checklist`
- `code`
- `inline-code`
- `raw`
- custom `linkTool`

## Prinsip Implementasi

1. Konten utama disimpan sebagai JSON block document.
2. Output frontend tidak dirender dari input mentah tanpa kontrol.
3. Tool editor dibatasi agar pengalaman admin tetap sederhana.
4. Gambar dikelola lewat Spatie Media Library melalui plugin.
5. Render frontend tetap bisa dikustomisasi jika hasil bawaan belum sesuai desain website.

## Dependensi dan Instalasi Nanti

Saat masuk tahap eksekusi, command yang dibutuhkan kemungkinan:

```bash
composer require athphane/filament-editorjs
php artisan vendor:publish --tag="filament-editorjs-config" --no-interaction
```

Jika frontend render ingin tampil lebih nyaman dengan typography Tailwind, bisa dipertimbangkan:

```bash
npm install -D @tailwindcss/typography
```

Catatan:

- dependensi baru belum dipasang sekarang
- dokumen ini hanya menjadi plan implementasi

## Rancangan Data

### 1. Penyimpanan Konten

Field utama editor disarankan menggunakan nama:

- `content`

Untuk model seperti `pages` dan `posts`, rekomendasi:

- simpan `content` dalam kolom `json`

Jika ada kebutuhan kompatibilitas khusus, alternatifnya:

- gunakan `longText` berisi JSON string

Rekomendasi utama tetap:

- gunakan kolom `json` bila struktur tabel memungkinkan

### 2. Field Pendukung CMS

Agar pengalaman terasa dekat dengan WordPress, field yang disarankan:

- `title`
- `slug`
- `excerpt`
- `featured_image`
- `status`
- `published_at`
- `meta_title`
- `meta_description`

Opsional:

- `author`
- `category`
- `tags`

## Kebutuhan Model

Model yang memakai plugin ini perlu disiapkan mengikuti pola plugin:

- implement `HasMedia`
- gunakan `InteractsWithMedia`
- gunakan trait `ModelHasEditorJsComponent`
- sediakan kolom `content`

Secara default plugin juga mengandalkan media collection untuk gambar konten.

## Integrasi Dengan Filament

Plugin ini bisa dipasang di panel provider dan dipakai langsung di resource form.

Rencana integrasi:

- daftarkan `FilamentEditorjsPlugin::make()` di panel admin
- gunakan `EditorjsTextField::make('content')` di form resource
- batasi tool sesuai kebutuhan user kampus
- gunakan `columnSpanFull()` untuk area editor utama

Implikasinya:

- tidak perlu membuat custom wrapper editor dari nol
- integrasi admin lebih cepat
- effort bisa difokuskan ke struktur form, validasi, dan UX penulis

## Layout Form Admin yang Direkomendasikan

Untuk kenyamanan penulis, layout yang direkomendasikan:

- area utama kiri: `title`, `excerpt`, `content`
- sidebar kanan: `slug`, `status`, `published_at`, `featured_image`, SEO fields

Untuk `berita`, layout ini paling dekat dengan pola CMS yang familiar.

Untuk `page`, layout bisa lebih sederhana:

- area utama: `title`, `content`
- sidebar: `slug`, `status`, SEO fields

## Upload Gambar

Keunggulan plugin ini adalah integrasi dengan Spatie Media Library.

Rencana upload image:

- user upload gambar langsung dari editor
- plugin menyimpan media melalui Spatie Media Library
- gambar yang tidak lagi dipakai dapat dikelola lebih rapi dibanding upload manual biasa

Hal yang perlu diputuskan saat implementasi:

- disk penyimpanan yang dipakai
- collection name jika perlu dipisah dari media lain
- aturan resize, optimasi, dan naming file

## Rendering Frontend

Plugin ini sudah menyediakan helper render konten, sehingga implementasi awal bisa memakai pendekatan paling cepat:

- render konten dari helper bawaan plugin di Blade

Namun untuk jangka menengah, tetap disarankan mengevaluasi hasil HTML bawaan terhadap kebutuhan desain website. Jika perlu:

- buat custom block renderer untuk block tertentu
- sesuaikan output HTML agar lebih cocok dengan komponen frontend dan SEO structure

Pendekatan bertahap yang direkomendasikan:

1. pakai renderer bawaan untuk percepatan implementasi
2. evaluasi output HTML di frontend
3. custom renderer hanya jika ada block yang butuh markup khusus

## Keamanan dan Validasi

Hal yang tetap wajib dijaga:

- batasi hanya tool yang benar-benar diperlukan
- validasi field lain tetap lewat Form Request atau validasi Filament resource
- batasi akses admin dengan auth dan policy yang sudah berlaku
- evaluasi output block `raw` sebelum diaktifkan

Catatan penting:

- block seperti `raw` sebaiknya tidak diaktifkan pada fase awal
- tool embed atau custom link hanya diaktifkan jika benar-benar dibutuhkan

## Risiko dan Catatan

### Kelebihan

- integrasi cepat dengan Filament
- tidak perlu membangun editor sendiri
- upload gambar lebih rapi karena memakai Media Library
- konten tetap berbasis block JSON
- frontend render bisa langsung jalan lebih cepat

### Tantangan

- model harus menyesuaikan pola plugin
- renderer bawaan mungkin perlu penyesuaian markup
- migrasi konten lama bisa tetap memerlukan mapping tambahan
- user kampus tetap perlu dibiasakan dengan editor blok baru

## Strategi Implementasi Bertahap

### Tahap 1

- pasang package `athphane/filament-editorjs`
- publish config plugin
- daftarkan plugin di `AdminPanelProvider`

### Tahap 2

- tentukan model pertama yang akan memakai editor
- siapkan kolom `content`
- siapkan trait dan interface Media Library pada model

### Tahap 3

- integrasikan `EditorjsTextField` pada resource Filament
- batasi tool ke profile sederhana
- uji alur create dan edit konten

### Tahap 4

- aktifkan upload image
- verifikasi media tersimpan dan tampil dengan benar
- pastikan konten frontend bisa dirender

### Tahap 5

- uji dengan editor/admin kampus
- sederhanakan tool yang membingungkan
- lanjutkan ke `page` setelah `berita` stabil

## Urutan Implementasi yang Direkomendasikan

Urutan paling aman:

1. implementasi di `berita`
2. validasi UX penulis
3. lanjut ke `page`
4. evaluasi perlu tidaknya custom renderer tambahan

## Keputusan Yang Perlu Ditetapkan Nanti

- model mana yang dikerjakan lebih dulu
- nama tabel dan nama field final untuk konten
- tool mana yang aktif pada versi awal
- apakah `raw` dan `embed` akan diizinkan
- bagaimana aturan media storage untuk gambar isi konten

## Output Saat Eksekusi Dimulai

Saat implementasi dimulai nanti, pekerjaan bisa dipecah menjadi:

- instalasi package
- update panel provider Filament
- update model terkait
- migration untuk kolom `content`
- update form resource Filament
- render konten di frontend
- test alur simpan dan edit konten

## Kesimpulan

Untuk project ini, `athphane/filament-editorjs` adalah pilihan yang paling masuk akal jika tujuan utamanya adalah membangun editor konten blok yang cepat dipasang, cukup kaya fitur, dan tetap rapi secara engineering. Ini memberi jalan tengah yang bagus antara kenyamanan editor kampus dan kebutuhan struktur konten yang baik di Laravel + Filament.
