<?php

namespace App\Filament\Resources\StudyPrograms\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StudyProgramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Program Studi')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->placeholder('Contoh: Informatika')
                            ->required(),
                        Select::make('faculty_id')
                            ->label('Fakultas')
                            ->relationship('faculty', 'name')
                            ->placeholder('Pilih fakultas')
                            ->required(),
                        TextInput::make('degree_level')
                            ->label('Jenjang')
                            ->placeholder('Contoh: S1')
                            ->required(),
                        TextInput::make('head_name')
                            ->label('Nama Ketua Program Studi')
                            ->placeholder('Contoh: Nur Aisyah, M.Kom.'),
                    ])
                    ->columns(2),
                Section::make('Konten dan Media')
                    ->schema([
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->placeholder('Tuliskan profil singkat program studi')
                            ->columnSpanFull(),
                        FileUpload::make('image')
                            ->label('Gambar')
                            ->helperText('Gambar akan tampil di halaman program studi.')
                            ->image()
                            ->disk('public')
                            ->directory('study-programs')
                            ->visibility('public'),
                    ]),
                Section::make('Pengaturan Akademik')
                    ->schema([
                        TextInput::make('accreditation')
                            ->label('Akreditasi')
                            ->placeholder('Contoh: Baik Sekali'),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->helperText('Hanya program studi aktif yang ditampilkan.')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
