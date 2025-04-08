<?php

namespace App\Filament\PegawaiKeuangan\Resources\WithdrawItemResource\Pages;

use App\Filament\PegawaiKeuangan\Resources\WithdrawItemResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateWithdrawItem extends CreateRecord
{
    protected static string $resource = WithdrawItemResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['taken_by'] = Auth::id();
        return $data;
    }
}
