<?php

namespace App\Http\Controllers\export;

use Illuminate\Http\Request;
use App\Exports\ProductExport;
use App\Exports\PriceListExport;
use App\Exports\exportProductForOS;
use App\Exports\PriceListExportWithData;
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
    public function priceListExport($id) {

        return Excel::download(new PriceListExport($id), 'priceListForUpdate.xlsx');
    }
    public function priceListExportWithData()
    {
        return Excel::download(new PriceListExportWithData, 'priceListExportWithData.xlsx');
    }
}
