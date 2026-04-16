<?php

namespace App\Filament\Resources\Downloads\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DownloadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Unduhan')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->required(),
                        TextInput::make('category')
                            ->label('Kategori'),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Berkas dan Statistik')
                    ->schema([
                        TextInput::make('file')
                            ->label('Berkas')
                            ->required(),
                        TextInput::make('download_count')
                            ->label('Jumlah Unduhan')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
                Section::make('Pengaturan')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->required(),
                    ]),
            ]);
    }
}
