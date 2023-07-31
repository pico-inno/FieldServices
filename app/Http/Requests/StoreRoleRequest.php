<?php

namespace App\Http\Requests;

use App\Rules\CheckAtLeastOneCheckbox;
use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
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
            'name'=>'required|unique:roles,name',
//            'permissions' => ['required', new CheckAtLeastOneCheckbox],
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Role name has already been taken.',
//            'permissions.required' => 'Please check at least one permission must be specified.',
        ];
    }
}
