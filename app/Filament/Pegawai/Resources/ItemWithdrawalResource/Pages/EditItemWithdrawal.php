<?php

namespace App\Filament\Pegawai\Resources\ItemWithdrawalResource\Pages;

use App\Filament\Pegawai\Resources\ItemWithdrawalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItemWithdrawal extends EditRecord
{
    protected static string $resource = ItemWithdrawalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
