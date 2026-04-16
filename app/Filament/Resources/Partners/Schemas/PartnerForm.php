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
                            ->required(),
                        TextInput::make('website')
                            ->label('Website')
                            ->url(),
                        TextInput::make('logo')
                            ->label('Logo')
                            ->required(),
                    ])
                    ->columns(2),
                Section::make('Pengaturan')
                    ->schema([
                        TextInput::make('order')
                            ->label('Urutan')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
