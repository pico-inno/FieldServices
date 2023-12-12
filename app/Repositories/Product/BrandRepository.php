<?php

namespace App\Repositories\Product;

use App\Models\Product\Brand;
use Illuminate\Support\Facades\Auth;

class BrandRepository
{
    public function query()
    {
        return Brand::query();
    }
    public function getAll()
    {
        return Brand::all();
    }

    public function getById($id)
    {
        return Brand::find($id);
    }

    public function create(array $data)
    {
        return Brand::create($data);
    }

    public function update($id, array $data)
    {
        return Brand::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        Brand::where('id', $id)->update(['deleted_by' => auth()->id()]);
        return Brand::destroy($id);
    }

    public function getOrCreateBrandId($brandName)
    {
        if ($brandName){
            $brand =  Brand::where('name', $brandName)->first();
            if (!$brand){
                $brand = $this->create(['name' => $brandName, 'created_by' => Auth::id()]);
            }
            return $brand->id;
        }
    }


}
