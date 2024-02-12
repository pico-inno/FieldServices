<?php

namespace App\Exports;

use App\Models\sale\sale_details;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class productItemReport implements FromView,ShouldAutoSize
{

    public $filterData;
    public $withFilter;
    public function __construct($filterData,$withFilter)
    {
        $this->filterData=$filterData;
        $this->withFilter= $withFilter;
    }
    public function query(){
        $defaultCampaignId=$this->filterData['defaultCampaignId'];
        return sale_details::query()
        ->select(
        'products.name',
        'uom.short_name as uom',
        'categories.name as category_name',
            DB::raw("SUM(stock_histories.decrease_qty) as totalQty"),
            DB::raw("SUM(total_sale_amount) as totalPrice")
        )
        ->leftJoin('sales', 'sale_details.sales_id', '=', 'sales.id')
        ->where('sales.channel_type', '=', 'campaign')
        ->leftJoin('stock_histories', 'stock_histories.transaction_details_id', 'sale_details.id')
        ->leftJoin('products', 'stock_histories.product_id', 'products.id')
        ->leftJoin('uom', 'stock_histories.ref_uom_id', 'uom.id')
        ->where( 'stock_histories.transaction_type', '=','sale')
        ->leftJoin('fscampaign', 'sales.channel_id', 'fscampaign.id')
        ->leftJoin('categories', 'products.category_id', 'categories.id')
        ->leftJoin('business_locations  as outlet', 'fscampaign.business_location_id', '=', 'outlet.id')
        ->leftJoin('receipe_of_material_details', 'receipe_of_material_details.component_variation_id', 'stock_histories.product_id')
        ->where('sale_details.is_delete','=', 0)
        ->where('sales.is_delete','=',0)
        ->where('receipe_of_material_details.id',null)

        ->groupBy('sale_details.variation_id','products.name', 'categories.name', 'uom.short_name')
        ->when($defaultCampaignId !== null, function ($query) use ($defaultCampaignId) {
            $query->where('fscampaign.id','=',$defaultCampaignId);
        });
    }
    public function view(): View
    {
        $categotryFilterId = $this->filterData['categotryFilterId'];
        $filterDate = $this->filterData['filterDate'];
        $campaignFilterId = $this->filterData['campaignFilterId'];
        $withFilter=$this->withFilter;
        $datas= $this->query()
            ->when($withFilter,function($q)use($filterDate,$campaignFilterId,$categotryFilterId){
                $q->when(isset($filterDate), function ($query) use ($filterDate) {
                    $query->whereDate('sales.sold_at', '>=', $filterDate[0])
                        ->whereDate('sales.sold_at', '<=', $filterDate[1]);
                })
                ->when($campaignFilterId != 'all', function ($query) use ($campaignFilterId) {
                    $query->where('fscampaign.id','=',$campaignFilterId);
                })
                ->when($categotryFilterId != 'all', function ($query) use ($categotryFilterId) {
                    $query->where('categories.id','=',$categotryFilterId);
                });
            })
            ->get();
        return view('App.fieldService.Export.productItemReport',compact('datas'));
    }

}
