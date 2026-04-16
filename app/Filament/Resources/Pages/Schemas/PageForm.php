<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul')
                    ->required(),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required(),
                RichEditor::make('content')
                    ->label('Konten')
                    ->columnSpanFull(),
                TextInput::make('template')
                    ->label('Template')
                    ->required()
                    ->default('default'),
                TextInput::make('status')
                    ->label('Status')
                    ->required()
                    ->default('draft'),
                TextInput::make('meta_title')
                    ->label('Meta Judul'),
                Textarea::make('meta_description')
                    ->label('Meta Deskripsi')
                    ->columnSpanFull(),
                TextInput::make('meta_keywords')
                    ->label('Meta Kata Kunci'),
                FileUpload::make('og_image')
                    ->label('Gambar OG')
                    ->image(),
                Toggle::make('is_in_sitemap')
                    ->label('Masuk Sitemap')
                    ->required(),
                DateTimePicker::make('published_at')
                    ->label('Tanggal Terbit'),
            ]);
    }
}
