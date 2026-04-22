# Editor.js Implementation Plan

## Latar Belakang

User kampus sudah terbiasa menggunakan WordPress untuk menulis halaman dan berita. Karena itu, pengalaman menulis berbasis blok akan lebih mudah diadopsi dibanding textarea biasa atau editor HTML klasik.

`Editor.js` dipilih sebagai kandidat editor konten karena:

- pendekatan blok lebih ramah untuk penulis non-teknis
- struktur konten lebih rapi dan mudah dikontrol
- lebih aman jika konten disimpan sebagai JSON lalu dirender lewat backend
- lebih mudah dibatasi hanya ke fitur yang benar-benar dibutuhkan

## Tujuan

- memberi pengalaman menulis yang terasa familiar bagi user kampus
- menggunakan satu editor yang konsisten untuk `page` dan `berita`
- menjaga struktur konten tetap aman, rapi, dan mudah dirender
- meminimalkan risiko layout rusak akibat input HTML bebas

## Scope Awal

Implementasi awal difokuskan untuk:

- halaman statis
- berita atau artikel

Fitur dasar yang diutamakan:

- heading
- paragraph
- list
- quote
- image
- delimiter
- table jika memang dibutuhkan
- embed jika memang dibutuhkan
- link
- code block jika memang dibutuhkan
- checklist jika memang dibutuhkan
- ordered list jika memang dibutuhkan
- unordered list jika memang dibutuhkan

## Prinsip Implementasi

1. Konten utama disimpan dalam format JSON.
2. JSON dari editor tidak langsung dipercaya sebagai HTML final.
3. Backend Laravel bertugas memvalidasi, menyimpan, dan merender konten.
4. Upload gambar dilakukan melalui endpoint Laravel sendiri.
5. Tool editor dibatasi agar pengalaman tetap sederhana dan stabil.

## Rancangan Data

### 1. Penyimpanan Konten

Gunakan kolom JSON untuk body konten, misalnya:

- `pages.content`
- `posts.content`

Jika saat ini kolom masih berupa `text` atau `longText`, perlu diputuskan salah satu:

- tambah kolom baru bertipe JSON
- tetap gunakan `longText` tetapi isi berupa JSON string

Rekomendasi utama:

- pakai tipe `json` jika database dan alur existing memungkinkan jika db menggunakan mysql/mariadb maka gunakan `longText` karena mysql versi lama belum support tipe json
- jika tetap pakai `longText`, pastikan ada validasi untuk memastikan isinya benar

### 2. Field Pendukung CMS

Agar pengalaman terasa dekat dengan WordPress, body editor saja tidak cukup. Field yang disarankan:

- `title`
- `slug`
- `excerpt`
- `featured_image`
- `status`
- `published_at`
- `meta_title`
- `meta_description`

Opsional tapi bisa dipertimbangkan untuk ditambahkan di awal:

- `author`
- `editor` // jika nanti kedepannya ada role editor terpisah
- `category`
- `tags`

## Alur Admin yang Disarankan

### Untuk Page

- user membuat judul halaman
- slug dibuat otomatis tetapi tetap bisa diubah dengan klik edit
- body halaman ditulis dengan `Editor.js`
- user dapat simpan sebagai draft atau publish

### Untuk Berita

- user mengisi judul
- sistem menyiapkan slug
- user menulis isi berita dengan `Editor.js`
- user memilih thumbnail
- user mengatur status publish
- user mengisi ringkasan dan SEO dasar

## Integrasi Dengan Filament

Karena project menggunakan Filament, kemungkinan implementasi paling realistis:

- buat custom form field atau wrapper untuk `Editor.js`
- simpan output editor ke hidden field JSON
- saat edit data existing, JSON dimuat kembali ke editor

Yang perlu dipastikan saat eksekusi nanti:

- apakah integrasi dilakukan langsung di resource form Filament
- apakah perlu komponen reusable agar bisa dipakai di `PageResource` dan `PostResource`
- bagaimana preview dan validasi ditampilkan di form

## Rendering Frontend

Konten `Editor.js` sebaiknya tidak dirender mentah dari hasil input. Opsi yang disarankan:

- buat renderer di Laravel yang mengubah blok JSON menjadi HTML terkontrol
- setiap block type memiliki renderer sendiri
- hanya block yang di-whitelist yang boleh tampil

Keuntungan pendekatan ini:

- HTML output lebih aman
- struktur markup bisa disesuaikan dengan desain website
- SEO lebih mudah dijaga
- lebih mudah menambahkan class Tailwind atau struktur Blade sesuai kebutuhan

## Upload Gambar

Untuk block image, upload sebaiknya masuk ke endpoint Laravel, bukan langsung ke layanan eksternal tanpa kontrol.

Rencana dasarnya:

- user upload gambar dari editor
- request dikirim ke route Laravel terproteksi
- backend validasi ukuran, mime type, dan autentikasi
- file disimpan ke disk yang digunakan aplikasi
- backend mengembalikan URL untuk dipakai oleh block image

Hal yang perlu diputuskan nanti:

- penyimpanan hanya lokal
- aturan resize atau kompresi
- naming file dan struktur folder

## Keamanan

Hal yang wajib dijaga saat implementasi:

- validasi schema JSON editor di backend
- whitelist block type yang diperbolehkan
- sanitasi data URL dan embed
- proteksi endpoint upload dengan auth dan policy
- jangan render HTML bebas dari request tanpa kontrol

## Risiko dan Catatan

### Kelebihan

- lebih mudah diadopsi oleh user non-teknis
- pengalaman menulis lebih modern dan terstruktur
- konten lebih siap dipakai ulang di berbagai tampilan

### Tantangan

- perlu custom integration di Filament
- perlu renderer JSON ke HTML
- migrasi dari konten lama bisa butuh mapping tambahan
- preview artikel perlu dirancang dengan baik atau jika terlalu ribet bisa di-skip dulu karena bisa langsung lihat hasilnya di frontend setelah publish

## Strategi Implementasi Bertahap

### Tahap 1

- tentukan model yang akan memakai `Editor.js`
- finalisasi struktur field konten dan metadata
- tentukan daftar tool editor yang diizinkan

### Tahap 2

- integrasikan `Editor.js` ke form admin
- simpan output JSON ke database
- pastikan edit existing content berjalan
- buat 2 layout (grid) 1 block editorjs untuk page dan berita dan sebelahnya untuk field lain seperti title, slug, dll atau rekomendasi anda bagaimana layout yang paling nyaman untuk penulis

### Tahap 3

- buat renderer backend dari JSON ke HTML
- tampilkan hasil konten di frontend page dan berita

### Tahap 4

- tambahkan upload image
- tambahkan validasi, sanitasi, dan otorisasi

### Tahap 5

- lakukan uji coba dengan user admin kampus
- sederhanakan tool yang jarang dipakai
- perbaiki UX berdasarkan kebiasaan penulis

## Rekomendasi Awal

Jika tujuan utamanya adalah memudahkan user kampus yang terbiasa WordPress, maka pendekatan yang direkomendasikan:

- gunakan `Editor.js` hanya untuk body content
- buat field CMS lain tetap sederhana dan familiar
- mulai dari berita dulu, lalu lanjut ke page
- aktifkan hanya tool yang benar-benar diperlukan

Urutan paling aman untuk implementasi:

1. berita
2. page
3. migrasi atau konversi konten lama jika memang dibutuhkan

## Keputusan Yang Perlu Ditetapkan Nanti

- model mana yang lebih dulu memakai `Editor.js`
- apakah page dan berita memakai schema block yang sama
- apakah embed video diperlukan di versi awal
- bagaimana penyimpanan dan manajemen gambar
- apakah perlu mode draft, scheduled publish, dan preview private

## Output Yang Akan Dikerjakan Saat Eksekusi Dimulai

Saat implementasi nanti, pekerjaan bisa dipecah menjadi:

- migration update
- form integration di Filament
- endpoint upload image
- service atau renderer untuk output HTML
- penyesuaian template frontend
- test untuk validasi dan alur simpan konten

## Kesimpulan

`Editor.js` layak dipakai di project ini, terutama karena target user sudah terbiasa dengan pola editing ala WordPress. Supaya hasilnya benar-benar membantu, implementasi jangan berhenti di editor saja, tetapi dibangun sebagai pengalaman CMS yang sederhana, aman, dan konsisten untuk penulis kampus.
