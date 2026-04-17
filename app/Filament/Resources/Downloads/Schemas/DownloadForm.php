<?php

namespace App\Filament\Resources\Downloads\Schemas;

use Filament\Forms\Components\FileUpload;
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
                        FileUpload::make('file')
                            ->label('Berkas')
                            ->helperText('Upload dokumen yang akan diunduh dari website.')
                            ->directory('downloads')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/vnd.ms-powerpoint',
                                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                'application/zip',
                            ])
                            ->required(),
                        TextInput::make('download_count')
                            ->label('Jumlah Unduhan')
                            ->helperText('Bertambah otomatis saat pengunjung mengunduh berkas.')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->dehydrated(false),
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
