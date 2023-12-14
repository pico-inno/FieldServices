<?php

namespace App\Http\Requests\Stock;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockAdjustmentRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'business_location' => 'required',
            'condition' => 'required',
            'status' => 'required',
            'adjustment_details' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'business_location' => 'Business location is required',
            'condition' => 'Adjustment condition is required',
            'status' => 'Business status is required',
            'adjustment_details' => 'At least one item is required to complete adjustment!',
        ];
    }
}
