<?php

namespace App\Http\Controllers\Stock;

use App\Helpers\UomHelper;
use App\Models\BusinessUser;
use App\Models\Contact\Contact;
use App\Models\CurrentStockBalance;
use App\Models\lotSerialDetails;
use App\Models\purchases\purchases;
use App\Models\Stock\StockAdjustment;
use App\Models\Stock\StockAdjustmentDetail;
use App\Models\Stock\StockTransferDetail;
use App\Models\stock_history;
use DateTime;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Models\Stock\StockTransfer;
use App\Http\Controllers\Controller;
use App\Models\settings\businessLocation;
use App\Models\settings\businessSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class StockAdjustmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = businessLocation::select('id', 'name')->get();
        $stockouts_person = BusinessUser::select('id', 'username')->get();
        $stockAdjustmentDetails = StockAdjustmentDetail::all();

        $suppliers=Contact::where('type','Supplier')
            ->select('id','company_name','first_name')
            ->get();
        return view('App.stock.adjustment.index', [
            'stock_transfers' => StockTransfer::all(),
            'locations' => $locations,
            'stockoutsperson' => $stockouts_person,
            'stock_adjustment_details' => $stockAdjustmentDetails,
            'suppliers' => $suppliers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $transfer_persons = BusinessUser::with('personal_info')->get();
        $locations = businessLocation::all();
        $products = Product::with('productVariations')->get();
        $setting=businessSettings::first();
        $currency=$setting->currency;

        return view('App.stock.adjustment.add', [
            'transfer_persons' => $transfer_persons,
            'locations' => $locations,
            'products' => $products,

            'currency' => $currency,
            'setting' => $setting,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Validator::make([
            'details'=>$request->adjustment_details,
        ],[
            'details'=>'required',
        ])->validate();
        $adjustmentDetails = $request->adjustment_details;
        $settings = businessSettings::all()->first();

        DB::beginTransaction();
        try {
            $prefix = 'SA';
            $voucherNumber = $this->generateVoucherNumber($prefix);
            $stockAdjustment = StockAdjustment::create([
                'adjustment_voucher_no' => $voucherNumber,
                'business_location' => $request->business_location,
                'status' => $request->status,
                'increase_subtotal' => 0,
                'decrease_subtotal' => 0,
                'created_at' => now(),
                'created_by' => Auth::id(),
            ]);


            foreach ($adjustmentDetails as $adjustmentDetail){

                $adjustmentType = $adjustmentDetail['adj_quantity'] < 0 ? 'decrease' : 'increase';

                if ($adjustmentType == 'increase'){
                    $referenceUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($adjustmentDetail['adj_quantity'], $adjustmentDetail['uom_id']);
                    $adjustQty = $referenceUomInfo['qtyByReferenceUom'];


                    $currentStockBalances = CurrentStockBalance::where('business_location_id', $request->business_location)
                        ->where('product_id',$adjustmentDetail['product_id'])
                        ->where('variation_id', $adjustmentDetail['variation_id'])
                        ->where('current_quantity', '>', 0)
                        ->latest()
                        ->first();

                    $subtotal = $request->status == 'completed' ? $currentStockBalances->ref_uom_price * $adjustQty : 0;

                    $stockAdjustmentDetail = StockAdjustmentDetail::create([
                        'adjustment_id' => $stockAdjustment->id,
                        'product_id' => $adjustmentDetail['product_id'],
                        'variation_id' => $adjustmentDetail['variation_id'],
                        'uom_id' => $adjustmentDetail['uom_id'],
                        'balance_quantity' => $adjustmentDetail['balance_quantity'],
                        'gnd_quantity' => $adjustmentDetail['gnd_quantity'],
                        'adjustment_type' => $adjustmentType,
                        'adj_quantity' => $adjustmentDetail['adj_quantity'],
                        'uom_price' => $request->status == 'completed' ? $currentStockBalances->ref_uom_price : 0,
                        'subtotal' => $subtotal,
                        'created_at' => now(),
                        'created_by' => Auth::id(),
                    ]);



                    if ($request->status == 'completed' && $adjustQty != 0){
                        $batchNo = UomHelper::generateBatchNo($adjustmentDetail['variation_id'],'',6);

                        CurrentStockBalance::create([
                            'business_location_id' => $request->business_location,
                            'product_id' => $adjustmentDetail['product_id'],
                            'variation_id' => $adjustmentDetail['variation_id'],
                            'transaction_type' => 'adjustment',
                            'transaction_detail_id' => $stockAdjustmentDetail->id,
                            'batch_no' => $batchNo,
                            'ref_uom_id' => $referenceUomInfo['referenceUomId'],
                            'ref_uom_quantity' => $adjustQty,
                            'ref_uom_price' => $currentStockBalances->ref_uom_price,
                            'current_quantity' => $adjustQty,
                            'created_at' => now(),

                        ]);

                        stock_history::create([
                            'business_location_id' => $request->business_location,
                            'product_id' => $adjustmentDetail['product_id'],
                            'variation_id' => $adjustmentDetail['variation_id'],
                            'lot_serial_no' => null,
                            'expired_date' => null,
                            'transaction_type' => 'adjustment',
                            'transaction_details_id' => $stockAdjustmentDetail->id,
                            'increase_qty' => $adjustQty,
                            'decrease_qty' => 0,
                            'ref_uom_id' => $referenceUomInfo['referenceUomId'],
                        ]);

                        $stockAdjustment->increase_subtotal += $subtotal;
                    }


                }else{

                    $stockAdjustmentDetail = StockAdjustmentDetail::create([
                        'adjustment_id' => $stockAdjustment->id,
                        'product_id' => $adjustmentDetail['product_id'],
                        'variation_id' => $adjustmentDetail['variation_id'],
                        'uom_id' => $adjustmentDetail['uom_id'],
                        'balance_quantity' => $adjustmentDetail['balance_quantity'],
                        'gnd_quantity' => $adjustmentDetail['gnd_quantity'],
                        'adjustment_type' => $adjustmentType,
                        'adj_quantity' => $adjustmentDetail['adj_quantity'],
                        'uom_price' => 0,
                        'subtotal' => 0,
                        'created_at' => now(),
                        'created_by' => Auth::id(),
                    ]);

                    $referenceUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty(abs($adjustmentDetail['adj_quantity']), $adjustmentDetail['uom_id']);
                    $adjustQty = $referenceUomInfo['qtyByReferenceUom'];
                    $adjustQtyToDecrease = $referenceUomInfo['qtyByReferenceUom'];

                    $totalRefUomPrice = 0;
                    $totalRows = 0;
                    if ($request->status == 'completed'){

                        $currentStockBalances = CurrentStockBalance::where('business_location_id', $request->business_location)
                            ->where('product_id',$adjustmentDetail['product_id'])
                            ->where('variation_id', $adjustmentDetail['variation_id'])
                            ->where('current_quantity', '>', 0)
                            ->when($settings->accounting_method == 'fifo', function ($query) {
                                return $query->orderBy('id');
                            }, function ($query) {
                                return $query->orderByDesc('id');
                            })
                            ->get();


                        foreach ($currentStockBalances as $currentStockBalance){
                            $currentQty = $currentStockBalance->current_quantity;
                            $refUomPrice = $currentStockBalance->ref_uom_price;

                            if ($currentQty > $adjustQtyToDecrease){
                                $leftStockQty = $currentQty - $adjustQtyToDecrease;

                                $currentStockBalance->update([
                                    'current_quantity' => $leftStockQty,
                                ]);

                                //record decreased qty to lot serial details
                                $this->updateOrCreateLotSerialDetails(null, $stockAdjustmentDetail->id,$currentStockBalance, 'adjustment',$adjustQtyToDecrease);

                                //Solution 1
                                $totalRefUomPrice += $refUomPrice * $adjustQtyToDecrease;
                                $totalRows += 1;



                                break;
                            }elseif ($adjustQtyToDecrease >= $currentQty){ //10 >3

                                //record decreased qty to lot serial details
                                $this->updateOrCreateLotSerialDetails(null, $stockAdjustmentDetail->id,$currentStockBalance, 'adjustment',$currentQty);

                                //Solution 1
                                $totalRefUomPrice += $refUomPrice * $currentQty;
                                $totalRows += 1;

                                $currentStockBalance->update([
                                    'current_quantity' => 0,
                                ]);

                                $adjustQtyToDecrease -= $currentQty;

                                if ($adjustQtyToDecrease == 0){
                                    break;
                                }
                            }
                        }

                        stock_history::create([
                            'business_location_id' => $request->business_location,
                            'product_id' => $adjustmentDetail['product_id'],
                            'variation_id' => $adjustmentDetail['variation_id'],
                            'lot_serial_no' => null,
                            'expired_date' => null,
                            'transaction_type' => 'adjustment',
                            'transaction_details_id' => $stockAdjustmentDetail->id,
                            'increase_qty' => 0,
                            'decrease_qty' => $adjustQty,
                            'ref_uom_id' => $referenceUomInfo['referenceUomId'],
                        ]);
                    }

                    $averageRefUomPrice = $totalRefUomPrice / $adjustQty;
                    $subtotal = $averageRefUomPrice * $adjustQty;

                    $stockAdjustmentDetail->uom_price = $averageRefUomPrice;
                    $stockAdjustmentDetail->subtotal = $subtotal;
                    $stockAdjustmentDetail->save();


                    $stockAdjustment->decrease_subtotal += $subtotal;
                }

            }

            $stockAdjustment->save();

            DB::commit();
            return redirect(route('stock-adjustment.index'))->with(['success' => 'Stock Adjustment successfully']);
        }catch (Exception $e){
            DB::rollBack();
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $stock_adjustment = StockAdjustment::with('businessLocation:id,name')->where('id', $id)->first();

        $stock_adjustment_details = StockAdjustmentDetail::with([
            'productVariation.product:id,name',
            'productVariation.variationTemplateValue:id,name',
            'uom'
        ])->where('adjustment_id', $id)->get();

        $modified_stock_adjustment_details = $stock_adjustment_details->lazy()->map(function($detail) {
            $variation = $detail->productVariation;
            return [
                'id' => $detail->id,
                'transfer_id' => $detail->adjustment_id,
                'product_id' => $detail->product_id,
                'variation_id' => $detail->variation_id,
                'uom_id' => $detail->uom_id,
                'balance_quantity' => number_format((float)$detail->balance_quantity, 2, '.', ''),
                'gnd_quantity' => number_format((float)$detail->gnd_quantity, 2, '.', ''),
                'adj_quantity' => number_format((float)$detail->adj_quantity, 2, '.', ''),
                'adjustment_type' => $detail->adjustment_type,
                'uom_price' => $detail->uom_price,
                'subtotal' => $detail->subtotal,
                'created_at' => $detail->created_at,
                'created_by' => $detail->created_by,
                'updated_at' => $detail->updated_at,
                'updated_by' => $detail->updated_by,
                'is_delete' => $detail->is_delete,
                'deleted_at' => $detail->deleted_at,
                'deleted_by' => $detail->deleted_by,
                'product_name' => $variation->product->name,
                'variation_name' => $variation->variationTemplateValue->name ?? '',
                'uom_name' => $detail->uom->name,
                'uom_short_name' => $detail->uom->short_name,
            ];
        });



        return view('App.stock.adjustment.view', [
            'stockAdjustment' => $stock_adjustment,
            'stockAdjustmentDetails' => $modified_stock_adjustment_details
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $transfer_persons = BusinessUser::with('personal_info')->get();
        $locations = businessLocation::all();
        $setting=businessSettings::first();

        $stockAdjustment = StockAdjustment::where('id', $id)->get()->first();
        $business_location_id=$stockAdjustment->business_location;
        $stock_adjustment_details = StockAdjustmentDetail::with(['productVariation' => function ($q) {
            $q->select('id', 'product_id', 'variation_template_value_id', 'default_selling_price')
                ->with([
                    'product' => function ($q) {
                        $q->select('id', 'name', 'product_type');
                    },
                    'variationTemplateValue' => function ($q) {
                        $q->select('id', 'name');
                    }
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
            ->where('adjustment_id', $id)->where('is_delete', 0)
            ->withSum(['stock' => function ($q) use ($business_location_id) {
                $q->where('business_location_id', $business_location_id);
            }], 'current_quantity');
        $adjustment_details = $stock_adjustment_details->get();

//        return $adjustment_details;

        return view('App.stock.adjustment.edit', [
            'stockAdjustment' => $stockAdjustment,
            'adjustment_details' => $adjustment_details,
            'transfer_persons' => $transfer_persons,
            'locations' => $locations,
//            'currency' => $currency,
            'setting' => $setting,

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $requestAdjustmentDetails = $request->adjustment_details;
        $settings =  businessSettings::all()->first();

//return $request;

        DB::beginTransaction();
        try {

            $existingAdjustmentDetailIds = [];

            $existingAdjustmentDetails = array_filter($requestAdjustmentDetails, function ($detail) {
                return isset($detail['adjustment_detail_id']);
            });

            $newAdjustmentDetails = array_filter($requestAdjustmentDetails, function ($detail) {
                return !isset($detail['adjustment_detail_id']);
            });

            $stockAdjustment = StockAdjustment::where('id', $id)->get()->first();
            $stockAdjustment->status = $request->status;


            // ========== Being:: Update existing row ==========
            foreach ($existingAdjustmentDetails as $adjustmentDetail){

                $adjustmentType = $adjustmentDetail['adj_quantity'] < 0 ? 'decrease' : 'increase';
                $oldAdjustmentType = $adjustmentDetail['old_adjustment_type'];

                $updateToAdjustmentDetail = StockAdjustmentDetail::where('id', $adjustmentDetail['adjustment_detail_id'])
                    ->where('adjustment_id', $id)->get()->first();

                $updateToAdjustmentDetail->uom_id = $adjustmentDetail['uom_id'];
                $updateToAdjustmentDetail->gnd_quantity = $adjustmentDetail['gnd_quantity'];

                if ($request->old_status == 'prepared' && $request->status == 'completed'){


                    $adjustmentType = $adjustmentDetail['adj_quantity'] < 0 ? 'decrease' : 'increase';

                    if ($adjustmentType == 'increase'){
                        $referenceUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($adjustmentDetail['adj_quantity'], $adjustmentDetail['uom_id']);
                        $adjustQty = $referenceUomInfo['qtyByReferenceUom'];


                        $currentStockBalances = CurrentStockBalance::where('business_location_id', $request->business_location)
                            ->where('product_id',$adjustmentDetail['product_id'])
                            ->where('variation_id', $adjustmentDetail['variation_id'])
                            ->where('current_quantity', '>', 0)
                            ->latest()
                            ->first();

                        $subtotal = $currentStockBalances->ref_uom_price * $adjustQty;

                        $updateToAdjustmentDetail->adjustment_type = 'increase';
                        $updateToAdjustmentDetail->uom_price = $currentStockBalances->ref_uom_price;
                        $updateToAdjustmentDetail->subtotal = $subtotal;
                        $updateToAdjustmentDetail->adj_quantity = $adjustmentDetail['adj_quantity'];
                        $updateToAdjustmentDetail->updated_at = now();
                        $updateToAdjustmentDetail->updated_by = Auth::id();
                        $updateToAdjustmentDetail->save();


                        $batchNo = UomHelper::generateBatchNo($adjustmentDetail['variation_id'],'',6);

                        CurrentStockBalance::create([
                            'business_location_id' => $request->business_location,
                            'product_id' => $adjustmentDetail['product_id'],
                            'variation_id' => $adjustmentDetail['variation_id'],
                            'transaction_type' => 'adjustment',
                            'transaction_detail_id' => $adjustmentDetail['adjustment_detail_id'],
                            'batch_no' => $batchNo,
                            'ref_uom_id' => $referenceUomInfo['referenceUomId'],
                            'ref_uom_quantity' => $adjustQty,
                            'ref_uom_price' => $currentStockBalances->ref_uom_price,
                            'current_quantity' => $adjustQty,
                            'created_at' => now(),
                        ]);

                        stock_history::create([
                            'business_location_id' => $request->business_location,
                            'product_id' => $adjustmentDetail['product_id'],
                            'variation_id' => $adjustmentDetail['variation_id'],
                            'lot_serial_no' => null,
                            'expired_date' => null,
                            'transaction_type' => 'adjustment',
                            'transaction_details_id' => $adjustmentDetail['adjustment_detail_id'],
                            'increase_qty' => $adjustQty,
                            'decrease_qty' => 0,
                            'ref_uom_id' => $referenceUomInfo['referenceUomId'],
                        ]);

                        $stockAdjustment->increase_subtotal += $subtotal;

                    }else{

                        $updateToAdjustmentDetail->adjustment_type = 'decrease';
                        $updateToAdjustmentDetail->adj_quantity = $adjustmentDetail['adj_quantity'];
                        $updateToAdjustmentDetail->save();

                        $referenceUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty(abs($adjustmentDetail['adj_quantity']), $adjustmentDetail['uom_id']);
                        $adjustQty = $referenceUomInfo['qtyByReferenceUom'];
                        $adjustQtyToDecrease = $referenceUomInfo['qtyByReferenceUom'];

                        $totalRefUomPrice = 0;
                        $totalRows = 0;
                        if ($request->status == 'completed'){

                            $currentStockBalances = CurrentStockBalance::where('business_location_id', $request->business_location)
                                ->where('product_id',$adjustmentDetail['product_id'])
                                ->where('variation_id', $adjustmentDetail['variation_id'])
                                ->where('current_quantity', '>', 0)
                                ->when($settings->accounting_method == 'fifo', function ($query) {
                                    return $query->orderBy('id');
                                }, function ($query) {
                                    return $query->orderByDesc('id');
                                })
                                ->get();


                            foreach ($currentStockBalances as $currentStockBalance){
                                $currentQty = $currentStockBalance->current_quantity;
                                $refUomPrice = $currentStockBalance->ref_uom_price;

                                if ($currentQty > $adjustQtyToDecrease){
                                    $leftStockQty = $currentQty - $adjustQtyToDecrease;

                                    $currentStockBalance->update([
                                        'current_quantity' => $leftStockQty,
                                    ]);

                                    //record decreased qty to lot serial details
                                    $this->updateOrCreateLotSerialDetails(null, $adjustmentDetail['adjustment_detail_id'],$currentStockBalance, 'adjustment',$adjustQtyToDecrease);

                                    $totalRefUomPrice += $refUomPrice * $adjustQtyToDecrease;

                                    break;
                                }elseif ($adjustQtyToDecrease >= $currentQty){

                                    //record decreased qty to lot serial details
                                    $this->updateOrCreateLotSerialDetails(null, $adjustmentDetail['adjustment_detail_id'],$currentStockBalance, 'adjustment',$currentQty);

                                    $totalRefUomPrice += $refUomPrice * $currentQty;

                                    $currentStockBalance->update([
                                        'current_quantity' => 0,
                                    ]);

                                    $adjustQtyToDecrease -= $currentQty;

                                    if ($adjustQtyToDecrease == 0){
                                        break;
                                    }
                                }
                            }

                            stock_history::create([
                                'business_location_id' => $request->business_location,
                                'product_id' => $adjustmentDetail['product_id'],
                                'variation_id' => $adjustmentDetail['variation_id'],
                                'lot_serial_no' => null,
                                'expired_date' => null,
                                'transaction_type' => 'adjustment',
                                'transaction_details_id' => $adjustmentDetail['adjustment_detail_id'],
                                'increase_qty' => 0,
                                'decrease_qty' => $adjustQty,
                                'ref_uom_id' => $referenceUomInfo['referenceUomId'],
                            ]);
                        }


                        $averageRefUomPrice = $totalRefUomPrice / $adjustQty;
                        $subtotal = $averageRefUomPrice * $adjustQty;


                        $updateToAdjustmentDetail->adjustment_type = 'decrease';
                        $updateToAdjustmentDetail->uom_price = $averageRefUomPrice;
                        $updateToAdjustmentDetail->subtotal = $subtotal;
                        $updateToAdjustmentDetail->adj_quantity = $adjustmentDetail['adj_quantity'];
                        $updateToAdjustmentDetail->updated_at = now();
                        $updateToAdjustmentDetail->updated_by = Auth::id();
                        $updateToAdjustmentDetail->save();


                        $stockAdjustment->decrease_subtotal += $subtotal;
                    }


                }

                if ($request->old_status == 'completed' && $request->status == 'completed'){
                    if ($oldAdjustmentType == 'increase'){
                        if ($adjustmentType == 'decrease'){
                            if (abs($adjustmentDetail['adj_quantity']) > $adjustmentDetail['before_edit_adj_quantity']){

                                $adjustQtyToDecrease = abs($adjustmentDetail['adj_quantity']) - $adjustmentDetail['before_edit_adj_quantity'];
                                $updateToAdjustmentDetail->adjustment_type = 'decrease';
                                $updateToAdjustmentDetail->adj_quantity = $adjustQtyToDecrease * -1;


                                $referenceUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($adjustQtyToDecrease, $adjustmentDetail['uom_id']);
                                $remainToDecrease = $referenceUomInfo['qtyByReferenceUom'];
                                $adjQty = $referenceUomInfo['qtyByReferenceUom'];
//                                    return 'update'.$remainToDecrease; break;
                                CurrentStockBalance::where('transaction_type', 'adjustment')
                                    ->where('transaction_detail_id', $adjustmentDetail['adjustment_detail_id'])->delete();

                                $currentStockBalances = CurrentStockBalance::where('business_location_id', $request->business_location)
                                    ->where('product_id',$adjustmentDetail['product_id'])
                                    ->where('variation_id', $adjustmentDetail['variation_id'])
                                    ->where('current_quantity', '>', 0)
                                    ->when($settings->accounting_method == 'fifo', function ($query) {
                                        return $query->orderBy('id');
                                    }, function ($query) {
                                        return $query->orderByDesc('id');
                                    })
                                    ->get();


                                $totalRefUomPrice = 0;

                                foreach ($currentStockBalances as $currentStockBalance){
                                    $currentQty = $currentStockBalance->current_quantity;
                                    $refUomPrice = $currentStockBalance->ref_uom_price;

                                    if ($currentQty > $remainToDecrease){
                                        if ($remainToDecrease == 0){
                                            break;
                                        }
                                        $leftStockQty = $currentQty - $remainToDecrease;

                                        $currentStockBalance->update([
                                            'current_quantity' => $leftStockQty,
                                        ]);

                                        //record decreased qty to lot serial details
                                        $this->updateOrCreateLotSerialDetails(null, $adjustmentDetail['adjustment_detail_id'],$currentStockBalance, 'adjustment',$remainToDecrease);


                                        $totalRefUomPrice += $refUomPrice * $remainToDecrease;

                                        break;
                                    }elseif ($remainToDecrease >= $currentQty){

                                        //record decreased qty to lot serial details
                                        $this->updateOrCreateLotSerialDetails(null, $adjustmentDetail['adjustment_detail_id'],$currentStockBalance, 'adjustment',$currentQty);


                                        $totalRefUomPrice += $refUomPrice * $currentQty;

                                        $currentStockBalance->update([
                                            'current_quantity' => 0,
                                        ]);

                                        $remainToDecrease -= $currentQty;

                                        if ($remainToDecrease == 0){
                                            break;
                                        }
                                    }
                                }

                                $averageRefUomPrice = $totalRefUomPrice / $adjQty;
                                $subtotal = $averageRefUomPrice * $adjQty;

                                $updateToAdjustmentDetail->uom_price = $averageRefUomPrice;
                                $updateToAdjustmentDetail->subtotal = $subtotal;
                                $updateToAdjustmentDetail->updated_at = now();
                                $updateToAdjustmentDetail->updated_by = Auth::id();
                                $updateToAdjustmentDetail->save();

                                $stockAdjustment->decrease_subtotal += $subtotal;

                            }elseif ($adjustmentDetail['before_edit_adj_quantity'] >= abs($adjustmentDetail['adj_quantity'])){


                                $adjustQtyToDecrease = $adjustmentDetail['before_edit_adj_quantity'] - abs($adjustmentDetail['adj_quantity']);
                                $updateToAdjustmentDetail->adjustment_type = 'increase';
                                $updateToAdjustmentDetail->adj_quantity = $adjustQtyToDecrease;

                                $subtotal= 0;

                                $referenceUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($adjustQtyToDecrease, $adjustmentDetail['uom_id']);
                                $adjToDecrease = $referenceUomInfo['qtyByReferenceUom'];
                                $adjQty = $referenceUomInfo['qtyByReferenceUom'];



                                if($adjToDecrease == 0){
                                    $currentBalance = CurrentStockBalance::where('transaction_type', 'adjustment')
                                        ->where('transaction_detail_id', $adjustmentDetail['adjustment_detail_id'])->delete();
                                    $subtotal = $adjQty * ($currentBalance->ref_uom_price ?? 0);
                                }else{
                                    $updateToCurrentBalance = CurrentStockBalance::where('transaction_type', 'adjustment')
                                        ->where('transaction_detail_id', $adjustmentDetail['adjustment_detail_id'])->get()->first();
                                    $updateToCurrentBalance->current_quantity = $adjToDecrease;
                                    $updateToCurrentBalance->ref_uom_quantity = $adjToDecrease;
                                    $updateToCurrentBalance->save();

                                    $subtotal = $adjQty * $updateToCurrentBalance->ref_uom_price;
                                }



                                $updateToAdjustmentDetail->subtotal -= $subtotal;
                                $updateToAdjustmentDetail->save();

                                $stockAdjustment->increase_subtotal -= $subtotal;

                            }

                        }
                        if ($adjustmentType == 'increase'){

                            $referenceUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($adjustmentDetail['adj_quantity'], $adjustmentDetail['uom_id']);
                            $adjToIncrease = $referenceUomInfo['qtyByReferenceUom'];
                            $adjQty = $referenceUomInfo['qtyByReferenceUom'];


                            $updateToCurrentBalance = CurrentStockBalance::where('transaction_type', 'adjustment')
                                ->where('transaction_detail_id', $adjustmentDetail['adjustment_detail_id'])
                                ->first();

                            if ($updateToCurrentBalance) {
                                $updateToCurrentBalance->current_quantity += $adjToIncrease;
                                $updateToCurrentBalance->ref_uom_quantity += $adjToIncrease;
                                $updateToCurrentBalance->save();

                                $subtotal = $adjQty * $updateToCurrentBalance->ref_uom_price;
                            } else {

                                $currentStockBalances = CurrentStockBalance::where('business_location_id', $request->business_location)
                                    ->where('product_id',$adjustmentDetail['product_id'])
                                    ->where('variation_id', $adjustmentDetail['variation_id'])
                                    ->where('current_quantity', '>', 0)
                                    ->latest()
                                    ->first();

                                $batchNo = UomHelper::generateBatchNo($adjustmentDetail['variation_id'],'',6);

                                CurrentStockBalance::create([
                                    'business_location_id' => $request->business_location,
                                    'product_id' => $adjustmentDetail['product_id'],
                                    'variation_id' => $adjustmentDetail['variation_id'],
                                    'transaction_type' => 'adjustment',
                                    'transaction_detail_id' => $adjustmentDetail['adjustment_detail_id'],
                                    'batch_no' => $batchNo,
                                    'ref_uom_id' => $referenceUomInfo['referenceUomId'],
                                    'ref_uom_quantity' => $adjToIncrease,
                                    'ref_uom_price' => $currentStockBalances->ref_uom_price,
                                    'current_quantity' => $adjToIncrease,
                                    'created_at' => now(),
                                ]);

                                stock_history::create([
                                    'business_location_id' => $request->business_location,
                                    'product_id' => $adjustmentDetail['product_id'],
                                    'variation_id' => $adjustmentDetail['variation_id'],
                                    'lot_serial_no' => null,
                                    'expired_date' => null,
                                    'transaction_type' => 'adjustment',
                                    'transaction_details_id' => $adjustmentDetail['adjustment_detail_id'],
                                    'increase_qty' => $adjToIncrease,
                                    'decrease_qty' => 0,
                                    'ref_uom_id' => $referenceUomInfo['referenceUomId'],
                                ]);

                                $subtotal = $adjQty * $currentStockBalances->ref_uom_price;
                            }



                            $updateToAdjustmentDetail->adjustment_type = 'increase';
                            $updateToAdjustmentDetail->adj_quantity = $adjustmentDetail['adj_quantity'] + $adjustmentDetail['before_edit_adj_quantity'];
                            $updateToAdjustmentDetail->subtotal += $subtotal;
                            $updateToAdjustmentDetail->save();

                            $stockAdjustment->increase_subtotal += $subtotal;
                        }

                    }

                    if ($oldAdjustmentType == 'decrease'){
                        if ($adjustmentType == 'decrease'){
                            $updateAdjQty = abs($adjustmentDetail['adj_quantity']) + abs($adjustmentDetail['before_edit_adj_quantity']);

                            $updateToAdjustmentDetail->adjustment_type = 'decrease';
                            $updateToAdjustmentDetail->adj_quantity =  $updateAdjQty * -1;
                            $updateToAdjustmentDetail->save();

                            $referenceUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty(abs($adjustmentDetail['adj_quantity']), $adjustmentDetail['uom_id']);
                            $adjustQty = $referenceUomInfo['qtyByReferenceUom'];
                            $adjustQtyToDecrease = $referenceUomInfo['qtyByReferenceUom'];

                            $totalRefUomPrice = 0;
                            $totalRows = 0;

                            $currentStockBalances = CurrentStockBalance::where('business_location_id', $request->business_location)
                                ->where('product_id',$adjustmentDetail['product_id'])
                                ->where('variation_id', $adjustmentDetail['variation_id'])
                                ->where('current_quantity', '>', 0)
                                ->when($settings->accounting_method == 'fifo', function ($query) {
                                    return $query->orderBy('id');
                                }, function ($query) {
                                    return $query->orderByDesc('id');
                                })
                                ->get();


                            foreach ($currentStockBalances as $currentStockBalance){
                                $currentQty = $currentStockBalance->current_quantity;
                                $refUomPrice = $currentStockBalance->ref_uom_price;

                                if ($currentQty > $adjustQtyToDecrease){
                                    $leftStockQty = $currentQty - $adjustQtyToDecrease;

                                    $currentStockBalance->update([
                                        'current_quantity' => $leftStockQty,
                                    ]);

                                    $lotDetail = lotSerialDetails::where('transaction_type', 'adjustment')
                                        ->where('transaction_detail_id', $adjustmentDetail['adjustment_detail_id'])
                                        ->where('current_stock_balance_id', $currentStockBalance->id)->get()->first();
                                    //record decreased qty to lot serial details
                                    $this->updateOrCreateLotSerialDetails($lotDetail, $adjustmentDetail['adjustment_detail_id'],$currentStockBalance, 'adjustment',$adjustQtyToDecrease);

                                    //Solution 1
//                                            $totalRefUomPrice += $refUomPrice * $adjustQtyToDecrease;
//                                            $totalRows += 1;



                                    break;
                                }elseif ($adjustQtyToDecrease >= $currentQty){

                                    //record decreased qty to lot serial details

                                    $lotDetail = lotSerialDetails::where('transaction_type', 'adjustment')
                                        ->where('transaction_detail_id', $adjustmentDetail['adjustment_detail_id'])
                                        ->where('current_stock_balance_id', $currentStockBalance->id)->get()->first();

                                    $this->updateOrCreateLotSerialDetails($lotDetail, $adjustmentDetail['adjustment_detail_id'],$currentStockBalance, 'adjustment',$currentQty);

                                    //Solution 1
//                                            $totalRefUomPrice += $refUomPrice * $currentQty;
//                                            $totalRows += 1;

                                    $currentStockBalance->update([
                                        'current_quantity' => 0,
                                    ]);

                                    $adjustQtyToDecrease -= $currentQty;

                                    if ($adjustQtyToDecrease == 0){
                                        break;
                                    }
                                }
                            }

                            stock_history::create([
                                'business_location_id' => $request->business_location,
                                'product_id' => $adjustmentDetail['product_id'],
                                'variation_id' => $adjustmentDetail['variation_id'],
                                'lot_serial_no' => null,
                                'expired_date' => null,
                                'transaction_type' => 'adjustment',
                                'transaction_details_id' => $adjustmentDetail['adjustment_detail_id'],
                                'increase_qty' => 0,
                                'decrease_qty' => $adjustQty,
                                'ref_uom_id' => $referenceUomInfo['referenceUomId'],
                            ]);


                        }

                        if ($adjustmentType == 'increase'){

                            if (abs($adjustmentDetail['before_edit_adj_quantity']) >= $adjustmentDetail['adj_quantity']) { // 2 -3

                                $adjustQtyToDecrease = abs($adjustmentDetail['before_edit_adj_quantity']) - $adjustmentDetail['adj_quantity'];

                                $updateToAdjustmentDetail->adjustment_type = 'decrease';
                                $updateToAdjustmentDetail->adj_quantity =  $adjustQtyToDecrease * -1;
                                $updateToAdjustmentDetail->save();


                                $referenceUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($adjustmentDetail['adj_quantity'], $adjustmentDetail['uom_id']);
                                $adjToIncrease = $referenceUomInfo['qtyByReferenceUom'];

                                $lotSerialDetails = lotSerialDetails::where('transaction_type', 'adjustment')
                                    ->where('transaction_detail_id', $adjustmentDetail['adjustment_detail_id'])
                                    ->when($settings->accounting_method == 'fifo', function ($query) {
                                        return $query->orderByDesc('current_stock_balance_id');
                                    }, function ($query) {
                                        return $query->orderBy('current_stock_balance_id');
                                    })
                                    ->get();

                                foreach ($lotSerialDetails as $lotDetail){
                                    $updateToCurrentBalance = CurrentStockBalance::where('id', $lotDetail->current_stock_balance_id)->get()->first();
                                    $updateToCurrentBalance->current_quantity += $adjToIncrease;
                                    $updateToCurrentBalance->save();

                                    $lotDetail->uom_quantity -= $adjToIncrease;
                                    $lotDetail->save();
                                }
                            }elseif ($adjustmentDetail['adj_quantity'] > abs($adjustmentDetail['before_edit_adj_quantity'])){

                                $adjustQtyToIncrease= $adjustmentDetail['adj_quantity'] - abs($adjustmentDetail['before_edit_adj_quantity']);

                                $updateToAdjustmentDetail->adjustment_type = 'increase';
                                $updateToAdjustmentDetail->adj_quantity =  $adjustQtyToIncrease;
                                $updateToAdjustmentDetail->save();

                                $referenceUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($adjustQtyToIncrease, $adjustmentDetail['uom_id']);
                                $adjToIncrease = $referenceUomInfo['qtyByReferenceUom'];

                                $lotSerialDetails = lotSerialDetails::where('transaction_type', 'adjustment')
                                    ->where('transaction_detail_id', $adjustmentDetail['adjustment_detail_id'])
                                    ->when($settings->accounting_method == 'fifo', function ($query) {
                                        return $query->orderByDesc('current_stock_balance_id');
                                    }, function ($query) {
                                        return $query->orderBy('current_stock_balance_id');
                                    })
                                    ->get();

                                foreach ($lotSerialDetails as $lotDetail){
                                    $updateToCurrentBalance = CurrentStockBalance::where('id', $lotDetail->current_stock_balance_id)->get()->first();
                                    $updateToCurrentBalance->current_quantity += $lotDetail->uom_quantity;
                                    $updateToCurrentBalance->save();
                                    $lotDetail->delete();
                                }

                                $currentStockBalances = CurrentStockBalance::where('business_location_id', $request->business_location)
                                    ->where('product_id',$adjustmentDetail['product_id'])
                                    ->where('variation_id', $adjustmentDetail['variation_id'])
                                    ->where('current_quantity', '>', 0)
                                    ->latest()
                                    ->first();


                                $batchNo = UomHelper::generateBatchNo($adjustmentDetail['variation_id'],'',6);

                                CurrentStockBalance::create([
                                    'business_location_id' => $request->business_location,
                                    'product_id' => $adjustmentDetail['product_id'],
                                    'variation_id' => $adjustmentDetail['variation_id'],
                                    'transaction_type' => 'adjustment',
                                    'transaction_detail_id' => $adjustmentDetail['adjustment_detail_id'],
                                    'batch_no' => $batchNo,
                                    'ref_uom_id' => $referenceUomInfo['referenceUomId'],
                                    'ref_uom_quantity' => $adjToIncrease,
                                    'ref_uom_price' => $currentStockBalances->ref_uom_price,
                                    'current_quantity' => $adjToIncrease,
                                    'created_at' => now(),
                                ]);

                            }




                        }
                    }
                }



                $existingAdjustmentDetailIds[] = $adjustmentDetail['adjustment_detail_id'];
            }
            // ========== End:: Update existing row ==========



            // ========== Being:: Delete rows that were not updated ==========
            $adjustmentDetails = StockAdjustmentDetail::where('adjustment_id', $id)
                ->whereNotIn('id', $existingAdjustmentDetailIds)
                ->get();



            foreach ($adjustmentDetails as $adjustmentDetail){


                CurrentStockBalance::where('transaction_detail_id', $adjustmentDetail->id)
                    ->where('transaction_type', 'adjustment')
                    ->delete();

                $restoreLotSerialDetails = lotSerialDetails::where('transaction_detail_id', $adjustmentDetail->id)
                    ->where('transaction_type', 'adjustment')
                    ->get();

                foreach ($restoreLotSerialDetails as $restoreDetail){
                    $currentStockBalance = CurrentStockBalance::where('id', $restoreDetail->current_stock_balance_id)->first();
                    $currentStockBalance->current_quantity += $restoreDetail->uom_quantity;
                    $currentStockBalance->save();
                    $restoreDetail->delete();
                }
            }

            StockAdjustmentDetail::where('adjustment_id', $id)
                ->whereNotIn('id', $existingAdjustmentDetailIds)
                ->update(['is_delete' => true, 'deleted_by' => Auth::id()]);

            StockAdjustmentDetail::where('adjustment_id', $id)
                ->whereNotIn('id', $existingAdjustmentDetailIds)
                ->delete();
            // ========== End:: Delete rows that were not updated ==========

            // ========== Being:: Create new rows ==========

            foreach ($newAdjustmentDetails as $adjustmentDetail){

                $adjustmentType = $adjustmentDetail['adj_quantity'] < 0 ? 'decrease' : 'increase';

                if ($adjustmentType == 'increase'){
                    $referenceUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($adjustmentDetail['adj_quantity'], $adjustmentDetail['uom_id']);
                    $adjustQty = $referenceUomInfo['qtyByReferenceUom'];


                    $currentStockBalances = CurrentStockBalance::where('business_location_id', $request->business_location)
                        ->where('product_id',$adjustmentDetail['product_id'])
                        ->where('variation_id', $adjustmentDetail['variation_id'])
                        ->where('current_quantity', '>', 0)
                        ->latest()
                        ->first();
                    //1-500
                    $subtotal = $request->status == 'completed' ? $currentStockBalances->ref_uom_price * $adjustQty : 0;
                    $stockAdjustmentDetail = StockAdjustmentDetail::create([
                        'adjustment_id' => $stockAdjustment->id,
                        'product_id' => $adjustmentDetail['product_id'],
                        'variation_id' => $adjustmentDetail['variation_id'],
                        'uom_id' => $adjustmentDetail['uom_id'],
                        'balance_quantity' => $adjustmentDetail['balance_quantity'],
                        'gnd_quantity' => $adjustmentDetail['gnd_quantity'],
                        'adjustment_type' => $adjustmentType,
                        'adj_quantity' => $adjustmentDetail['adj_quantity'],
                        'uom_price' => $request->status == 'completed' ? $currentStockBalances->ref_uom_price : 0,
                        'subtotal' => $subtotal,
                        'created_at' => now(),
                        'created_by' => Auth::id(),
                    ]);



                    if ($request->status == 'completed' && $adjustQty != 0){
                        $batchNo = UomHelper::generateBatchNo($adjustmentDetail['variation_id'],'',6);

                        CurrentStockBalance::create([
                            'business_location_id' => $request->business_location,
                            'product_id' => $adjustmentDetail['product_id'],
                            'variation_id' => $adjustmentDetail['variation_id'],
                            'transaction_type' => 'adjustment',
                            'transaction_detail_id' => $stockAdjustmentDetail->id,
                            'batch_no' => $batchNo,
                            'ref_uom_id' => $referenceUomInfo['referenceUomId'],
                            'ref_uom_quantity' => $adjustQty,
                            'ref_uom_price' => $currentStockBalances->ref_uom_price,
                            'current_quantity' => $adjustQty,
                            'created_at' => now(),

                        ]);

                        stock_history::create([
                            'business_location_id' => $request->business_location,
                            'product_id' => $adjustmentDetail['product_id'],
                            'variation_id' => $adjustmentDetail['variation_id'],
                            'lot_serial_no' => null,
                            'expired_date' => null,
                            'transaction_type' => 'adjustment',
                            'transaction_details_id' => $stockAdjustmentDetail->id,
                            'increase_qty' => $adjustQty,
                            'decrease_qty' => 0,
                            'ref_uom_id' => $referenceUomInfo['referenceUomId'],
                        ]);

                        $stockAdjustment->increase_subtotal += $subtotal;
                    }


                }else{

                    $stockAdjustmentDetail = StockAdjustmentDetail::create([
                        'adjustment_id' => $stockAdjustment->id,
                        'product_id' => $adjustmentDetail['product_id'],
                        'variation_id' => $adjustmentDetail['variation_id'],
                        'uom_id' => $adjustmentDetail['uom_id'],
                        'balance_quantity' => $adjustmentDetail['balance_quantity'],
                        'gnd_quantity' => $adjustmentDetail['gnd_quantity'],
                        'adjustment_type' => $adjustmentType,
                        'adj_quantity' => $adjustmentDetail['adj_quantity'],
                        'uom_price' => 0,
                        'subtotal' => 0,
                        'created_at' => now(),
                        'created_by' => Auth::id(),
                    ]);

                    $referenceUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty(abs($adjustmentDetail['adj_quantity']), $adjustmentDetail['uom_id']);
                    $adjustQty = $referenceUomInfo['qtyByReferenceUom'];
                    $adjustQtyToDecrease = $referenceUomInfo['qtyByReferenceUom'];

                    $totalRefUomPrice = 0;
                    $totalRows = 0;
                    if ($request->status == 'completed'){

                        $currentStockBalances = CurrentStockBalance::where('business_location_id', $request->business_location)
                            ->where('product_id',$adjustmentDetail['product_id'])
                            ->where('variation_id', $adjustmentDetail['variation_id'])
                            ->where('current_quantity', '>', 0)
                            ->when($settings->accounting_method == 'fifo', function ($query) {
                                return $query->orderBy('id');
                            }, function ($query) {
                                return $query->orderByDesc('id');
                            })
                            ->get();


                        foreach ($currentStockBalances as $currentStockBalance){
                            $currentQty = $currentStockBalance->current_quantity;
                            $refUomPrice = $currentStockBalance->ref_uom_price;

                            if ($currentQty > $adjustQtyToDecrease){
                                $leftStockQty = $currentQty - $adjustQtyToDecrease;

                                $currentStockBalance->update([
                                    'current_quantity' => $leftStockQty,
                                ]);

                                //record decreased qty to lot serial details
                                $this->updateOrCreateLotSerialDetails(null, $stockAdjustmentDetail->id,$currentStockBalance, 'adjustment',$adjustQtyToDecrease);

                                //Solution 1
                                $totalRefUomPrice += $refUomPrice * $adjustQtyToDecrease;
                                $totalRows += 1;



                                break;
                            }elseif ($adjustQtyToDecrease >= $currentQty){ //10 >3

                                //record decreased qty to lot serial details
                                $this->updateOrCreateLotSerialDetails(null, $stockAdjustmentDetail->id,$currentStockBalance, 'adjustment',$currentQty);

                                //Solution 1
                                $totalRefUomPrice += $refUomPrice * $currentQty;
                                $totalRows += 1;

                                $currentStockBalance->update([
                                    'current_quantity' => 0,
                                ]);

                                $adjustQtyToDecrease -= $currentQty;

                                if ($adjustQtyToDecrease == 0){
                                    break;
                                }
                            }
                        }

                        stock_history::create([
                            'business_location_id' => $request->business_location,
                            'product_id' => $adjustmentDetail['product_id'],
                            'variation_id' => $adjustmentDetail['variation_id'],
                            'lot_serial_no' => null,
                            'expired_date' => null,
                            'transaction_type' => 'adjustment',
                            'transaction_details_id' => $stockAdjustmentDetail->id,
                            'increase_qty' => 0,
                            'decrease_qty' => $adjustQty,
                            'ref_uom_id' => $referenceUomInfo['referenceUomId'],
                        ]);
                    }



                    //Solution 1
                    $averageRefUomPrice = $totalRefUomPrice / $adjustQty;
                    $subtotal = $averageRefUomPrice * $adjustQty;

                    $stockAdjustmentDetail->uom_price = $averageRefUomPrice;
                    $stockAdjustmentDetail->subtotal = $subtotal;
                    $stockAdjustmentDetail->save();


                    $stockAdjustment->decrease_subtotal += $subtotal;
                }

            }
            // ========== End:: Create new rows ==========

















            $stockAdjustment->save();
            DB::commit();
            return redirect(route('stock-adjustment.index'))->with(['success' => 'Adjustment successfully edited']);
        }catch(\Exception $e){
            DB::rollBack();
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    }

    public function softDelete(string $id){
        $restore = request()->query('restore');
        $settings = businessSettings::all()->first();
        if ($restore == 'true') {

            $adjustmentDetails = StockAdjustmentDetail::where('adjustment_id', $id)->pluck('id');

            $lotSerialDetails = lotSerialDetails::whereIn('transaction_detail_id', $adjustmentDetails)
                ->where('transaction_type', 'adjustment')
                ->when($settings->accounting_method == 'fifo', function ($query) {
                    return $query->orderByDesc('current_stock_balance_id');
                }, function ($query) {
                    return $query->orderBy('current_stock_balance_id');
                })
                ->get();


            foreach ($lotSerialDetails as $restoreDetail){
                $currentStockBalance = CurrentStockBalance::where('id', $restoreDetail->current_stock_balance_id)->first();
                $currentStockBalance->current_quantity += $restoreDetail->uom_quantity;
                $currentStockBalance->save();
            }
            lotSerialDetails::whereIn('transaction_detail_id', $adjustmentDetails)
                ->where('transaction_type', 'adjustment')
                ->delete();


            CurrentStockBalance::whereIn('transaction_detail_id', $adjustmentDetails)
                ->where('transaction_type', 'adjustment')
                ->delete();


            StockAdjustment::where('id', $id)->update(['deleted_by' => Auth::id(), 'is_delete' => true]);
            StockAdjustment::where('id', $id)->delete();
            StockAdjustmentDetail::where('adjustment_id', $id)->update(['deleted_by' => Auth::id(), 'is_delete' => true]);
            StockAdjustmentDetail::where('adjustment_id', $id)->delete();


            $data = [
                'success' => 'Adjustment was removed, and the quantity was returned.',
            ];



        }else{
            StockAdjustment::where('id', $id)->update(['deleted_by' => Auth::id(), 'is_delete' => true]);
            StockAdjustment::where('id', $id)->delete();
            StockAdjustmentDetail::where('adjustment_id', $id)->update(['deleted_by' => Auth::id(), 'is_delete' => true]);
            StockAdjustmentDetail::where('adjustment_id', $id)->delete();

            $data = [
                'success' => 'Adjustment was removed',
            ];
        }



        return response()->json($data, 200);
    }

    public function listData()
    {

        $adjustmentResults= StockAdjustment::where('is_delete',0)
            ->with(['businessLocation:id,name', 'createdPerson:id,username'])
            ->OrderBy('id','desc')->get();
        return DataTables::of($adjustmentResults)
            ->addColumn('checkbox',function($adjustment){
                return
                    '
                    <div class="form-check form-check-sm form-check-custom ">
                        <input class="form-check-input" type="checkbox" data-checked="delete" value='.$adjustment->id.' />
                    </div>
                ';
            })
            ->editColumn('created_by', function($adjustment){
                return $adjustment->createdPerson['username'] ?? '-';
            })
            ->editColumn('location_name', function($adjustment){
                return $adjustment->businessLocation['name'] ?? '';
            })

            ->editColumn('date',function($adjustment){
                $dateTime = DateTime::createFromFormat("Y-m-d H:i:s",$adjustment->created_at);
                $formattedDate = $dateTime->format("m-d-Y " );
                $formattedTime = $dateTime->format(" h:i A " );
                return $formattedDate.'<br>'.'('.$formattedTime.')';
            })
//            ->editColumn('adjustmentItems',function($adjustment){
//                $adjustmentDetails=$adjustment->purchase_details;
//                $items='';
//                foreach ($adjustmentDetails as $key => $pd) {
//                    $variation=$pd->productVariation;
//                    $productName=$variation->product->name;
//                    $sku=$variation->product->sku ?? '';
//                    $variationName=$variation->variationTemplateValue->name ?? '';
//                    $items.="$productName,$variationName,$sku ;";
//                }
//                return $items;
//            })
            ->editColumn('status', function($adjustment) {
                $html='';
                if($adjustment->status== 'prepared'){
                    $html= "<span class='badge badge-light-warning> $adjustment->status </span>";
                }elseif($adjustment->status == 'completed'){
                    $html = "<span class='badge badge-light-success'>$adjustment->status</span>";
                }
                return $html;
            })
            ->addColumn('action', function ($adjustment) {

                $html = '
                    <div class="dropdown ">
                        <button class="btn m-2 btn-sm btn-light btn-primary fw-semibold fs-7  dropdown-toggle " type="button" id="purchaseDropDown" data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                        </button>
                        <div class="z-3">
                        <ul class="dropdown-menu z-10 p-5 " aria-labelledby="purchaseDropDown" role="menu">';
                if(hasView('stock transfer')){
                    $html .= '<a class="dropdown-item p-2  px-3 view_detail  text-gray-600 rounded-2"   type="button" data-href="'.route('stock-adjustment.show', $adjustment->id).'">
                                View
                            </a>';
                }
                if (hasPrint('stock transfer')){
                    $html .= ' <a class="dropdown-item p-2  px-3  text-gray-600 print-invoice rounded-2"  data-href="' . route('adjustment.print',$adjustment->id) .'">print</a>';
                }
                if (hasUpdate('stock transfer')){
                    $html .= '      <a href="'.route('stock-adjustment.edit', $adjustment->id).'" class="dropdown-item p-2  px-3 view_detail  text-gray-600 rounded-2">Edit</a> ';
                }
                if (hasDelete('stock transfer')){
                    $html .= '<a class="dropdown-item p-2  px-3 view_detail  text-gray-600 round rounded-2" data-id='.$adjustment->id.' data-adjustment-voucher-no='.$adjustment->adjustment_voucher_no.' data-adjustment-status='.$adjustment->status.' data-kt-adjustmentItem-table="delete_row">Delete</a>';
                }
                $html .= '</ul></div></div>';

                return (hasView('stock transfer') && hasPrint('stock transfer') && hasUpdate('stock transfer') && hasDelete('stock transfer') ? $html : 'No Access');
            })
            ->rawColumns(['action', 'checkbox', 'location_name', 'status','date', 'created_by'])
            ->make(true);
    }

    public function invoicePrint($id)
    {
        $adjustment=StockAdjustment::with(['businessLocation', 'createdPerson:id,username'])->where('id',$id)->first()->toArray();



        $location = $adjustment['business_location'];

        $adjustment_details=StockAdjustmentDetail::where('adjustment_id',$adjustment['id'])
            ->where('is_delete','0')
            ->with(['product', 'uom','productVariation'=>function($q){
                $q->with('variationTemplateValue');
            }])
            ->get();


        $invoiceHtml = view('App.stock.adjustment.invoice',compact('adjustment','location','adjustment_details'))->render();
        return response()->json(['html' => $invoiceHtml]);
    }
    public function filterList(Request $request)
    {

        $dateRange = $request->data['filter_date'];
        $dates = explode(' - ', $dateRange);

        $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[0])->startOfDay();
        $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[1])->endOfDay();


        $query = StockAdjustment::where('is_delete', 0)
            ->with(['businessLocation:id,name', 'created_by:id,username'])
            ->whereBetween('created_at', [$startDate, $endDate]);;

        if ($request->data['filter_status'] != 0) {
            $status = '';

            switch ($request->data['filter_status']) {
                case 1:
                    $status = 'prepared';
                    break;
                case 2:
                    $status = 'completed';
                    break;
            }

            $query->where('status', $status);
        }

        if ($request->data['filter_locations'] != 0) {
            $query->where('business_location', $request->data['filter_locations']);
        }

//        if ($request->data['filter_locations_to'] != 0) {
//            $query->where('to_location', $request->data['filter_locations_to']);
//        }
//
//        if ($request->data['filter_stocktransferperson'] != 0) {
//            $query->where('transfered_person', $request->data['filter_stocktransferperson']);
//        }
//
//        if ($request->data['filter_stockreceiveperson'] != 0) {
//            $query->where('received_person', $request->data['filter_stockreceiveperson']);
//        }

        $stockAdjustments = $query->get();


        return response()->json($stockAdjustments, 200);
    }

    private function updateOrCreateLotSerialDetails($checkLotDetail, $transferDetailId, $balance, $transactionType, $qty)
    {
        if ($checkLotDetail) {
            $checkLotDetail->uom_quantity += $qty;
            $checkLotDetail->save();
        } else {
            lotSerialDetails::create([
                'transaction_detail_id' => $transferDetailId,
                'transaction_type' => $transactionType,
                'current_stock_balance_id' => $balance['id'],
                'uom_id' => $balance['ref_uom_id'],
                'uom_quantity' => $qty
            ]);
        }
    }

    public function generateVoucherNumber($prefix)
    {
        $lastStockAdjustmentId = StockAdjustment::orderBy('id', 'DESC')->select('id')->first()->id ?? 0;

        $voucherNumber =  sprintf($prefix.'-' . '%06d', ($lastStockAdjustmentId + 1));

        return $voucherNumber;
    }
}
