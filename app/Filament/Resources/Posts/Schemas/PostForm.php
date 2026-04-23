<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Services\EditorJsContentRenderer;
use Athphane\FilamentEditorjs\Forms\Components\EditorjsTextField;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
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
                        EditorjsTextField::make('content')
                            ->label('Konten')
                            ->placeholder('Tuliskan isi berita')
                            ->formatStateUsing(fn (mixed $state): ?array => app(EditorJsContentRenderer::class)->toEditorJsDocument($state))
                            ->minHeight(480)
                            ->tools('default')
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
                            ->hidden(fn (Get $get): bool => $get('status') !== 'published')
                            ->helperText('Otomatis terisi saat status dipilih Terbit, dan kosong saat Draft.'),
                        Toggle::make('is_featured')
                            ->label('Unggulan')
                            ->helperText('Maksimal 3 berita unggulan di beranda. Saat ada unggulan baru, berita unggulan paling lama akan dinonaktifkan otomatis.')
                            ->required(),
                    ])
                    ->columns(2),
                Section::make('Pengaturan SEO')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta Judul')
                            ->helperText('Opsional. Jika kosong, otomatis memakai judul berita.')
                            ->placeholder('Judul SEO yang tampil di mesin pencari'),
                        TextInput::make('meta_keywords')
                            ->label('Meta Kata Kunci')
                            ->helperText('Opsional. Jika kosong, otomatis dibuat dari judul dan ringkasan.')
                            ->placeholder('Contoh: kampus, prestasi, riset'),
                        Textarea::make('meta_description')
                            ->label('Meta Deskripsi')
                            ->helperText('Opsional. Jika kosong, otomatis memakai ringkasan berita.')
                            ->placeholder('Ringkasan berita untuk mesin pencari')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
