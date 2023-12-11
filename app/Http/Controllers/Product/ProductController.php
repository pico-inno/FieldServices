<?php

namespace App\Http\Controllers\Product;

use App\Actions\product\ProductAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\Product\ProductCreateRequest;
use App\Http\Requests\Product\Product\ProductUpdateRequest;
use App\Models\Product\Category;
use App\Models\Product\PriceListDetails;
use App\Models\Product\Product;
use App\Models\Product\ProductVariation;
use App\Models\Product\ProductVariationsTemplates;
use App\Models\Product\VariationTemplateValues;
use App\Models\settings\businessLocation;
use App\Repositories\Product\BrandRepository;
use App\Repositories\Product\CategoryRepository;
use App\Repositories\Product\GenericRepository;
use App\Repositories\Product\ManufacturerRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\UnitCategoryRepository;
use App\Repositories\Product\UOMRepository;
use App\Repositories\Product\VariationRepository;
use App\Repositories\UserManagement\BusinessUserRepository;
use App\Services\packaging\packagingServices;
use App\Services\product\productServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    private $variation_template_values;
    protected $businessUserRepository, $productRepository, $brandRepository, $categoryRepository, $manufacturerRepository, $genericRepository, $uomRepository, $unitCategoryRepository, $variationRepository;

    public function __construct(
        BusinessUserRepository $businessUserRepository,
        BrandRepository $brandRepository,
        CategoryRepository $categoryRepository,
        ManufacturerRepository $manufacturerRepository,
        GenericRepository $genericRepository,
        UOMRepository $uomRepository,
        UnitCategoryRepository $unitCategoryRepository,
        VariationRepository $variationRepository,
        ProductRepository $productRepository,
    )
    {
        $this->middleware(['auth', 'isActive']);
        $this->middleware('canView:product')->only(['index', 'productDatas']);
        $this->middleware('canCreate:product')->only(['add', 'create']);
        $this->middleware('canUpdate:product')->only(['edit', 'update']);
        $this->middleware('canDelete:product')->only('delete');
        $this->variation_template_values = VariationTemplateValues::query();

        $this->businessUserRepository = $businessUserRepository;
        $this->brandRepository = $brandRepository;
        $this->categoryRepository = $categoryRepository;
        $this->manufacturerRepository = $manufacturerRepository;
        $this->genericRepository = $genericRepository;
        $this->uomRepository = $uomRepository;
        $this->unitCategoryRepository = $unitCategoryRepository;
        $this->variationRepository = $variationRepository;
        $this->productRepository = $productRepository;
    }


    public function productDatas(Request $request)
    {
        $length = $request->input('length', 10);

        $products = Product::with('productVariations', 'category', 'brand')->get();

        return DataTables::of($products)
            ->addColumn('product', function ($product) {
                return ['image' => $product->image, 'name' => $product->name];
                // return ['image' => $product->image, 'name' => $product->name];
            })
            ->addColumn('purchase_price', function ($product) {
                $purchase_price = null;
                $variation_values = null;
                // for single product
                if ($product->has_variation === "single") {
                    $purchase_price = $product->productVariations[0]->default_purchase_price ?? 0;
                }
                // for variation product
                if ($product->has_variation === "variable") {
                    foreach ($product->productVariations as $value) {
                        $purchase_price[] = $value->default_purchase_price;
                        $variation_values[] = $value->variationTemplateValue->name;
                    }
                }
                return ['purchase_prices' => $purchase_price, 'variation_name' => $variation_values, 'has_variation' => $product->has_variation];
            })
            ->addColumn('selling_price', function ($product) {
                $selling_price = null;
                // for single product
                if ($product->has_variation === "single") {
                    $selling_price = $product->productVariations[0]->default_selling_price ?? 0;
                }
                // for variation product
                if ($product->has_variation === "variable") {
                    foreach ($product->productVariations as $value) {
                        $selling_price[] = $value->default_selling_price;
                    }
                }

                return ['selling_prices' => $selling_price, 'has_variation' => $product->has_variation];
            })
            ->addColumn('category', function ($product) {
                $parentCategory = null;
                $subCategory = null;
                if ($product->category_id) {
                    $parentCategory = Category::with('parentCategory', 'childCategory')->find($product->category_id)->name ?? null;
                }
                if ($product->sub_category_id) {
                    $subCategory = Category::with('parentCategory', 'childCategory')->find($product->sub_category_id)->name ?? null;
                }

                return ['parentCategory' => $parentCategory, 'subCategory' => $subCategory];
            })
            ->addColumn('brand', function ($product) {
                $brand = null;
                if ($product->brand_id) {
                    $brand = $product->brand->name ?? null;
                }
                return $brand;
            })
            ->addColumn('generic', function ($product) {
                $generic = null;
                if ($product->generic_id) {
                    $generic = $product->generic->name ?? null;
                }
                return $generic;
            })
            ->addColumn('manufacturer', function ($product) {
                $manufacturer = null;
                if ($product->manufacturer_id) {
                    $manufacturer = $product->manufacturer->name ?? null;
                }
                return $manufacturer;
            })
            ->addColumn('action', function ($product) {

                return $product->id;
            })
            ->rawColumns(['product', 'purchase_price', 'category', 'brand', 'generic', 'manufacturer', 'action'])
            ->make(true);
    }

    public function index()
    {
        return view('App.product.product.productList');
    }

    public function add($quickAdd = false)
    {
        $view = $quickAdd ? 'App.product.product.quickAddProduct' : 'App.product.product.productAdd';

        return view($view, [
            'brands' => $this->brandRepository->getAll(),
            'categories' => $this->categoryRepository->getWithRelationships(['parentCategory', 'childCategory']),
            'manufacturers' => $this->manufacturerRepository->getAll(),
            'generics' => $this->genericRepository->getAll(),
            'variations' => $this->variationRepository->getAllTemplate(),
            'unitCategories' => $this->unitCategoryRepository->getAll(),
            'uoms' => $this->uomRepository->getAll(),
        ]);

    }

    public function create(ProductCreateRequest $request, ProductAction $productAction)
    {

        $productAction->create($request);

        if ($request->save === "save") {
            return redirect()->route('products')->with('message', 'Product created successfully');
        }
        if ($request->save === "save_and_another") {
            return redirect()->route('product.add')->with('message', 'Product created successfully');
        }
        if ($request->input('form_type') === 'from_pos') {
            return response()->json(['success' => true, 'message' => 'Product created successfully']);
        }
        if ($request->save === "app_opening_stock") {
            return view('App.openingStock.add', [
                'stockin_persons' => $this->businessUserRepository->getAllWithRelationships(['personal_info']),
                'locations' => businessLocation::all(),
            ]);
        }

    }

    public function edit(Product $product, productServices $productServices)
    {
        return view('App.product.product.productEdit', [
                'product' => $product,
                'brands' => $this->brandRepository->getAll(),
                'categories' => $this->categoryRepository->getWithRelationships(['parentCategory', 'childCategory']),
                'manufacturers' => $this->manufacturerRepository->getAll(),
                'generics' => $this->genericRepository->getAll(),
                'variations' => $this->variationRepository->getAllTemplate(),
                'unitCategories' => $this->unitCategoryRepository->getAll(),
                'uoms' => $this->uomRepository->getAll(),
                'productVariation' => $this->productRepository->getVariationByProductIdWithRelationships($product->id, ['product', 'variationTemplateValue']),
                'additional_products' => $productServices->additionalProductsRetrive($product->id),
                'packagings' => $this->productRepository->getPackagingByProductIdWithRelationships($product->id, ['uom']),
                'unit_category_id' => $this->uomRepository->getUomByUomId($product->uom_id)->first()->unit_category_id,
            ]);
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {

        DB::beginTransaction();
        try {
            // Update Product
            $img_name = $this->saveProductImage($request, $product->image);
            $productData = $this->prepareProductData($request, $img_name, false);
            DB::table('products')->where('id', $product->id)->update($productData);

            // Update Product Variationn
            $this->insertProductVariations($request, $product, false);

            $productService = new productServices();
            $productService->createAdditionalProducts($request->additional_product_details, $product,false);

            // for packaging
            if ($request->packaging_repeater) {
                $packagingServices = new packagingServices();
                $packagingServices->update($request->packaging_repeater, $product);
            }
            // Update Product Variation Template
            ProductVariationsTemplates::where('product_id', $product->id)->update([
                'product_id' => $product->id,
                'variation_template_id' => $request->variation_template_id_hidden,
                'updated_by' => Auth::user()->id
            ]);

            DB::commit();
            if ($request->has('save')) {
                return redirect('/product');
            }
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return back()->with('message', $e->getMessage());
        }
    }

    public function delete(Product $product)
    {
        DB::beginTransaction();
        try {
            $productVariationIds = ProductVariation::where('product_id', $product->id)->get()->pluck('id'); // to delete
            ProductVariation::whereIn('id', $productVariationIds)->update(['deleted_by' => Auth::user()->id]);

            $product->deleted_by = Auth::user()->id;
            $product->save();
            if ($product->image) {
                Storage::delete('product-image/' . $product->image);
            }

            $product->delete();
            ProductVariation::destroy($productVariationIds);

            // to UOMSellingPrice
            // $toDeleteUomSelling = UOMSellingprice::byProductVariationIds($productVariationIds)->get()->pluck('id');
            // UOMSellingprice::byId($toDeleteUomSelling)->update(['deleted_by' => Auth::user()->id]);
            // UOMSellingprice::destroy($toDeleteUomSelling);

            DB::commit();
            return response()->json(['message' => 'Deleted Sucessfully Product']);
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    // Product Variation Delete
    public function deleteProductVariation($id)
    {
        DB::beginTransaction();
        try {
            $productVariation = ProductVariation::find($id);
            $productVariation->deleted_by = Auth::user()->id;
            $productVariation->save();

            $productVariation->delete();

            // to UOMSellingPrice
            // UOMSellingprice::where('product_variation_id', $id)->update(['deleted_by' => Auth::user()->id]);
            // UOMSellingprice::where('product_variation_id', $id)->delete();

            DB::commit();
            return response()->json(['message' => 'Deleted Sucessfully Product variation']);
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    private function saveProductImage($request, $existingImagePath = null)
    {
        if ($request->hasFile('avatar')) {
            if ($existingImagePath) {
                Storage::delete('product-image/' . $existingImagePath);
            }
            $file = $request->file('avatar');
            $uuid = Str::uuid()->toString();
            $extension = $file->getClientOriginalExtension();

            $fileName = $uuid . '.' . $extension;


            if (Storage::disk('public')->put('product-image/' . $fileName, file_get_contents($file))) {
                // File successfully saved
                return $fileName;
            } else {
                return null;
            }
        } else {
            if ($request->avatar_remove == 1 && $existingImagePath) {
                Storage::delete('product-image/' . $existingImagePath);
                return null;
            } else {
                return $existingImagePath;
            }
        }
    }

    private function prepareProductData($request, $img_name, $isCreating = true)
    {
        $has_variation = $isCreating ? $request->has_variation : $request->has_variation_hidden;

        $productData = [
            'name' => $request->product_name,
            'product_code' => $request->product_code,
            'brand_id' => $request->brand,
            'category_id' => $request->category,
            'sub_category_id' => $request->sub_category,
            'manufacturer_id' => $request->manufacturer,
            'generic_id' => $request->generic,
            'uom_id' => $request->uom_id,
            'purchase_uom_id' => $request->purchase_uom_id,
            'product_custom_field1' => $request->custom_field1,
            'product_custom_field2' => $request->custom_field2,
            'product_custom_field3' => $request->custom_field3,
            'product_custom_field4' => $request->custom_field4,
            'product_description' => $request->quill_data,
            'sku' => $request->sku ?? sprintf('%07d', Product::count() + 1),
            'image' => $img_name ?? null,
            'can_sale' => $request->can_sale ? 1 : 0,
            'can_purchase' => $request->can_purchase ? 1 : 0,
            'can_expense' => $request->can_expense ? 1 : 0,
            'is_recurring' => $request->is_recurring ? 1 : 0,
            'is_inactive' => $request->product_inactive ? 1 : 0,
            'has_variation' => $has_variation,
            'product_type' => $request->product_type,
        ];

        if ($isCreating) {
            $productData['created_by'] = Auth::user()->id;
        } else {
            $productData['updated_by'] = Auth::user()->id;
        }
        return $productData;
    }


    private function insertProductVariations($request, $nextProduct, $isCreating = true)
    {
        $nextProductId = $nextProduct->id;
        $has_variation = $isCreating ? $request->has_variation : $request->has_variation_hidden;
        $variation_template_id = $isCreating ? $request->variation_name : $request->variation_template_id_hidden;
        $priceListId = getSystemData('defaultPriceListId');
        if ($has_variation === "variable") {
            if (!$isCreating) {
                // Get all variation IDs of the product from the database
                $dbVariationIds = ProductVariation::where('product_id', $nextProductId)->pluck('id');

                // Find variation IDs to delete
                $variationsToDelete = array_diff($dbVariationIds->toArray(), $request->product_variation_id);

                if (!empty($variationsToDelete)) {
                    ProductVariation::whereIn('id', $variationsToDelete)->update([
                        'deleted_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ]);

                    ProductVariation::destroy($variationsToDelete);
                }
            }
            $variationTemplateValuesQuery = $this->variation_template_values
                ->where('variation_template_id', $variation_template_id)
                ->select('id', 'name')
                ->get();

            $lowercaseVariationNames = $variationTemplateValuesQuery->pluck('name')->map(fn ($v) => strtolower($v))->toArray();

            $variationNameAndIdMap = $variationTemplateValuesQuery->mapWithKeys(function ($item) {
                return [strtolower($item->name) => $item->id];
            })->toArray();

            $productVariationCount = ProductVariation::withTrashed()->count();
            $productVariations = [];

            foreach ($request->variation_value as $index => $value) {
                $variationName = strtolower($value);

                if (in_array($variationName, $lowercaseVariationNames)) {
                    $variationTemplateValueId = $variationNameAndIdMap[$variationName];
                } else {
                    $variationTemplateValueId = VariationTemplateValues::create([
                        'name' => $value,
                        'variation_template_id' => $variation_template_id,
                        'created_by' => auth()->id(),
                    ])->id;
                }
                //   $productVariationSku = $request->variation_sku[$index] ?? sprintf('%07d', ($productVariationCount + ($index + 1)));
                $variationData = [
                    'product_id' => $nextProductId,
                    'variation_sku' => $nextProduct['sku'] . '-0' . $index,
                    'variation_template_value_id' => $variationTemplateValueId,
                    'default_purchase_price' => $request->exc_purchase[$index],
                    'profit_percent' => $request->profit_percentage[$index],
                    'default_selling_price' => $request->selling_price[$index],
                    'alert_quantity' => $request->alert_quantity[$index],
                ];

                if ($isCreating) {
                    $variationData['created_by'] = auth()->id();
                    $variationData['created_at'] = now();
                }
                if (!$isCreating) {
                    $variationData['id'] = $request->product_variation_id[$index] ?? null;
                    $variationData['updated_by'] = auth()->id();
                    $variationData['updated_at'] = now();
                }

                $productVariations[] = $variationData;
            }

            if ($isCreating) {
                foreach ($productVariations as $productVariation) {
                    $variationData = ProductVariation::create($productVariation);
                    $this->createOrUpdatePriceListDetail('Variation', $variationData->id, $variationData['default_selling_price']);
                }
            }
            if (!$isCreating) {
                foreach ($productVariations as $variation) {
                    ProductVariation::updateOrCreate(['id' => $variation['id']], $variation);
                    $this->createOrUpdatePriceListDetail('Variation', $variation['id'], $variationData['default_selling_price']);
                }
            }
        }
        if ($has_variation === "single") {
            $productSingle = [
                'product_id' => $nextProductId,
                'variation_sku' => $nextProduct['sku'],
                //                'variation_template_value_id' => $nextProduct['sku'],
                'default_purchase_price' => $request->single_exc,
                'profit_percent' => $request->single_profit,
                'default_selling_price' => $request->single_selling,
                'alert_quantity' => $request->single_alert_quantity,
            ];
            $this->createOrUpdatePriceListDetail('Product', $nextProductId, $productSingle['default_selling_price']);
            if ($isCreating) {
                $productSingle['created_by'] = auth()->id();
                $productSingle['created_at'] = now();
                $variation = DB::table('product_variations')->insert($productSingle);
            }

            if (!$isCreating) {
                $productVariationId = ProductVariation::where('product_id', $nextProductId)->first()->id;
                $productSingle['updated_by'] = auth()->id();
                $productSingle['updated_at'] = now();
                DB::table('product_variations')->where('id', $productVariationId)->update($productSingle);
            }
        }
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


    public function getProducts(Request $request){
        $q = $request->q;
        $porducts = Product::where(function ($query) use ($q) {
            if($q!=''){
                $query->where('name', 'like', '%' . $q . '%');
//                    ->orWhere('name', 'like', '%' . $q . '%');
            }{
                return $query;
            }
        })
            ->paginate(10);
        return response()->json($porducts, 200);
    }
}
