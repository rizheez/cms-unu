<?php

namespace App\Filament\Resources\Downloads\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DownloadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul')
                    ->required(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
                TextInput::make('file')
                    ->label('Berkas')
                    ->required(),
                TextInput::make('category')
                    ->label('Kategori'),
                TextInput::make('download_count')
                    ->label('Jumlah Unduhan')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->required(),
            ]);
    }
}
