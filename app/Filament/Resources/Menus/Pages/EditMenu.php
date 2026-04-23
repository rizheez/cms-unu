<?php

namespace App\Filament\Resources\Menus\Pages;

use App\Filament\Resources\Menus\MenuResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMenu extends EditRecord
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('builder')
                ->label('Builder Menu')
                ->icon('heroicon-o-arrows-up-down')
                ->url(fn (): string => MenuResource::getUrl('builder', ['record' => $this->getRecord()])),
            DeleteAction::make(),
        ];
    }
}
