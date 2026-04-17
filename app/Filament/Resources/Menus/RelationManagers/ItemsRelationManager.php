<?php

namespace App\Filament\Resources\Menus\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('parent_id')
                    ->label('Induk Menu')
                    ->options(fn (): array => $this->getOwnerRecord()
                        ->items()
                        ->pluck('label', 'id')
                        ->all())
                    ->placeholder('Menu utama')
                    ->searchable()
                    ->preload()
                    ->helperText('Kosongkan untuk menu utama.'),
                TextInput::make('label')
                    ->label('Label')
                    ->placeholder('Contoh: Berita')
                    ->required()
                    ->maxLength(255),
                Select::make('page_id')
                    ->label('Halaman')
                    ->relationship('page', 'title')
                    ->placeholder('Pilih halaman internal')
                    ->searchable()
                    ->preload()
                    ->helperText('Jika dipilih, link menu otomatis memakai slug halaman.'),
                TextInput::make('url')
                    ->label('URL')
                    ->placeholder('Contoh: /berita atau https://example.com')
                    ->maxLength(255),
                Select::make('target')
                    ->label('Target')
                    ->options([
                        '_self' => 'Tab yang sama',
                        '_blank' => 'Tab baru',
                    ])
                    ->default('_self')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->defaultSort('order')
            ->reorderable('order')
            ->columns([
                TextColumn::make('parent.label')
                    ->label('Induk Menu')
                    ->placeholder('Menu utama')
                    ->searchable(),
                TextColumn::make('page.title')
                    ->label('Halaman')
                    ->placeholder('URL custom')
                    ->searchable(),
                TextColumn::make('label')
                    ->label('Label')
                    ->searchable(),
                TextColumn::make('url')
                    ->label('URL')
                    ->searchable(),
                TextColumn::make('target')
                    ->label('Target')
                    ->badge(),
                TextColumn::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
