<?php

namespace App\Filament\PegawaiKeuangan\Resources\ItemResource\Pages;

use App\Filament\PegawaiKeuangan\Resources\ItemResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Colors\Color;

class ListItems extends ListRecords
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
            ->color(Color::Green)
            ->url(route('admin.download.item-report')),
            Actions\CreateAction::make(),
        ];
    }
}
