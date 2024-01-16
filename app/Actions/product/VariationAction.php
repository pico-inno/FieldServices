<?php

namespace App\Actions\product;

use App\Repositories\Product\VariationRepository;
use Illuminate\Support\Facades\DB;

class VariationAction
{
    protected $variationRepository;
    public function __construct(VariationRepository $variationRepository){
        $this->variationRepository = $variationRepository;
    }

    public function create($templateData, $templateValuesData){

            $preparedTemplateData = $this->prepareVariationTemplateData($templateData);
            $preparedTemplateData['created_by'] = auth()->id();
            $template = $this->variationRepository->createTemplate($preparedTemplateData);

            foreach ($templateValuesData as $templateValue){
                $preparedTemplateValuesData = $this->prepareVariationTemplateValuesData($templateValue);
                $preparedTemplateValuesData['variation_template_id'] = $template->id;
                $preparedTemplateValuesData['created_by'] = auth()->id();
                $this->variationRepository->createTemplateValues($preparedTemplateValuesData);
            }

            return $template;

    }

    public function update($variation_id, $templateData, $templateValuesData){

        $preparedTemplateData = $this->prepareVariationTemplateData($templateData);
        $preparedTemplateData['updated_by'] = auth()->id();

        $variation_template = $this->variationRepository->updateTemplate($variation_id, $preparedTemplateData);

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

        return $variation_template;
    }

    public function delete($id)
    {

            $this->variationRepository->deleteTemplate($id);

            $ids = $this->variationRepository->getTemplateValuesByTemplateId($id)->pluck('id');

            $this->variationRepository->deleteTemplateValues($ids);

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
