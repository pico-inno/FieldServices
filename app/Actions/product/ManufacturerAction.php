<?php

namespace App\Actions\product;

use App\Repositories\Product\ManufacturerRepository;
use Illuminate\Support\Facades\DB;

class ManufacturerAction
{

    protected $manufacturerRepository;

    public function __construct(ManufacturerRepository $manufacturerRepository)
    {
        $this->manufacturerRepository = $manufacturerRepository;
    }

    public function create($data)
    {
        return DB::transaction(function () use ($data) {
            $preparedData = $this->prepareManufacturerData($data);
            $preparedData['created_by'] = auth()->id();

            $this->manufacturerRepository->create($preparedData);
        });
    }

    public function update($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $preparedData = $this->prepareManufacturerData($data);
            $preparedData['updated_by'] = auth()->id();

            $this->manufacturerRepository->update($id, $preparedData);

        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $this->manufacturerRepository->delete($id);
        });
    }

    private function prepareManufacturerData($data)
    {
        return [
            'name' => $data->manufacturer_name,
        ];
    }
}
