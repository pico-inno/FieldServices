<?php

namespace App\Livewire\FieldService;

use Livewire\Component;
use App\Models\Currencies;
use App\Models\BusinessUser;
use Livewire\WithPagination;
use App\Datatables\datatable;
use App\Models\Product\Category;
use App\Models\sale\sale_details;
use App\Exports\productItemReport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\settings\businessLocation;
use App\Exports\campaignProductOverAllReport;
use Modules\FieldService\Entities\FsCampaign;

class CampaignProductSummeryReport extends Component
{
    use WithPagination,datatable;
    public $currency;
    public $defaultCampaignId=null;
    public $campaignFilterId='all';
    public $newsearch;
    public $categotryFilterId='all';
    public $outletFilterId='all';
    public $outletTypeFilter='all';
    public $pgFilterId='all';
    public $filterDate;
    public  $campaigns;
    public $outlets;
    public $employee;
    public function updated(){
        $this->resetPage();
    }
    public function export($withFilter=true){
        return Excel::download(new productItemReport([
            'categotryFilterId' => $this->categotryFilterId,
            'filterDate' => $this->filterDate,
            'campaignFilterId' => $this->campaignFilterId,
            'defaultCampaignId'=> $this->defaultCampaignId,
            'outletFilterId'=>$this->outletFilterId,
            'outletTypeFilter'=>$this->outletTypeFilter,
            'pgFilterId'=>$this->pgFilterId,
        ], $withFilter), 'ItemReport.xlsx');
    }
    public function mount(){
        $this->outlets = businessLocation::select('id', 'name')->get()->toArray();

        $this->employee = BusinessUser::select('username', 'id', 'personal_info_id')
            ->with('personal_info:first_name,last_name,id')->get();
    }
    public function render()
    {
        $search=$this->search;
        $categotryFilterId=$this->categotryFilterId;
        $filterDate=$this->filterDate;
        $defaultCampaignId=$this->defaultCampaignId;
        $campaignFilterId=$this->campaignFilterId;
        $outletFilterId=$this->outletFilterId;
        $pgFilterId=$this->pgFilterId;
        $outletTypeFilter=$this->outletTypeFilter;
        $currencyId= getSettingsValue('currency_id');
        $categories = Category::select('name', 'id')->get();
        $this->campaigns = FsCampaign::select('id', 'name')->get();
        $currency=Currencies::select('symbol')->where('id', $currencyId)->first();
        $datas = sale_details::query()
            ->select(
            'products.name',
            'uom.short_name as uom',
            'categories.name as category_name',
            'fscampaign.name as campaign',
            'outlet.name as outletName',
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

            ->when($defaultCampaignId !== null, function ($query) use ($defaultCampaignId) {
                $query->where('fscampaign.id','=',$defaultCampaignId);
            })
            ->when(rtrim($search), function ($query) use ($search) {
                $query->where("products.name", 'like', '%' . $search . '%');
            })
            ->when(isset($filterDate), function ($query) use ($filterDate) {
                $query->whereDate('sales.sold_at', '>=', $filterDate[0])
                    ->whereDate('sales.sold_at', '<=', $filterDate[1]);
            })
            ->when($campaignFilterId != 'all', function ($query) use ($campaignFilterId) {
                $query->where('fscampaign.id','=',$campaignFilterId);
            })

            ->when($categotryFilterId != 'all', function ($query) use ($categotryFilterId) {
                $query->where('categories.id','=',$categotryFilterId);
            })

            ->when($outletFilterId != 'all', function ($query) use ($outletFilterId) {
                $lids=childLocationIDs($outletFilterId);
                $query->whereIn('fscampaign.business_location_id',$lids);
            })
            ->when($outletTypeFilter != 'all', function ($query) use ($outletTypeFilter) {
                $query->where('outlet.outlet_type',$outletTypeFilter);
            })

            ->when($pgFilterId != 'all', function ($query) use ($pgFilterId) {
                $query->where('sales.sold_by',$pgFilterId);
            })


            ->groupBy('sale_details.variation_id','products.name', 'categories.name', 'uom.short_name','fscampaign.name','outlet.name')
            ->paginate(15);
        return view('livewire.campaignProductSummeryReport',compact('datas','categories'));
    }
}
