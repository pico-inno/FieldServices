<?php

namespace App\Http\Controllers\export;

use App\Exports\exportProductForOS;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function export()
    {
        return Excel::download(new exportProductForOS, 'OpeningStockWithData.xlsx');
    }
}
