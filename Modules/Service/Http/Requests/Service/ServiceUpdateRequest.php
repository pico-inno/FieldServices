<?php

namespace Modules\Service\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class ServiceUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'service_type_id' => 'required',
            'uom_id' => 'required',
            'price' => 'numeric|regex:/^\d+(\.\d{1,2})?$/'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Service name is required',
            'service_type_id.required' => 'Select Service type',
            'uom_id.required' => 'Select Unit'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
