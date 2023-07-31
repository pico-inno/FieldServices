<?php

namespace Modules\Service\Http\Requests\ServiceSale;

use Illuminate\Foundation\Http\FormRequest;

class ServiceSaleUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [ 
            'business_location_id' => 'required',
            'contact_id' => 'required',
            'status' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'business_location_id.required' => 'Business location required',
            'contact_id.required' => 'Contact required',
            'status.required' => 'Status required'
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
