<?php

namespace App\repositories;

use App\Models\Product\PriceListDetails;

class PriceRepository
{
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

    public function createPriceListDetails(array $data)
    {
        return PriceListDetails::create($data);
    }

    public function update($id, array $data)
    {
        return PriceListDetails::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        return;
    }
}
