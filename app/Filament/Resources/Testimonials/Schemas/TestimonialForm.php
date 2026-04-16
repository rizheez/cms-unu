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
                            ->placeholder('Contoh: Aulia Rahman')
                            ->required(),
                        TextInput::make('position')
                            ->label('Jabatan')
                            ->placeholder('Contoh: Alumni Informatika'),
                        TextInput::make('photo')
                            ->label('Foto')
                            ->placeholder('Contoh: testimonials/aulia-rahman.jpg'),
                    ])
                    ->columns(3),
                Section::make('Isi Testimoni')
                    ->schema([
                        RichEditor::make('content')
                            ->label('Konten')
                            ->placeholder('Tuliskan isi testimoni')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Pengaturan')
                    ->schema([
                        TextInput::make('rating')
                            ->label('Rating')
                            ->helperText('Isi 1 sampai 5.')
                            ->required()
                            ->numeric()
                            ->default(5),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->helperText('Hanya testimoni aktif yang ditampilkan.')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
