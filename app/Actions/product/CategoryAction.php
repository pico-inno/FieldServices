<?php

namespace App\Actions\product;

use App\repositories\CategoryRepository;
use Illuminate\Support\Facades\DB;

class CategoryAction
{
    protected $categoryRepository;
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function create($data){
        return DB::transaction(function () use ($data){
            $preparedData = $this->prepareCategoryData($data);

            if($data->parent_id !== "Select"){
                $preparedData['parent_id'] = $data-> parent_id;
            }
            $preparedData['created_by'] = auth()->id();

            $this->categoryRepository->create($preparedData);
        });
    }

    public function update($id, $data){
        return DB::transaction(function () use ($id, $data){
            $preparedData = $this->prepareCategoryData($data);

            if($data->parent_id !== "Select"){
                $preparedData['parent_id'] = $data->parent_id;
            }

            $preparedData['updated_by'] = auth()->id();
            $this->categoryRepository->update($id, $preparedData);

        });
    }

    public function delete($id){
        return DB::transaction(function () use ($id){
           $this->categoryRepository->delete($id);
        });
    }

    private function prepareCategoryData($data){
        return [
            'name'   => $data->category_name,
            'short_code' => $data->category_code,
            'description' => $data->category_desc,
        ];
    }
}
