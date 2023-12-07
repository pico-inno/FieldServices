<?php

namespace App\Actions\product;

use App\Repositories\Product\BrandRepository;
use Illuminate\Support\Facades\DB;

class BrandAction
{
    protected $brandRepository;
    public function __construct(BrandRepository $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    public function create($data){
        return DB::transaction(function () use ($data){
            $preparedData = $this->prepareBrandData($data);
            $preparedData['created_by'] = auth()->id();

            $this->brandRepository->create($preparedData);
        });
    }

    public function update($id, $data){
        return DB::transaction(function () use ($id, $data){
            $preparedData = $this->prepareBrandData($data);
            $preparedData['updated_by'] = auth()->id();

            $this->brandRepository->update($id, $preparedData);

        });
    }

    public function delete($id){
        return DB::transaction(function () use ($id){
            $this->brandRepository->delete($id);
        });
    }

    private function prepareBrandData($data){
        return [
            'name'   => $data->brand_name,
            'description' => $data->brand_desc,
        ];
    }
}
