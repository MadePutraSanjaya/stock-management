<?php

namespace App\Filament\PegawaiKeuangan\Resources\ItemEntryResource\Pages;

use App\Filament\PegawaiKeuangan\Resources\ItemEntryResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Colors\Color;

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
