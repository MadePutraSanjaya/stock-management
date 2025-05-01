<?php

namespace App\Exports;

use App\Models\ItemEntry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemEntriesExport implements FromCollection, WithHeadings, WithStyles
{
    private array $filter;

    public function __construct(array $filter = [])
    {
        $this->filter = $filter;
    }

    public function headings(): array
    {
        return [
            'Item Name',
            'Quantity',
            'Entry Date',
            'Created By',
            'Notes',
            'Created At',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = ItemEntry::query();

        if (isset($this->filter['item_id']) && ! empty($this->filter['item_id'])) {
            $query->where('item_id', $this->filter['item_id']);
        }

        if (isset($this->filter['start_date']) && ! empty($this->filter['start_date'])) {
            $query->where('entry_date', '>=', $this->filter['start_date']);
        }

        if (isset($this->filter['end_date']) && ! empty($this->filter['end_date'])) {
            $query->where('entry_date', '<=', $this->filter['end_date']);
        }

        return $query
            ->with(['item', 'user'])  
            ->orderBy($this->filter['orderBy'] ?? 'created_at', $this->filter['order_type'] ?? 'DESC')
            ->get()
            ->map(function ($itemEntry) {
                return [
                    $itemEntry->item->name ?? 'N/A',
                    $itemEntry->quantity,
                    $itemEntry->entry_date->format('d-m-Y'),
                    $itemEntry->user->nama_lengkap ?? 'N/A', 
                    $itemEntry->notes,
                    $itemEntry->created_at->format('d-m-Y'),
                ];
            });
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1')->getFont()->setBold(true);
    }
}