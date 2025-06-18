<?php

namespace App\Filament\PegawaiKeuangan\Resources\ItemRequestResource\Pages;

use App\Filament\PegawaiKeuangan\Resources\ItemRequestResource;
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
