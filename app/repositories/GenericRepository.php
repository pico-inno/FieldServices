<?php

namespace App\repositories;

use App\Models\Product\Generic;

class GenericRepository
{
    public function getAll()
    {
        return Generic::all();
    }

    public function getById($id)
    {
        return Generic::find($id);
    }

    public function create(array $data)
    {
        return Generic::create($data);
    }

    public function update($id, array $data)
    {
        return Generic::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        Generic::where('id', $id)->update(['deleted_by' => auth()->id()]);
        return Generic::destroy($id);
    }
}
