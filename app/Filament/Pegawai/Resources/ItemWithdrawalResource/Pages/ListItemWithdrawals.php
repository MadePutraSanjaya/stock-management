<?php

namespace App\Filament\Pegawai\Resources\ItemWithdrawalResource\Pages;

use App\Filament\Pegawai\Resources\ItemWithdrawalResource;
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
