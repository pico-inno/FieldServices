<?php

namespace App\Repositories;

use App\Models\lotSerialDetails;

class LotSerialDetailsRepository
{
    public function query()
    {
        return lotSerialDetails::query();
    }

    public function create(array $data)
    {
        return lotSerialDetails::create($data);
    }
}
