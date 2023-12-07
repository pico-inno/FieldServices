<?php

namespace App\Actions\product;

use App\Repositories\Product\CategoryRepository;
use Illuminate\Support\Facades\DB;
use Modules\Service\Actions\ServiceCategoryAction;

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

            $createdCategory = $this->categoryRepository->create($preparedData);

            if (hasModule('Service') && isEnableModule('Service') && $data->service_category)
            {
                $serviceCategoryAction = new ServiceCategoryAction();
                $serviceCategoryAction->createOrDelete($createdCategory->id);
            }

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

            if (hasModule('Service') && isEnableModule('Service'))
            {
                $serviceCategoryAction = new ServiceCategoryAction();
                if ($data->service_category){
                    $serviceCategoryAction->createOrDelete($id);
                }else{
                    $serviceCategoryAction->delete($id);
                }

            }

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
