<?php

namespace App\Livewire\PriceList;

use Livewire\Component;
use App\Models\Product\PriceListDetails;

class PriceListEditTable extends Component
{
    public $id;
    public $editingId=null;

    public function render()
    {
        $priceListDetails = PriceListDetails::where('pricelist_id',$this->id)->paginate('5');
        // dd($priceListDetails);
        return view('livewire.price-list.price-list-edit-table',compact('priceListDetails'));
    }
    public function editMode($id){
        $this->editingId=$id;
    }
    public function minQtyChange($value,$id){
        PriceListDetails::where('id',$id)->update([
            'min_qty'=>$value
        ]);
        $this->editingId=null;
    }
}
