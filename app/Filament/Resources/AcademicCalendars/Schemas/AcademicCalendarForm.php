<?php

namespace App\Filament\Resources\AcademicCalendars\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AcademicCalendarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kegiatan')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->placeholder('Contoh: Ujian Tengah Semester')
                            ->required(),
                        TextInput::make('category')
                            ->label('Kategori')
                            ->placeholder('Contoh: Perkuliahan, Ujian, Wisuda')
                            ->required(),
                        TextInput::make('color')
                            ->label('Warna')
                            ->placeholder('Contoh: #10b981')
                            ->required()
                            ->default('#10b981'),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->placeholder('Tuliskan ringkasan kegiatan akademik')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Jadwal')
                    ->schema([
                        DatePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->helperText('Tanggal awal kegiatan berlangsung.')
                            ->required(),
                        DatePicker::make('end_date')
                            ->label('Tanggal Selesai')
                            ->helperText('Kosongkan jika kegiatan hanya berlangsung satu hari.'),
                    ])
                    ->columns(2),
            ]);
    }
}
