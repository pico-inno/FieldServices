<?php

namespace App\Http\Requests\Product\Manufacturer;

use Illuminate\Foundation\Http\FormRequest;

class ManufacturerUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'manufacturer_name' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'manufacturer_name.required' => 'Manufacturer name is required',
        ];
    }
}
