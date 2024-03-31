<?php

namespace App\Imports\Product;

use App\Models\Product\VariationValue;
use App\Repositories\Product\VariationValueRepository;
use Exception;
use App\Models\Product\UOM;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Product\PriceListDetails;
use App\Models\Product\ProductVariation;
use Maatwebsite\Excel\Concerns\Importable;
use App\Repositories\Product\UOMRepository;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Repositories\Product\BrandRepository;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Repositories\Product\GenericRepository;
use App\Repositories\Product\ProductRepository;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\Repositories\Product\CategoryRepository;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Repositories\Product\VariationRepository;
use App\Repositories\Product\ManufacturerRepository;
use App\Repositories\Product\UnitCategoryRepository;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;


class ProductsImportV2 implements
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
        $variationRepository,
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
            $createdTemplates =[];
            $prevRow = null;
            $productName = null;
            $variationsName = null;
            $productId = null;
            $skuIndex = 0;
            foreach ($rows as $key => $row) {

                if (!empty($row['selling_price']) && !empty($row['profit_margin'])){
                            return throw new Exception("Note: If you use profit, you don't need to use selling price");
                }

                if (($productName !== $row['name'] && $variationsName !== $row['variation_name_seperated_values_blank_if_product_type_if_single']) || $key === 0) {

                    $skuIndex = 0;
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

//
                    $sku = $row['sku_leave_blank_to_auto_generate_sku'] ?? productSKU();
                    //ok

                    $prepareProductData = [
                        "name" => $row["name"],
                        "product_code" => $row["product_code"],
                        "sku" => $sku,
                        "product_type" => $row["product_type_consumeable_or_storable_or_service"],
                        "has_variation" => strtolower(trim($row["has_variation_single_or_variable"])),
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


                    $raw_purchase_price = floatval(str_replace(',', '', $row['purchase_price']));
                    $raw_selling_price = floatval(str_replace(',', '', $row['selling_price']));
                    $raw_profit_margin = floatval(str_replace(',', '', $row['profit_margin']));


                    if (!empty($row['profit_margin']) && empty($row['selling_price'])){
                        $profit =  $raw_purchase_price * $raw_profit_margin / 100 ;
                        $sellingPrice = $raw_purchase_price + $profit;
                        $profitMargin = $raw_profit_margin;
                    }

                    if (!empty($row['selling_price']) && empty($row['profit_margin'])){
                        $profitMargin =  (($raw_selling_price - $raw_purchase_price) / $raw_purchase_price) * 100;
                        $sellingPrice = $raw_selling_price;
                    }

                    $hasVariation = $row['has_variation_single_or_variable'];

                    if (strtolower(trim($hasVariation)) === "variable") {
                        $raw_variation_sku = strtolower(trim($row['variation_sku_keep_blank_if_product_type_is_single']));

                        if (empty($raw_variation_sku)) {
                            $vari_sku = $sku . '-0'.$skuIndex;
                        } else {
                            $vari_sku = $raw_variation_sku;
                        }

                        $product_variation = $this->productRepository->createVariation([
                            'product_id' => $product_id,
                            'variation_sku' => $vari_sku,
                            'variation_template_value_id' => null,
                            'default_purchase_price' => floatval(str_replace(',', '',$raw_purchase_price)) ?? null,
                            'profit_percent' => $profitMargin,
                            'default_selling_price' => $sellingPrice,
                        ]);


                        $variations_name = $row['variation_name_seperated_values_blank_if_product_type_if_single'];
                        $variations_value = $row['variation_values_keep_blank_if_product_type_is_single'];

                        $variations_name_array = array_map('trim', explode("|", $variations_name));
                        $variations_value_array = array_map('trim', explode("-", $variations_value));

                        if (count($variations_name_array) !== count($variations_value_array)) {
                            return throw new Exception("Variation names and values do not match in your excel at row ".$key+2);
                        }

                        $count = count($variations_name_array);

                        for ($index = 0; $index < $count; $index++) {
                            $variation_name = $variations_name_array[$index];
                            $variation_value = $variations_value_array[$index];

                            $variation_template = $this->variationRepository->getOrCreateVariationId($variation_name);

                            $prepareVariationsTemplatesData = [
                                'product_id' => $product_id,
                                'variation_template_id' => $variation_template,
                                'created_by' => Auth::id(),
                            ];

                            $this->productRepository->createVariationTemplate($prepareVariationsTemplatesData);

                            if (!$variation_template) {
                                echo "Error: Variation template not found for name: $variation_name\n";
                                continue;
                            }

                            $variationTemplateValue = $this->variationRepository->queryTemplateValues()
                                ->where('name', trim($variation_value))->first();

                            if ($variationTemplateValue){
                                $template_value = $variationTemplateValue;
                            }else {
                                $template_value = $this->variationRepository->createTemplateValues([
                                    'name' => $variation_value,
                                    'variation_template_id' => $variation_template,
                                    'created_by' => Auth::id()
                                ]);
                            }

                            if (!$template_value) {
                                echo "Error: Failed to create template value for variation name: $variation_name\n";
                            }

                            $this->variationValueRepository->create([
                                'product_id' => $product_id,
                                'product_variation_id' => $product_variation->id,
                                'variation_template_value_id' => $template_value->id,
                            ]);
                        }


                        //Product Packaging
                        if (isset($row['packaging_info_package_qty_unit'])){
                            $packageRaw = $row['packaging_info_package_qty_unit'];
                            $this->createPackaging($packageRaw, $product_id, $product_variation, $uom->unit_category_id);
                        }
                        //Product Packaging

                        $this->createOrUpdatePriceListDetail('Variation', $product_variation->id, floatval(str_replace(',', '',$raw_selling_price)));


                    }

                    if (strtolower(trim($hasVariation)) === "single") {

                        $product_variation = $this->productRepository->createVariation([
                            'product_id' => $product_id,
                            'variation_sku' => $createdProduct->sku,
                            'default_purchase_price' => floatval(str_replace(',', '',$row['purchase_price'])),
                            'profit_percent' => $profitMargin,
                            'default_selling_price' => $sellingPrice,
                        ]);

                        //Product Packaging
                        if (isset($row['packaging_info_package_qty_unit'])){
                            $packageRaw = $row['packaging_info_package_qty_unit'];
                            $this->createPackaging($packageRaw, $product_id, $product_variation, $uom->unit_category_id);
                        }
                        //Product Packaging

                        $this->createOrUpdatePriceListDetail('Product', $product_id, floatval(str_replace(',', '',$row['purchase_price'])));
                    }

                    $skuIndex++;
                }else{



                    $raw_variation_sku = strtolower(trim($row['variation_sku_keep_blank_if_product_type_is_single']));

                    $raw_purchase_price = floatval(str_replace(',', '', $row['purchase_price']));
                    $raw_selling_price = floatval(str_replace(',', '', $row['selling_price']));
                    $raw_profit_margin = floatval(str_replace(',', '', $row['profit_margin']));


                    if (!empty($row['profit_margin']) && empty($row['selling_price'])){
                        $profit =  $raw_purchase_price * $raw_profit_margin / 100 ;
                        $sellingPrice = $raw_purchase_price + $profit;
                        $profitMargin = $raw_profit_margin;
                    }

                    if (!empty($row['selling_price']) && empty($row['profit_margin'])){
                        $profitMargin =  (($raw_selling_price - $raw_purchase_price) / $raw_purchase_price) * 100;
                        $sellingPrice = $raw_selling_price;
                    }

                    if (empty($raw_variation_sku)) {
                        $vari_sku = $sku . '-0'.$skuIndex;
                    } else {
                        $vari_sku = $raw_variation_sku;
                    }

                    $product_variation = $this->productRepository->createVariation([
                        'product_id' => $productId,
                        'variation_sku' => $vari_sku,
                        'variation_template_value_id' => null,
                        'default_purchase_price' => $raw_purchase_price,
                        'profit_percent' => $profitMargin,
                        'default_selling_price' => $sellingPrice,
                    ]);

                    $variations_name = $row['variation_name_seperated_values_blank_if_product_type_if_single'];
                    $variations_value = $row['variation_values_keep_blank_if_product_type_is_single'];

                    $variations_name_array = array_map('trim', explode("|", $variations_name));
                    $variations_value_array = array_map('trim', explode("-", $variations_value));

                    if (count($variations_name_array) !== count($variations_value_array)) {
                        return throw new Exception("In row ". $key+2 . ", the data in the variable name column and the variable value do not match in your Excel sheet.");
                    }

                    $count = count($variations_name_array);

                    for ($index = 0; $index < $count; $index++) {
                        $variation_name = $variations_name_array[$index];
                        $variation_value = $variations_value_array[$index];

                        $variation_template = $this->variationRepository->getOrCreateVariationId($variation_name);

                        if (!$variation_template) {
                            echo "Error: Variation template not found for name: $variation_name\n";
                            continue; // Skip to the next iteration
                        }

                        $variationTemplateValue = $this->variationRepository->queryTemplateValues()
                            ->where('name', trim($variation_value))->first();

                        if ($variationTemplateValue){
                            $template_value = $variationTemplateValue;
                        }else{
                            $template_value = $this->variationRepository->createTemplateValues([
                                'name' => $variation_value,
                                'variation_template_id' => $variation_template,
                                'created_by' => Auth::id()
                            ]);
                        }



                        if (!$template_value) {
                            echo "Error: Failed to create template value for variation name: $variation_name\n";
                        }

                        $this->variationValueRepository->create([
                            'product_id' => $productId,
                            'product_variation_id' => $product_variation->id,
                            'variation_template_value_id' => $template_value->id,
                        ]);
                    }

                    //Product Packaging
                    if (isset($row['packaging_info_package_qty_unit'])){
                        $packageRaw = $row['packaging_info_package_qty_unit'];
                        $this->createPackaging($packageRaw, $productId, $product_variation, $uom->unit_category_id);
                    }
                    //Product Packaging

                    $this->createOrUpdatePriceListDetail('Variation', $productId, floatval(str_replace(',', '',$row['purchase_price'])));

                    $skuIndex++;
                }

                $productName = $row['name'];
                $productId = $product_id;
                $variationsName = $row['variation_name_seperated_values_blank_if_product_type_if_single'];
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

        // this is to set default price need to change code struucture.This is bad practice
        $this->productRepository->insertVariation($variations);
        $variations=ProductVariation::where('product_id',$product_id)->get();
        foreach ($variations as $key => $variation) {
            $this->createOrUpdatePriceListDetail('Variation', $variation->id, $variation['default_selling_price']);
        }



    }


    private function createPackaging($packageRaw, $product_id, $createdProductVariation, $unit_category_id)
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
                    'product_variation_id' => $createdProductVariation->id,
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

    public function createOrUpdatePriceListDetail($type, $value, $defaultSellingPrice)
    {
        $priceListId = getSystemData('defaultPriceListId');

        $pricelistDetailQuery = PriceListDetails::where('pricelist_id', $priceListId)
            ->where('applied_type', $type)
            ->where('applied_value', $value);
        $pricelistDetailCheck = $pricelistDetailQuery->exists();

        if ($pricelistDetailCheck) {
            $pricelistDetailQuery->update([
                'cal_value' => $defaultSellingPrice,
            ]);
        } else {
            PriceListDetails::create([
                'pricelist_id' => $priceListId,
                'applied_type' => $type,
                'applied_value' => $value,
                'min_qty' => '1',
                'cal_type' => 'fixed',
                'cal_value' => $defaultSellingPrice,
            ]);
        }
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
