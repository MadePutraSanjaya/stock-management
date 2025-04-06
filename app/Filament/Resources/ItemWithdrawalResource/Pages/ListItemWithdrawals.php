<?php

namespace App\Filament\Resources\ItemWithdrawalResource\Pages;

use App\Filament\Resources\ItemWithdrawalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListItemWithdrawals extends ListRecords
{
    protected static string $resource = ItemWithdrawalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
