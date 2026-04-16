<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konten Halaman')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->required(),
                        RichEditor::make('content')
                            ->label('Konten')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Pengaturan Halaman')
                    ->schema([
                        TextInput::make('template')
                            ->label('Template')
                            ->required()
                            ->default('default'),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Terbit',
                                'archived' => 'Arsip',
                            ])
                            ->required()
                            ->default('draft'),
                        DateTimePicker::make('published_at')
                            ->label('Tanggal Terbit'),
                    ])
                    ->columns(3),
                Section::make('Pengaturan SEO')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta Judul'),
                        TextInput::make('meta_keywords')
                            ->label('Meta Kata Kunci'),
                        Textarea::make('meta_description')
                            ->label('Meta Deskripsi')
                            ->columnSpanFull(),
                        FileUpload::make('og_image')
                            ->label('Gambar OG')
                            ->image(),
                        Toggle::make('is_in_sitemap')
                            ->label('Masuk Sitemap')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
