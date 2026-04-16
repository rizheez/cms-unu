<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestPosts extends TableWidget
{
    protected static ?string $heading = 'Artikel Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Post::query()->with(['category', 'author'])->latest()->limit(5))
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('published_at')
                    ->label('Terbit')
                    ->dateTime('d M Y H:i'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
