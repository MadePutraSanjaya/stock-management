<?php

namespace App\Filament\PegawaiKeuangan\Resources\UserResource\Pages;

use App\Filament\PegawaiKeuangan\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
