<?php

namespace App\Http\Requests\Product\PriceList;

use Illuminate\Foundation\Http\FormRequest;

class PriceListUpdateRequest extends FormRequest
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
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'base_price.required' => 'Base Price is required',
            'currency_id.required' => 'Currency is required',
        ];
    }
}
