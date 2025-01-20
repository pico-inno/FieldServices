<?php

namespace App\Repositories\Product;

use App\Models\Product\PriceListDetails;
use App\Models\Product\PriceLists;

class PriceRepository
{
    public function query()
    {
        return PriceLists::query();
    }
    public function queryPriceListDetails()
    {
        return PriceListDetails::query();
    }
    public function getPriceListDetailsByConditions(array $conditions)
    {
        return PriceListDetails::where($conditions);
    }

    public function getAll()
    {
        return;
    }

    public function getById($id)
    {
        return;
    }
    public function createPriceList(array $data)
    {
        return PriceLists::create($data);
    }

    public function createPriceListDetails(array $data)
    {
        return PriceListDetails::create($data);
    }

    public function update($id, array $data)
    {
        return PriceListDetails::where('id', $id)->update($data);
    }

    public function deletePriceListDetails($id)
    {
       return PriceListDetails::destroy($id);
    }
}
