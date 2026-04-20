<?php

namespace App\Filament\Resources\Announcements\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AnnouncementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konten Pengumuman')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->placeholder('Contoh: Pemeliharaan Sistem Akademik')
                            ->required(),
                        RichEditor::make('content')
                            ->label('Konten')
                            ->placeholder('Tuliskan isi pengumuman')
                            ->helperText('Boleh dikosongkan jika pengumuman hanya menggunakan gambar.')
                            ->columnSpanFull(),
                        FileUpload::make('image')
                            ->label('Gambar Pengumuman')
                            ->helperText('Opsional. Gunakan jika isi pengumuman berupa poster atau gambar.')
                            ->image()
                            ->disk('public')
                            ->directory('announcements')
                            ->visibility('public')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Pengaturan Tampilan')
                    ->schema([
                        TextInput::make('type')
                            ->label('Jenis')
                            ->placeholder('Contoh: info, warning, success')
                            ->required()
                            ->default('info'),
                        Toggle::make('is_popup')
                            ->label('Tampilkan sebagai popup')
                            ->helperText('Aktifkan jika pengumuman perlu muncul sebagai popup.')
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->helperText('Hanya pengumuman aktif yang ditampilkan.')
                            ->required(),
                        DateTimePicker::make('start_at')
                            ->label('Mulai Tayang')
                            ->helperText('Kosongkan jika langsung tayang.'),
                        DateTimePicker::make('end_at')
                            ->label('Selesai Tayang')
                            ->helperText('Kosongkan jika tidak ada batas akhir.'),
                    ])
                    ->columns(2),
            ]);
    }
}
