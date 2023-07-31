<?php

namespace App\Http\Requests\Product\Variation;

use Illuminate\Foundation\Http\FormRequest;

class VariationUpdateRequest extends FormRequest
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
            'variation_name' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'variation_name.required' => 'Variation name is required',
        ];
    }
}
