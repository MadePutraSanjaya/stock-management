<?php

namespace App\Filament\Resources\EntriesRelationManagerResource\Pages;

use App\Filament\Resources\EntriesRelationManagerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEntriesRelationManagers extends ListRecords
{
    protected static string $resource = EntriesRelationManagerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
