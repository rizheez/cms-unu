<?php

declare(strict_types=1);

namespace App\Filament\Resources\PostComments\Tables;

use App\Models\PostComment;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PostCommentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('post.title')
                    ->label('Berita')
                    ->limit(44)
                    ->searchable()
                    ->sortable(),
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
                TextColumn::make('ip_address')
                    ->label('IP')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Masuk')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_approved')
                    ->label('Status tampil')
                    ->trueLabel('Tampil di website')
                    ->falseLabel('Disembunyikan')
                    ->native(false),
                SelectFilter::make('post_id')
                    ->label('Berita')
                    ->relationship('post', 'title')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                Action::make('openPost')
                    ->label('Buka Berita')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (PostComment $record): string => route('news.show', $record->post))
                    ->openUrlInNewTab(),
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
                DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Hapus komentar terpilih'),
                ]),
            ]);
    }
}
