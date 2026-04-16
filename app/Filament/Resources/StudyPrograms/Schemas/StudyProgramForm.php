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
                            ->required(),
                        Select::make('faculty_id')
                            ->label('Fakultas')
                            ->relationship('faculty', 'name')
                            ->required(),
                        TextInput::make('degree_level')
                            ->label('Jenjang')
                            ->required(),
                        TextInput::make('head_name')
                            ->label('Nama Ketua Program Studi'),
                    ])
                    ->columns(2),
                Section::make('Konten dan Media')
                    ->schema([
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                        FileUpload::make('image')
                            ->label('Gambar')
                            ->image(),
                    ]),
                Section::make('Pengaturan Akademik')
                    ->schema([
                        TextInput::make('accreditation')
                            ->label('Akreditasi'),
                        TextInput::make('order')
                            ->label('Urutan')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->required(),
                    ])
                    ->columns(3),
            ]);
    }
}
