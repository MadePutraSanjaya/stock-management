<?php

namespace App\Filament\PegawaiKeuangan\Resources\WithdrawItemResource\Pages;

use App\Filament\PegawaiKeuangan\Resources\WithdrawItemResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Colors\Color;

class ListWithdrawItems extends ListRecords
{
    protected static string $resource = WithdrawItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
            ->color(Color::Green)
            ->url(route('admin.download.item-drawals')),
            Actions\CreateAction::make(),
        ];
    }
}
