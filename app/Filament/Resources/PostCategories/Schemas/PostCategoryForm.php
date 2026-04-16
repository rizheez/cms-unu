<?php

namespace App\Filament\Resources\PostCategories\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PostCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kategori')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->placeholder('Contoh: Kampus')
                            ->required(),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->placeholder('Tuliskan deskripsi singkat kategori')
                            ->columnSpanFull(),
                    ]),
                Section::make('Tampilan')
                    ->schema([
                        TextInput::make('color')
                            ->label('Warna')
                            ->placeholder('Contoh: #00a9b7'),
                    ]),
            ]);
    }
}
