<?php

namespace App\Http\Controllers\sell;

use Error;
use DateTime;
use stdClass;
use Exception;
use App\Models\resOrders;
use App\Helpers\UomHelper;
use App\Models\Currencies;
use App\Models\res_orders;
use App\Models\sale\sales;
use App\Helpers\UomHelpers;
use App\Models\Product\UOM;
use App\Models\posRegisters;
use Illuminate\Http\Request;
use App\Models\stock_history;
use Illuminate\Support\Carbon;
use App\Models\Contact\Contact;
use App\Models\paymentAccounts;
use App\Models\Product\Product;
use App\Models\lotSerialDetails;
use App\Helpers\generatorHelpers;
use App\Models\sale\sale_details;
use App\Models\Product\PriceGroup;
use App\Models\Product\PriceLists;
use Illuminate\Support\Facades\DB;
use App\Models\CurrentStockBalance;
use App\Models\purchases\purchases;
use App\Http\Controllers\Controller;
use App\Models\paymentsTransactions;
use Illuminate\Support\Facades\Auth;
use App\Models\hospitalRoomSaleDetails;
use App\Models\Product\UOMSellingprice;
use App\Models\Product\PriceListDetails;
use App\Models\Product\ProductVariation;
use Yajra\DataTables\Facades\DataTables;
use App\Models\settings\businessLocation;
use App\Models\settings\businessSettings;
use Illuminate\Support\Facades\Validator;
use App\Models\purchases\purchase_details;
use Modules\Reservation\Entities\Reservation;
use App\Models\posSession\posRegisterSessions;
use Modules\Reservation\Entities\FolioInvoice;
use Modules\Reservation\Entities\FolioInvoiceDetail;
use App\Http\Controllers\posSession\posSessionController;
use App\Models\posRegisterTransactions;
use Modules\HospitalManagement\Entities\hospitalFolioInvoices;
use Modules\HospitalManagement\Entities\hospitalRegistrations;
use Modules\HospitalManagement\Entities\hospitalFolioInvoiceDetails;

class saleController extends Controller
{
    private $setting;
    private $currency;
    private $accounting_method;
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
        $this->middleware('canView:sell')->only(['index', 'saleItemsList']);
        $this->middleware('canCreate:sell')->only(['createPage', 'store']);
        $this->middleware('canUpdate:sell')->only(['saleEdit', 'update']);
        $this->middleware('canDelete:sell')->only('softDelete', 'softSelectedDelete');
        $settings = businessSettings::select('lot_control', 'currency_id','accounting_method','enable_line_discount_for_sale')->with('currency')->first();
        $this->setting = $settings;
        $this->currency = $settings->currency;
        $this->accounting_method=$settings->accounting_method;

    }

    //
    public function index()
    {
        $locations=businessLocation::select('name','id')->get();
        $customers = contact::where('type', 'Customer')->get();
        // dd($customers);
        return view('App.sell.sale.allSales',compact('locations','customers'));
    }
    public function saleItemsList(Request $request)
    {
        $saleItems = sales::where('is_delete', 0)->orderBy('id','DESC')->with('business_location_id', 'customer');
        if($request->filled('form_data') && $request->filled('to_date')){
            $saleItems=$saleItems->whereDate('created_at', '>=', $request->form_data)->whereDate('created_at', '<=',$request->to_date);
        }
        $saleItems=$saleItems->get();
        return DataTables::of($saleItems)
            ->editColumn('saleItems',function($saleItems){
                $saleDetails=$saleItems->sale_details;
                $items='';
                foreach ($saleDetails as $key => $sd) {
                    $variation=$sd->productVariation;
                    $productName=$variation->product->name;
                    $sku=$variation->product->sku ?? '';
                    $variationName=$variation->variationTemplateValue->name ?? '';
                    $items.="$productName,$variationName,$sku ;";
                }
                return $items;
            })
            ->addColumn('checkbox', function ($saleItem) {
                return
                    '
                    <div class="form-check form-check-sm form-check-custom ">
                        <input class="form-check-input" type="checkbox" data-checked="delete" value=' . $saleItem->id . ' />
                    </div>
                ';
            })
            ->editColumn('customer', function ($saleItem) {
                if($saleItem->customer){
                 return $saleItem->customer['company_name'] ?? $saleItem->customer['first_name'].' '. $saleItem->customer['middle_name'] . ' ' .  $saleItem->customer['last_name'];
                }
                return '';
            })
            ->editColumn('sold_at', function ($saleItem) {
                return fDate($saleItem->sold_at,true);
            })
            ->editColumn('sale_amount', function ($saleItem) {
                return price($saleItem->sale_amount,$saleItem->currency_id);
            })
            ->editColumn('status', function ($purchase) {
                $html = '';
                if ($purchase->status == 'delivered') {
                    $html = "<span class='badge badge-success'> $purchase->status </span>";
                } elseif ($purchase->status == 'draft') {
                    $html = "<span class='badge badge-dark'>$purchase->status</span>";
                } elseif ($purchase->status == 'pending') {
                    $html = "<span class='badge badge-warning'>$purchase->status</span>";
                } elseif ($purchase->status == 'order') {
                    $html = "<span class='badge badge-primary'>$purchase->status</span>";
                } elseif ($purchase->status == 'partial') {
                    $html = "<span class='badge badge-info'>$purchase->status</span>";
                } elseif ($purchase->status == 'quotation') {
                    $html = "<span class='badge badge-secondary'>$purchase->status</span>";
                }
                return $html;
                // return $purchase->supplier['company_name'] ?? $purchase->supplier['first_name'];
            })
            ->addColumn('action', function ($saleItem) {
                $postTo='';
                if (hasModule('HospitalManagement') && isEnableModule('HospitalManagement')){
                    $postTo = '<a type="button" class="dropdown-item p-2 edit-unit  postToRegisterationFolio" data-href="'.route('postToRegistrationFolio', $saleItem->id).'">Post to Folio</a>';
                }
                $html = '
                    <div class="dropdown text-center">
                        <button class="btn btn-sm btn-light btn-primary fw-semibold fs-7  dropdown-toggle " type="button" id="saleItemDropDown" data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                        </button>
                        <div class="z-3">
                        <ul class="dropdown-menu z-10 p-5 " aria-labelledby="saleItemDropDown" role="menu">';
                            if(hasView('sell')){
                                $html .= ' <a class="dropdown-item p-2   view_detail"   type="button" data-href="' . route('saleDetail', $saleItem->id) . '">
                                view
                            </a>';
                            }
                            if (hasUpdate('sell')){
                                $html .= ' <a href="' . route('saleEdit', $saleItem->id) . '" class="dropdown-item p-2   edit-unit " >Edit</a>';
                            }
                            if (hasPrint('sell')){
                                $html .= '<a class="dropdown-item p-2  cursor-pointer  print-invoice"  data-href="' . route('print_sale', $saleItem->id) . '">print</a>';
                            }
                            if($saleItem->balance_amount>0){
                                $html.='<a class="dropdown-item p-2 cursor-pointer " id="paymentCreate"   data-href="'.route('paymentTransaction.createForSale',['id' => $saleItem->id,'currency_id'=>$saleItem->currency_id]).'">Add Payment</a>';
                            }
                            $html.='<a class="dropdown-item p-2 cursor-pointer " id="viewPayment"   data-href="'.route('paymentTransaction.viewForSell',$saleItem->id).'">View Payment</a>';
                            $html.=$postTo;

                            $html.='<a type="button" class="dropdown-item p-2  post-to-reservation" data-href="'.route('postToReservationFolio', $saleItem->id).'">Post to Reservation</a>';


                            if (hasDelete('sell')){
                                $html .= ' <a class="dropdown-item p-2  cursor-pointer bg-active-danger text-danger"  data-id="' . $saleItem->id . '" data-kt-saleItem-table="delete_row">Delete</a>';
                            }

                $html .= '</ul></div></div>';
                return (hasView('sell') && hasPrint('sell') && hasUpdate('sell') && hasDelete('sell') ? $html : 'No Access');
            })
            ->rawColumns(['action', 'checkbox','status','sold_at'])
            ->make(true);
    }

    public function saleDetail($id)
    {

        $sale = sales::with('business_location_id', 'sold_by', 'confirm_by', 'customer', 'updated_by','currency')->where('id', $id)->first()->toArray();

        $location = $sale['business_location_id'];
        $setting=$this->setting;
        $sale_details_query = sale_details::with(['productVariation' => function ($q) {
            $q->select('id', 'product_id', 'variation_template_value_id')
                ->with([
                    'product' => function ($q) {
                        $q->select('id', 'name', 'product_type');
                    },
                    'variationTemplateValue' => function ($q) {
                        $q->select('id', 'name');
                    }
                ]);
        },
        'product','uom', 'currency'
        ])
            ->where('sales_id', $id)->where('is_delete', 0);

        $sale_details= $sale_details_query->get();
        return view('App.sell.sale.details.saleDetail', compact(
            'sale',
            'location',
            'sale_details',
            'setting'
        ));

    }




    // sale create page
    public function createPage()
    {
        $locations = businessLocation::all();
        $products = Product::with('productVariations')->get();
        $customers = Contact::where('type', 'Customer')->get();
        $priceLists = PriceLists::select('id', 'name', 'description')->get();
        $paymentAccounts=paymentAccounts::get();
        $setting=businessSettings::first();
        $defaultCurrency=$this->currency;
        $currencies=Currencies::get();
        $locations = businessLocation::all();
        return view('App.sell.sale.addSale', compact('locations', 'products', 'customers', 'priceLists','setting','defaultCurrency','paymentAccounts','currencies'));
    }
    // for edit page
    public function saleEdit($id)
    {
        $locations = businessLocation::all();
        $products = Product::with('productVariations')->get();
        $customers = Contact::where('type', 'Customer')->get();
        $priceLists = PriceLists::select('id', 'name', 'description')->get();
        // $priceGroups = PriceGroup::select('id', 'name', 'description')->get();
        // $locations = businessLocation::all();
        $setting = businessSettings::first();
        $currency = $this->currency;


        $sale = sales::with('currency')->where('id', $id)->get()->first();
        $business_location_id=$sale->business_location_id;
        $sale_details_query = sale_details::with(['currency',
            'productVariation' => function ($q) {
                $q->select('id', 'product_id', 'variation_template_value_id', 'default_selling_price')
                    ->with([
                        'product' => function ($q) {
                            $q->select('id', 'name', 'product_type');
                        },
                        'variationTemplateValue' => function ($q) {
                            $q->select('id', 'name');
                        }, 'uomSellingPrice'
                    ]);
            },
            'stock'=>function($q) use($business_location_id) {
                $q->where('current_quantity', '>', 0)
                    ->where('business_location_id', $business_location_id);
            },
            'Currentstock',  'product'=>function($q){
                $q->with(['uom'=>function($q){
                    $q->with(['unit_category'=>function($q){
                        $q->with('uomByCategory');
                    }]);
                }]);
            },
        ])
        ->where('sales_id', $id)->where('is_delete', 0)
        ->withSum(['stock' => function ($q) use ($business_location_id) {
            $q->where('business_location_id', $business_location_id);
        }], 'current_quantity');
        $sale_details= $sale_details_query->get();
        $currencies=Currencies::get();
        $defaultCurrency=$this->currency;
        // $child_sale_details = $sale_details_query->whereNotNull('parent_sale_details_id', '!=', null)->get();
        // dd($sale_details->toArray());
        return view('App.sell.sale.edit', compact('products', 'customers', 'priceLists', 'sale', 'sale_details','setting','currency','currencies','defaultCurrency','locations'));
    }
    // sale store
    public function store(Request $request)
    {
        $sale_details = $request->sale_details;
        if($request->type=='pos'){
            $registeredPos=posRegisters::where('id',$request->pos_register_id)->select('id','payment_account_id','use_for_res')->first();
            $paymentAccountIds=json_decode($registeredPos->payment_account_id);
            $request['payment_account']=$paymentAccountIds[0];
            $request['currency_id']=$this->currency->id;
        }
        DB::beginTransaction();
        Validator::make($request->toArray(), [
            'sale_details' => 'required',
            'business_location_id' => 'required',
            'contact_id' => 'required',
            'status' => 'required',
        ],[
            'sale_details.required' => 'Sale Items are required!',
            'business_location_id.required' => 'Bussiness Location is required!',
            'contact_id.required' => 'Contact is required!',
        ])->validate();
        try {
            $lastSaleId = sales::orderBy('id', 'DESC')->select('id')->first()->id ?? 0;
                if($request->paid_amount == 0){
                    $payment_status='due';
                }elseif($request->paid_amount >= $request->total_sale_amount ){
                    $payment_status='paid';
                }else{
                    $payment_status='partial';
                }
                $sale_data = sales::create([
                    'business_location_id' => $request->business_location_id,
                    'sales_voucher_no' => sprintf('SVN-' . '%06d', ($lastSaleId + 1)),
                    'contact_id' => $request->contact_id,
                    'status' => $request->status,
                    'sale_amount' => $request->sale_amount,
                    'total_item_discount' => $request->total_item_discount,
                    'extra_discount_type' => $request->extra_discount_type,
                    'extra_discount_amount' => $request->extra_discount_amount,
                    'total_sale_amount' => $request->total_sale_amount,
                    'paid_amount' => $request->paid_amount,
                    'payment_status'=>$payment_status,
                    'pos_register_id'=>$request->pos_register_id ?? null,
                    'table_id'=>$request->table_id,
                    'balance_amount' => $request->balance_amount,
                    'currency_id' => $request->currency_id,
                    'sold_at' => now(),
                    'sold_by' => Auth::user()->id,
                    'created_by' => Auth::user()->id,
                ]);

                if($request->payment_account && $request->paid_amount >0){
                    $payemntTransaction=$this->makePayment($sale_data,$request->payment_account);
                }else{
                    $suppliers=Contact::where('id',$request->contact_id)->first();
                    $suppliers_receivable=$suppliers->receivable_amount;
                    $suppliers->update([
                        'receivable_amount'=>$suppliers_receivable+$request->balance_amount
                    ]);
                }
                $resOrderData=null;
                if($request->type=='pos' && $registeredPos->use_for_res == 1){
                    $resOrderData=$this->resOrderCreation($sale_data,$request);
                    $this->saleDetailCreation($request, $sale_data, $sale_details,$resOrderData);
                }else{
                    $this->saleDetailCreation($request, $sale_data, $sale_details);
                }
                DB::commit();


            if($request->type=='pos'){
                posRegisterTransactions::create([
                    'register_session_id'=>$request->sessionId,
                    'payment_account_id'=>$request->payment_account,
                    'transaction_type'=>'sale',
                    'transaction_id'=>$sale_data->id,
                    'transaction_amount'=>$request->total_sale_amount,
                    'currency_id'=>$request->currency_id,
                    'payment_transaction_id'=>$payemntTransaction->id?? null,
                ]);
                 return response()->json([
                    'data' => $sale_data->sales_voucher_no,
                    'status'=>'200',
                    'message'=>'successfully Created'
                 ], 200);
            }else{
                if($request->save =='save_&_print'){
                    return redirect()->route('all_sales')->with([
                        'success' => 'Successfully Created Purchase',
                        'print'=>$sale_data->id,
                    ]);
                }else{
                    return redirect()->route('all_sales')->with(['success' => 'Successfully Created Sale']);
                }
            }
        } catch (Exception $e) {
            logger($e);
            DB::rollBack();
            if($request->type=='pos'){
                return response()->json([
                   'status'=>'500',
                   'message'=>'Something wrong'
                ], 500);
           }else{
            return redirect()->back()->with(['warning'=>'Something Went Wrong While creating sale']);
           }
        }
    }
    public function resOrderCreation($sale_data,$request){
        return resOrders::create([
            'order_voucher_no'=>generatorHelpers::resOrderVoucherNo(),
            'order_status'=>'order',
            'location_id'=>$sale_data->business_location_id,
            'services'=>$request->services,
            'pos_register_id'=>$request->pos_register_id
        ]);

    }

    public function changeStockQty($requestQty, $business_location_id, $sale_detail, $current_stock=[])
    {
        $sale_detail_id=$sale_detail['id'];
        // check lot control from setting
        $lot_control = businessSettings::select('lot_control')->first()->lot_control;
        if($lot_control=='off'){
            $product_id = $sale_detail['product_id'];
            $variation_id = $sale_detail['variation_id'];
            $totalStocks = CurrentStockBalance::select('id', 'current_stock_id')
                        ->where('product_id', $product_id)
                        ->where('variation_id', $variation_id)
                        ->where('business_location_id', $business_location_id)
                        ->where('current_quantity', '>', '0')
                        ->sum('current_quantity');

            if($requestQty> $totalStocks){
                return false;
            }else{
                $stocks = CurrentStockBalance::where('product_id', $product_id)
                        ->where('variation_id', $variation_id)
                        ->where('business_location_id', $business_location_id)
                        ->where('current_quantity','>', '0');
                if($this->accounting_method=='lifo'){
                    $stocks=$stocks->orderBy('id','DESC')->get();
                }else{
                    $stocks=$stocks->get();
                }
                $qtyToRemove=$requestQty;
                // dd($requestQty);
                $data=[];
                foreach ($stocks as  $stock) {
                    $stockQty= $stock->current_quantity;
                    // prepare data for stock history
                    $stock_history_data = [
                        'business_location_id' => $stock['business_location_id'],
                        'product_id' => $stock['product_id'],
                        'variation_id' => $stock['variation_id'],
                        'expired_date' => $stock['expired_date'],
                        'transaction_type' => 'sale',
                        'transaction_details_id' => $sale_detail_id,
                        'increase_qty' => 0,
                        'ref_uom_id' => $stock['ref_uom_id'],
                    ];

                    //remove qty from current stock
                    if($qtyToRemove > $stockQty){
                        $data[] =[
                            'stockQty'=>$stockQty,
                            'batch_no' => $stock['batch_no'],
                            'lot_serial_no' => $stock['lot_serial_no'],
                            'ref_uom_id'=>$stock->ref_uom_id,
                            'stock_id'=>$stock->id
                        ];
                        $qtyToRemove-=$stockQty;
                        CurrentStockBalance::where('id', $stock->id)->first()->update([
                            'current_quantity'=>0,
                        ]);
                        $stock_history_data['decrease_qty']= $stockQty;
                        stock_history::create($stock_history_data);
                    }else{
                        $leftStockQty=$stockQty - $qtyToRemove;
                        $data[] =[
                            'stockQty' => $qtyToRemove,
                            'batch_no' => $stock['batch_no'],
                            'lot_serial_no' => $stock['lot_serial_no'],
                            'ref_uom_id' => $stock->ref_uom_id,
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
                // dd($data);
                return $data;
            }

        }else {
            $current_stock_id = $current_stock['id'];
            $product_id = $current_stock['product_id'];
            $variation_id = $current_stock['variation_id'];
            $currentStock = CurrentStockBalance::where('business_location_id', $business_location_id)
                ->where('product_id', $product_id)
                ->where('variation_id', $variation_id)
                ->where('id', $current_stock_id);
            // dd($currentStock->get()->toArray());
            $current_stock_qty =  $currentStock->first()->current_quantity;
            return abort(404, '');
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
                    'batch_no' => $currentStockData['batch_no'],
                    'expired_date' => $currentStockData['expired_date'],
                    'transaction_type' => 'sale',
                    'transaction_details_id' => $sale_detail_id,
                    'increase_qty' => 0,
                    'decrease_qty' => $requestQty,
                    'ref_uom_id' => $currentStockData['ref_uom_id'],
                ]);
                return true;
            }
        }


    }



    public function update($id, Request $request)
    {
        $request_sale_details = $request->sale_details;
        $lot_control=$this->setting->lot_control;

        // I fetch to sales data one for store as old data that fetch form database and one is to update data and after updated ,if you call slaes the will be updated!!
        $saleBeforeUpdate=sales::where('id', $id)->first();
        $sales= sales::where('id', $id)->first();
        DB::beginTransaction();
        try {
            if($request->type=='pos'){
                $registeredPos=posRegisters::where('id',$request->pos_register_id)->select('id','payment_account_id','use_for_res')->first();
                $paymentAccountIds=json_decode($registeredPos->payment_account_id);
                $request['payment_account']=$paymentAccountIds[0];
                $request['currency_id']=$this->currency->id;
            }
            $sales->update([
                'contact_id' => $request->contact_id,
                'status' => $request->status,
                'sale_amount' => $request->sale_amount,
                'total_item_discount' => $request->total_item_discount,
                'extra_discount_type' => $request->extra_discount_type,
                'extra_discount_amount' => $request->extra_discount_amount,
                'total_sale_amount' => $request->total_sale_amount,
                'paid_amount' => $request->paid_amount,
                'balance_amount' => $request->balance_amount,
                'currency_id' => $request->currency_id,
                'updated_by' => Auth::user()->id,
            ]);
            $this->changeTransaction($saleBeforeUpdate,$sales,$request);
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

                    if (count($request_old_sale)<=1) {
                        continue;
                    };

                    $sale_detail_id = $request_old_sale['sale_detail_id'];
                    unset($request_old_sale["sale_detail_id"]);
                    $request_old_sale['updated_by'] = Auth::user()->id;

                    $sale_details = sale_details::where('id', $sale_detail_id)->where('is_delete', 0)->first();
                    $request_old_sale['id']= $sale_details->id;


                    //get old sale_detail qty from db
                    // dd($sale_details->toArray());
                    $sale_detial_qty_from_db = UomHelper::getReferenceUomInfoByCurrentUnitQty( $sale_details->quantity, $sale_details->uom_id)['qtyByReferenceUom'];

                    // smallest qty from client
                    $requestQty = UomHelper::getReferenceUomInfoByCurrentUnitQty($request_old_sale['quantity'], $request_old_sale['uom_id'])['qtyByReferenceUom'];


                    $dif_sale_qty = $requestQty - $sale_detial_qty_from_db;
                    // dd($dif_sale_qty);

                        $lotSerialCheck= lotSerialDetails::where('transaction_type','sale')->where('transaction_detail_id', $sale_details->id)->exists();
                        $businessLocation = businessLocation::where('id', $request->business_location_id)->first();
                        if($saleBeforeUpdate->status != 'delivered' && $request->status== "delivered" && !$lotSerialCheck && $businessLocation->allow_sale_order == 0){
                            $changeQtyStatus = $this->changeStockQty($requestQty, $request->business_location_id, $request_old_sale);
                            if ($changeQtyStatus == false) {
                                return redirect()->back()->withInput()->with(['warning' => "product Out of Stock"]);
                            } else {
                                if($this->setting->lot_control == "off") {
                                    $datas = $changeQtyStatus;
                                    foreach ($datas as $data) {
                                        // dd($datas);
                                        $sale_uom_qty = UomHelper::changeQtyOnUom($data['ref_uom_id'], $request_old_sale['uom_id'], $data['stockQty']);
                                        $bsd = lotSerialDetails::create([
                                            'transaction_type' => 'sale',
                                            'transaction_detail_id' => $sale_details->id,
                                            'current_stock_balance_id' => $data['stock_id'],
                                            'lot_serial_numbers' => $data['lot_serial_no'],
                                            'uom_quantity' => $sale_uom_qty,
                                            'uom_id' => $request_old_sale['uom_id'],
                                        ]);
                                    }
                                }
                            }
                        }
                        elseif($saleBeforeUpdate->status == 'delivered' && $request->status != "delivered" && $businessLocation->allow_sale_order == 0 ){
                            $lotSerials = lotSerialDetails::where('transaction_type', 'sale')->where('transaction_detail_id', $sale_details->id);
                            if ($lotSerials->exists()) {
                                $this->adjustStock($lotSerials->get());
                                foreach ($lotSerials->get() as $lotSerial) {
                                    $lotSerial->delete();
                                }
                            };
                        }
                        else{
                            $referecneQty = UomHelper::getReferenceUomInfoByCurrentUnitQty($sale_details->quantity, $sale_details->uom_id)['qtyByReferenceUom'];
                            if($request_old_sale['quantity'] > $sale_details->quantity) {
                                $current_stock= CurrentStockBalance::where('product_id', $sale_details->product_id)
                                                                    ->where('business_location_id', $businessLocation->id)
                                                                    ->where('variation_id', $sale_details->variation_id)
                                                                    ->where('current_quantity', '>', '0');
                                $availableStocks =$current_stock->get();
                                $availableQty = $current_stock->sum('current_quantity');
                                $newQty=round($requestQty- $sale_detial_qty_from_db,2);
                                if($newQty > $availableQty){
                                    return redirect()->route('all_sales')->with(['warning'=>'out of stock']);
                                }
                                $qtyToRemove= $request_old_sale['quantity']- $sale_details->quantity;

                                foreach ($availableStocks as $stock) {
                                    $stockQty=round(UomHelper::changeQtyOnUom($stock->ref_uom_id,$sale_details->uom_id,$stock->current_quantity),2);
                                    // $stockQty = UomHelper::getReferenceUomInfoByCurrentUnitQty( $stock->current_quantity, $stock->ref_uom_id)['qtyByReferenceUom'];
                                    // dd($qtyToRemove , $stockQty);
                                    if ($qtyToRemove >= $stockQty) {
                                        CurrentStockBalance::where('id', $stock->id)->first()->update([
                                            'current_quantity' => 0,
                                        ]);

                                        $lotSerialDetailsByStock = lotSerialDetails::where('transaction_type', 'sale')->where('transaction_detail_id', $sale_details['id'] )->where('current_stock_balance_id', $stock->id);
                                        if ($lotSerialDetailsByStock->exists()) {
                                            $sale_detial_qty = $lotSerialDetailsByStock->first()->uom_quantity;
                                            $lotSerialDetailsByStock->update([
                                                'uom_quantity' => $sale_detial_qty + $qtyToRemove,
                                            ]);
                                        } else {
                                            lotSerialDetails::create([
                                                'transaction_type' => 'sale',
                                                'transaction_detail_id' => $sale_details['id'],
                                                'current_stock_balance_id' =>  $stock->id,
                                                'lot_serial_numbers' => $stock->lot_serial_no,
                                                'uom_quantity' => $qtyToRemove,
                                                'uom_id' => $request_old_sale['uom_id'],
                                            ]);
                                        }
                                        $qtyToRemove -= $stockQty;
                                    } else {
                                        $leftStockQty = $stockQty - $qtyToRemove;
                                        $stock_for_update=CurrentStockBalance::where('id', $stock->id)->first();
                                        $smallest_leftStockQty=UomHelper::getReferenceUomInfoByCurrentUnitQty( $leftStockQty, $sale_details->uom_id)['qtyByReferenceUom'];
                                        $stock_for_update->update([
                                            'current_quantity' => $smallest_leftStockQty,
                                        ]);

                                        $lotSerialDetails = lotSerialDetails::where('transaction_type', 'sale')->where('transaction_detail_id', $sale_detail_id)->where('current_stock_balance_id', $stock->id);
                                        if($lotSerialDetails->exists()){
                                            $sale_detial_qty= $lotSerialDetails->first()->uom_quantity;
                                            $lotSerialDetails->update([
                                                'uom_quantity'=> $sale_detial_qty + $qtyToRemove,
                                                ]);
                                        }else{
                                            lotSerialDetails::create([
                                                'transaction_type' => 'sale',
                                                'transaction_detail_id' =>  $sale_details['id'],
                                                'current_stock_balance_id' => $stock->id,
                                                'lot_serial_numbers' => $stock->lot_serial_no,
                                                'uom_quantity' =>  $qtyToRemove,
                                                'uom_id' => $request_old_sale['uom_id'],
                                            ]);
                                        }
                                        $qtyToRemove = 0;
                                        break;
                                    }
                                }
                            }elseif($request_old_sale['quantity'] < $sale_details->quantity) {
                                // dd('junk');
                                $qty_to_replace = $sale_details->quantity - $request_old_sale['quantity'];
                                $lotSerialDetails=lotSerialDetails::where('transaction_type','sale')->where('transaction_detail_id', $sale_details->id)->OrderBy('id','DESC')->get();
                                foreach ($lotSerialDetails as $bsd) {
                                    // dd($bsd->toArray(), $qty_to_replace, $bsd->uom_quantity);
                                    if ($qty_to_replace > $bsd->uom_quantity) {
                                        lotSerialDetails::where('id', $bsd->id)->first()->delete();
                                        $referecneQty=UomHelper::getReferenceUomInfoByCurrentUnitQty($bsd->uom_quantity,$bsd->uom_id)['qtyByReferenceUom'];
                                        $current_stock= CurrentStockBalance::where('id', $bsd->current_stock_balance_id)->first();
                                        $current_stock->update([
                                            'current_quantity' =>  $current_stock->current_quantity+$referecneQty,
                                        ]);
                                        $qty_to_replace -= $bsd->uom_quantity;
                                        if($qty_to_replace<= 0){
                                            break;
                                        }
                                    } elseif($qty_to_replace <= $bsd->uom_quantity) {
                                        $referenceUomToReplace =(int) UomHelper::getReferenceUomInfoByCurrentUnitQty($qty_to_replace, $bsd->uom_id)['qtyByReferenceUom'];
                                        $current_stock= CurrentStockBalance::where('id', $bsd->current_stock_balance_id)->first();
                                        $resultForBsd=$bsd->uom_quantity - $qty_to_replace;
                                        if($resultForBsd==0){
                                            lotSerialDetails::where('id', $bsd->id)->first()->delete();
                                        }else{
                                            lotSerialDetails::where('id', $bsd->id)->first()->update([
                                                'uom_quantity'=> $resultForBsd,
                                            ]);
                                        }
                                        $current_stock->update([
                                            'current_quantity' =>  $current_stock->current_quantity+$referenceUomToReplace,
                                        ]);
                                        $qty_to_replace = 0;
                                        break;
                                    }

                                }

                                // dd($saleDetailsByParent->toArray());
                            }
                        };

                    // dd($request_old_sale);

                    $request_sale_details_data = [
                        'uom_id' => $request_old_sale['uom_id'],
                        'quantity' => $request_old_sale['quantity'],
                        'uom_price' => $request_old_sale['uom_price'],
                        'subtotal' =>  $request_old_sale['subtotal'],
                        'discount_type' => $request_old_sale['discount_type'],
                        'per_item_discount' => $request_old_sale['per_item_discount'],
                        'subtotal_with_discount' => $request_old_sale['subtotal'] - ($request_old_sale['line_subtotal_discount'] ?? 0),
                        'currency_id' => $request->currency_id,
                        'price_list_id' =>$request_old_sale['price_list_id'] == "default_selling_price" ? null :   $request_old_sale['price_list_id'],
                        'updated_by' => $request_old_sale['updated_by'],
                        'subtotal_with_tax' => $request_old_sale['subtotal'] - ($request_old_sale['line_subtotal_discount'] ?? 0),
                    ];
                    logger($request_sale_details_data);
                    // dd($request_sale_details_data);
                    // dd($request_sale_details_data);
                    if($request_old_sale['quantity'] <= 0){
                        $request_sale_details_data['is_delete']  = 1;
                        $request_sale_details_data['deleted_at']  = now();
                        $request_sale_details_data['is_delete']  = Auth::user()->id;
                        $sale_details->update($request_sale_details_data);
                    }else{
                        $sale_details->update($request_sale_details_data);
                    };
                    if($requestQty>0){
                        stock_history::where('transaction_details_id', $sale_detail_id)->where('transaction_type','sale')->update([
                            'decrease_qty' => $requestQty,
                        ]);
                    }else{
                        stock_history::where('transaction_details_id', $sale_detail_id)->where('transaction_type', 'sale')->delete();
                    }
                }

                //get added sale_details_ids from database
                $fetch_sale_details = sale_details::where('sales_id', $id)->where('is_delete', 0)->select('id')->get()->toArray();
                $get_fetched_sale_details_id = array_column($fetch_sale_details, 'id');
                //to remove edited sale_detais that are already created
                $request_old_sale_details_id_for_delete = array_diff($get_fetched_sale_details_id, $request_old_sale_details_ids); //for delete row
                // sale_details for delete

                foreach ($request_old_sale_details_id_for_delete as $sale_detail_id) {

                    $sale_details = sale_details::where('id', $sale_detail_id)->where('is_delete', 0);
                    $sale_details_count= count($sale_details->get()->toArray());
                    if($sale_details_count > 0) {
                        $get_sale_details = $sale_details->first();
                        $lotSerials=lotSerialDetails::where('transaction_type','sale')->where('transaction_detail_id',$get_sale_details->id);

                        if ($lotSerials->exists()) {
                            $this->adjustStock($lotSerials->get());
                            foreach ($lotSerials->get() as $lotSerial) {
                                $lotSerial->delete();
                            }

                        };
                        stock_history::where('transaction_details_id', $sale_detail_id)->where('transaction_type', 'sale')->delete();
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
                    $resOrderData=null;
                    if($request->type=='pos' && $registeredPos->use_for_res == 1){
                        $resOrderData=$this->resOrderCreation($sales,$request);
                        $this->saleDetailCreation($request, $sales, $new_sale_details,$resOrderData);
                    }else{
                        $this->saleDetailCreation($request, $sales, $new_sale_details,$resOrderData);
                    }
                }
            } else {
                $saleDetailQuery= sale_details::where('sales_id', $id);
                $allSaleDetailIdToRemove= $saleDetailQuery->get();
                foreach ($allSaleDetailIdToRemove as   $sd) {
                    $lotSerials = lotSerialDetails::where('transaction_type', 'sale')->where('transaction_detail_id', $sd->id);
                    if ($lotSerials->exists()) {
                        $this->adjustStock($lotSerials->get());
                        foreach ($lotSerials->get() as $lotSerial) {
                            $lotSerial->delete();
                        }
                    };
                }

                stock_history::where('transaction_details_id', $id)->where('transaction_type', 'sale')->delete();
                $saleDetailQuery->update([
                    'is_delete' => 1,
                    'deleted_at' => now(),
                    'deleted_by' => Auth::user()->id,
                ]);
                sales::where('id',$id)->update([
                    'is_delete' => 1,
                    'deleted_at' => now(),
                    'deleted_by' => Auth::user()->id,
                ]);
            }


            DB::commit();
        } catch (Exception $e) {
            logger($e);
            DB::rollBack();
        }
        // dd($request->toArray());
        if($request->type=='pos'){
            return response()->json([
               'status'=>'200',
               'message'=>'successfully Updated'
            ], 200);
       }else{
        return redirect()->route('all_sales')->with(['success' => 'successfully updated']);
        }
    }


    public function softDelete($id)
    {
        $this->softDeletion($id);
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
                $this->softDeletion($id);
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

    private function softDeletion($id){
        $saleDetailQuery = sale_details::where('sales_id', $id);
        $allSaleDetailIdToRemove = $saleDetailQuery->get();
        foreach ($allSaleDetailIdToRemove as   $sd) {
            $lotSerials = lotSerialDetails::where('transaction_type', 'sale')->where('transaction_detail_id', $sd->id)->OrderBy('id', 'DESC');
            if ($lotSerials->exists()) {
                if (request('restore')=='true') {
                    $this->adjustStock($lotSerials->get());
                }
                foreach ($lotSerials->get() as $lotSerial) {
                    $lotSerial->delete();
                }
            };
        }
        stock_history::where('transaction_details_id', $id)->where('transaction_type', 'sale')->delete();
        $saleDetailQuery->update([
            'is_delete' => 1,
            'deleted_at' => now(),
            'deleted_by' => Auth::user()->id,
        ]);
        sales::where('id', $id)->update([
            'is_delete' => 1,
            'deleted_by' => Auth::user()->id,
            'deleted_at' => now()
        ]);
    }

    public function getProduct(Request $request)
    {
        $business_location_id = $request->data['business_location_id'];
        $q = $request->data['query'];
        $variation_id=$request->data['variation_id'] ?? null;

        $products = Product::select('id', 'name', 'product_code', 'category_id','sku', 'product_type', 'uom_id', 'purchase_uom_id')
            ->where('can_sale',1)
            ->where('deleted_at',null)
            ->where('name', 'like', '%' . $q . '%')
            ->orWhere('sku', 'like', '%' . $q . '%')

            ->with([
                'productVariations' => function ($query) use ($variation_id){
                    $query->select('id', 'product_id', 'variation_template_value_id', 'default_purchase_price', 'default_selling_price')
                        ->when($variation_id, function ($query) use ($variation_id) {
                            $query->where('id', $variation_id);
                        })
                        ->with(['variationTemplateValue' => function ($query) {
                            $query->select('id', 'name');
                        }, 'uomSellingPrice']);
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
                $batch_nos=[];
                foreach ($product->stock as $stock) {
                    $no = $stock['batch_no'];
                    $lot_id = $stock['id'];
                    $batch_nos[] = [ 'id' => $lot_id,'no' => $no];
                }
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'product_code' => $product->product_code,
                    'category_id'=>$product->category_id,
                    'sku' => $product->sku,
                    'product_type' => $product->product_type,
                    'uom_id' => $product->uom_id,
                    'uom'=>$product->uom,
                    'purchase_uom_id' => $product->purchase_uom_id,
                    'product_variations' => $product->product_type == 'single' ? $product->productVariations->toArray()[0] : $product->productVariations->toArray(),
                    'total_current_stock_qty' => $product->stock_sum_current_quantity ?? 0,
                    'batch_nos' => $batch_nos,
                    'stock' => $product->stock->toArray(),
                ];
            });
        foreach ($products as $product) {
            if ($product['product_type'] == 'variable') {
                $product_variation = $product['product_variations'];
                foreach ($product_variation as $variation) {
                    $batch_nos = [];
                    $reference_uom_id = [];
                    $total_current_stock_qty = 0;
                    $stocks = array_filter($product['stock'], function ($s) use ($variation) {
                        return $s['variation_id'] == $variation['id'] && $s['current_quantity'] > 0;
                    });
                    foreach ($stocks as $stock) {
                        $total_current_stock_qty += $stock['current_quantity'];
                        $no = $stock['batch_no'];
                        $lot_id=$stock['id'];
                        $batch_nos[] = ['no'=>$no,'id'=> $lot_id];
                        $reference_uom_id=$stock['ref_uom_id'];
                    }
                    $variation_product = [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'sku' => $product['sku'],
                        'total_current_stock_qty' => $total_current_stock_qty,
                        'stock' =>[...$stocks],
                        'uom_id' =>$reference_uom_id,
                        'product_variations' => $variation,
                        'uom_id' => $product['uom_id'],
                        'uom' => $product['uom'],
                        'batch_nos'=> $product['batch_nos'],
                        'variation_id' => $variation['id'],
                        'category_id'=>$product['category_id'],
                        'product_type' => 'sub_variable',
                        'variation_name' => $variation['variation_template_value']['name'],
                    ];
                    $products[] = $variation_product;
                }
            }
        }
        return response()->json($products, 200);
    }

    public function saleInvoice($id)
    {
        $sale = sales::with('business_location_id', 'sold_by', 'confirm_by', 'customer', 'updated_by','currency')->where('id', $id)->first()->toArray();

        $location = $sale['business_location_id'];


        $sale_details = sale_details::with(['productVariation' => function ($q) {
            $q->select('id', 'product_id', 'variation_template_value_id')
            ->with([
                'product' => function ($q) {
                    $q->select('id', 'name', 'product_type');
                },
                'variationTemplateValue' => function ($q) {
                    $q->select('id', 'name');
                }
            ]);
        }, 'product', 'uom','currency'])
        ->where('sales_id', $id)->where('is_delete', 0)->get();
        $invoiceHtml = view('App.sell.print.saleInvoice3', compact('sale', 'location', 'sale_details'))->render();
        return response()->json(['html' => $invoiceHtml]);
    }


    // stock out
    private function adjustStock($lotSerials){
        foreach ($lotSerials as $lotSerial) {
            $sale_detail_qty = UomHelper::getReferenceUomInfoByCurrentUnitQty($lotSerial->uom_quantity, $lotSerial->uom_id)['qtyByReferenceUom'];

            $currentStock = CurrentStockBalance::where('id', $lotSerial->current_stock_balance_id);
            $current_stock_qty = $currentStock->get()->first()->current_quantity;
            $result = $current_stock_qty + $sale_detail_qty;
            $currentStock->update(['current_quantity' => $result]);
        }


    }

    private function saleDetailCreation($request,Object $sale_data,Array $sale_details,$resOrderData=null){
        foreach ($sale_details as $sale_detail) {

            $stock = CurrentStockBalance::where('product_id', $sale_detail['product_id'])
                ->where('business_location_id', $sale_data->business_location_id)
                // ->where('id', $sale_detail['stock_id_by_batch_no'])
                ->with(['product' => function ($q) {
                    $q->select('id', 'name');
                }])
                ->where('variation_id', $sale_detail['variation_id'])
                ->get()->first();
            $line_subtotal_discount= $sale_detail['line_subtotal_discount'] ?? 0;
            $subtotal_with_discount=$request->type =='pos' ? $sale_detail['subtotal_with_discount']: ($sale_detail['subtotal']  - $line_subtotal_discount);
            $sale_details_data = [
                'sales_id' => $sale_data->id,
                'product_id' => $sale_detail['product_id'],
                'variation_id' => $sale_detail['variation_id'],
                'uom_id' => $sale_detail['uom_id'],
                'quantity' => $sale_detail['quantity'],
                'uom_price' => $sale_detail['uom_price'],
                'subtotal' =>  $sale_detail['subtotal'],
                'discount_type' => $sale_detail['discount_type'],
                'per_item_discount' => $sale_detail['per_item_discount'],
                'subtotal_with_discount' =>$subtotal_with_discount,
                'currency_id'=>$request->currency_id ?? $this->currency->id,
                'price_list_id'=> $sale_detail['price_list_id'] == "default_selling_price" ? null :  $sale_detail['price_list_id'],
                'tax_amount' =>0,
                'per_item_tax'=>0,
                'subtotal_with_tax' =>$sale_detail['subtotal']  - $line_subtotal_discount ,
            ];
            if($resOrderData){
                $sale_details_data['rest_order_id']=$resOrderData ? $resOrderData->id : null;
                $sale_details_data['rest_order_status']=$resOrderData ? 'order' : null;
            }
            $created_sale_details = sale_details::create($sale_details_data);

            $requestQty = UomHelper::getReferenceUomInfoByCurrentUnitQty($sale_detail['quantity'], $sale_detail['uom_id'])['qtyByReferenceUom'];
            $businessLocation = businessLocation::where('id', $request->business_location_id)->first();
            if($request->status=='delivered' && $businessLocation->allow_sale_order == 0){
                $changeQtyStatus = $this->changeStockQty($requestQty, $request->business_location_id, $created_sale_details->toArray(), $stock);
                if ($changeQtyStatus == false) {
                    return redirect()->back()->withInput()->with(['warning' => "Out of Stock In " . $stock['product']['name']]);
                } else {
                    if ($this->setting->lot_control == "off") {
                        $datas = $changeQtyStatus;
                        foreach ($datas as $data) {
                            // dd($datas);
                            $sale_uom_qty = UomHelper::changeQtyOnUom($data['ref_uom_id'], $created_sale_details->uom_id, $data['stockQty']);
                            lotSerialDetails::create([
                                'transaction_type' => 'sale',
                                'transaction_detail_id' => $created_sale_details->id,
                                'current_stock_balance_id' => $data['stock_id'],
                                'lot_serial_numbers' => $data['lot_serial_no'],
                                'uom_quantity' => $sale_uom_qty,
                                'uom_id' => $created_sale_details->uom_id,
                            ]);
                        }
                    }
                }
            }
        }
        return;
    }

    public function postToRegistrationFolio($id){
        $folioDetailQuery= hospitalFolioInvoiceDetails::where('transaction_type','sale')
                                                        ->where('transaction_id',$id);
        $checkFolioDetails= $folioDetailQuery->exists();
        $postedRegistration=[];
        if($checkFolioDetails){
            $folioDetail=$folioDetailQuery->select('id', 'folio_invoice_id')->get()->first();
            $registrationId=hospitalFolioInvoices::where('id',$folioDetail->folio_invoice_id)->select('registration_id')->first()->registration_id;
            $postedRegistration=hospitalRegistrations::where('id', $registrationId)->get()->first();
        }
        $SaleVoucher=sales::where('id',$id)->select('id','sales_voucher_no')->first();
        $registrations=hospitalRegistrations::with('patient')->where('is_delete',0)->get();

         return view('App.sell.modal.joinToRegistrationFolioModal',compact('registrations', 'SaleVoucher', 'postedRegistration'));


    }
    public function addToRegistrationFolio(Request $request){
        try {


            $sale_id = $request->sale_id;
            $registration_id = $request->registration_id;

            $folioDetailQuery = hospitalFolioInvoiceDetails::where('transaction_type', 'sale')
                                ->where('transaction_id', $sale_id);
            $checkFolioDetails = $folioDetailQuery->exists();
            if($checkFolioDetails){
                $folio = hospitalFolioInvoices::where('registration_id', $registration_id)->select('id')->first();
                $oldFolioDetail= $folioDetailQuery->first();
                hospitalFolioInvoiceDetails::where('id',$oldFolioDetail->id)->update([
                        'folio_invoice_id' => $folio->id,
                        'transaction_type' => 'sale',
                        'transaction_id' => $sale_id,
                ]);

            }else{
                $folio = hospitalFolioInvoices::where('registration_id', $registration_id)->select('id')->first();
                hospitalFolioInvoiceDetails::create([
                    'folio_invoice_id'=>$folio->id,
                    'transaction_type'=>'sale',
                    'transaction_id'=> $sale_id,
                ]);
            }
            return response()->json(['success' => true, 'msg' => ' Successfully Joined Registration']);
        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'msg' => ` Something Wrong While Posting Registration's folio`]);
        }
    }

    public function postToReservationFolio($id) {
        $folioInvoiceDetail= FolioInvoiceDetail::where('transaction_type','sale')
        ->where('transaction_id', $id);

        $checkFolioDetails= $folioInvoiceDetail->exists();
        $postedReservation=[];
        if($checkFolioDetails){
            $folioDetail=$folioInvoiceDetail->select('id', 'folio_invoice_id')->get()->first();
            $reservationId=FolioInvoice::where('id',$folioDetail->folio_invoice_id)->select('reservation_id')->first()->reservation_id;
            $postedReservation=Reservation::where('id', $reservationId)->get()->first();
        }
        $saleVoucher = sales::where('id', $id)->select('id', 'sales_voucher_no')->first();
        $reservations=Reservation::with('contact', 'company')->where('is_delete',0)->get();
        return view('App.sell.modal.joinToReservationFolioModal')->with(compact('saleVoucher', 'reservations','postedReservation'));
    }

    public function addToReservationFolio(Request $request) {
        try {
            $sale_id = $request->sale_id;
            $reservation_id = $request->reservation_id;

            $folioInvoiceDetail = FolioInvoiceDetail::where('transaction_type', 'sale')
                                ->where('transaction_id', $sale_id);

            $checkFolioDetails = $folioInvoiceDetail->exists();

            if($checkFolioDetails){
                $folio = FolioInvoice::where('reservation_id', $reservation_id)->select('id')->first();
                $oldFolioDetail= $folioInvoiceDetail->first();
                FolioInvoiceDetail::where('id',$oldFolioDetail->id)->update([
                        'folio_invoice_id' => $folio->id,
                        'transaction_type' => 'sale',
                        'transaction_id' => $sale_id,
                ]);

            }else{
                $folio = FolioInvoice::where('reservation_id', $reservation_id)->select('id')->first();
               FolioInvoiceDetail::create([
                    'folio_invoice_id'=>$folio->id,
                    'transaction_type'=>'sale',
                    'transaction_id'=> $sale_id,
                ]);
            }
            return response()->json(['success' => true, 'msg' => ' Successfully Joined Reservation']);
        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'msg' => ` Something Wrong While Posting Reservation's folio`]);
        }
    }


    protected function makePayment($sale,$payment_account_id,$increatePayment=false,$increaseAmount=0){
        $paymentAmount=$increatePayment ? $increaseAmount :$sale->paid_amount;
        if($paymentAmount>0){
            $data=[
                'payment_voucher_no'=>generatorHelpers::paymentVoucher(),
                'payment_date'=>now(),
                'transaction_type'=>'sale',
                'transaction_id'=>$sale->id,
                'transaction_ref_no'=>$sale->sales_voucher_no,
                'payment_method'=>'card',
                'payment_account_id'=>$payment_account_id,
                'payment_type'=>'debit',
                'payment_amount'=>$paymentAmount,
                'currency_id'=>$sale->currency_id,
            ];
            $paymentTransaction=paymentsTransactions::create($data);
            $accountInfo=paymentAccounts::where('id',$payment_account_id);
            if($accountInfo){
                $currentBalanceFromDb=$accountInfo->first()->current_balance ;
                $finalCurrentBalance=$currentBalanceFromDb+$paymentAmount;
                $accountInfo->update([
                    'current_balance'=>$finalCurrentBalance,
                ]);
            }
            $suppliers=Contact::where('id',$sale->contact_id)->first();
            if($sale->balance_amount > 0){
                $suppliers_receivable=$suppliers->receivable_amount;
                $suppliers->update([
                    'receivable_amount'=>$suppliers_receivable+$sale->receivable_amount
                ]);
            }else if($sale->balance_amount < 0){
                $suppliers_payable=$suppliers->receivable_amount;
                $suppliers->update([
                    'payable_amount'=>$suppliers_payable+$sale->balance_amount
                ]);
            }
            return $paymentTransaction;
        }
        return null;
    }



    protected function changeTransaction($saleBeforeUpdate,$updatedSale,$request){
        $transaction=paymentsTransactions::orderBy('id','DESC')
                                         ->where('transaction_ref_no',$saleBeforeUpdate->sales_voucher_no)
                                         ->where('transaction_id',$saleBeforeUpdate->id)
                                         ->where('payment_type','debit');
                                        //  dd($transaction->first()->toArray());
        $oldTransaction=$transaction->first();
       if($oldTransaction){
            if($oldTransaction->payment_account_id != $request->payment_account && isset($request->payment_account) ){
                $this->depositeToBeforeChangeAcc($oldTransaction,$saleBeforeUpdate);
                $this->makePayment($updatedSale,$request->payment_account);
            }elseif($updatedSale->paid_amount>$saleBeforeUpdate->paid_amount){

                $increaseAmount=$updatedSale->paid_amount-$saleBeforeUpdate->paid_amount;
                $this->makePayment($updatedSale,$request->payment_account,true,$increaseAmount);

            }elseif($updatedSale->paid_amount<=$saleBeforeUpdate->paid_amount){

                $decreaseAmount=$saleBeforeUpdate->paid_amount-$updatedSale->paid_amount;
                $this->depositeToBeforeChangeAcc($oldTransaction,$saleBeforeUpdate,true,$decreaseAmount);
            }
       }
    }

    public function depositeToBeforeChangeAcc($oldTransaction,$saleBeforeUpdate,$decreasePayment=false,$decreaseAmount=0){
        $paymentAmount=$decreasePayment ? $decreaseAmount :$saleBeforeUpdate->paid_amount;
        if($paymentAmount>0){
            $data=[
                'payment_voucher_no'=>generatorHelpers::paymentVoucher(),
                'payment_date'=>now(),
                'transaction_type'=>'sale',
                'transaction_id'=>$saleBeforeUpdate->id,
                'transaction_ref_no'=>$saleBeforeUpdate->sales_voucher_no,
                'payment_method'=>'card',
                'payment_account_id'=>$oldTransaction->payment_account_id,
                'payment_type'=>'credit',
                'payment_amount'=> $paymentAmount,
                'currency_id'=>$saleBeforeUpdate->currency_id,
            ];

            paymentsTransactions::create($data);
            $accountInfo=paymentAccounts::where('id',$oldTransaction->payment_account_id);
            if($accountInfo){
                $currentBalanceFromDb=$accountInfo->first()->current_balance ;
                $finalCurrentBalance=$currentBalanceFromDb- $paymentAmount;
                $accountInfo->update([
                    'current_balance'=>$finalCurrentBalance,
                ]);
            }
        }
    }

    public function getPriceList($id){
        $data=PriceListDetails::where('pricelist_id',$id)->first();
        $priceData=[
            'mainPriceList'=>$data?$data->toArray():'',
            'basePriceList'=>$this->getBasePrice($data)->toArray()
        ];
        // dd($priceData);
        return response()->json($priceData, 200);
    }

    private function getBasePrice($data)
    {
        $descendants = collect([]);
        if ($data !==null) {
            if($data->base_price_data != null){
                unset($data['base_price_data']);
                $descendants->push($data->base_price_data);
            }
            $descendants =$descendants->merge($this->getBasePrice($data->base_price_data));
        }
        return $descendants;
    }

    public function saleSplitForPos(Request $request){
        $lastSaleId = sales::orderBy('id', 'DESC')->select('id')->first()->id ?? 0;
        parse_str($request->saleDetailIds,$saleDetailId);
        $saleId=$request->saleId;

        $originalItem = sales::find($saleId);
        if (!$originalItem) {
            return redirect()->back()->with('error', 'Sales voucher not found.');
        }

        // Clone the original item
        $clonedItem = $originalItem->replicate();

        $clonedItem->sales_voucher_no = sprintf('SVN-' . '%06d', ($lastSaleId + 1));
        $clonedItem->created_at = now();
        $clonedItem->created_by=Auth::user()->id;
        $clonedItem->updated_at = now();
        $clonedItem->save();
        foreach ($saleDetailId as $id) {
            sale_details::where('id',$id)->update([
                'sales_id'=>$clonedItem->id
            ]);
        }
    }
}
