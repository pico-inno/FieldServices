<?php

namespace App\repositories;

use App\Models\Product\UOM;

class UOMRepository
{
    public function getAll()
    {
        return UOM::all();
    }

    public function getById($id)
    {
        return UOM::find($id);
    }

    public function create(array $data)
    {
        return UOM::create($data);
    }

    public function update($id, array $data)
    {
        return UOM::where('id', $id)->update($data);
    }

    public function delete($id)
    {
       UOM::where('id', $id)->update(['deleted_by' => auth()->id()]);
       return UOM::destroy($id);
    }

    public function getByCategoryId($category_id){
        return UOM::where('unit_category_id', $category_id)->get();
    }

    public function getUomByUomId($id){
        $unitCategoryId = UOM::whereId($id)->first()->unit_category_id;
        $uoms = UOM::whereUnitCategoryId($unitCategoryId)->get();
        return $uoms;
    }
}
