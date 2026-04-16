<?php

namespace App\Filament\Resources\Partners\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PartnerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Mitra')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->placeholder('Contoh: Bank Syariah Indonesia')
                            ->required(),
                        TextInput::make('website')
                            ->label('Website')
                            ->placeholder('Contoh: https://bankbsi.co.id')
                            ->url(),
                        TextInput::make('logo')
                            ->label('Logo')
                            ->placeholder('Contoh: partners/logo-bsi.png')
                            ->required(),
                    ])
                    ->columns(2),
                Section::make('Pengaturan')
                    ->schema([
                        TextInput::make('order')
                            ->label('Urutan')
                            ->helperText('Angka kecil akan tampil lebih dulu.')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->helperText('Hanya mitra aktif yang ditampilkan.')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
