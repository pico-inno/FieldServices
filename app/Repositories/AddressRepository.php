<?php

namespace App\Repositories;

use App\Models\Address;
use App\Trait\RepositoryMethods;

class AddressRepository
{
    use RepositoryMethods;

    protected $model;

    public function __construct(Address $address)
    {
        $this->model = $address;
    }
}
