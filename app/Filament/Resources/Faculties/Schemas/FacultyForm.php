<?php

namespace App\Filament\Resources\Faculties\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FacultyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Fakultas')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->placeholder('Contoh: Fakultas Teknologi dan Sains')
                            ->required(),
                        TextInput::make('short_name')
                            ->label('Nama Singkat')
                            ->placeholder('Contoh: FTS'),
                        TextInput::make('dean_name')
                            ->label('Nama Dekan')
                            ->placeholder('Contoh: Dr. Ahmad Fauzi, M.Kom.'),
                        TextInput::make('accreditation')
                            ->label('Akreditasi')
                            ->placeholder('Contoh: Baik Sekali'),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->placeholder('Tuliskan profil singkat fakultas')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Kontak')
                    ->schema([
                        TextInput::make('email')
                            ->label('Alamat Email')
                            ->placeholder('Contoh: fts@unu.ac.id')
                            ->email(),
                        TextInput::make('phone')
                            ->label('Telepon')
                            ->placeholder('Contoh: +62 812 4000 2026')
                            ->tel(),
                    ])
                    ->columns(2),
                Section::make('Media dan Pengaturan')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Gambar')
                            ->helperText('Gunakan gambar fakultas yang jelas dan proporsional.')
                            ->image(),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->helperText('Hanya fakultas aktif yang ditampilkan.')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
