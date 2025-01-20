<?php

namespace App\Repositories;

use App\Models\CurrentStockBalance;

class CurrentStockBalanceRepository
{
    public function query()
    {
        return CurrentStockBalance::query();
    }

    public function create(array $data)
    {
        return CurrentStockBalance::create($data);
    }
}
