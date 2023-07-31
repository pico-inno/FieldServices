<?php

namespace App\Http\Requests\Product\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
            'product_name' => 'required',
            'uom_id' => 'required',
            'purchase_uom_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'product_name.required' => 'Product name is required',
            'uom_id.required' => 'Select Sale UoM',
            'purchase_uom_id.required' => 'Select Purchase UoM'
        ];
    }
}
