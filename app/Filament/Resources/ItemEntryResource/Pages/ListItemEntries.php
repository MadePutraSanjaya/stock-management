<?php

namespace App\Filament\Resources\ItemEntryResource\Pages;

use App\Filament\Resources\ItemEntryResource;
use Filament\Actions;
use Filament\Support\Colors\Color;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListItemEntries extends ListRecords
{
    protected static string $resource = ItemEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
            ->color(Color::Green)
            ->url(route('admin.download.item-entries')),
            Actions\CreateAction::make(),
        ];
    }
}
