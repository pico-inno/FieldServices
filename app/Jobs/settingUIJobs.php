<?php

namespace App\Jobs;

class settingUIJobs
{
    public static function tabDatas($settingData){
        return [
            'businessTab'=>[
                [
                    'label' => __('business_settings.business_name'),
                    'type' => 'input',
                    'iType' => 'text',
                    'id' => 'business_name',
                    'class' => '',
                    'value'=> $settingData['name'],
                ],
                [
                    'label' => __('business_settings.business_name'),
                    'type' => 'input',
                    'iType' => 'text',
                    'id' => 'business_name',
                    'class' => '',
                    'value' => $settingData->owner ? $settingData->owner->username : '',
                ],
                [
                    'label' => __('business_settings.business_name'),
                    'type' => 'input',
                    'iType' => 'text',
                    'id' => 'business_name',
                    'class' => '',
                    'value' => $settingData->owner ? $settingData->owner->username : '',
                ],
                [
                    'label' => __('business_settings.business_name'),
                    'type' => 'input',
                    'iType' => 'text',
                    'id' => 'business_name',
                    'class' => '',
                    'value' => $settingData->owner ? $settingData->owner->username : '',
                ],


            ]
        ];
    }
}
