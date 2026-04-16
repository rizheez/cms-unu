<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Pengirim')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->required(),
                        TextInput::make('email')
                            ->label('Alamat Email')
                            ->email()
                            ->required(),
                        TextInput::make('phone')
                            ->label('Telepon')
                            ->tel(),
                    ])
                    ->columns(3),
                Section::make('Isi Pesan')
                    ->schema([
                        TextInput::make('subject')
                            ->label('Subjek')
                            ->required(),
                        Textarea::make('message')
                            ->label('Pesan')
                            ->required()
                            ->columnSpanFull(),
                    ]),
                Section::make('Status Pembacaan')
                    ->schema([
                        TextInput::make('status')
                            ->label('Status')
                            ->required()
                            ->default('unread'),
                        DateTimePicker::make('read_at')
                            ->label('Dibaca Pada'),
                    ])
                    ->columns(2),
            ]);
    }
}
