<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Pengguna')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->placeholder('Contoh: Administrator UNU')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Alamat Email')
                            ->placeholder('Contoh: admin@unu.ac.id')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        DateTimePicker::make('email_verified_at')
                            ->label('Email Diverifikasi Pada')
                            ->helperText('Kosongkan jika email belum diverifikasi.'),
                    ])
                    ->columns(2),
                Section::make('Keamanan')
                    ->schema([
                        TextInput::make('password')
                            ->label('Kata Sandi')
                            ->placeholder('Isi kata sandi baru')
                            ->password()
                            ->revealable()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->helperText('Saat edit, kosongkan jika tidak ingin mengganti kata sandi.')
                            ->maxLength(255),
                    ]),
                Section::make('Hak Akses')
                    ->schema([
                        Select::make('roles')
                            ->label('Peran')
                            ->relationship('roles', 'name')
                            ->placeholder('Pilih peran pengguna')
                            ->multiple()
                            ->preload()
                            ->searchable(),
                    ]),
            ]);
    }
}
