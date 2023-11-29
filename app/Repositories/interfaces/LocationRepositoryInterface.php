<?php

namespace App\Repositories\interfaces;


interface LocationRepositoryInterface
{

    public function getAll();
    public function find($id);
    public function getAllPermitted();
}
