<?php

namespace App\Filament\Resources\MenuItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MenuItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('menu_id')
                    ->relationship('menu', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('parent_id')
                    ->relationship('parent', 'label')
                    ->searchable()
                    ->preload()
                    ->helperText('Kosongkan untuk menu utama. Pilih parent untuk membuat dropdown bertingkat.'),
                TextInput::make('label')
                    ->required()
                    ->maxLength(255),
                TextInput::make('url')
                    ->helperText('Isi path seperti /akademik, atau URL lengkap seperti https://example.com.')
                    ->maxLength(255),
                Select::make('target')
                    ->options([
                        '_self' => 'Tab yang sama',
                        '_blank' => 'Tab baru',
                    ])
                    ->default('_self')
                    ->required(),
                TextInput::make('order')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }
}
