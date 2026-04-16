<?php

namespace App\Filament\Resources\Galleries\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Galeri')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->required(),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                    ]),
                Section::make('Media dan Pengaturan')
                    ->schema([
                        FileUpload::make('cover_image')
                            ->label('Gambar Sampul')
                            ->image(),
                        TextInput::make('type')
                            ->label('Jenis')
                            ->required()
                            ->default('photo'),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
