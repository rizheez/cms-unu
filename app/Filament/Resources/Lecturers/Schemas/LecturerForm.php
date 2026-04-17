<?php

namespace App\Filament\Resources\Lecturers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LecturerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Dosen')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->placeholder('Contoh: Dr. Ahmad Fauzi, M.Kom.')
                            ->required(),
                        TextInput::make('nidn')
                            ->label('NIDN')
                            ->placeholder('Contoh: 1122334455'),
                        TextInput::make('email')
                            ->label('Alamat Email')
                            ->placeholder('Contoh: dosen@unu.ac.id')
                            ->email(),
                        TextInput::make('position')
                            ->label('Jabatan')
                            ->placeholder('Contoh: Ketua Program Studi'),
                    ])
                    ->columns(2),
                Section::make('Relasi Akademik')
                    ->schema([
                        Select::make('faculty_id')
                            ->label('Fakultas')
                            ->relationship('faculty', 'name')
                            ->placeholder('Pilih fakultas'),
                        Select::make('study_program_id')
                            ->label('Program Studi')
                            ->relationship('studyProgram', 'name')
                            ->placeholder('Pilih program studi'),
                        TextInput::make('education_level')
                            ->label('Pendidikan Terakhir')
                            ->placeholder('Contoh: S2, S3'),
                        TextInput::make('expertise')
                            ->label('Keahlian')
                            ->placeholder('Contoh: Sistem Informasi, Data Science'),
                    ])
                    ->columns(2),
                Section::make('Profil')
                    ->schema([
                        FileUpload::make('photo')
                            ->label('Foto')
                            ->helperText('Upload foto profil dosen.')
                            ->directory('lecturers')
                            ->image(),
                        RichEditor::make('bio')
                            ->label('Biografi')
                            ->placeholder('Tuliskan biografi singkat dosen')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Pengaturan')
                    ->schema([
                        TextInput::make('order')
                            ->label('Urutan')
                            ->helperText('Angka kecil akan tampil lebih dulu.')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->helperText('Hanya dosen aktif yang ditampilkan.')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
