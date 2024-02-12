<?php

namespace App\Livewire\Report;

use Livewire\Component;
use Livewire\WithPagination;
use App\Datatables\datatable;
use App\Models\Product\Product;
use App\Models\Product\ProductVariation;
use Illuminate\Support\Facades\DB;

class ItemReportTable extends Component
{
    use WithPagination, datatable;
    public $filterDate;
    public function __construct()
    {
        $this->queryString= [...$this->queryString,
            'filterDate', 'customerFilterId'
        ];

    }
    public function render()
    {

        $filterDate=$this->filterDate;
        $keyword=$this->search;
        $datas=ProductVariation::select(
            // 'products.id as id',
            'sales.sales_voucher_no',
            'transfers.transfer_voucher_no',
            DB::raw("CONCAT(products.name, IFNULL(CONCAT(' (', variation_template_values.name, ') '), '')) AS name"),
            'products.sku as sku',

            // 'sale_details.quantity as sell_qty',
            'lot_serial_details.ref_uom_quantity  as sell_qty',
            'sale_details.uom_price as selling_price',
            // 'sale_details.subtotal_with_tax as sale_subtotal',

            'supplier.company_name as supplier',
            DB::raw("CONCAT(customer.first_name,' ',IFNULL(customer.middle_name,''),' ',IFNULL(customer.last_name,''),'') AS customer_name"),
            'openingPerson.username as openingPerson',
            'purchase_details.uom_price as purchase_price',
            'current_stock_balance.transaction_type as csbT',
            'purchase_voucher_no',
            'purchases.created_at as purchase_date',
            'purchases.created_at as osDate',
            'business_locations.name as location',
            'opening_stock_voucher_no',
            'stockin_voucher_no',
            'opening_date',
            'transfered_at',
            'stockin_date',
            'received_person',
            'transferLocaiton.name as transferLocaitonName',
            'stockLocation.name as stockLocationName',
            'stockinPerson.username as stockinPersonName',
            'sales.sold_at',

            DB::raw('lot_serial_details.uom_quantity * sale_details.uom_price as sale_subtotal'),
            DB::raw('lot_serial_details.ref_uom_quantity *  current_stock_balance.ref_uom_price as total_cogs')
        )
            ->leftJoin('products','product_variations.product_id' , '=','products.id' )
            ->leftJoin('variation_template_values', 'product_variations.variation_template_value_id', '=', 'variation_template_values.id')
            ->leftJoin('sale_details', 'product_variations.id', '=', 'sale_details.variation_id')
            ->leftJoin('sales', 'sale_details.sales_id', '=', 'sales.id')
            ->leftJoin('contacts as customer', 'sales.contact_id', '=', 'customer.id')
            ->leftJoin('business_locations', 'sales.business_location_id', '=', 'business_locations.id')
            ->leftJoin('lot_serial_details', function ($join) {
                $join->on('lot_serial_details.transaction_type', '=', DB::raw("'sale'"))
                    ->where('lot_serial_details.transaction_detail_id', '=', DB::raw('sale_details.id'));
            })
            ->leftJoin('current_stock_balance', 'lot_serial_details.current_stock_balance_id', '=', 'current_stock_balance.id')

            ->join('purchase_details', 'current_stock_balance.transaction_detail_id', '=', 'purchase_details.id')
            ->join('purchases', 'purchase_details.purchases_id', '=', 'purchases.id')

            ->leftJoin('opening_stock_details', 'current_stock_balance.transaction_detail_id', '=', 'opening_stock_details.id')
            ->leftJoin('opening_stocks', 'opening_stock_details.opening_stock_id', '=', 'opening_stocks.id')


            ->leftJoin('transfer_details', 'current_stock_balance.transaction_detail_id', '=', 'transfer_details.id')
            ->leftJoin('transfers', 'transfer_details.transfer_id', '=', 'transfers.id')

            ->leftJoin('stockin_details', 'current_stock_balance.transaction_detail_id', '=', 'stockin_details.id')
            ->leftJoin('stockins', 'stockin_details.stockin_id', '=', 'stockins.id')

            ->leftJoin('business_locations as stockLocation', 'stockins.business_location_id', '=', 'stockLocation.id')


            ->leftJoin('contacts as supplier', 'purchases.contact_id', '=', 'supplier.id')
            ->leftJoin('business_users as openingPerson', 'opening_stocks.opening_person', '=', 'openingPerson.id')
            ->leftJoin('business_users as stockinPerson', 'stockins.stockin_person', '=', 'stockinPerson.id')
            ->leftJoin('business_locations as transferLocaiton', 'transfers.to_location', '=', 'transferLocaiton.id')
            ->when(rtrim($keyword),function($q) use($keyword){
                $q->where('products.name', 'like',"%".$keyword."%")
                ->orwhere('sales.sales_voucher_no', 'like',"%".$keyword."%");
            })
            ->when(isset($filterDate), function ($query) use ($filterDate) {
                $query->whereDate('sales.sold_at', '>=', $filterDate[0])
                        ->whereDate('sales.sold_at', '<=', $filterDate[1]);
            })
            ->where('sales.is_delete', 0)
            ->where('sale_details.is_delete', 0)
            ->paginate($this->perPage);
            // ->get()->toArray();
            // dd($datas);
        return view('livewire.item-report-table',[
            'datas'=>$datas
                // ->paginate($this->perPage)
        ]);
    }
}
