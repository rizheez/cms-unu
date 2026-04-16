<?php

namespace App\Filament\Resources\Sliders\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SliderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konten Banner')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->required(),
                        TextInput::make('subtitle')
                            ->label('Subjudul'),
                    ])
                    ->columns(2),
                Section::make('Media dan Tombol')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Gambar')
                            ->image()
                            ->required(),
                        TextInput::make('button_text')
                            ->label('Teks Tombol'),
                        TextInput::make('button_url')
                            ->label('URL Tombol')
                            ->url(),
                    ])
                    ->columns(2),
                Section::make('Pengaturan')
                    ->schema([
                        TextInput::make('order')
                            ->label('Urutan')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
