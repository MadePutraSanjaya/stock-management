<?php

namespace App\Http\Controllers;

use App\Exports\ItemEntriesExport;
use App\Exports\ItemReportExport;
use App\Exports\ItemRequestExport;
use App\Exports\ItemWithdrawalExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class Download extends Controller
{
    public function ItemEntry(Request $request)
    {
        $now = now();

        return Excel::download(new ItemEntriesExport($request->all()), "item-entry-$now.xlsx");
    }

    public function ItemWithdrawal(Request $request)
    {
        $now = now();

        return Excel::download(new ItemWithdrawalExport($request->all()), "item-withdrawal-$now.xlsx");
    }

    public function ItemReport(Request $request)
    {
        $now = now();

        return Excel::download(new ItemReportExport($request->all()), "item-report-$now.xlsx");
    }

    public function ItemRequest(Request $request)
    {
        $now = now();

        return Excel::download(new ItemRequestExport($request->all()), "item-request-$now.xlsx");
    }
}
