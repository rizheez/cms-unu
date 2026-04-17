<?php

namespace App\Filament\Resources\Galleries\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
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
                            ->placeholder('Contoh: Kegiatan Mahasiswa')
                            ->required(),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->placeholder('Tuliskan deskripsi singkat galeri')
                            ->columnSpanFull(),
                    ]),
                Section::make('Media dan Pengaturan')
                    ->schema([
                        FileUpload::make('cover_image')
                            ->label('Gambar Sampul')
                            ->helperText('Gambar ini menjadi sampul daftar galeri.')
                            ->image()
                            ->disk('public')
                            ->directory('galleries/covers')
                            ->visibility('public'),
                        Select::make('type')
                            ->label('Jenis')
                            ->placeholder('Pilih jenis galeri')
                            ->options([
                                'photo' => 'Foto',
                                'video' => 'Video',
                            ])
                            ->required()
                            ->default('photo'),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->helperText('Hanya galeri aktif yang ditampilkan.')
                            ->required(),
                    ])
                    ->columns(2),
                Section::make('Foto Galeri')
                    ->schema([
                        Repeater::make('items')
                            ->label('Item Galeri')
                            ->helperText('Isi salah satu saja: upload Foto untuk item foto, atau isi URL Video YouTube untuk item video.')
                            ->relationship()
                            ->schema([
                                FileUpload::make('image')
                                    ->label('Foto')
                                    ->helperText('Upload foto kegiatan jika item ini berupa foto. Kosongkan jika memakai URL video.')
                                    ->image()
                                    ->disk('public')
                                    ->directory('galleries/items')
                                    ->visibility('public'),
                                TextInput::make('caption')
                                    ->label('Keterangan')
                                    ->placeholder('Contoh: Dokumentasi kegiatan mahasiswa di aula utama')
                                    ->maxLength(255),
                                TextInput::make('video_url')
                                    ->label('URL Video')
                                    ->placeholder('Contoh: https://www.youtube.com/watch?v=...')
                                    ->helperText('Isi URL YouTube jika item ini berupa video. Kosongkan jika sudah upload foto.')
                                    ->url()
                                    ->maxLength(255),
                            ])
                            ->columns(2)
                            ->columnSpanFull()
                            ->orderColumn('order')
                            ->addActionLabel('Tambah Foto')
                            ->reorderableWithButtons()
                            ->collapsible(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
