<?php

namespace App\Actions\product;

use App\repositories\VariationRepository;
use App\Services\product\VariationService;
use Illuminate\Support\Facades\DB;

class VariationAction
{
    protected $variationRepository;
    public function __construct(VariationRepository $variationRepository){
        $this->variationRepository = $variationRepository;
    }

    public function create($templateData, $templateValuesData){
        return DB::transaction(function () use ($templateData, $templateValuesData){
            $preparedTemplateData = $this->prepareVariationTemplateData($templateData);
            $preparedTemplateData['created_by'] = auth()->id();
            $template = $this->variationRepository->createTemplate($preparedTemplateData);

            foreach ($templateValuesData as $templateValue){
                $preparedTemplateValuesData = $this->prepareVariationTemplateValuesData($templateValue);
                $preparedTemplateValuesData['variation_template_id'] = $template->id;
                $preparedTemplateValuesData['created_by'] = auth()->id();
                $this->variationRepository->createTemplateValues($preparedTemplateValuesData);
            }
        });
    }

    public function update($variation_id, $templateData, $templateValuesData){
        return DB::transaction(function () use ($variation_id, $templateData, $templateValuesData){
            $preparedTemplateData = $this->prepareVariationTemplateData($templateData);
            $preparedTemplateData['updated_by'] = auth()->id();

            $this->variationRepository->updateTemplate($variation_id, $preparedTemplateData);

            foreach ($templateValuesData as $templateValue){
                $preparedTemplateValuesData = $this->prepareVariationTemplateValuesData($templateValue);

                if ($templateValue['id'] === null) {
                    $preparedTemplateValuesData['variation_template_id'] = $variation_id;
                    $preparedTemplateValuesData['created_by'] = auth()->id();
                    $this->variationRepository->createTemplateValues($preparedTemplateValuesData);
                }else{
                    $preparedTemplateValuesData['updated_by'] = auth()->id();
                    $this->variationRepository->updateTemplateValues($templateValue['id'], $preparedTemplateValuesData);
                }

            }
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $this->variationRepository->deleteTemplate($id);

            $ids = $this->variationRepository->getTemplateValuesByTemplateId($id)->pluck('id');

            $this->variationRepository->deleteTemplateValues($ids);
        });
    }
    private function prepareVariationTemplateData($templateData){
        return [
            'name' => $templateData->variation_name
        ];
    }

    private function prepareVariationTemplateValuesData(array $data){
        return [
            'name' => $data['value'],
        ];
    }
}
