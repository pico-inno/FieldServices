<?php

namespace App\Http\Controllers\export;

use App\Exports\exportProductForOS;
use App\Exports\ProductExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function export()
    {
        return Excel::download(new exportProductForOS, 'OpeningStockWithData.xlsx');
    }

    public function productListExport()
    {
        return Excel::download(new ProductExport, 'AllProductList.xlsx');
    }
    public function priceListForProduct()
    {
        return Excel::download(new ProductExport, 'AllProductList.xlsx');
    }
}
