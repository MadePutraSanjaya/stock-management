<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;

class ItemReportExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private array $filter;

    public function __construct(array $filter = [])
    {
        $this->filter = $filter;
    }

    public function headings(): array
    {
        return [
            'Name',
            'Stock',
            'Unit',
        ];
    }


    public function collection()
    {
        $query = Item::query();

        if (isset($this->filter['start_date']) && !empty($this->filter['start_date'])) {
            $query->whereDate('created_at', '>=', $this->filter['start_date']);
        }

        if (isset($this->filter['end_date']) && !empty($this->filter['end_date'])) {
            $query->whereDate('created_at', '<=', $this->filter['end_date']);
        }

        return $query
            ->orderBy($this->filter['orderBy'] ?? 'created_at', $this->filter['order_type'] ?? 'DESC')
            ->get()
            ->map(function ($item) {
                return [
                    $item->name,
                    $item->stock,
                    $item->unit,
                ];
            });
    }
    
}
