<?php

namespace App\Repositories\Product;

use App\Models\Product\VariationValue;
use App\Trait\RepositoryMethods;

class VariationValueRepository
{
    use RepositoryMethods;

    protected $model;

    public function __construct(VariationValue $variationValue)
    {
        $this->model = $variationValue;
    }

}
