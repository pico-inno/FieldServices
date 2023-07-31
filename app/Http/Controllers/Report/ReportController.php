<?php

namespace App\Http\Controllers\Report;
use App\Helpers\UomHelper;
use App\Http\Controllers\Controller;
use App\Models\BusinessUser;
use App\Models\CurrentStockBalance;
use App\Models\Product\Brand;
use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Models\Product\Unit;
use App\Models\settings\businessLocation;
use App\Models\Stock\Stockin;
use App\Models\Stock\Stockout;
use App\Models\Stock\StockTransfer;

use Illuminate\Http\Request;

class ReportController extends Controller
{

    //Being: Inventory Reports
//    public function stock_index(){
//        $locations = businessLocation::select('id', 'name')->get();
//        $stocks_person = BusinessUser::select('id', 'username')->get();
//
//        return view('App.report.inventory.stock.index', [
//            'locations' => $locations,
//            'stocksperson' => $stocks_person,
//        ]);
//    }
//
//    public function stockFilter(Request $request)
//    {
//        $filterType = $request->data['filter_type'];
//        $dateRange = $request->data['filter_date'];
//        $dates = explode(' - ', $dateRange);
//
//        $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[0])->startOfDay();
//        $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[1])->endOfDay();
//
//        if ($filterType == 1){
//            $query = Stockin::where('is_deleted', 0)
//                ->with(['businessLocation:id,name', 'stockinPerson:id,username', 'created_by:id,username'])
//                ->whereBetween('stockin_date', [$startDate, $endDate]);;
//
//            // if ($request->data['filter_status'] != 0) {
//            //     $status = '';
//
//            //     switch ($request->data['filter_status']) {
//            //         case 1:
//            //             $status = 'pending';
//            //             break;
//            //         case 2:
//            //             $status = 'received';
//            //             break;
//            //         case 3:
//            //             $status = 'issued';
//            //             break;
//            //         case 4:
//            //             $status = 'confirmed';
//            //             break;
//            //     }
//
//            //     $query->where('status', $status);
//            // }
//
//            if ($request->data['filter_locations'] != 0) {
//                $query->where('business_location_id', $request->data['filter_locations']);
//            }
//
//            if ($request->data['filter_stocksperson'] != 0) {
//                $query->where('stockin_person', $request->data['filter_stocksperson']);
//            }
//
//        }
//
//        if ($filterType == 2){
//
//            $query = Stockout::where('is_deleted', 0)
//                ->with(['businessLocation:id,name', 'stockoutPerson:id,username', 'created_by:id,username'])
//                ->whereBetween('stockout_date', [$startDate, $endDate]);;
//
//            // if ($request->data['filter_status'] != 0) {
//            //     $status = '';
//
//            //     switch ($request->data['filter_status']) {
//            //         case 1:
//            //             $status = 'pending';
//            //             break;
//            //         case 2:
//            //             $status = 'received';
//            //             break;
//            //         case 3:
//            //             $status = 'issued';
//            //             break;
//            //         case 4:
//            //             $status = 'confirmed';
//            //             break;
//            //     }
//
//            //     $query->where('status', $status);
//            // }
//
//            if ($request->data['filter_locations'] != 0) {
//                $query->where('business_location_id', $request->data['filter_locations']);
//            }
//
//            if ($request->data['filter_stocksperson'] != 0) {
//                $query->where('stockout_person', $request->data['filter_stocksperson']);
//            }
//
//        }
//
//        $results = $query->get();
//
//        return response()->json($results, 200);
//    }


    public function stock_transfer_index(){
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
            ->with(['businessLocationFrom:id,name', 'businessLocationTo:id,name','stocktransferPerson:id,username', 'stockreceivePerson:id,username', 'created_by:id,username'])
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

//    public function stock_details_index(){
//        $locations = businessLocation::select('id', 'name')->get();
//        $categories = Category::select('id', 'name', 'parent_id')->get();
//        $brands = Brand::select('id', 'name',)->get();
//        $products = Product::select('id', 'name')->get();
//
//
//        return view('App.report.inventory.stock.details', [
//            'locations' => $locations,
//            'categories' => $categories,
//            'brands' => $brands,
//            'products' => $products
//        ]);
//    }
//
//    public function stockDetailsFilter(Request $request){
//
//        $filterType = $request->data['filter_type'];
//        $filterProduct = $request->data['filter_product'];
//        $filterCategory = $request->data['filter_category'];
//        $filterBrand = $request->data['filter_brand'];
//        $dateRange = $request->data['filter_date'];
//        $dates = explode(' - ', $dateRange);
//
//        $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[0])->startOfDay();
//        $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[1])->endOfDay();
//
//        $query = null;
//        $stockDetails = [];
//
//        //for stockin
//        if ($filterType == 1) {
//            $query = Stockin::where('is_deleted', 0)
//                ->with(['stockindetails'])
//                ->whereBetween('stockin_date', [$startDate, $endDate]);
//
//            if ($request->data['filter_locations'] != 0) {
//                $query->where('business_location_id', $request->data['filter_locations']);
//            }
//
//            $stockDetails = $query->get()->pluck('stockindetails')->flatten();
//        }
//
//        //for stockout
//        if ($filterType == 2) {
//            $query = Stockout::where('is_deleted', 0)
//                ->with('stockoutdetails')
//                ->whereBetween('stockout_date', [$startDate, $endDate]);
//
//            if ($request->data['filter_locations'] != 0) {
//                $query->where('business_location_id', $request->data['filter_locations']);
//            }
//
//            $stockDetails = $query->get()->pluck('stockoutdetails')->flatten();
//        }
//
//        $productIds = $stockDetails->pluck('product_id')->unique()->toArray();
//
//        $finalProduct = Product::select('id', 'name', 'product_code', 'sku', 'product_type', 'brand_id', 'category_id', 'uom_id', 'purchase_uom_id')
//            ->with(['category:id,name', 'brand:id,name', 'productVariations' => function ($query) {
//                $query->select('id', 'product_id', 'variation_template_value_id', 'default_purchase_price', 'default_selling_price', 'variation_sku')
//                    ->with(['variationTemplateValue' => function ($query) {
//                        $query->select('id', 'name', 'variation_template_id')
//                            ->with(['variationTemplate:id,name']);
//                    }]);
//            }, 'uom' => function ($q) {
//                $q->with(['unit_category' => function ($q) {
//                    $q->with('uomByCategory');
//                }]);
//            }
//            ])
//            ->whereIn('id', $productIds);
//
//        if ($filterProduct != 0) {
//            $finalProduct->where('id', $filterProduct);
//        }
//
//        if ($filterCategory != 0) {
//            $finalProduct->where('category_id', $filterCategory);
//        }
//
//        if ($filterBrand != 0) {
//            $finalProduct->where('brand_id', $filterBrand);
//        }
//
//        $finalProduct = $finalProduct->get()->toArray();
//
//
//
//        $result = [];
//
//
//        foreach ($stockDetails as $stockDetail) {
//            $productId = $stockDetail['product_id'];
//            $variationId = $stockDetail['variation_id'];
//            // $lotNo = $stockDetail['lot_no'];
//
//            foreach ($finalProduct as $product) {
//                if ($product['id'] == $productId) {
//                    $variations = $product['product_variations'];
//
//                    foreach ($variations as $variation) {
//                        if ($variation['id'] == $variationId) {
//                            // $smallest_qty = UomHelper::smallestQty($stockDetail['uomset_id'], $stockDetail['unit_id'], $stockDetail['quantity']);
//                            // $smallest_price = UomHelper::smallestPrice($stockDetail['uomset_id'],$stockDetail['unit_id'],$stockDetail['quantity'],$stockDetail['purchase_price']);
//                            // $smallest_unit_name =Unit::find(UomHelper::smallestUnitId($stockDetail['uomset_id']))->name;
//                            $variationProduct = [
//                                'id' => $product['id'],
//                                'name' => $product['name'],
//                                'sku' => $product['sku'],
//                                'variation_id' => $variation['id'],
//                                'product_type' => $product['product_type'],
//                                'variation_sku' => $product['product_type'] == 'variable' ? $variation['variation_sku'] : "",
//                                'category_id' => $product['category']['id'] ?? '',
//                                'category_name' => $product['category']['name'] ?? '',
//                                'brand_name' => $product['brand']['name'] ?? '',
//                                'brand_id' => $product['brand']['id'] ?? '',
//                                'uom_short_name' => $stockDetail['uom']['short_name'],
//                                'uom_name' => $stockDetail['uom']['name'],
//                                'default_purchase_price' => number_format($variation['default_purchase_price'], 2),
//                                'variation_template_name' => $variation['variation_template_value']['variation_template']['name'] ?? '',
//                                'variation_value_name' => $variation['variation_template_value']['name'] ?? '',
//                                'stock_qty' => number_format($stockDetail['quantity'], 2),
//
//                            ];
//
//                            $result[] = $variationProduct;
//                        }
//                    }
//                }
//            }
//        }
//
//        return response()->json($result, 200);
//    }


    public function transfer_details_index(){
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

    public function transferDetailsFilter(Request $request){

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


    public function currentStockBalanceIndex(){
        $locations = businessLocation::select('id', 'name')->get();
        $categories = Category::select('id', 'name', 'parent_id')->get();
        $brands = Brand::select('id', 'name',)->get();
        $products = Product::select('id', 'name')->get();

        return view('App.report.inventory.stock.currentBalance', [
            'locations' => $locations,
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products
        ]);
    }


    public function currentStockBalanceFilter(Request $request){
        $filterLot = $request->data['filter_lot'];
        $filterProduct = $request->data['filter_product'];
        $filterCategory = $request->data['filter_category'];
        $filterBrand = $request->data['filter_brand'];
        $dateRange = $request->data['filter_date'];
        $dates = explode(' - ', $dateRange);

        $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[0])->startOfDay();
        $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[1])->endOfDay();


        $query = CurrentStockBalance::with(['uom','location:id,name'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($request->data['filter_locations'] != 0) {
            $query->where('business_location_id', $request->data['filter_locations']);
        }

        $currentStocks = $query->get();
        $productIds = $currentStocks->pluck('product_id')->unique()->toArray();

        $result = [];

        $finalProduct = Product::select('id', 'name', 'product_code', 'sku', 'product_type', 'brand_id', 'category_id')
            ->with(['category:id,name', 'brand:id,name', 'productVariations' => function ($query) {
                $query->select('id', 'product_id', 'variation_template_value_id', 'default_purchase_price', 'default_selling_price', 'variation_sku')
                    ->with(['variationTemplateValue' => function ($query) {
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

        if ($filterLot == 1) {
            $mergedStocks = [];
            $mergedBatchCount = 0;

            foreach ($currentStocks as $currentStock) {
                // $productId = $currentStock['product_id'];
                $variationId = $currentStock['variation_id'];
                $locationId = $currentStock['location']['id'];

                // $batchNo = $currentStock['batch_no']


                $key = $currentStock['batch_no'] . '_' . $variationId . '_' . $locationId;
                // $key = $currentStock['batch_no'];


                if (!isset($mergedStocks[$key])) {
                    $mergedStocks[$key] = $currentStock;
                    $mergedStocks[$key]['marged_batch'] =  $currentStock['batch_no'];
                } else {
                    $mergedBatchCount++;
                    $mergedStocks[$key]['marged_batch'] =  'Batch merged';
                    $mergedStocks[$key]['ref_uom_quantity'] += $currentStock['ref_uom_quantity'];
                    $mergedStocks[$key]['current_quantity'] += $currentStock['current_quantity'];

                }
            }

            $stocks = $mergedStocks;
        } else {
            $stocks = $currentStocks;
        }

        $lotCounts = [];
        $result = [];

        foreach ($stocks as $currentStock) {
            $productId = $currentStock['product_id'];
            $variationId = $currentStock['variation_id'];
            $locationId = $currentStock['location']['id'];

            // Generate a unique key for the product, variation, and location combination
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
                                'name' => $product['name'],
                                'sku' => $product['sku'],
                                'variation_id' => $variation['id'],
                                'product_type' => $product['product_type'],
                                'variation_sku' => $product['product_type'] == 'variable' ? $variation['variation_sku'] : "",
                                'variation_template_name' => $variation['variation_template_value']['variation_template']['name'] ?? '',
                                'variation_value_name' => $variation['variation_template_value']['name'] ?? '',
                                'lot_no' => $filterLot ? $currentStock['marged_batch'] : $currentStock['batch_no'],
//                                'lot_no' => $filterLot ? $currentStock['marged_batch_count'] : 'lot ' . $lotCounts[$key],
                                'location_name' => $currentStock['location']['name'],
                                'category_id' => $product['category']['id'] ?? '',
                                'category_name' => $product['category']['name'] ?? '',
                                'brand_name' => $product['brand']['name'] ?? '',
                                'brand_id' => $product['brand']['id'] ?? '',
                                'ref_uom_name' => $currentStock['uom']['name'],
                                'ref_uom_short_name' => $currentStock['uom']['short_name'],
                                'purchase_qty' => $currentStock['ref_uom_quantity'],
                                'current_qty' => $currentStock['current_quantity'],
                            ];

                            $result[] = $variationProduct;
                        }
                    }
                }
            }
        }


        return response()->json( $result, 200);
    }
    //End: Inventory Report

}
