<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Services\SitemapService;
use BackedEnum;
use Filament\Actions\Action;
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
use Illuminate\Support\Carbon;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class SeoSettings extends Page
{
    /**
     * @var array<string, array{default: string, group: string}>
     */
    private const SETTINGS = [
        'meta_title' => ['default' => 'Universitas Nahdlatul Ulama | Kampus Unggul dan Berakhlak', 'group' => 'seo'],
        'meta_description' => ['default' => 'Website resmi Universitas Nahdlatul Ulama: informasi akademik, berita kampus, pendaftaran, layanan mahasiswa, dan kolaborasi mitra.', 'group' => 'seo'],
        'meta_keywords' => ['default' => 'UNU, Universitas Nahdlatul Ulama, Kampus NU, Perguruan Tinggi Islam, Kampus Kalimantan', 'group' => 'seo'],
        'robots_txt' => ['default' => '', 'group' => 'seo'],
    ];

    protected string $view = 'filament.pages.seo-settings';

    protected static ?string $title = 'SEO & Sitemap';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentMagnifyingGlass;

    protected static string|UnitEnum|null $navigationGroup = 'SEO';

    protected static ?string $navigationLabel = 'SEO & Sitemap';

    protected static ?int $navigationSort = 40;

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
                Section::make('Meta Default Website')
                    ->description('Dipakai sebagai fallback untuk halaman yang belum punya meta SEO khusus.')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta Judul')
                            ->placeholder('Judul utama untuk mesin pencari')
                            ->maxLength(255),
                        TextInput::make('meta_keywords')
                            ->label('Meta Kata Kunci')
                            ->placeholder('Contoh: UNU, kampus, universitas')
                            ->maxLength(255),
                        Textarea::make('meta_description')
                            ->label('Meta Deskripsi')
                            ->placeholder('Deskripsi singkat website untuk mesin pencari')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Robots.txt')
                    ->description('Kosongkan untuk memakai robots.txt default yang mengizinkan crawler dan menunjuk ke sitemap.xml.')
                    ->schema([
                        Textarea::make('robots_txt')
                            ->label('Isi Robots.txt')
                            ->placeholder("User-agent: *\nAllow: /\n\nSitemap: ".url('/sitemap.xml'))
                            ->rows(7)
                            ->columnSpanFull(),
                    ]),
                Section::make('Sitemap')
                    ->description('Sitemap juga tersedia dinamis di /sitemap.xml. Tombol generate akan menulis file public/sitemap.xml.')
                    ->schema([
                        TextInput::make('sitemap_url')
                            ->label('URL Sitemap')
                            ->default(url('/sitemap.xml'))
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('sitemap_last_generated_at')
                            ->label('Terakhir Generate File')
                            ->default($this->lastGeneratedLabel())
                            ->disabled()
                            ->dehydrated(false),
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
                                ->label('Simpan SEO')
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                            Action::make('generateSitemap')
                                ->label('Generate Sitemap')
                                ->icon(Heroicon::OutlinedArrowPath)
                                ->action('generateSitemap'),
                            Action::make('openSitemap')
                                ->label('Buka Sitemap')
                                ->icon(Heroicon::OutlinedGlobeAlt)
                                ->url(url('/sitemap.xml'))
                                ->openUrlInNewTab(),
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
            ->title('Pengaturan SEO disimpan')
            ->send();
    }

    public function generateSitemap(): void
    {
        $sitemap = app(SitemapService::class);

        $sitemap->writeToPublicFile();
        Setting::set('sitemap_last_generated_at', now()->toDateTimeString(), 'seo');
        $this->form->fill($this->settingsState());

        Notification::make()
            ->success()
            ->title('Sitemap berhasil dibuat')
            ->body(url('/sitemap.xml'))
            ->send();
    }

    /**
     * @return array<string, string>
     */
    private function settingsState(): array
    {
        $settings = collect(self::SETTINGS)
            ->mapWithKeys(fn (array $setting, string $key): array => [
                $key => (string) Setting::get($key, $setting['default']),
            ]);

        return $settings
            ->merge([
                'sitemap_url' => url('/sitemap.xml'),
                'sitemap_last_generated_at' => $this->lastGeneratedLabel(),
            ])
            ->all();
    }

    private function lastGeneratedLabel(): string
    {
        $value = (string) Setting::get('sitemap_last_generated_at', '');

        if ($value === '') {
            return 'Belum pernah generate file';
        }

        return Carbon::parse($value)->translatedFormat('d F Y H:i');
    }
}
