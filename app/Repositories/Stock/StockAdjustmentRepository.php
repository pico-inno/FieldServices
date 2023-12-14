<?php

namespace App\Repositories\Stock;

use App\Models\Stock\StockAdjustment;
use App\Models\Stock\StockAdjustmentDetail;

class StockAdjustmentRepository
{

    public function query()
    {
        return StockAdjustment::query();
    }

    public function queryDetails()
    {
        return StockAdjustmentDetail::query();
    }

    public function create(array $data)
    {
        return StockAdjustment::create($data);
    }

    public function createDetail(array $data)
    {
        return StockAdjustmentDetail::create($data);
    }

    public function insertDetail(array $data)
    {
        return StockAdjustment::insert($data);
    }

    public function update($id, array $data)
    {
        return StockAdjustment::where('id', $id)->update($data);
    }
    public function updateDetail($id,array $data)
    {
        return StockAdjustmentDetail::where('id', $id)->update($data);
    }
}
