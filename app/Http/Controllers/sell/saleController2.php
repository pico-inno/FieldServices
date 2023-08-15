<?php

namespace App\Http\Controllers\sell;

use Error;
use stdClass;
use Exception;
use App\Helpers\UomHelper;
use App\Models\sale\sales;
use App\Helpers\UomHelpers;
use App\Models\Product\UOM;
use App\Models\Product\Unit;
use Illuminate\Http\Request;
use App\Models\stock_history;
use App\Models\Product\UOMSet;
use Illuminate\Support\Carbon;
use App\Models\Contact\Contact;
use App\Models\Product\Product;
use App\Models\sale\sale_details;
use App\Models\Product\PriceGroup;
use Illuminate\Support\Facades\DB;
use App\Models\CurrentStockBalance;
use App\Models\purchases\purchases;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Product\UOMSellingprice;
use App\Models\Product\ProductVariation;
use Yajra\DataTables\Facades\DataTables;
use App\Models\settings\businessLocation;
use Illuminate\Support\Facades\Validator;
use App\Models\purchases\purchase_details;
use App\Models\settings\businessSettings;

class saleController2 extends Controller
{
    private $setting;
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
        $this->middleware('canView:sell')->only(['index', 'saleItemsList']);
        $this->middleware('canCreate:sell')->only(['createPage', 'store']);
        $this->middleware('canUpdate:sell')->only(['saleEdit', 'update']);
        $this->middleware('canDelete:sell')->only('softDelete', 'softSelectedDelete');
        $this->setting = businessSettings::select('lot_control')->first();
    }

    //
    public function index()
    {
        return view('App.sell.sale.allSales');
    }

    public function saleItemsList()
    {
        $saleItems = sales::where('is_delete', 0)->orderBy('id', 'DESC')->with('business_location_id', 'customer')->get();
        return DataTables::of($saleItems)
            ->addColumn('checkbox', function ($saleItem) {
                return
                    '
                    <div class="form-check form-check-sm form-check-custom ">
                        <input class="form-check-input" type="checkbox" data-checked="delete" value=' . $saleItem->id . ' />
                    </div>
                ';
            })
            ->editColumn('customer', function ($saleItem) {
                return $saleItem->customer['company_name'] ?? $saleItem->customer['first_name'];
            })
            ->addColumn('action', function ($saleItem) {
                $html = '
                    <div class="dropdown text-center">
                        <button class="btn btn-sm btn-light btn-primary fw-semibold fs-7  dropdown-toggle " type="button" id="saleItemDropDown" data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                        </button>
                        <div class="z-3">
                        <ul class="dropdown-menu z-10 p-5 " aria-labelledby="saleItemDropDown" role="menu">';
                if (hasView('sell')) {
                    $html .= ' <a class="dropdown-item p-2  px-3 view_detail"   type="button" data-href="' . route('saleDetail', $saleItem->id) . '">
                                view
                            </a>';
                }

                if (hasPrint('sell')) {
                    $html .= '<a class="dropdown-item p-2  cursor-pointer bg-active-danger text-info print-invoice"  data-href="' . route('print_sale', $saleItem->id) . '">print</a>';
                }

                if (hasUpdate('sell')) {
                    $html .= ' <a href="' . route('saleEdit', $saleItem->id) . '" class="dropdown-item p-2 edit-unit bg-active-primary text-primary" >Edit</a>';
                }

                if (hasDelete('sell')) {
                    $html .= ' <a class="dropdown-item p-2  cursor-pointer bg-active-danger text-danger"  data-id="' . $saleItem->id . '" data-kt-saleItem-table="delete_row">Delete</a>';
                }

                $html .= '</ul></div></div>';
                return (hasView('sell') && hasPrint('sell') && hasUpdate('sell') && hasDelete('sell') ? $html : 'No Access');
            })
            ->rawColumns(['action', 'checkbox'])
            ->make(true);
    }

    public function saleDetail($id)
    {

        $sale = sales::with('business_location_id', 'sold_by', 'confirm_by', 'customer', 'updated_by')->where('id', $id)->first()->toArray();

        $location = $sale['business_location_id'];
        $setting = $this->setting;
        $sale_details_query = sale_details::with(['productVariation' => function ($q) {
            $q->select('id', 'product_id', 'variation_template_value_id')
                ->with([
                    'product' => function ($q) {
                        $q->select('id', 'name', 'product_type', 'unit_id');
                    },
                    'variationTemplateValue' => function ($q) {
                        $q->select('id', 'name');
                    }
                ]);
        }, 'uomSet', 'unit', 'product'])
            ->leftJoin('uom_sets', 'sale_details.uomset_id', '=', 'uom_sets.id')
            ->select('sale_details.*', 'uom_sets.uomset_name')
            ->where('sales_id', $id)->where('is_delete', 0);

        $sale_details = $sale_details_query->whereNull('parent_sale_details_id')->get();
        $child_sale_details = $sale_details_query->whereNotNull('parent_sale_details_id', '!=', null)->get();
        return view('App.sell.sale.details.saleDetail', compact(
            'sale',
            'location',
            'sale_details',
            'child_sale_details',
            'setting'
        ));
    }

    // for edit page
    public function saleEdit($id)
    {
        $locations = businessLocation::all();
        $products = Product::with('productVariations', 'unit')->get();
        $customers = Contact::where('type', 'Customer')->get();
        $priceGroups = PriceGroup::select('id', 'name', 'description')->get();
        $uom_sets = UOMSet::select('id', 'uomset_name as text')->get();
        $locations = businessLocation::all();
        $setting = businessSettings::first();


        $sale = sales::where('id', $id)->get()->first();

        $sale_details_query = sale_details::with([
            'productVariation' => function ($q) {
                $q->select('id', 'product_id', 'variation_template_value_id')
                    ->with([
                        'product' => function ($q) {
                            $q->select('id', 'name', 'product_type', 'unit_id');
                        },
                        'variationTemplateValue' => function ($q) {
                            $q->select('id', 'name');
                        }
                    ]);
            },
            'stock' => function ($q) {
                $q->with([
                    'uomSet' => function ($q) {
                        $q->with('units', 'uom_sellingprices')->select('id', 'uomset_name');
                    }
                ]);
            },
            'Currentstock' => function ($q) {
                $q->with([
                    'uomSet' => function ($q) {
                        $q->with('units', 'uom_sellingprices')->select('id', 'uomset_name');
                    }
                ]);
            }, 'uomSet', 'unit', 'product',
        ])
            ->leftJoin('uom_sets', 'sale_details.uomset_id', '=', 'uom_sets.id')
            ->select('sale_details.*', 'uom_sets.uomset_name')
            ->where('sales_id', $id)->where('is_delete', 0);
        $sale_details = $sale_details_query->whereNull('parent_sale_details_id')->get();
        $child_sale_details = $sale_details_query->whereNotNull('parent_sale_details_id', '!=', null)->get();
        return view('App.sell.sale.edit', compact('locations', 'products', 'uom_sets', 'customers', 'priceGroups', 'sale', 'sale_details', 'setting', 'child_sale_details'));
    }


    // sale create page
    public function createPage()
    {
        $locations = businessLocation::all();
        $products = Product::with('productVariations')->get();
        $customers = Contact::where('type', 'Customer')->get();
        $priceGroups = PriceGroup::select('id', 'name', 'description')->get();
        $setting = businessSettings::first();
        // $uom_sets = UOMSet::select('id', 'uomset_name as text')->get();
        $locations = businessLocation::all();
        return view('App.sell.sale.addSale', compact('locations', 'products', 'customers', 'priceGroups', 'setting'));
    }

    // sale store
    public function store(Request $request)
    {
        $sale_details = $request->sale_details;
        DB::beginTransaction();
        Validator::make($request->toArray(), [
            'sale_details' => 'required',
            'business_location_id' => 'required',
            'contact_id' => 'required',
            'status' => 'required',
        ], [
            'sale_details.required' => 'Sale Items are required!',
            'business_location_id.required' => 'Bussiness Location is required!',
            'contact_id.required' => 'Contact is required!',
        ])->validate();
        try {
            $sale_data = sales::create([
                'business_location_id' => $request->business_location_id,
                'contact_id' => $request->contact_id,
                'sale_amount' => $request->sale_amount,
                'status' => $request->status,
                'discount_type' => $request->discount_type,
                'discount_amount' => $request->discount_amount,
                'sale_expense' => $request->sale_expense,
                'total_sale_amount' => $request->total_sale_amount,
                'paid_amount' => $request->paid_amount,
                'balance' => $request->balance_amount,
                'sold_at' => now(),
                'sold_by' => Auth::user()->id,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);

            foreach ($sale_details as $sale_detail) {
                $stock = CurrentStockBalance::where('product_id', $sale_detail['product_id'])
                    ->with(['product' => function ($q) {
                        $q->select('id', 'name');
                    }])
                    ->where('variation_id', $sale_detail['variation_id'])
                    ->where('lot_no', $sale_detail['lot_no'])
                    ->get()->first()->toArray();
                $sale_details_data = [
                    'sales_id' => $sale_data->id,
                    'product_id' => $sale_detail['product_id'],
                    'purchase_detail_id' => $stock['transaction_detail_id'],
                    'current_stock_balance_id' => $stock['id'],
                    'variation_id' => $sale_detail['variation_id'],
                    'lot_no' => $sale_detail['lot_no'],
                    'expired_date' => $stock['expired_date'],
                    'uomset_id' => $stock['uomset_id'],
                    'quantity' => $sale_detail['quantity'],
                    'unit_id' => $sale_detail['unit_id'],
                    'sale_price_without_discount' => $sale_detail['sale_price_without_discount'],
                    'sale_price' => $sale_detail['sale_price'],
                    'discount_type' => $sale_detail['discount_type'],
                    'discount_amount' => $sale_detail['discount_amount'],
                ];
                $created_sale_details = sale_details::create($sale_details_data);
                $requestQty = UomHelper::smallestQty($stock['uomset_id'], $sale_detail['unit_id'], $sale_detail['quantity']);
                $changeQtyStatus = $this->changeStockQty($requestQty, $request->business_location_id, $stock, $sale_detail['lot_no'], $created_sale_details->id);

                if ($changeQtyStatus == false) {
                    return redirect()->back()->withInput()->with(['warning' => "Out of Stock In " . $stock['product']['name']]);
                } else {
                    if ($this->setting->lot_control == "off") {
                        $datas = $changeQtyStatus;
                        foreach ($datas as $data) {
                            $sale_details_data['parent_sale_details_id'] = $created_sale_details->id;
                            $resultQty = UomHelper::convertQtyByUnit($data['smallest_unit'], $sale_detail['unit_id'], $stock['uomset_id'], $data['stockQty']);
                            $sale_details_data['lot_no'] = $data['lot_no'];
                            $sale_details_data['current_stock_balance_id'] = $data['stock_id'];
                            $sale_details_data['quantity'] = $resultQty;
                            sale_details::create($sale_details_data);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('all_sales', 'allSales')->with(['success' => 'successfully created sale']);
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
        }
    }


    public function changeStockQty($requestQty, $business_location_id, $current_stock, $lot_no, $sale_detail_id)
    {
        $current_stock_id = $current_stock['id'];
        $product_id = $current_stock['product_id'];
        $variation_id = $current_stock['variation_id'];

        $currentStock = CurrentStockBalance::where('business_location_id', $business_location_id)
            ->where('product_id', $product_id)
            ->where('variation_id', $variation_id)
            ->where('id', $current_stock_id)
            ->where('lot_no', $lot_no);
        // dd($currentStock->get()->toArray());
        $current_stock_qty =  $currentStock->first()->current_quantity;


        // check lot control from setting
        $lot_control = businessSettings::select('lot_control')->first()->lot_control;
        if ($lot_control == 'off') {

            $totalStocks = CurrentStockBalance::select('id', 'current_quantity')->where('product_id', $product_id)->where('current_quantity', '>', '0')->sum('current_quantity');
            if ($requestQty > $totalStocks) {
                return false;
            } else {
                $stocks = CurrentStockBalance::where('product_id', $product_id)->where('variation_id', $variation_id)->where('current_quantity', '>', '0')->get();
                $qtyToRemove = $requestQty;
                $data = [];
                foreach ($stocks as  $stock) {
                    $stockQty = $stock->current_quantity;

                    // prepare data for stock history
                    $stock_history_data = [
                        'business_location_id' => $stock['business_location_id'],
                        'product_id' => $stock['product_id'],
                        'variation_id' => $stock['variation_id'],
                        'lot_no' => $stock['lot_no'],
                        'expired_date' => $stock['expired_date'],
                        'transaction_type' => 'sale',
                        'transaction_details_id' => $sale_detail_id,
                        'increase_qty' => 0,
                        'smallest_unit_id' => $stock['smallest_unit_id'],
                    ];

                    //remove qty from current stock
                    if ($qtyToRemove > $stockQty) {
                        $data[] = [
                            'stockQty' => $stockQty,
                            'lot_no' => $stock->lot_no,
                            'smallest_unit' => $stock->smallest_unit_id,
                            'stock_id' => $stock->id
                        ];
                        $qtyToRemove -= $stockQty;
                        CurrentStockBalance::where('id', $stock->id)->first()->update([
                            'current_quantity' => 0,
                        ]);
                        $stock_history_data['decrease_qty'] = $stockQty;
                        stock_history::create($stock_history_data);
                    } else {
                        $leftStockQty = $stockQty - $qtyToRemove;
                        $data[] = [
                            'stockQty' => $qtyToRemove,
                            'lot_no' => $stock->lot_no,
                            'smallest_unit' => $stock->smallest_unit_id,
                            'stock_id' => $stock->id
                        ];
                        $stock_history_data['decrease_qty'] = $qtyToRemove;
                        $qtyToRemove = 0;
                        CurrentStockBalance::where('id', $stock->id)->first()->update([
                            'current_quantity' => $leftStockQty,
                        ]);
                        stock_history::create($stock_history_data);
                        break;
                    }
                };
                return $data;
            }
        } else {
            if ($requestQty > $current_stock_qty) {
                return false;
            } else {
                $left_qty = $current_stock_qty - $requestQty;
                $currentStock->update([
                    'current_quantity' => $left_qty
                ]);
                $currentStockData = $currentStock->get()->first()->toArray();
                stock_history::create([
                    'business_location_id' => $currentStockData['business_location_id'],
                    'product_id' => $currentStockData['product_id'],
                    'variation_id' => $currentStockData['variation_id'],
                    'lot_no' => $currentStockData['lot_no'],
                    'expired_date' => $currentStockData['expired_date'],
                    'transaction_type' => 'sale',
                    'transaction_details_id' => $sale_detail_id,
                    'increase_qty' => 0,
                    'decrease_qty' => $requestQty,
                    'smallest_unit_id' => $currentStockData['smallest_unit_id'],
                ]);
                return true;
            }
        }
    }



    public function update($id, Request $request)
    {
        $request_sale_details = $request->sale_details;
        $lot_control = $this->setting->lot_control;
        $sales = sales::where('id', $id)->first();
        DB::beginTransaction();
        try {
            $sales->update([
                'contact_id' => $request->contact_id,
                'sale_amount' => $request->sale_amount,
                'status' => $request->status,
                'discount_type' => $request->discount_type,
                'discount_amount' => $request->discount_amount,
                'sale_expense' => $request->sale_expense,
                'total_sale_amount' => $request->total_sale_amount,
                'paid_amount' => $request->paid_amount,
                'balance' => $request->balance_amount,
                'updated_by' => Auth::user()->id,
            ]);
            // begin sale_detail_update
            if ($request_sale_details) {
                //get old sale_details
                $request_old_sale_details = array_filter($request_sale_details, function ($item) {
                    return isset($item['sale_detail_id']);
                });

                // get old sale_details ids from client [1,2]
                $request_old_sale_details_ids = array_column($request_old_sale_details, 'sale_detail_id');

                // update sale detail's data and related current stock
                foreach ($request_old_sale_details as $request_old_sale) {

                    if (count($request_old_sale) <= 1) {
                        continue;
                    };

                    $sale_detail_id = $request_old_sale['sale_detail_id'];
                    unset($request_old_sale["sale_detail_id"]);
                    $request_old_sale['updated_by'] = Auth::user()->id;

                    $sale_details = sale_details::where('id', $sale_detail_id)->where('is_delete', 0)->first();


                    //get old sale_detail qty from db
                    $sale_detial_qty_from_db = UomHelper::smallestQty($sale_details->uomset_id, $sale_details->unit_id, $sale_details->quantity);

                    // smallest qty from client
                    $requestQty = UomHelper::smallestQty($sale_details->uomset_id, $request_old_sale['unit_id'], $request_old_sale['quantity']);

                    $dif_sale_qty = $requestQty - $sale_detial_qty_from_db;


                    $currentStockFromReq = CurrentStockBalance::where('business_location_id', $sales->business_location_id)
                        ->where('id', $sale_details->current_stock_balance_id)
                        ->where('lot_no', $sale_details->lot_no);
                    if ($currentStockFromReq->get()->first() == null) {
                        $product_name = Product::where('id', $request_old_sale['product_id'])->get()->first()->name;
                        return redirect()->back()->with(['warning' => "There is no $product_name stock for this location "]);
                    }
                    if ($lot_control == "off") {
                        $saleDetailsByParent = sale_details::where('parent_sale_details_id', $sale_detail_id)->where('is_delete', 0)->get();
                        $saleDetailQty = UomHelper::smallestQty($sale_details->uomset_id, $sale_details->unit_id, $sale_details->quantity);

                        $request_sale_details_data = [
                            'sales_id' => $sale_details['sales_id'],
                            'product_id' => $sale_details['product_id'],
                            'parent_sale_details_id' => $sale_details['id'],
                            'variation_id' => $sale_details['variation_id'],
                            'purchase_detail_id' => $sale_details['purchase_detail_id'],
                            'expired_date' => $sale_details['expired_date'],
                            'uomset_id' => $sale_details['uomset_id'],
                            'unit_id' => $sale_details['unit_id'],
                            'sale_price_without_discount' => $request_old_sale['sale_price_without_discount'],
                            'discount_type' => $request_old_sale['discount_type'],
                            'discount_amount' => $request_old_sale['discount_amount'],
                            'sale_price' => $request_old_sale['sale_price'],
                        ];
                        if (count($saleDetailsByParent) > 0) {
                            if ($request_old_sale['quantity'] > $sale_details->quantity) {
                                $current_stock = CurrentStockBalance::where('product_id', $sale_details->product_id)->where('variation_id', $sale_details->variation_id)->where('current_quantity', '>', '0');
                                $availableStocks = $current_stock->get();
                                $availableQty = $current_stock->sum('current_quantity');
                                $newQty = round($requestQty - $sale_detial_qty_from_db, 2);
                                if ($newQty > $availableQty) {
                                    return redirect()->route('all_sales', 'allSales')->with(['warning' => 'out of stock']);
                                }
                                $qtyToRemove = $request_old_sale['quantity'] - $sale_details->quantity;
                                foreach ($availableStocks as $stock) {
                                    $stockQty = round(UomHelper::convertQtyByUnit($stock->smallest_unit_id, $sale_details->unit_id, $stock->uomset_id, $stock->current_quantity), 2);
                                    if ($qtyToRemove > $stockQty) {
                                        CurrentStockBalance::where('id', $stock->id)->first()->update([
                                            'current_quantity' => 0,
                                        ]);

                                        $sale_details_by_stock = sale_details::where('parent_sale_details_id', $sale_detail_id)->where('is_delete', 0)->where('current_stock_balance_id', $stock->id);
                                        if ($sale_details_by_stock->exists()) {
                                            $sale_detial_qty = $sale_details_by_stock->first()->quantity;
                                            $sale_details_by_stock->update([
                                                'quantity' => $sale_detial_qty + $qtyToRemove,
                                            ]);
                                        } else {
                                            $request_sale_details_data['lot_no'] = $stock->lot_no;
                                            $request_sale_details_data['current_stock_balance_id'] = $stock->id;
                                            $request_sale_details_data['quantity'] = $qtyToRemove;
                                            sale_details::create($request_sale_details_data);
                                        }
                                        $qtyToRemove -= $stockQty;
                                    } else {
                                        $leftStockQty = $stockQty - $qtyToRemove;
                                        $stock_for_update = CurrentStockBalance::where('id', $stock->id)->first();
                                        $smallest_leftStockQty = UomHelper::smallestQty($sale_details->uomset_id, $sale_details->unit_id, $leftStockQty);

                                        $stock_for_update->update([
                                            'current_quantity' => $smallest_leftStockQty,
                                        ]);

                                        $sale_details_by_stock = sale_details::where('parent_sale_details_id', $sale_detail_id)->where('is_delete', 0)->where('current_stock_balance_id', $stock->id);

                                        if ($sale_details_by_stock->exists()) {
                                            $sale_detial_qty = $sale_details_by_stock->first()->quantity;
                                            $sale_details_by_stock->update([
                                                'quantity' => $sale_detial_qty + $qtyToRemove,
                                            ]);
                                        } else {
                                            $request_sale_details_data['lot_no'] = $stock->lot_no;
                                            $request_sale_details_data['current_stock_balance_id'] = $stock->id;
                                            $request_sale_details_data['quantity'] = $qtyToRemove;
                                            // dd($request_sale_details_data);
                                            sale_details::create($request_sale_details_data);
                                        }
                                        $qtyToRemove = 0;
                                        break;
                                    }
                                }
                            } elseif ($request_old_sale['quantity'] < $sale_details->quantity) {

                                $qty_to_replace = $sale_details->quantity - $request_old_sale['quantity'];
                                foreach ($saleDetailsByParent as $sdp) {

                                    if ($qty_to_replace >= $sdp->quantity) {
                                        sale_details::where('id', $sdp->id)->first()->update([
                                            'quantity' => 0,
                                            'is_delete' => 1,
                                            'deleted_at' => now(),
                                            'deleted_by' => Auth::user()->id,
                                        ]);
                                        $smallest_qty = UomHelper::smallestQty($sdp->uomset_id, $sdp->unit_id, $sdp->quantity);
                                        $current_stock = CurrentStockBalance::where('id', $sdp->current_stock_balance_id)->first();
                                        // dd($current_stock->toArray());
                                        $current_stock->update([
                                            'current_quantity' =>  $current_stock->current_quantity + $smallest_qty,
                                        ]);
                                        $qty_to_replace -= $sdp->quantity;
                                        if ($qty_to_replace <= 0) {
                                            break;
                                        }
                                    } elseif ($qty_to_replace < $sdp->quantity) {
                                        $smallest_qty = UomHelper::smallestQty($sdp->uomset_id, $sdp->unit_id, $qty_to_replace);
                                        $current_stock = CurrentStockBalance::where('id', $sdp->current_stock_balance_id)->first();

                                        sale_details::where('id', $sdp->id)->first()->update([
                                            'quantity' => $sdp->quantity - $qty_to_replace,
                                        ]);
                                        $current_stock->update([
                                            'current_quantity' =>  $current_stock->current_quantity + $smallest_qty,
                                        ]);
                                        $qty_to_replace = 0;
                                        break;
                                    }
                                }

                                // dd($saleDetailsByParent->toArray());
                            }
                        }
                    } else {
                        $currentQty = $currentStockFromReq->get()->first()->current_quantity;
                        $left_qty = $currentQty - $dif_sale_qty;
                        if (round($dif_sale_qty, 4) > round($currentQty, 4)) {
                            return redirect()->back()->with(['warning' => 'Out of Stock']);
                        } else {
                            $currentStockFromReq->update([
                                'current_quantity' => $left_qty
                            ]);
                        }
                    }
                    if ($sale_details->parent_sale_details_id) {
                        $parent_sale_detail = sale_details::where('id', $sale_details->parent_sale_details_id)->first();
                        $parent_sale_detail_qty = $parent_sale_detail->quantity - $sale_details->quantity;
                        $parent_sale_detail->update(['quantity' => $parent_sale_detail_qty + $request_old_sale['quantity']]);
                    };
                    $request_sale_details_data = [
                        'quantity' => $request_old_sale['quantity'],
                        'sale_price_without_discount' => $request_old_sale['sale_price_without_discount'],
                        'discount_type' => $request_old_sale['discount_type'],
                        'discount_amount' => $request_old_sale['discount_amount'],
                        'sale_price' => $request_old_sale['sale_price'],
                        'updated_by' => $request_old_sale['updated_by'],
                    ];
                    if ($request_old_sale['quantity'] <= 0) {
                        $request_sale_details_data['is_delete']  = 1;
                        $request_sale_details_data['deleted_at']  = now();
                        $request_sale_details_data['is_delete']  = Auth::user()->id;
                        $sale_details->update($request_sale_details_data);
                    } else {

                        $sale_details->update($request_sale_details_data);
                    };
                    stock_history::where('transaction_details_id', $sale_detail_id)->where('transaction_type', 'sale')->update([
                        'decrease_qty' => $requestQty,
                    ]);
                }

                //get added sale_details_ids from database
                $fetch_sale_details = sale_details::where('sales_id', $id)->where('is_delete', 0)->select('id')->get()->toArray();
                $get_fetched_sale_details_id = array_column($fetch_sale_details, 'id');
                //to remove edited sale_detais that are already created
                $request_old_sale_details_id_for_delete = array_diff($get_fetched_sale_details_id, $request_old_sale_details_ids); //for delete row
                // sale_details for delete
                foreach ($request_old_sale_details_id_for_delete as $sale_detail_id) {

                    if ($this->setting->lot_control == 'off') {
                        $sale_details = sale_details::where('id', $sale_detail_id)->whereNull('parent_sale_details_id')->where('is_delete', 0);
                    } else {
                        $sale_details = sale_details::where('id', $sale_detail_id)->where('is_delete', 0);
                    }
                    $sale_details_count = count($sale_details->get()->toArray());
                    if ($sale_details_count > 0) {
                        $sale_detail_data = $sale_details->get()->first();
                        $request_old_sale_detail_qty = UomHelper::smallestQty($sale_detail_data->uomset_id, $sale_detail_data->unit_id, $sale_detail_data->quantity);
                        $currentStock = CurrentStockBalance::where('id', $sale_detail_data->current_stock_balance_id);
                        $current_stock_qty = $currentStock->get()->first()->current_quantity;
                        $result = $current_stock_qty + $request_old_sale_detail_qty;
                        $currentStock->update(['current_quantity' => $result]);
                        $get_sale_details = $sale_details->first();
                        if ($get_sale_details->parent_sale_details_id) {
                            $parent_sale_detail = sale_details::where('id', $get_sale_details->parent_sale_details_id)->first();
                            $parent_sale_detail_qty = $parent_sale_detail->quantity - $get_sale_details->quantity;
                            $parent_sale_detail->update(['quantity' => $parent_sale_detail_qty + $request_old_sale['quantity']]);
                        };
                        $sale_details->update([
                            'is_delete' => 1,
                            'deleted_at' => now(),
                            'deleted_by' => Auth::user()->id,
                        ]);
                    }
                }

                //to create sale details
                $new_sale_details = array_filter($request_sale_details, function ($item) {
                    return !isset($item['sale_detail_id']);
                });
                if (count($new_sale_details) > 0) {
                    foreach ($new_sale_details as $sale_detail) {
                        $stock = CurrentStockBalance::where('product_id', $sale_detail['product_id'])
                            ->where('variation_id', $sale_detail['variation_id'])
                            ->where('lot_no', $sale_detail['lot_no'])
                            ->with('product')
                            ->get()->first()->toArray();

                        $sale_details_data = [
                            'sales_id' => $id,
                            'product_id' => $sale_detail['product_id'],
                            'purchases_detail_id' => $stock['transaction_detail_id'],
                            'current_stock_balance_id' => $stock['id'],
                            'variation_id' => $sale_detail['variation_id'],
                            'lot_no' => $sale_detail['lot_no'],
                            'expired_date' => $stock['expired_date'],
                            'uomset_id' => $stock['uomset_id'],
                            'quantity' => $sale_detail['quantity'],
                            'unit_id' => $sale_detail['unit_id'],
                            'sale_price_without_discount' => $sale_detail['sale_price_without_discount'],
                            'sale_price' => $sale_detail['sale_price'],
                            'discount_type' => $sale_detail['discount_type'],
                            'discount_amount' => $sale_detail['discount_amount'],
                        ];
                        $sale_detail = sale_details::create($sale_details_data);
                        $requestQty = UomHelper::smallestQty($stock['uomset_id'], $sale_detail['unit_id'], $sale_detail['quantity']);
                        $changeQtyStatus = $this->changeStockQty($requestQty, $stock['business_location_id'], $stock, $sale_detail['lot_no'], $sale_detail->id);

                        if ($changeQtyStatus == false) {
                            return redirect()->back()->withInput()->with(['warning' => "Out of Stock In " . $stock['product']['name']]);
                        } else {
                            if ($this->setting->lot_control == "off") {
                                $datas = $changeQtyStatus;
                                foreach ($datas as $data) {
                                    $sale_details_data['parent_sale_details_id'] = $sale_detail->id;
                                    $resultQty = UomHelper::convertQtyByUnit($data['smallest_unit'], $sale_detail['unit_id'], $stock['uomset_id'], $data['stockQty']);
                                    $sale_details_data['lot_no'] = $data['lot_no'];
                                    $sale_details_data['current_stock_balance_id'] = $data['stock_id'];
                                    $sale_details_data['quantity'] = $resultQty;
                                    sale_details::create($sale_details_data);
                                }
                            }
                        }
                    }
                }
            } else {
                $sales_details = sale_details::where('sales_id', $id)->whereNull('parent_sale_details_id')->where('is_delete', '0')->get()->toArray();
                foreach ($sales_details as $sale_detail) {
                    if ($this->setting->lot_control == 'off') {
                        $saleDetailsByParent = sale_details::where('parent_sale_details_id', $sale_detail['id'])->where('is_delete', 0)->get();
                        $qty_to_replace = $sale_detail['quantity'];
                        foreach ($saleDetailsByParent as $sdp) {
                            if ($qty_to_replace >= $sdp->quantity) {
                                $smallest_qty = UomHelper::smallestQty($sdp->uomset_id, $sdp->unit_id, $sdp->quantity);
                                $current_stock = CurrentStockBalance::where('id', $sdp->current_stock_balance_id)->first();
                                $current_stock->update([
                                    'current_quantity' =>  $current_stock->current_quantity + $smallest_qty,
                                ]);
                                $qty_to_replace -= $sdp->quantity;
                                if ($qty_to_replace <= 0) {
                                    break;
                                }
                            } elseif ($qty_to_replace < $sdp->quantity) {
                                $smallest_qty = UomHelper::smallestQty($sdp->uomset_id, $sdp->unit_id, $qty_to_replace);
                                $current_stock = CurrentStockBalance::where('id', $sdp->current_stock_balance_id)->first();
                                $current_stock->update([
                                    'current_quantity' =>  $current_stock->current_quantity + $smallest_qty,
                                ]);
                                $qty_to_replace = 0;
                                break;
                            }
                        }
                    } else {
                        $currentStock = CurrentStockBalance::where('id', $sale_detail['current_stock_balance_id']);
                        $current_stock_qty = $currentStock->get()->first()->current_quantity;
                        $sale_detail_qty = UomHelper::smallestQty($sale_detail['uomset_id'], $sale_detail['unit_id'], $sale_detail['quantity']);
                        $result = $current_stock_qty + $sale_detail_qty;
                        $currentStock->update(['current_quantity' => $result]);
                    }
                }
                sale_details::where('sales_id', $id)->update([
                    'is_delete' => 1,
                    'deleted_at' => now(),
                    'deleted_by' => Auth::user()->id,
                ]);
                sales::where('id', $id)->update([
                    'is_delete' => 1,
                    'deleted_at' => now(),
                    'deleted_by' => Auth::user()->id,
                ]);
            }



            DB::commit();
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
        }
        // dd($request->toArray());
        return redirect()->route('all_sales', 'allSales')->with(['success' => 'successfully updated']);
    }


    public function softDelete($id)
    {
        sales::where('id', $id)->update([
            'is_delete' => 1,
            'deleted_by' => Auth::user()->id,
            'deleted_at' => now()
        ]);
        $data = [
            'success' => 'Successfully Deleted'
        ];
        return response()->json($data, 200);
    }
    public function softSelectedDelete()
    {
        $ids = request('data');
        DB::beginTransaction();
        try {
            foreach ($ids as $id) {
                sales::where('id', $id)->update([
                    'is_delete' => 1,
                    'deleted_by' => Auth::user()->id,
                    'deleted_at' => now()
                ]);
            }
            $data = [
                'success' => 'Successfully Deleted'
            ];

            DB::commit();
            return response()->json($data, 200);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
            return response()->json($e, 200);
        }
    }

    public function getProduct(Request $request)
    {
        $business_location_id = $request->data['business_location_id'];
        $q = $request->data['query'];
        $variation_id = $request->data['variation_id'] ?? null;

        $products = Product::select('id', 'name', 'product_code', 'sku', 'product_type', 'uom_id', 'purchase_uom_id')
            ->where('name', 'like', '%' . $q . '%')
            ->orWhere('sku', 'like', '%' . $q . '%')

            ->with([
                'productVariations' => function ($query) use ($variation_id) {
                    $query->select('id', 'product_id', 'variation_template_value_id', 'default_purchase_price', 'default_selling_price')
                        ->when($variation_id, function ($query) use ($variation_id) {
                            $query->where('id', $variation_id);
                        })
                        ->with(['variationTemplateValue' => function ($query) {
                            $query->select('id', 'name');
                        }]);
                },
                'stock' => function ($query) use ($business_location_id) {
                    $query->where('current_quantity', '>', 0)
                        ->where('business_location_id', $business_location_id);
                }, 'uom' => function ($q) {
                    $q->with(['unit_category' => function ($q) {
                        $q->with('uomByCategory');
                    }]);
                }
            ])
            ->withSum(['stock' => function ($q) use ($business_location_id) {
                $q->where('business_location_id', $business_location_id);
            }], 'current_quantity')
            ->get()
            ->map(function ($product) {
                $lotNos = $product->stock->pluck('lot_no')->toArray();
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'product_code' => $product->product_code,
                    'sku' => $product->sku,
                    'product_type' => $product->product_type,
                    'uom_id' => $product->uom_id,
                    'uom' => $product->uom,
                    'purchase_uom_id' => $product->purchase_uom_id,
                    'product_variations' => $product->product_type == 'single' ? $product->productVariations->toArray()[0] : $product->productVariations->toArray(),
                    'total_current_stock_qty' => $product->stock_sum_current_quantity ?? 0,
                    'lot_nos' => $lotNos,
                    'stock' => $product->stock->toArray(),
                ];
            });
        foreach ($products as $product) {
            if ($product['product_type'] == 'variable') {
                $product_variation = $product['product_variations'];
                foreach ($product_variation as $variation) {
                    $lot_nos = [];
                    $uom_sets = [];
                    $total_current_stock_qty = 0;
                    $stocks = array_filter($product['stock'], function ($s) use ($variation) {
                        return $s['variation_id'] == $variation['id'] && $s['current_quantity'] > 0;
                    });
                    foreach ($stocks as $stock) {
                        $total_current_stock_qty += $stock['current_quantity'];
                        $no = $stock['lot_no'];
                        $lot_nos[] = $no;
                        $uom_sets[] = $stock['uom_set'];
                    }
                    $variation_product = [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'sku' => $product['sku'],
                        'smallest_unit' => $product['smallest_unit'],
                        'samllest_units_id' => $product['samllest_units_id'],
                        'lot_nos' => $lot_nos,
                        'total_current_stock_qty' => $total_current_stock_qty,
                        'stock' => [...$stocks],
                        'uom_sets' => [...$uom_sets],
                        'product_variations' => $variation,
                        // 'uom_set'=>$product['uom_set'],
                        'variation_id' => $variation['id'],
                        'product_type' => 'sub_variable',
                        'variation_name' => $variation['variation_template_value']['name'],
                    ];
                    $products[] = $variation_product;
                }
            }
        }
        return response()->json($products, 200);
    }

    public function getStock(Request $request)
    {
        $lot_no = $request->lotNo;
        $data = CurrentStockBalance::where('lot_no', $lot_no)
            ->with(['uomSet' => function ($q) {
                $q->with('units', 'uom_sellingprices')->select('id', 'uomset_name');
            }, 'smallest_unit' => function ($q) {
                $q->select('id', 'name');
            }])
            ->get()->first();
        return response()->json($data, 200);
    }

    public function getSellingPrice(Request $request)
    {
        $lot_no = $request->lot_no;
        $unit_id = $request->unit_id;
        $price_group = $request->price_group;
        $stock_data = CurrentStockBalance::where('lot_no', $lot_no)->select('variation_id', 'uomset_id', 'current_quantity')->get()->first()->toArray();
        if ($price_group !== 'default_selling_price') {
            $getUom = UOM::where('uomset_id', $stock_data['uomset_id'])->where('unit_id', $unit_id)->select('id')->get()->first()->toArray();
            $sellingPrice = UOMSellingprice::where('product_variation_id', $stock_data['variation_id'])
                ->select('price_inc_tax')
                ->where('uom_id', $getUom['id'])
                ->where('pricegroup_id', $price_group)
                ->get()->first()->toArray();
        } else if ($price_group === 'default_selling_price') {
            if ($stock_data['variation_id'] <= 0) {
                $sellingPrice = 0;
            } else {
                $sellingPrice = ProductVariation::where('id', $stock_data['variation_id'])->select('default_selling_price')->first()->toArray();
            }
        }
        $data = ['sellingPrice' => $sellingPrice, 'current_quantity' => $stock_data['current_quantity']];
        return response()->json($data, 200);
    }


    public function getCurrentQtyOnUnit(Request $request)
    {

        $setting = businessSettings::select('lot_control')->first()->lot_control;
        $new_unit_id = $request->unit_id;
        $lot_no = $request->lot_no;
        $product_id = $request->product_id;
        $variation_id = $request->variation_id;
        $stock_data = CurrentStockBalance::where('lot_no', $lot_no)
            ->where('product_id', $product_id)
            ->where('variation_id', $variation_id)
            ->select('smallest_unit_id', 'uomset_id', 'current_quantity')
            ->get()->first()->toArray();
        if ($setting == 'off') {
            $allQty = CurrentStockBalance::select('smallest_unit_id', 'uomset_id', 'current_quantity')
                ->where('product_id', $product_id)
                ->where('variation_id', $variation_id)
                ->get()->sum('current_quantity');
            $result = UomHelper::convertQtyByUnit($stock_data['smallest_unit_id'], $new_unit_id, $stock_data['uomset_id'], $allQty);
        } else {
            $result = UomHelper::convertQtyByUnit($stock_data['smallest_unit_id'], $new_unit_id, $stock_data['uomset_id'], $stock_data['current_quantity']);
        }

        return response()->json($result, 200);
    }

    public function saleInvoice($id)
    {
        $sale = sales::with('business_location_id', 'sold_by', 'confirm_by', 'customer', 'updated_by')->where('id', $id)->first()->toArray();

        $location = $sale['business_location_id'];


        $sale_details = sale_details::with(['productVariation' => function ($q) {
            $q->select('id', 'product_id', 'variation_template_value_id')
                ->with([
                    'product' => function ($q) {
                        $q->select('id', 'name', 'product_type', 'unit_id');
                    },
                    'variationTemplateValue' => function ($q) {
                        $q->select('id', 'name');
                    }
                ]);
        }, 'uomSet', 'unit', 'product'])
            ->leftJoin('uom_sets', 'sale_details.uomset_id', '=', 'uom_sets.id')
            ->select('sale_details.*', 'uom_sets.uomset_name')
            ->where('sales_id', $id)->where('is_delete', 0)->get();
        $invoiceHtml = view('App.sell.print.saleInvoice3', compact('sale', 'location', 'sale_details'))->render();
        return response()->json(['html' => $invoiceHtml]);
    }

    public function getLotByUom(Request $request)
    {
        $product_id = $request->product_id;
        $uomset_id = $request->uomset_id;
        $variation_id = $request->variation_id;
        $results = CurrentStockBalance::where('product_id', $product_id)
            ->where('uomset_id', $uomset_id)
            ->where('variation_id', $variation_id)
            ->where('current_quantity', '>', '0')
            ->get()->toArray();

        return response()->json($results, 200);
    }
}
