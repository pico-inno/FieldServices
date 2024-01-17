<?php

namespace App\Exports;

use App\Models\Product\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProductExport implements FromView,ShouldAutoSize
{


    public function view(): View
    {

        $products = Product::with('productVariations', 'category', 'brand', 'packaging')->get();
        return view('App.product.export.productListTemplate',compact('products'));
    }
}
