<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Services\EditorJsContentRenderer;
use Athphane\FilamentEditorjs\Forms\Components\EditorjsTextField;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
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
                            ->placeholder('Contoh: Profil Universitas')
                            ->required(),
                        EditorjsTextField::make('content')
                            ->label('Konten')
                            ->placeholder('Tuliskan isi halaman')
                            ->formatStateUsing(fn (mixed $state): ?array => app(EditorJsContentRenderer::class)->toEditorJsDocument($state))
                            ->minHeight(480)
                            ->tools('default')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Pengaturan Halaman')
                    ->schema([
                        TextInput::make('template')
                            ->label('Template')
                            ->placeholder('Contoh: default')
                            ->required()
                            ->default('default'),
                        Select::make('status')
                            ->label('Status')
                            ->placeholder('Pilih status halaman')
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
                    ])
                    ->columns(3),
                Section::make('Pengaturan SEO')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta Judul')
                            ->helperText('Opsional. Jika kosong, otomatis memakai judul halaman.')
                            ->placeholder('Judul SEO yang tampil di mesin pencari'),
                        TextInput::make('meta_keywords')
                            ->label('Meta Kata Kunci')
                            ->helperText('Opsional. Jika kosong, otomatis dibuat dari judul dan isi halaman.')
                            ->placeholder('Contoh: kampus, universitas, pendidikan'),
                        Textarea::make('meta_description')
                            ->label('Meta Deskripsi')
                            ->helperText('Opsional. Jika kosong, otomatis memakai beberapa kata pertama dari isi halaman.')
                            ->placeholder('Ringkasan halaman untuk mesin pencari')
                            ->columnSpanFull(),
                        FileUpload::make('og_image')
                            ->label('Gambar OG')
                            ->helperText('Opsional. Jika kosong, otomatis memakai logo situs.')
                            ->image()
                            ->disk('public')
                            ->directory('pages')
                            ->visibility('public'),
                    ])
                    ->columns(2),
            ]);
    }
}
