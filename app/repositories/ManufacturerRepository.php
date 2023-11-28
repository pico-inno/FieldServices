<?php

namespace App\repositories;

use App\Models\Product\Manufacturer;

class ManufacturerRepository
{
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
}
