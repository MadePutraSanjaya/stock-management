<?php

namespace App\Filament\PegawaiKeuangan\Resources\ItemRequestResource\Pages;

use App\Filament\PegawaiKeuangan\Resources\ItemRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListItemRequests extends ListRecords
{
    protected static string $resource = ItemRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
