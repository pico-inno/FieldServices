<?php

namespace App\Http\Controllers\Report;

use App\Helpers\UomHelper;
use App\Models\lotSerialDetails;
use App\Models\sale\sales;
use App\Models\BusinessUser;
use App\Models\Product\Unit;
use App\Models\Stock\StockTransferDetail;
use App\Models\stock_history;
use App\Repositories\interfaces\LocationRepositoryInterface;
use App\Repositories\Stock\StockAdjustmentRepository;
use App\Services\Stock\StockAdjustmentServices;
use Faker\Core\Number;
use Illuminate\Http\Request;
use App\Models\Product\Brand;
use App\Models\Stock\Stockout;
use App\Models\Contact\Contact;
use App\Models\Product\Product;
use App\Models\Product\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CurrentStockBalance;
use App\Models\purchases\purchases;
use App\Models\Stock\StockTransfer;
use App\Http\Controllers\Controller;
use App\Models\Stock\StockAdjustment;
use App\Services\Report\reportServices;
use Modules\StockInOut\Entities\Stockin;
use mysql_xdevapi\Exception;
use mysql_xdevapi\Expression;
use Yajra\DataTables\Facades\DataTables;
use App\Models\settings\businessLocation;

use App\Models\settings\businessSettings;



class ReportController extends Controller
{
    private $locations;
    public function __construct(
        LocationRepositoryInterface $locationRepository,
    )
    {
        $this->middleware(['auth', 'isActive']);
        $this->locations = $locationRepository;
    }

    //Start: Sale
    public function saleIndex()
    {

        $locations = businessLocation::select('id', 'name', 'parent_location_id')->get();
        $customers = Contact::where('type', 'Customer')->get();

        return view('App.report.sale.index', [
            'locations' => $locations,
            'customers' => $customers,
        ]);
    }

    public function saleFilterThisMonths(Request $request)
    {

        $startDate = $request->data['filter_first_day'];
        $endDate = $request->data['filter_last_day'];

        $query = sales::where('is_delete', 0)
            ->whereBetween('sold_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(sold_at) as sold_date'), DB::raw('SUM(total_sale_amount) as total_sale_amount'))
            ->groupBy('sold_date');


        $result = $query->get()->toArray();

        return response()->json($result, 200);
    }
    public function saleFilter(Request $request)
    {
        $dateRange = $request->data['filter_date'];
        $dates = explode(' - ', $dateRange);

        $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[0])->startOfDay();
        $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[1])->endOfDay();

        $query = sales::where('is_delete', 0)
            ->with('business_location_id', 'customer')
            ->whereBetween('sold_at', [$startDate, $endDate]);

        //        if ($request->data['filter_locations'] != 0) {
        //            $query->where('business_location_id', $request->data['filter_locations']);
        //        }

        if ($request->data['filter_locations'] != 0) {
            $locationId = childLocationIDs($request->data['filter_locations']);
            $query->whereIn('business_location_id', $locationId);
        }

        if ($request->data['filter_customers'] != 0) {
            $query->where('contact_id', $request->data['filter_customers']);
        }

        if ($request->data['filter_status'] != 0) {
            $query->where('status', $request->data['filter_status']);
        }


        $result = $query->get()->toArray();

        return response()->json($result, 200);
    }

    public function saleDetailsIndex()
    {
        $locations = businessLocation::select('id', 'name', 'parent_location_id')->get();
        $customers = Contact::where('type', 'Customer')->get();

        $categories = Category::select('id', 'name', 'parent_id')->get();
        $brands = Brand::select('id', 'name',)->get();
        $products = Product::select('id', 'name')->get();

        return view('App.report.sale.details', [
            'locations' => $locations,
            'customers' => $customers,

            'categories' => $categories,
            'brands' => $brands,
            'products' => $products,
        ]);
    }
    public function saleDetailsFilter(Request $request)
    {
        $filterProduct = $request->data['filter_product'];
        $filterCategory = $request->data['filter_category'];
        $filterBrand = $request->data['filter_brand'];
        $dateRange = $request->data['filter_date'];
        $dates = explode(' - ', $dateRange);

        $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[0])->startOfDay();
        $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[1])->endOfDay();

        $query = sales::where('is_delete', 0)
            ->with(['saleDetails' => function ($query) {
                $query->where('is_delete', 0);
            }, 'customer', 'business_location_id'])
            ->whereBetween('sold_at', [$startDate, $endDate]);

        //        if ($request->data['filter_locations'] != 0) {
        //            $query->where('business_location_id', $request->data['filter_locations']);
        //        }

        if ($request->data['filter_locations'] != 0) {
            $locationId = childLocationIDs($request->data['filter_locations']);
            $query->whereIn('business_location_id', $locationId);
        }

        $sales = $query->get();
        $saleDetails = $query->get()->pluck('saleDetails')->flatten();

        $productIds = $saleDetails->pluck('product_id')->unique()->toArray();

        $finalProduct = Product::select('id', 'name', 'product_code', 'sku', 'product_type', 'brand_id', 'category_id')
            ->with([
                'category:id,name', 'brand:id,name', 'productVariations' => function ($query) {
                    $query->select('id', 'product_id', 'variation_template_value_id', 'default_purchase_price', 'default_selling_price', 'variation_sku')
                        ->with(['variationTemplateValue' => function ($query) {
                            $query->select('id', 'name', 'variation_template_id')
                                ->with(['variationTemplate:id,name']);
                        }]);
                }, 'uom' => function ($q) {
                    $q->with(['unit_category' => function ($q) {
                        $q->with('uomByCategory');
                    }]);
                }
            ])
            ->whereIn('id', $productIds);

        if ($filterProduct != 0) {
            $finalProduct->where('id', $filterProduct);
        }

        if ($filterCategory != 0) {
            $finalProduct->where('category_id', $filterCategory);
        }

        if ($filterBrand != 0) {
            $finalProduct->where('brand_id', $filterBrand);
        }

        $finalProduct = $finalProduct->get()->toArray();

        $result = [];

        foreach ($saleDetails as $detail) {
            $productId = $detail['product_id'];
            $variationId = $detail['variation_id'];
            $lotNo = $detail['lot_no'];

            foreach ($finalProduct as $product) {
                if ($product['id'] == $productId) {
                    $variations = $product['product_variations'];

                    foreach ($variations as $variation) {
                        if ($variation['id'] == $variationId) {
                            $sale = $sales->firstWhere('id', $detail['sales_id']);

                            $variationProduct = [
                                'id' => $product['id'],
                                'sale_data' => $sale,
                                'name' => $product['name'],
                                'sku' => $product['sku'],
                                'variation_id' => $variation['id'],
                                'product_type' => $product['product_type'],
                                'variation_sku' => $product['product_type'] == 'variable' ? $variation['variation_sku'] : "",
                                'category_id' => $product['category']['id'] ?? '',
                                'category_name' => $product['category']['name'] ?? '',
                                'brand_name' => $product['brand']['name'] ?? '',
                                'brand_id' => $product['brand']['id'] ?? '',
                                'quantity' => $detail['quantity'],
                                'uom_price' => $detail['uom_price'],
                                'subtotal' => $detail['subtotal'],
                                'per_item_discount' => $detail['per_item_discount'] ?? 0,
                                'subtotal_with_discount' => $detail['subtotal_with_discount'],
                                'uom_name' => $detail['uom']['name'],
                                'uom_short_name' => $detail['uom']['short_name'],
                            ];

                            $result[] = $variationProduct;
                        }
                    }
                }
            }
        }


        return response()->json($result, 200);
    }
    //End: Sale

    //Being: Purchase
    public function purchaseIndex()
    {
        $locations = businessLocation::select('id', 'name', 'parent_location_id')->get();
        $suppliers = Contact::where('type', 'Supplier')->get();

        return view('App.report.purchase.index', [
            'locations' => $locations,
            'suppliers' => $suppliers,
        ]);
    }
    public function purchaseFilter(Request $request)
    {
        $dateRange = $request->data['filter_date'];
        $dates = explode(' - ', $dateRange);

        $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[0])->startOfDay();
        $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[1])->endOfDay();

        $query = purchases::where('is_delete', 0)
            ->with('business_location_id', 'supplier')
            ->whereBetween('purchased_at', [$startDate, $endDate]);

        if ($request->data['filter_locations'] != 0) {
            $locationId = childLocationIDs($request->data['filter_locations']);
            $query->whereIn('business_location_id', $locationId);
        }

        if ($request->data['filter_customers'] != 0) {
            $query->where('contact_id', $request->data['filter_customers']);
        }

        if ($request->data['filter_status'] != 0) {
            $query->where('status', $request->data['filter_status']);
        }


        $result = $query->get()->toArray();

        return response()->json($result, 200);
    }

    public function purchaseDetailsIndex()
    {
        $locations = businessLocation::select('id', 'name', 'parent_location_id')->get();
        $customers = Contact::where('type', 'Customer')->get();

        $categories = Category::select('id', 'name', 'parent_id')->get();
        $brands = Brand::select('id', 'name',)->get();
        $products = Product::select('id', 'name')->get();

        return view('App.report.purchase.details', [
            'locations' => $locations,
            'customers' => $customers,

            'categories' => $categories,
            'brands' => $brands,
            'products' => $products,
        ]);
    }
    public function purchaseDetailsFilter(Request $request)
    {

        $filterProduct = $request->data['filter_product'];
        $filterCategory = $request->data['filter_category'];
        $filterBrand = $request->data['filter_brand'];
        $dateRange = $request->data['filter_date'];
        $dates = explode(' - ', $dateRange);

        $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[0])->startOfDay();
        $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[1])->endOfDay();

        $query = purchases::where('is_delete', 0)
            ->with([
                'purchase_details' => function ($purchase_detail) {
                    $purchase_detail->with('purchaseUom:id,name,short_name');
                },
                'supplier',
                'businessLocation'
            ])
            ->whereBetween('purchased_at', [$startDate, $endDate]);

        if ($request->data['filter_locations'] != 0) {
            $locationId = childLocationIDs($request->data['filter_locations']);
            $query->whereIn('business_location_id', $locationId);
        }

        $purchase = $query->get();

        $purchaseDetails = $query->get()->pluck('purchase_details')->flatten();

        $productIds = $purchaseDetails->pluck('product_id')->unique()->toArray();

        $finalProduct = Product::select('id', 'name', 'product_code', 'sku', 'product_type', 'brand_id', 'category_id')
            ->with([
                'category:id,name', 'brand:id,name', 'productVariations' => function ($query) {
                    $query->select('id', 'product_id', 'variation_template_value_id', 'default_purchase_price', 'default_selling_price', 'variation_sku')
                        ->with(['variationTemplateValue' => function ($query) {
                            $query->select('id', 'name', 'variation_template_id')
                                ->with(['variationTemplate:id,name']);
                        }]);
                }, 'uom' => function ($q) {
                    $q->with(['unit_category' => function ($q) {
                        $q->with('uomByCategory');
                    }]);
                }
            ])
            ->whereIn('id', $productIds);

        if ($filterProduct != 0) {
            $finalProduct->where('id', $filterProduct);
        }

        if ($filterCategory != 0) {
            $finalProduct->where('category_id', $filterCategory);
        }

        if ($filterBrand != 0) {
            $finalProduct->where('brand_id', $filterBrand);
        }

        $finalProduct = $finalProduct->get()->toArray();

        $result = [];

        foreach ($purchaseDetails as $detail) {
            $productId = $detail['product_id'];
            $variationId = $detail['variation_id'];

            foreach ($finalProduct as $product) {
                if ($product['id'] == $productId) {
                    $variations = $product['product_variations'];

                    foreach ($variations as $variation) {
                        if ($variation['id'] == $variationId) {
                            $purchase = $purchase->firstWhere('id', $detail['purchases_id']);

                            $variationProduct = [
                                'id' => $product['id'],
                                'purchase_data' => $purchase,
                                'location_name' => $purchase['businessLocation']['name'],
                                'supplier_name' => $purchase['supplier']['company_name'] ??  $purchase['supplier']['company_name']['first_name'] ?? '',
                                'name' => $product['name'],
                                'sku' => $product['sku'],
                                'variation_id' => $variation['id'],
                                'product_type' => $product['product_type'],
                                'variation_sku' => $product['product_type'] == 'variable' ? $variation['variation_sku'] : "",
                                'category_id' => $product['category']['id'] ?? '',
                                'category_name' => $product['category']['name'] ?? '',
                                'brand_name' => $product['brand']['name'] ?? '',
                                'brand_id' => $product['brand']['id'] ?? '',
                                'quantity' => $detail['quantity'],
                                'uom_price' => $detail['uom_price'],
                                'uom_name' => $detail['purchaseUom']['name'],
                                'uom_short_name' => $detail['purchaseUom']['short_name'],
                            ];

                            $result[] = $variationProduct;
                        }
                    }
                }
            }
        }


        return response()->json($result, 200);
    }
    //End: Purchase

    //Being: Qty Alert
    public function quantityAlert()
    {
        $locations = businessLocation::select('id', 'name')->get();
        $customers = Contact::where('type', 'Customer')->get();

        $categories = Category::select('id', 'name', 'parent_id')->get();
        $brands = Brand::select('id', 'name',)->get();
        $products = Product::select('id', 'name')->get();

        return view('App.report.stockAlert.quantity', [
            'locations' => $locations,
            'customers' => $customers,
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products,
        ]);
    }

    public function quantityAlertFilter(Request $request)
    {
        $filterProduct = $request->data['filter_product'];
        $filterCategory = $request->data['filter_category'];
        $filterBrand = $request->data['filter_brand'];
        $dateRange = $request->data['filter_date'];
        [$startDate, $endDate] = explode(' - ', $dateRange);
        $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', $startDate)->startOfDay();
        $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', $endDate)->endOfDay();

        $query = CurrentStockBalance::with(['uom', 'location:id,name'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($request->data['filter_locations'] != 0) {
            $query->where('business_location_id', $request->data['filter_locations']);
        }


        $currentStocks = $query->get();
        $productIds = $currentStocks->pluck('product_id')->unique()->toArray();

        $finalProductQuery = Product::select('id', 'name', 'product_code', 'sku', 'product_type', 'brand_id', 'category_id')
            ->with([
                'category:id,name',
                'brand:id,name',
                'productVariations' => function ($query) {
                    $query->select('id', 'product_id', 'variation_template_value_id', 'default_purchase_price', 'default_selling_price', 'variation_sku', 'alert_quantity')
                        ->with(['variationTemplateValue.variationTemplate:id,name']);
                },
                'uom'
            ])
            ->whereIn('id', $productIds);

        if ($filterProduct != 0) {
            $finalProductQuery->where('id', $filterProduct);
        }

        if ($filterCategory != 0) {
            $finalProductQuery->where('category_id', $filterCategory);
        }

        if ($filterBrand != 0) {
            $finalProductQuery->where('brand_id', $filterBrand);
        }

        $finalProduct = $finalProductQuery->get()->toArray();

        $mergedStocks = [];
        foreach ($currentStocks as $currentStock) {
            $productId = $currentStock['product_id'];
            $variationId = $currentStock['variation_id'];
            $locationId = $currentStock['location']['id'];

            $key = $productId . '_' . $variationId . '_' . $locationId;

            if (!isset($mergedStocks[$key])) {
                $mergedStocks[$key] = $currentStock;
            } else {
                $mergedStocks[$key]['ref_uom_quantity'] += $currentStock['ref_uom_quantity'];
                $mergedStocks[$key]['current_quantity'] += $currentStock['current_quantity'];
            }
        }

        $result = [];
        foreach ($mergedStocks as $currentStock) {
            foreach ($finalProduct as $product) {
                if ($product['id'] == $currentStock['product_id']) {
                    $variations = $product['product_variations'];


                    foreach ($variations as $variation) {
                        $alertQty = $variation['alert_quantity'];

                        if ($currentStock['current_quantity'] <= $alertQty) {
                            if ($variation['id'] == $currentStock['variation_id']) {
                                $variationProduct = [
                                    'id' => $product['id'],
                                    'name' => $product['name'],
                                    'sku' => $product['sku'],
                                    'product_type' => $product['product_type'],
                                    'variation_sku' => $product['product_type'] == 'variable' ? $variation['variation_sku'] : "",
                                    'variation_template_name' => $variation['variation_template_value']['variation_template']['name'] ?? '',
                                    'variation_value_name' => $variation['variation_template_value']['name'] ?? '',
                                    'location_name' => businessLocationName($currentStock->location),
                                    'category_name' => $product['category']['name'] ?? '',
                                    'brand_name' => $product['brand']['name'] ?? '',
                                    'ref_uom_name' => $currentStock['uom']['name'],
                                    'ref_uom_short_name' => $currentStock['uom']['short_name'],
                                    'purchase_qty' => $currentStock['ref_uom_quantity'],
                                    'current_qty' => $currentStock['current_quantity'],
                                    'alert_qty' => $alertQty,
                                ];
                                $result[] = $variationProduct;
                            }
                        }
                    }
                }
            }
        }

        return response()->json($result, 200);
    }

    //End: Qty Alert

    //Being: Expire Alert
    public function expireAlert()
    {

//        $locations = businessLocation::select('id', 'name')->get();
        $customers = Contact::where('type', 'Customer')->get();

        $categories = Category::select('id', 'name', 'parent_id')->get();
        $brands = Brand::select('id', 'name',)->get();
        $products = Product::select('id', 'name')->get();
        $locations = $this->locations->getforTx();

        return view('App.report.stockAlert.expire', [
            'locations' => $locations,
            'customers' => $customers,
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products,
        ]);
    }

    public function expireAlertFilter(Request $request)
    {


        $filterProduct = $request->data['filter_product'];
        $filterCategory = $request->data['filter_category'];
        $filterBrand = $request->data['filter_brand'];
        $filterExpire = $request->data['filter_expire'];

        $currentDate = now();

        $expire_alert_day = getSettingsValue('expire_alert_day');

        $dateInterval = null;
        $expiredCondition = false;
        switch ($filterExpire) {
            case 'expire_week':
                $dateInterval = now()->addWeek()->format('Y-m-d');
                break;
            case 'expire_15_days':
                $dateInterval = now()->addDays(15)->format('Y-m-d');
                break;
            case 'expire_month':
                $dateInterval = now()->addMonth()->format('Y-m-d');
                break;
            case 'expire_3_months':
                $dateInterval = now()->addMonth(3)->format('Y-m-d');
                break;
            case 'expire_6_months':
                $dateInterval = now()->addMonth(6)->format('Y-m-d');
                break;
            case 'expire_year':
                $dateInterval = now()->addYear()->format('Y-m-d');
                break;
            case 'expired':
                $expiredCondition = true;
                break;
            case '0':
                $dateInterval = now()->addDays($expire_alert_day)->format('Y-m-d');
                break;
            default:

                break;
        }


        $query = CurrentStockBalance::with(['uom', 'location:id,name'])->whereNotNull('expired_date')
            ->where('current_quantity', '>', 0);

        if ($dateInterval) {
            $query->where('expired_date', '<=', $dateInterval);
        }

        if ($expiredCondition) {
            $query->where('expired_date', '<=', $currentDate);
        }



        if ($request->data['filter_locations'] != 0) {
            $query->where('business_location_id', $request->data['filter_locations']);
        }


        $currentStocks = $query->get();
        $productIds = $currentStocks->pluck('product_id')->unique()->toArray();

        $finalProductQuery = Product::select('id', 'name', 'product_code', 'sku', 'product_type', 'brand_id', 'category_id')
            ->with([
                'category:id,name',
                'brand:id,name',
                'productVariations' => function ($query) {
                    $query->select('id', 'product_id', 'variation_template_value_id', 'default_purchase_price', 'default_selling_price', 'variation_sku')
                        ->with(['variationTemplateValue.variationTemplate:id,name']);
                },
                'uom'
            ])
            ->whereIn('id', $productIds);

        if ($filterProduct != 0) {
            $finalProductQuery->where('id', $filterProduct);
        }

        if ($filterCategory != 0) {
            $finalProductQuery->where('category_id', $filterCategory);
        }

        if ($filterBrand != 0) {
            $finalProductQuery->where('brand_id', $filterBrand);
        }

        $finalProduct = $finalProductQuery->get()->toArray();

//        $mergedStocks = [];
//        foreach ($currentStocks as $currentStock) {
//            $productId = $currentStock['product_id'];
//            $variationId = $currentStock['variation_id'];
//            $locationId = $currentStock['location']['id'];
//
//            $key = $productId . '_' . $variationId . '_' . $locationId;
//
//            if (!isset($mergedStocks[$key])) {
//                $mergedStocks[$key] = $currentStock;
//            } else {
//                $mergedStocks[$key]['ref_uom_quantity'] += $currentStock['ref_uom_quantity'];
//                $mergedStocks[$key]['current_quantity'] += $currentStock['current_quantity'];
//            }
//        }

        $result = [];
        foreach ($currentStocks as $currentStock) {
            foreach ($finalProduct as $product) {
                if ($product['id'] == $currentStock['product_id']) {
                    $variations = $product['product_variations'];

                    foreach ($variations as $variation) {
                        if ($variation['id'] == $currentStock['variation_id']) {
                            $variationProduct = [
                                'id' => $product['id'],
                                'csb_id' => $currentStock['id'],
                                'name' => $product['name'],
                                'sku' => $product['sku'],
                                'product_type' => $product['product_type'],
                                'variation_sku' => $product['product_type'] == 'variable' ? $variation['variation_sku'] : "",
                                'variation_template_name' => $variation['variation_template_value']['variation_template']['name'] ?? '',
                                'variation_value_name' => $variation['variation_template_value']['name'] ?? '',
                                'location_name' => businessLocationName($currentStock->location),
                                'category_name' => $product['category']['name'] ?? '',
                                'brand_name' => $product['brand']['name'] ?? '',
                                'ref_uom_name' => $currentStock['uom']['name'],
                                'ref_uom_short_name' => $currentStock['uom']['short_name'],
                                'purchase_qty' => $currentStock['ref_uom_quantity'],
                                'current_qty' => number_format($currentStock['current_quantity'], 2,'.',''),
                                'expired_date' => $currentStock['expired_date'],
                            ];
                            $result[] = $variationProduct;
                        }
                    }
                }
            }
        }

        return response()->json($result, 200);
    }

    function removeExpireItem(Request $request, StockAdjustmentRepository $stockAdjustmentRepository)
    {
        try {
            DB::beginTransaction();
            $currentStockBalances = CurrentStockBalance::whereIn('id', $request->ids)
                ->get();

            $currentLocation = null;
            $createdStockAdjustmentId = null;

            foreach($currentStockBalances as $balance){

                $location = $balance['business_location_id'];
                $toRemoveQty = $balance['current_quantity'];
                $subtotal = $balance['ref_uom_price'] * $toRemoveQty;

                // Check if the location has changed
                if ($location != $currentLocation) {
                    $preparedAdjustmentData = [
                        'business_location' => $location,
                        'adjustment_voucher_no' => stockAdjustmentVoucherNo(),
                        'condition' => 'expire',
                        'status' => 'completed',
                        'increase_subtotal' => 0,
                        'decrease_subtotal' => $subtotal,
                        'adjustmented_at' => now(),
                        'created_at' => now(),
                        'created_by' =>  Auth::id(),
                    ];

                    $createdStockAdjustment = $stockAdjustmentRepository->create($preparedAdjustmentData);
                    $createdStockAdjustmentId = $createdStockAdjustment->id;
                    $currentLocation = $location;
                }else{
                    $stockAdjustmentRepository->query()->where('id',$createdStockAdjustmentId)
                        ->increment('decrease_subtotal', $subtotal);
                }

                $preparedAdjustmentDetailData = [
                    'product_id' => $balance['product_id'],
                    'variation_id' => $balance['variation_id'],
                    'uom_id' => $balance['ref_uom_id'],
                    'adjustment_id' => $createdStockAdjustmentId,
                    'adjustment_type' => 'decrease',
                    'uom_price' => $balance['ref_uom_price'],
                    'subtotal' => $subtotal,
                    'balance_quantity' => $toRemoveQty,
                    'gnd_quantity' => 0,
                    'adj_quantity' => $toRemoveQty,
                    'created_at' => now(),
                    'created_by' =>  Auth::id(),
                ];

                $createdStockAdjustmentDetail = $stockAdjustmentRepository->createDetail($preparedAdjustmentDetailData);

                stock_history::create([
                    'business_location_id' => $location,
                    'product_id' => $balance['product_id'],
                    'variation_id' => $balance['variation_id'],
                    'lot_serial_numbers' => $balance['lot_serial_no'],
                    'expired_date' => $balance['expired_date'],
                    'transaction_type' => 'adjustment',
                    'transaction_details_id' => $createdStockAdjustmentDetail->id,
                    'increase_qty' => 0,
                    'decrease_qty' => $toRemoveQty,
                    'ref_uom_id' => $balance['ref_uom_id'],
                    'balance_quantity' => 0,
                    'created_at' => now(),
                ]);

                lotSerialDetails::create([
                    'transaction_type' => 'adjustment',
                    'transaction_detail_id' => $createdStockAdjustmentDetail->id,
                    'current_stock_balance_id' => $balance['id'],
                    'lot_serial_numbers' => $balance['lot_serial_no'],
                    'expired_date' => $balance['expired_date'],
                    'uom_id' => $balance['ref_uom_id'],
                    'uom_quantity' => $toRemoveQty,
                    'ref_uom_quantity' => $toRemoveQty,
                ]);


                CurrentStockBalance::where('id', $balance['id'])
                    ->update(['current_quantity' => 0]);
            }
            DB::commit();
            return response()->json(['message' => 'success'], 200);
        }catch(Exception $exception){
            DB::rollBack();
            return response()->json(['message' => 'error', 'data' => $exception], 200);
        }

    }

    function transferExpireItem(Request $request)
    {
        try {
            DB::beginTransaction();
            $currentStockBalances = CurrentStockBalance::whereIn('id', $request->ids)
                ->get();

            $currentLocation = null;
            $createdTransferId = null;

            foreach($currentStockBalances as $balance){

                $location = $balance['business_location_id'];
                $toRemoveQty = $balance['current_quantity'];

                if ($location != $request->location){
                    // Check if the location has changed
                    if ($location != $currentLocation) {

                        $stock_transfer = StockTransfer::create([
                            'transfer_voucher_no' => stockTransferVoucher(),
                            'from_location' => $location,
                            'to_location' => $request->location,
                            'transfered_at' => now(),
                            'transfered_person' => Auth::id(),
                            'status' => 'completed',
                            'received_person' => Auth::id(),
                            'created_at' => now(),
                            'created_by' => Auth::id(),
                        ]);

                        $createdTransferId = $stock_transfer->id;
                        $currentLocation = $location;
                    }


                    $transferDetail = StockTransferDetail::create([
                        'transfer_id' => $createdTransferId,
                        'product_id' => $balance['product_id'],
                        'variation_id' => $balance['variation_id'],
                        'uom_id' => $balance['ref_uom_id'],
                        'quantity' => $toRemoveQty,
                        'uom_price' => $balance['ref_uom_price'],
                        'subtotal' => $balance['ref_uom_price'] * $toRemoveQty,
                        'per_item_expense' => 0,
                        'expense' => 0,
                        'subtotal_with_expense' => 0,
                        'per_ref_uom_price' => $balance['ref_uom_price'],
                        'ref_uom_id' => $balance['ref_uom_id'],
                        'currency_id' => getSettingsValue('currency_id'),
                        'created_at' => now(),
                        'created_by' => Auth::id(),
                    ]);

                    stock_history::create([
                        'business_location_id' => $location,
                        'product_id' => $balance['product_id'],
                        'variation_id' => $balance['variation_id'],
                        'lot_serial_numbers' => $balance['lot_serial_no'],
                        'expired_date' => $balance['expired_date'],
                        'transaction_type' => 'transfer',
                        'transaction_details_id' => $transferDetail->id,
                        'increase_qty' => 0,
                        'decrease_qty' => $toRemoveQty,
                        'ref_uom_id' => $balance['ref_uom_id'],
                        'balance_quantity' => 0,
                        'created_at' => now(),
                    ]);

                    lotSerialDetails::create([
                        'transaction_type' => 'transfer',
                        'transaction_detail_id' => $transferDetail->id,
                        'current_stock_balance_id' => $balance['id'],
                        'lot_serial_numbers' => $balance['lot_serial_no'],
                        'expired_date' => $balance['expired_date'],
                        'uom_id' => $balance['ref_uom_id'],
                        'uom_quantity' => $toRemoveQty,
                        'ref_uom_quantity' => $toRemoveQty,
                    ]);


                    CurrentStockBalance::create([
                        'business_location_id' => $request->location,
                        'product_id' => $balance['product_id'],
                        'variation_id' => $balance['variation_id'],
                        'transaction_type' => 'transfer',
                        'transaction_detail_id' => $transferDetail->id,
                        'batch_no' => $balance['batch_no'],
                        'lot_serial_no' => $balance['lot_serial_no'],
                        'expired_date' => $balance['expired_date'],
                        'ref_uom_id' => $balance['ref_uom_id'],
                        'ref_uom_quantity' => $balance['current_quantity'],
                        'ref_uom_price' => $balance['ref_uom_price'],
                        'current_quantity' => $balance['current_quantity'],
                        'created_at' => now(),
                        'lot_serial_type' => $balance['lot_serial_type'],
                    ]);

                    CurrentStockBalance::where('id', $balance['id'])
                        ->update(['current_quantity' => 0]);
                }
            }

            DB::commit();
            return response()->json(['message' => 'success'], 200);
        }catch(Exception $exception){
            DB::rollBack();
            return response()->json(['message' => 'error', 'data' => $exception], 200);
        }
    }
    //End: Expire ALert


    public function stock_transfer_index()
    {
        $locations = businessLocation::select('id', 'name')->get();
        $stocks_person = BusinessUser::select('id', 'username')->get();


        return view('App.report.inventory.transfer.index', [
            'locations' => $locations,
            'stocksperson' => $stocks_person,
        ]);
    }

    public function transferFilter(Request $request)
    {
        $dateRange = $request->data['filter_date'];
        $dates = explode(' - ', $dateRange);

        $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[0])->startOfDay();
        $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[1])->endOfDay();


        $query = StockTransfer::where('is_delete', 0)
            ->with(['businessLocationFrom:id,name', 'businessLocationTo:id,name', 'stocktransferPerson:id,username', 'stockreceivePerson:id,username', 'created_by:id,username'])
            ->whereBetween('transfered_at', [$startDate, $endDate]);;

        if ($request->data['filter_status'] != 0) {
            $status = '';

            switch ($request->data['filter_status']) {
                case 1:
                    $status = 'pending';
                    break;
                case 2:
                    $status = 'completed';
                    break;
            }

            $query->where('status', $status);
        }

        if ($request->data['filter_locations_from'] != 0) {
            $query->where('from_location', $request->data['filter_locations_from']);
        }

        if ($request->data['filter_locations_to'] != 0) {
            $query->where('to_location', $request->data['filter_locations_to']);
        }

        if ($request->data['filter_stocktransferperson'] != 0) {
            $query->where('transfered_person', $request->data['filter_stocktransferperson']);
        }

        if ($request->data['filter_stockreceiveperson'] != 0) {
            $query->where('received_person', $request->data['filter_stockreceiveperson']);
        }

        $stocktransfers = $query->get();


        return response()->json($stocktransfers, 200);
    }

    public function transfer_details_index()
    {
        $locations = businessLocation::select('id', 'name')->get();
        $categories = Category::select('id', 'name', 'parent_id')->get();
        $brands = Brand::select('id', 'name',)->get();
        $products = Product::select('id', 'name')->get();

        return view('App.report.inventory.transfer.details', [
            'locations' => $locations,
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products
        ]);
    }

    public function transferDetailsFilter(Request $request)
    {

        $filterProduct = $request->data['filter_product'];
        $filterCategory = $request->data['filter_category'];
        $filterBrand = $request->data['filter_brand'];
        $dateRange = $request->data['filter_date'];
        $dates = explode(' - ', $dateRange);

        $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[0])->startOfDay();
        $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[1])->endOfDay();

        $query = StockTransfer::where('is_delete', 0)
            ->with('stockTransferDetails')
            ->whereBetween('transfered_at', [$startDate, $endDate]);

        if ($request->data['filter_locations_from'] != 0) {
            $query->where('from_location', $request->data['filter_locations_from']);
        }

        if ($request->data['filter_locations_to'] != 0) {
            $query->where('to_location', $request->data['filter_locations_to']);
        }

        $stockTransfer = $query->get();
        $stockDetails = $query->get()->pluck('stockTransferDetails')->flatten();
        $productIds = $stockDetails->pluck('product_id')->unique()->toArray();



        $finalProduct = Product::select('id', 'name', 'product_code', 'sku', 'product_type', 'brand_id', 'category_id')
            ->with([
                'category:id,name', 'brand:id,name', 'productVariations' => function ($query) {
                    $query->select('id', 'product_id', 'variation_template_value_id', 'default_purchase_price', 'default_selling_price', 'variation_sku')
                        ->with(['variationTemplateValue' => function ($query) {
                            $query->select('id', 'name', 'variation_template_id')
                                ->with(['variationTemplate:id,name']);
                        }]);
                }, 'uom' => function ($q) {
                    $q->with(['unit_category' => function ($q) {
                        $q->with('uomByCategory');
                    }]);
                }
            ])
            ->whereIn('id', $productIds);

        if ($filterProduct != 0) {
            $finalProduct->where('id', $filterProduct);
        }

        if ($filterCategory != 0) {
            $finalProduct->where('category_id', $filterCategory);
        }

        if ($filterBrand != 0) {
            $finalProduct->where('brand_id', $filterBrand);
        }

        $finalProduct = $finalProduct->get()->toArray();

        $result = [];

        foreach ($stockDetails as $stockDetail) {
            $productId = $stockDetail['product_id'];
            $variationId = $stockDetail['variation_id'];
            $lotNo = $stockDetail['lot_no'];
            $transferDate = null;
            $voucherNo = null;
            foreach ($stockTransfer as $transfer){
                 if ($transfer['id'] == $stockDetail['transfer_id']){
                    $transferDate =  $transfer['transfered_at'];
                    $voucherNo = $transfer['transfer_voucher_no'];
                 }
            }


            foreach ($finalProduct as $product) {
                if ($product['id'] == $productId) {
                    $variations = $product['product_variations'];

                    foreach ($variations as $variation) {
                        if ($variation['id'] == $variationId) {

                            // $smallest_qty = UomHelper::smallestQty($stockDetail['uomset_id'],$stockDetail['unit_id'],$stockDetail['quantity']);
                            // $smallest_unit_name =Unit::find(UomHelper::smallestUnitId($stockDetail['uomset_id']))->name;
                            // $smallest_price = UomHelper::smallestPrice($stockDetail['uomset_id'],$stockDetail['unit_id'],$stockDetail['quantity'],$stockDetail['purchase_price']);
                            $variationProduct = [
                                'id' => $product['id'],
                                'voucher_no' => $voucherNo,
                                'name' => $product['name'],
                                'sku' => $product['sku'],
                                'variation_id' => $variation['id'],
                                'product_type' => $product['product_type'],
                                'variation_sku' => $product['product_type'] == 'variable' ? $variation['variation_sku'] : "",
                                'category_id' => $product['category']['id'] ?? '',
                                'category_name' => $product['category']['name'] ?? '',
                                'brand_name' => $product['brand']['name'] ?? '',
                                'brand_id' => $product['brand']['id'] ?? '',
                                'transfered_at' => $transferDate,
                                // 'unit_name' => $stockDetail['unit']['name'],
                                // 'uom_name' => $stockDetail['uomset']['uomset_name'],
                                'uom_short_name' => $stockDetail['uom']['short_name'],
                                'uom_name' => $stockDetail['uom']['name'],
                                'purchase_price' => number_format($stockDetail['purchase_price'], 2),
                                // 'smallest_purchase_price' => $smallest_price,
                                'transfer_quantity' => number_format($stockDetail['quantity'], 2),
                                'variation_template_name' => $variation['variation_template_value']['variation_template']['name'] ?? '',
                                'variation_value_name' => $variation['variation_template_value']['name'] ?? '',
                                'remark' => $stockDetail['remark'] ?? '-',
                                // 'samllest_stock_qty' => number_format($smallest_qty, 2),
                                // 'smallest_unit_name' =>  $smallest_unit_name,
                            ];

                            $result[] = $variationProduct;
                        }
                    }
                }
            }
        }

        return response()->json($result, 200);
    }

    public function adjustmentIndex()
    {
        $locations = businessLocation::select('id', 'name')->get();
        $stocks_person = BusinessUser::select('id', 'username')->get();


        return view('App.report.inventory.adjustment.index', [
            'locations' => $locations,
            'stocksperson' => $stocks_person,
        ]);
    }

    public function adjustmentFilter(Request $request)
    {
        $adjustmentSummaryFilterData = reportServices::adjustmentSummary($request->data);

        return response()->json($adjustmentSummaryFilterData, 200);
    }

    public function adjustmentDetails()
    {
        $locations = businessLocation::select('id', 'name')->get();
        $stocks_person = BusinessUser::select('id', 'username')->get();

        return view('App.report.inventory.adjustment.details', [
            'locations' => $locations,
            'stocksperson' => $stocks_person,
        ]);
    }

    public function adjustmentDetailsFilter(Request $request)
    {

        $adjustmentDetailsFilterData = reportServices::adjustmentDetails($request->data);
        return response()->json($adjustmentDetailsFilterData);
    }


    public function currentStockBalanceIndex($nav_type)
    {
        $locations = businessLocation::select('id', 'name', 'parent_location_id')->get();
        $categories = Category::select('id', 'name', 'parent_id')->get();
        $brands = Brand::select('id', 'name',)->get();
        $products = Product::select('id', 'name')->get();
        $enableLotSerial = businessSettings::find(1)->lot_control;

        $startDate = '2023-10-01'; // Example start date
        $endDate = '2023-10-31'; // Example end date

        $query = CurrentStockBalance::with(['uom', 'location:id,name,parent_location_id'])
            ->select('current_stock_balance.*', 'product_packaging_transactions.*', 'product_packagings.*', 'current_stock_balance.created_at as csb_created_at')
            ->leftJoin('product_packaging_transactions', function ($join) {
                $join->on('current_stock_balance.transaction_type', '=', 'product_packaging_transactions.transaction_type')
                    ->on('current_stock_balance.transaction_detail_id', '=', 'product_packaging_transactions.transaction_details_id');
            })
            ->leftJoin('product_packagings', 'product_packaging_transactions.product_packaging_id', '=', 'product_packagings.id')
            ->whereBetween('current_stock_balance.created_at', [$startDate, $endDate]);

        $results = $query->get();


//                return $results;

        return view('App.report.inventory.stock.currentBalance', [
            'nav_type' => $nav_type,
            'locations' => $locations,
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products,
            'enableLotSerial' => $enableLotSerial,
        ]);
    }


    public function currentStockBalanceFilter(Request $request)
    {

        $filterView = $request->data['filter_view'];
        $filterProduct = $request->data['filter_product'];
        $filterCategory = $request->data['filter_category'];
        $filterBrand = $request->data['filter_brand'];
        $dateRange = $request->data['filter_date'];
        $filterType = $request->data['filter_type'];
        $filterStockStatus = $request->data['filter_stock_status'];

        if ($dateRange) {
            $dates = explode(' - ', $dateRange);

            $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[0])->startOfDay();
            $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[1])->endOfDay();
        }

        if ($filterType == 2) {
            $query = CurrentStockBalance::with(['uom', 'packaging_uom', 'location:id,name,parent_location_id',     'lot_serial_details' => function ($query) {
                $query->where('stock_status', 'reserve');
            }])
                ->select(
                    'current_stock_balance.*',
                    'product_packaging_transactions.*',
                    'product_packagings.*',
                    'current_stock_balance.created_at as csb_created_at',
                    'current_stock_balance.product_id as csb_product_id',
                    'current_stock_balance.variation_id as csb_variation_id',
                )
                ->where('current_quantity', '>', 0)
                ->leftJoin('product_packaging_transactions', function ($join) {
                    $join->on('current_stock_balance.transaction_type', '=', 'product_packaging_transactions.transaction_type')
                        ->on('current_stock_balance.transaction_detail_id', '=', 'product_packaging_transactions.transaction_details_id');
                })
                ->leftJoin('product_packagings', 'product_packaging_transactions.product_packaging_id', '=', 'product_packagings.id');

            if ($dateRange) {
                $query->whereBetween('current_stock_balance.created_at', [$startDate, $endDate]);
            }
        } else {
            $query = CurrentStockBalance::with(['uom', 'location:id,name,parent_location_id'])
                ->where('current_quantity', '>', 0);

            if ($dateRange) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }

        if ($request->data['filter_locations'] != 0) {
            $locationId = childLocationIDs($request->data['filter_locations']);
            $query->whereIn('business_location_id', $locationId);
        }

        $currentStocks = $query->get();
        //        return response()->json($currentStocks, 200);
        $productIds = $filterType == 2 ? $currentStocks->pluck('csb_product_id')->unique()->toArray() : $currentStocks->pluck('product_id')->unique()->toArray();

        $finalProduct = Product::select('id', 'name', 'product_code', 'sku', 'product_type', 'brand_id', 'category_id', 'has_variation')
            ->with(['category:id,name', 'brand:id,name', 'productVariations' => function ($query) {
                $query->select('id', 'product_id', 'variation_template_value_id', 'default_purchase_price', 'default_selling_price', 'variation_sku')
                    ->with(['variation_values.variation_template_value','packaging', 'variationTemplateValue' => function ($query) {
                        $query->select('id', 'name', 'variation_template_id')
                            ->with(['variationTemplate:id,name']);
                    }]);
            }], 'uom')
            ->whereIn('id', $productIds);

        if ($filterProduct != 0) {
            $finalProduct->where('id', $filterProduct);
        }

        if ($filterCategory != 0) {
            $finalProduct->where('category_id', $filterCategory);
        }

        if ($filterBrand != 0) {
            $finalProduct->where('brand_id', $filterBrand);
        }

        $finalProduct = $finalProduct->get()->toArray();
        if ($filterView == 1) {

            foreach ($currentStocks as $currentStock) {
                $product_package_id = $currentStock['product_packaging_id'];
                $batchNo = $currentStock['batch_no'];
                $variationId = $currentStock['variation_id'];
                $locationId = $currentStock['location']['id'];
                $currentQty = $currentStock['current_quantity'];
                $refQty =  $currentStock['ref_uom_quantity'];
                $package_qty = $currentStock['quantity'] ?? 1;


                $package_currentQty = $currentQty / $package_qty;
                $package_refQty = $refQty / $package_qty;

                if ($filterType == 2) {
                    $new_current_qty = $package_currentQty;
                    $new_purchase_qty = $package_refQty;
                } else {
                    $new_current_qty = $currentQty;
                    $new_purchase_qty = $refQty;
                }


                $key = $filterType == 2 ? $batchNo . '_' . $variationId . '_' . $locationId . '_' . $product_package_id : $batchNo . '_' . $variationId . '_' . $locationId;


                if (!isset($mergedStocks[$key])) {
                    $mergedStocks[$key] = $currentStock;
                    $mergedStocks[$key]['batch_number'] =  $batchNo;
                    $mergedStocks[$key]['ref_uom_quantity'] = $new_purchase_qty;
                    $mergedStocks[$key]['current_quantity'] = $new_current_qty;
                } else {

                    $mergedStocks[$key]['batch_number'] =  $batchNo . '(merged)';
                    $mergedStocks[$key]['ref_uom_quantity'] += $new_purchase_qty;
                    $mergedStocks[$key]['current_quantity'] += $new_current_qty;
                }
            }

            $mergeAllBatchStocks = $mergedStocks;
        } elseif ($filterView == 2) {

            foreach ($currentStocks as $currentStock) {
                $product_package_id = $currentStock['product_packaging_id'];
                $batchNo = $currentStock['batch_no'];
                $transactionID = $currentStock['transaction_detail_id'];
                $variationId = $currentStock['variation_id'];
                $locationId = $currentStock['location']['id'];
                $currentQty = $currentStock['current_quantity'];
                $refQty =  $currentStock['ref_uom_quantity'];
                $package_qty = $currentStock['quantity'] ?? 1;


                $package_currentQty = $currentQty / $package_qty;
                $package_refQty = $refQty / $package_qty;

                if ($filterType == 2) {
                    $new_current_qty = $package_currentQty;
                    $new_purchase_qty = $package_refQty;
                } else {
                    $new_current_qty = $currentQty;
                    $new_purchase_qty = $refQty;
                }


                $key = $filterType == 2 ? $batchNo . '_' . $variationId . '_' . $locationId . '-' . $transactionID . '_' . $product_package_id : $batchNo . '_' . $variationId . '_' . $locationId . '-' . $transactionID;

                if (!isset($mergedStocks[$key])) {
                    $mergedStocks[$key] = $currentStock;
                    $mergedStocks[$key]['batch_number'] =  $currentStock['batch_no'];
                    $mergedStocks[$key]['ref_uom_quantity'] = $new_purchase_qty;
                    $mergedStocks[$key]['current_quantity'] =   $new_current_qty;
                } else {

                    $mergedStocks[$key]['batch_number'] =  $currentStock['batch_no'] . '(merged)';
                    $mergedStocks[$key]['ref_uom_quantity'] += $new_purchase_qty;
                    $mergedStocks[$key]['current_quantity'] +=   $new_current_qty;
                }
            }

            $mergeAllBatchStocks = $mergedStocks;
        } elseif ($filterView == 3) {
            $mergeAllBatchStocks = $currentStocks;
        } else {
            if ($currentStocks->count() > 0) {
                foreach ($currentStocks as $currentStock) {

                    $product_package_id = $currentStock['product_packaging_id'];
                    $variationId = $filterType == 2 ? $currentStock['csb_variation_id'] : $currentStock['variation_id'];
                    $locationId = $currentStock['location']['id'];
                    $currentQty = $currentStock['current_quantity'];
                    $refQty =  $currentStock['ref_uom_quantity'];
                    $package_qty = $currentStock['quantity'] ?? 1;

                    $package_currentQty = $currentQty / $package_qty;
                    $package_refQty = $refQty / $package_qty;

                    if ($filterType == 2) {
                        $new_current_qty = $package_currentQty;
                        $new_purchase_qty = $package_refQty;
                    } else {
                        $new_current_qty = $currentQty;
                        $new_purchase_qty = $refQty;
                    }



                    $key = $filterType == 2 ? $variationId . '_' . $product_package_id : $variationId;



                    if (!isset($mergeAllBatchs[$key])) {
                        $mergeAllBatchs[$key] = $currentStock;
                        $mergeAllBatchs[$key]['batch_number'] =  '-';
                        $mergeAllBatchs[$key]['ref_uom_quantity'] = $new_purchase_qty;
                        $mergeAllBatchs[$key]['current_quantity'] = $new_current_qty;
                    } else {

                        $mergeAllBatchs[$key]['batch_number'] =  '-';
                        $mergeAllBatchs[$key]['ref_uom_quantity'] += $new_purchase_qty;
                        $mergeAllBatchs[$key]['current_quantity'] += $new_current_qty;
                    }
                }
                $mergeAllBatchStocks = $mergeAllBatchs;
            } else {
                $mergeAllBatchStocks = $currentStocks;
            }
        }
        //        return response()->json($mergeAllBatchStocks, 200);
        $lotCounts = [];
        $result = [];
        foreach ($mergeAllBatchStocks as $currentStock) {
            $productId = $filterType == 2 ? $currentStock['csb_product_id'] : $currentStock['product_id'];
            $variationId = $filterType == 2 ? $currentStock['csb_variation_id'] : $currentStock['variation_id'];
            $locationId = $currentStock['location']['id'];
            $packageName = $currentStock['packaging_name'] ?? $currentStock['uom']['short_name'];

            $key = $productId . '_' . $variationId . '_' . $locationId;

            // Check if the key exists in the lotCounts array
            if (!isset($lotCounts[$key])) {
                $lotCounts[$key] = 1;
            } else {
                $lotCounts[$key]++;
            }

            $totalRefUOMQuantity = 0;

            if ($filterStockStatus == 2) {
                foreach ($currentStock['lot_serial_details'] as $lotSerialDetail) {
                    $totalRefUOMQuantity += $lotSerialDetail['ref_uom_quantity'];
                }
            }
            if($filterStockStatus == 1){
                $totalRefUOMQuantity = 0;
            }



            foreach ($finalProduct as $product) {
                if ($product['id'] == $productId) {
                    $variations = $product['product_variations'];

                    foreach ($variations as $variation) {
                        if ($variation['id'] == $variationId) {


                                $value_names = '';
                                foreach ($variation['variation_values'] as $value) {
                                    $value_names .= $value['variation_template_value']['name'] . '-';
                                }
                                $value_names = rtrim($value_names, '-');



                            $variationProduct = [
                                'id' => $product['id'],
                                'product_packaging_id' => $currentStock['product_packaging_id'] ?? 0,
                                'name' => $product['name'],
                                'sku' => $product['sku'],
                                'variation_full_name' => $value_names,
                                'variation_id' => $variation['id'],
                                'product_type' => $product['product_type'],
                                'variation_sku' => $product['has_variation'] == 'variable' ? $variation['variation_sku'] : "",
                                'variation_template_name' => $variation['variation_template_value']['variation_template']['name'] ?? '',
                                'variation_value_name' => $variation['variation_template_value']['name'] ?? '',
                                'batch_no' => $filterView == 3 ? $currentStock['batch_no'] : $currentStock['batch_number'],
                                'lot_no' => $filterView == 3 ? $currentStock['lot_serial_no'] : '-',
                                'location_name' => $request->data['filter_locations'] == 0 ? 'All Location' : businessLocationName($currentStock->location),
                                'category_id' => $product['category']['id'] ?? '',
                                'category_name' => $product['category']['name'] ?? '',
                                'brand_name' => $product['brand']['name'] ?? '',
                                'brand_id' => $product['brand']['id'] ?? '',
                                'ref_uom_name' => $currentStock['uom']['name'],
                                'ref_uom_short_name' => $currentStock['uom']['short_name'],
                                'purchase_qty' => $currentStock['ref_uom_quantity'],
                                'current_qty' => $currentStock['current_quantity'] + $totalRefUOMQuantity,
                                'total_qty' => $totalRefUOMQuantity,
//                                'lot_info' => $currentStock['lot_serial_details'],
                                'package_name' =>  $packageName,
                                'package_qty' => $currentStock['quantity'],
                                'packaging_uom_short_name' => $currentStock['packaging_uom']['short_name'] ?? '',
                                'expire_date' => $currentStock['expired_date'] ?? '',
                            ];
                            $result[] = $variationProduct;
                        }
                    }
                }
            }
        }

        return response()->json($result, 200);
    }
    //End: Inventory Report


    public function proftLoss()
    {
        return view('App.report.profitLoss.index');
    }
    public function profitLossData(Request $request)
    {
        $filterData = isFilter($request->toArray());
        $netProfit = price(reportServices::netProfit($filterData));
        if (!$filterData) {
            $tlOsAmount = totalOSTransactionAmount($filterData);
        } else {
            $tlOsAmount =  osWithFromCs($filterData);
        }
        // $tlOsAmount=  osWithFromCs($filterData)+totalOSTransactionAmount($filterData);
        $tlPAmount = totalPurchaseAmount($filterData);
        $tlExAmount = totalExpenseAmount($filterData);
        $tlOutcome = $tlOsAmount  + $tlExAmount + $tlPAmount;

        $tlCsAmount = closingStocksCalWithCogs($filterData);
        $tlSAmount = totalSaleAmount($filterData);
        $tlIncome = $tlCsAmount + $tlSAmount;

        $grossProfit = price(reportServices::grossProfitCalWithCogs($filterData));
        $cogs = reportServices::cogs($filterData);
        $data = [
            'grossProfit' => $grossProfit,
            'netProfit' => $netProfit,
            'tlOsAmount' => price($tlOsAmount),
            'otx'=>price(totalOSTransactionAmount($filterData)),
            'tlPAmount' => price($tlPAmount),
            'tlExAmount' => price($tlExAmount),
            'tlOutcome' => price($tlOutcome),

            'tlCsAmount' => price($tlCsAmount),
            'tlSAmount' => price($tlSAmount),
            'tlOIAmount' => price(0),
            'tlIncome' => price($tlIncome),
            'cogs'=>price($cogs),
        ];
        return response()->json($data, 200);
    }

    public function expenseReport()
    {
        return view('App.report.expense.index');
    }

    public function salePurchaseReport()
    {
        return view('App.report.purchaseSale.index');
    }
    public function salePurchaseData(Request $request)
    {

        $filterData = isFilter($request->toArray());

        $tsa = totalSaleAmount($filterData);
        $tpa = totalPurchaseAmount($filterData);
        $diffTSPA = $tsa - $tpa;


        $tpwd = totalPurchaseAmountWithoutDis($filterData);
        $tpd = totalPurchaseDiscountAmt($filterData);
        $tpda = totalPurchaseDueAmount($filterData);


        $tswd = totalSaleAmountWithoutDis($filterData);
        $tsd = totalSaleDiscount($filterData);
        $tsda = totalSaleDueAmount($filterData);
        $data = [
            'tsa' => price($tsa),
            'tpa' => price($tpa),
            'diffTSPA' => price($diffTSPA),

            'tpwd' => price($tpwd),
            'tpd' => price($tpd),
            'tpda' => price($tpda),

            'tswd' => price($tswd),
            'tsd' => price($tsd),
            'tpda' => price($tsda),
        ];
        return response()->json($data, 200);
    }

    public function itemReport()
    {

        return view('App.report.item.index');
    }
    public function itemCount()
    {
        $productCount = Product::select('products.id')
            ->leftJoin('product_variations', 'products.id', '=', 'product_variations.product_id')
            ->count();
        $productCountExcVaria = Product::select('id')->count();
        $data = [
            'productCount' => $productCount,
            'productCountExcVaria' => $productCountExcVaria,
        ];
        return response()->json($data, 200);
    }
    // public function itemData()
    // {
    //     $data = Product::select(
    //         // 'products.id as id',
    //         'sales.sales_voucher_no',
    //         'transfers.transfer_voucher_no',
    //         DB::raw("CONCAT(products.name, '-', variation_template_values.name) AS name"),
    //         'products.name as name',
    //         'products.sku as sku',

    //         // 'sale_details.quantity as sell_qty',
    //         'lot_serial_details.uom_quantity  as sell_qty',
    //         'sale_details.uom_price as selling_price',
    //         // 'sale_details.subtotal_with_tax as sale_subtotal',

    //         'supplier.company_name as supplier',
    //         DB::raw("CONCAT(customer.first_name,' ',customer.middle_name,' ','customer.last_name') AS customer_name"),
    //         'openingPerson.username as openingPerson',
    //         'purchase_details.uom_price as purchase_price',
    //         'current_stock_balance.transaction_type as csbT',
    //         'purchase_voucher_no',
    //         'purchases.created_at as purchase_date',
    //         'opening_stocks.created_at as osDate',
    //         'business_locations.name as location',
    //         'opening_stock_voucher_no',
    //         'stockin_voucher_no',
    //         'opening_date',
    //         'transfered_at',
    //         'stockin_date',
    //         'received_person',
    //         'transferLocaiton.name as transferLocaitonName',
    //         'stockLocation.name as stockLocationName',
    //         'stockinPerson.username as stockinPersonName',

    //         DB::raw('lot_serial_details.uom_quantity * sale_details.uom_price as sale_subtotal'),
    //         DB::raw('lot_serial_details.ref_uom_quantity *  current_stock_balance.ref_uom_price as total_cogs')
    //     )
    //         ->leftJoin('product_variations', 'products.id', '=', 'product_variations.product_id')
    //         ->leftJoin('variation_template_values', 'product_variations.variation_template_value_id', '=', 'variation_template_values.id')
    //         ->leftJoin('sale_details', 'product_variations.id', '=', 'sale_details.variation_id')
    //         ->leftJoin('sales', 'sale_details.sales_id', '=', 'sales.id')
    //         ->leftJoin('contacts as customer', 'sales.contact_id', '=', 'customer.id')
    //         ->leftJoin('business_locations', 'sales.business_location_id', '=', 'business_locations.id')
    //         ->leftJoin('lot_serial_details', function ($join) {
    //             $join->on('lot_serial_details.transaction_type', '=', DB::raw("'sale'"))
    //                 ->where('lot_serial_details.transaction_detail_id', '=', DB::raw('sale_details.id'));
    //         })
    //         ->leftJoin('current_stock_balance', 'lot_serial_details.current_stock_balance_id', '=', 'current_stock_balance.id')
    //         ->join('purchase_details', 'current_stock_balance.transaction_detail_id', '=', 'purchase_details.id')
    //         ->join('purchases', 'purchase_details.purchases_id', '=', 'purchases.id')
    //         ->leftJoin('opening_stock_details', 'current_stock_balance.transaction_detail_id', '=', 'opening_stock_details.id')
    //         ->leftJoin('opening_stocks', 'opening_stock_details.opening_stock_id', '=', 'opening_stocks.id')


    //         ->leftJoin('transfer_details', 'current_stock_balance.transaction_detail_id', '=', 'transfer_details.id')
    //         ->leftJoin('transfers', 'transfer_details.transfer_id', '=', 'transfers.id')

    //         ->leftJoin('stockin_details', 'current_stock_balance.transaction_detail_id', '=', 'stockin_details.id')
    //         ->leftJoin('stockins', 'stockin_details.stockin_id', '=', 'stockins.id')

    //         ->where('sale_details.is_delete', 0)
    //         ->where('sales.is_delete', 0)
    //         ->leftJoin('contacts as supplier', 'purchases.contact_id', '=', 'supplier.id')
    //         ->leftJoin('business_users as openingPerson', 'opening_stocks.opening_person', '=', 'openingPerson.id')
    //         ->leftJoin('business_users as stockinPerson', 'stockins.stockin_person', '=', 'stockinPerson.id')
    //         ->leftJoin('business_locations as transferLocaiton', 'transfers.to_location', '=', 'transferLocaiton.id')
    //         ->leftJoin('business_locations as stockLocation', 'stockins.business_location_id', '=', 'business_locations.id')
    //         // ->where('products.id',6601)
    //         // ->whereNotNull('sales.id')
    //     ;
    //     // dd($data->get()->toArray());
    //     return DataTables::of($data)
    //         ->filter(function($data){

    //             $keyword= request('search')['value'];
    //             if(rtrim($keyword)){
    //                 $data->where('products.name', 'like',"%".$keyword."%")
    //                     ->orwhere('sales.sales_voucher_no', 'like',"%".$keyword."%");
    //             };
    //         })
    //         ->editColumn('purchase_voucher_no', function ($data) {
    //             if ($data->csbT == 'purchase') {
    //                 return $data->purchase_voucher_no;
    //             } elseif ($data->csbT == 'opening_stock') {
    //                 return $data->opening_stock_voucher_no;
    //             }
    //             elseif ($data->csbT == 'stock_in') {
    //                 return $data->stockin_voucher_no;
    //             }
    //             elseif ($data->csbT == 'transfer') {
    //                 return $data->transfer_voucher_no;
    //             }
    //             return '';
    //         })

    //         ->editColumn('purchase_date', function ($data) {
    //             if ($data->csbT == 'purchase') {
    //                 return fDate($data->purchase_date, false, false);
    //             } elseif ($data->csbT == 'opening_stock') {
    //                 return fDate($data->osDate, false, false);
    //             } elseif ($data->csbT == 'transfer') {
    //                 return $data->transfered_at;
    //             } elseif ($data->csbT == 'stock_in') {
    //                 return $data->stockin_date;
    //             }
    //             return '';
    //         })
    //         ->editColumn('supplier', function ($data) {
    //             if ($data->csbT == 'purchase') {
    //                 return $data->supplier;
    //             } elseif ($data->csbT == 'opening_stock') {
    //                 return $data->openingPerson;
    //             } elseif ($data->csbT == 'transfer') {
    //                 return $data->transferLocaitonName;
    //             }
    //             elseif ($data->csbT == 'stock_in') {
    //                 return $data->stockinPersonName;
    //             }
    //             return '';
    //         })
    //         ->rawColumns(['purchase_date'])->make(true);
    // }
}
