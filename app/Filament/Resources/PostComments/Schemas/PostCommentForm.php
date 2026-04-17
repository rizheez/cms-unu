<?php

declare(strict_types=1);

namespace App\Filament\Resources\PostComments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PostCommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Komentar')
                    ->schema([
                        Select::make('post_id')
                            ->label('Berita')
                            ->relationship('post', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('name')
                            ->label('Nama Pengirim')
                            ->placeholder('Anonim')
                            ->maxLength(80),
                        Textarea::make('body')
                            ->label('Isi Komentar')
                            ->required()
                            ->rows(7)
                            ->maxLength(1200)
                            ->columnSpanFull(),
                        Toggle::make('is_approved')
                            ->label('Tampilkan di website')
                            ->helperText('Matikan jika komentar perlu disembunyikan tanpa dihapus.')
                            ->default(true),
                    ])
                    ->columns(2),
                Section::make('Informasi Teknis')
                    ->schema([
                        TextInput::make('ip_address')
                            ->label('IP Address')
                            ->disabled()
                            ->dehydrated(false),
                        Textarea::make('user_agent')
                            ->label('User Agent')
                            ->rows(3)
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2),
            ]);
    }
}
