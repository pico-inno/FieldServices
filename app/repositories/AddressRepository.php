<?php

namespace App\Repositories;

use App\Models\LocalAddress;
use App\Trait\RepositoryMethods;

class AddressRepository
{
    use RepositoryMethods;

    protected $model;

    public function __construct(LocalAddress $address)
    {
        $this->model = $address;
    }
}
