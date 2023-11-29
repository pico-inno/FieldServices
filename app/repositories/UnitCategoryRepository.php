<?php

namespace App\repositories;

use App\Models\Product\UnitCategory;

class UnitCategoryRepository
{
    public function getAll()
    {
        return UnitCategory::all();
    }

    public function getById($id)
    {
        return UnitCategory::find($id);
    }

    public function create(array $data)
    {
        return UnitCategory::create($data);
    }

    public function update($id, array $data)
    {
        return UnitCategory::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        UnitCategory::where('id', $id)->update(['deleted_by' => auth()->id()]);
        return UnitCategory::destroy($id);
    }
}
