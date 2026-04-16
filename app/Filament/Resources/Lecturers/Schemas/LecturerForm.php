<?php

namespace App\Filament\Resources\Lecturers\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LecturerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('faculty_id')
                    ->label('Fakultas')
                    ->relationship('faculty', 'name'),
                Select::make('study_program_id')
                    ->label('Program Studi')
                    ->relationship('studyProgram', 'name'),
                TextInput::make('name')
                    ->label('Nama')
                    ->required(),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required(),
                TextInput::make('nidn')
                    ->label('NIDN'),
                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email(),
                TextInput::make('position')
                    ->label('Jabatan'),
                TextInput::make('education_level')
                    ->label('Pendidikan Terakhir'),
                RichEditor::make('bio')
                    ->label('Biografi')
                    ->columnSpanFull(),
                TextInput::make('photo')
                    ->label('Foto'),
                TextInput::make('expertise')
                    ->label('Keahlian'),
                TextInput::make('order')
                    ->label('Urutan')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->required(),
            ]);
    }
}
