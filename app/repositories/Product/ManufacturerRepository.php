<?php

namespace App\Repositories\Product;

use App\Models\Product\Manufacturer;
use Illuminate\Support\Facades\Auth;

class ManufacturerRepository
{
    public function query()
    {
        return Manufacturer::query();
    }
    public function getAll()
    {
        return Manufacturer::all();
    }

    public function getById($id)
    {
        return Manufacturer::find($id);
    }

    public function create(array $data)
    {
        return Manufacturer::create($data);
    }

    public function update($id, array $data)
    {
        return Manufacturer::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        Manufacturer::where('id', $id)->update(['deleted_by' => auth()->id()]);
        return Manufacturer::destroy($id);
    }

    public function getOrCreateManufacturerId($manufacturerName)
    {
        if ($manufacturerName){
            $manufacturer =  Manufacturer::where('name', $manufacturerName)->first();
            if (!$manufacturer){
                $manufacturer = $this->create(['name' => $manufacturerName, 'created_by' => Auth::id()]);
            }
            return $manufacturer->id;
        }
    }
}
