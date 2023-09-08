<?php

namespace Modules\StockInOut\Http\Controllers;

use App\Models\lotSerialDetails;
use App\Models\Contact\Contact;
use App\Models\purchases\purchase_details;
use App\Models\purchases\purchases;
use App\Models\sale\sale_details;
use App\Models\sale\sales;
use App\Models\settings\businessSettings;
use App\Models\stock_history;
use Exception;
use App\Helpers\UomHelper;
use App\Models\Product\UOM;
use App\Models\BusinessUser;
//use App\Models\Product\Unit;
use Illuminate\Http\Request;
//use App\Models\Stock\Stockin;
//use App\Models\Product\UOMSet;

use Illuminate\Support\Carbon;
use App\Models\Product\Product;
use Illuminate\Support\Facades\DB;
use App\Models\CurrentStockBalance;
//use App\Models\Stock\StockinDetail;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use App\Models\Product\UOMSellingprice;
use App\Models\settings\businessLocation;
use Modules\StockInOut\Entities\Stockin;
use Modules\StockInOut\Entities\Stockout;
use Modules\StockInOut\Entities\StockoutDetail;
use Modules\StockInOut\Http\Requests\StoreStockOutRequest;
use Modules\StockInOut\Http\Requests\UpdateStockOutRequest;

class StockOutController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive', 'appType', 'moduleName:StockInOut']);
        $this->middleware('canView:stockout')->only(['index', 'show']);
        $this->middleware('canCreate:stockout')->only(['create', 'store']);
        $this->middleware('canUpdate:stockout')->only(['edit', 'update']);
        $this->middleware('canDelete:stockout')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stockouts = Stockout::with('businessLocation')->get();

        $locations = businessLocation::select('id', 'name')->get();
        $stockouts_person = BusinessUser::select('id', 'username')->get();

        return view('stockinout::out.index', [
            'stockouts' => $stockouts,
            'locations' => $locations,
            'stockoutsperson' => $stockouts_person,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function outgoing()
    {

        $locations = businessLocation::select('id', 'name')->get();
        $stockouts_person = BusinessUser::select('id', 'username')->get();
        $stockouts = Stockin::with([
            'businessLocation:id,name',
            'stockinPerson:id,username'
        ])->get();


        $outgoingLists = sales::with(['businessLocation:id,name','created_by', 'sale_details'])
            ->whereIn('status', ['order', 'partial'])
            ->get()
            ->map(function ($sale) {
                $sale->location_name = $sale->businessLocation->name;
                $sale->total_received_quantity = $sale->sale_details->sum('delivered_quantity');
                $sale->total_quantity = $sale->sale_details->sum('quantity');
                return $sale;
            });


        return view('stockinout::out.outgoing', [
            'locations' => $locations,
            'stockouts' => $stockouts,
            'stockoutsperson' => $stockouts_person,
            'outgoingLists' => $outgoingLists,
        ]);
    }

    public function deliveredProduct(string $id)
    {
        $stockin_persons = BusinessUser::with('personal_info')->get();
        $locations = businessLocation::all();
        $dispensedData = sales::with(['customer:id,first_name', 'businessLocation:id,name'])
            ->where('id', $id)
            ->first();
//return $dispensedData;

        return view('stockinout::out.deliveredProduct', [
            'stockin_persons' => $stockin_persons,
            'locations' => $locations,
            'dispensed_data' => $dispensedData
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $stockout_persons = BusinessUser::with('personal_info')->get();
        $locations = businessLocation::all();
        $contact_id_array = sales::whereIn('status', ['order', 'partial'])
            ->select('contact_id')
            ->distinct()
            ->pluck('contact_id');
        $customers = Contact::whereIn('id', $contact_id_array)->select('id', 'first_name')->get();


        return view('stockinout::out.add', [
            'stockout_persons' => $stockout_persons,
            'locations' => $locations,
            'customers' => $customers,
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStockOutRequest $request)
    {
//        return $request;
        $validatedData = $request->validate([
            'customers' => 'required',
            'sale_voucher_no' => 'required',
            'business_location_id' => 'required|exists:business_locations,id',
            'stockout_date' => 'required',
            'stockout_person' => 'required|exists:business_users,id'
        ], [
            'required' => "The sale voucher is required.",
            'business_location_id.required' => 'The business location is required.',
            'business_location_id.exists' => 'The selected business location is invalid.',
            'stockin_date.required' => 'The stockout date is required.',
            'stockin_person.required' => 'The stockout person is required.',
            'stockin_person.exists' => 'The selected stockout person is invalid.'
        ]);

        DB::transaction(function () use ($request) {
            $settings =  businessSettings::all()->first();
            $stockout_details = $request->stockout_details;
            $stockout_date = date('Y-m-d', strtotime($request->stockout_date));
            $created_by = Auth::user()->id;
            $prefix = 'SO';
            $voucherNumber = $this->generateVoucherNumber($prefix);


            $stockout = Stockout::create([
                'business_location_id' => $request->business_location_id,
                'stockout_voucher_no' => $voucherNumber,
                'stockout_date' => $stockout_date,
                'stockout_person' => $request->stockout_person,
                'note' => $request->note,
                'created_at' => now(),
                'created_by' => $created_by,
            ]);


            // insert repeat data into the stock_detail table
            foreach ($stockout_details as $stockout_detail) {
                $stockoutDetail = StockoutDetail::create([
                    'stockout_id' => $stockout->id,
                    'product_id' => $stockout_detail['product_id'],
                    'variation_id' => $stockout_detail['variation_id'],
                    'transaction_type' => 'sale',
                    'transaction_detail_id' => $stockout_detail['sale_detail_id'],
                    'uom_id' => $stockout_detail['uom_id'],
                    'quantity' => $stockout_detail['out_quantity'],
                    'remark' => $stockout_detail['remark'],
                    'created_at' => now(),
                    'created_by' => $created_by,
                ]);

                $currentStockBalances = CurrentStockBalance::where('business_location_id', $request->business_location_id)
                    ->where('product_id', $stockout_detail['product_id'])
                    ->where('variation_id', $stockout_detail['variation_id'])
                    ->where('current_quantity', '>', 0)
                    ->when($settings->accounting_method == 'fifo', function ($query) {
                        return $query->orderBy('id');
                    }, function ($query) {
                        return $query->orderByDesc('id');
                    })
                    ->get();

                $referencUomInfo=UomHelper::getReferenceUomInfoByCurrentUnitQty( $stockout_detail['out_quantity'],$stockout_detail['uom_id']);
                $qtyToRemove = $referencUomInfo['qtyByReferenceUom'];// 12

                foreach ($currentStockBalances as $stock) {
                    $stockQty = $stock->current_quantity; //6

                    $stock_history_data = [
                        'business_location_id' => $stock['business_location_id'],
                        'product_id' => $stock['product_id'],
                        'variation_id' => $stock['variation_id'],
                        'lot_serial_no' => $stock['lot_serial_no'],
                        'expired_date' => $stock['expired_date'],
                        'transaction_type' => 'stock_out',
                        'transaction_details_id' => $stockoutDetail->id,
                        'increase_qty' => 0,
                        'ref_uom_id' => $stock['ref_uom_id'],
                    ];

                    if ($qtyToRemove > $stockQty) { //12>6
                        $qtyToRemove -= $stockQty;

                        CurrentStockBalance::where('id', $stock->id)->update([
                            'current_quantity' => 0,
                        ]);

                        $stock_history_data['decrease_qty'] = $stockQty;
                        stock_history::create($stock_history_data);

                        lotSerialDetails::create([
                            'transaction_detail_id' => $stockoutDetail->id,
                            'transaction_type' => 'stock_out',
                            'current_stock_balance_id' => $stock['id'],
                            'uom_id' => $stock['ref_uom_id'],
                            'uom_quantity' => $stockQty
                        ]);
                    } else {
                        $leftStockQty = $stockQty - $qtyToRemove;
                        $stock_history_data['decrease_qty'] = $qtyToRemove;
                        $qtyToRemove = 0;

                        CurrentStockBalance::where('id', $stock->id)->update([
                            'current_quantity' => $leftStockQty,
                        ]);

                        stock_history::create($stock_history_data);

                        lotSerialDetails::create([
                            'transaction_detail_id' => $stockoutDetail->id,
                            'transaction_type' => 'stock_out',
                            'current_stock_balance_id' => $stock['id'],
                            'uom_id' => $stock['ref_uom_id'],
                            'uom_quantity' => $stockQty-$leftStockQty
                        ]);

                        break;
                    }
                }

                $calDeliveredQty = $stockout_detail['out_quantity'] + $stockout_detail['delivered_quantity'];
                $saleDetailsToUpdate[$stockout_detail['sale_detail_id']] = $calDeliveredQty;
                $saleToUpdate[$stockout_detail['sale_id']] = $calDeliveredQty;
            }



            foreach ($saleDetailsToUpdate as $saleId => $outQuantity) {
                sale_details::where('id', $saleId)->update(['delivered_quantity' => $outQuantity]);
            }

            // Fetch purchases with partial or order status
            $salesToUpdate = sales::whereIn('id', array_keys($saleToUpdate))
                ->whereHas('sale_details', function ($query) {
                    $query->where('delivered_quantity', '<>', 'quantity');
                })
                ->get();

            foreach ($salesToUpdate as $sale) {
                $details = $sale->sale_details;

                // Check received qty in all purchase details and update status
                $completed = $details->every(function ($detail) {
                    return $detail->delivered_quantity === $detail->quantity;
                });


                $sale->status = $completed ? 'delivered' : 'partial';
                $sale->save();
            }
        });


        $route = $request->deliver_product == 'deliver_form' ? 'stock-out.outgoing.index' : 'stock-out.index';
        return redirect(route($route))->with(['success' => 'Stockin created successfully']);


        return redirect(route('stock-out.index'))->with(['success' => 'stockout created successfully']);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $stockout = Stockout::with('stockoutdetails')->findOrFail($id);
        $stockin_person = BusinessUser::findOrFail($stockout->stockout_person)->username;
        $location = businessLocation::findOrFail($stockout->business_location_id)->name;

        $stockout_details = StockoutDetail::where('stockout_id', $id)
            ->with(['product:id,name,product_type', 'uom:id,name,short_name', 'productVariation' => function($query) {
                $query->select('id', 'variation_template_value_id')
                    ->with('variationTemplateValue:id,name');
            }])
            ->get();


        return view('stockinout::out.view', [
            'stockout' => $stockout,
            'stockout_person' => $stockin_person,
            'location' => $location,
            'stockout_details' => $stockout_details,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $stockout_persons = BusinessUser::with('personal_info')->get();
        $locations = businessLocation::all();

        $stockout = Stockout::with('stockoutdetails')->findOrFail($id);
        $stockout_details = StockoutDetail::where('stockout_id', $id)->get();




//        $contact_id_array = sales::whereIn('status', ['order', 'partial'])
//            ->select('contact_id')
//            ->distinct()
//            ->pluck('contact_id');
//        $customers = Contact::whereIn('id', $contact_id_array)->select('id', 'first_name')->get();

        $contactIds = [];
        $saleIds = [];

        foreach ($stockout_details as $detail){
            $sale_detail = sale_details::with('sale')->findOrFail($detail->transaction_detail_id);

            $detail->sales_id = $sale_detail->sales_id;


            if ($sale_detail) {
                $contact_id = $sale_detail->sale->contact_id;
                $sale_id = $sale_detail->sale->id;

                if (!in_array($contact_id, $contactIds)) {
                    $contactIds[] = $contact_id;
                }

                if (!in_array($sale_id, $saleIds)) {
                    $saleIds[] = $sale_id;
                }
            }
        }

        $uniqueContactId = null;
        if (count($contactIds) === 1) {
            $uniqueContactId = $contactIds[0];
        }


        return view('stockinout::out.edit', [
            'stockout' => $stockout,
            'stockout_details' => $stockout_details,
            'uniqueContactId' => $uniqueContactId,
            'stockout_persons' => $stockout_persons,
            'locations' => $locations,
//            'customers' => $customers,
            'saleIds' => $saleIds,
        ]);

    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStockOutRequest $request, string $id)
    {
//return $request;

        $settings =  businessSettings::all()->first();
        $requestStockoutDetails = $request->stockout_details;

        DB::beginTransaction();
        try {
            $existingStockoutDetailIds = [];

            $existingStockoutDetails = array_filter($requestStockoutDetails, function ($detail) {
                return isset($detail['stockout_detail_id']);
            });

            $newStockoutDetails = array_filter($requestStockoutDetails, function ($detail) {
                return !isset($detail['stockout_detail_id']);
            });

            Stockout::where('id', $id)->update([
               'note' =>  $request->note
            ]);

            // Update existing rows
            foreach ($existingStockoutDetails as $detail) {
                $stockoutDetailId = $detail['stockout_detail_id'];
                $beforeEditQuantity = $detail['before_edit_quantity'];
                $outQuantity = $detail['out_quantity'];


                $stockoutDetail = StockoutDetail::where('id', $stockoutDetailId)
                    ->where('stockout_id', $id)->firstOrFail();

                $stockoutDetail->remark = $detail['remark'];
                $stockoutDetail->quantity = $detail['out_quantity'];
                $stockoutDetail->updated_at = now();
                $stockoutDetail->updated_by  = Auth::id();
                $stockoutDetail->save();

                $transactionDetailId = $stockoutDetail->transaction_detail_id;

                $saleDetail = sale_details::where('id', $transactionDetailId)
                    ->firstOrFail();

                $saleDetail->delivered_quantity -= $detail['before_edit_quantity']-$detail['out_quantity'];
                $saleDetail->save();


                if ($outQuantity > $beforeEditQuantity) {
                    $updateQty =  $outQuantity -  $beforeEditQuantity;
                    $referenceUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($updateQty, $detail['uom_id']);
                    $qtyToRemove = $referenceUomInfo['qtyByReferenceUom'];//10

                    stock_history::where('transaction_type', 'stock_out')
                        ->where('transaction_details_id', $stockoutDetailId)
                        ->increment('decrease_qty' , $qtyToRemove);

                    $currentBalances = CurrentStockBalance::where('business_location_id', $request->business_location_id)
                        ->where('product_id', $detail['product_id'])
                        ->where('variation_id', $detail['variation_id'])
                        ->where('current_quantity', '>', 0)
                        ->when($settings->accounting_method == 'fifo', function ($query) {
                            return $query->orderBy('id');
                        }, function ($query) {
                            return $query->orderByDesc('id');
                        })
                        ->get();

                    foreach ($currentBalances as $balance) {
                        $stockQty = $balance->current_quantity;

                        if ($qtyToRemove > $stockQty) {

                            $lotSerialDetail = lotSerialDetails::where('current_stock_balance_id', $balance->id)
                                ->where('transaction_detail_id', $stockoutDetailId)
                                ->where('transaction_type', 'stock_out')
                                ->first();

                            if ($lotSerialDetail) {
                                $lotSerialDetail->uom_quantity += $stockQty;
                                $lotSerialDetail->save();
                            }else {
                                lotSerialDetails::create([
                                    'transaction_detail_id' => $stockoutDetailId,
                                    'transaction_type' => 'stock_out',
                                    'current_stock_balance_id' => $balance->id,
                                    'uom_id' => $balance->ref_uom_id,
                                    'uom_quantity' => $stockQty,
                                ]);
                            }

                            $balance->update([
                                'current_quantity' => 0,
                            ]);
                            $qtyToRemove -= $stockQty;

                        } elseif ($stockQty > $qtyToRemove) {

                            $balance->update([
                                'current_quantity' => $balance->current_quantity-$qtyToRemove,
                            ]);

                            $lotSerialDetail = lotSerialDetails::where('current_stock_balance_id', $balance->id)
                                ->where('transaction_detail_id', $stockoutDetailId)
                                ->where('transaction_type', 'stock_out')
                                ->first();

                            if ($lotSerialDetail) {
                                $lotSerialDetail->uom_quantity += $qtyToRemove;
                                $lotSerialDetail->save();
                            } else {
                                lotSerialDetails::create([
                                    'transaction_detail_id' => $stockoutDetailId,
                                    'transaction_type' => 'stock_out',
                                    'current_stock_balance_id' => $balance->id,
                                    'uom_id' => $balance->ref_uom_id,
                                    'uom_quantity' => $qtyToRemove,
                                ]);
                            }

                            break;
                        }else{
                            break;
                        }
                    }


                } elseif ($beforeEditQuantity > $outQuantity) {

                    $updateQty = $beforeEditQuantity - $outQuantity;
                    $referencUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($updateQty, $detail['uom_id']);
                    $qtyToIncrease = $referencUomInfo['qtyByReferenceUom'];//0.5

                    stock_history::where('transaction_type', 'stock_out')
                        ->where('transaction_details_id', $stockoutDetailId)
                        ->decrement('decrease_qty' , $qtyToIncrease);


                    // Re-Increase
                    $lotSerialDetails = lotSerialDetails::where('transaction_detail_id', $stockoutDetailId)
                        ->where('transaction_type', 'stock_out')
                        ->when($settings->accounting_method == 'fifo', function ($query) {
                            return $query->orderByDesc('current_stock_balance_id');
                        }, function ($query) {
                            return $query->orderBy('current_stock_balance_id');
                        })
                        ->get();

                    //     ->orderByDesc('current_stock_balance_id')
                    //    ->get();


                    foreach ($lotSerialDetails as $lotSerialDetail) {
                        $stockQty = $lotSerialDetail->uom_quantity; //
                        if ($qtyToIncrease > $stockQty){//10 > 20
                            $qtyToIncrease -= $stockQty;
                            $currentStockBalance = CurrentStockBalance::where('id', $lotSerialDetail->current_stock_balance_id)->first();
                            $currentStockBalance->current_quantity += $stockQty;
                            $currentStockBalance->save();

                            $lotSerialDetail->delete();

                            if ($qtyToIncrease == 0) {
                                break;
                            }
                        }elseif($stockQty > $qtyToIncrease ){
                            $currentStockBalance = CurrentStockBalance::where('id', $lotSerialDetail->current_stock_balance_id)->first();
                            $currentStockBalance->current_quantity += $qtyToIncrease;
                            $currentStockBalance->save();

                            $lotSerialDetail->uom_quantity -= $qtyToIncrease;
                            $lotSerialDetail->save();
                            break;
                        }else{
                            break;
                        }

                    }



                }

                $existingStockoutDetailIds[] = $stockoutDetailId;

            }


            // Delete rows that were not updated
            $transactionDetails = StockoutDetail::where('stockout_id', $id)
                ->whereNotIn('id', $existingStockoutDetailIds)
                ->get();

            foreach ($transactionDetails as $transactionDetail){
                $saleDetail = sale_details::where('id', $transactionDetail->transaction_detail_id)->first();
                $saleDetail->delivered_quantity -= $transactionDetail->quantity;
                $saleDetail->save();

                $restoreLotSerialDetail = lotSerialDetails::where('transaction_detail_id', $transactionDetail->id)
                    ->where('transaction_type', 'stock_out')
                    ->first();
                $currentStockBalance = CurrentStockBalance::where('id', $restoreLotSerialDetail->current_stock_balance_id)->first();
                $currentStockBalance->current_quantity += $restoreLotSerialDetail->uom_quantity;
                $currentStockBalance->save();
                $restoreLotSerialDetail->delete();
            }


            StockoutDetail::where('stockout_id', $id)
                ->whereNotIn('id', $existingStockoutDetailIds)
                ->update(['is_delete' => true, 'deleted_by' => Auth::id()]);

            StockoutDetail::where('stockout_id', $id)
                ->whereNotIn('id', $existingStockoutDetailIds)
                ->delete();




            // Create new rows
            $saleDetailsToUpdate = [];
            foreach ($newStockoutDetails as $detail) {

                $stockoutDetail = StockoutDetail::create([
                    'stockout_id' => $id,
                    'product_id' => $detail['product_id'],
                    'variation_id' => $detail['variation_id'],
                    'transaction_type' => 'sale',
                    'transaction_detail_id' => $detail['sale_detail_id'],
                    'uom_id' => $detail['uom_id'],
                    'quantity' => $detail['out_quantity'],
                    'remark' => $detail['remark'],
                    'created_at' => now(),
                    'created_by' => Auth::id(),
                    'updated_at' => now(),
                    'updated_by' => Auth::id(),
                    'is_delete' => false,
                    'deleted_at' => null,
                    'deleted_by' => null,
                ]);



                $currentStockBalances = CurrentStockBalance::where('business_location_id', $request->business_location_id)
                    ->where('product_id', $detail['product_id'])
                    ->where('variation_id', $detail['variation_id'])
                    ->where('current_quantity', '>', 0)
                    ->when($settings->accounting_method == 'fifo', function ($query) {
                        return $query->orderBy('id');
                    }, function ($query) {
                        return $query->orderByDesc('id');
                    })
                    ->get();

                $referencUomInfo=UomHelper::getReferenceUomInfoByCurrentUnitQty( $detail['out_quantity'],$detail['uom_id']);
                $qtyToRemove = $referencUomInfo['qtyByReferenceUom'];// 12

                foreach ($currentStockBalances as $stock) {
                    $stockQty = $stock->current_quantity; //6

                    $stock_history_data = [
                        'business_location_id' => $stock['business_location_id'],
                        'product_id' => $stock['product_id'],
                        'variation_id' => $stock['variation_id'],
                        'lot_serial_no' => $stock['lot_serial_no'],
                        'expired_date' => $stock['expired_date'],
                        'transaction_type' => 'stock_out',
                        'transaction_details_id' => $stockoutDetail->id,
                        'increase_qty' => 0,
                        'ref_uom_id' => $stock['ref_uom_id'],
                    ];

                    if ($qtyToRemove > $stockQty) {
                        $qtyToRemove -= $stockQty;

                        CurrentStockBalance::where('id', $stock->id)->update([
                            'current_quantity' => 0,
                        ]);

                        $stock_history_data['decrease_qty'] = $stockQty;
                        stock_history::create($stock_history_data);

                        lotSerialDetails::create([
                            'transaction_detail_id' => $stockoutDetail->id,
                            'transaction_type' => 'stock_out',
                            'current_stock_balance_id' => $stock['id'],
                            'uom_id' => $stock['ref_uom_id'],
                            'uom_quantity' => $stockQty
                        ]);
                    } else {
                        $leftStockQty = $stockQty - $qtyToRemove;
                        $stock_history_data['decrease_qty'] = $qtyToRemove;
                        $qtyToRemove = 0;

                        CurrentStockBalance::where('id', $stock->id)->update([
                            'current_quantity' => $leftStockQty,
                        ]);

                        stock_history::create($stock_history_data);

                        lotSerialDetails::create([
                            'transaction_detail_id' => $stockoutDetail->id,
                            'transaction_type' => 'stock_out',
                            'current_stock_balance_id' => $stock['id'],
                            'uom_id' => $stock['ref_uom_id'],
                            'uom_quantity' => $stockQty-$leftStockQty
                        ]);

                        break;
                    }
                }
                $calDeliveredQty = $detail['delivered_quantity'] + $detail['out_quantity'];
                $saleDetailsToUpdate[$detail['sale_detail_id']] = $calDeliveredQty;

                $saleDetail = sale_details::where('id',$detail['sale_detail_id'])->first();
                $saleDetail->delivered_quantity = $detail['out_quantity'] + $detail['delivered_quantity'];
                $saleDetail->save();


            }



            // Fetch purchases with partial or order status
            $salesToUpdate = sales::whereIn('id', array_keys($saleDetailsToUpdate))
                ->whereHas('sale_details', function ($query) {
                    $query->where('delivered_quantity', '<>', 'quantity');
                })
                ->get();

            foreach ($salesToUpdate as $sale) {
                $details = $sale->sale_details;

                // Check received qty in all purchase details and update status
                $completed = $details->every(function ($detail) {
                    return $detail->delivered_quantity === $detail->quantity;
                });


                $sale->status = $completed ? 'delivered' : 'partial';
                $sale->save();
            }

            DB::commit();
            return redirect(route('stock-out.index'))->with(['success' => 'Stockout Updated Successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
            return redirect(route('stock-out.index'))->with(['success' => 'Failed to update rows']);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function softDelete(string $id){
        $restore = request()->query('restore');
        if ($restore == 'true') {

            $detailIds = StockoutDetail::where('stockout_id', $id)->pluck('id');

            $transactionDetails = StockoutDetail::where('stockout_id', $id)
                ->whereIn('id', $detailIds)
                ->get();

            foreach ($transactionDetails as $transactionDetail){
                $saleDetail = sale_details::where('id', $transactionDetail->transaction_detail_id)->first();
                $saleDetail->delivered_quantity -= $transactionDetail->quantity;
                $saleDetail->save();

            }

            $lotSerialDetails = lotSerialDetails::whereIn('transaction_detail_id', $detailIds)
                ->orderByDesc('current_stock_balance_id')
                ->get();

            foreach ($lotSerialDetails as $lotSerialDetail) {
                $currentStockBalance = CurrentStockBalance::where('id', $lotSerialDetail->current_stock_balance_id)->first();
                $currentStockBalance->current_quantity += $lotSerialDetail->uom_quantity;
                $currentStockBalance->save();
            }
            lotSerialDetails::whereIn('transaction_detail_id', $detailIds)->delete();


            DB::transaction(function () use ($id) {
                Stockout::where('id', $id)->update(['deleted_by' => Auth::id(), 'is_deleted' => true]);
                Stockout::where('id', $id)->delete();
                StockoutDetail::where('stockout_id', $id)->update(['deleted_by' => Auth::id(), 'is_delete' => true]);
                StockoutDetail::where('stockout_id', $id)->delete();
            });

            $data = [
                'success' => 'Successfully Deleted',
            ];

        }else{

            DB::transaction(function () use ($id) {
                Stockout::where('id', $id)->update(['deleted_by' => Auth::id(), 'is_deleted' => true]);
                Stockout::where('id', $id)->delete();
                StockoutDetail::where('stockout_id', $id)->update(['deleted_by' => Auth::id(), 'is_delete' => true]);
                StockoutDetail::where('stockout_id', $id)->delete();
            });


            $data = [
                'success' => 'Successfully Deleted',
            ];

        }

        return response()->json($data, 200);
    }

    public function filterCustomer(Request $request){
        $locationId =  $request->data;

        $customers = Contact::join('sales', 'contacts.id', '=', 'sales.contact_id')
            ->where('type', 'Customer')
            ->where('sales.business_location_id', $locationId)
            ->whereIn('sales.status', ['order', 'partial'])
            ->distinct()
            ->select('contacts.id', 'contacts.first_name')
            ->get();


        return response()->json($customers, 200);


    }

    public function filterSaleOrder(Request $request){

        $results = sales::where('contact_id', $request->data)
            ->whereIn('status', ['order', 'partial'])
            ->pluck('id', 'sales_voucher_no')->toArray();

        $response = [];
        foreach ($results as $name => $value) {
            $response[] = ['name' => $name, 'value' => $value];
        }

        return $response;
    }



    public function filterSaleOrderData(Request $request){

        $sale = sales::where('id', $request->data)
            ->with('businessLocation:id,name')
            ->select('id', 'business_location_id')
            ->first();


        $results = sale_details::with(['product:id,name,product_type', 'uom:id,name', 'productVariation' => function($query){
            $query->select('id', 'variation_template_value_id')
                ->with('variationTemplateValue:id,name');
        }])
            ->where('sales_id', $sale->id)
            ->where('is_delete', 0)
            ->get();

        $results->each(function ($result) use ($sale) {
            $result->business_location_id = $sale->business_location_id;
            $result->business_location_name = $sale->businessLocation->name;
        });

        return  $results;
    }


    public function filterList(Request $request)
    {
        $dateRange = $request->data['filter_date'];
        $dates = explode(' - ', $dateRange);

        $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[0])->startOfDay();
        $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[1])->endOfDay();


        $query = Stockout::where('is_deleted', 0)
            ->with(['businessLocation:id,name', 'stockoutPerson:id,username', 'created_by:id,username'])
            ->whereBetween('stockout_date', [$startDate, $endDate]);;

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

        if ($request->data['filter_stockoutsperosn'] != 0) {
            $query->where('stockout_person', $request->data['filter_stockoutsperosn']);
        }

        $stockouts = $query->get();

        return response()->json($stockouts, 200);
    }

    public function stockoutInvoicePrint($id)
    {

        $stockout = Stockout::with('businessLocation:id,name,city,state,country,landmark,zip_code')->where('id', $id)->first()->toArray();
        $location = $stockout['business_location'];

        $stockout_details = StockoutDetail::where('stockout_id', $stockout['id'])
            ->where('is_delete', '0')
            ->with(['product:id,name', 'productVariation:id,variation_template_value_id', 'productVariation.variationTemplateValue:id,name'])
            ->leftJoin('uom_sets', 'stockout_details.uomset_id', '=', 'uom_sets.id')
            ->leftJoin('units', 'stockout_details.unit_id', '=', 'units.id')
            ->get();


        $invoiceHtml = view('stockinout::out.invoice', compact('stockout', 'location', 'stockout_details'))->render();

        return response()->json(['html' => $invoiceHtml]);

    }


    private function createStockoutDetails($stockoutDetails, $stockoutId, $status)
    {
        foreach ($stockoutDetails as $stockoutDetail) {
            $expired_date = date('Y-m-d', strtotime($stockoutDetail['expired_date']));
            $stockoutDetail = StockoutDetail::create([
                'stockout_id' => $stockoutId,
                'product_id' => $stockoutDetail['product_id'],
                'variation_id' => $stockoutDetail['variation_id'],
                'lot_no' => $stockoutDetail['lot_no'],
                'current_stock_balance_id' => $stockoutDetail['current_stock_balance_id'],
                'expired_date' => $expired_date,
                'uomset_id' => $stockoutDetail['uomset_id'],
                'unit_id' => $stockoutDetail['unit_id'],
                'quantity' => $stockoutDetail['quantity'],
                'purchase_price' => $stockoutDetail['purchase_price'],
                'remark' => $stockoutDetail['remark'],
                'created_at' => now(),
                'created_by' => Auth::id(),
                'updated_at' => now(),
                'updated_by' => Auth::id()
            ]);


            if ($status == 'received' || $status == 'confirmed') {

                $current_stock_balance = CurrentStockBalance::where('id', $stockoutDetail->current_stock_balance_id)->first();
                $smallest_qty = UomHelper::smallestQty($stockoutDetail->uomset_id, $stockoutDetail->unit_id, $stockoutDetail->quantity);
                $updated_quantity = $current_stock_balance->current_quantity - $smallest_qty;

                // Update to Current Stock Balance
                CurrentStockBalance::where('id', $stockoutDetail->current_stock_balance_id)
                    ->update([
                        'current_quantity' => $updated_quantity,
                    ]);

            }

        }

    }

    private function getStockoutData($request)
    {
        return [
            'stockout_date' => $request->stockout_date,
            'stockout_person' => $request->stockout_person,
            'business_location_id' => $request->business_location_id,
            'status' => $request->status,
            'note' => $request->note,
            'updated_by' => Auth::id(),
            'updated_at' => now(),
        ];
    }

    public function generateVoucherNumber($prefix)
    {
        $lastStockoutId = Stockout::orderBy('id', 'DESC')->select('id')->first()->id ?? 0;

        $voucherNumber =  sprintf($prefix.'-' . '%06d', ($lastStockoutId + 1));

        return $voucherNumber;
    }



}
