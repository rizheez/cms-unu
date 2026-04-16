<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pertanyaan dan Jawaban')
                    ->schema([
                        TextInput::make('question')
                            ->label('Pertanyaan')
                            ->required(),
                        RichEditor::make('answer')
                            ->label('Jawaban')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Kategori dan Pengaturan')
                    ->schema([
                        TextInput::make('category')
                            ->label('Kategori'),
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
