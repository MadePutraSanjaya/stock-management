<?php

namespace App\Filament\PegawaiKeuangan\Resources\WithdrawItemResource\Pages;

use App\Filament\PegawaiKeuangan\Resources\WithdrawItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWithdrawItem extends EditRecord
{
    protected static string $resource = WithdrawItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
