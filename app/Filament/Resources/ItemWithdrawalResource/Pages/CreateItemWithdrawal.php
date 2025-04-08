<?php

namespace App\Filament\Resources\ItemWithdrawalResource\Pages;

use App\Filament\Resources\ItemWithdrawalResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateItemWithdrawal extends CreateRecord
{
    protected static string $resource = ItemWithdrawalResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['taken_by'] = Auth::id();
        return $data;
    }
}
