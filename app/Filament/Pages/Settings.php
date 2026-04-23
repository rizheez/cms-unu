<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class Settings extends Page
{
    /**
     * @var array<string, array{default: string, group: string}>
     */
    private const SETTINGS = [
        'site_name' => ['default' => 'Universitas Nahdlatul Ulama', 'group' => 'general'],
        'site_tagline' => ['default' => 'Kampus Unggul, Berkarakter, dan Berdampak', 'group' => 'general'],
        'site_address' => ['default' => 'Jl. Pendidikan No. 1, Samarinda, Kalimantan Timur', 'group' => 'contact'],
        'site_city' => ['default' => 'Samarinda', 'group' => 'contact'],
        'site_region' => ['default' => 'Kalimantan Timur', 'group' => 'contact'],
        'site_postal_code' => ['default' => '', 'group' => 'contact'],
        'site_country' => ['default' => 'ID', 'group' => 'contact'],
        'site_latitude' => ['default' => '', 'group' => 'contact'],
        'site_longitude' => ['default' => '', 'group' => 'contact'],
        'google_maps_url' => ['default' => '', 'group' => 'contact'],
        'site_phone' => ['default' => '+62 812 4000 2026', 'group' => 'contact'],
        'site_email' => ['default' => 'info@unu.ac.id', 'group' => 'contact'],
        'meta_title' => ['default' => 'Universitas Nahdlatul Ulama Kalimantan Timur | Kampus di Samarinda', 'group' => 'seo'],
        'meta_description' => ['default' => 'Website resmi Universitas Nahdlatul Ulama Kalimantan Timur, kampus di Samarinda yang menyediakan informasi akademik, berita kampus, pendaftaran mahasiswa baru, dan layanan mahasiswa.', 'group' => 'seo'],
        'google_site_verification' => ['default' => '', 'group' => 'seo'],
        'robots_txt' => ['default' => '', 'group' => 'seo'],
        'site_logo' => ['default' => '', 'group' => 'appearance'],
        'site_favicon' => ['default' => '', 'group' => 'appearance'],
        'ticker_text' => ['default' => 'Pendaftaran Mahasiswa Baru telah dibuka - Daftarkan dirimu sekarang! - Wisuda Sarjana akan diselenggarakan semester ini - UNU raih akreditasi Baik Sekali - Seminar nasional terbuka untuk umum', 'group' => 'appearance'],
        'social_facebook' => ['default' => 'https://www.facebook.com/unukaltim.official', 'group' => 'social'],
        'social_instagram' => ['default' => 'https://www.instagram.com/unukaltim/', 'group' => 'social'],
        'social_youtube' => ['default' => 'https://www.youtube.com/@unukaltim2402', 'group' => 'social'],
        'social_tiktok' => ['default' => 'https://www.tiktok.com/@unukaltim', 'group' => 'social'],
        'vision' => ['default' => 'Menjadi universitas unggul yang melahirkan generasi profesional, berakhlak, dan berdaya saing global berlandaskan nilai Ahlussunnah wal Jamaah.', 'group' => 'profile'],
        'mission' => ['default' => 'Menyelenggarakan pendidikan transformatif, riset aplikatif, dan pengabdian masyarakat yang menjawab kebutuhan zaman.', 'group' => 'profile'],
        'accreditation' => ['default' => 'Baik Sekali', 'group' => 'profile'],
        'home_students_count' => ['default' => '12400', 'group' => 'homepage'],
        'home_service_years' => ['default' => '30', 'group' => 'homepage'],
        'home_hero_video' => ['default' => '', 'group' => 'homepage'],
        'home_about_image' => ['default' => '', 'group' => 'homepage'],
    ];

    protected string $view = 'filament.pages.settings';

    protected static ?string $title = 'Pengaturan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Website';

    protected static ?string $navigationLabel = 'Pengaturan';

    protected static ?int $navigationSort = 30;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->settingsState());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Situs')
                    ->schema([
                        TextInput::make('site_name')
                            ->label('Nama Situs')
                            ->placeholder('Contoh: Universitas Nahdlatul Ulama')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('site_tagline')
                            ->label('Tagline Situs')
                            ->placeholder('Contoh: Kampus Unggul, Berkarakter, dan Berdampak')
                            ->maxLength(255),
                    ])
                    ->columns(2),
                Section::make('Kontak')
                    ->schema([
                        TextInput::make('site_address')
                            ->label('Alamat')
                            ->placeholder('Alamat lengkap kampus')
                            ->columnSpanFull(),
                        TextInput::make('site_city')
                            ->label('Kota')
                            ->placeholder('Contoh: Samarinda')
                            ->maxLength(255),
                        TextInput::make('site_region')
                            ->label('Provinsi')
                            ->placeholder('Contoh: Kalimantan Timur')
                            ->maxLength(255),
                        TextInput::make('site_postal_code')
                            ->label('Kode Pos')
                            ->placeholder('Contoh: 75119')
                            ->maxLength(30),
                        TextInput::make('site_country')
                            ->label('Kode Negara')
                            ->placeholder('Contoh: ID')
                            ->maxLength(2),
                        TextInput::make('site_latitude')
                            ->label('Latitude')
                            ->placeholder('Contoh: -0.502106')
                            ->numeric(),
                        TextInput::make('site_longitude')
                            ->label('Longitude')
                            ->placeholder('Contoh: 117.153709')
                            ->numeric(),
                        TextInput::make('google_maps_url')
                            ->label('URL Google Maps')
                            ->helperText('Bisa diisi URL dari atribut src iframe embed Google Maps agar kartu nama tempat tampil di peta.')
                            ->placeholder('https://www.google.com/maps/embed?pb=...')
                            ->url()
                            ->columnSpanFull(),
                        TextInput::make('site_phone')
                            ->label('Telepon / WhatsApp')
                            ->placeholder('Contoh: +62 812 4000 2026')
                            ->tel()
                            ->maxLength(255),
                        TextInput::make('site_email')
                            ->label('Email')
                            ->placeholder('Contoh: info@unu.ac.id')
                            ->email()
                            ->maxLength(255),
                    ])
                    ->columns(2),
                Section::make('SEO Dasar')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Judul SEO Default')
                            ->placeholder('Universitas Nahdlatul Ulama Kalimantan Timur | Kampus di Samarinda')
                            ->required()
                            ->maxLength(70),
                        TextInput::make('google_site_verification')
                            ->label('Google Site Verification')
                            ->helperText('Isi kode verifikasi dari Google Search Console jika memakai meta tag.')
                            ->maxLength(255),
                        Textarea::make('meta_description')
                            ->label('Deskripsi SEO Default')
                            ->placeholder('Deskripsi singkat website untuk Google.')
                            ->required()
                            ->rows(3)
                            ->maxLength(200)
                            ->columnSpanFull(),
                        Textarea::make('robots_txt')
                            ->label('Robots.txt Kustom')
                            ->helperText('Opsional. Kosongkan untuk memakai robots.txt bawaan yang sudah berisi sitemap dan blok area admin.')
                            ->rows(6)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Media Sosial')
                    ->schema([
                        TextInput::make('social_facebook')
                            ->label('Facebook')
                            ->placeholder('https://facebook.com/...')
                            ->url()
                            ->maxLength(255),
                        TextInput::make('social_instagram')
                            ->label('Instagram')
                            ->placeholder('https://instagram.com/...')
                            ->url()
                            ->maxLength(255),
                        TextInput::make('social_youtube')
                            ->label('YouTube')
                            ->placeholder('https://youtube.com/...')
                            ->url()
                            ->maxLength(255),
                        TextInput::make('social_tiktok')
                            ->label('TikTok')
                            ->placeholder('https://tiktok.com/@...')
                            ->url()
                            ->maxLength(255),
                    ])
                    ->columns(2),
                Section::make('Profil Kampus')
                    ->schema([
                        Textarea::make('vision')
                            ->label('Visi')
                            ->placeholder('Tuliskan visi kampus')
                            ->rows(4),
                        Textarea::make('mission')
                            ->label('Misi')
                            ->placeholder('Tuliskan misi kampus')
                            ->rows(4),
                        TextInput::make('accreditation')
                            ->label('Akreditasi')
                            ->placeholder('Contoh: Baik Sekali')
                            ->maxLength(255),
                    ])
                    ->columns(2),
                Section::make('Statistik Beranda')
                    ->schema([
                        TextInput::make('home_students_count')
                            ->label('Jumlah Mahasiswa')
                            ->placeholder('Contoh: 12400')
                            ->helperText('Ditampilkan pada statistik beranda dan badge hero.')
                            ->numeric()
                            ->minValue(0)
                            ->step(1)
                            ->required(),
                        TextInput::make('home_service_years')
                            ->label('Tahun Pengabdian')
                            ->placeholder('Contoh: 30')
                            ->helperText('Ditampilkan pada statistik beranda.')
                            ->numeric()
                            ->minValue(0)
                            ->step(1)
                            ->required(),
                        FileUpload::make('home_hero_video')
                            ->label('Video Hero Beranda')
                            ->helperText('Opsional. Format MP4, WebM, atau OGG. Disarankan video pendek dan ringan, maksimal 50 MB.')
                            ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg'])
                            ->maxSize(51200)
                            ->disk('public')
                            ->directory('homepage')
                            ->visibility('public')
                            ->columnSpanFull(),
                        FileUpload::make('home_about_image')
                            ->label('Foto Tentang Kampus')
                            ->helperText('Opsional. Jika diisi, gambar ini menggantikan ilustrasi kiri pada section Tentang UNU.')
                            ->image()
                            ->disk('public')
                            ->directory('homepage')
                            ->visibility('public')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Tampilan')
                    ->schema([
                        FileUpload::make('site_logo')
                            ->label('Logo Situs')
                            ->helperText('Logo utama website. Jika kosong, tampilan memakai logo teks bawaan.')
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->visibility('public'),
                        FileUpload::make('site_favicon')
                            ->label('Favicon')
                            ->helperText('Ikon kecil untuk tab browser.')
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->visibility('public'),
                        Textarea::make('ticker_text')
                            ->label('Teks Running Info')
                            ->helperText('Teks ini tampil di bar INFO paling atas. Pisahkan beberapa informasi dengan tanda minus (-).')
                            ->rows(5)
                            ->required()
                            ->trim()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    EmbeddedSchema::make('form'),
                ])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Simpan Pengaturan')
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                        ]),
                    ]),
            ]);
    }

    public function save(): void
    {
        /** @var array<string, mixed> $data */
        $data = $this->form->getState();

        foreach (self::SETTINGS as $key => $setting) {
            Setting::set($key, $data[$key] ?? '', $setting['group']);
        }

        Notification::make()
            ->success()
            ->title('Pengaturan disimpan')
            ->send();
    }

    /**
     * @return array<string, string>
     */
    private function settingsState(): array
    {
        return collect(self::SETTINGS)
            ->mapWithKeys(fn (array $setting, string $key): array => [
                $key => (string) Setting::get($key, $setting['default']),
            ])
            ->all();
    }
}
