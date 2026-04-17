<?php

namespace App\Filament\Resources\Partners\Schemas;

use Filament\Forms\Components\FileUpload;
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
                        FileUpload::make('logo')
                            ->label('Logo')
                            ->helperText('Upload logo mitra dalam format gambar.')
                            ->image()
                            ->disk('public')
                            ->directory('partners')
                            ->visibility('public')
                            ->required(),
                    ])
                    ->columns(2),
                Section::make('Pengaturan')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->helperText('Hanya mitra aktif yang ditampilkan.')
                            ->required(),
                    ])
                    ->columns(1),
            ]);
    }
}
