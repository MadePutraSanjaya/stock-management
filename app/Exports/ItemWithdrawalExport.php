<?php

namespace App\Exports;

use App\Models\ItemWithdrawal;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemWithdrawalExport implements FromCollection, WithHeadings, WithStyles
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
            'Withdrawal Date',
            'Purpose',
            'Taken By',
            'Created At',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = ItemWithdrawal::query();

        if (isset($this->filter['item_id']) && ! empty($this->filter['item_id'])) {
            $query->where('item_id', $this->filter['item_id']);
        }

        if (isset($this->filter['start_date']) && ! empty($this->filter['start_date'])) {
            $query->where('withdrawal_date', '>=', $this->filter['start_date']);
        }

        if (isset($this->filter['end_date']) && ! empty($this->filter['end_date'])) {
            $query->where('withdrawal_date', '<=', $this->filter['end_date']);
        }

        return $query
            ->with(['item', 'user'])  // Eager load relationships to avoid N+1 query issues
            ->orderBy($this->filter['orderBy'] ?? 'created_at', $this->filter['order_type'] ?? 'DESC')
            ->get()
            ->map(function ($itemWithdrawal) {
                // Format the date safely
                $withdrawalDate = $itemWithdrawal->withdrawal_date;
                if ($withdrawalDate instanceof \Carbon\Carbon) {
                    $formattedWithdrawalDate = $withdrawalDate->format('d-m-Y');
                } else if (is_string($withdrawalDate) && !empty($withdrawalDate)) {
                    // Convert string to Carbon if possible
                    try {
                        $formattedWithdrawalDate = Carbon::parse($withdrawalDate)->format('d-m-Y');
                    } catch (\Exception $e) {
                        $formattedWithdrawalDate = $withdrawalDate;
                    }
                } else {
                    $formattedWithdrawalDate = 'N/A';
                }

                // Format the created_at date safely
                $createdAt = $itemWithdrawal->created_at;
                if ($createdAt instanceof \Carbon\Carbon) {
                    $formattedCreatedAt = $createdAt->format('d-m-Y');
                } else if (is_string($createdAt) && !empty($createdAt)) {
                    try {
                        $formattedCreatedAt = Carbon::parse($createdAt)->format('d-m-Y');
                    } catch (\Exception $e) {
                        $formattedCreatedAt = $createdAt;
                    }
                } else {
                    $formattedCreatedAt = 'N/A';
                }

                return [
                    $itemWithdrawal->item->name ?? 'N/A',
                    $itemWithdrawal->quantity,
                    $formattedWithdrawalDate,
                    $itemWithdrawal->purpose,
                    $itemWithdrawal->user->nama_lengkap ?? 'N/A',
                    $formattedCreatedAt,
                ];
            });
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1')->getFont()->setBold(true);
    }
}