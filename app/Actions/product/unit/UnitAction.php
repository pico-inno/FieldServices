<?php

namespace App\Actions\product\unit;

use App\Repositories\Product\UnitCategoryRepository;
use App\Repositories\Product\UOMRepository;
use Illuminate\Support\Facades\DB;

class UnitAction
{
    protected $uomRepository;
    protected $unitCategoryRepository;
    public function __construct(UOMRepository $uomRepository, UnitCategoryRepository $unitCategoryRepository)
    {
        $this->uomRepository = $uomRepository;
        $this->unitCategoryRepository = $unitCategoryRepository;
    }

    public function create($unit)
    {
        return DB::transaction(function () use ($unit) {
            $data = $this->prepareUnitCategoryData($unit);
            $data['created_by'] = auth()->id();
            $this->unitCategoryRepository->create($data);
        });
    }

    public function update($id, $unit)
    {
        return DB::transaction(function () use ($id, $unit) {
            $data = $this->prepareUnitCategoryData($unit);
            $data['updated_by'] = auth()->id();
            $this->unitCategoryRepository->update($id, $data);
        });
    }

    public function delete($unitCategoryId)
    {
        return DB::transaction(function () use ($unitCategoryId) {

            $uomIds = $this->uomRepository->getByCategoryId($unitCategoryId)->pluck('id');

            if (!$uomIds->isEmpty()) {
                foreach ($uomIds as $uom_id){
                    $this->uomRepository->delete($uom_id);
                }
            }
            $this->unitCategoryRepository->delete($unitCategoryId);
        });
    }

    private function prepareUnitCategoryData($unit)
    {
        return [
            'name' => $unit->name,
        ];
    }


}
