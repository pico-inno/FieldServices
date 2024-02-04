<?php

namespace App\Livewire\Fieldservice;

use Livewire\Component;
use App\Models\BusinessUser;
use Livewire\WithPagination;
use App\Datatables\datatable;
use App\Exports\campaignProductOverAllReport;
use App\Models\Product\Category;
use App\Models\sale\sale_details;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\settings\businessLocation;
use Modules\FieldService\Entities\FsCampaign;

class OverAllReportTable extends Component
{
    use WithPagination, datatable;

    public $businesslocationFilterId = 'all';
    public $deraultCampaignId=null;
    public $pgFilterId = 'all';
    public $categotryFilterIdOA = 'all';
    public $campaignFilterId='all';
    public $campaignFilterIdUpdate=false;
    public $dataLoading=false;
    public $filterDate;
    public $saleType;

    public $locations;
    public $employee;
    public $categories;
    public $campaigns;
    public $query;
    public $campaignId=null;
    public function __construct()
    {

        $this->sortField = 'products.id';
    }
    public function mount(){
        $this->locations = businessLocation::select('name', 'id', 'parent_location_id')->get();
        $this->employee = BusinessUser::select('username', 'id', 'personal_info_id')
            ->with('personal_info:first_name,last_name,id')->get()->toArray();
        $this->categories = Category::select('name', 'id')->get()->toArray();
        $this->campaigns = FsCampaign::select('id', 'name')->get()->toArray();
    }
    public function updated()
    {
        $this->resetPage();
    }
    public function updatedcampaignFilterId($newVal){
        $this->campaignFilterIdUpdate=true;
    }

    public function export($withFilter=true){
        $date=now()->format('d-M-y');
        $fileName='overallreport_'.$date;
        return Excel::download(new campaignProductOverAllReport([
            'businesslocationFilterId' => $this->businesslocationFilterId,
            'pgFilterId' => $this->pgFilterId,
            'categotryFilterId' => $this->categotryFilterIdOA,
            'filterDate' => $this->filterDate,
            'campaignFilterId' => $this->campaignFilterId,
            'sordField'=> $this->sortField,
            'sortAsc'=>$this->sortAsc,
            'deraultCampaignId'=>$this->deraultCampaignId,
        ], $withFilter), $fileName.'.xlsx');
    }
    public function render()
    {

        $search = $this->search;
        $businesslocationFilterId = $this->businesslocationFilterId;
        $pgFilterId = $this->pgFilterId;
        $categotryFilterId = $this->categotryFilterIdOA;
        $filterDate = $this->filterDate;
        $campaignFilterId = $this->campaignFilterId;
        $this->dataLoading = true;
        $campaignId=$this->campaignId ?? null;
        $deraultCampaignId=$this->deraultCampaignId ?? null;
        $datas= sale_details::query()
            ->select(
                'sale_details.variation_id',
                'fscampaign.name as campaign_name',
                'fscampaign.id as campaign_id',
                'fscampaign.business_location_id as campaign_business_location_id',
                'sales.business_location_id as sales_business_location_id',
                'products.name',
                'product_variations.variation_sku',
                'sales.created_by',
                'product_packaging_transactions.product_packaging_id',
                'product_packaging_transactions.quantity as pkgQty',
                'product_packagings.packaging_name as pkg',
                'sales.sold_at',
                'sale_details.quantity as quantity',
                'outlet.name as outlet',
                'categories.name as category_name',
                'uom.short_name as uom',
                'pf.first_name as pg_fs',
                'pf.last_name as pg_ls',
                'pg.username as pg_name',
                'fscampaign.name as campaignName',
                'sales.sold_at as soldAt'
            )

            // ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
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
            ->when(rtrim($search), function ($query) use ($search) {
                $query->where("products.name", 'like', '%' . $search . '%');
            })
            ->when($campaignId != null, function ($query) use ($campaignId) {
                $query->where('fscampaign.id', '=', $campaignId);
            })
            ->when($businesslocationFilterId != 'all', function ($query) use ($businesslocationFilterId) {
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
            ->when($deraultCampaignId,function($query)use($deraultCampaignId){
                $query->where('fscampaign.id', $deraultCampaignId);
            })
            ->when(isset($filterDate), function ($query) use ($filterDate) {
                $query->whereDate('sales.sold_at', '>=', $filterDate[0])
                    ->whereDate('sales.sold_at', '<=', $filterDate[1]);
            })->paginate('15');

        $this->dataLoading = true;
        return view('livewire.fieldservice.over-all-report-table',compact('datas'));
    }
}




