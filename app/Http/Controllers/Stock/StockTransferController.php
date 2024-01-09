<?php

namespace App\Http\Controllers\Stock;

use App\Helpers\UomHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Stock\StoreStockTransferRequest;
use App\Http\Requests\Stock\UpdateStockTransferRequest;
use App\Models\lotSerialDetails;
use App\Models\BusinessUser;
use App\Models\CurrentStockBalance;
use App\Models\Product\PriceGroup;
use App\Models\Product\Product;
use App\Models\Product\Unit;
use App\Models\Product\UOM;
use App\Models\Product\UOMSet;
use App\Models\productPackagingTransactions;
use App\Models\settings\businessLocation;
use App\Models\settings\businessSettings;
use App\Models\Stock\Stockout;
use App\Models\Stock\StockoutDetail;
use App\Models\Stock\StockTransfer;
use App\Models\Stock\StockTransferDetail;
use App\Models\stock_history;
use App\Services\packaging\packagingServices;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use function PHPUnit\Framework\isEmpty;
use function Symfony\Component\String\b;

class StockTransferController extends Controller
{
    private $currency;

    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
        $this->middleware('canCreate:user')->only(['create', 'store']);
        $settings = businessSettings::select('lot_control', 'currency_id')->with('currency')->first();
        $this->setting = $settings;
        $this->currency = $settings->currency;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $locations = businessLocation::select('id', 'name')->get();
        $stockouts_person = BusinessUser::select('id', 'username')->get();
        return view('App.stock.transfer.index', [
            'stock_transfers' => StockTransfer::all(),
            'locations' => $locations,
            'stockoutsperson' => $stockouts_person,
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
        $currency=$this->currency;

        return view('App.stock.transfer.add', [
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
    public function store(StoreStockTransferRequest $request)
    {
        DB::transaction(function () use ($request) {
            $settings = businessSettings::all()->first();
            $transfer_details = $request->transfer_details;
            $transfered_at = date('Y-m-d', strtotime($request->transfered_at));
            $prefix = 'ST';
            $voucherNumber = $this->generateVoucherNumber($prefix);

            $stock_transfer = StockTransfer::create([
                'transfer_voucher_no' => $voucherNumber,
                'from_location' => $request->from_location,
                'to_location' => $request->to_location,
                'transfered_at' => $transfered_at,
                'transfered_person' => $request->transfered_person,
                'status' => $request->status,
                'received_person' => $request->received_person,
                'remark' => $request->remark,
                'created_at' => now(),
                'created_by' => Auth::id(),
            ]);

            foreach ($transfer_details as $transfer_detail) {

                $referencUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($transfer_detail['quantity'],$transfer_detail['uom_id']);
                $requestQty=$referencUomInfo['qtyByReferenceUom'];

                $transferDetail = StockTransferDetail::create([
                    'transfer_id' => $stock_transfer->id,
                    'product_id' => $transfer_detail['product_id'],
                    'variation_id' => $transfer_detail['variation_id'],
                    'uom_id' => $transfer_detail['uom_id'],
                    'quantity' => $transfer_detail['quantity'],
                    'uom_price' => 0,
                    'subtotal' => 0,
                    'per_item_expense' => 0,
                    'expense' => 0,
                    'subtotal_with_expense' => 0,
                    'per_ref_uom_price' => 0,
                    'ref_uom_id' => $referencUomInfo['referenceUomId'],
                    'currency_id' => $transfer_detail['currency_id'],
                    'remark' => $transfer_detail['remark'],
                    'created_at' => now(),
                    'created_by' => Auth::id(),
                ]);

                $packaging=new packagingServices();
                $packaging->packagingForTx($transfer_detail,$transferDetail->id,'transfer');



                $referenceUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($transfer_detail['quantity'], $transfer_detail['uom_id']);
                $qtyToDecrease = $referenceUomInfo['qtyByReferenceUom'];
                $qtyToIncrease = $referenceUomInfo['qtyByReferenceUom'];


                if ($request->status == 'in_transit' || $request->status == 'completed') {

                    stock_history::create([
                        'business_location_id' => $request->from_location,
                        'product_id' => $transfer_detail['product_id'],
                        'variation_id' => $transfer_detail['variation_id'],
                        'lot_serial_no' => null,
                        'expired_date' => null,
                        'transaction_type' => 'transfer',
                        'transaction_details_id' => $transferDetail->id,
                        'increase_qty' => 0,
                        'decrease_qty' => $referenceUomInfo['qtyByReferenceUom'],
                        'ref_uom_id' => $referenceUomInfo['referenceUomId'],
                        'created_at' => $transfered_at,
                    ]);


                    // Decrease current quantity for "from_location"
                    $currentBalances = CurrentStockBalance::where('business_location_id', $request->from_location)
                        ->where('product_id', $transfer_detail['product_id'])
                        ->where('variation_id', $transfer_detail['variation_id'])
                        ->where('current_quantity', '>', 0)
                        ->when($settings->accounting_method == 'fifo', function ($query) {
                            return $query->orderBy('id');
                        }, function ($query) {
                            return $query->orderByDesc('id');
                        })
                        ->get();

                    foreach ($currentBalances as $balance) {
                        $stockQty = $balance->current_quantity;

                        if ($qtyToDecrease > $stockQty) { //10 8 6 /2 2 10
                            $balance->update([
                                'current_quantity' => 0,
                            ]);

                            //record decreased qty to lot serial details
                            $this->recordLotSerialDetails($transferDetail->id,$balance, 'transfer', $stockQty);


                            $qtyToDecrease -= $stockQty;
                        }elseif($stockQty > $qtyToDecrease){ //10 > 6
                            $leftStockQty = $stockQty - $qtyToDecrease;

                            $balance->update([
                                'current_quantity' => $leftStockQty,
                            ]);

                            //record decreased qty to lot serial details
                            $this->recordLotSerialDetails($transferDetail->id,$balance, 'transfer', $qtyToDecrease);


                            break;
                        }elseif($stockQty == $qtyToDecrease){
                            $balance->update([
                                'current_quantity' => 0,
                            ]);

                            //record decreased qty to lot serial details
                            $this->recordLotSerialDetails($transferDetail->id,$balance, 'transfer', $stockQty);
                            //transfer detail record to history

                            break;
                        }
                    }


                }

                if ($request->status == 'completed'){
                    $lotSerialDetails = lotSerialDetails::where('transaction_type', 'transfer')
                        ->where('transaction_detail_id', $transferDetail->id)->get();

                    foreach ($lotSerialDetails as $lotDetail){
                        $currentStockDetail = CurrentStockBalance::where('id', $lotDetail->current_stock_balance_id)->first();

                        // Increase current quantity for "to_location"
                       CurrentStockBalance::create([
                            'business_location_id' => $request->to_location,
                            'product_id' => $currentStockDetail->product_id,
                            'variation_id' => $currentStockDetail->variation_id,
                            'transaction_type' => 'transfer',
                            'transaction_detail_id' => $transferDetail->id,
                            'expired_date' => $currentStockDetail->expired_date,
                            'batch_no' => $currentStockDetail->batch_no,
                            'lot_serial_no' => $currentStockDetail->lot_serial_no,
                            'ref_uom_id' => $currentStockDetail->ref_uom_id,
                            'ref_uom_quantity' => $lotDetail->uom_quantity,
                            'ref_uom_price' => $currentStockDetail->ref_uom_price,
                            'current_quantity' => $lotDetail->uom_quantity,
                           'created_at' => now(),
                        ]);
                    }

                    stock_history::create([
                        'business_location_id' => $request->to_location,
                        'product_id' => $transfer_detail['product_id'],
                        'variation_id' => $transfer_detail['variation_id'],
                        'lot_serial_no' => null,
                        'expired_date' => null,
                        'transaction_type' => 'transfer',
                        'transaction_details_id' => $transferDetail->id,
                        'increase_qty' => $referenceUomInfo['qtyByReferenceUom'],
                        'decrease_qty' => 0,
                        'ref_uom_id' => $referenceUomInfo['referenceUomId'],
                        'created_at' => $transfered_at,
                    ]);


                }

            }


        });


        return redirect(route('stock-transfer.index'))->with(['success' => 'Stock transferred successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(StockTransfer $stockTransfer)
    {

        $fromLocation = BusinessLocation::find($stockTransfer->from_location);
        $toLocation = BusinessLocation::find($stockTransfer->to_location);
        $transferedPerson = BusinessUser::find($stockTransfer->transfered_person);
        $receivedPerson = BusinessUser::find($stockTransfer->received_person);

        $modifiedStockTransfer = $stockTransfer;
        $modifiedStockTransfer->from_location_name = $fromLocation->name;
        $modifiedStockTransfer->to_location_name = $toLocation->name;
        $modifiedStockTransfer->transfered_person_name = $transferedPerson->username;
        $modifiedStockTransfer->received_person_name = $receivedPerson->username;

        $stock_transfer_details = StockTransferDetail::with([
            'productVariation.product:id,name',
            'productVariation.variationTemplateValue:id,name',
            'uom'
        ])->where('transfer_id', $stockTransfer->id)->get();
        //    return $stock_transfer_details;
        $modified_stock_transfer_details = $stock_transfer_details->lazy()->map(function($detail) {
            $variation = $detail->productVariation;
            return [
                'id' => $detail->id,
                'transfer_id' => $detail->transfer_id,
                'product_id' => $detail->product_id,
                'variation_id' => $detail->variation_id,
                'uom_id' => $detail->uom_id,
                'quantity' => number_format((float)$detail->quantity, 2, '.', ''),
                'remark' => $detail->remark,
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
                'total_purchase_price' => $detail->purchase_price*$detail->quantity,
            ];
        });

        //    return $modified_stock_transfer_details;
        return view('App.stock.transfer.view', [
            'stockTransfer' => $modifiedStockTransfer,
            'stockTransferDetails' => $modified_stock_transfer_details
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockTransfer $stockTransfer)
    {
        if($stockTransfer->status == 'completed'){
            $stockTransfers = StockTransferDetail::where('transfer_id', $stockTransfer->id)->get();
            $current = CurrentStockBalance::where('transaction_type', 'transfer')->whereIn('transaction_detail_id', $stockTransfers->pluck('id'))->get()->pluck('id');
            $finalCheck = lotSerialDetails::whereIn('transaction_type', ['sale', 'stock_out'])->whereIn('current_stock_balance_id', $current)->get();
            if ($finalCheck->count() == 0) {
                $transfer_persons = BusinessUser::with('personal_info')->get();
                $locations = businessLocation::all();
                $setting=businessSettings::first();
                $currency=$this->currency;
                $transfer = StockTransfer::with('currency')->where('id', $stockTransfer->id)->get()->first();
                $business_location_id=$transfer->from_location;
                $stock_transfer_details = StockTransferDetail::with([
                    'packagingTx',
                    'currency',
                    'productVariation' => function ($q) {
                        $q->select('id', 'product_id', 'variation_template_value_id', 'default_selling_price')
                            ->with([
                                'packaging' => function($q) { $q->with('uom');},
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
                    ->where('transfer_id', $stockTransfer->id)->where('is_delete', 0)
                    ->withSum(['stock' => function ($q) use ($business_location_id) {
                        $q->where('business_location_id', $business_location_id);
                    }], 'current_quantity');
                $transfer_details= $stock_transfer_details->get();


                return view('App.stock.transfer.edit', [
                    'stockTransfer' => $stockTransfer,
                    'stock_transfer_details' => $transfer_details,
                    'transfer_persons' => $transfer_persons,
                    'locations' => $locations,
                    'currency' => $currency,
                    'setting' => $setting,

                ]);
            } else {
                return redirect()->back()->with(['alart'=>'Something Went Wrong While creating sale']);
            }


        }else{
            $transfer_persons = BusinessUser::with('personal_info')->get();
            $locations = businessLocation::all();
            $setting=businessSettings::first();
            $currency=$this->currency;
            $transfer = StockTransfer::with('currency')->where('id', $stockTransfer->id)->get()->first();
            $business_location_id=$transfer->from_location;
            $stock_transfer_details = StockTransferDetail::with([
                'packagingTx',
                'currency',
                'productVariation' => function ($q) {
                    $q->select('id', 'product_id', 'variation_template_value_id', 'default_selling_price')
                        ->with([
                            'packaging' => function($q) { $q->with('uom');},
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
                ->where('transfer_id', $stockTransfer->id)->where('is_delete', 0)
                ->withSum(['stock' => function ($q) use ($business_location_id) {
                    $q->where('business_location_id', $business_location_id);
                }], 'current_quantity');
            $transfer_details= $stock_transfer_details->get();

            return view('App.stock.transfer.edit', [
                'stockTransfer' => $stockTransfer,
                'stock_transfer_details' => $transfer_details,
                'transfer_persons' => $transfer_persons,
                'locations' => $locations,
                'currency' => $currency,
                'setting' => $setting,

            ]);
        }


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStockTransferRequest $request, StockTransfer $stockTransfer)
    {

        $requestStocktransferDetails = $request->transfer_details;
        $oldStatus = $stockTransfer->status;
        $newStatus = $request->status;
        $transfered_at = date('Y-m-d', strtotime($request->transfered_at));

        $settings =  businessSettings::all()->first();

        DB::beginTransaction();
        try {
            $packagingService=new packagingServices();
            if($oldStatus != 'completed'){
                $existingTransferDetailIds = [];

                $existingTransferDetails = array_filter($requestStocktransferDetails, function ($detail) {
                    return isset($detail['transfer_detail_id']);
                });

                $newTransferDetails = array_filter($requestStocktransferDetails, function ($detail) {
                    return !isset($detail['transfer_detail_id']);
                });


                StockTransfer::where('id', $stockTransfer->id)->update([
                    'to_location' => $request->to_location,
                    'transfered_at' => $transfered_at,
                    'status' => $request->status,
                    'remark' => $request->remark,
                    'updated_by' => \auth()->id()
                ]);


                // ========== Being:: Update existing row ==========
                foreach ($existingTransferDetails as $transferDetail) {
                    $transferDetailId = $transferDetail['transfer_detail_id'];

                    $packagingService->updatePackagingForTx($transferDetail,$transferDetailId,'transfer');
                    $newQty = $transferDetail['quantity'];
                    $beforeEditQty = $transferDetail['before_edit_quantity'];

                    $stockTransferDetailData = [
                        'remark' => $transferDetail['remark'],
                        'quantity' => $newQty,
                        'uom_id' => $transferDetail['uom_id'],
                        'updated_at' => now(),
                        'updated_by' => Auth::id(),
                    ];


                    StockTransferDetail::where('id', $transferDetailId)
                        ->where('transfer_id', $stockTransfer->id)
                        ->update($stockTransferDetailData);

                    $referenceUomInfoForHis = UomHelper::getReferenceUomInfoByCurrentUnitQty($newQty, $transferDetail['uom_id']);



                    $currentBalances = CurrentStockBalance::where('business_location_id', $request->from_location)
                        ->where('product_id', $transferDetail['product_id'])
                        ->where('variation_id', $transferDetail['variation_id'])
                        ->where('current_quantity', '>', 0)
                        ->when($settings->accounting_method == 'fifo', function ($query) {
                            return $query->orderBy('id');
                        }, function ($query) {
                            return $query->orderByDesc('id');
                        })
                        ->get();

                    if($newStatus == 'prepared' || $newStatus == 'pending'){
                        stock_history::where('transaction_type', 'transfer')
                            ->where('transaction_details_id', $transferDetail['transfer_detail_id'])->delete();
                        if($oldStatus == 'in_transit'){
                            $restoreLotSerialDetails = lotSerialDetails::where('transaction_detail_id', $transferDetailId)
                                ->where('transaction_type', 'transfer')
                                ->get();

                            foreach ($restoreLotSerialDetails as $restoreDetail){
                                $currentStockBalance = CurrentStockBalance::where('id', $restoreDetail->current_stock_balance_id)->first();
                                $currentStockBalance->current_quantity += $restoreDetail->uom_quantity;
                                $currentStockBalance->save();
                                $restoreDetail->delete();
                            }
                        }
                    }

                    if ($newStatus == 'in_transit' || $newStatus == 'completed') {

                        $stock_history = stock_history::where('transaction_type', 'transfer')
                            ->where('transaction_details_id', $transferDetailId)
                            ->first();

                        if ($stock_history){
                            $stock_history->decrease_qty = $referenceUomInfoForHis['qtyByReferenceUom'];
                            $stock_history->ref_uom_id = $referenceUomInfoForHis['referenceUomId'];
                            $stock_history->created_at = $request->transfered_at;
                            $stock_history->save();
                        }else{
                            stock_history::create([
                                'business_location_id' => $request->from_location,
                                'product_id' => $transferDetail['product_id'],
                                'variation_id' => $transferDetail['variation_id'],
                                'lot_serial_no' => null,
                                'expired_date' => null,
                                'transaction_type' => 'transfer',
                                'transaction_details_id' => $transferDetailId,
                                'increase_qty' => 0,
                                'decrease_qty' => $referenceUomInfoForHis['qtyByReferenceUom'],
                                'ref_uom_id' => $referenceUomInfoForHis['referenceUomId'],
                                'created_at' => $transfered_at,
                            ]);
                        }


                        if ($newQty > $beforeEditQty || ($newQty == $beforeEditQty && $newStatus == 'in_transit')) {
                            $updateableQty = ($oldStatus == 'prepared' || $oldStatus == 'pending' || $newQty == $beforeEditQty) ? $newQty : ($newQty - $beforeEditQty);
                            $referenceUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($updateableQty, $transferDetail['uom_id']);
                            $qtyToDecrease = $referenceUomInfo['qtyByReferenceUom'];


                            foreach ($currentBalances as $balance) {
                                $currentQty = $balance->current_quantity;
                                $checkLotDetail = lotSerialDetails::where('transaction_type', 'transfer')
                                    ->where('transaction_detail_id', $transferDetailId)
                                    ->where('current_stock_balance_id', $balance->id)
                                    ->first();

                                if ($qtyToDecrease > $currentQty) {
                                    $this->updateOrCreateLotSerialDetails($checkLotDetail, $transferDetailId, $balance, 'transfer', $currentQty);
                                    $balance->update([
                                        'current_quantity' => 0,
                                    ]);
                                    $qtyToDecrease -= $currentQty;
                                } elseif ($currentQty > $qtyToDecrease) {
                                    $this->updateOrCreateLotSerialDetails($checkLotDetail, $transferDetailId, $balance, 'transfer', $qtyToDecrease);
                                    $balance->update([
                                        'current_quantity' => $currentQty - $qtyToDecrease,
                                    ]);
                                    break;
                                } elseif ($currentQty == $qtyToDecrease) {
                                    $this->updateOrCreateLotSerialDetails($checkLotDetail, $transferDetailId, $balance, 'transfer', $qtyToDecrease);
                                    $balance->update([
                                        'current_quantity' => $currentQty - $qtyToDecrease,
                                    ]);
                                    $qtyToDecrease -= $currentQty;

                                    if ($qtyToDecrease == 0) {
                                        break;
                                    }
                                }
                            }
                        }

                        if ($oldStatus == 'prepared' || $oldStatus == 'pending') {
                            if ($beforeEditQty > $newQty ||  ($newQty == $beforeEditQty && $newStatus == 'completed')) {
                                $updateableQty = $newQty;
                                $referenceUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($updateableQty, $transferDetail['uom_id']);
                                $qtyToDecrease = $referenceUomInfo['qtyByReferenceUom'];

                                foreach ($currentBalances as $balance) {
                                    $currentQty = $balance->current_quantity;
                                    $checkLotDetail = lotSerialDetails::where('transaction_type', 'transfer')
                                        ->where('transaction_detail_id', $transferDetailId)
                                        ->where('current_stock_balance_id', $balance->id)
                                        ->first();

                                    if ($qtyToDecrease > $currentQty) {
                                        $this->updateOrCreateLotSerialDetails($checkLotDetail, $transferDetailId, $balance, 'transfer', $currentQty);
                                        $balance->update([
                                            'current_quantity' => 0,
                                        ]);
                                        $qtyToDecrease -= $currentQty;
                                    } elseif ($currentQty > $qtyToDecrease) {
                                        $this->updateOrCreateLotSerialDetails($checkLotDetail, $transferDetailId, $balance, 'transfer', $qtyToDecrease);
                                        $balance->update([
                                            'current_quantity' => $currentQty - $qtyToDecrease,
                                        ]);
                                        break;
                                    } elseif ($currentQty == $qtyToDecrease) {
                                        $this->updateOrCreateLotSerialDetails($checkLotDetail, $transferDetailId, $balance, 'transfer', $qtyToDecrease);
                                        $balance->update([
                                            'current_quantity' => $currentQty - $qtyToDecrease,
                                        ]);
                                        $qtyToDecrease -= $currentQty;

                                        if ($qtyToDecrease == 0) {
                                            break;
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($beforeEditQty > $newQty) {
                                $updateableQty = $beforeEditQty - $newQty;
                                $referenceUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($updateableQty, $transferDetail['uom_id']);
                                $qtyToReincrease = $referenceUomInfo['qtyByReferenceUom'];

                                $lotSerialDetails = lotSerialDetails::where('transaction_detail_id', $transferDetailId)
                                    ->where('transaction_type', 'transfer')
                                    ->when($settings->accounting_method == 'fifo', function ($query) {
                                        return $query->orderByDesc('current_stock_balance_id');
                                    }, function ($query) {
                                        return $query->orderBy('current_stock_balance_id');
                                    })
                                    ->get();

                                foreach ($lotSerialDetails as $lotSerialDetail) {
                                    $stockQty = $lotSerialDetail->uom_quantity;

                                    if ($qtyToReincrease >= $stockQty) {
                                        $qtyToReincrease -= $stockQty;

                                        $currentStockBalance = CurrentStockBalance::where('id', $lotSerialDetail->current_stock_balance_id)->first();
                                        $currentStockBalance->current_quantity += $stockQty;
                                        $currentStockBalance->save();

                                        $lotSerialDetail->delete();

                                        if ($qtyToReincrease == $stockQty) {
                                            CurrentStockBalance::where('transaction_detail_id', $transferDetailId)
                                                ->where('transaction_type', 'transfer')
                                                ->where('batch_no', $currentStockBalance->batch_no)
                                                ->delete();
                                        }

                                        if ($qtyToReincrease == 0) {
                                            break;
                                        }
                                    } elseif ($stockQty > $qtyToReincrease) {
                                        $currentStockBalance = CurrentStockBalance::where('id', $lotSerialDetail->current_stock_balance_id)->first();
                                        $currentStockBalance->current_quantity += $qtyToReincrease;
                                        $currentStockBalance->save();

                                        $lotSerialDetail->uom_quantity -= $qtyToReincrease;
                                        $lotSerialDetail->save();
                                        break;
                                    } else {
                                        break;
                                    }
                                }
                            }
                        }
                    }




                    if ($newStatus == 'completed'){

                        StockTransfer::where('id', $stockTransfer->id)
                            ->update([
                                'received_at' => now(),
                                'received_person' => Auth::id(),
                            ]);

                        $lotSerialDetails = lotSerialDetails::where('transaction_type', 'transfer')
                            ->where('transaction_detail_id', $transferDetailId)->get();

                            stock_history::create([
                                'business_location_id' => $request->to_location,
                                'product_id' => $transferDetail['product_id'],
                                'variation_id' => $transferDetail['variation_id'],
                                'lot_serial_no' => null,
                                'expired_date' => null,
                                'transaction_type' => 'transfer',
                                'transaction_details_id' => $transferDetailId,
                                'increase_qty' => $referenceUomInfoForHis['qtyByReferenceUom'],
                                'decrease_qty' => 0,
                                'ref_uom_id' => $referenceUomInfoForHis['referenceUomId'],
                                'created_at' => $transfered_at,
                            ]);
//                        }

                        foreach ($lotSerialDetails as $lotDetail){
                            $currentStockDetail = CurrentStockBalance::where('id', $lotDetail->current_stock_balance_id)->first();

                            // Increase current quantity for "to_location"
                            CurrentStockBalance::create([
                                'business_location_id' => $request->to_location,
                                'product_id' => $currentStockDetail->product_id,
                                'variation_id' => $currentStockDetail->variation_id,
                                'transaction_type' => 'transfer',
                                'transaction_detail_id' => $transferDetailId,
                                'expired_date' => $currentStockDetail->expired_date,
                                'batch_no' => $currentStockDetail->batch_no,
                                'lot_serial_no' => $currentStockDetail->lot_serial_no,
                                'ref_uom_id' => $currentStockDetail->ref_uom_id,
                                'ref_uom_quantity' => $lotDetail->uom_quantity,
                                'ref_uom_price' => $currentStockDetail->ref_uom_price,
                                'current_quantity' => $lotDetail->uom_quantity,
                                'created_at' => now(),
                            ]);
                        }

                    }


                    $existingTransferDetailIds[] = $transferDetailId;
                }

                // ========== Being:: Update existing row ==========


                // ========== Being:: Delete rows that were not updated ==========
                $transactionDetails = StockTransferDetail::where('transfer_id', $stockTransfer->id)
                    ->whereNotIn('id', $existingTransferDetailIds)
                    ->get();



                foreach ($transactionDetails as $transactionDetail){

                    $stock_history = stock_history::where('transaction_type', 'transfer')
                        ->where('transaction_details_id', $transactionDetail->id)
                        ->delete();

                    CurrentStockBalance::where('transaction_detail_id', $transactionDetail->id)
                        ->where('transaction_type', 'transfer')
                        ->delete();

                    $restoreLotSerialDetails = lotSerialDetails::where('transaction_detail_id', $transactionDetail->id)
                        ->where('transaction_type', 'transfer')
                        ->get();


                    foreach ($restoreLotSerialDetails as $restoreDetail){
                        $currentStockBalance = CurrentStockBalance::where('id', $restoreDetail->current_stock_balance_id)->first();
                        $currentStockBalance->current_quantity += $restoreDetail->uom_quantity;
                        $currentStockBalance->save();
                        $restoreDetail->delete();
                    }
                }

                StockTransferDetail::where('transfer_id', $stockTransfer->id)
                    ->whereNotIn('id', $existingTransferDetailIds)
                    ->update(['is_delete' => true, 'deleted_by' => Auth::id()]);

                StockTransferDetail::where('transfer_id', $stockTransfer->id)
                    ->whereNotIn('id', $existingTransferDetailIds)
                    ->delete();
                // ========== End:: Delete rows that were not updated ==========

                // ========== Being:: Create new rows ==========
                $transferDetailsToUpdate = [];
                foreach ($newTransferDetails as $transfer_detail) {

                    $transferDetail = StockTransferDetail::create([
                        'transfer_id' => $stockTransfer->id,
                        'product_id' => $transfer_detail['product_id'],
                        'variation_id' => $transfer_detail['variation_id'],
                        'uom_id' => $transfer_detail['uom_id'],
                        'quantity' => $transfer_detail['quantity'],
                        'uom_price' => 0,
                        'subtotal' => 0,
                        'per_item_expense' => 0,
                        'expense' => 0,
                        'subtotal_with_expense' => 0,
                        'per_ref_uom_price' => 0,
                        'ref_uom_id' => $transfer_detail['ref_uom_id'],
                        'currency_id' => $transfer_detail['currency_id'],
                        'remark' => $transfer_detail['remark'],
                        'created_at' => now(),
                        'created_by' => Auth::id(),
                    ]);

                    $packaging=new packagingServices();
                    $packaging->packagingForTx($transfer_detail,$transferDetail->id,'transfer');

                    $referenceUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($transfer_detail['quantity'], $transfer_detail['uom_id']);
                    $qtyToDecrease = $referenceUomInfo['qtyByReferenceUom'];
                    $qtyToIncrease = $referenceUomInfo['qtyByReferenceUom'];


                    if ($request->status == 'in_transit' || $request->status == 'completed') {

                        stock_history::create([
                            'business_location_id' => $request->from_location,
                            'product_id' => $transfer_detail['product_id'],
                            'variation_id' => $transfer_detail['variation_id'],
                            'lot_serial_no' => null,
                            'expired_date' => null,
                            'transaction_type' => 'transfer',
                            'transaction_details_id' => $transferDetail->id,
                            'increase_qty' => 0,
                            'decrease_qty' => $referenceUomInfo['qtyByReferenceUom'],
                            'ref_uom_id' => $referenceUomInfo['referenceUomId'],
                            'created_at' => $transfered_at,
                        ]);


                        // Decrease current quantity for "from_location"
                        $currentBalances = CurrentStockBalance::where('business_location_id', $request->from_location)
                            ->where('product_id', $transfer_detail['product_id'])
                            ->where('variation_id', $transfer_detail['variation_id'])
                            ->where('current_quantity', '>', 0)
                            ->when($settings->accounting_method == 'fifo', function ($query) {
                                return $query->orderBy('id');
                            }, function ($query) {
                                return $query->orderByDesc('id');
                            })
                            ->get();
//1
                        foreach ($currentBalances as $balance) {
                            $stockQty = $balance->current_quantity; //2 5 5 5

                            if ($qtyToDecrease > $stockQty) { //13 > 2
                                $balance->update([
                                    'current_quantity' => 0,
                                ]);

                                //record decreased qty to lot serial details
                                $this->recordLotSerialDetails($transferDetail->id,$balance, 'transfer', $stockQty);

                                $qtyToDecrease -= $stockQty;
                                //transfer detail record to history
//                                $this->recordHistories($request->from_location, $transferDetail, $qtyToDecrease, 'decrease', $currentBalances = 0);

                            }elseif($stockQty > $qtyToDecrease){ //1 > 5
                                $leftStockQty = $stockQty - $qtyToDecrease;

                                $balance->update([
                                    'current_quantity' => $leftStockQty,
                                ]);

                                //record decreased qty to lot serial details
                                $this->recordLotSerialDetails($transferDetail->id,$balance, 'transfer', $qtyToDecrease);
                                //transfer detail record to history
//                                $this->recordHistories($request->from_location, $transferDetail, $qtyToDecrease, 'decrease', $leftStockQty);

                                break;
                            }
                        }

                    }

                    if ($request->status == 'completed') {


                        //transfer detail record to history
                        stock_history::create([
                            'business_location_id' => $request->to_location,
                            'product_id' => $transfer_detail['product_id'],
                            'variation_id' => $transfer_detail['variation_id'],
                            'lot_serial_no' => null,
                            'expired_date' => null,
                            'transaction_type' => 'transfer',
                            'transaction_details_id' => $transferDetail->id,
                            'increase_qty' => $referenceUomInfo['qtyByReferenceUom'],
                            'decrease_qty' => 0,
                            'ref_uom_id' => $referenceUomInfo['referenceUomId'],
                            'created_at' => $transfered_at,
                        ]);

                        $lotSerialDetails = lotSerialDetails::where('transaction_type', 'transfer')
                            ->where('transaction_detail_id', $transferDetail->id)->get();

                        foreach ($lotSerialDetails as $lotDetail) {
                            $currentStockDetail = CurrentStockBalance::where('id', $lotDetail->current_stock_balance_id)->first();

                            // Increase current quantity for "to_location"
                            $current_stock_balance_increment_new = CurrentStockBalance::create([
                                'business_location_id' => $request->to_location,
                                'product_id' => $currentStockDetail->product_id,
                                'variation_id' => $currentStockDetail->variation_id,
                                'transaction_type' => 'transfer',
                                'transaction_detail_id' => $transferDetail->id,
                                'expired_date' => $currentStockDetail->expired_date,
                                'batch_no' => $currentStockDetail->batch_no,
                                'lot_serial_no' => $currentStockDetail->lot_serial_no,
                                'ref_uom_id' => $currentStockDetail->ref_uom_id,
                                'ref_uom_quantity' => $lotDetail->uom_quantity,
                                'ref_uom_price' => $currentStockDetail->ref_uom_price,
                                'current_quantity' => $lotDetail->uom_quantity,
                                'created_at' => now(),
                            ]);

//                            $this->recordHistories($request->to_location, $transferDetail, $qtyToIncrease, 'increase', $lotDetail->uom_quantity );
                        }


                    }

                }
                // ========== End:: Create new rows ==========
            }

            if($oldStatus == 'completed'){
                StockTransfer::where('id', $stockTransfer->id)->update([
                    'status' => $request->status,
                ]);

                if($newStatus == 'in_transit'){
                    foreach($requestStocktransferDetails as $detail) {
                        CurrentStockBalance::where('transaction_type', 'transfer')
                            ->where('transaction_detail_id', $detail['transfer_detail_id'])->delete();

                        stock_history::where('business_location_id', $request->to_location)
                            ->where('transaction_type', 'transfer')
                            ->where('transaction_details_id', $detail['transfer_detail_id'])->delete();
                    }
                }
            }

            DB::commit();
            return redirect(route('stock-transfer.index'))->with(['success' => 'Stock transferred successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockTransfer $stockTransfer)
    {

        DB::transaction(function () use ($stockTransfer) {
            $details = StockTransferDetail::where('transfer_id', $stockTransfer->id)->get();
            $from_location = $stockTransfer->from_location;
            $to_location = $stockTransfer->to_location;


            foreach ($details as $detail) {
                $product_id = $detail->product_id;
                $variation_id = $detail->variation_id;
                $lot_no = $detail->lot_no;
                $smallest_qty = UomHelper::smallestQty($detail->uomset_id, $detail->unit_id, $detail->quantity);


                CurrentStockBalance::where('business_location_id', $to_location)
                    ->where('product_id', $product_id)
                    ->where('variation_id', $variation_id)
                    ->decrement('current_quantity', $smallest_qty);

                CurrentStockBalance::where('business_location_id', $to_location)
                    ->where('product_id', $product_id)
                    ->where('variation_id', $variation_id)
                    ->where('transaction_type', 'transfer')
                    ->delete();

                CurrentStockBalance::where('business_location_id', $from_location)
                    ->where('product_id', $product_id)
                    ->where('variation_id', $variation_id)
                    ->where('lot_no', $lot_no)
                    ->increment('current_quantity', $smallest_qty);
            }


            StockTransfer::where('id', $stockTransfer->id)->update(['deleted_by' => Auth::id(), 'is_delete' => true]);
            StockTransferDetail::where('transfer_id', $stockTransfer->id)->update(['deleted_by' => Auth::id(), 'is_delete' => true]);
            StockTransfer::where('id', $stockTransfer->id)->delete();
            StockTransferDetail::where('transfer_id', $stockTransfer->id)->delete();
        });

        return redirect(route('stock-transfer.index'))->with(['success' => 'Stockout deleted successfully']);
    }

    public function softDelete(string $id){
        $restore = request()->query('restore');

        if ($restore == 'true') {

            $transfer = StockTransfer::findOrFail($id);
            $transferDetailIds = StockTransferDetail::where('transfer_id', $id)->pluck('id');

            if ($transfer->status == 'in_transit' || $transfer->status == 'completed') {
                $lotSerialDetails = lotSerialDetails::whereIn('transaction_detail_id', $transferDetailIds)
                    ->orderByDesc('current_stock_balance_id')
                    ->get();


                foreach ($lotSerialDetails as $lotSerialDetail) {
                    $currentStockBalance = CurrentStockBalance::where('id', $lotSerialDetail->current_stock_balance_id)->first();
                    $currentStockBalance->current_quantity += $lotSerialDetail->uom_quantity;
                    $currentStockBalance->save();
                }
                lotSerialDetails::whereIn('transaction_detail_id', $transferDetailIds)->delete();
                $data = [
                    'success' => 'In Transit quantity was restored.',
                ];
            }

            if ($transfer->status == 'completed'){

                CurrentStockBalance::where('transaction_type', 'transfer')
                    ->whereIn('transaction_detail_id', $transferDetailIds)
                    ->delete();
                $data = [
                    'success' => 'Transferred quantity was restored.',
                ];
            }

            StockTransfer::where('id', $id)->update(['deleted_by' => Auth::id(), 'is_delete' => true]);
            StockTransfer::where('id', $id)->delete();
            StockTransferDetail::where('transfer_id', $id)->update(['deleted_by' => Auth::id(), 'is_delete' => true]);
            StockTransferDetail::where('transfer_id', $id)->delete();


        }else{
            StockTransfer::where('id', $id)->update(['deleted_by' => Auth::id(), 'is_delete' => true]);
            StockTransfer::where('id', $id)->delete();
            StockTransferDetail::where('transfer_id', $id)->update(['deleted_by' => Auth::id(), 'is_delete' => true]);
            StockTransferDetail::where('transfer_id', $id)->delete();

            $data = [
                'success' => '',
            ];
        }


        return response()->json($data, 200);

    }


    public function recordHistories($location, $recordDetails, $quantity, $qtyStatus, $remainBalanceQty){

        $currentStockData = $recordDetails->toArray();


        stock_history::create([
            'business_location_id' => $location,
            'product_id' => $currentStockData['product_id'],
            'variation_id' => $currentStockData['variation_id'],
            'lot_serial_no' => null,
            'expired_date' => null,
            'transaction_type' => 'transfer',
            'transaction_details_id' => $currentStockData['id'],
            'increase_qty' => $qtyStatus == 'increase' ? $quantity : 0,
            'decrease_qty' => $qtyStatus == 'decrease' ? $quantity : 0,
            'ref_uom_id' => $currentStockData['ref_uom_id'],
            'balance_quantity' => $remainBalanceQty,
            'created_at' => now(),
        ]);
        return true;

//
//        return stock_history::create([
//            'business_location_id' => $location,
//            'product_id' => $recordDetails->product_id,
//            'variation_id' => $recordDetails->variation_id,
//            'lot_serial_no' =>  null,
//            'expired_date' => null,
//            'transaction_type' => 'transfer',
//            'transaction_details_id' => $recordDetails->id,
//            'increase_qty' => $qtyStatus == 'increase' ? $quantity : 0,
//            'decrease_qty' => $qtyStatus == 'decrease' ? $quantity : 0,
//            'ref_uom_id' => $recordDetails->ref_uom_id,
//        ]);
    }

    public function recordLotSerialDetails($transactionId, $stockDetails, $transactionType, $uomQty){
        return lotSerialDetails::create([
            'transaction_detail_id' => $transactionId,
            'transaction_type' => $transactionType,
            'current_stock_balance_id' => $stockDetails['id'],
            'uom_id' => $stockDetails['ref_uom_id'],
            'uom_quantity' => $uomQty
        ]);
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

    public function listData()
    {

        $transferResults = StockTransfer::where('is_delete',0)
            ->with([
                'businessLocationFrom:id,name',
                'businessLocationTo:id,name',
                'stocktransferPerson:id,username',
                'stockreceivePerson:id,username',
            ])
            ->OrderBy('id','desc')->get();


        return DataTables::of($transferResults)
            ->addColumn('checkbox',function($transfer){
                return
                    '
                    <div class="form-check form-check-sm form-check-custom ">
                        <input class="form-check-input" type="checkbox" data-checked="delete" value='.$transfer->id.' />
                    </div>
                ';
            })
            ->editColumn('business_location_from', function($transfer){
                return $transfer->businessLocationFrom['name'] ?? '-';
            })
            ->editColumn('business_location_to', function($transfer){
                return $transfer->businessLocationTo['name'] ?? '-';
            })
//            ->editColumn('stocktransfer_person', function($transfer){
//                return $transfer->stocktransfer_person['username'] ?? '-';
//            })
//            ->editColumn('stockreceive_person', function($transfer){
//                return $transfer->stockreceive_person['username'] ?? '-';
//            })
            ->editColumn('status', function($transfer) {
                $html='';
                if($transfer->status== 'prepared'){
                    $html= "<span class='badge badge-light-secondary'> $transfer->status</span>";
                }elseif($transfer->status == 'pending'){
                    $html= "<span class='badge badge-light-primary'> $transfer->status</span>";
                }elseif ($transfer->status == 'in_transit'){
                    $html = "<span class='badge badge-light-warning'>$transfer->status</span>";
                }elseif($transfer->status == 'completed'){
                    $html = "<span class='badge badge-light-success'>$transfer->status</span>";
                }
                return $html;
            })
            ->addColumn('action', function ($transfer) {

                $html = '
                    <div class="dropdown ">
                        <button class="btn m-2 btn-sm btn-light btn-primary fw-semibold fs-7  dropdown-toggle " type="button" id="purchaseDropDown" data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                        </button>
                        <div class="z-3">
                        <ul class="dropdown-menu z-10 p-5 " aria-labelledby="purchaseDropDown" role="menu">';
                if(hasView('stock transfer')){
                    $html .= '<a class="dropdown-item p-2  px-3 view_detail  text-gray-600 rounded-2"   type="button" data-href="'.route('stock-transfer.show', $transfer->id).'">
                                View
                            </a>';
                }
                if (hasPrint('stock transfer')){
                    $html .= ' <a class="dropdown-item p-2  px-3  text-gray-600 print-invoice rounded-2"  data-href="' . route('transfer.print',$transfer->id) .'">print</a>';
                }
                if (hasUpdate('stock transfer')){
                    $html .= '      <a href="'.route('stock-transfer.edit', $transfer->id).'" class="dropdown-item p-2  px-3 view_detail  text-gray-600 rounded-2">Edit</a> ';
                }
                if (hasDelete('stock transfer')){
                        $html .= '<a class="dropdown-item p-2  px-3 view_detail  text-gray-600 round rounded-2" data-id='.$transfer->id.' data-voucherno='.$transfer->adjustment_voucher_no.' data-transfer-status='.$transfer->status.' data-kt-transferItem-table="delete_row">Delete</a>';
                }
                $html .= '</ul></div></div>';

                return (hasView('stock transfer') && hasPrint('stock transfer') && hasUpdate('stock transfer') && hasDelete('stock transfer') ? $html : 'No Access');
            })
            ->rawColumns(['checkbox', 'action', 'business_location_from', 'business_location_to', 'stocktransfer_person','stockreceive_person','status'])
            ->make(true);
    }

    public function invoicePrint($id)
    {
        $transfer = StockTransfer::with([
            'businessLocationFrom',
            'businessLocationTo',
            'stocktransferPerson:id,username',
            'stockreceivePerson:id,username',
        ])->where('id',$id)->first()->toArray();


        $transfer_details =StockTransferDetail::where('transfer_id',$transfer['id'])
            ->where('is_delete','0')
            ->with(['product', 'uom','productVariation'=>function($q){
                $q->with('variationTemplateValue');
            }])
            ->get();


        $invoiceHtml = view('App.stock.transfer.invoice',compact('transfer','transfer_details'))->render();
        return response()->json(['html' => $invoiceHtml]);
    }

//    public function filterList(Request $request)
//    {
//
//        $dateRange = $request->data['filter_date'];
//        $dates = explode(' - ', $dateRange);
//
//        $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[0])->startOfDay();
//        $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[1])->endOfDay();
//
//
//        $query = StockTransfer::where('is_delete', 0)
//            ->with(['businessLocationFrom:id,name', 'businessLocationTo:id,name','stocktransferPerson:id,username', 'stockreceivePerson:id,username', 'created_by:id,username'])
//            ->whereBetween('transfered_at', [$startDate, $endDate]);;
//
//        if ($request->data['filter_status'] != 0) {
//            $status = '';
//
//            switch ($request->data['filter_status']) {
//                case 1:
//                    $status = 'pending';
//                    break;
//                case 2:
//                    $status = 'confirmed';
//                    break;
//            }
//
//            $query->where('status', $status);
//        }
//
//        if ($request->data['filter_locations_from'] != 0) {
//            $query->where('from_location', $request->data['filter_locations_from']);
//        }
//
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
//
//        $stocktransfers = $query->get();
//
//
//        return response()->json($stocktransfers, 200);
//    }

//    public function stocktransferInvoicePrint($id)
//    {
//
//        $stocktransfer = StockTransfer::with([
//            'businessLocationFrom:id,name,city,state,country,landmark,zip_code',
//            'businessLocationTo:id,name,city,state,country,landmark,zip_code',
//            'stocktransferPerson:id,username',
//            'stockreceivePerson:id,username'
//        ])->where('id', $id)->first()->toArray();
//
//        $stocktransfer_details = StockTransferDetail::where('transfer_id', $stocktransfer['id'])
//            ->where('is_delete', '0')
//            ->with(['product:id,name', 'productVariation:id,variation_template_value_id', 'productVariation.variationTemplateValue:id,name'])
//            ->leftJoin('uom_sets', 'transfer_details.uomset_id', '=', 'uom_sets.id')
//            ->leftJoin('units', 'transfer_details.unit_id', '=', 'units.id')
//            ->get();
//
//
//        $invoiceHtml = view('App.stock.transfer.invoice', compact('stocktransfer',  'stocktransfer_details'))->render();
//
//        return response()->json(['html' => $invoiceHtml]);
//
//    }


    private function createStockTansferDetails($stockTransferDetails, $stocktransferId)
    {
//        $lotNo = StockTransferDetail::count();
        foreach ($stockTransferDetails as $detail) {
//            $expired_date = date('Y-m-d', strtotime($detail['expired_date']));
            StockTransferDetail::create([
                'transfer_id' => $stocktransferId,
                'product_id' => $detail['product_id'],
                'variation_id' => $detail['variation_id'],
                'lot_no' => $detail['lot_no'],
//                'expired_date' => $expired_date,
                'uomset_id' => $detail['uomset_id'],
                'unit_id' => $detail['unit_id'],
                'quantity' => $detail['quantity'],
                'purchase_price' => $detail['purchase_price'],
                'remark' => $detail['remark'],
                'created_at' => now(),
                'created_by' => Auth::id(),
                'updated_at' => now(),
                'updated_by' => Auth::id()
            ]);
        }

    }

    private function getStockTransferData($request)
    {
        return [
            'transfered_at' => $request->transfered_at,
            'transfered_person' => $request->transfered_person,
            'received_person' => $request->received_person,
            'status' => $request->status,
            'transfer_expense' => $request->transfer_expense,
            'from_location' => $request->from_location,
            'to_location' => $request->to_location,
            'updated_by' => Auth::id(),
            'updated_at' => now(),
        ];
    }


    public function generateVoucherNumber($prefix)
    {
        $lastStockTransferId = StockTransfer::orderBy('id', 'DESC')->select('id')->first()->id ?? 0;

        $voucherNumber =  stockTransferVoucher($lastStockTransferId);

        return $voucherNumber;
    }

    public function getProduct(Request $request)
    {
        $business_location_id = $request->data['business_location_id'];
        $q = $request->data['query'];

//        $business_location_id = $request->data['business_location_id'];
//        $q = $request->data['query'];
        $variation_id=$request->data['variation_id'] ?? null;

        $products = Product::select('id', 'name', 'product_code', 'sku', 'product_type', 'uom_id', 'purchase_uom_id')
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
                $lot_serial_nos=[];
                foreach ($product->stock as $stock) {
                    $no = $stock['lot_serial_no'];
                    $lot_id = $stock['id'];
                    $lot_serial_nos[] = [ 'id' => $lot_id,'no' => $no];
                }
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'product_code' => $product->product_code,
                    'sku' => $product->sku,
                    'product_type' => $product->product_type,
                    'uom_id' => $product->uom_id,
                    'uom'=>$product->uom,
                    'purchase_uom_id' => $product->purchase_uom_id,
                    'product_variations' => $product->product_type == 'single' ? $product->productVariations->toArray()[0] : $product->productVariations->toArray(),
                    'total_current_stock_qty' => $product->stock_sum_current_quantity ?? 0,
                    'lot_serial_nos' => $lot_serial_nos,
                    'stock' => $product->stock->toArray(),
                ];
            });
        foreach ($products as $product) {
            if ($product['product_type'] == 'variable') {
                $product_variation = $product['product_variations'];
                foreach ($product_variation as $variation) {
                    $lot_serial_nos = [];
                    $reference_uom_id = [];
                    $total_current_stock_qty = 0;
                    $stocks = array_filter($product['stock'], function ($s) use ($variation) {
                        return $s['variation_id'] == $variation['id'] && $s['current_quantity'] > 0;
                    });
                    foreach ($stocks as $stock) {
                        $total_current_stock_qty += $stock['current_quantity'];
                        $no = $stock['lot_serial_no'];
                        $lot_id=$stock['id'];
                        $lot_serial_nos[] = ['no'=>$no,'id'=> $lot_id];
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
                        'lot_serial_nos'=> $product['lot_serial_nos'],
                        'variation_id' => $variation['id'],
                        'product_type' => 'sub_variable',
                        'variation_name' => $variation['variation_template_value']['name'],
                    ];
                    $products[] = $variation_product;
                }
            }
        }
        return response()->json($products, 200);

//        $products = Product::select('id', 'name', 'product_code', 'sku', 'product_type')
//            ->where('name', 'like', '%' . $q . '%')
//            ->orWhere('sku', 'like', '%' . $q . '%')
//            ->with([
//                'productVariations' => function ($query) {
//                    $query->select('id', 'product_id', 'variation_template_value_id', 'default_purchase_price', 'default_selling_price')
//                        ->with(['variationTemplateValue' => function ($query) {
//                            $query->select('id', 'name');
//                        }]);
//                },
//                'stock' => function ($query) use ($business_location_id) {
//                    $query->where('current_quantity', '>', 0)
//                        ->with(['uom' => function ($query) {
//                            $query->select('id', 'uomset_name')->with('units');
//                        }])
//                        ->where('business_location_id', $business_location_id);
//                }
//
//            ])
//            ->withSum(['stock' => function ($q) use ($business_location_id) {
//                $q->where('business_location_id', $business_location_id);
//            }], 'current_quantity')
//            ->get()
//            ->map(function ($product) {
//                $smallest_units_id = $product->stock->pluck('smallest_unit_id')->last();
//                $smallest_unit = Unit::where('id', $smallest_units_id)->select('name')->first();
//                $lotNos = $product->stock->pluck('lot_no')->toArray();
//                return [
//                    'id' => $product->id,
//                    'name' => $product->name,
//                    'product_code' => $product->product_code,
//                    'sku' => $product->sku,
//                    'product_type' => $product->product_type,
//                    'product_variations' => $product->product_type == 'single' ? $product->productVariations->toArray()[0] : $product->productVariations->toArray(),
//                    'smallest_unit' => $smallest_unit->name ?? '',
//                    'samllest_units_id' => $smallest_units_id,
//                    'total_current_stock_qty' => $product->stock_sum_current_quantity ?? 0,
//                    'lot_nos' => $lotNos,
//                    'stock' => $product->stock->toArray(),
//                    // 'uom_set'=>$product->stock[count($product->stock)-1]->uomSet->toArray(),
//                ];
//            });
//        foreach ($products as $product) {
//            if ($product['product_type'] == 'variable') {
//                $product_variation = $product['product_variations'];
//                foreach ($product_variation as $variation) {
//                    $lot_nos = [];
//                    $total_current_stock_qty = 0;
//                    $stocks = array_filter($product['stock'], function ($s) use ($variation) {
//                        return $s['variation_id'] == $variation['id'] && $s['current_quantity'] > 0;
//                    });
//                    foreach ($stocks as $stock) {
//                        $total_current_stock_qty += $stock['current_quantity'];
//                        $no = $stock['lot_no'];
//                        $lot_nos[] = $no;
//                    }
//                    $variation_product = [
//                        'id' => $product['id'],
//                        'name' => $product['name'],
//                        'sku' => $product['sku'],
//                        'smallest_unit' => $product['smallest_unit'],
//                        'samllest_units_id' => $product['samllest_units_id'],
//                        'lot_nos' => $lot_nos,
//                        'total_current_stock_qty' => $total_current_stock_qty,
//                        'stock' => $stocks,
//                        'product_variations' => $variation,
//                        // 'uom_set'=>$product['uom_set'],
//                        'variation_id' => $variation['id'],
//                        'product_type' => 'sub_variable',
//                        'variation_name' => $variation['variation_template_value']['name'],
//                    ];
//                    $products[] = $variation_product;
//                }
//            }
//        }
//        return response()->json($products, 200);
    }

    public function editGetProduct(Request $request)
    {
        $business_location_id = $request->data['business_location_id'];
        $q = $request->data['query'];

        $products = Product::select('id', 'name', 'product_code', 'sku', 'product_type')
            ->where('id', $q)
            ->with([
                'productVariations' => function ($query) {
                    $query->select('id', 'product_id', 'variation_template_value_id', 'default_purchase_price', 'default_selling_price')
                        ->with(['variationTemplateValue' => function ($query) {
                            $query->select('id', 'name');
                        }]);
                },
                'stock' => function ($query) use ($business_location_id) {
                    $query->where('current_quantity', '>', 0)
                        ->with(['uomSet' => function ($query) {
                            $query->select('id', 'uomset_name')->with('units');
                        }])
                        ->where('business_location_id', $business_location_id);
                }

            ])
            ->withSum(['stock' => function ($q) use ($business_location_id) {
                $q->where('business_location_id', $business_location_id);
            }], 'current_quantity')
            ->get()
            ->map(function ($product) {
                $smallest_units_id = $product->stock->pluck('smallest_unit_id')->last();
                $smallest_unit = Unit::where('id', $smallest_units_id)->select('name')->first();
                $lotNos = $product->stock->pluck('lot_no')->toArray();
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'product_code' => $product->product_code,
                    'sku' => $product->sku,
                    'product_type' => $product->product_type,
                    'product_variations' => $product->product_type == 'single' ? $product->productVariations->toArray()[0] : $product->productVariations->toArray(),
                    'smallest_unit' => $smallest_unit->name ?? '',
                    'samllest_units_id' => $smallest_units_id,
                    'total_current_stock_qty' => $product->stock_sum_current_quantity ?? 0,
                    'lot_nos' => $lotNos,
                    'stock' => $product->stock->toArray(),
                    // 'uom_set'=>$product->stock[count($product->stock)-1]->uomSet->toArray(),
                ];
            });
        foreach ($products as $product) {
            if ($product['product_type'] == 'variable') {
                $product_variation = $product['product_variations'];
                foreach ($product_variation as $variation) {
                    $lot_nos = [];
                    $total_current_stock_qty = 0;
                    $stocks = array_filter($product['stock'], function ($s) use ($variation) {
                        return $s['variation_id'] == $variation['id'] && $s['current_quantity'] > 0;
                    });
                    foreach ($stocks as $stock) {
                        $total_current_stock_qty += $stock['current_quantity'];
                        $no = $stock['lot_no'];
                        $lot_nos[] = $no;
                    }
                    $variation_product = [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'sku' => $product['sku'],
                        'smallest_unit' => $product['smallest_unit'],
                        'samllest_units_id' => $product['samllest_units_id'],
                        'lot_nos' => $lot_nos,
                        'total_current_stock_qty' => $total_current_stock_qty,
                        'stock' => $stocks,
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
                $q->with('units')->select('id', 'uomset_name');
            }, 'smallest_unit' => function ($q) {
                $q->select('id', 'name');
            }])
            ->get()->first();
        return response()->json($data, 200);
    }

    public function getCurrentQtyOnUnit(Request $request)
    {
        $new_unit_id = $request->unit_id;
        $lot_no = $request->lot_no;
        $stock_data = CurrentStockBalance::where('lot_no', $lot_no)->select('smallest_unit_id', 'uomset_id', 'current_quantity')->get()->first()->toArray();
        $result = UomHelper::convertQtyByUnit($stock_data['smallest_unit_id'], $new_unit_id, $stock_data['uomset_id'], $stock_data['current_quantity']);
        return response()->json($result, 200);
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


}
