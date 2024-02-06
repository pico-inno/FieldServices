<?php

namespace App\Imports\Product;

use App\Repositories\Product\BrandRepository;
use App\Repositories\Product\CategoryRepository;
use App\Repositories\Product\GenericRepository;
use App\Repositories\Product\ManufacturerRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\UnitCategoryRepository;
use App\Repositories\Product\UOMRepository;
use App\Repositories\Product\VariationRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Models\Product\UOM;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;


class ProductsImport implements
    WithValidation,
    ToCollection,
    WithHeadingRow,
    WithChunkReading,
    WithBatchInserts
{
    use Importable;

//    private $brands;
//    private $categories;
//    private $generics;
//    private $manufacturers;
//    private $unit_category;
    private $uom;
//    private $variations;
//    private $variation_values;
//    private  $packageing;
//    private $realtionId = [];

    private $imageCoordinates = [];

    private $rowCount;

    protected $productRepository,
        $brandRepository,
        $manufacturerRepository,
        $categoryRepository,
        $genericRepository,
        $uomRepository,
        $unitCategoryRepository,
        $variationRepository;

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
    }



    public function collection(Collection $rows)
    {
        $this->rowCount = $rows->count();
        try {
            // begin: for image
            $spreadsheet = IOFactory::load(request()->file('import-products'));
            $i = 0;
            foreach ($spreadsheet->getActiveSheet()->getDrawingCollection() as $drawing) {
                if ($drawing instanceof MemoryDrawing) {
                    ob_start();
                    call_user_func(
                        $drawing->getRenderingFunction(),
                        $drawing->getImageResource()
                    );
                    $imageContents = ob_get_contents();
                    ob_end_clean();
                    switch ($drawing->getMimeType()) {
                        case MemoryDrawing::MIMETYPE_PNG:
                            $extension = 'png';
                            break;
                        case MemoryDrawing::MIMETYPE_GIF:
                            $extension = 'gif';
                            break;
                        case MemoryDrawing::MIMETYPE_JPEG:
                            $extension = 'jpg';
                            break;
                    }
                } else if ($drawing instanceof Drawing) {
                    $image = $drawing->getImage();
                    $imageContents = $image->getImageData();
                    $extension = $image->getExtension();
                }

                if (isset($imageContents) && isset($extension)) {
                    $myFileName = '00_Image_' . ++$i . '.' . $extension;
                    $this->imageCoordinates[] = [
                        $drawing->getCoordinates() => $myFileName
                    ];
                    Storage::put('product-image/' . $myFileName, $imageContents);
                }
            }

            // end: for image
            $imageArrays = array_merge(...$this->imageCoordinates);
            // dd($imageArrays);
            $count = 1;
            foreach ($rows as $key => $row) {

                $currentRow = 'P' . ++$count;
                $imageName = null;
                if (array_key_exists($currentRow, $imageArrays)) {
                    $imageName = $currentRow;
                }

                //ok
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

                $variation_name = $row['variation_name_keep_blank_if_product_type_is_single'];
                $variation_id = $this->variationRepository->getOrCreateVariationId($variation_name);
                $sku = $row['sku_leave_blank_to_auto_generate_sku'] ?? productSKU();
                //ok

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
                    "image" => $imageName ? $imageArrays[$imageName] : null,
                    "product_description" => $row['product_description'],
                    "created_by" => auth()->id()
                ];

                $createdProduct = $this->productRepository->create($prepareProductData);

                $product_id = $createdProduct->id;

                $prepareVariationsTemplatesData = [
                    'product_id' => $product_id,
                    'variation_template_id' => $variation_id,
                    'created_by' => Auth::id(),
                ];

                $createdProductVariationsTemplates = $this->productRepository->createVariationTemplate($prepareVariationsTemplatesData);


                //Product Packaging
                if (isset($row['packaging_info_package_qty_unit'])){
                    $packageRaw = $row['packaging_info_package_qty_unit'];
                    $this->createPackaging($packageRaw, $product_id, $createdProductVariationsTemplates, $uom->unit_category_id);
                }
                //Product Packaging


                // for product variation
                $hasVariation = $row['has_variation_single_or_variable'];
                if (strtolower(trim($hasVariation)) === "variable") {
                    $raw_variation_value = $row['variation_values_seperated_values_blank_if_product_type_if_single'];
                    $variation_value_array = array_map(fn ($value) => trim($value), explode("|", $raw_variation_value));

                    $raw_variation_sku = $row['variation_skus_seperated_values_blank_if_product_type_if_single'];
                    $variation_sku_array = [];
                    if ($raw_variation_sku){
                        $variation_sku_array = array_map(fn ($value) => trim($value), explode("|", $raw_variation_sku));
                    }

                    $raw_purchase_price = $row['purchase_price'];
                    $raw_selling_price = $row['selling_price'];
                    $purchase_price_array = [];
                    $selling_price_array = [];
                    if ($raw_purchase_price){
                        $purchase_price_array = array_map(fn ($value) => trim($value), explode("|", $raw_purchase_price));
                    }
                    if ($raw_selling_price){
                        $selling_price_array = array_map(fn ($value) => trim($value), explode("|", $raw_selling_price));
                    }


                     $this->createProductVariation($key, $product_id, $variation_id, $variation_value_array, $variation_sku_array, $row, $sku, $purchase_price_array, $selling_price_array);

                }

                if (strtolower(trim($hasVariation)) === "single") {

                    $preparedProductVariation = $this->prepareProductVariation($row);
                    $preparedProductVariation['default_purchase_price'] = floatval(str_replace(',', '',$row['purchase_price']));
                    $preparedProductVariation['default_selling_price'] = floatval(str_replace(',','', $row['selling_price']));
                    $preparedProductVariation['product_id'] = $product_id;
                    $preparedProductVariation['variation_sku'] = $createdProduct->sku;

                    $this->productRepository->createVariation($preparedProductVariation);
                }
            }

        } catch (Exception $e) {

            $errorMessage = $e->getMessage();
            return throw new Exception($errorMessage);
        }
    }

    private function createProductVariation($row_key, $product_id, $variation_id, $variation_values, $variation_sku, $row, $product_sku, $purchase_price, $selling_price)
    {

        $variations = [];
        foreach ($variation_values as $key => $value) {

            $variation_value_query = $this->variationRepository->queryTemplateValues()->where('variation_template_id', $variation_id);

            $variation_value_names = $variation_value_query->pluck('name')->map(fn ($v) => strtolower($v));


            $variation_template_value_id = null;

            if (!$variation_value_names->contains(strtolower($value))) {
                $createdTemplateValues = $this->variationRepository->createTemplateValues([
                        'name' => $value,
                        'variation_template_id' => $variation_id,
                        'created_by' => auth()->id()]
                );
                $variation_template_value_id = $createdTemplateValues->id;
            }
            if ($variation_value_names->contains(strtolower($value))) {
                foreach ($variation_value_query->get() as $v) {
                    if (strtolower($v->name) === strtolower($value)) {
                        $variation_template_value_id = $v->id;
                    }
                }
            }

            if (empty($variation_sku)){
                $vari_sku = $product_sku . '-0' . $key;
            }else{
//                if (trim($variation_sku[$key]) == '') {
//                    $vari_sku = $product_sku . '-' . $key + 1;
//                } else {
                    $vari_sku = $variation_sku[$key];
//                };
            }


            if (count($selling_price) != count($variation_values) || count($purchase_price) != count($variation_values)) {
                throw new Exception("Variable value and price are not match in your excel at row ". $row_key + 2);
            }

            if (isset($purchase_price)){
                $purchase_price_variable = $purchase_price[$key];
            }

            if (isset($selling_price)){
                $selling_price_variable = $selling_price[$key];
            }

            $preparedProductVariation = $this->prepareProductVariation($row);
            $preparedProductVariation['default_purchase_price'] = floatval(str_replace(',', '', $purchase_price_variable)) ?? null;
            $preparedProductVariation['default_selling_price'] = floatval(str_replace(',', '',$selling_price_variable)) ?? null;
            $preparedProductVariation['product_id'] = $product_id;
            $preparedProductVariation['variation_sku'] = $vari_sku;
            $preparedProductVariation['variation_template_value_id'] = $variation_template_value_id;
            $variations[] = $preparedProductVariation;
        };
        $this->productRepository->insertVariation($variations);



    }


    private function createPackaging($packageRaw, $product_id, $createdProductVariationsTemplates, $unit_category_id)
    {

        if ($packageRaw !== null) {
            $packages = explode('|', $packageRaw);
            $packaging = [];
            foreach ($packages as $package) {



                if (!preg_match('/^([^=]+)=(\d+)-([^=]+)$/', $package, $matches)) {

                    throw new \Exception('Invalid package format: ' . $package .' in your excel.');
                }

                $name = $matches[1];
                $quantity = $matches[2];
                $uom_name = $matches[3];

                $uom =$this->uomRepository->getPurchaseUom(trim($uom_name), $unit_category_id);

                $uomId = $uom->id ?? null;

                if ($uomId == null) {
                    return throw new Exception("Package UOM '{$uom_name}' and Default UOM are do not match in your excel");
                }

                $preparePackagingData = [
                    'packaging_name' => $name,
                    'product_id' => $product_id,
                    'product_variation_id' => $createdProductVariationsTemplates->id,
                    'quantity' => $quantity,
                    'uom_id' => $uomId,
                    'for_purchase' => true,
                    'for_sale' => true,
                ];
                $packaging[] = $preparePackagingData;

            }
            $this->productRepository->insertPackaging($packaging);
        }
    }

    private function prepareProductVariation($row)
    {
        return  [
//            'default_purchase_price' => $row['purchase_price'],
            'profit_percent' => floatval(str_replace(',', '', $row['profit_margin']) ) ?? null,
//            'default_selling_price' => $row['selling_price'],
            'created_by' => auth()->id()
        ];
    }


    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function rules(): array
    {
        return [
            '*.sku_leave_blank_to_auto_generate_sku' => [
                'nullable',
                Rule::unique('products', 'sku'),
            ],

            '*.uom' => [
                Rule::exists('uom', 'name'),
            ],

            '*.purchase_uom' => [
                Rule::exists('uom', 'name'),
            ],

            '*.has_variation_single_or_variable' => [
              Rule::In(['single', 'Single','variable','Variable', 'SINGLE', 'VARIABLE'])
            ],

        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.sku_leave_blank_to_auto_generate_sku' => 'The SKU has already been taken.',
            '*.uom' => 'UOM not found!',
            '*.purchase_uom' => 'Purchase UOM not found!',
            '*.has_variation_single_or_variable' => 'Incorrect name of single or variable',
        ];
    }

}
