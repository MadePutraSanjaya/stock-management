<?php

namespace App\Exports;

use App\Models\ItemRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemRequestExport implements FromCollection, WithHeadings, WithStyles
{
    private array $filter;

    public function __construct(array $filter = [])
    {
        $this->filter = $filter;
    }

    public function headings(): array
    {
        return [
            'Requested By',
            'Title',
            'Description',
            'Quantity',
            'Status',
            'Approved By',
            'Approved At',
        ];
    }

    public function collection()
    {
        $query = ItemRequest::query();

        if (isset($this->filter['user_id']) && ! empty($this->filter['user_id'])) {
            $query->where('user_id', $this->filter['user_id']);
        }

        if (isset($this->filter['status']) && ! empty($this->filter['status'])) {
            $query->where('status', $this->filter['status']);
        }

        if (isset($this->filter['start_date']) && ! empty($this->filter['start_date'])) {
            $query->whereDate('created_at', '>=', $this->filter['start_date']);
        }

        if (isset($this->filter['end_date']) && ! empty($this->filter['end_date'])) {
            $query->whereDate('created_at', '<=', $this->filter['end_date']);
        }

        return $query
            ->with(['user', 'approvedBy'])
            ->orderBy($this->filter['orderBy'] ?? 'created_at', $this->filter['order_type'] ?? 'DESC')
            ->get()
            ->map(function ($itemRequest) {
                return [
                    $itemRequest->user->nama_lengkap ?? 'N/A',
                    $itemRequest->title,
                    $itemRequest->description,
                    $itemRequest->quantity,
                    ucfirst($itemRequest->status),
                    $itemRequest->approvedBy->nama_lengkap ?? 'N/A',
                    optional($itemRequest->approved_at)->format('d-m-Y') ?? 'N/A',
                ];
            });
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1')->getFont()->setBold(true);
    }
}
