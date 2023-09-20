<?php

namespace App\Exports;

use App\Models\Product\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class exportProductForOS implements FromView,ShouldAutoSize
{

    public function view(): View
    {
        $products=Product::with('productVariations')
            ->orderBy('name', 'asc')
            ->select('id','name', 'purchase_uom_id', 'has_variation')->get();
        return view('App.openingStock.export.exportWithData',compact('products'));
    }
}

