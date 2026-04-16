<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class UnreadMessages extends TableWidget
{
    protected static ?string $heading = 'Pesan Belum Dibaca';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => ContactMessage::query()->where('status', 'unread')->latest()->limit(5))
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('subject')
                    ->label('Subjek')
                    ->limit(35)
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Masuk')
                    ->since(),
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
