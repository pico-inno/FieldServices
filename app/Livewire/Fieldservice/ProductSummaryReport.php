<?php

namespace App\Livewire\Fieldservice;

use Livewire\Component;
use App\Models\Currencies;
use App\Models\BusinessUser;
use App\Datatables\datatable;
use App\Models\Product\Category;
use App\Models\sale\sale_details;
use Illuminate\Support\Facades\DB;

class ProductSummaryReport extends Component
{

    use datatable;
    public $campaign_id=8;

    public $currency;
    public $categories;
    public $productFilterData;
    public $search;
    public $categotryFilterId;
    public function mount(){
        $currencyId= getSettingsValue('currency_id');
        $this->categories = Category::select('name', 'id')->get();
        $this->currency=Currencies::select('symbol')->where('id', $currencyId)->first();
    }
    public function render()
    {
        $search=$this->search;
        $categotryFilterId=$this->categotryFilterId;
        $datas = sale_details::query()
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
            ->where('sale_details.is_delete', 0)
            ->where('sales.is_delete',0)
            ->where('receipe_of_material_details.id',null)
            // ->where('fscampaign.id','=',$this->campaign_id)
            ->groupBy('sale_details.variation_id','products.name', 'categories.name', 'uom.short_name')
            ->paginate(15);
        return view('livewire.fieldservice.product-summary-report',compact('datas'));
    }
}
