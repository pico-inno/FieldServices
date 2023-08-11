<?php

namespace App\Http\Controllers;

use App\Models\Contact\Contact;
use App\Models\CurrentStockBalance;
use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Models\purchases\purchases;
use App\Models\sale\sales;
use App\Models\settings\businessLocation;
use Carbon\Carbon;
use Illuminate\Http\Request;



class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','isActive']);
    }

    public function index()
    {
        $locations = businessLocation::select('id', 'name')->get();
        $categories = Category::select('id', 'name', 'parent_id')->get();

        return view('App.dashboard',[
            'locations' => $locations,
            'categories' => $categories,
        ]);
    }


    public function currentBalanceFilter(Request $request){

        $filterCategory = $request->data['filter_category'];


        $query = CurrentStockBalance::with(['uom', 'location:id,name']);

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


        if ($filterCategory != 0) {
            $finalProductQuery->where('category_id', $filterCategory);
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
                            $createdAtFormatted = date('Y-m-d H:i:s', strtotime($currentStock['created_at']));

                            $variationProduct = [
                                'id' => $product['id'],
                                'name' => $product['name'],
                                'sku' => $product['sku'],
                                'product_type' => $product['product_type'],
                                'variation_sku' => $product['product_type'] == 'variable' ? $variation['variation_sku'] : "",
                                'variation_template_name' => $variation['variation_template_value']['variation_template']['name'] ?? '',
                                'variation_value_name' => $variation['variation_template_value']['name'] ?? '',
                                'location_name' => $currentStock['location']['name'],
                                'category_name' => $product['category']['name'] ?? '',
                                'brand_name' => $product['brand']['name'] ?? '',
                                'ref_uom_price' => number_format($currentStock['ref_uom_price'],2),
                                'ref_uom_name' => $currentStock['uom']['name'],
                                'ref_uom_short_name' => $currentStock['uom']['short_name'],
                                'purchase_qty' => number_format($currentStock['ref_uom_quantity'],2),
                                'current_qty' => number_format($currentStock['current_quantity'],2),
                                'created_at' => $createdAtFormatted,
                                'expired_date' => $currentStock['expired_date'] ?? ' - ',
                            ];
                            $result[] = $variationProduct;
                        }
                    }
                }
            }
        }

        return response()->json($result, 200);
    }

    public function totalCurrentBalanceQty(){
        $totalQty = CurrentStockBalance::select('current_quantity')->get()->sum('current_quantity');
        return $totalQty;
    }

    public function totalContact(){
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        $contacts = Contact::select('type')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->get()
            ->groupBy('type');

        $customerCount = $contacts->get('Customer')->count();
        $supplierCount = $contacts->get('Supplier')->count();

        $result = [
            'totalCustomers' => $customerCount,
            'totalSuppliers' => $supplierCount,
        ];

        return response()->json($result, 200);
    }

    public function totalSaleAndPurchaseOrder(){

        $purchaseData = purchases::where('status', 'order')
            ->where('is_delete', 0)
            ->get()->count();
        $saleData = sales::where('status', 'order')
            ->where('is_delete', 0)
            ->get()->count();


        $result = [
            'totalSaleOrder' => $saleData,
            'totalPurchaseOrder' => $purchaseData,
        ];

        return response()->json($result, 200);
    }

}
