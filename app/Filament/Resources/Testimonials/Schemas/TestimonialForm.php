<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Pemberi Testimoni')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->required(),
                        TextInput::make('position')
                            ->label('Jabatan'),
                        TextInput::make('photo')
                            ->label('Foto'),
                    ])
                    ->columns(3),
                Section::make('Isi Testimoni')
                    ->schema([
                        RichEditor::make('content')
                            ->label('Konten')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Pengaturan')
                    ->schema([
                        TextInput::make('rating')
                            ->label('Rating')
                            ->required()
                            ->numeric()
                            ->default(5),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
