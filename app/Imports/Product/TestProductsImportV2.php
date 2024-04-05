<?php

namespace App\Imports\Product;

use App\Models\Product\PriceListDetails;
use App\Models\Product\ProductVariation;
use App\Models\Product\UOM;
use App\Models\Product\VariationValue;
use App\Repositories\Product\BrandRepository;
use App\Repositories\Product\CategoryRepository;
use App\Repositories\Product\GenericRepository;
use App\Repositories\Product\ManufacturerRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\UnitCategoryRepository;
use App\Repositories\Product\UOMRepository;
use App\Repositories\Product\VariationRepository;
use App\Repositories\Product\VariationValueRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class TestProductsImportV2 implements ToCollection
{
    use Importable;

    protected $productRepository,
        $brandRepository,
        $manufacturerRepository,
        $categoryRepository,
        $genericRepository,
        $uomRepository,
        $unitCategoryRepository,
        $variationValueRepository;
    public function __construct()
    {

        ini_set('max_execution_time', '0');
        ini_set("memory_limit", "-1");
        ini_set("max_allowed_packet", "-1");

        $this->uom = new UOM;
        $this->productRepository = new ProductRepository();
        $this->brandRepository = new BrandRepository();
        $this->manufacturerRepository = new ManufacturerRepository();
        $this->categoryRepository = new CategoryRepository();
        $this->genericRepository = new GenericRepository();
        $this->uomRepository = new UOMRepository();
        $this->unitCategoryRepository = new UnitCategoryRepository();
        $this->variationRepository = new VariationRepository();
        $this->variationValueRepository = new VariationValueRepository(new VariationValue());
    }

    public function collection(Collection $rows)
    {
        try {
            $processedVariations = [];
            $createdTemplates = [];
            $createdTemplateValues = [];
            $prevRow = null;
            $product_id = null;

            foreach ($rows as  $index => $row){



                    $uomName = $row['uom'];
                    $purchaseUoMName = $row['purchase_uom'];

                    $brand_id = $this->brandRepository->getOrCreateBrandId($row['brand']);
                    $category_id = $this->categoryRepository->getOrCreateCategoryId($row['category']);
                    $manufacturer_id = $this->manufacturerRepository->getOrCreateManufacturerId($row['manufacturer']);
                    $generic_id = $this->genericRepository->getOrCreateGenericId($row['generic']);
                    $uom = $this->uomRepository->getByName($uomName);
                    $uom_id = $uom->id;
                    $purchase_uom = $this->uomRepository->getPurchaseUom($purchaseUoMName, $uom->unit_category_id);
                    $purchase_uom_id = $purchase_uom->id ?? null;

                    if ($uom_id !== $purchase_uom_id || $purchase_uom_id == null) {
                        return throw new Exception("UOM '{$row["uom"]}' and Purchase UOM '{$row["purchase_uom"]}' do not match in your excel");
                    }

                    $sku = $row['sku_leave_blank_to_auto_generate_sku'] ?? productSKU();
                    $prepareProductData = [
                        "name" => $row["name"],
                        "product_code" => $row["product_code"],
                        "sku" => $sku,
                        "product_type" => $row["product_type_consumeable_or_storable_or_service"],
                        "has_variation" => $row["has_variation_single_or_variable"],
                        "brand_id" => $brand_id,
                        "category_id" => $category_id,
                        "manufacturer_id" => $manufacturer_id,
                        "generic_id" => $generic_id,
                        "can_sale" => $row['can_sale_0_or_1'] ?? 0,
                        "can_purchase" => $row['can_purchase_0_or_1'] ?? 0,
                        "can_expense" => $row['can_expense_0_or_1'] ?? 0,
                        "uom_id" => $uom_id,
                        "purchase_uom_id" => $purchase_uom_id,
                        "product_custom_field1" => $row["custom_field_1"],
                        "product_custom_field2" => $row["custom_field_2"],
                        "product_custom_field3" => $row["custom_field_2"],
                        "product_custom_field4" => $row["custom_field_3"],
                        "image" => null,
                        "product_description" => $row['product_description'],
                        "created_by" => auth()->id()
                    ];

                    $createdProduct = $this->productRepository->create($prepareProductData);

                    $product_id = $createdProduct->id;
                    $hasVariation = $row['has_variation_single_or_variable'];


                    if (strtolower(trim($hasVariation)) === "variable") {

                        $raw_variation_sku = $row['variation_skus_seperated_values_blank_if_product_type_if_single'];
                        $raw_purchase_price = $row['purchase_price'];
                        $raw_selling_price = $row['selling_price'];



                        if (empty($raw_variation_sku)){
                            $vari_sku = $sku . '-0';
                        }else{
                            $vari_sku = $raw_variation_sku;
                        }

                        $product_variation = $this->productRepository->createVariation([
                            'product_id' => $product_id,
                            'variation_sku' => $vari_sku,
                            'variation_template_value_id' => null,
                            'default_purchase_price' => $raw_purchase_price,
                            'profit_percent' => floatval(str_replace(',', '', $row['profit_margin']) ) ?? null,
                            'default_selling_price' => $raw_selling_price,
                        ]);


                    }

                    $variations_name = $row['variation_name_keep_blank_if_product_type_is_single'];
                    $variations_value = $row['variation_values_seperated_values_blank_if_product_type_if_single'];

                    $variations_name_array = array_map(fn ($value) => trim($value), explode("|", $variations_name));
                    $variations_value_array = array_map(fn($value) => trim($value), explode("-", $variations_value));


                    foreach ($variations_name_array as $key => $variation_name){
                        if (!isset($createdTemplates[$variation_name])) {
                            $variation_template = $this->variationRepository->getOrCreateVariationId($variation_name);
                            $createdTemplates[$variation_name] = $variation_template->id;
                        }

                        $template_value = $this->variationRepository->createTemplateValues([
                                'name' => $variations_value_array[$key],
                                'variation_template_id' => $createdTemplates[$variation_name],
                                'created_by' => Auth::id()]
                        );

                        $this->variationValueRepository->create([
                            'product_id' => $product_id,
                            'product_variation_id' => $product_variation->id,
                            'variation_template_value_id' => $template_value->id,
                        ]);


                    }

            }

        }catch (\Exception $exception){
            $errorMessage = $exception->getMessage();
            dd($errorMessage);
            return throw new Exception($errorMessage);
        }
    }




}
