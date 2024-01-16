<?php

namespace App\Actions\product\unit;

use App\Repositories\Product\UnitCategoryRepository;
use App\Repositories\Product\UOMRepository;
use Illuminate\Support\Facades\DB;

class UOMAction
{
    protected $uomRepository;
    protected $unitCategoryRepository;
    public function __construct(UOMRepository $uomRepository, UnitCategoryRepository $unitCategoryRepository)
    {
        $this->uomRepository = $uomRepository;
        $this->unitCategoryRepository = $unitCategoryRepository;
    }

    public function create($uom)
    {
        $data = $this->prepareUomData($uom);
        $data['created_by'] = auth()->id();
        $this->uomRepository->create($data);
    }

    public function update($id, $uom)
    {
        $data = $this->prepareUomData($uom);
        $data['updated_by'] = auth()->id();
        $this->uomRepository->update($id, $data);
    }


    public function delete($id){
       $this->uomRepository->delete($id);
    }

    private function prepareUomData($uom)
    {
        return [
            'name' => $uom->name,
            'short_name' => $uom->short_name,
            'unit_category_id' => $uom->unit_category,
            'unit_type' => $uom->unit_type,
            'value' => $uom->value,
            'rounded_amount' => $uom->rounded_amount,
        ];
    }

}
