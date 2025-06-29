<?php

namespace App\Filament\Widgets;

use App\Models\ItemEntry as ItemEntryModel;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ItemEntry extends ChartWidget
{
    protected static ?string $heading = 'Item Entry Chart';
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 3;


    protected function getData(): array
    {
        $data = ItemEntryModel::select(
            DB::raw('DATE(entry_date) as date'),
            DB::raw('SUM(quantity) as total_quantity')
        )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Barang Masuk',
                    'data' => $data->pluck('total_quantity'),
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data->pluck('date')->map(fn($date) => date('d M', strtotime($date))),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
