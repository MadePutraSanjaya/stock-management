<?php

namespace App\Filament\Pegawai\Resources\ItemRequestResource\Pages;

use App\Filament\Pegawai\Resources\ItemRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Colors\Color;
use Filament\Actions\Action;

class ListItemRequests extends ListRecords
{
    protected static string $resource = ItemRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->color(Color::Green)
                ->url(route('admin.download.item-request')),
            Actions\CreateAction::make(),
        ];
    }
}
