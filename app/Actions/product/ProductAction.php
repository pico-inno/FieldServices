<?php

namespace App\Actions\product;

use App\Models\Product\Product;
use App\Repositories\Product\PriceRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\VariationRepository;
use App\Repositories\Product\VariationValueRepository;
use App\Services\packaging\packagingServices;
use App\Services\product\productServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductAction
{
    protected $productRepository;
    protected $variationRepository;
    protected $priceRepository;
    protected $variationValueRepository;
    public function __construct(
        ProductRepository $productRepository,
        VariationRepository $variationRepository,
        PriceRepository $priceRepository,
        VariationValueRepository $variationValueRepository,
    ){
        $this->productRepository = $productRepository;
        $this->variationRepository = $variationRepository;
        $this->priceRepository = $priceRepository;
        $this->variationValueRepository = $variationValueRepository;
    }

    public function create($data){

       $savedImageName = $this->saveProductImage($data);
       $preparedProductData = $this->prepareProductData($data);
       $preparedProductData['image'] = $savedImageName;
       $preparedProductData['created_by'] = \auth()->id();

       $createdProduct = $this->productRepository->create($preparedProductData);

       //variation
        if ($data->has_variation === "variable") { //for variation

            foreach ($data->variation_id as $index => $id) {


                $variationData = [
                    'product_id' => $createdProduct->id,
                    'variation_sku' => $createdProduct->sku . '-0' . $index,
                    'default_purchase_price' => $data->exc_purchase[$index],
                    'profit_percent' => $data->profit_percentage[$index],
                    'default_selling_price' => $data->selling_price[$index],
                    'alert_quantity' => $data->alert_quantity[$index],
                    'created_by' => \auth()->id(),
                ];


                if (strpos($id, '-') !== false) { //Multi Variation

                    $createdProductVariation = $this->productRepository->createVariation($variationData);

                    $individual_ids = explode('-', $id);

                    foreach ($individual_ids as $individual_id) {
                        $this->variationValueRepository->create([
                            'product_id' => $createdProduct->id,
                            'product_variation_id' => $createdProductVariation->id,
                            'variation_template_value_id' => $individual_id,
                        ]);
                    }

                }else{ //One Variation

                    $variationData['variation_template_value_id'] = $id;

                    $createdProductVariation = $this->productRepository->createVariation($variationData);

                    $this->variationValueRepository->create([
                        'product_id' => $createdProduct->id,
                        'product_variation_id' => $createdProductVariation->id,
                        'variation_template_value_id' => $id,
                    ]);

                }


                $this->createOrUpdatePriceListDetail('Variation', $createdProductVariation->id, $data->selling_price[$index]);

            }

            //Creation of Product Variation Template
            foreach ($data->variation_name as $variation_template_id){
                $productVariationsTemplateData = [
                    'product_id' => $createdProduct->id,
                    'variation_template_id' => $variation_template_id,
                    'created_by' => \auth()->id(),
                ];

                $this->productRepository->createVariationTemplate($productVariationsTemplateData);
            }

        }else{ //for single
            $preparedProductVariationData = [
                'product_id' => $createdProduct->id,
                'variation_sku' => $createdProduct->sku,
                'default_purchase_price' => $data->single_exc,
                'profit_percent' => $data->single_profit,
                'default_selling_price' => $data->single_selling,
                'alert_quantity' => $data->single_alert_quantity,
                'created_by' => \auth()->id()
            ];
            $this->createOrUpdatePriceListDetail('Product', $createdProduct->id, $data->single_selling);

            $this->productRepository->createVariation($preparedProductVariationData);

            $productVariationsTemplateData = [
                'product_id' => $createdProduct->id,
                'variation_template_id' => $data->variation_name,
                'created_by' => \auth()->id(),
            ];

            $this->productRepository->createVariationTemplate($productVariationsTemplateData);
        }








        if ($data->additional_product_details) {
            $productService = new productServices();
            $productService->createAdditionalProducts($data->additional_product_details, $createdProduct,true);
        }

        if($data->packaging_repeater){
            $packagingServices= new packagingServices();
            $packagingServices->createWithBulk($data->packaging_repeater, $createdProduct);
        }

    }

    public function update($product, $data){
        return DB::transaction(function () use ($product, $data){
            $savedImageName = $this->saveProductImage($data, $product->image);
            $preparedProductData = $this->prepareProductData($data);
            $preparedProductData['image'] = $savedImageName;
            $preparedProductData['updated_by'] = \auth()->id();

            $updatedProduct = $this->productRepository->update($product->id, $preparedProductData);
        });
    }

    public function createPackaging(){

    }
    private function saveProductImage($request, $existingImagePath = null)
    {
        if ($request->hasFile('avatar')) {
            if ($existingImagePath) {
                Storage::delete('product-image/' . $existingImagePath);
            }

            $file = $request->file('avatar');
            $fileName = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();

            if (Storage::disk('public')->putFileAs('product-image', $file, $fileName)) {
                return $fileName;
            }

            return null;
        }

        if ($request->avatar_remove == 1 && $existingImagePath) {
            Storage::delete('product-image/' . $existingImagePath);
            return null;
        }

        return $existingImagePath;
    }


    public function createOrUpdatePriceListDetail($type, $value, $defaultSellingPrice)
    {
        $priceListId = getSystemData('defaultPriceListId');

        $conditions = [
            'pricelist_id' => $priceListId,
            'applied_type' => $type,
            'applied_value' => $value,
        ];
        $priceListDetails = $this->priceRepository->getPriceListDetailsByConditions($conditions);

        if ($priceListDetails->exists()) {
            $priceListDetails->update([
                'cal_value' => $defaultSellingPrice,
            ]);
        } else {
            $preparePriceListDetails = [
                'pricelist_id' => $priceListId,
                'applied_type' => $type,
                'applied_value' => $value,
                'min_qty' => '1',
                'cal_type' => 'fixed',
                'cal_value' => $defaultSellingPrice,
            ];
            $this->priceRepository->createPriceListDetails($preparePriceListDetails);
        }
    }



    private function prepareProductData($data)
    {
        return [
            'name' => $data->product_name,
            'product_code' => $data->product_code,
            'brand_id' => $data->brand,
            'category_id' => $data->category,
            'sub_category_id' => $data->sub_category,
            'manufacturer_id' => $data->manufacturer,
            'generic_id' => $data->generic,
            'uom_id' => $data->uom_id,
            'purchase_uom_id' => $data->purchase_uom_id,
            'product_custom_field1' => $data->custom_field1,
            'product_custom_field2' => $data->custom_field2,
            'product_custom_field3' => $data->custom_field3,
            'product_custom_field4' => $data->custom_field4,
            'product_description' => $data->quill_data,
            'sku' => $data->sku ?? sprintf('%07d', Product::count() + 1),
//            'image' => $img_name ?? null,
            'can_sale' => $data->can_sale ? 1 : 0,
            'can_purchase' => $data->can_purchase ? 1 : 0,
            'can_expense' => $data->can_expense ? 1 : 0,
            'is_recurring' => $data->is_recurring ? 1 : 0,
            'is_inactive' => $data->product_inactive ? 1 : 0,
            'has_variation' => $data->has_variation ?? $data->has_variation_hidden,
            'product_type' => $data->product_type,
        ];

    }

    private function prepareProductVariationData($data){
        return [
//            'product_id',
//            'variation_sku',
//            'variation_template_value_id',
            'default_purchase_price' => $data->exc_purchase,
            'profit_percent' => $data->profit_percentage,
            'default_selling_price' => $data->selling_price,
            'alert_quantity' => $data->alert_quantity,
        ];
    }


}
