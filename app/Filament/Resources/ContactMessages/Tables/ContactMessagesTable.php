<?php

namespace App\Filament\Resources\ContactMessages\Tables;

use App\Models\ContactMessage;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Alamat Email')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Telepon')
                    ->searchable(),
                TextColumn::make('subject')
                    ->label('Subjek')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->searchable(),
                TextColumn::make('read_at')
                    ->label('Dibaca Pada')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'unread' => 'Belum Dibaca',
                        'read' => 'Dibaca',
                    ])
                    ->native(false),
            ])
            ->recordActions([
                Action::make('markAsRead')
                    ->label('Tandai Dibaca')
                    ->color('success')
                    ->visible(fn (ContactMessage $record): bool => $record->status === 'unread')
                    ->action(fn (ContactMessage $record): bool => $record->update([
                        'status' => 'read',
                        'read_at' => now(),
                    ])),
                Action::make('markAsUnread')
                    ->label('Tandai Belum Dibaca')
                    ->color('warning')
                    ->visible(fn (ContactMessage $record): bool => $record->status === 'read')
                    ->action(fn (ContactMessage $record): bool => $record->update([
                        'status' => 'unread',
                        'read_at' => null,
                    ])),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
