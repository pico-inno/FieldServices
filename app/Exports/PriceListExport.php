<?php

namespace App\Exports;

use App\Models\Product\Product;
use Illuminate\Contracts\View\View;
use App\Models\Product\PriceListDetails;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PriceListExport implements FromView, ShouldAutoSize
{
    protected $priceListId;
    public function __construct($id)
    {
        $this->priceListId=$id;
    }
    public function view(): View
    {
        try {
            $priceListDetail = PriceListDetails::where('pricelist_id', $this->priceListId)->get();
            // dd($priceListDetail);
            return view('App.openingStock.export.priceList4Edit', compact('priceListDetail'));
        } catch (\Throwable $th) {
           return back()->with(['error'=>$th->getMessage()]);
        }
    }
}
