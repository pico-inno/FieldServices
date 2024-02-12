<?php

namespace App\Livewire\Stock;

use Livewire\Component;
use Livewire\WithPagination;
use App\Datatables\datatable;
use App\Models\Product\ProductVariation;
use App\Models\stock_history;
use Illuminate\Support\Facades\DB;

class StockHistoryTable extends Component
{
    use WithPagination,datatable;
    public $products,$variationId;
    public function __construct()
    {
        $this->queryString= [...$this->queryString,
            'variationId',
        ];
    }
    public function mount(){
        $this->variationId=ProductVariation::select('id')->first()->id;
        $this->products=ProductVariation::query()
                        ->select('product_variations.id','products.image','product_variations.variation_sku as sku', DB::raw("CONCAT(products.name, IFNULL(CONCAT(' (', variation_template_values.name, ') '), '')) AS name"))
                        ->leftJoin('products', 'product_variations.product_id', '=', 'products.id')
                        ->leftJoin('variation_template_values', 'product_variations.variation_template_value_id', '=', 'variation_template_values.id')
                        ->get()->toArray();

    }
    public function render()
    {
        $variationId=$this->variationId;
        $isAsc=$this->sortAsc;

        $datas = stock_history::query()
        ->select('stock_histories.*','products.name as jjk',
        DB::raw("CONCAT(products.name, IFNULL(CONCAT(' (', variation_template_values.name, ') '), '')) AS name")
        )
        ->leftJoin('product_variations', 'stock_histories.variation_id', '=', 'product_variations.id')
        ->leftJoin('variation_template_values', 'product_variations.variation_template_value_id', '=', 'variation_template_values.id')
        ->leftJoin('products', 'product_variations.product_id', '=', 'products.id')
        ->where('variation_id',$variationId)
        ->orderBy('stock_histories.id',$isAsc ? 'ASC' : 'DESC')

        ->when(!hasModule('StockInOut') ,function($q){
            $q->whereNotIn('stock_histories.transaction_type', ['stock_in', 'stock_out']);
        })->paginate($this->perPage);
        $totalBalanceQty=0;
        $balanceQtyBeforePage=0;
        // dd($IncreaseQtybeforePage-$DecreaseQtybeforePage,$totalBalanceQty);
        if (count($datas )>0) {

            $totalBalanceQty=stock_history::where('variation_id',$variationId)->select(DB::raw('SUM(increase_qty) - SUM(decrease_qty) as totalBalanceQty'))->groupBy('product_id')->first()['totalBalanceQty'];
            if($isAsc){
                $IncreaseQtybeforePage = stock_history::where('id', '<', $datas[0]->id)->where('variation_id',$variationId)->sum('increase_qty');
                $DecreaseQtybeforePage = stock_history::where('id', '<', $datas[0]->id)->where('variation_id',$variationId)->sum('decrease_qty');
                $balanceQtyBeforePage=$IncreaseQtybeforePage-$DecreaseQtybeforePage;
            }else{
                $IncreaseQtybeforePage = stock_history::where('id', '>', $datas[0]->id)->where('variation_id',$variationId)->sum('increase_qty');
                $DecreaseQtybeforePage = stock_history::where('id', '>', $datas[0]->id)->where('variation_id',$variationId)->sum('decrease_qty');
                $beforeBalance=$DecreaseQtybeforePage-$IncreaseQtybeforePage;
                $balanceQtyBeforePage=$beforeBalance;
                $balanceQtyBeforePage+=$totalBalanceQty;
            }
        }

        return view('livewire.stock.stock-history-table',[
            'datas'=>$datas,
            'balanceQtyBeforePage'=>$balanceQtyBeforePage
        ]);
    }
}
