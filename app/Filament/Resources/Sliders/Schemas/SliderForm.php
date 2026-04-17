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
                            ->placeholder('Contoh: Cetak Generasi Unggul')
                            ->required(),
                        TextInput::make('subtitle')
                            ->label('Subjudul')
                            ->placeholder('Tuliskan subjudul banner'),
                    ])
                    ->columns(2),
                Section::make('Media dan Tombol')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Gambar')
                            ->helperText('Gunakan gambar lebar berkualitas baik untuk banner.')
                            ->image()
                            ->disk('public')
                            ->directory('sliders')
                            ->visibility('public')
                            ->required(),
                        TextInput::make('button_text')
                            ->label('Teks Tombol')
                            ->placeholder('Contoh: Daftar Sekarang'),
                        TextInput::make('button_url')
                            ->label('URL Tombol')
                            ->placeholder('Contoh: /penerimaan-mahasiswa-baru')
                            ->url(),
                    ])
                    ->columns(2),
                Section::make('Pengaturan')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->helperText('Hanya banner aktif yang ditampilkan.')
                            ->required(),
                    ])
                    ->columns(1),
            ]);
    }
}
