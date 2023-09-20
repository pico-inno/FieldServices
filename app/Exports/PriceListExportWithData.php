<?php

namespace App\Exports;

use App\Models\Product\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PriceListExportWithData implements FromView, ShouldAutoSize
{

    public function view(): View
    {

        $products = Product::with('productVariations', 'category', 'brand')->get();
        return view('App.openingStock.export.priceListExportWithData', compact('products'));
    }
}
