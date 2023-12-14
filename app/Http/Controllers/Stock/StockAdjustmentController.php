<?php

namespace App\Http\Controllers\Stock;

use App\Helpers\UomHelper;
use App\Http\Requests\Stock\StoreStockAdjustmentRequest;
use App\Models\BusinessUser;
use App\Models\Contact\Contact;
use App\Models\CurrentStockBalance;
use App\Models\lotSerialDetails;
use App\Models\productPackagingTransactions;
use App\Models\purchases\purchases;
use App\Models\Stock\StockAdjustment;
use App\Models\Stock\StockAdjustmentDetail;
use App\Models\Stock\StockTransferDetail;
use App\Models\stock_history;
use App\Repositories\LocationRepository;
use App\Services\packaging\packagingServices;
use App\Services\Stock\StockAdjustmentServices;
use DateTime;
use Exception;
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
    private $locationRepository;
    public function __construct(
        LocationRepository $locationRepository
    )
    {
        $this->middleware(['auth', 'isActive']);
        $this->locationRepository = $locationRepository;

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
        $locations = $this->locationRepository->getforTx();
//        $locations = businessLocation::all();
        $products = Product::with('productVariations')->get();
        $setting=businessSettings::first();
        $currency=$setting->currency;

        return view('App.stock.adjustment.add', [

            'locations' => $locations,
            'products' => $products,
            'currency' => $currency,
            'setting' => $setting,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStockAdjustmentRequest $request,StockAdjustmentServices $adjustmentServices)
    {
        try {
            DB::beginTransaction();
            $adjustmentServices->create($request);
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
//        $locations = businessLocation::all();
        $locations = $this->locationRepository->getforTx();
        $setting=businessSettings::first();

        $stockAdjustment = StockAdjustment::where('id', $id)->get()->first();
        $business_location_id=$stockAdjustment->business_location;
        $stock_adjustment_details = StockAdjustmentDetail::with([
            'packagingTx',
            'productVariation' => function ($q) {
            $q->select('id', 'product_id', 'variation_template_value_id', 'default_selling_price')
                ->with([
                    'packaging' => function($q){ $q->with('uom'); },
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
    public function update(Request $request, string $id, StockAdjustmentServices $stockAdjustmentServices)
    {
        try {
            DB::beginTransaction();
                $stockAdjustmentServices->update($id, $request);
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

            stock_history::whereIn('transaction_details_id', $adjustmentDetails)
                ->where('transaction_type', 'adjustment')
                ->delete();

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
                if($adjustment->status == 'prepared'){
                    $html= "<span class='badge badge-light-warning'> $adjustment->status </span>";
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
                   if ($adjustment->status == 'prepared'){
                       $html .= '      <a href="'.route('stock-adjustment.edit', $adjustment->id).'" class="dropdown-item p-2  px-3 view_detail  text-gray-600 rounded-2">Edit</a> ';
                   }
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

        $voucherNumber = stockAdjustmentVoucherNo($lastStockAdjustmentId);

        return $voucherNumber;
    }
}
