<?php

namespace App\Filament\Resources\ItemRequestResource\Pages;

use App\Filament\Resources\ItemRequestResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
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
