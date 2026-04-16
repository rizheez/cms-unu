<?php

namespace App\Filament\Resources\Announcements\Schemas;

use Filament\Forms\Components\DateTimePicker;
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
                            ->required(),
                        RichEditor::make('content')
                            ->label('Konten')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Pengaturan Tampilan')
                    ->schema([
                        TextInput::make('type')
                            ->label('Jenis')
                            ->required()
                            ->default('info'),
                        Toggle::make('is_popup')
                            ->label('Tampilkan sebagai popup')
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->required(),
                        DateTimePicker::make('start_at')
                            ->label('Mulai Tayang'),
                        DateTimePicker::make('end_at')
                            ->label('Selesai Tayang'),
                    ])
                    ->columns(2),
            ]);
    }
}
