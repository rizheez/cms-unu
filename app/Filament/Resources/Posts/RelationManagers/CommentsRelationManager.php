<?php

namespace App\Filament\Resources\Posts\RelationManagers;

use App\Models\PostComment;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Pengirim')
                    ->placeholder('Anonim')
                    ->maxLength(80),
                Textarea::make('body')
                    ->label('Isi Komentar')
                    ->required()
                    ->rows(7)
                    ->maxLength(1200)
                    ->columnSpanFull(),
                Toggle::make('is_approved')
                    ->label('Tampilkan di website')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('body')
            ->columns([
                TextColumn::make('name')
                    ->label('Pengirim')
                    ->placeholder('Anonim')
                    ->searchable(),
                TextColumn::make('body')
                    ->label('Komentar')
                    ->limit(90)
                    ->lineClamp(2)
                    ->searchable(),
                IconColumn::make('is_approved')
                    ->label('Tampil')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Masuk')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_approved')
                    ->label('Status tampil')
                    ->trueLabel('Tampil di website')
                    ->falseLabel('Disembunyikan')
                    ->native(false),
            ])
            ->recordActions([
                Action::make('hide')
                    ->label('Sembunyikan')
                    ->icon('heroicon-o-eye-slash')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn (PostComment $record): bool => $record->is_approved)
                    ->action(fn (PostComment $record): bool => $record->update(['is_approved' => false])),
                Action::make('show')
                    ->label('Tampilkan')
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (PostComment $record): bool => ! $record->is_approved)
                    ->action(fn (PostComment $record): bool => $record->update(['is_approved' => true])),
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
