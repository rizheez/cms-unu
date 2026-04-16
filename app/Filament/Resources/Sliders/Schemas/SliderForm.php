<?php

namespace App\Filament\Resources\Sliders\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SliderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul')
                    ->required(),
                TextInput::make('subtitle')
                    ->label('Subjudul'),
                FileUpload::make('image')
                    ->label('Gambar')
                    ->image()
                    ->required(),
                TextInput::make('button_text')
                    ->label('Teks Tombol'),
                TextInput::make('button_url')
                    ->label('URL Tombol')
                    ->url(),
                TextInput::make('order')
                    ->label('Urutan')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->required(),
            ]);
    }
}
