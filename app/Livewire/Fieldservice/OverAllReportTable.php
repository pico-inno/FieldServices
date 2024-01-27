<?php

namespace App\Livewire\Fieldservice;

use Livewire\Component;
use App\Models\BusinessUser;
use Livewire\WithPagination;
use App\Datatables\datatable;
use App\Models\Product\Category;
use App\Models\sale\sale_details;
use Illuminate\Support\Facades\DB;
use App\Models\settings\businessLocation;

class OverAllReportTable extends Component
{
    use WithPagination, datatable;

    public $businesslocationFilterId = 'all';
    public $customerFilterId = 'all';
    public $statusFilter = 'all';
    public $filterDate;
    public $saleType;
    public function render()
    {
        $search = $this->search;
        $businesslocationFilterId = $this->businesslocationFilterId;
        $customerFilterId = $this->customerFilterId;
        $statusFilter = $this->statusFilter;
        $filterDate = $this->filterDate;


        $locations = businessLocation::select('name', 'id', 'parent_location_id')->get();
        $employee = BusinessUser::select('username', 'id')->get();
        $categories = Category::select('name', 'id')->get();
        $datas = sale_details::select(
            'sale_details.variation_id',
            'fscampaign.name as campaign_name',
            'fscampaign.id as campaign_id',
            'fscampaign.business_location_id as campaign_business_location_id',
            'sales.business_location_id as sales_business_location_id',
            'products.name',
            'product_variations.variation_sku',
            'sales.created_by',
            'product_packaging_transactions.product_packaging_id',
            'sales.created_at',
            'categories.name as category_name',
            'uom.short_name as uom'
        )
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
            ->when(isset($filterDate), function ($query) use ($filterDate) {
                $query->whereDate('sales.sold_at', '>=', $filterDate[0])
                    ->whereDate('sales.sold_at', '<=', $filterDate[1]);
            })
            // ->groupBy(
            //     'sale_details.variation_id',
            //     'sales.business_location_id',
            //     'categories.name',
            //     'fscampaign.name',
            //     'fscampaign.business_location_id',
            //     'fscampaign.id',
            //     'sales.created_at',
            //     'product_packaging_transactions.product_packaging_id',
            //     'sales.created_by',
            //     'products.name',
            //     'stock_histories.ref_uom_id',
            //     'product_variations.variation_sku'
            // )
            ->paginate('10');
        return view('livewire.fieldservice.over-all-report-table',compact('datas','locations','employee','categories'));
    }
}
