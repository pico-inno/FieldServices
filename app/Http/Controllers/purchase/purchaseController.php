<?php

namespace App\Http\Controllers\purchase;

use DateTime;
use Exception;
use Carbon\Carbon;
use App\Helpers\UomHelper;
use App\Models\Currencies;
use App\Models\Product\UOM;
use App\Models\Product\Unit;
use Illuminate\Http\Request;
use App\Models\stock_history;
use App\Models\Product\UOMSet;
use App\Models\Contact\Contact;
use App\Models\paymentAccounts;
use App\Models\Product\Product;
use App\Helpers\generatorHelpers;
use Illuminate\Support\Facades\DB;
use App\Models\CurrentStockBalance;
use App\Models\purchases\purchases;
use App\Http\Controllers\Controller;
use App\Models\exchangeRates;
use App\Models\paymentsTransactions;
use Illuminate\Support\Facades\Auth;
use App\Models\Product\ProductVariation;
use Yajra\DataTables\Facades\DataTables;
use App\Models\settings\businessLocation;
use App\Models\settings\businessSettings;
use Illuminate\Support\Facades\Validator;
use App\Models\purchases\purchase_details;

class purchaseController extends Controller
{
    private $setting;
    private $currency;
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
        $this->middleware('canView:purchase')->only(['index', 'listData']);
        $this->middleware('canCreate:purchase')->only(['add', 'store']);
        $this->middleware('canUpdate:purchase')->only(['edit', 'update']);
        $this->middleware('canDelete:purchase')->only('softOneItemDelete', 'softSelectedDelete');

        $settings = businessSettings::select('lot_control', 'currency_id','enable_line_discount_for_purchase')->with('currency')->first();
        $this->setting = $settings;
        $this->currency= $settings->currency;
    }

    public function index()
    {
        $locations = businessLocation::all();
        $suppliers=Contact::where('type','Supplier')
            ->select('id','company_name','first_name')
            ->get();
        return view('App.purchase.listPurchase', compact('locations','suppliers'));
    }

    public function add()
    {
        $locations=businessLocation::all();
        $currency=$this->currency;
        $suppliers=Contact::where('type','Supplier')
                    ->select('id','company_name','first_name','address_line_1','address_line_2','zip_code','city','state','country')
                    ->get();
        $currencies=Currencies::get();
        $setting=$this->setting;
        return view('App.purchase.addPurchase',compact('locations','suppliers','setting', 'currency','currencies'));
    }

    public function purchase__new_add()
    {
        $locations=businessLocation::all();
        $currency=$this->currency;
        $suppliers=Contact::where('type','Supplier')
                    ->select('id','company_name','first_name','address_line_1','address_line_2','zip_code','city','state','country')
                    ->get();
        $currencies=Currencies::get();
        $setting=$this->setting;
        return view('App.purchase.addNewPurchase',compact('locations','suppliers','setting', 'currency','currencies'));
    }
    public function store(Request $request)
    {
        Validator::make([
            'details'=>$request->purchase_details,
        ],[
            'details'=>'required',
        ])->validate();
        DB::beginTransaction();
        try {
            $lastPurchaseId=purchases::orderBy('id','DESC')->select('id')->first()->id ?? 0;
            $purchases_data=$this->purchaseData($request);
            $purchases_data['purchase_voucher_no'] =sprintf('PVN-' . '%06d', ($lastPurchaseId + 1));
            $purchases_data['purchased_by']=Auth::user()->id;
            $purchases_data['confirm_at']=$request->status==='confirmed'? now() :null;
            $purchases_data['confirm_by']=$request->status==='confirmed'?  Auth::user()->id : null;
            $purchase=purchases::create($purchases_data);
            $purchases_details= $request->purchase_details;
            $purchase_id=$purchase->id;
            if ($purchases_details) {
                $this->purchase_detail_creation($purchases_details, $purchase_id, $purchase);
            }
            if($request->paid_amount>0 && $request->payment_account){
                $this->makePayment($purchase,$request->payment_account);
            }else{
                $suppliers=Contact::where('id',$request->contact_id)->first();
                $suppliers_payable=$suppliers->payable_amount;
                $suppliers->update([
                    'payable_amount'=>$suppliers_payable+$request['balance_amount']
                ]);
            }
            DB::commit();
            if($request->save =='save_&_print'){
                return redirect()->route('purchase_list')->with([
                    'success' => 'Successfully Created Purchase',
                    'print'=>$purchase_id,
                ]);
            }else{
                return redirect()->route('purchase_list')->with(['success' => 'Successfully Created Purchase']);
            }
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect()->back()->with(['warning' => 'An error occurred while creating the purchasse']);
        }
    }
    public function listData()
    {

        $purchases=purchases::where('is_delete',0)
            ->with('business_location_id','supplier')
            ->OrderBy('id','desc')->get();
        return DataTables::of($purchases)
            ->addColumn('checkbox',function($purchase){
                return
                '
                    <div class="form-check form-check-sm form-check-custom ">
                        <input class="form-check-input" type="checkbox" data-checked="delete" value='.$purchase->id.' />
                    </div>
                ';
            })
            ->editColumn('supplier', function($purchase){
                return $purchase->supplier['company_name']??$purchase->supplier['first_name'];
            })

            ->editColumn('date',function($purchase){
                return fDate($purchase->created_at,true);
            })
            ->editColumn('purchaseItems',function($purchase){
                $purchaseDetails=$purchase->purchase_details;
                $items='';
                foreach ($purchaseDetails as $key => $pd) {
                    $variation=$pd->productVariation;
                    $productName=$variation->product->name;
                    $sku=$variation->product->sku ?? '';
                    $variationName=$variation->variationTemplateValue->name ?? '';
                    $items.="$productName,$variationName,$sku ;";
                }
                return $items;
            })
            ->editColumn('status', function($purchase) {
                $html='';
                if($purchase->status== 'received'){
                    $html= "<span class='badge badge-success'> Received </span>";
                }elseif($purchase->status == 'request'){
                    $html = "<span class='badge badge-secondary'>$purchase->status</span>";
                } elseif ($purchase->status == 'pending') {
                    $html = "<span class='badge badge-warning'>$purchase->status</span>";
                } elseif ($purchase->status == 'order') {
                    $html = "<span class='badge badge-primary'>$purchase->status</span>";
                } elseif ($purchase->status == 'partial') {
                    $html = "<span class='badge badge-info'>$purchase->status</span>";
                }
                return $html;
                // return $purchase->supplier['company_name'] ?? $purchase->supplier['first_name'];
            })
            ->editColumn('payment_status',function($e){
                if($e->payment_status=='pending'){
                    return '<span class="badge badge-warning">Pending</span>';
                }elseif($e->payment_status=='partial'){
                    return '<span class="badge badge-primary">Partial</span>';
                }elseif($e->payment_status=='paid'){
                    return '<span class="badge badge-success">Paid</span>';
                }else{
                    return '-';
                }
            })
            ->addColumn('action', function ($purchase) {
                $editBtn=$purchase->status!="confirmed"? '<a href=" ' . route('purchase_edit', $purchase->id) . ' " class="dropdown-item p-2 edit-unit bg-active-primary fw-semibold" >Edit</a>':'';
                $html = '
                    <div class="dropdown ">
                        <button class="btn m-2 btn-sm btn-light btn-primary fw-semibold fs-7  dropdown-toggle " type="button" id="purchaseDropDown" data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                        </button>
                        <div class="z-3">
                        <ul class="dropdown-menu z-10 p-5 " aria-labelledby="purchaseDropDown" role="menu">';
                            if(hasView('purchase')){
                                $html .= '<a class="dropdown-item p-2  px-3 view_detail  fw-semibold"   type="button" data-href="'.route('purchaseDetail', $purchase->id).'">
                                view
                            </a>';
                            }
                           if (hasPrint('purchase')){
                               $html .= ' <a class="dropdown-item p-2  cursor-pointer bg-active-danger fw-semibold print-invoice"  data-href="' . route('print_purchase',$purchase->id) .'">print</a>';
                           }
                            if (hasUpdate('purchase')){
                                $html .= $editBtn;
                            }
                            if($purchase->balance_amount>0){
                                $html.='<a class="dropdown-item p-2 cursor-pointer " id="paymentCreate"   data-href="'.route('paymentTransaction.createForPurchase',['id' => $purchase->id,'currency_id'=>$purchase->currency_id]).'">Add Payment</a>';
                            }
                            $html.='<a class="dropdown-item p-2 cursor-pointer " id="viewPayment"   data-href="'.route('paymentTransaction.viewForPurchase',$purchase->id).'">View Payment</a>';
                            if (hasDelete('purchase')){
                                $html .= '   <a class="dropdown-item p-2  cursor-pointer bg-active-danger fw-semibold text-danger"  data-id="'.$purchase->id. '" data-kt-purchase-table="delete_row">Delete</a>';
                            }
                       $html .= '</ul></div></div>';

                       return (hasView('purchase') && hasPrint('purchase') && hasUpdate('purchase') && hasDelete('purchase') ? $html : 'No Access');
            })
            ->rawColumns(['action', 'checkbox', 'status','date','payment_status'])
            ->make(true);
    }

    public function edit($id)
    {
        $locations = businessLocation::all();
        $currency = $this->currency;
        $suppliers=Contact::where('type','Supplier')
                ->select('id','company_name','first_name','address_line_1','address_line_2','zip_code','city','state','country')
                ->get();
        $purchase=purchases::where('id',$id)->first();
        $currencies=Currencies::get();
        $purchase_detail=purchase_details::with([
                'productVariation'=>function($q){
                    $q->select('id','product_id','variation_template_value_id','default_purchase_price','profit_percent','default_selling_price')
                        ->with(
                        [
                            'variationTemplateValue'=>function($q){
                                $q->select('id','name');
                            }
                        ]);
                },'product'=>function($q){
                    $q->with([
                        'uom'=>function($q){
                            $q->with(['unit_category' => function ($q) {
                                $q->with('uomByCategory');
                            }]);
                        }
                    ]);
                }
        ])->where('purchases_id',$id)->where('is_delete',0)->get();
        // dd($purchase_detail->toArray());

        $setting = $this->setting;
        return view('App.purchase.editPurchase', compact('purchase','locations', 'purchase_detail','suppliers','setting','currency','currencies'));
    }




    public function update($id, Request $request){
        Validator::make([
            'details' => $request->purchase_details,
        ], [
            'details' => 'required',
        ])->validate();
        $request_purchase_details=$request->purchase_details;
        $purchases_data = $this->purchaseData($request);
        $purchases_data['updated_at']=Carbon::now();
        $purchases_data['updated_by']=Auth::user()->id;
        if($request->status==='received'){
            $check=purchases::where('id',$id)->where('status','confirmed')->exists();
            if(!$check){
                $purchases_data['confirm_at']= now();
                $purchases_data['confirm_by']= Auth::user()->id;
            }
        }
        DB::beginTransaction();
        try {
            // update  purchase data
            $selectPurchase=purchases::where('id',$id);
            $purchase=$selectPurchase->first();
            $update=$selectPurchase->update($purchases_data);
            $updatedPurchase=$selectPurchase->first();

            // change transaction when currency and payment account change
            // $this->changeTransaction($purchase,$updatedPurchase,$request);

            $businessLocation = businessLocation::where('id', $purchases_data['business_location_id'])->first();
            if($request_purchase_details){
                    //get old purchase_details
                    $old_purchase_details = array_filter($request_purchase_details, function ($item) {
                        return isset($item['purchase_detail_id']);
                    });
                    // get old purchase_details ids from client [1,2,]
                    $old_purchase_details_ids = array_column($old_purchase_details, 'purchase_detail_id');
                    // update purchase detail's data and related current stock
                    foreach ($old_purchase_details as $pd) {
                        $purchase_detail_id=$pd['purchase_detail_id'];
                        unset($pd["purchase_detail_id"]);
                        $purchase_details=purchase_details::where('id',$purchase_detail_id)->where('is_delete', 0)->first();

                        if($purchase->status == 'received' && $purchases_data['status'] != "received"){
                            // dd($purchase_detail_id);
                            CurrentStockBalance::where('transaction_detail_id', $purchase_detail_id)->where('transaction_type', 'purchase')->delete();
                            stock_history::where('transaction_details_id', $purchase_detail_id)->where('transaction_type', 'purchase')->first()->delete();
                        }
                        $stock_check=currentStockBalance::where('transaction_detail_id',$purchase_detail_id)->where('transaction_type','purchase')->exists();
                        if(!$stock_check){
                            $purchase_details->update($pd);
                            if($purchase->status!='received' && $request->status=='received'){
                                // if(){
                                    $data=$this->currentStockBalanceData($purchase_details,$purchase,'purchase');
                                    if ($businessLocation->allow_purchase_order == 0) {
                                        CurrentStockBalance::create($data);
                                        stock_history::create([
                                            'business_location_id' => $data['business_location_id'],
                                            'product_id' => $data['product_id'],
                                            'variation_id' => $data['variation_id'],
                                            'batch_no' => $data['batch_no'],
                                            'expired_date' => $data['expired_date'],
                                            'transaction_type' => 'purchase',
                                            'transaction_details_id' => $purchase_detail_id,
                                            'increase_qty' => $data['ref_uom_quantity'],
                                            'decrease_qty' => 0,
                                            'ref_uom_id' => $data['ref_uom_id'],
                                        ]);
                                    }
                            //    }
                            }
                        }else {
                            $referencUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($pd['quantity'],$pd['purchase_uom_id']);
                            $requestQty=$referencUomInfo['qtyByReferenceUom'];
                            $referencteUom= $referencUomInfo['referenceUomId'];

                            $currentStock= currentStockBalance::where('transaction_detail_id', $purchase_detail_id)->where('transaction_type', 'purchase');

                            $purchase_quantity=(int) $currentStock->get()->first()->ref_uom_quantity;
                            $current_qty_from_db = (int)  $currentStock->get()->first()->current_quantity;
                            $diff_qty = $purchase_quantity- $current_qty_from_db;
                            $currentResultQty= $requestQty-$diff_qty;
                            $pd['subtotal'] = $pd['uom_price'] * $pd['quantity'];
                            $pd['subtotal_with_discount'] = $pd['subtotal_with_discount'];
                            $pd['expense'] = $pd['per_item_expense'] * $pd['quantity'];
                            $pd['per_ref_uom_price'] = $pd['uom_price'] ?? 0 /  $requestQty;
                            $pd['ref_uom_id'] = $referencteUom;
                            $pd['per_item_tax'] = 0;
                            $pd['tax_amount'] = 0;
                            $pd['subtotal_wit_tax'] = $pd['per_item_expense'] * $pd['quantity'] + 0;
                            $pd['updated_by'] = Auth::user()->id;
                            $pd['updated_at'] = now();

                            if($request->status=='received'){
                                if ($businessLocation->allow_purchase_order == 0) {
                                    $currentStock->first()->update([
                                        "business_location_id"=>$request->business_location_id,
                                        "ref_uom_id"=> $referencteUom,
                                        "batch_no"=>$request->batch_no,
                                        "ref_uom_price"=> $pd['per_ref_uom_price'],
                                        "ref_uom_quantity" => $requestQty,
                                        "current_quantity"=> $currentResultQty >=0 ? $currentResultQty :  0,
                                    ]);
                                    stock_history::where('transaction_details_id', $purchase_detail_id)->where('transaction_type', 'purchase')->first()->update([
                                        'increase_qty'=> $requestQty,
                                        "business_location_id"=>$request->business_location_id,
                                    ]);
                                }else{

                                    return redirect()->route('purchase_list')->with(['warning' => 'Something wrong on Updating Purchase']);
                                    // $te=$currentStock->whereColumn('column_b', '>=', 'column_a');
                                    // dd($te);
                                }
                            }

                            // purchase details will update last because in update diff qty of stock need to check
                            $purchase_details->update($pd);
                        }



                    }
                    //get added purchase_details_ids from database
                    $fetch_purchase_details = purchase_details::where('purchases_id', $id)->where('is_delete',0)->select('id')->get()->toArray();
                    $get_fetched_purchase_details_id = array_column($fetch_purchase_details, 'id');
                    //to remove edited purchase_detais that are already created
                    $old_purchase_details_id_for_delete = array_diff($get_fetched_purchase_details_id, $old_purchase_details_ids); //for delete row
                    foreach ($old_purchase_details_id_for_delete as $p_id) {
                        purchase_details::where('id', $p_id)->update([
                            'is_delete' => 1,
                            'deleted_at' => now(),
                            'deleted_by' => Auth::user()->id,
                        ]);
                        CurrentStockBalance::where('transaction_detail_id',$p_id)->where('transaction_type', 'purchase')->delete();
                    }

                    //to create purchase details
                    $new_purchase_details = array_filter($request_purchase_details, function ($item) {
                        return !isset($item['purchase_detail_id']);
                    });
                    if(count($new_purchase_details)>0){
                        $this->purchase_detail_creation($new_purchase_details, $id, $purchase);
                    }

            }else{
                $fetch_purchase_details = purchase_details::where('purchases_id', $id)->where('is_delete', 0)->select('id')->get();
                foreach ($fetch_purchase_details as $p) {
                    CurrentStockBalance::where('trnasaction_detail_id', $p->id)->where('transaction_type', 'purchase')->delete();
                }
                purchase_details::where('purchases_id', $id)->update([
                    'is_delete'=>1,
                    'deleted_at'=>now(),
                    'deleted_by'=>Auth::user()->id,
                ]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('purchase_list')->with(['warning' => 'Something wrong on Updating Purchase']);
            throw $e;
        }
        // return back()->with(['success' => 'Successfully Updated Purchase']);
        return redirect()->route('purchase_list')->with(['success' => 'Successfully Updated Purchase']);
    }


    public function purhcaseInvoice($id)
    {
        $purchase=purchases::with('business_location_id', 'supplier', 'purchased_by','currency')->where('id',$id)->first()->toArray();
        $location=$purchase['business_location_id'];
        $purchase_details=purchase_details::where('purchases_id',$purchase['id'])
                        ->where('is_delete','0')
                        ->with(['product','currency', 'purchaseUom','productVariation'=>function($q){
                            $q->with('variationTemplateValue');
                        }])
                        ->get();
        $invoiceHtml = view('App.purchase.invoice.invoice',compact('purchase','location','purchase_details'))->render();
        return response()->json(['html' => $invoiceHtml]);
    }
    public function purchaseDetail($id)
    {

        $purchase = purchases::with('business_location_id', 'purchased_by','confirm_by','supplier','updated_by', 'currency')->where('id', $id)->first()->toArray();
        $location = $purchase['business_location_id'];
        $purchase_details=purchase_details::with(['productVariation'=>function($q){
            $q->select('id','product_id','variation_template_value_id')
                ->with([
                    'product'=>function($q){
                     $q->select('id','name','product_type','uom_id');
            },
            'variationTemplateValue'=>function($q){
                $q->select('id','name');
            }]);
        },'product', 'purchaseUom','currency'])
        ->where('purchases_id',$id)->where('is_delete',0)->get();
        $setting=$this->setting;
        return view('App.purchase.DetailView.purchaseDetail',compact(
            'purchase',
            'location',
            'purchase_details',
            'setting'
        ));
    }



    public function softOneItemDelete($id){
        purchases::where('id',$id)->update([
            'is_delete'=>1,
            'deleted_by'=>Auth::user()->id,
            'deleted_at'=>now()
        ]);
        $purchaseDetails= purchase_details::where('purchases_id', $id);
        foreach ($purchaseDetails->get() as $pd) {
            CurrentStockBalance::where('transaction_type','purchase')->where('transaction_detail_id',$pd->id)->delete();
        }
        $purchaseDetails->update([
            'is_delete' => 1,
            'deleted_by' => Auth::user()->id,
            'deleted_at' => now()
        ]);
        $data=[
            'success'=>'Successfully Deleted'
        ];
        return response()->json($data, 200);
    }

    public function softSelectedDelete()
    {
        $ids= request('data');
        DB::beginTransaction();
        try {
            foreach ($ids as $id) {
                purchases::where('id',$id)->update([
                    'is_delete'=>1,
                    'deleted_by'=>Auth::user()->id,
                    'deleted_at'=>now()
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

    public function getUnits($id)
    {
        $uoms = $this->UOM_unit($id);
        return response()->json($uoms->toArray(), 200);
    }




    protected function purchase_detail_creation(Array $purchases_details,$purchase_id,$purchase)
    {

        foreach ($purchases_details as $key=>$pd) {
            $referencteUom = UomHelper::getReferenceUomInfoByCurrentUnitQty($pd['quantity'], $pd['purchase_uom_id']);
            $pd['purchases_id'] = $purchase_id;
            $pd['subtotal'] =$pd['uom_price'] * $pd['quantity'];
            $pd['subtotal_with_discount'] = $pd['subtotal_with_discount'];
            $pd['currency_id'] = $purchase->currency_id;
            $pd['expense'] = $pd['per_item_expense'] * $pd['quantity'];
            $pd['ref_uom_id'] = $referencteUom['referenceUomId'];
            $pd['per_ref_uom_price'] =$pd['uom_price'] ?? 0 /$referencteUom['qtyByReferenceUom'] ?? 0;
            $pd['batch_no'] = UomHelper::generateBatchNo($pd['variation_id']);
            $pd['per_item_tax']=0;
            $pd['tax_amount'] =0;
            $pd['subtotal_wit_tax'] = $pd['per_item_expense'] * $pd['quantity'] + 0;
            $pd['created_by'] = Auth::user()->id;
            $pd['purchased_by'] = Auth::user()->id;
            $pd['updated_by'] = Auth::user()->id;
            $pd['deleted_by'] = Auth::user()->id;
            $pd['is_delete'] = 0;
            $pd=purchase_details::create($pd);
            $this->currentStockBalanceCreation($pd,$purchase,'purchase');
        }
    }

    private function UOM_unit($id){
        return UOM::where('uomset_id', $id)
        ->leftJoin('units', 'uoms.unit_id', '=', 'units.id')
        ->select('units.id', 'name as text')
        ->get();
    }

    private function purchaseData($request)
    {
        if($request->paid_amount == 0){
            $payment_status='pending';
        }elseif($request->paid_amount >= $request->total_purchase_amount ){
            $payment_status='paid';
        }else{
            $payment_status='partial';
        }
        return [
            'business_location_id' => $request->business_location_id,
            'contact_id' => $request->contact_id,
            'status' => $request->status,
            'purchase_amount' => $request->purchase_amount,
            'total_line_discount' => $request->total_line_discount,
            'extra_discount_type' => $request->extra_discount_type,
            'extra_discount_amount' => $request->extra_discount_amount,
            'total_discount_amount' => $request->total_discount_amount,
            'purchase_expense' => $request->purchase_expense,
            'total_purchase_amount' => $request->total_purchase_amount,
            'currency_id' => $request->currency_id,
            'paid_amount' => $request->paid_amount,
            'purchased_at' => $request->purchased_at,
            'total_purchase_amount' => $request->total_purchase_amount,
            'balance_amount' => $request->balance_amount,
            'payment_status'=>$payment_status
        ];
    }

    protected function currentStockBalanceCreation($purchase_detail_data,$purchase,$type){
        $data=$this->currentStockBalanceData($purchase_detail_data,$purchase,$type);
        $businessLocation=businessLocation::where('id', $data['business_location_id'])->first();
        if($purchase->status== 'received'){
            if($businessLocation->allow_purchase_order==1){
                return;
            }
            CurrentStockBalance::create($data);
            stock_history::create([
                'business_location_id' => $data['business_location_id'],
                'product_id' => $data['product_id'],
                'variation_id' => $data['variation_id'],
                'batch_no' => $data['batch_no'],
                'expired_date' => $data['expired_date'],
                'transaction_type' => 'purchase',
                'transaction_details_id' => $purchase_detail_data->id,
                'increase_qty' => $data['ref_uom_quantity'],
                'decrease_qty' => 0,
                'ref_uom_id' => $data['ref_uom_id'],
            ]);
        }


    }

    protected function currentStockBalanceData($purchase_detail_data,$purchase,$type)
    {
        $referencUomInfo=UomHelper::getReferenceUomInfoByCurrentUnitQty( $purchase_detail_data->quantity,$purchase_detail_data->purchase_uom_id);
        $batchNo=UomHelper::generateBatchNo($purchase_detail_data['variation_id']);
        $per_ref_uom_price_by_default_currency=exchangeCurrency($purchase_detail_data->per_ref_uom_price,$purchase->currency_id,$this->currency->id) ?? 0;
        return [
            "business_location_id"=> $purchase->business_location_id,
            "product_id"=> $purchase_detail_data->product_id,
            "variation_id" => $purchase_detail_data->variation_id,
            "transaction_type"=>$type,
            "transaction_detail_id"=> $purchase_detail_data->id,
            "batch_no"=> $purchase_detail_data->batch_no,
            "expired_date"=>$purchase_detail_data->expired_date,
            "uomset_id"=> $purchase_detail_data->uomset_id,
            'batch_no'=>$batchNo,
            "ref_uom_id"=> $referencUomInfo['referenceUomId'],
            "ref_uom_quantity"=> $referencUomInfo['qtyByReferenceUom'],
            "ref_uom_price"=> $per_ref_uom_price_by_default_currency,
            "current_quantity"=> $referencUomInfo['qtyByReferenceUom'],
            'currency_id' => $purchase->currency_id,
        ];
    }

    protected function getProductForPurchase(Request $request)
    {
        $q=$request->data;
        $products = Product::select('id', 'name', 'product_code', 'sku', 'product_type','uom_id', 'purchase_uom_id')
        ->where('can_purchase',1)
        ->where('name', 'like', '%' . $q . '%')
        ->orWhere('sku', 'like', '%' . $q . '%')
        ->with([
            'productVariations' => function ($query) {
                $query->select('id', 'product_id', 'variation_template_value_id', 'default_purchase_price', 'default_selling_price')
                ->with([
                    'variationTemplateValue' => function ($query) {
                        $query->select('id', 'name');
                    }
                ]);
            },'uom'=>function($q){
                $q->with(['unit_category'=>function($q){
                    $q->with('uomByCategory');
                }]);
            }
        ]
        )->get()->toArray();
        foreach ($products as $product) {
            if ($product['product_type'] == 'variable') {
                $p = $product['product_variations'];
                foreach ($p as $variation) {
                    $variation_product = [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'sku' => $product['sku'],
                        'purchase_uom_id'=>$product['purchase_uom_id'],
                        'uom_id'=>$product['uom_id'],
                        'uom'=>$product['uom'],
                        'variation_id' => $variation['id'],
                        'product_type' => 'sub_variable',
                        'variation_name' => $variation['variation_template_value']['name'],
                        'default_purchase_price' => $variation['default_purchase_price'],
                        'default_selling_price' => $variation['default_selling_price']
                    ];
                    array_push($products, $variation_product);
                }
            }
        }
        return response()->json($products, 200);
    }

    protected function makePayment($purchase,$payment_account_id,$increasePayment=false,$increaseAmount=0){
        $paymentAmount=$increasePayment ? $increaseAmount :$purchase->paid_amount;
        $data=[
            'payment_voucher_no'=>generatorHelpers::paymentVoucher(),
            'payment_date'=>now(),
            'transaction_type'=>'purchase',
            'transaction_id'=>$purchase->id,
            'transaction_ref_no'=>$purchase->purchase_voucher_no,
            'payment_method'=>'card',
            'payment_account_id'=>$payment_account_id,
            'payment_type'=>'credit',
            'payment_amount'=>$paymentAmount,
            'currency_id'=>$purchase->currency_id,
        ];
        paymentsTransactions::create($data);
        $accountInfo=paymentAccounts::where('id',$payment_account_id);
        if($accountInfo){
            $currentBalanceFromDb=$accountInfo->first()->current_balance ;
            $finalCurrentBalance=$currentBalanceFromDb-$paymentAmount;
            $accountInfo->update([
                'current_balance'=>$finalCurrentBalance,
            ]);
        }
        $suppliers=Contact::where('id',$purchase->contact_id)->first();
        if($purchase->balance_amount > 0){
            $suppliers_payable=$suppliers->payable_amount;
            $suppliers->update([
                'payable_amount'=>$suppliers_payable+$purchase->balance_amount
            ]);
        }else if($purchase->balance_amount < 0){
            $suppliers_receivable=$suppliers->receivable_amount;
            $suppliers->update([
                'receivable_amount'=>$suppliers_receivable+$purchase->receivable_amount
            ]);
        }
    }
    protected function changeTransaction($purchase,$updatedPurchase,$request){
        $transaction=paymentsTransactions::orderBy('id','DESC')
                                         ->where('transaction_ref_no',$purchase->purchase_voucher_no)
                                         ->where('transaction_id',$purchase->id)
                                         ->where('payment_type','credit');
                                        //  dd($transaction->first()->toArray());
        $oldTransaction=$transaction->first();
        if($oldTransaction->payment_account_id != $request->payment_account && isset($request->payment_account) ){
            $this->depositeToBeforeChangeAcc($oldTransaction,$purchase);
            $this->makePayment($updatedPurchase,$request->payment_account);
        }elseif($updatedPurchase->paid_amount>$purchase->paid_amount){

            $increaseAmount=$updatedPurchase->paid_amount-$purchase->paid_amount;
            $this->makePayment($updatedPurchase,$request->payment_account,true,$increaseAmount);

        }elseif($updatedPurchase->paid_amount<=$purchase->paid_amount){

            $decreaseAmount=$purchase->paid_amount-$updatedPurchase->paid_amount;
            $this->depositeToBeforeChangeAcc($oldTransaction,$purchase,true,$decreaseAmount);
        }
        // die;
    }

    public function depositeToBeforeChangeAcc($oldTransaction,$purchase,$decreasePayment=false,$decreaseAmount=0){
        $paymentAmount=$decreasePayment ? $decreaseAmount :$purchase->paid_amount;
        $data=[
                'payment_voucher_no'=>generatorHelpers::paymentVoucher(),
                'payment_date'=>now(),
                'transaction_type'=>'purchase',
                'transaction_id'=>$purchase->id,
                'transaction_ref_no'=>$purchase->purchase_voucher_no,
                'payment_method'=>'card',
                'payment_account_id'=>$oldTransaction->payment_account_id,
                'payment_type'=>'debit',
                'payment_amount'=>$paymentAmount,
                'currency_id'=>$purchase->currency_id,
            ];

            paymentsTransactions::create($data);
            $accountInfo=paymentAccounts::where('id',$oldTransaction->payment_account_id);
            if($accountInfo){
                $currentBalanceFromDb=$accountInfo->first()->current_balance ;
                $finalCurrentBalance=$currentBalanceFromDb+$paymentAmount;
                $accountInfo->update([
                    'current_balance'=>$finalCurrentBalance,
                ]);
            }
    }
}

