<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ContactMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Pengirim')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->placeholder('Contoh: Raka Pratama')
                            ->required()
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('email')
                            ->label('Alamat Email')
                            ->placeholder('Contoh: nama@email.com')
                            ->email()
                            ->required()
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('phone')
                            ->label('Telepon')
                            ->placeholder('Contoh: 081234567890')
                            ->tel()
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(3),
                Section::make('Isi Pesan')
                    ->schema([
                        TextInput::make('subject')
                            ->label('Subjek')
                            ->placeholder('Contoh: Informasi PMB')
                            ->required()
                            ->disabled()
                            ->dehydrated(false),
                        Textarea::make('message')
                            ->label('Pesan')
                            ->placeholder('Isi pesan dari pengirim')
                            ->required()
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ]),
                Section::make('Status Pembacaan')
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->placeholder('Pilih status pesan')
                            ->options([
                                'unread' => 'Belum Dibaca',
                                'read' => 'Dibaca',
                            ])
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state): void {
                                if ($state === 'read') {
                                    $set('read_at', now());
                                }

                                if ($state === 'unread') {
                                    $set('read_at', null);
                                }
                            })
                            ->required()
                            ->default('unread'),
                        DateTimePicker::make('read_at')
                            ->label('Dibaca Pada')
                            ->helperText('Otomatis terisi saat status Dibaca.')
                            ->disabled()
                            ->dehydrated(true),
                    ])
                    ->columns(2),
            ]);
    }
}
