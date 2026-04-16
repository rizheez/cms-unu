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
                Select::make('page_id')
                    ->relationship('page', 'title')
                    ->searchable()
                    ->preload()
                    ->helperText('Pilih halaman internal. Jika dipilih, link menu otomatis memakai slug halaman ini.'),
                TextInput::make('label')
                    ->required()
                    ->maxLength(255),
                TextInput::make('url')
                    ->helperText('Opsional untuk link custom seperti /akademik, /kontak, atau URL lengkap. Abaikan jika sudah memilih Page.')
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
