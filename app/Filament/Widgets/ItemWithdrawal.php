<?php

namespace App\Filament\Widgets;

use App\Models\ItemWithdrawal as ItemWithdrawalModel;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ItemWithdrawal extends ChartWidget
{
    protected static ?string $heading = 'Item Withdrawal Chart';
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = ItemWithdrawalModel::select(
            DB::raw('DATE(withdrawal_date) as date'),
            DB::raw('SUM(quantity) as total_quantity')
        )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Barang Keluar',
                    'data' => $data->pluck('total_quantity'),
                    'borderColor' => 'rgb(255, 99, 132)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
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
