<?php

namespace App\Filament\PegawaiKeuangan\Resources\ItemEntryResource\Pages;

use App\Filament\PegawaiKeuangan\Resources\ItemEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListItemEntries extends ListRecords
{
    protected static string $resource = ItemEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
