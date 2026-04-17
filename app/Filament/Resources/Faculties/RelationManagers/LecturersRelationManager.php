<?php

namespace App\Filament\Resources\Faculties\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LecturersRelationManager extends RelationManager
{
    protected static string $relationship = 'lecturers';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                TextInput::make('nidn')
                    ->label('NIDN')
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->maxLength(255),
                TextInput::make('position')
                    ->label('Jabatan')
                    ->maxLength(255),
                Select::make('study_program_id')
                    ->label('Program Studi')
                    ->options(fn (): array => $this->getOwnerRecord()
                        ->studyPrograms()
                        ->pluck('name', 'id')
                        ->all())
                    ->placeholder('Pilih program studi')
                    ->searchable()
                    ->preload(),
                TextInput::make('education_level')
                    ->label('Pendidikan Terakhir')
                    ->maxLength(255),
                TextInput::make('expertise')
                    ->label('Keahlian')
                    ->maxLength(255),
                FileUpload::make('photo')
                    ->label('Foto')
                    ->directory('lecturers')
                    ->image()
                    ->disk('public')
                    ->visibility('public'),
                RichEditor::make('bio')
                    ->label('Biografi')
                    ->columnSpanFull(),
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
                TextColumn::make('studyProgram.name')
                    ->label('Program Studi')
                    ->searchable(),
                TextColumn::make('nidn')
                    ->label('NIDN')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                ImageColumn::make('photo')
                    ->label('Foto'),
                TextColumn::make('expertise')
                    ->label('Keahlian')
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
