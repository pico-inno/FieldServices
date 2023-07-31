<?php

namespace App\Http\Requests\Product\ImportProduct;

use Illuminate\Foundation\Http\FormRequest;

class ImportProductCreateRequest extends FormRequest
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
            'import-products' => 'required|mimes:xlx,xls|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'import-products.required' => 'Import product excel file is required',
            'import-products.mimes' => 'Invalid file type. Only xlx and xls files are allowed.',
            'import-products.max' => 'The file size should not exceed 2048 kilobytes.',
        ];
    }
}
