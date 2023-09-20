<?php

namespace App\Http\Controllers\Product\UI;

use App\Models\Product\Product;
use App\Models\Product\Category;
use App\Http\Controllers\Product\PriceListDetailController;
use PDO;

class PriceListDetailsUI
{

    public static function detailsUI($pl)
    {
        if ($pl['applied_type'] == 'Category') {
            $applyValues = Category::get();
        } else if ($pl['applied_type'] == 'Product') {
            $applyValues = Product::get();
        } else if ($pl['applied_type'] == 'Variation') {
            $applyValues = PriceListDetailController::getVariationOptions();
        }

        $Avoption = '';
        if ($pl['applied_type'] == 'Variation') {
            foreach ($applyValues as $av) {
                $Avoption .= '<option value="' . $av->id . '"' . ($av->id == $pl['applied_value'] ? ' selected' : '') . '>' . $av->product_variation_name . '</option>';
            }
        } else {
            foreach ($applyValues as $av) {
                $Avoption .= '<option value="' . $av->id . '"' . ($av->id == $pl['applied_value'] ? ' selected' : '') . '>' . $av->name . '</option>';
            }
        }
        $start_date='';
        $end_date='';
        if (isset($pl['start_date'])) {
            $numericDate=$pl['start_date'];
            $timestamp = (($numericDate - 25569) * 86400); // Convert Excel date to Unix timestamp
            $start_date = date('Y/d/m', $timestamp);

            // dd($start_date);
        }

        if(isset($pl['end_date'])){
            $numericDate = $pl['end_date'];
            $timestamp = (($numericDate - 25569) * 86400); // Convert Excel date to Unix timestamp
            $end_date = date('Y/d/m', $timestamp);
        }
        // dd($start_date);
        $html = '<tr class="price_list_row">
                <td>

                    <input type="hidden" name="price_list_detail_id[]" value="'.$pl['detail_id'] .'">
                    <div class="fv-row">
                        <select name="apply_type[]" class="form-select form-select-sm rounded-0 fs-7" data-control="select2"
                            data-hide-search="true" data-placeholder="Please select">
                            <option></option>
                            <option value="All">All</option>
                            <option value="Category"' . ($pl['applied_type'] == 'Category' ? ' selected' : '') . '>Category</option>
                            <option value="Product"' . ($pl['applied_type'] == 'Product' ? ' selected' : '') . '>Product</option>
                            <option value="Variation"' . ($pl['applied_type'] == 'Variation' ? ' selected' : '') . '>Variations</option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="fv-row">
                        <select name="apply_value[]" class="form-select form-select-sm rounded-0 fs-7" data-control="select2"
                            data-hide-search="false" data-placeholder="Please select">
                            ' . $Avoption . '
                        </select>
                    </div>
                </td>
                <td>
                    <div class="fv-row">
                        <input type="text" class="form-control form-control-sm rounded-0" name="min_qty[]" value="' . $pl['min_qty'] . '">
                    </div>
                </td>
                <td>
                    <div class="fv-row">
                        <select name="cal_type[]" class="form-select form-select-sm rounded-0 fs-7" data-control="select2"
                            data-hide-search="true" data-placeholder="Please select">
                            <option></option>
                            <option value="fixed"' . (($pl['cal_type'] == 'fixed' || $pl['cal_type'] == 'fix') ? ' selected' : '') . '>Fix</option>
                            <option value="percentage"' . ($pl['cal_type'] == 'percentage'  ? ' selected' : '') . '>Percentage</option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="fv-row">
                        <input type="text" class="form-control form-control-sm rounded-0" name="cal_val[]" value="' . $pl['cal_value'] . '">
                    </div>
                </td>
                <td>
                    <input type="text" name="start_date[]" class="form-control form-control-sm rounded-0 fs-7 select_date"
                        placeholder="Select date" autocomplete="off" value="'. $start_date. '" />
                </td>
                <td>
                    <input type="text" name="end_date[]" class="form-control form-control-sm rounded-0 fs-7 select_date"
                        placeholder="Select date" autocomplete="off" value="' . $end_date . '" />
                </td>
                <td><button type="button" class="btn btn-light-danger btn-sm delete_each_row"><i
                            class="fa-solid fa-trash"></i></button></td>
            </tr>';

        return $html;
    }

}
