<?php

namespace App\Filament\Resources\Menus\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Menu')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->placeholder('Contoh: Menu Utama')
                            ->required(),
                        TextInput::make('location')
                            ->label('Lokasi')
                            ->placeholder('Contoh: header atau footer')
                            ->required(),
                    ])
                    ->columns(2),
                Section::make('Status')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->helperText('Hanya menu aktif yang digunakan di website.')
                            ->required(),
                    ]),
            ]);
    }
}
