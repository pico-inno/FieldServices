<?php

namespace App\Services\product;

use App\Models\Product\VariationTemplateValues;
use App\repositories\ProductRepository;
use App\repositories\VariationRepository;

class VariationService
{
    protected $variationRepository;
    protected $productRepository;

    public function __construct(
        VariationRepository $variationRepository,
        ProductRepository   $productRepository
    )
    {
        $this->variationRepository = $variationRepository;
        $this->productRepository = $productRepository;
    }

    public function isVariationAssociatedWithProducts($variationId)
    {
        $productVariationIds = $this->productRepository->getProductVariationIdsByVariationTemplateId($variationId);

        return in_array($variationId, $productVariationIds);
    }
}
