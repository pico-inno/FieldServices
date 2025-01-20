<?php

namespace App\Repositories\Stock;

use App\Models\stock_history;

class StockHistoryRepository
{

    public function query()
    {
        return stock_history::query();
    }

    public function create(array $data)
    {
        return stock_history::create($data);
    }
}
