<?php

namespace App\Actions\product;

use App\repositories\GenericRepository;
use Illuminate\Support\Facades\DB;

class GenericAction
{
    protected $genericRepository;

    public function __construct(GenericRepository $genericRepository)
    {
        $this->genericRepository = $genericRepository;
    }

    public function create($data)
    {
        return DB::transaction(function () use ($data) {
            $preparedData = $this->prepareGenericData($data);
            $preparedData['created_by'] = auth()->id();

            $this->genericRepository->create($preparedData);
        });
    }

    public function update($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $preparedData = $this->prepareGenericData($data);
            $preparedData['updated_by'] = auth()->id();

            $this->genericRepository->update($id, $preparedData);

        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $this->genericRepository->delete($id);
        });
    }

    private function prepareGenericData($data)
    {
        return [
            'name' => $data->generic_name,
        ];
    }
}
