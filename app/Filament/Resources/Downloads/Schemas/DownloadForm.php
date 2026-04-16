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
                            ->placeholder('Contoh: Panduan Akademik Mahasiswa')
                            ->required(),
                        TextInput::make('category')
                            ->label('Kategori')
                            ->placeholder('Contoh: Akademik, PMB, Kemahasiswaan'),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->placeholder('Tuliskan deskripsi singkat dokumen')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Berkas dan Statistik')
                    ->schema([
                        TextInput::make('file')
                            ->label('Berkas')
                            ->placeholder('Contoh: dokumen/panduan-akademik.pdf')
                            ->required(),
                        TextInput::make('download_count')
                            ->label('Jumlah Unduhan')
                            ->helperText('Biasanya dibiarkan 0 karena akan bertambah otomatis.')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
                Section::make('Pengaturan')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->helperText('Hanya unduhan aktif yang ditampilkan di website.')
                            ->required(),
                    ]),
            ]);
    }
}
