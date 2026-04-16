<?php

namespace App\Filament\Resources\Faculties\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FacultyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required(),
                TextInput::make('short_name')
                    ->label('Nama Singkat'),
                TextInput::make('dean_name')
                    ->label('Nama Dekan'),
                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email(),
                TextInput::make('phone')
                    ->label('Telepon')
                    ->tel(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->label('Gambar')
                    ->image(),
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
            ]);
    }
}
