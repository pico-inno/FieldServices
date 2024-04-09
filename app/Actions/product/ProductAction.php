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
    ) {
        $this->productRepository = $productRepository;
        $this->variationRepository = $variationRepository;
        $this->priceRepository = $priceRepository;
        $this->variationValueRepository = $variationValueRepository;
    }

    public function create($data)
    {
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
                } else { //One Variation

                    //                    $variationData['variation_template_value_id'] = $id;

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
            //            foreach ($data->variation_name as $variation_template_id){
            //                $productVariationsTemplateData = [
            //                    'product_id' => $createdProduct->id,
            //                    'variation_template_id' => $variation_template_id,
            //                    'created_by' => \auth()->id(),
            //                ];
            //
            //                $this->productRepository->createVariationTemplate($productVariationsTemplateData);
            //            }

        } else { //for single
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
        }



        //Creation of Product Variation Template
        if ($data->variation_name) {
            foreach ($data->variation_name as $variation_template_id) {
                $productVariationsTemplateData = [
                    'product_id' => $createdProduct->id,
                    'variation_template_id' => $variation_template_id,
                    'created_by' => \auth()->id(),
                ];

                $this->productRepository->createVariationTemplate($productVariationsTemplateData);
            }
        }



        if ($data->additional_product_details) {
            $productService = new productServices();
            $productService->createAdditionalProducts($data->additional_product_details, $createdProduct, true);
        }

        if ($data->packaging_repeater) {
            $packagingServices = new packagingServices();
            $packagingServices->createWithBulk($data->packaging_repeater, $createdProduct);
        }
    }


    //Start
    public function edit($data, $productId)
    {
        $updatedProductData = $this->prepareProductData($data);

        // Update product image if provided
        if ($data->hasFile('image')) {
            $savedImageName = $this->saveProductImage($data);
            $updatedProductData['image'] = $savedImageName;
        }

        // Update the product
        $updatedProductData['updated_by'] = auth()->id();
        DB::table('products')
            ->where('id', $productId)
            ->update($updatedProductData);

        // Check new remaining variations include or not
        if ($this->hasNewVariations($data->product_variation_id)) {

            //Start creating new variations//
            $newVariationIds = $this->getNewVariationIds($data->variation_id, $data->product_variation_id);

            //This function is getting first index point of new variations. we make variation create tasks based on that start index number
            function getIndexBeforeNull($productVariationIds)
            {
                $index = null;
                foreach ($productVariationIds as $key => $value) {
                    if ($value === null) {
                        $index = $key - 1;
                        break;
                    }
                }
                return $index + 1;
            }

            $loop = getIndexBeforeNull($data->product_variation_id);

            foreach ($newVariationIds as $index => $newVariationId) {

                $variationData = [
                    'product_id' => $productId,
                    'variation_sku' => $data->sku . '-0' . ($loop + $index), //we use getIndexBeforeNull() function to get correct array index number of data pass from ProductEdit.blade.php
                    'default_purchase_price' => $data->exc_purchase[$loop + $index],
                    'profit_percent' => $data->profit_percentage[$loop + $index],
                    'default_selling_price' => $data->selling_price[$loop + $index],
                    'alert_quantity' => $data->alert_quantity[$loop + $index],
                    'created_by' => \auth()->id(),
                ];

                if (strpos($newVariationId, '-') !== false) {
                    // Multi Variation
                    $createdProductVariation = $this->productRepository->createVariation($variationData);

                    $individualIds = explode('-', $newVariationId);


                    foreach ($individualIds as $individualId) {
                        $this->variationValueRepository->create([
                            'product_id' => $productId,
                            'product_variation_id' => $createdProductVariation->id,
                            'variation_template_value_id' => $individualId,
                        ]);
                    }
                } else {
                    // Single Variation
                    $createdProductVariation = $this->productRepository->createVariation($variationData);
                    $this->variationValueRepository->create([
                        'product_id' => $productId,
                        'product_variation_id' => $createdProductVariation->id,
                        'variation_template_value_id' => $newVariationId,
                    ]);
                }
            }
            //End creating new variations//
            // Start updating existing variations //
            if ($data->has_variation_hidden === "variable") {
                foreach ($data->variation_id as $index => $variationId) {
                    if ($index >= $loop - 1) {
                        break;
                    }
                    $variationData = [
                        'product_id' => $productId,
                        'variation_sku' => $updatedProductData['sku'] . '-0' . $index,
                        'default_purchase_price' => $data->exc_purchase[$index],
                        'profit_percent' => $data->profit_percentage[$index],
                        'default_selling_price' => $data->selling_price[$index],
                        'alert_quantity' => $data->alert_quantity[$index],
                        'updated_by' => auth()->id(),
                    ];

                    if (strpos($variationId, '-') !== false) {
                        // Update existing variations and their template values
                        $individualIds = explode('-', $variationId);
                        $productVariationId = $data->product_variation_id[$index];


                        // Update the product variation
                        DB::table('product_variations')
                            ->where('id', $productVariationId)
                            ->update($variationData);

                        // Update variation template values
                        DB::table('variation_values')
                            ->where('product_variation_id', $productVariationId)
                            ->delete();

                        foreach ($individualIds as $templateValueId) {
                            DB::table('variation_values')->insert([
                                'product_id' => $productId,
                                'product_variation_id' => $productVariationId,
                                'variation_template_value_id' => $templateValueId,
                            ]);
                        }
                    } else {
                        // Single variation template value
                        $variationData['variation_template_value_id'] = $variationId;
                        $productVariationId = $data->product_variation_id[$index];

                        // Update the product variation
                        DB::table('product_variations')
                            ->where('id', $productVariationId)
                            ->update($variationData);

                        // Update variation template value

                        DB::table('variation_values')
                            ->where('product_variation_id', $productVariationId)
                            ->update(['variation_template_value_id' => $variationId]);
                    }


                    $this->createOrUpdatePriceListDetail('Variation', $productVariationId, $data->selling_price[$index]);
                }
            }
            // Start updating existing variations //
        } else {
            // Else Condition is for scenario for nothing new variations, just update existing variation 
            if ($data->has_variation_hidden === "variable") {
                foreach ($data->variation_id as $index => $variationId) {
                    $variationData = [
                        'product_id' => $productId,
                        'variation_sku' => $updatedProductData['sku'] . '-0' . $index,
                        'default_purchase_price' => $data->exc_purchase[$index],
                        'profit_percent' => $data->profit_percentage[$index],
                        'default_selling_price' => $data->selling_price[$index],
                        'alert_quantity' => $data->alert_quantity[$index],
                        'updated_by' => auth()->id(),
                    ];

                    if (strpos($variationId, '-') !== false) {
                        // Update existing variations and their template values
                        $individualIds = explode('-', $variationId);
                        $productVariationId = $data->product_variation_id[$index];


                        // Update the product variation
                        DB::table('product_variations')
                            ->where('id', $productVariationId)
                            ->update($variationData);

                        // Update variation template values
                        DB::table('variation_values')
                            ->where('product_variation_id', $productVariationId)
                            ->delete();

                        foreach ($individualIds as $templateValueId) {
                            DB::table('variation_values')->insert([
                                'product_id' => $productId,
                                'product_variation_id' => $productVariationId,
                                'variation_template_value_id' => $templateValueId,
                            ]);
                        }
                    } else {
                        // Single variation template value
                        $variationData['variation_template_value_id'] = $variationId;
                        $productVariationId = $data->product_variation_id[$index];

                        // Update the product variation
                        DB::table('product_variations')
                            ->where('id', $productVariationId)
                            ->update($variationData);

                        // Update variation template value

                        DB::table('variation_values')
                            ->where('product_variation_id', $productVariationId)
                            ->update(['variation_template_value_id' => $variationId]);
                    }


                    $this->createOrUpdatePriceListDetail('Variation', $productVariationId, $data->selling_price[$index]);
                }
            }
        }



        // Update Product Variation Templates

        $this->updateProductVariationTemplates($data, $productId);


        // Update additional product details if provided
        // if ($data->additional_product_details) {
        //     $productService = new ProductService();
        //     $productService->updateAdditionalProducts($data->additional_product_details, $productId);
        // }

        // Update packaging details if provided
        // if ($data->packaging_repeater) {
        //     $packagingServices = new PackagingServices();
        //     $packagingServices->updateWithBulk($data->packaging_repeater, $productId);
        // }
    }

    private function updateProductVariationTemplates($data, $productId)
    {

        // Delete existing product variation templates
        DB::table('product_variations_tmplates')
            ->where('product_id', $productId)
            ->delete();

        // Create updated product variation templates if provided
        if ($data->variation_name) {
            foreach ($data->variation_name as $variationTemplateId) {
                DB::table('product_variations_tmplates')->insert([
                    'product_id' => $productId,
                    'variation_template_id' => $variationTemplateId,
                    'created_by' => auth()->id(),
                ]);
            }
        }
    }


    public function update($product, $data)
    {
        return DB::transaction(function () use ($product, $data) {
            $savedImageName = $this->saveProductImage($data, $product->image);
            $preparedProductData = $this->prepareProductData($data);
            $preparedProductData['image'] = $savedImageName;
            $preparedProductData['updated_by'] = \auth()->id();

            $updatedProduct = $this->productRepository->update($product->id, $preparedProductData);
        });
    }

    public function createPackaging()
    {
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

    private function prepareProductVariationData($data)
    {
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

    protected function hasNewVariations($productVariationIds)
    {
        foreach ($productVariationIds as $variationId) {
            if ($variationId === null) {
                return true; // New variation found
            }
        }

        return false; // No new variations found
    }
    protected function getNewVariationIds($variationIds, $productVariationIds)
    {
        $newVariationIds = [];

        foreach ($productVariationIds as $index => $productVariationId) {
            if ($productVariationId === null) {
                $newVariationIds[] = $variationIds[$index];
            }
        }

        return $newVariationIds;
    }
}
