<?php

namespace App\Http\Requests\Product\UoM;

use Illuminate\Foundation\Http\FormRequest;

class UoMCreateRequest extends FormRequest
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
    public function rules()
    {
        return [
            'unit_category' => 'required',
            'name' => 'required',
            'unit_type' => 'required',
            'value' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'unit_category.required' => 'Unit Category is required',
            'name.required' => 'Name is required',
            'unit_type.required' => 'Unit type is required',
            'value.required' => 'Value is required'
        ];
    }
}
