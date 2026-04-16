<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class RecentActivity extends TableWidget
{
    protected static ?string $heading = 'Aktivitas Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Activity::query()->latest()->limit(5))
            ->columns([
                TextColumn::make('description')
                    ->label('Aktivitas')
                    ->limit(50),
                TextColumn::make('event')
                    ->label('Event')
                    ->badge(),
                TextColumn::make('causer.name')
                    ->label('Pengguna')
                    ->placeholder('-'),
                TextColumn::make('created_at')
                    ->label('Waktu')
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
