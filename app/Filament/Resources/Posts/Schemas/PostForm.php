<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konten Berita')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->required(),
                        TextInput::make('post_category_id')
                            ->label('Kategori Berita')
                            ->numeric(),
                        TextInput::make('user_id')
                            ->label('Penulis')
                            ->required()
                            ->numeric(),
                        Textarea::make('excerpt')
                            ->label('Ringkasan')
                            ->columnSpanFull(),
                        RichEditor::make('content')
                            ->label('Konten')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull()
                    ->columns(2),
                Section::make('Media')
                    ->schema([
                        FileUpload::make('featured_image')
                            ->label('Gambar Utama')
                            ->image(),
                    ]),
                Section::make('Publikasi')
                    ->schema([
                        TextInput::make('status')
                            ->label('Status')
                            ->required()
                            ->default('draft'),
                        DateTimePicker::make('published_at')
                            ->label('Tanggal Terbit'),
                        Toggle::make('is_featured')
                            ->label('Unggulan')
                            ->required(),
                        TextInput::make('views')
                            ->label('Jumlah Dilihat')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
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
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
