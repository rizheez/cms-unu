<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
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
                            ->placeholder('Contoh: UNU Perkuat Transformasi Digital')
                            ->required(),
                        Select::make('post_category_id')
                            ->label('Kategori Berita')
                            ->relationship('category', 'name')
                            ->placeholder('Pilih kategori berita')
                            ->searchable()
                            ->preload(),
                        Textarea::make('excerpt')
                            ->label('Ringkasan')
                            ->placeholder('Tuliskan ringkasan singkat berita')
                            ->columnSpanFull(),
                        RichEditor::make('content')
                            ->label('Konten')
                            ->placeholder('Tuliskan isi berita')
                            ->resizableImages()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull()
                    ->columns(2),
                Section::make('Media')
                    ->schema([
                        FileUpload::make('featured_image')
                            ->label('Gambar Utama')
                            ->helperText('Gambar utama akan tampil sebagai thumbnail berita.')
                            ->image()
                            ->disk('public')
                            ->directory('posts')
                            ->visibility('public'),
                    ]),
                Section::make('Publikasi')
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->placeholder('Pilih status berita')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Terbit',
                                'archived' => 'Arsip',
                            ])
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state): void {
                                if ($state === 'published') {
                                    $set('published_at', now());
                                }

                                if ($state === 'draft') {
                                    $set('published_at', null);
                                }
                            })
                            ->required()
                            ->default('draft'),
                        DateTimePicker::make('published_at')
                            ->label('Tanggal Terbit')
                            ->hidden(fn(Get $get): bool => $get('status') !== 'published')
                            ->helperText('Otomatis terisi saat status dipilih Terbit, dan kosong saat Draft.'),
                        Toggle::make('is_featured')
                            ->label('Unggulan')
                            ->helperText('Aktifkan agar berita diprioritaskan di beranda.')
                            ->required(),
                    ])
                    ->columns(2),
                Section::make('Pengaturan SEO')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta Judul')
                            ->placeholder('Judul SEO yang tampil di mesin pencari'),
                        TextInput::make('meta_keywords')
                            ->label('Meta Kata Kunci')
                            ->placeholder('Contoh: kampus, prestasi, riset'),
                        Textarea::make('meta_description')
                            ->label('Meta Deskripsi')
                            ->placeholder('Ringkasan berita untuk mesin pencari')
                            ->columnSpanFull(),
                        FileUpload::make('og_image')
                            ->label('Gambar OG')
                            ->helperText('Gambar untuk pratinjau saat berita dibagikan.')
                            ->image()
                            ->disk('public')
                            ->directory('posts/og')
                            ->visibility('public'),
                        Toggle::make('is_in_sitemap')
                            ->label('Masuk Sitemap')
                            ->helperText('Aktifkan agar berita masuk sitemap.')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
