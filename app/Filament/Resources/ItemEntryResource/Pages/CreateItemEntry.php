<?php

namespace App\Filament\Resources\ItemEntryResource\Pages;

use App\Filament\Resources\ItemEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateItemEntry extends CreateRecord
{
    protected static string $resource = ItemEntryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();
        return $data;
    }
}
