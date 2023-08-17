<?php

namespace App\Http\Requests\Product\PriceList;

use Illuminate\Foundation\Http\FormRequest;

class PriceListCreateRequest extends FormRequest
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
            'name' => 'required', 
            'base_price' => 'required',
            'currency_id' => 'required',
            'apply_type.*' => 'required',
            'apply_value.*' => 'required', 
            'min_qty.*' => 'required|numeric',
            'cal_val.*' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'base_price.required' => 'Base Price is required',
            'currency_id.required' => 'Currency is required',
            'apply_type.*.required' => 'Select apply type',
            'apply_value.*.required' => 'Select apply value',
            'min_qty.*.required' => 'Min quantity is required',
            'min_qty.*.numeric' => 'Minimum quantity must be number',
            'cal_val.*.required' => 'Calculate value is required',
            'cal_val.*.numeric' => 'Calculate value must be number',
        ];
    }
}
