<?php

namespace App\Http\Requests\sale;

use Illuminate\Foundation\Http\FormRequest;

class saleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'sale_details' => 'required',
            'business_location_id' => 'required',
            'contact_id' => 'required',
            'status' => 'required'
        ];
    }

    public function messages()
    {
        return  [
            'sale_details.required' => 'Sale Items are required!',
            'business_location_id.required' => 'Bussiness Location is required!',
            'contact_id.required' => 'Contact is required!',
        ];
    }
}
