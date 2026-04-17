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
                            ->placeholder('Contoh: Bagaimana cara mendaftar mahasiswa baru?')
                            ->required(),
                        RichEditor::make('answer')
                            ->label('Jawaban')
                            ->placeholder('Tuliskan jawaban yang jelas dan ringkas')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Kategori dan Pengaturan')
                    ->schema([
                        TextInput::make('category')
                            ->label('Kategori')
                            ->placeholder('Contoh: Pendaftaran, Akademik, Layanan'),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->helperText('Hanya FAQ aktif yang ditampilkan.')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
