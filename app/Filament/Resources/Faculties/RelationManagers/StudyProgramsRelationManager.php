<?php

namespace App\Filament\Resources\Faculties\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StudyProgramsRelationManager extends RelationManager
{
    protected static string $relationship = 'studyPrograms';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                TextInput::make('degree_level')
                    ->label('Jenjang')
                    ->placeholder('Contoh: S1, S2')
                    ->maxLength(255),
                TextInput::make('head_name')
                    ->label('Kepala Program Studi')
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->label('Gambar')
                    ->directory('study-programs')
                    ->image(),
                TextInput::make('accreditation')
                    ->label('Akreditasi')
                    ->maxLength(255),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('order')
            ->reorderable('order')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('degree_level')
                    ->label('Jenjang')
                    ->searchable(),
                TextColumn::make('head_name')
                    ->label('Kepala Prodi')
                    ->searchable(),
                ImageColumn::make('image')
                    ->label('Gambar'),
                TextColumn::make('accreditation')
                    ->label('Akreditasi')
                    ->searchable(),
                TextColumn::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
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
