<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('category.name')
                    ->label('Kategori Berita')
                    ->searchable(),
                TextColumn::make('author.name')
                    ->label('Penulis')
                    ->searchable(),
                TextColumn::make('title')
                    ->label('Judul')
                    ->limit(50)
                    ->searchable(),
                // TextColumn::make('slug')
                //     ->label('Slug')
                //     ->searchable(),
                ImageColumn::make('featured_image')
                    ->label('Gambar Utama'),
                TextColumn::make('status')
                    ->label('Status')
                    ->searchable(),
                IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean(),
                TextColumn::make('views')
                    ->label('Jumlah Dilihat')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('published_at')
                    ->label('Tanggal Terbit')
                    ->dateTime('d F Y H:i:s', 'Asia/Makassar')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d F Y H:i:s', 'Asia/Makassar')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d F Y H:i:s', 'Asia/Makassar')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label('Dihapus')
                    ->dateTime('d F Y H:i:s', 'Asia/Makassar')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('publish')
                    ->label('Terbitkan')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Post $record): bool => $record->status === 'draft' && ! $record->trashed())
                    ->action(fn (Post $record): bool => $record->update([
                        'status' => 'published',
                        'published_at' => $record->published_at ?? now(),
                    ])),
                Action::make('toggle_featured')
                    ->label(fn (Post $record): string => $record->is_featured ? 'Nonaktifkan Unggulan' : 'Jadikan Unggulan')
                    ->icon(fn (Post $record): string => $record->is_featured ? 'heroicon-m-x-mark' : 'heroicon-m-star')
                    ->color(fn (Post $record): string => $record->is_featured ? 'gray' : 'warning')
                    ->visible(fn (Post $record): bool => ! $record->trashed())
                    ->requiresConfirmation()
                    ->action(fn (Post $record): bool => $record->update([
                        'is_featured' => ! $record->is_featured,
                    ])),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
