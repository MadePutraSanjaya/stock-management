<?php

namespace App\Filament\PegawaiKeuangan\Resources\ItemResource\Pages;

use App\Filament\PegawaiKeuangan\Resources\ItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItem extends EditRecord
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
