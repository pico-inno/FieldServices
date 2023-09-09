<?php

namespace Modules\StockInOut\Http\Controllers;


use App\Helpers\UomHelper;
use App\Models\BusinessUser;
use App\Models\Contact\Contact;
use App\Models\CurrentStockBalance;
use App\Models\Product\Unit;
use App\Models\Product\UOM;
use App\Models\Product\UOMSet;
use App\Models\purchases\purchase_details;
use App\Models\purchases\purchases;
use App\Models\settings\businessSettings;
use App\Models\stock_history;
use Exception;
use Illuminate\Http\Request;
//use App\Models\Stock\Stockin;
use App\Models\Product\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
//use App\Models\Stock\StockinDetail;
use App\Http\Controllers\Controller;
use App\Http\Controllers\stockHistoryController;
use Illuminate\Support\Facades\Auth;
use App\Models\settings\businessLocation;
use App\Models\Product\VariationTemplates;
use Modules\StockInOut\Entities\Stockin;
use Modules\StockInOut\Entities\StockinDetail;
use Modules\StockInOut\Http\Requests\StoreStockInRequest;
use Modules\StockInOut\Http\Requests\UpdateStockInRequest;
use Yajra\DataTables\Facades\DataTables;

class StockInController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive', 'appType', 'moduleName:StockInOut']);
        $this->middleware('canView:stockin')->only(['index', 'show']);
        $this->middleware('canCreate:stockin')->only(['create', 'store']);
        $this->middleware('canUpdate:stockin')->only(['edit', 'update']);
        $this->middleware('canDelete:stockin')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = businessLocation::select('id', 'name')->get();
        $stockins_person = BusinessUser::select('id', 'username')->get();
        $stockins = Stockin::with([
            'businessLocation:id,name',
            'stockinPerson:id,username'
        ])->get();

        return view('stockinout::in.index', [
            'locations' => $locations,
            'stockins' => $stockins,
            'stockinsperson' => $stockins_person,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function upcoming(){
        $locations = businessLocation::select('id', 'name')->get();
        $stockins_person = BusinessUser::select('id', 'username')->get();
        $stockins = Stockin::with([
            'stockindetails',
            'businessLocation:id,name',
            'stockinPerson:id,username'
        ])->get();


        $upcomingLists = purchases::with(['businessLocation:id,name','created_by', 'purchase_details'])
            ->whereIn('status', ['order', 'partial'])
            ->get()
            ->map(function ($purchase) {
//                $purchase->location_name = $purchase->businessLocation->name;
                $purchase->total_received_quantity = $purchase->purchase_details->sum('received_quantity');
                $purchase->total_quantity = $purchase->purchase_details->sum('quantity');
                return $purchase;
            });


        return view('stockinout::in.upcoming', [
            'locations' => $locations,
            'stockins' => $stockins,
            'stockinsperson' => $stockins_person,
            'upcomingLists' => $upcomingLists,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function receiveProduct(string $id)
    {
        $stockin_persons = BusinessUser::with('personal_info')->get();
        $locations = businessLocation::all();
        $receivedData = purchases::with(['supplier:id,company_name', 'businessLocation:id,name'])
            ->where('id', $id)
            ->select('id', 'purchase_voucher_no', 'business_location_id', 'contact_id', 'status', 'purchase_amount', 'total_line_discount', 'extra_discount_type', 'extra_discount_amount', 'total_discount_amount', 'purchase_expense', 'total_purchase_amount', 'paid_amount', 'balance_amount', 'purchased_at', 'purchased_by', 'confirm_at', 'confirm_by', 'created_at', 'created_by', 'updated_at', 'updated_by', 'is_delete', 'deleted_at', 'deleted_by')
            ->first();

        $setting = businessSettings::all()->first();
        return view('stockinout::in.receiveProduct', [
            'stockin_persons' => $stockin_persons,
            'locations' => $locations,
            'received_data' => $receivedData,
            'setting' => $setting,
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $stockin_persons = BusinessUser::with('personal_info')->get();
        $locations = businessLocation::all();
        $purchases = purchases::whereIn('status', ['order', 'partial'])
            ->select('id', 'contact_id', 'business_location_id')
            ->with(['supplier:id,company_name', 'businessLocation:id,name'])
            ->get();
        $contact_id_array = $purchases->pluck('contact_id');
        $suppliers = Contact::where('type', 'Supplier')
            ->whereIn('id', $contact_id_array)
            ->select('id', 'company_name')->get();
        $setting = businessSettings::all()->first();

        foreach ($purchases as $purchase) {
            $purchase->supplier_name = $purchase->supplier->company_name;
        }
        return view('stockinout::in.add', [
            'stockin_persons' => $stockin_persons,
            'locations' => $locations,
            'suppliers' => $suppliers,
            'setting' => $setting,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStockInRequest $request)
    {
        // return $request;

        $validatedData = $request->validate([
            'business_location_id' => 'required|exists:business_locations,id',
            'stockin_date' => 'required',
            'stockin_person' => 'required|exists:business_users,id'
        ], [
            'business_location_id.required' => 'The business location is required.',
            'business_location_id.exists' => 'The selected business location is invalid.',
            'stockin_date.required' => 'The stockin date is required.',
            'stockin_person.required' => 'The stockin person is required.',
            'stockin_person.exists' => 'The selected stockin person is invalid.'
        ]);



        DB::transaction(function () use ($request){
            $lotSerialContorl = businessSettings::all()->first()->lot_control;
            $stockin_details = $request->stockin_details;
            $purchaseDetailsToUpdate = [];
            $stockin_date = date('Y-m-d', strtotime($request->stockin_date));
            $prefix = 'SI';
            $voucherNumber = $this->generateVoucherNumber($prefix);
            $created_by = Auth::user()->id;


            $stockin = Stockin::create([
                'business_location_id' => $request->business_location_id,
                'stockin_voucher_no' => $voucherNumber,
                'stockin_date' => $stockin_date,
                'stockin_person' => $request->stockin_person,
                'note' => $request->note,
                'created_at' => now(),
                'created_by' => $created_by,
            ]);

            foreach ($stockin_details as $stockin_detail){

                $lotSerialDetails = json_decode($stockin_detail['lot_sertial_details'], true);
                $referencUomInfo=UomHelper::getReferenceUomInfoByCurrentUnitQty( $stockin_detail['in_quantity'],$stockin_detail['purchase_uom_id']);
                $variation_id = $stockin_detail['variation_id'];

                $stockinDetail = StockinDetail::create([
                    'stockin_id' => $stockin->id,
                    'product_id' => $stockin_detail['product_id'],
                    'variation_id' => $stockin_detail['variation_id'],
                    'transaction_type' => 'purchase',
                    'transaction_detail_id' => $stockin_detail['purchase_detail_id'],
                    'uom_id' => $stockin_detail['uom_id'],
                    'quantity' => $stockin_detail['in_quantity'],
                    'remark' => $stockin_detail['remark'],
                    'created_at' => now(),
                    'created_by' => $created_by,
                ]);

                $checkForBatch = StockinDetail::where('transaction_type', 'purchase')
                    ->where('transaction_detail_id',  $stockin_detail['purchase_detail_id'])->pluck('id');

                $currentStockBalance_batchNo = CurrentStockBalance::where('transaction_type', 'stock_in')
                    ->whereIn('transaction_detail_id', $checkForBatch )
                    ->where('variation_id', $variation_id)
                    ->first();

                if ($currentStockBalance_batchNo == null){
                    $batch_no = UomHelper::generateBatchNo($variation_id, 'SI', 6);
                }else{
                    $batch_no = $currentStockBalance_batchNo->batch_no;
                }
                if($lotSerialContorl == 'on'){
                    foreach($lotSerialDetails as $lotSerialDetail){

                        $lotReferencUomInfo=UomHelper::getReferenceUomInfoByCurrentUnitQty( $lotSerialDetail['number_of_in'],$lotSerialDetail['lot_uom_id']);

                        $currentStockBalance = CurrentStockBalance::create([
                            'business_location_id' => $request->business_location_id,
                            'product_id' => $stockin_detail['product_id'],
                            'variation_id' => $stockin_detail['variation_id'],
                            'transaction_type' => 'stock_in',
                            'transaction_detail_id' => $stockinDetail->id,
                            'batch_no' => $batch_no,
                            'lot_serial_no' => $lotSerialDetail['lot_serials'],
                            'expired_date' => $lotSerialDetail['expire_date'],
                            'ref_uom_id' => $lotReferencUomInfo['referenceUomId'],
                            'ref_uom_quantity' => $lotReferencUomInfo['qtyByReferenceUom'],
                            'ref_uom_price' => $stockin_detail['per_ref_uom_price'],
                            'current_quantity' => $lotReferencUomInfo['qtyByReferenceUom'],
                        ]);

                        $stockHistory = stock_history::create([
                            'business_location_id' => $request->business_location_id,
                            'product_id' => $stockin_detail['product_id'],
                            'variation_id' => $stockin_detail['variation_id'],
                            'lot_no' => $lotSerialDetail['lot_serials'],
                            'expired_date' => $lotSerialDetail['expire_date'],
                            'transaction_type' => 'stock_in',
                            'transaction_details_id' => $stockinDetail->id,
                            'increase_qty' => $lotReferencUomInfo['qtyByReferenceUom'],
                            'decrease_qty' => 0,
                            'ref_uom_id' => $lotReferencUomInfo['referenceUomId'],
                        ]);
                    }
                    $calReceivedQty = $stockin_detail['in_quantity'] + $stockin_detail['received_quantity'];
                    $purchaseDetailsToUpdate[$stockin_detail['purchase_detail_id']] = $calReceivedQty;
                    $purchaseToUpdate[$stockin_detail['purchase_id']] = $calReceivedQty;
                }else{
                    $currentStockBalance = CurrentStockBalance::create([
                        'business_location_id' => $request->business_location_id,
                        'product_id' => $stockin_detail['product_id'],
                        'variation_id' => $stockin_detail['variation_id'],
                        'transaction_type' => 'stock_in',
                        'transaction_detail_id' => $stockinDetail->id,
                        'batch_no' => $batch_no,
                        'lot_serial_no' => null,
                        'expired_date' => null,
                        'ref_uom_id' => $referencUomInfo['referenceUomId'],
                        'ref_uom_quantity' => $referencUomInfo['qtyByReferenceUom'],
                        'ref_uom_price' => $stockin_detail['per_ref_uom_price'],
                        'current_quantity' => $referencUomInfo['qtyByReferenceUom'],
                    ]);

                    $stockHistory = stock_history::create([
                        'business_location_id' => $request->business_location_id,
                        'product_id' => $stockin_detail['product_id'],
                        'variation_id' => $stockin_detail['variation_id'],
                        'lot_no' => null,
                        'expired_date' => null,
                        'transaction_type' => 'stock_in',
                        'transaction_details_id' => $stockinDetail->id,
                        'increase_qty' => $referencUomInfo['qtyByReferenceUom'],
                        'decrease_qty' => 0,
                        'ref_uom_id' => $referencUomInfo['referenceUomId'],
                    ]);
                    $calReceivedQty = $stockin_detail['in_quantity'] + $stockin_detail['received_quantity'];
                    $purchaseDetailsToUpdate[$stockin_detail['purchase_detail_id']] = $calReceivedQty;
                    $purchaseToUpdate[$stockin_detail['purchase_id']] = $calReceivedQty;
                }

            }




            // ====== Being:: Data update to purchase and detail table ======
            foreach ($purchaseDetailsToUpdate as $purchaseId => $receivedQuantity) {
                purchase_details::where('id', $purchaseId)->update(['received_quantity' => $receivedQuantity]);
            }

            // Fetch purchases with partial or order status
            $purchasesToUpdate = purchases::whereIn('id', array_keys($purchaseToUpdate))
                ->whereHas('purchase_details', function ($query) {
                    $query->where('received_quantity', '<>', 'quantity');
                })
                ->get();

            foreach ($purchasesToUpdate as $purchase) {
                $details = $purchase->purchase_details;

                // Check received qty in all purchase details and update status
                $completed = $details->every(function ($detail) {
                    return $detail->received_quantity === $detail->quantity;
                });


                $purchase->status = $completed ? 'received' : 'partial';
                $purchase->save();
            }
            // ====== End:: Data update to purchase and detail table ======

        });


        $route = $request->receive_product== 'receive_form' ? 'stock-in.upcoming.index' : 'stock-in.index';
        return redirect(route($route))->with(['success' => 'Stockin created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $stockin = Stockin::with('stockindetails')->findOrFail($id);
        $stockin_details = StockinDetail::where('stockin_id', $id)
            ->with(['product:id,name,product_type', 'uom:id,name,short_name', 'productVariation' => function($query) {
                $query->select('id', 'variation_template_value_id')
                    ->with('variationTemplateValue:id,name');
            }])
            ->get();


        $stockin_person = BusinessUser::findOrFail($stockin->stockin_person)->username;
        $location = businessLocation::findOrFail($stockin->business_location_id)->name;



        return view('stockinout::in.view', [
            'stockin' => $stockin,
            'stockin_person' => $stockin_person,
            'location' => $location,
            'stockin_details' => $stockin_details,
        ]);
    }

    public function showDataDetails(Request $request)
    {

        $stockin_details = StockinDetail::where('stockin_id', $request->stockinId)
        ->with([
            'product:id,name,product_type',
            'uom:id,name,short_name',
            'productVariation' => function ($query) {
                $query->select('id', 'variation_template_value_id')
                    ->with('variationTemplateValue:id,name');
            },
        ])
        ->get();

        foreach ($stockin_details as $detail){

            $currentStock = CurrentStockBalance::where('transaction_type', 'stock_in')
            ->with('uom')
            ->where('transaction_detail_id', $detail->id)->select('id','lot_serial_no', 'expired_date', 'ref_uom_id', 'ref_uom_quantity')->get();

            $detail->current_banalce= $currentStock;

        }

        // $products = Product::with('productVariations', 'category', 'brand')->get();

        return DataTables::of($stockin_details)

        ->addColumn('productName', function($stockin_detail) {
           return $stockin_detail->product->name;
        })
        ->addColumn('receivedQty', function($stockin_detail){
            return $stockin_detail->quantity;
        })
        ->addColumn('uomName', function($stockin_detail){
            return $stockin_detail->uom->name;
        })
        ->addColumn('remark', function($stockin_detail){
            return $stockin_detail->remark;
        })
        ->addColumn('current_banalce', function($stockin_detail){
            return $stockin_detail->current_banalce;
        })

        ->rawColumns(['productName', 'receivedQty', 'uomName', 'remark'])
        ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $stockin = Stockin::with('stockindetails')->findOrFail($id);
        $stockin_details = $stockin->stockindetails;

        $stockin_persons = BusinessUser::with('personal_info')->get();
        $locations = businessLocation::all();
        $contact_id_array = purchases::whereIn('status', ['order', 'partial'])
            ->select('contact_id')
            ->distinct()
            ->pluck('contact_id');
        $suppliers = Contact::whereIn('id', $contact_id_array)->select('id', 'company_name')->first();


        $contactIds = [];
        $purchaseIds = [];
        foreach ($stockin_details as $detail){
            $purchase_detail = purchase_details::with('purchase')->findOrFail($detail->transaction_detail_id);
            $currentStock = CurrentStockBalance::where('transaction_type', 'stock_in')
            ->where('transaction_detail_id', $detail->id)->select('id','lot_serial_no', 'expired_date', 'ref_uom_id', 'ref_uom_quantity')->get();

            $detail->purchases_id = $purchase_detail->purchases_id;
            $detail->current_banalce= $currentStock;


            if ($purchase_detail) {
                $contact_id = $purchase_detail->purchase->contact_id;
                $purchase_id = $purchase_detail->purchase->id;

                if (!in_array($contact_id, $contactIds)) {
                    $contactIds[] = $contact_id;
                }

                if (!in_array($purchase_id, $purchaseIds)) {
                    $purchaseIds[] = $purchase_id;
                }
            }
        }

        $uniqueContactId = null;
        if (count($contactIds) === 1) {
            $uniqueContactId = $contactIds[0];
        }
        // return $stockin_details;
        $setting = businessSettings::all()->first();
        return view('stockinout::in.edit', [
            'stockin' => $stockin,
            'stockin_details' => $stockin_details,
            'uniqueContactId' => $uniqueContactId,
            'stockin_persons' => $stockin_persons,
            'locations' => $locations,
            'suppliers' => $suppliers,
            'purchaseIds' => $purchaseIds,
            'setting' => $setting,
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStockInRequest $request, string $id)
    {
//return $request;

        $requestStockinDetails = $request->stockin_details;
        DB::beginTransaction();
        $lotSerialContorl = businessSettings::all()->first()->lot_control;
        try {
            if ($requestStockinDetails) {


                // Pre-added stockin data
                $stockinDetailsWithStockinId = array_filter($requestStockinDetails, function ($item) {
                    return isset($item['stockin_detail_id']);
                });

                $oldStockinDetailsIds = array_column($stockinDetailsWithStockinId, 'stockin_detail_id');


                // Remove pre-added stockin details when user removes them with the current stock balance
                $transactionDetailIds = StockinDetail::where('stockin_id', $id)
                    ->whereNotIn('id', $oldStockinDetailsIds)
                    ->pluck('transaction_detail_id');


                //Return decrease qty of purchase order
                foreach ($transactionDetailIds as $transactionDetailId){
                    $filteredQuantities = array_column($requestStockinDetails, 'received_quantity', 'transactionDetailId');
                    $filteredQuantity = $filteredQuantities[$transactionDetailId] ?? null;
                    purchase_details::where('id', $transactionDetailId)->update([
                        'received_quantity' => $filteredQuantity
                    ]);

                }

                CurrentStockBalance::where('transaction_type', 'stock_in')
                    ->whereNotIn('transaction_detail_id', $oldStockinDetailsIds)
                    ->delete();

                StockinDetail::where('stockin_id', $id)
                    ->whereNotIn('id', $oldStockinDetailsIds)
                    ->update(['deleted_by' => Auth::id(), 'is_delete' => true]);

                StockinDetail::where('stockin_id', $id)
                    ->whereNotIn('id', $oldStockinDetailsIds)
                    ->delete();

                // End: remove pre-add details data
                if($lotSerialContorl == 'on'){
                    foreach ($stockinDetailsWithStockinId as $item) {

                        //update existing data when changes
                        if ($item['lot_sertial_details'] != 'update_lot'){

                            $lotSerialDetails = json_decode($item['lot_sertial_details'], true);

                            $stockinDetailId = $item['stockin_detail_id'];

                            $transactionDetailId = StockinDetail::where('stockin_id', $id)
                                ->where('id', $stockinDetailId)
                                ->first()
                                ->transaction_detail_id;


                            if ($item['in_quantity'] > $item['old_qty']) {
                                purchase_details::where('id', $transactionDetailId)
                                    ->increment('received_quantity', $item['in_quantity'] - $item['old_qty']);
                            }

                            if ($item['in_quantity'] < $item['old_qty']) {
                                purchase_details::where('id', $transactionDetailId)
                                    ->decrement('received_quantity', $item['old_qty'] - $item['in_quantity']);
                            }

                            // Pre-added lot details data
                            $lotDetailsWithCurrentId = array_filter($lotSerialDetails, function ($item) {
                                return isset($item['current_balance_id']);
                            });

                            $oldLotDetailsIds = array_column($lotDetailsWithCurrentId, 'current_balance_id');

                            // Remove pre-added lot serial details when user removes from current stock balance
                           CurrentStockBalance::where('transaction_type', 'stock_in')
                                            ->where('transaction_detail_id', $item['stockin_detail_id'])
                                            ->whereNotIn('id', $oldLotDetailsIds)->delete();

                            foreach ($lotDetailsWithCurrentId as $lotSerialDetail){

                                $lotReferencUomInfo=UomHelper::getReferenceUomInfoByCurrentUnitQty( $lotSerialDetail['number_of_in'],$lotSerialDetail['lot_uom_id']);
                                CurrentStockBalance::where('id', $lotSerialDetail['current_balance_id'])
                                    ->update([
                                        'lot_serial_no' => $lotSerialDetail['lot_serials'],
                                        'expired_date' => $lotSerialDetail['expire_date'],
                                        'ref_uom_quantity' => $lotReferencUomInfo['qtyByReferenceUom'],
                                        'current_quantity' => $lotReferencUomInfo['qtyByReferenceUom']
                                    ]);


                            }



                            //Add new lot row
                            $lotDetailsWithoutCurrentBalanceId = array_filter($lotSerialDetails, function ($item) {
                                return !isset($item['current_balance_id']);
                            });


                            foreach ($lotDetailsWithoutCurrentBalanceId as $lotSerialDetail){

                                $lotReferencUomInfo=UomHelper::getReferenceUomInfoByCurrentUnitQty( $lotSerialDetail['number_of_in'],$lotSerialDetail['lot_uom_id']);

                                $currentStockBalance = CurrentStockBalance::create([
                                    'business_location_id' => $request->business_location_id,
                                    'product_id' => $item['product_id'],
                                    'variation_id' => $item['variation_id'],
                                    'transaction_type' => 'stock_in',
                                    'transaction_detail_id' => $item['stockin_detail_id'],
                                    'batch_no' => null,
                                    'lot_serial_no' => $lotSerialDetail['lot_serials'],
                                    'expired_date' => $lotSerialDetail['expire_date'],
                                    'ref_uom_id' => $lotReferencUomInfo['referenceUomId'],
                                    'ref_uom_quantity' => $lotReferencUomInfo['qtyByReferenceUom'],
                                    'ref_uom_price' => $item['per_ref_uom_price'],
                                    'current_quantity' => $lotReferencUomInfo['qtyByReferenceUom'],
                                ]);

                                $stockHistory = stock_history::create([
                                    'business_location_id' => $request->business_location_id,
                                    'product_id' => $item['product_id'],
                                    'variation_id' => $item['variation_id'],
                                    'lot_no' => $lotSerialDetail['lot_serials'],
                                    'expired_date' => $lotSerialDetail['expire_date'],
                                    'transaction_type' => 'stock_in',
                                    'transaction_details_id' => $item['stockin_detail_id'],
                                    'increase_qty' => $lotReferencUomInfo['qtyByReferenceUom'],
                                    'decrease_qty' => 0,
                                    'ref_uom_id' => $lotReferencUomInfo['referenceUomId'],
                                ]);

                            }


                            unset($item['stockin_detail_id']);


                            StockinDetail::where('id', $stockinDetailId)
                                ->where('stockin_id', $id)
                                ->update([
                                    'quantity' => $item['in_quantity'],
                                    'remark' => $item['remark'],
                                    'updated_by' => Auth::id(),
                                ]);

                            stock_history::where('transaction_type', 'stock_in')
                                ->where('transaction_details_id', $stockinDetailId)
                                ->update([
                                    'increase_qty' => $lotReferencUomInfo['qtyByReferenceUom'],
                                ]);
                        }
                    }



                    //
                    $stockinDetailsWithoutStockinId = array_filter($requestStockinDetails, function ($item) {
                        return !isset($item['stockin_detail_id']);
                    });
                    $purchaseDetailsToUpdate = [];

                    foreach ($stockinDetailsWithoutStockinId as $item){
                        $lotSerialDetails = json_decode($item['lot_sertial_details'], true);
                        $stockinDetail = StockinDetail::create([
                            'stockin_id' => $id,
                            'product_id' => $item['product_id'],
                            'variation_id' => $item['variation_id'],
                            'transaction_type' => 'purchase',
                            'transaction_detail_id' => $item['purchase_detail_id'],
                            'uom_id' => $item['purchase_uom_id'],
                            'quantity' => $item['in_quantity'],
                            'remark' => $item['remark'],
                            'created_at' => now(),
                            'created_by' => Auth::id(),
                        ]);

                        $checkForBatch = StockinDetail::where('transaction_type', 'purchase')
                            ->where('transaction_detail_id',  $stockinDetail['purchase_detail_id'])->pluck('id');

                        $currentStockBalance_batchNo = CurrentStockBalance::where('transaction_type', 'stock_in')
                            ->whereIn('transaction_detail_id', $checkForBatch )
                            ->where('variation_id', $item['variation_id'])
                            ->first();

                        if ($currentStockBalance_batchNo == null){
                            $batch_no = UomHelper::generateBatchNo($item['variation_id'], 'SI', 6);
                        }else{
                            $batch_no = $currentStockBalance_batchNo->batch_no;
                        }

                        foreach($lotSerialDetails as $lotSerialDetail){

                            $lotReferencUomInfo=UomHelper::getReferenceUomInfoByCurrentUnitQty( $lotSerialDetail['number_of_in'],$lotSerialDetail['lot_uom_id']);

                            $currentStockBalance = CurrentStockBalance::create([
                                'business_location_id' => $request->business_location_id,
                                'product_id' => $stockinDetail['product_id'],
                                'variation_id' => $stockinDetail['variation_id'],
                                'transaction_type' => 'stock_in',
                                'transaction_detail_id' => $stockinDetail->id,
                                'batch_no' => $batch_no,
                                'lot_serial_no' => $lotSerialDetail['lot_serials'],
                                'expired_date' => $lotSerialDetail['expire_date'],
                                'ref_uom_id' => $lotReferencUomInfo['referenceUomId'],
                                'ref_uom_quantity' => $lotReferencUomInfo['qtyByReferenceUom'],
                                'ref_uom_price' => $stockinDetail['per_ref_uom_price'],
                                'current_quantity' => $lotReferencUomInfo['qtyByReferenceUom'],
                            ]);

                            $stockHistory = stock_history::create([
                                'business_location_id' => $request->business_location_id,
                                'product_id' => $stockinDetail['product_id'],
                                'variation_id' => $stockinDetail['variation_id'],
                                'lot_no' => $lotSerialDetail['lot_serials'],
                                'expired_date' => $lotSerialDetail['expire_date'],
                                'transaction_type' => 'stock_in',
                                'transaction_details_id' => $stockinDetail->id,
                                'increase_qty' => $lotReferencUomInfo['qtyByReferenceUom'],
                                'decrease_qty' => 0,
                                'ref_uom_id' => $lotReferencUomInfo['referenceUomId'],
                            ]);
                        }


                        $calReceivedQty = $item['in_quantity'] + $item['received_quantity'];
                        $purchaseDetailsToUpdate[$item['purchase_detail_id']] = $calReceivedQty;
                        $purchaseToUpdate[$item['purchase_id']] = $calReceivedQty;

                        foreach ($purchaseDetailsToUpdate as $purchaseId => $receivedQuantity) {
                            purchase_details::where('id', $purchaseId)->update(['received_quantity' => $receivedQuantity]);
                        }

                        $purchasesToUpdate = purchases::whereIn('id', array_keys($purchaseToUpdate))
                            ->whereHas('purchase_details', function ($query) {
                                $query->where('received_quantity', '<>', 'quantity');
                            })
                            ->get();

                        foreach ($purchasesToUpdate as $purchase) {
                            $details = $purchase->purchase_details;

                            // Check received qty in all purchase details and update status
                            $completed = $details->every(function ($detail) {
                                return $detail->received_quantity === $detail->quantity;
                            });


                            $purchase->status = $completed ? 'received' : 'partial';
                            $purchase->save();
                        }
                    }








                }else {
                    foreach ($stockinDetailsWithStockinId as $item) {

                        $referencUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($item['in_quantity'], $item['purchase_uom_id']);
                        $stockinDetailId = $item['stockin_detail_id'];

                        $transactionDetailId = StockinDetail::where('stockin_id', $id)
                            ->where('id', $stockinDetailId)
                            ->first()
                            ->transaction_detail_id;


                        if ($item['in_quantity'] > $item['old_qty']) {
                            purchase_details::where('id', $transactionDetailId)
                                ->increment('received_quantity', $item['in_quantity'] - $item['old_qty']);
                        }

                        if ($item['in_quantity'] < $item['old_qty']) {
                            purchase_details::where('id', $transactionDetailId)
                                ->decrement('received_quantity', $item['old_qty'] - $item['in_quantity']);
                        }


                        CurrentStockBalance::where('transaction_type', 'stock_in')
                            ->where('transaction_detail_id', $item['stockin_detail_id'])
                            ->update([
                                'ref_uom_quantity' => $referencUomInfo['qtyByReferenceUom'],
                                'current_quantity' => $referencUomInfo['qtyByReferenceUom']
                            ]);

                        unset($item['stockin_detail_id']);


                        StockinDetail::where('id', $stockinDetailId)
                            ->where('stockin_id', $id)
                            ->update([
                                'quantity' => $item['in_quantity'],
                                'remark' => $item['remark'],
                                'updated_by' => Auth::id(),
                            ]);

                        stock_history::where('transaction_type', 'stock_in')
                            ->where('transaction_details_id', $stockinDetailId)
                            ->update([
                                'increase_qty' => $referencUomInfo['qtyByReferenceUom'],
                            ]);

                    }
                    //End: update existed row details

                    //Being: add new details
                    $stockinDetailsWithoutStockinId = array_filter($requestStockinDetails, function ($item) {
                        return !isset($item['stockin_detail_id']);
                    });
                    $purchaseDetailsToUpdate = [];

                    foreach ($stockinDetailsWithoutStockinId as $item){
                        $stockinDetail = StockinDetail::create([
                            'stockin_id' => $id,
                            'product_id' => $item['product_id'],
                            'variation_id' => $item['variation_id'],
                            'transaction_type' => 'purchase',
                            'transaction_detail_id' => $item['purchase_detail_id'],
                            'uom_id' => $item['purchase_uom_id'],
                            'quantity' => $item['in_quantity'],
                            'remark' => $item['remark'],
                            'created_at' => now(),
                            'created_by' => Auth::id(),
                        ]);

                        $referencUomInfo=UomHelper::getReferenceUomInfoByCurrentUnitQty( $item['in_quantity'],$item['purchase_uom_id']);
                        $currentStockBalance = CurrentStockBalance::create([
                            'business_location_id' => $request->business_location_id,
                            'product_id' => $item['product_id'],
                            'variation_id' => $item['variation_id'],
                            'transaction_type' => 'stock_in',
                            'transaction_detail_id' => $stockinDetail->id,
                            'lot_serial_no' => null,
                            'expired_date' => null,
                            'ref_uom_id' => $referencUomInfo['referenceUomId'],
                            'ref_uom_quantity' => $referencUomInfo['qtyByReferenceUom'],
                            'ref_uom_price' => $item['per_ref_uom_price'],
                            'current_quantity' => $referencUomInfo['qtyByReferenceUom']
                        ]);

                        $stockHistory = stock_history::create([
                            'business_location_id' => $request->business_location_id,
                            'product_id' => $item['product_id'],
                            'variation_id' => $item['variation_id'],
                            'lot_no' => null,
                            'expired_date' => null,
                            'transaction_type' => 'stock_in',
                            'transaction_details_id' => $stockinDetail->id,
                            'increase_qty' => $referencUomInfo['qtyByReferenceUom'],
                            'decrease_qty' => 0,
                            'ref_uom_id' => $referencUomInfo['referenceUomId'],
                        ]);


                        $calReceivedQty = $item['in_quantity'] + $item['received_quantity'];
                        $purchaseDetailsToUpdate[$item['purchase_detail_id']] = $calReceivedQty;
                        $purchaseToUpdate[$item['purchase_id']] = $calReceivedQty;

                        foreach ($purchaseDetailsToUpdate as $purchaseId => $receivedQuantity) {
                            purchase_details::where('id', $purchaseId)->update(['received_quantity' => $receivedQuantity]);
                        }

                        $purchasesToUpdate = purchases::whereIn('id', array_keys($purchaseToUpdate))
                            ->whereHas('purchase_details', function ($query) {
                                $query->where('received_quantity', '<>', 'quantity');
                            })
                            ->get();

                        foreach ($purchasesToUpdate as $purchase) {
                            $details = $purchase->purchase_details;

                            // Check received qty in all purchase details and update status
                            $completed = $details->every(function ($detail) {
                                return $detail->received_quantity === $detail->quantity;
                            });


                            $purchase->status = $completed ? 'received' : 'partial';
                            $purchase->save();
                        }
                    }
                }
                //End: update pre-add details data



            } else {
                StockinDetail::where('stockin_id', $id)->delete();
            }

            //Update Stockin Table
            $stockinData = $this->getStockinData($request);
            Stockin::where('id', $id)->update($stockinData);
            DB::commit();

        }catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect(route('stock-in.index'))->with(['success' => 'Stockin Updated Successfully']);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    }


    public function softDelete(string $id){
        $restore = request()->query('restore');
        if ($restore == 'true') {


            DB::transaction(function () use ($id) {


                $detailIds = StockinDetail::where('stockin_id', $id)->pluck('id');


                $transactionDetails = StockinDetail::where('stockin_id', $id)
                    ->whereIn('id', $detailIds)
                    ->get();

                CurrentStockBalance::where('transaction_type', 'stock_in')
                    ->whereIn('transaction_detail_id', $detailIds)
                    ->delete();

                stock_history::where('transaction_type', 'stock_in')
                    ->whereIn('transaction_details_id', $detailIds)
                    ->delete();

                foreach ($transactionDetails as $transactionDetail) {
                    $purchaseDetail = purchase_details::where('id', $transactionDetail->transaction_detail_id)->first();
                    $purchaseDetail->received_quantity -= $transactionDetail->quantity;
                    $purchaseDetail->save();
                }


                Stockin::where('id', $id)
                    ->update(['deleted_by' => Auth::id(), 'is_deleted' => true]);
                Stockin::where('id', $id)->delete();

                StockinDetail::where('stockin_id', $id)
                    ->update(['deleted_by' => Auth::id(), 'is_delete' => true]);
                StockinDetail::where('stockin_id', $id)->delete();
            });

            $data = [
                'success' => 'Successfully Deleted',
            ];


        }else{

            DB::transaction(function () use ($id) {

                Stockin::where('id', $id)
                    ->update(['deleted_by' => Auth::id(), 'is_deleted' => true]);
                Stockin::where('id', $id)->delete();

                StockinDetail::where('stockin_id', $id)
                    ->update(['deleted_by' => Auth::id(), 'is_delete' => true]);
                StockinDetail::where('stockin_id', $id)->delete();
            });

            $data = [
                'success' => 'Successfully Deleted',
            ];


        }

        return response()->json($data, 200);
    }





    protected function storeCurrentStockBalance($request, $stockin_detail)
    {
        $lotNumber = $this->generateLotNumber();
        $expired_date = date('Y-m-d', strtotime($stockin_detail['expired_date']));
        $referencUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($stockin_detail['quantity'], $stockin_detail['uom_id']);

        $currentStockBalance = CurrentStockBalance::create([
            'business_location_id' => $request->business_location_id,
            'product_id' => $stockin_detail['product_id'],
            'variation_id' => $stockin_detail['variation_id'],
            'transaction_type' => 'stock_in',
            'transaction_detail_id' => $stockin_detail->id,
            'lot_serial_no' => $lotNumber,
            'expired_date' => $expired_date,
            'ref_uom_id' => $referencUomInfo['referenceUomId'],
            'ref_uom_quantity' => $referencUomInfo['qtyByReferenceUom'],
            'ref_uom_price' => $stockin_detail['purchase_price'],
            'current_quantity' => $referencUomInfo['qtyByReferenceUom'],
        ]);

        return $currentStockBalance;
    }

    protected function storeStockHistories($request, $stockin_detail)
    {
        $lotNumber = $this->generateLotNumber();
        $expired_date = date('Y-m-d', strtotime($stockin_detail['expired_date']));
        $referencUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($stockin_detail['quantity'], $stockin_detail['uom_id']);

        $stockHistory = stock_history::create([
            'business_location_id' => $request->business_location_id,
            'product_id' => $stockin_detail['product_id'],
            'variation_id' => $stockin_detail['variation_id'],
            'lot_no' => $lotNumber,
            'expired_date' => $expired_date,
            'transaction_type' => 'stock_in',
            'transaction_details_id' => $stockin_detail->id,
            'increase_qty' => $referencUomInfo['qtyByReferenceUom'],
            'decrease_qty' => 0,
            'ref_uom_id' => $referencUomInfo['referenceUomId'],
        ]);

        return $stockHistory;
    }

    public function filterSupplier(Request $request){
        $locationId =  $request->data;

        $suppliers = Contact::join('purchases', 'contacts.id', '=', 'purchases.contact_id')
            ->where('purchases.business_location_id', $locationId)
            ->whereIn('purchases.status', ['order', 'partial'])
            ->distinct()
            ->select('contacts.id', 'contacts.company_name')
            ->get();


        return response()->json($suppliers, 200);


    }

    public function filterPurchaseOrder(Request $request){

//        $results = purchases::where('business_location_id', $request->locationId)
            $results = purchases::where('contact_id', $request->supplierVal)
            ->whereIn('status', ['order', 'partial'])
            ->pluck('id', 'purchase_voucher_no')->toArray();

        $response = [];
        foreach ($results as $name => $value) {
            $response[] = ['name' => $name, 'value' => $value];
        }

        return $response;
    }

    public function filterPurchaseOrderData(Request $request) {
        $purchase = purchases::where('id', $request->data)
            ->with('businessLocation:id,name')
            ->select('id', 'business_location_id')
            ->first();

        $results = purchase_details::with([
            'product:id,name,product_type',
            'purchaseUom:id,name',
            'productVariation' => function($query){
                $query->select('id', 'variation_template_value_id')
                    ->with('variationTemplateValue:id,name');
            }
        ])->where('purchases_id', $purchase->id)->get();

        // Fetch all the necessary uom_data in advance
        $uomDataMap = UOM::whereIn('id', $results->pluck('purchase_uom_id'))
            ->with([
                'unit_category' => function ($q) {
                    $q->with('uomByCategory');
                }
            ])->get()
            ->keyBy('id'); // Organize the data as a map for easy lookup

        $results->each(function ($result) use ($purchase, $uomDataMap) {
            $result->business_location_id = $purchase->business_location_id ?? '';
            $result->business_location_name = $purchase->businessLocation->name ?? '';

            $result->uom_data = $uomDataMap[$result->purchase_uom_id]->unit_category;
        });

        return $results;
    }


    public function searchProduct(Request $request){
        $q=$request->data;

        $products = Product::select('id', 'name', 'product_code', 'sku', 'product_type','uom_id', 'purchase_uom_id')
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

    public function filterList(Request $request)
    {
        $dateRange = $request->data['filter_date'];
        $dates = explode(' - ', $dateRange);

        $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[0])->startOfDay();
        $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[1])->endOfDay();


        $query = Stockin::where('is_deleted', 0)
            ->with(['businessLocation:id,name', 'stockinPerson:id,username', 'created_by:id,username'])
            ->whereBetween('stockin_date', [$startDate, $endDate]);;

        if ($request->data['filter_status'] != 0) {
            $status = '';

            switch ($request->data['filter_status']) {
                case 1:
                    $status = 'pending';
                    break;
                case 2:
                    $status = 'received';
                    break;
                case 3:
                    $status = 'issued';
                    break;
                case 4:
                    $status = 'confirmed';
                    break;
            }

            $query->where('status', $status);
        }

        if ($request->data['filter_locations'] != 0) {
            $query->where('business_location_id', $request->data['filter_locations']);
        }

        if ($request->data['filter_stockinsperosn'] != 0) {
            $query->where('stockin_person', $request->data['filter_stockinsperosn']);
        }

        $stockins = $query->get();

        return response()->json($stockins, 200);
    }


    private function createStockinDetails($stockinDetails, $stockinId)
    {
        $lotNo = StockinDetail::count();

        foreach ($stockinDetails as &$stockinDetail) {
            $stockinDetail['lot_no'] = sprintf('%04d', ++$lotNo);
            $stockinDetail['stockin_id'] = $stockinId;
            $stockinDetail['created_by'] = Auth::id();
        }

        StockinDetail::insert($stockinDetails);
    }

    private function createCurrentStockBalance($stockinDetails, $stockinId)
    {
        $lotNo = StockinDetail::count();

        foreach ($stockinDetails as &$stockinDetail) {
            $stockinDetail['lot_no'] = sprintf('%04d', ++$lotNo);
            $stockinDetail['stockin_id'] = $stockinId;
            $stockinDetail['created_by'] = Auth::id();
        }

        StockinDetail::insert($stockinDetails);
    }


    private function getStockinData($request)
    {
        return [
            'stockin_date' => $request->stockin_date,
            'stockin_person' => $request->stockin_person,
            'business_location_id' => $request->business_location_id,
            'note' => $request->note,
            'updated_by' => Auth::id(),
            'updated_at' => now(),
        ];
    }

    public function getUOMVal($unit_id, $uom_id)
    {

        $uomset_vals = UOM::where('uomset_id', $uom_id)
            ->pluck('value')
            ->toArray();
        $uom = UOM::where('uomset_id', $uom_id)
            ->where('unit_id', $unit_id)
            ->select('value', 'level')->get();


        $start_index = $uom[0]->level + 1;

        $selected_numbers = array_slice($uomset_vals, $start_index);
        $total_uom_vals = array_reduce($selected_numbers, function ($previousValue, $currentValue) {
            return $previousValue * $currentValue;
        }, 1);


        return response()->json($total_uom_vals, 200);
    }

    public function stockinInvoicePrint($id)
    {
        $stockin = Stockin::with('businessLocation:id,name,city,state,country,landmark,zip_code')->where('id', $id)->first()->toArray();
        $location = $stockin['business_location'];

        $stockin_details = StockinDetail::where('stockin_id', $stockin['id'])
            ->where('is_delete', '0')
            ->with(['product:id,name', 'productVariation:id,variation_template_value_id', 'productVariation.variationTemplateValue:id,name'])
            ->leftJoin('uom_sets', 'stockin_details.uomset_id', '=', 'uom_sets.id')
            ->leftJoin('units', 'stockin_details.unit_id', '=', 'units.id')
            ->get();


        $invoiceHtml = view('App.stock.in.invoice', compact('stockin', 'location', 'stockin_details'))->render();
        return response()->json(['html' => $invoiceHtml]);

    }

    public function getUnits($id)
    {
        $uoms = $this->UOM_unit($id);
        $selectedValue = UomHelper::smallestUnitId($id);
        foreach ($uoms as $uom) {
            if ($uom->id == $selectedValue) {
                $uom->selected = true;
            }
        }
        return response()->json($uoms->toArray(), 200);
    }

    private function UOM_unit($id)
    {
        return UOM::where('uomset_id', $id)
            ->leftJoin('units', 'uoms.unit_id', '=', 'units.id')
            ->select('units.id', 'name as text')
            ->get();
    }

    public function generateVoucherNumber($prefix)
    {

        $lastStockinId = Stockin::orderBy('id', 'DESC')->select('id')->first()->id ?? 0;

        $voucherNumber =  sprintf($prefix.'-' . '%06d', ($lastStockinId + 1));

        return $voucherNumber;
    }

    private function generateLotNumber()
    {
        $lastLot = CurrentStockBalance::orderBy('id', 'desc')->first();
        $lastSequence = 0;
        if ($lastLot && isset($lastLot->lot_no)) {
            $lastSequence = intval(substr($lastLot->lot_no, -4));
        }
        $nextSequence = str_pad($lastSequence + 1, 4, '0', STR_PAD_LEFT);

        return $nextSequence;
    }


}
