<?php

namespace App\Http\Controllers\Product;

use App\Models\locationProduct;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Models\Product\Category;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Actions\product\ProductAction;
use Illuminate\Support\Facades\Storage;
use App\Models\Product\PriceListDetails;
use App\Models\Product\ProductVariation;
use App\Repositories\LocationRepository;
use Yajra\DataTables\Facades\DataTables;
use App\Models\settings\businessLocation;
use App\Services\product\productServices;
use App\Repositories\Product\UOMRepository;
use App\Repositories\Product\BrandRepository;
use App\Services\packaging\packagingServices;
use App\Models\Product\VariationTemplateValues;
use App\Repositories\Product\GenericRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\CategoryRepository;
use App\Repositories\Product\VariationRepository;
use App\Models\Product\ProductVariationsTemplates;
use App\Repositories\Product\ManufacturerRepository;
use App\Repositories\Product\UnitCategoryRepository;
use App\Http\Requests\Product\Product\ProductCreateRequest;
use App\Http\Requests\Product\Product\ProductUpdateRequest;
use App\Repositories\UserManagement\BusinessUserRepository;
use App\Repositories\interfaces\LocationRepositoryInterface;

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
    ) {
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
            ->addColumn('check_box', function ($product) {
                return $product->id;
            })
            ->addColumn('product', function ($product) {
                $deleted_variation = false;

                if ($product->has_variation === "variable") {
                    foreach ($product->productVariations as $value) {
                        $deleted_variation = optional($value)->variationTemplateValue->name ??  'deleted';
                    }
                }

                return ['image' => $product->image, 'name' => $product->name, 'deleted_variation' => $deleted_variation];
                // return ['image' => $product->image, 'name' => $product->name];
            })
            ->addColumn('assign_location', function ($product) {
                $data = locationProduct::where('product_id', $product->id)
                    ->with(['location:id,name'])
                    ->get()
                    ->pluck('location.name')
                    ->toArray();
                $result = implode(', ', $data);
                $strEnd = strlen('result') > 80 ? '.....' : '';
                return substr($result, 0, 80) . $strEnd;
            })
            ->addColumn('purchase_price', function ($product) {
                $purchase_price = null;
                $variation_values = null;
                $deleted_variation_values = null;
                // for single product
                if ($product->has_variation === "single") {
                    $purchase_price = $product->productVariations[0]->default_purchase_price ?? 0;
                }
                // for variation product
                if ($product->has_variation === "variable") {
                    foreach ($product->productVariations as $value) {
                        $purchase_price[] = $value->default_purchase_price;

                        $variation_values[] = optional($value)->variationTemplateValue->name ??  'deleted';
                        $deleted_variation_values[] = optional($value)->variationTemplateValue->name ?? VariationTemplateValues::withTrashed()->where('id', $value->variation_template_value_id)->pluck('name')->first();
                    }
                }
                return ['purchase_prices' => $purchase_price, 'variation_name' => $variation_values, 'deleted_variation_name' => $deleted_variation_values, 'has_variation' => $product->has_variation];
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


                if ($parentCategory && $subCategory) {
                    return $parentCategory + ", " + $subCategory;
                }
                if ($parentCategory) {
                    return $parentCategory;
                }
                if ($subCategory) {
                    return $subCategory;
                }
                return '';
                //                return ['parentCategory' => $parentCategory, 'subCategory' => $subCategory];
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
            ->rawColumns(['product', 'assign_location', 'purchase_price', 'category', 'brand', 'generic', 'manufacturer', 'action'])
            ->make(true);
    }

    public function index(
        LocationRepositoryInterface $locationRepository,
    ) {
//                return Product::with('productVariations', 'category', 'brand')->paginate();
        //        return $products = Product::with('productVariations', 'category', 'brand', 'packaging')->get();
        $categories = $this->categoryRepository->query()->select('name')->distinct()->pluck('name');
        $brands = $this->brandRepository->query()->select('name')->distinct()->pluck('name');
        $generics = $this->genericRepository->query()->select('name')->distinct()->pluck('name');
        $manufactures = $this->manufacturerRepository->query()->select('name')->distinct()->pluck('name');
        $product_types = $this->productRepository->query()->select('product_type')->distinct()->pluck('product_type')->toArray();
        $locations = $locationRepository->locationWithAccessControlQuery()->select('id', 'name')->get();

//        return Product::with('productVariations.variation_values.variation_template_value')->get();

        return view('App.product.product.productListV2', compact(
            'locations',
            'product_types',
            'categories',
            'brands',
            'generics',
            'manufactures'
        ));
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
//        return $request;
        try {
            DB::beginTransaction();
            $productAction->create($request);
            DB::commit();
            activity('product-transaction')
                ->log('Product creation has been success')
                ->event('create')
                ->properties(['product_name' => $request->product_name])
                ->status('success')
                ->save();

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
        } catch (Exception $exception) {
            DB::rollBack();
            dd($exception);
            $message = $exception->getMessage();
            activity('product-transaction')
                ->log('Product creation has been fail')
                ->event('create')
                ->status('fail')
                ->save();
            return redirect()->route('products')->with('error', "Product created fail due to $message");
        }
    }

    public function edit(Product $product, productServices $productServices, ProductRepository $productRepository)
    {
        $product_variation_template_value_ids = $this->productRepository->query()
            ->leftJoin('product_variations', 'product_variations.product_id', '=', 'products.id')
            ->select('products.*', 'product_variations.variation_template_value_id as variation_template_value_id')
            ->where('products.id', $product->id)
            ->pluck('variation_template_value_id');

        $variation_template_value_ids = $this->productRepository->queryVariationsTemplates()
            ->leftJoin('variation_template_values', 'variation_template_values.variation_template_id', '=', 'product_variations_tmplates.variation_template_id')
            ->where('product_variations_tmplates.product_id', $product->id)
            ->select('variation_template_values.*')
            ->pluck('id', 'name');

        $remain_variation_ids = array_diff($variation_template_value_ids->toArray(), $product_variation_template_value_ids->toArray());

        $product = $productRepository->query()
            ->where('id', $product->id)
            ->with([
                'product_variation_templates:id,product_id,variation_template_id',
            ])
            ->first();
        $productVariation = $this->productRepository->getVariationByProductIdWithRelationships($product->id, ['product', 'variationTemplateValue', 'variation_values.variation_template_value:id,name,variation_template_id']);

        //        if (empty($difference1) && empty($difference2)) {
        //            echo "Arrays have the same values.";
        //        } else {
        //            echo "Arrays have different values.\n";
        //            echo "Values in the first array but not in the second: " . implode(', ', $difference1) . "\n";
        //            echo "Values in the second array but not in the first: " . implode(', ', $difference2) . "\n";
        //        }




        return view('App.product.product.productEdit', [
            'product' => $product,
            'remain_variation_ids' => $remain_variation_ids,
            'brands' => $this->brandRepository->getAll(),
            'categories' => $this->categoryRepository->getWithRelationships(['parentCategory', 'childCategory']),
            'manufacturers' => $this->manufacturerRepository->getAll(),
            'generics' => $this->genericRepository->getAll(),
            'variations' => $this->variationRepository->getAllTemplate(),
            'unitCategories' => $this->unitCategoryRepository->getAll(),
            'uoms' => $this->uomRepository->getAll(),
            'productVariation' => $productVariation,
            'additional_products' => $productServices->additionalProductsRetrive($product->id),
            'packagings' => $this->productRepository->getPackagingByProductIdWithRelationships($product->id, ['uom']),
            'unit_category_id' => $this->uomRepository->getUomByUomId($product->uom_id)->first()->unit_category_id,
        ]);
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        try {
            DB::beginTransaction();
            // Update Product
            $img_name = $this->saveProductImage($request, $product->image);
            $productData = $this->prepareProductData($request, $img_name, false);
            DB::table('products')->where('id', $product->id)->update($productData);

            // Update Product Variationn
            $this->insertProductVariations($request, $product, false);

            $productService = new productServices();
            $productService->createAdditionalProducts($request->additional_product_details, $product, false);

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
            activity('product-transaction')
                ->log('Product update has been success')
                ->event('update')
                ->properties(['product_name' => $request->product_name])
                ->status('success')
                ->save();
            if ($request->has('save')) {
                return redirect('/product');
            }
        } catch (Exception $e) {
            DB::rollBack();
            activity('product-transaction')
                ->log('Product update has been fail')
                ->event('update')
                ->status('fail')
                ->save();
            return back()->with('message', $e->getMessage());
        }
    }

    public function delete(Product $product)
    {

        if ($product->receipe_of_material_id !== null && $product->product_type === 'consumeable') {
            activity('product-transaction')
                ->log('Product deletion has been warn due to associated with one or more combo/kit template')
                ->event('delete')
                ->properties(['product_name' => $product->name])
                ->status('warn')
                ->save();
            return response()->json(['error' => 'This product is associated with one or more combo/kit template. Delete the combo/kit template or associate them with a different product.']);
        }

        if ($product->receipe_of_material_id !== null && $product->product_type === 'service') {
            activity('product-transaction')
                ->log('Product deletion has been warn due to associated with one or more service template')
                ->event('delete')
                ->properties(['product_name' => $product->name])
                ->status('warn')
                ->save();
            return response()->json(['error' => 'This product is associated with one or more service template. Delete the service template or associate them with a different product.']);
        }


        try {
            DB::beginTransaction();
            $productVariationIds = ProductVariation::where('product_id', $product->id)->get()->pluck('id'); // to delete
            ProductVariation::whereIn('id', $productVariationIds)->update(['deleted_by' => Auth::user()->id]);

            $product->deleted_by = Auth::user()->id;
            $product->save();
            if ($product->image) {
                Storage::delete('product-image/' . $product->image);
            }

            $product->delete();
            ProductVariation::destroy($productVariationIds);

            DB::commit();
            activity('product-transaction')
                ->log('Product deletion has been success')
                ->event('delete')
                ->properties(['product_name' => $product->name])
                ->status('success')
                ->save();
            return response()->json(['message' => 'Deleted Successfully Product']);
        } catch (Exception $e) {
            DB::rollBack();
            activity('product-transaction')
                ->log('Product deletion has been fail')
                ->event('delete')
                ->status('fail')
                ->save();
            return back()->with('message', $e->getMessage());
        }
    }

    // Product Variation Delete
    public function deleteProductVariation($id)
    {

        try {
            DB::beginTransaction();
            $productVariation = ProductVariation::find($id);
            $productVariation->deleted_by = Auth::user()->id;
            $productVariation->save();

            $productVariation->delete();
            DB::commit();

            activity('product-transaction')
                ->log('Product Variation deletion has been success')
                ->event('delete')
                ->status('success')
                ->save();

            return response()->json(['message' => 'Deleted Successfully Product variation']);
        } catch (Exception $e) {
            DB::rollBack();

            activity('product-transaction')
                ->log('Product Variation deletion has been fail')
                ->event('delete')
                ->status('fail')
                ->save();

            return back()->with('message', $e->getMessage());
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
                    $this->createOrUpdatePriceListDetail('Variation', $variation['id'], $variation['default_selling_price']);
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


    public function getProducts(Request $request)
    {
        $q = $request->q;
        $porducts = Product::where(function ($query) use ($q) {
            if ($q != '') {
                $query->where('name', 'like', '%' . $q . '%')->orWhere('sku', '=', $q);
            } {
                return $query;
            }
        })
            ->paginate(10);
        return response()->json($porducts, 200);
    }
    public function getVariations(Request $request)
    {
        $keyword = $request->q;
        $variations = ProductVariation::query()
            ->select('product_variations.id', 'products.image', 'product_variations.variation_sku as sku', DB::raw("CONCAT(products.name, IFNULL(CONCAT(' (', variation_template_values.name, ') '), '')) AS name"))
            ->when($keyword && rtrim($keyword != ''), function ($q) use ($keyword) {
                $q->where('products.name', 'like', '%' . $keyword . '%')
                    ->orWhere('sku', '=', $keyword)
                    ->orWhere('product_variations.variation_sku', '=', $keyword);
            })
            ->leftJoin('products', 'product_variations.product_id', '=', 'products.id')
            ->leftJoin('variation_template_values', 'product_variations.variation_template_value_id', '=', 'variation_template_values.id')
            ->paginate(10);
        return response()->json($variations, 200);
    }

    public function getProductVariation(Request $request)
    {
        $q = $request->q;
        $products = Product::select(
            'products.*',
            'product_variations.*',
            'variation_template_values.*',
            'variation_template_values.name as variation_name',
            'products.name as name',
            'products.id as id',
            'product_variations.id as variation_id'
        )->leftJoin('product_variations', 'products.id', '=', 'product_variations.product_id')->leftJoin('variation_template_values', 'product_variations.variation_template_value_id', '=', 'variation_template_values.id')
            ->where(function ($query) use ($q) {
                if ($q != '') {
                    $query->where('products.name', 'like', '%' . $q . '%');
                } {
                    return $query;
                }
            })
            ->with([
                'product_packaging' => function ($query) use ($q) {
                    $query->where('package_barcode', $q);
                },
                'uom' => function ($q) {
                    $q->with('unit_category.uomByCategory');
                },
                'product_variations.packaging.uom'
            ])
            ->paginate(10);
        return response()->json($products, 200);
    }
}
