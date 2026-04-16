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
                    ->relationship('faculty', 'name'),
                Select::make('study_program_id')
                    ->relationship('studyProgram', 'name'),
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('nidn'),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('position'),
                TextInput::make('education_level'),
                RichEditor::make('bio')
                    ->columnSpanFull(),
                TextInput::make('photo'),
                TextInput::make('expertise'),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
