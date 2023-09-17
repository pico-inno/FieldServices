<?php

namespace App\Imports\priceList;

use App\Models\Product\PriceListDetails;
use App\Models\Product\PriceLists;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class priceListImport implements ToModel,WithHeadingRow
{
    protected $priceList;
    public function __construct($priceList)
    {
        $this->priceList= $priceList;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        try {
            if (
                $row['apply_type'] &&
                $row['apply_value'] &&
                $row['min_quantity'] &&
                $row['cal_type_fix_or_percentage'] &&
                $row['cal_value']
            ) {
                $pricelistData = [
                    'pricelist_id' => $this->priceList['id'],
                    'applied_type' => $row['apply_type'],
                    'applied_value' => $row['apply_value']== 'all product' ? 0 : '',
                    'min_qty' => $row['min_quantity'],
                    'cal_type' => $row['cal_type_fix_or_percentage'],
                    'cal_value' => $row['cal_value'],
                    'base_price' => $this->priceList['base_price'],
                ];
                PriceListDetails::create($pricelistData);
            }
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }

    }
}
