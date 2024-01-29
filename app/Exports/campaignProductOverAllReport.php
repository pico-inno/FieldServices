<?php

namespace App\Exports;

use App\Models\sale\sale_details;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;

class campaignProductOverAllReport implements FromView,ShouldAutoSize
{

    public $filterData;
    public $withFilter;
    public $deraultCampaignId;
    public function __construct($filterData,$withFilter)
    {
        $this->filterData=$filterData;
        $this->withFilter= $withFilter;
    }
    public function query(){
        $sortField=$this->filterData['sortField'] ?? 'sale_details.created_at';
        $sortAsc = $this->filterData['sortAsc'] ?? 'desc';
        $deraultCampaignId=$this->deraultCampaignId;
        return sale_details::query()
            ->select(
                'sale_details.variation_id',
                'fscampaign.name as campaign_name',
                'fscampaign.id as campaign_id',
                'fscampaign.business_location_id as campaign_business_location_id',
                'sales.business_location_id as sales_business_location_id',
                'products.name',
                'product_variations.variation_sku',
                'sales.created_by',
                'sale_details.quantity as quantity',
                'product_packaging_transactions.product_packaging_id',
                'product_packaging_transactions.quantity as pkgQty',
                'product_packagings.packaging_name as pkg',
                'sales.created_at',
                'outlet.name as outlet',
                'categories.name as category_name',
                'uom.short_name as uom',
                'pf.first_name as pg_fs',
                'pf.last_name as pg_ls',
                'pg.username as pg_name',
                'fscampaign.name as campaignName'
            )
            ->orderBy($sortField, $sortAsc ? 'asc' : 'desc')
            ->leftJoin('sales', 'sale_details.sales_id', '=', 'sales.id')
            ->where('sales.channel_type', '=', 'campaign')
            ->leftJoin('fscampaign', 'sales.channel_id', 'fscampaign.id')
            ->leftJoin('product_variations', 'sale_details.variation_id', 'product_variations.id')
            ->leftJoin('products', 'sale_details.product_id', 'products.id')
            ->leftJoin('uom', 'sale_details.uom_id', 'uom.id')

            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->leftJoin(
                'product_packaging_transactions',
                function ($join) {
                    $join->on('sale_details.id', '=', 'product_packaging_transactions.transaction_details_id')
                    ->where('product_packaging_transactions.transaction_type', '=', 'sale');
                }
            )
            ->leftJoin('product_packagings', 'product_packaging_transactions.product_packaging_id', '=', 'product_packagings.id')
            ->leftJoin('business_locations  as outlet', 'fscampaign.business_location_id', '=', 'outlet.id')
            ->leftJoin('business_users  as pg', 'sales.created_by', '=', 'pg.id')
            ->leftJoin('personal_infos  as pf', 'pg.personal_info_id', '=', 'pf.id')
            ->when($deraultCampaignId,function($query)use($deraultCampaignId){
                $query->where('fscampaign.id', $deraultCampaignId);
            });
    }
    public function view(): View
    {

        $businesslocationFilterId = $this->filterData['businesslocationFilterId'];
        $pgFilterId = $this->filterData['pgFilterId'];
        $categotryFilterId = $this->filterData['categotryFilterId'];
        $filterDate = $this->filterData['filterDate'];
        $campaignFilterId = $this->filterData['campaignFilterId'];
        $datas= $this->query()
            ->when($this->withFilter,function($query)use($businesslocationFilterId, $pgFilterId, $campaignFilterId, $categotryFilterId, $filterDate){
                $query->when($businesslocationFilterId != 'all', function ($query) use ($businesslocationFilterId) {
                        $query->where('outlet.id', '=', $businesslocationFilterId);
                    })
                    ->when($pgFilterId != 'all', function ($query) use ($pgFilterId) {
                        $query->where('pg.id', '=', $pgFilterId);
                    })
                    ->when($campaignFilterId != 'all', function ($query) use ($campaignFilterId) {
                        $query->where('fscampaign.id', '=', $campaignFilterId);
                    })
                    ->when($categotryFilterId != 'all', function ($query) use ($categotryFilterId) {
                        $query->where('categories.id', '=', $categotryFilterId);
                    })
                    ->when(isset($filterDate), function ($query) use ($filterDate) {
                        $query->whereDate('sales.sold_at', '>=', $filterDate[0])
                            ->whereDate('sales.sold_at', '<=', $filterDate[1]);
                    });
                })
            ->get();
        return view('App.fieldService.Export.productOverAllCampaing',compact('datas'));
    }

}
