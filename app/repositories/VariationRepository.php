<?php

namespace App\repositories;

use App\Models\Product\VariationTemplates;
use App\Models\Product\VariationTemplateValues;

class VariationRepository
{
    public function prepareVariationTemplateValuesData(array $data){
        return [
            'name' => $data['value'],
        ];
    }
    public function getAllTemplate(){
        return VariationTemplates::all();
    }
    public function getAllTemplateValues(){
        return VariationTemplateValues::all();
    }
    public function getTemplateWithRelationships($relations = [])
    {
        return VariationTemplates::with($relations)->get();
    }

    public function getTemplateValuesByTemplateId($variation_id)
    {
        return VariationTemplateValues::where('variation_template_id', $variation_id)->get();
    }

    public function createTemplate(array $templateData)
    {

        return VariationTemplates::create($templateData);

    }

    public function createTemplateValues(array $templateValuesData)
    {
        return VariationTemplateValues::create($templateValuesData);
    }

    public function updateTemplate($id, array $templateData)
    {
        return VariationTemplates::where('id', $id)->update($templateData);
    }

    public function updateTemplateValues($id, array $templateValuesData)
    {
        return VariationTemplateValues::where('id', $id)->update($templateValuesData);
    }

    public function deleteTemplate($id)
    {
        VariationTemplates::where('id', $id)->update(['deleted_by' => auth()->id()]);
        return VariationTemplates::destroy($id);
    }

    public function deleteTemplateValues($ids)
    {
        VariationTemplateValues::whereIn('id', $ids)->update(['deleted_by' => auth()->id()]);
        return VariationTemplateValues::destroy($ids);
    }

    public function getTemplateValuesByIdWithRelationships($variation_id, $relations = [])
    {

        return VariationTemplateValues::with($relations)->where('variation_template_id', $variation_id)->get();

    }

}
