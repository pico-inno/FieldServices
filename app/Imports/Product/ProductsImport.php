<?php

namespace App\Imports\Product;

use Exception;
use App\Models\Product\Brand;
use App\Models\Product\Generic;
use App\Models\Product\Product;
use Illuminate\Validation\Rule;
use App\Models\Product\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Product\Manufacturer;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Product\ProductVariation;
use App\Models\Product\VariationTemplates;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Models\Product\VariationTemplateValues;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Models\Product\ProductVariationsTemplates;
use App\Models\Product\UnitCategory;
use App\Models\Product\UOM;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use Illuminate\Support\Facades\Validator;

class ProductsImport implements
    WithValidation,
    ToCollection,
    WithHeadingRow,
    WithChunkReading
{
    use Importable;

    private $brands;
    private $categories;
    private $generics;
    private $manufacturers;
    private $unit_category;
    private $uom;
    private $variations;
    private $variation_values;

    private $realtionId = [];

    private $imageCoordinates = [];

    public function __construct()
    {
        $this->brands = Brand::select('id', 'name');
        $this->categories = Category::select('id', 'name', 'parent_id');
        $this->generics = Generic::select('id', 'name');
        $this->manufacturers = Manufacturer::select('id', 'name');
        $this->unit_category = new UnitCategory;
        $this->uom = new UOM;
        $this->variations = VariationTemplates::select('id', 'name');
        $this->variation_values = VariationTemplateValues::select('id', 'name', 'variation_template_id');
    }

    private function createOrGetRecord($model, $query, $field1, $value1, $field2 = null){
        if($value1){
            if (array_key_exists($value1, $this->realtionId)) {
                $id = $this->realtionId[$value1];
                return $id;
            } else {
                if (!$field2) {
                    $raw_query = $query->where($field1, $value1)->first();
                    if ($raw_query) {
                        $this->realtionId[$value1] = $raw_query->id;
                        return $raw_query->id;
                    }
                }
                if ($field2) {
                    $raw_query = $query->where($field1, $value1)->whereNull($field2)->first();
                    if ($raw_query) {
                        $this->realtionId[$value1] = $raw_query->id;
                        return $raw_query->id;
                    }
                }

                $newData = $model::create([
                    $field1 => $value1,
                    'created_by' => auth()->id()
                ])->id;
                if ($newData) {
                    $this->realtionId[$value1] = $newData;
                    return $newData;
                }
            }
        }
    }

    private function formatProductVariation($product_id, $variation_id, $variation_values, $variation_sku, $row, $product_sku){

        $format_data = [];
        foreach($variation_values as $key => $value){
            $variation_value_query = $this->variation_values->where('variation_template_id', $variation_id);
            $vari_val_query_lowercase = $variation_value_query->pluck('name')->map(fn($v) => strtolower($v));
            $variation_template_value_id = null;
            if(!$vari_val_query_lowercase->contains(strtolower($value))){
                $variation_template_value_id = VariationTemplateValues::create(['name' => $value,'variation_template_id' => $variation_id, 'created_by' => auth()->id()])->id;
            }
            if($vari_val_query_lowercase->contains(strtolower($value))){
                foreach($variation_value_query->get() as $v){
                    if(strtolower($v->name) === strtolower($value)){
                        $variation_template_value_id = $v->id;
                    }
                }
            }

            $vari_sku = $variation_sku[$key] ?? $product_sku . '-' . $key+1;

            $format_data[] = [
                'product_id' => $product_id,
                'variation_sku' => $vari_sku,
                'variation_template_value_id' => $variation_template_value_id,
                'default_purchase_price' => $row['purchase_price'],
                'profit_percent' => '',
                'default_selling_price' => $row['selling_price'],
                'created_by' => auth()->id()
            ];
        };

        return $format_data;
    }
    private function UoMID($query1, $query2, $data1, $data2){
        if ($data1 && $data2) {
            $raw_query1 = $query1->where('name', $data1)->first();
            if ($raw_query1) {
                 $raw_data = $query2->where('name', $data2)->where('unit_category_id',  $raw_query1->id)->first();

                 if(!$raw_data){
                    throw new \Exception("UoM doesn't exist");
                }
                 return $raw_data->id;
            }
        }
    }

    private function PurchaseUoMID($query1, $query2, $data1, $data2){
        if ($data1 && $data2) {
            $raw_query1 = $query1->where('name', $data1)->first();
            if ($raw_query1) {
                 $raw_data = $query2->where('name', $data2)->where('unit_category_id', $raw_query1->id)->first();

                 if(!$raw_data){
                    throw new \Exception("UoM doesn't exist");
                }
                 return $raw_data->id;
            }
        }
    }               

    private function processUoM($rowData){
        $unitCategoryName = $rowData['unit_category'];
        $uomName = $rowData['uom'];
        $purchaseUoMName = $rowData['purchase_uom'];

        $unitCategoryId = $this->unit_category->where('name', $unitCategoryName)->pluck('id')->first();
        $uomId = $this->uom->where('name', $uomName)->where('unit_category_id', $unitCategoryId)->pluck('id')->first();
        $purchaseUoMId = $this->uom->where('name', $purchaseUoMName)->where('unit_category_id', $unitCategoryId)->pluck('id')->first();
        if(!$uomId){
            throw new \Exception("UoM doesn't exist");
        }
        if(!$purchaseUoMId){
            throw new \Exception("Purchase UoM doesn't exist");
        }

        if($uomId && $purchaseUoMId){
            return [
                'uom_id' => $uomId,
                'purchaseUoM_id' => $purchaseUoMId
            ];
        }
    }

    public function collection(Collection $rows)
    {
        // dd($rows->toArray());
        DB::beginTransaction();
        try{
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
                        case MemoryDrawing::MIMETYPE_PNG :
                            $extension = 'png';
                            break;
                        case MemoryDrawing::MIMETYPE_GIF:
                            $extension = 'gif';
                            break;
                        case MemoryDrawing::MIMETYPE_JPEG :
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
                    Storage::put('product-image/' .$myFileName, $imageContents);
                }
            }

            // end: for image
            $imageArrays = array_merge(...$this->imageCoordinates);
            // dd($imageArrays);
            $count = 1;
            foreach($rows as $key => $row){
                $currentRow = 'P'. ++$count;
                $imageName = null;
                if(array_key_exists($currentRow, $imageArrays)){
                    $imageName = $currentRow;
                }
                $brand_id = $this->createOrGetRecord(Brand::class, $this->brands, 'name', $row['brand']);
                $category_id = $this->createOrGetRecord(Category::class, $this->categories, 'name', $row['category'], 'parent_id');
                $generic_id = $this->createOrGetRecord(Generic::class, $this->generics, 'name', $row['generic']);
                $manufacturer_id = $this->createOrGetRecord(Manufacturer::class, $this->manufacturers, 'name', $row['manufacturer']);
                // $uom = $this->processUoM($row);
                $uom_id = $this->UoMID($this->unit_category, $this->uom, $row['unit_category'], $row['uom']);
                $purchase_uom_id = $this->PurchaseUoMID($this->unit_category, $this->uom, $row['unit_category'], $row['purchase_uom']);
                $variation_name = $row['variation_name_keep_blank_if_product_type_is_single'];
                $variation_id = $variation_name ? $this->createOrGetRecord(VariationTemplates::class, $this->variations, 'name', $variation_name) : null;

                $product_count = Product::withTrashed()->count();
                $sku = $row['sku_leave_blank_to_auto_generate_sku'] ?? sprintf('%07d',($product_count+1));

                $product = new Product([
                    "name" => $row["name"],
                    "product_code" => $row["product_code"],
                    "sku" => $sku,
                    "product_type" => $row["product_type_consumeable_or_storable_or_service"],
                    "has_variation"=>$row["has_variation_single_or_variable"],
                    "brand_id" => $brand_id,
                    "category_id" => $category_id,
                    "manufacturer_id" => $manufacturer_id,
                    "generic_id" => $generic_id,
                    "can_sale" => $row['can_sale_0_or_1'],
                    "can_purchase" => $row['can_purchase_0_or_1'],
                    "can_expense" => $row['can_expense_0_or_1'],
                    "uom_id" => $uom_id,
                    "purchase_uom_id" => $purchase_uom_id,
                    "product_custom_field1" => $row["custom_field_1"],
                    "product_custom_field2" => $row["custom_field_2"],
                    "product_custom_field3" => $row["custom_field_2"],
                    "product_custom_field4" => $row["custom_field_3"],
                    "image" => $imageName ? $imageArrays[$imageName] : null,
                    "product_description" => $row['product_description'],
                    "created_by" => auth()->id()
                ]);
                $product->save();
                $product_id = $product->fresh()->id;

                ProductVariationsTemplates::create([
                    'product_id' => $product_id,
                    'variation_template_id' => $variation_id,
                    'created_by' => auth()->id()
                ]);

                // for product variation
                $hasVariation = $row['has_variation_single_or_variable'];
                if(trim($hasVariation) === "variable"){
                    $raw_variation_value = $row['variation_values_seperated_values_blank_if_product_type_if_single'];
                    $variation_value_array = array_map(fn($value) => trim($value), explode("|", $raw_variation_value));

                    $raw_variation_sku = $row['variation_skus_seperated_values_blank_if_product_type_if_single'];
                    $variation_sku_array = array_map(fn($value) => trim($value), explode("|", $raw_variation_sku));
                    $insert_data = $this->formatProductVariation($product_id, $variation_id, $variation_value_array, $variation_sku_array, $row, $sku);
                    ProductVariation::insert($insert_data);
                }

                if(trim($hasVariation) === "single"){
                    ProductVariation::create([
                        'product_id' => $product_id,
                        'default_purchase_price' => $row['purchase_price'],
                        'profit_percent' => $row['profit_margin'],
                        'default_selling_price' => $row['selling_price'],
                        'created_by' => auth()->id()
                    ]);
                }
            }
            DB::commit();
        }catch (Exception $e){
            $errorMessage = $e->getMessage();
            DB::rollBack();
            return back()->withErrors($errorMessage)->withInput();
            Log::error($errorMessage);
        }
    }

    public function chunkSize(): int
    {
        return 20;
    }

    public function rules(): array
    {
        return [
            '*.sku_leave_blank_to_auto_generate_sku' => ['nullable',
                                                         Rule::unique('products', 'sku')]
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.sku_leave_blank_to_auto_generate_sku' => 'The SKU has already been taken.'
        ];
    }
}

