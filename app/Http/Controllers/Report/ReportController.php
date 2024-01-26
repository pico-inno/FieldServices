<?php

namespace App\Http\Controllers\Report;

use App\Helpers\UomHelper;
use App\Models\sale\sales;
use App\Models\BusinessUser;
use App\Models\Product\Unit;
use Illuminate\Http\Request;
use App\Models\Product\Brand;
use App\Models\Stock\Stockout;
use App\Models\Contact\Contact;
use App\Models\Product\Product;
use App\Models\Product\Category;
use Illuminate\Support\Facades\DB;
use App\Models\CurrentStockBalance;
use App\Models\purchases\purchases;
use App\Models\Stock\StockTransfer;
use App\Http\Controllers\Controller;
use App\Models\Stock\StockAdjustment;
use App\Services\Report\reportServices;
use Modules\StockInOut\Entities\Stockin;
use Yajra\DataTables\Facades\DataTables;
use App\Models\settings\businessLocation;

use App\Models\settings\businessSettings;
use App\Http\Controllers\sell\saleController;
use Modules\StockInOut\Entities\StockinDetail;
use App\Http\Controllers\Contact\CustomerController;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
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
            ->with('saleDetails', 'customer', 'business_location_id')
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
                                //                                'purchase_data' => $purchase,
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

        $locations = businessLocation::select('id', 'name')->get();
        $customers = Contact::where('type', 'Customer')->get();

        $categories = Category::select('id', 'name', 'parent_id')->get();
        $brands = Brand::select('id', 'name',)->get();
        $products = Product::select('id', 'name')->get();

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
            default:

                break;
        }


        $query = CurrentStockBalance::with(['uom', 'location:id,name']);

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

        //        if ($request->data['filter_locations_from'] != 0) {
        //            $locationId = childLocationIDs($request->data['filter_locations_from']);
        //            $query->whereIn('from_location', $locationId);
        //        }

        if ($request->data['filter_locations_to'] != 0) {
            $query->where('to_location', $request->data['filter_locations_to']);
        }

        //        if ($request->data['filter_locations_to'] != 0) {
        //            $locationId = childLocationIDs($request->data['filter_locations_to']);
        //            $query->whereIn('to_location', $locationId);
        //        }

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

        // Additional optimization steps can be applied here, such as caching or pagination

        $result = [];

        foreach ($stockDetails as $stockDetail) {
            $productId = $stockDetail['product_id'];
            $variationId = $stockDetail['variation_id'];
            $lotNo = $stockDetail['lot_no'];

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
                                'name' => $product['name'],
                                'sku' => $product['sku'],
                                'variation_id' => $variation['id'],
                                'product_type' => $product['product_type'],
                                'variation_sku' => $product['product_type'] == 'variable' ? $variation['variation_sku'] : "",
                                'category_id' => $product['category']['id'] ?? '',
                                'category_name' => $product['category']['name'] ?? '',
                                'brand_name' => $product['brand']['name'] ?? '',
                                'brand_id' => $product['brand']['id'] ?? '',
                                // 'unit_name' => $stockDetail['unit']['name'],
                                // 'uom_name' => $stockDetail['uomset']['uomset_name'],
                                'uom_short_name' => $stockDetail['uom']['short_name'],
                                'uom_name' => $stockDetail['uom']['name'],
                                'purchase_price' => number_format($stockDetail['purchase_price'], 2),
                                // 'smallest_purchase_price' => $smallest_price,
                                'transfer_quantity' => number_format($stockDetail['quantity'], 2),
                                'variation_template_name' => $variation['variation_template_value']['variation_template']['name'] ?? '',
                                'variation_value_name' => $variation['variation_template_value']['name'] ?? '',
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


        //        return $results;

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

        if ($dateRange) {
            $dates = explode(' - ', $dateRange);

            $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[0])->startOfDay();
            $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[1])->endOfDay();
        }

        if ($filterType == 2) {
            $query = CurrentStockBalance::with(['uom', 'packaging_uom', 'location:id,name,parent_location_id'])
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

        $finalProduct = Product::select('id', 'name', 'product_code', 'sku', 'product_type', 'brand_id', 'category_id')
            ->with(['category:id,name', 'brand:id,name', 'productVariations' => function ($query) {
                $query->select('id', 'product_id', 'variation_template_value_id', 'default_purchase_price', 'default_selling_price', 'variation_sku')
                    ->with(['packaging', 'variationTemplateValue' => function ($query) {
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

            foreach ($finalProduct as $product) {
                if ($product['id'] == $productId) {
                    $variations = $product['product_variations'];

                    foreach ($variations as $variation) {
                        if ($variation['id'] == $variationId) {

                            $variationProduct = [
                                'id' => $product['id'],
                                'product_packaging_id' => $currentStock['product_packaging_id'] ?? 0,
                                'name' => $product['name'],
                                'sku' => $product['sku'],
                                'variation_id' => $variation['id'],
                                'product_type' => $product['product_type'],
                                'variation_sku' => $product['product_type'] == 'variable' ? $variation['variation_sku'] : "",
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
                                'current_qty' => $currentStock['current_quantity'],
                                'package_name' =>  $packageName,
                                'package_qty' => $currentStock['quantity'],
                                'packaging_uom_short_name' => $currentStock['packaging_uom']['short_name'] ?? '',
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
    public function itemData()
    {
        $data = Product::select(
            // 'products.id as id',
            'sales.sales_voucher_no',
            'products.name as name',
            'products.sku as sku',

            'sale_details.quantity as sell_qty',
            'sale_details.uom_price as selling_price',
            'sale_details.subtotal_with_tax as sale_subtotal',

            'supplier.company_name as supplier',
            'customer.first_name as customer_name',
            'openingPerson.username as openingPerson',
            'purchase_details.uom_price as purchase_price',
            'current_stock_balance.transaction_type as csbT',
            'purchase_voucher_no',
            'purchases.created_at as purchase_date',
            'opening_stocks.created_at as osDate',
            'business_locations.name as location',
            'opening_stock_voucher_no',
            'opening_date'
        )
            ->leftJoin('product_variations', 'products.id', '=', 'product_variations.product_id')
            ->leftJoin('sale_details', 'product_variations.id', '=', 'sale_details.variation_id')
            ->leftJoin('sales', 'variation_id', '=', 'sales.id')
            ->leftJoin('contacts as customer', 'sales.contact_id', '=', 'customer.id')
            ->leftJoin('business_locations', 'sales.business_location_id', '=', 'business_locations.id')
            ->leftJoin('lot_serial_details', function ($join) {
                $join->on('lot_serial_details.transaction_type', '=', DB::raw("'sale'"))
                    ->where('lot_serial_details.transaction_detail_id', '=', DB::raw('sale_details.id'));
            })
            ->leftJoin('current_stock_balance', 'lot_serial_details.current_stock_balance_id', '=', 'current_stock_balance.id')
            ->leftJoin('purchase_details', 'current_stock_balance.transaction_detail_id', '=', 'purchase_details.id')
            ->leftJoin('purchases', 'purchase_details.purchases_id', '=', 'purchases.id')
            ->leftJoin('opening_stock_details', 'current_stock_balance.transaction_detail_id', '=', 'opening_stock_details.id')
            ->leftJoin('opening_stocks', 'opening_stock_details.opening_stock_id', '=', 'opening_stocks.id')
            ->where('purchase_details.is_delete', 0)
            ->where('sale_details.is_delete', 0)

            ->where('purchases.is_delete', 0)
            ->where('sales.is_delete', 0)
            ->leftJoin('contacts as supplier', 'purchases.contact_id', '=', 'supplier.id')
            ->leftJoin('business_users as openingPerson', 'opening_stocks.opening_person', '=', 'openingPerson.id')
            // ->where('products.id',6601)
            // ->whereNotNull('sales.id')
        ;
        // dd($data->get()->toArray());
        return DataTables::of($data)
            ->editColumn('purchase_voucher_no', function ($data) {
                if ($data->csbT == 'purchase') {
                    return $data->purchase_voucher_no;
                } elseif ($data->csbT == 'opening_stock') {
                    return $data->opening_stock_voucher_no;
                }
                return '';
            })

            ->editColumn('purchase_date', function ($data) {
                if ($data->csbT == 'purchase') {
                    return fDate($data->purchase_date, false, false);
                } elseif ($data->csbT == 'opening_stock') {
                    return fDate($data->osDate, false, false);
                }
                return '';
            })
            ->editColumn('supplier', function ($data) {
                if ($data->csbT == 'purchase') {
                    return $data->supplier;
                } elseif ($data->csbT == 'opening_stock') {
                    return $data->openingPerson;
                }
                return '';
            })
            ->rawColumns(['purchase_date'])->make(true);
    }
}
