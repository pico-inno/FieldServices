<?php

namespace Modules\ComboKit\Repositories;

use Modules\ComboKit\Entities\ReceipeOfMaterial;
use Modules\ComboKit\Entities\ReceipeOfMaterialDetail;
use Modules\ComboKit\Services\RoMService;
use Modules\Service\Repositories\ServiceCategoryRepository;

class RoMRepository
{

    public function query()
    {
        return ReceipeOfMaterial::query();
    }
    public function detailsQuery()
    {
        return ReceipeOfMaterialDetail::query();
    }
    public function getAll()
    {
        return ReceipeOfMaterial::all();
    }

    public function getByServiceWithRelationships($relations = [], $conditions = [])
    {
        $query = ReceipeOfMaterial::with($relations)->where('rom_type', 'service')->get();

        foreach ($query as $q){
            $q->status = $q->product->receipe_of_material_id === $q->id ? 'Default' : '';
        }
        return $query;
    }

    public function getByManufacturingWithRelationships($relations = [], $conditions = [])
    {
        $query = ReceipeOfMaterial::with($relations)->where('rom_type', 'manufacture')->get();

        return $query;
    }



    public function getById($id)
    {
        return ReceipeOfMaterial::find($id);
    }

    public function create(array $data)
    {
        return ReceipeOfMaterial::create($data);
    }

    public function createDetails(array $data)
    {
        return ReceipeOfMaterialDetail::create($data);
    }

    public function update($id, array $data)
    {
        return ReceipeOfMaterial::where('id', $id)->update($data);
    }

    public function updateDetails($id, array $data)
    {
        return ReceipeOfMaterialDetail::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        return ReceipeOfMaterial::destroy($id);
    }

    public function deleteDetails($id)
    {
        return ReceipeOfMaterialDetail::where('receipe_of_material_id', $id)->delete();
    }
}
