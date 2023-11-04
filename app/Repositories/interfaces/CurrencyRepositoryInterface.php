<?php

namespace App\Repositories\interfaces;

interface CurrencyRepositoryInterface
{
    public function getAll();
    public function find($id);
    public function defaultCurrency();
}
