<?php

namespace App\Livewire;

use App\Models\Product\ProductVariation;
use App\Models\Product\VariationTemplateValues;
use App\Repositories\Product\VariationRepository;
use Livewire\Component;

class ProductVariationList extends Component
{
    public $product;

    public $variations;

    public $variation_template_values;
    public function mount($product,
    VariationRepository $variationRepository
    )
    {
        $this->product = $product;

        $this->variation_template_values = $variationRepository->queryTemplateValues()->select('id','name')->get();

        $this->variations = ProductVariation::where('product_id', $this->product->id)
            ->with('variation_values.variation_template_value')
            ->get();

        foreach ($this->variations as $variation) {
            $value_names = '';
            foreach ($variation->variation_values as $value) {
                $value_names .= $value->variation_template_value->name . '-';
            }
            $value_names = rtrim($value_names, '-');

            $variation->variation_name = $value_names;
        }

    }
    public function render()
    {
        return view('livewire.product-variation-list');
    }


}
