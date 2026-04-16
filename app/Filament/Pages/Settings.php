<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class Settings extends Page
{
    private const DEFAULT_TICKER_TEXT = 'Pendaftaran Mahasiswa Baru telah dibuka - Daftarkan dirimu sekarang! - Wisuda Sarjana akan diselenggarakan semester ini - UNU raih akreditasi Baik Sekali - Seminar nasional terbuka untuk umum';

    protected string $view = 'filament.pages.settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Website';

    protected static ?string $navigationLabel = 'Settings';

    protected static ?int $navigationSort = 30;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'ticker_text' => Setting::get('ticker_text', self::DEFAULT_TICKER_TEXT),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('ticker_text')
                    ->label('Teks Running Info')
                    ->helperText('Teks ini tampil di bar INFO paling atas. Pisahkan beberapa informasi dengan tanda minus (-).')
                    ->rows(5)
                    ->required()
                    ->trim()
                    ->columnSpanFull(),
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
        /** @var array{ticker_text: string} $data */
        $data = $this->form->getState();

        Setting::set('ticker_text', $data['ticker_text'], 'appearance');

        Notification::make()
            ->success()
            ->title('Pengaturan disimpan')
            ->send();
    }
}
