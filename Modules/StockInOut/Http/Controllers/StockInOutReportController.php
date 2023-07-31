<?php

namespace Modules\StockInOut\Http\Controllers;

use App\Models\BusinessUser;
use App\Models\Product\Brand;
use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Models\settings\businessLocation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\StockInOut\Entities\Stockin;
use Modules\StockInOut\Entities\Stockout;

class StockInOutReportController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }
    
    public function stock_index(){
//        return 'index wrok';
        $locations = businessLocation::select('id', 'name')->get();
        $stocks_person = BusinessUser::select('id', 'username')->get();

        return view('stockinout::reports.index', [
            'locations' => $locations,
            'stocksperson' => $stocks_person,
        ]);
    }

    public function stockFilter(Request $request)
    {
        $filterType = $request->data['filter_type'];
        $dateRange = $request->data['filter_date'];
        $dates = explode(' - ', $dateRange);

        $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[0])->startOfDay();
        $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[1])->endOfDay();

        if ($filterType == 1){
            $query = Stockin::where('is_deleted', 0)
                ->with(['businessLocation:id,name', 'stockinPerson:id,username', 'created_by:id,username'])
                ->whereBetween('stockin_date', [$startDate, $endDate]);;


            if ($request->data['filter_locations'] != 0) {
                $query->where('business_location_id', $request->data['filter_locations']);
            }

            if ($request->data['filter_stocksperson'] != 0) {
                $query->where('stockin_person', $request->data['filter_stocksperson']);
            }

        }

        if ($filterType == 2){

            $query = Stockout::where('is_deleted', 0)
                ->with(['businessLocation:id,name', 'stockoutPerson:id,username', 'created_by:id,username'])
                ->whereBetween('stockout_date', [$startDate, $endDate]);;


            if ($request->data['filter_locations'] != 0) {
                $query->where('business_location_id', $request->data['filter_locations']);
            }

            if ($request->data['filter_stocksperson'] != 0) {
                $query->where('stockout_person', $request->data['filter_stocksperson']);
            }

        }

        $results = $query->get();

        return response()->json($results, 200);
    }

    public function stock_details_index(){
        $locations = businessLocation::select('id', 'name')->get();
        $categories = Category::select('id', 'name', 'parent_id')->get();
        $brands = Brand::select('id', 'name',)->get();
        $products = Product::select('id', 'name')->get();


        return view('stockinout::reports.details', [
            'locations' => $locations,
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products
        ]);
    }

    public function stockDetailsFilter(Request $request){

        $filterType = $request->data['filter_type'];
        $filterProduct = $request->data['filter_product'];
        $filterCategory = $request->data['filter_category'];
        $filterBrand = $request->data['filter_brand'];
        $dateRange = $request->data['filter_date'];
        $dates = explode(' - ', $dateRange);

        $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[0])->startOfDay();
        $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[1])->endOfDay();

        $query = null;
        $stockDetails = [];

        //for stockin
        if ($filterType == 1) {
            $query = Stockin::where('is_deleted', 0)
                ->with(['stockindetails'])
                ->whereBetween('stockin_date', [$startDate, $endDate]);

            if ($request->data['filter_locations'] != 0) {
                $query->where('business_location_id', $request->data['filter_locations']);
            }

            $stockDetails = $query->get()->pluck('stockindetails')->flatten();
        }

        //for stockout
        if ($filterType == 2) {
            $query = Stockout::where('is_deleted', 0)
                ->with('stockoutdetails')
                ->whereBetween('stockout_date', [$startDate, $endDate]);

            if ($request->data['filter_locations'] != 0) {
                $query->where('business_location_id', $request->data['filter_locations']);
            }

            $stockDetails = $query->get()->pluck('stockoutdetails')->flatten();
        }

        $productIds = $stockDetails->pluck('product_id')->unique()->toArray();

        $finalProduct = Product::select('id', 'name', 'product_code', 'sku', 'product_type', 'brand_id', 'category_id', 'uom_id', 'purchase_uom_id')
            ->with(['category:id,name', 'brand:id,name', 'productVariations' => function ($query) {
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
            // $lotNo = $stockDetail['lot_no'];

            foreach ($finalProduct as $product) {
                if ($product['id'] == $productId) {
                    $variations = $product['product_variations'];

                    foreach ($variations as $variation) {
                        if ($variation['id'] == $variationId) {
                            // $smallest_qty = UomHelper::smallestQty($stockDetail['uomset_id'], $stockDetail['unit_id'], $stockDetail['quantity']);
                            // $smallest_price = UomHelper::smallestPrice($stockDetail['uomset_id'],$stockDetail['unit_id'],$stockDetail['quantity'],$stockDetail['purchase_price']);
                            // $smallest_unit_name =Unit::find(UomHelper::smallestUnitId($stockDetail['uomset_id']))->name;
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
                                'uom_short_name' => $stockDetail['uom']['short_name'],
                                'uom_name' => $stockDetail['uom']['name'],
                                'default_purchase_price' => number_format($variation['default_purchase_price'], 2),
                                'variation_template_name' => $variation['variation_template_value']['variation_template']['name'] ?? '',
                                'variation_value_name' => $variation['variation_template_value']['name'] ?? '',
                                'stock_qty' => number_format($stockDetail['quantity'], 2),

                            ];

                            $result[] = $variationProduct;
                        }
                    }
                }
            }
        }

        return response()->json($result, 200);
    }

}
