<?php

namespace App\Filament\Resources\MenuItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MenuItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Relasi Menu')
                    ->schema([
                        Select::make('menu_id')
                            ->label('Menu')
                            ->relationship('menu', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('parent_id')
                            ->label('Induk Menu')
                            ->relationship('parent', 'label')
                            ->searchable()
                            ->preload()
                            ->helperText('Kosongkan untuk menu utama. Pilih parent untuk membuat dropdown bertingkat.'),
                    ])
                    ->columns(2),
                Section::make('Tautan')
                    ->schema([
                        TextInput::make('label')
                            ->label('Label')
                            ->required()
                            ->maxLength(255),
                        Select::make('page_id')
                            ->label('Halaman')
                            ->relationship('page', 'title')
                            ->searchable()
                            ->preload()
                            ->helperText('Pilih halaman internal. Jika dipilih, link menu otomatis memakai slug halaman ini.'),
                        TextInput::make('url')
                            ->label('URL')
                            ->helperText('Opsional untuk tautan khusus seperti /akademik, /kontak, atau URL lengkap. Abaikan jika sudah memilih halaman.')
                            ->maxLength(255),
                    ])
                    ->columns(2),
                Section::make('Pengaturan Tampilan')
                    ->schema([
                        Select::make('target')
                            ->label('Target')
                            ->options([
                                '_self' => 'Tab yang sama',
                                '_blank' => 'Tab baru',
                            ])
                            ->default('_self')
                            ->required(),
                        TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
