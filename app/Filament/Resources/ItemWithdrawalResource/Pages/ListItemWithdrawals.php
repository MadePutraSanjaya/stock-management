<?php

namespace App\Filament\Resources\ItemWithdrawalResource\Pages;

use App\Filament\Resources\ItemWithdrawalResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Colors\Color;

class ListItemWithdrawals extends ListRecords
{
    protected static string $resource = ItemWithdrawalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
            ->color(Color::Green)
            ->url(route('admin.download.item-drawals')),
            Actions\CreateAction::make(),
        ];
    }
}
