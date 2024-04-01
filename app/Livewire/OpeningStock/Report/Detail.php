<?php

namespace App\Livewire\OpeningStock\Report;

use Livewire\Component;
use Livewire\WithPagination;
use App\Datatables\datatable;
use App\Exports\osDetailExport;
use Illuminate\Support\Facades\DB;
use App\Models\openingStockDetails;
use Maatwebsite\Excel\Facades\Excel;

class Detail extends Component
{
    use WithPagination,datatable;
    public function export($withFilter=true){
        $query=$this->query();
        return Excel::download(new osDetailExport($query), 'openingStockDetail.xlsx');
    }
    public function query(){
        $keyword=rtrim($this->search);
        return openingStockDetails::query()
        ->select('opening_stock_details.*','products.name as productName','variation_template_values.name as variation_name',
        'opening_stocks.opening_stock_voucher_no','business_users.username','product_variations.variation_sku',
        'uom.name as uomName',
        'uom.short_name as uomShortName',
        'opening_stocks.opening_date',
        'business_locations.name as locationName')
        ->where('opening_stock_details.is_delete',0)

        ->leftJoin('opening_stocks','opening_stocks.id','opening_stock_details.opening_stock_id')
        ->leftJoin('business_users','business_users.id','opening_stocks.opening_person')
        ->leftJoin('product_variations','product_variations.id','opening_stock_details.variation_id')
        ->leftJoin('products','products.id','opening_stock_details.product_id')
        ->leftJoin('variation_template_values','variation_template_values.id','product_variations.variation_template_value_id')
        ->leftJoin('uom','uom.id','opening_stock_details.uom_id')
        ->leftJoin('business_locations','business_locations.id','opening_stocks.business_location_id')
        ->when($keyword,function($query)use($keyword){
            // $query->orWhereRaw("CONCAT(pf.first_name,' ', pf.last_name ) LIKE ?", ['%' . $search . '%'])
            $query->whereRaw("CONCAT(products.name, IFNULL(CONCAT(' (', variation_template_values.name, ') '), ''))  Like ?",['%' . $keyword . '%'])
                ->orWhere("opening_stocks.opening_stock_voucher_no", 'like', '%' . $keyword )
                ->orWhere("product_variations.variation_sku", 'like', '%' . $keyword );
        })
        ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');

    }
    public function render()
    {
        $openingStockDetails=$this->query()->paginate($this->perPage);
        return view('livewire.OpeningStock.report.detail-table',compact('openingStockDetails'));
    }
}
