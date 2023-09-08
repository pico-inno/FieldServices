<?php

namespace Modules\StockInOut\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStockInRequest extends FormRequest
{
    public function rules()
    {
        return [
            //
        ];
    }


    public function authorize()
    {
        return true;
    }
}
