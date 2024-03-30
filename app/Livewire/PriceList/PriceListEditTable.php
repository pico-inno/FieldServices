<?php

namespace App\Livewire\PriceList;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Datatables\datatable;
use Illuminate\Support\Facades\DB;
use App\Models\Product\PriceListDetails;

class PriceListEditTable extends Component
{
    use datatable,WithPagination;
    public $id;

    public function render()
    {
        $id=$this->id;
        $search=$this->search;
        $priceListDetails = PriceListDetails::query()
                            // ->select('price_list_details.*','products.name')
                            ->where('pricelist_id',$id)
                            // ->when($search,function($q) use($search) {
                            //     $q->where('products.name','like','%'.$search.'%')
                            //     ->where('products.sku','like','%'.$search.'%');
                            // })
                            // ->leftJoin('products','price_list_details.applied_value','products.id')
                            ->orderBy('price_list_details.id','DESC')
                            ->paginate($this->perPage);
        // dd($priceListDetails);
        return view('livewire.price-list.price-list-edit-table',compact('priceListDetails','id'));
    }
    public function minQtyChange($value,$id){
        try {
            DB::beginTransaction();
            PriceListDetails::where('id',$id)->update([
                'min_qty'=>$value
            ]);
            $this->dispatch('successfully-saved');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error', message:$th->getMessage());
            //throw $th;
        }
    }
    public function calValChange($value,$id){
        try {
            DB::beginTransaction();
            PriceListDetails::where('id',$id)->update([
                'cal_value'=>$value
            ]);
            $this->dispatch('successfully-saved');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error', message:$th->getMessage());
        }
    }


    #[On('data-change')]
    public function change($field='',$value='',$id=''){
        try {
            DB::beginTransaction();
            PriceListDetails::where('id',$id)->update([
                $field=>$value
            ]);
            $this->dispatch('successfully-saved');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error', message:$th->getMessage());
        }

    }
    #[On('delete')]
    public function delete($id){
        try {
            DB::beginTransaction();
            PriceListDetails::where('id',$id)->first()->delete();
            $this->dispatch('successfully-saved');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error', message:$th->getMessage());
        }
    }
}
