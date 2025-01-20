<?php

namespace App\Repositories\Product;

use App\Models\Product\Generic;
use Illuminate\Support\Facades\Auth;

class GenericRepository
{
    public function query()
    {
        return Generic::query();
    }
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

    public function getOrCreateGenericId($genericName)
    {
        if ($genericName){
            $generic =  Generic::where('name', $genericName)->first();
            if (!$generic){
                $generic = $this->create(['name' => $genericName, 'created_by' => Auth::id()]);
            }
            return $generic->id;
        }
    }
}
