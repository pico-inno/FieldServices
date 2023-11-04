<?php

namespace App\Http\Controllers;

use Exception;
use App\Helpers\UomHelper;
use App\Models\Product\UOM;
use App\Models\BusinessUser;
use App\Models\Product\Unit;
use Illuminate\Http\Request;
use App\Models\openingStocks;
use App\Models\stock_history;
use App\Models\Product\UOMSet;
use Illuminate\Support\Carbon;
use App\Models\Contact\Contact;
use App\Models\Product\Product;
use Illuminate\Support\Facades\DB;
use App\Models\CurrentStockBalance;
use App\Models\locationAddress;
use App\Models\openingStockDetails;
use App\Models\productPackagingTransactions;
use App\Models\purchases\purchases;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\settings\businessLocation;
use Illuminate\Support\Facades\Validator;
use App\Models\purchases\purchase_details;
use App\Services\packaging\packagingServices;

class openingStockController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }
    public function index()
    {
        return view('App.openingStock.index');
    }
    public function OpeningStockData()
    {

        $openingStocks = openingStocks::where('is_delete', 0)
        ->with('business_location_id', 'businessLocation','opening_person')
        ->OrderBy('id', 'desc')
        ->get();
        return DataTables::of($openingStocks)
            ->addColumn('checkbox', function ($stock) {
                return
                    '
                    <div class="form-check form-check-sm form-check-custom ">
                        <input class="form-check-input" type="checkbox" data-checked="delete" value="'. $stock->id . ' " />
                    </div>
                ';
            })
            ->editColumn('location',function($stock){
                return businessLocationName($stock->businessLocation);
            })
            ->addColumn('action', function ($stock) {
                $route=route('view_opening_stock',$stock->id);
                $invoiceRoute=route('printInvoice', $stock->id);
                $editBtn = $stock->status != "confirmed" ? '<a href=" ' . route('openingStockEdit', $stock->id) . ' " class="dropdown-item p-2 edit-unit bg-active-primary text-primary" >Edit</a>' : '';
                return '
                    <div class="dropdown text-center">
                        <button class="btn btn-sm btn-light btn-primary fw-semibold fs-7  dropdown-toggle " type="button" id="stockDropDown" data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                        </button>
                        <div class="z-3">
                        <ul class="dropdown-menu z-10 p-5 " aria-labelledby="stockDropDown" role="menu">
                            <a class="dropdown-item p-2  px-3 view_detail"   type="button" data-href="'.$route. '">
                                view
                            </a>
                            <a class="dropdown-item p-2  cursor-pointer bg-active-danger text-info print-invoice"   data-id="' . $stock->id . '"  data-href="' . $invoiceRoute . '">print</a>
                            '. $editBtn.'
                            <a class="dropdown-item p-2  cursor-pointer bg-active-danger text-danger"  data-id="'. $stock->id .'" data-kt-stock-table="delete_row" data-kt-openingStock-table="delete_row">Delete</a>
                        </ul></div>
                    </div>
                ';
            })
            ->rawColumns(['action', 'checkbox'])
            ->make(true);
    }

    public function add()
    {
        $stockin_persons = BusinessUser::with('personal_info')->get();
        $locations = businessLocation::all();


        return view('App.openingStock.add', [
            'stockin_persons' => $stockin_persons,
            'locations' => $locations,
        ]);
    }
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $opening_stock_details=$request->opening_stock_details;
            Validator::make($request->toArray(),[
                'opening_stock_details'=>'required',
                'business_location_id' => 'required',
                'opening_date' => 'required',
            ],[
                'business_location_id.required'=>'Bussiness Location is required!',
                'opening_date.required' => 'Opening Date is required!',
            ])->validate();
            if($opening_stock_details){
                $packagingService=new packagingServices();

                $opening_stock_data=$this->dataForOpeningStock($request);
                $opening_stock_data['created_by']=Auth::user()->id;
                $opening_stock_data['updated_at']=null;
                $created_opening_data=openingStocks::create($opening_stock_data) ;
                    foreach ($opening_stock_details as $detail) {
                        $data=$this->dataForOpeningStockDetails($detail);
                        $data['opening_stock_id']= $created_opening_data->id;
                        $data['opening_date']= now();
                        $data['created_by']=Auth::user()->id;
                        $data['updated_at']= null;
                        // dd($data);
                        $openingStockDetailData=openingStockDetails::create($data);
                        $packagingService->packagingForTx($detail, $openingStockDetailData['id'],'opening_stock');
                        $current_stock_data = $this->currentStockBalanceData($openingStockDetailData, $created_opening_data, 'opening_stock');
                        CurrentStockBalance::create($current_stock_data);
                    }
                DB::commit();
                return redirect()->route('opening_stock_list')->with(['success' => ' Opening Stock Successfully created']);
            }else{
                return back()->with(['warning' => 'Opening Stock Detail is required']);
            }

        } catch (\Throwable $e) {
            throw($e);
            DB::rollBack();
            return back()->with(['warning'=>'Something wrong while creating Opening Stock']);
        }

    }


    public function edit($id)
    {
        $locations = businessLocation::all();
        $openingStock = openingStocks::where('id', $id)->first();
        $openingStockDetails = openingStockDetails::with([
            'productVariation' => function ($q) {
                $q->select('id', 'product_id', 'variation_template_value_id', 'default_purchase_price', 'profit_percent', 'default_selling_price')
                    ->with(
                        [
                            'variationTemplateValue' => function ($q) {
                                $q->select('id', 'name');
                            },'packaging'
                        ]
                    );
            }, 'product', 'packagingTx'
        ])->where('opening_stock_id', $id)->where('is_delete', 0)->get();
        // dd($openingStockDetails->toArray(),productPackagingTransactions::get()->toArray);
        return view('App.openingStock.edit', compact('openingStock', 'locations',  'openingStockDetails'));
    }
    public function update($id,Request $request)
    {
        $request_opening_stock_details = $request->opening_stock_details;
        $opening_stock_data = [
            'business_location_id' => $request->business_location_id,
            'opening_date' =>date('Y-m-d', strtotime($request->opening_date)),
            'note' => $request->note,
            'updated_by' => Auth::user()->id,
            'updated_at' => Carbon::now(),
        ];
        $opening_stock_data['updated_by'] = Auth::user()->id;
        $opening_stock_data['updated_at'] = Carbon::now();
        DB::beginTransaction();
        try {
            // update  purchase data
            $selectOpeningStock = openingStocks::where('id', $id);
            $stock = $selectOpeningStock->first();
            $selectOpeningStock->update($opening_stock_data);
            if ($request_opening_stock_details) {

                //get old opening_stock_detail_id
                $old_opening_stock_details = array_filter($request_opening_stock_details, function ($item) {
                    return isset($item['opening_stock_detail_id']);
                });

                // get old opening_stock_detail_id ids from client [1,2,]
                $old_opening_stock_details_ids = array_column($old_opening_stock_details, 'opening_stock_detail_id');
                // update purchase detail's data and related current stock
                foreach ($old_opening_stock_details as $osd) {
                    $opening_stock_detail_id = $osd['opening_stock_detail_id'];

                    $updateOpeningStockData=$this->dataForOpeningStockDetails($osd);
                    $osd['updated_by'] = Auth::user()->id;

                    $opening_stock_detail = openingStockDetails::where('id', $opening_stock_detail_id)->where('is_delete', 0)->first();

                    $packagingService = new packagingServices();
                    $packagingService->updatePackagingForTx($osd, $opening_stock_detail_id, 'opening_stock');
                    $opening_stock_detail->update($updateOpeningStockData);
                    // dd($opening_stock_detail->toArray());
                    $referencteUom= UomHelper::getReferenceUomInfoByCurrentUnitQty($updateOpeningStockData['quantity'],$updateOpeningStockData['uom_id']);
                    $requestQty =$referencteUom['qtyByReferenceUom'];
                    $refUnitId = $referencteUom['referenceUomId'];

                    $currentStock = CurrentStockBalance::where('transaction_detail_id', $opening_stock_detail_id)->where('transaction_type', 'opening_stock');
                    $refUomQtyFromStock = $currentStock->get()->first()->ref_uom_quantity;
                    $current_qty_from_db =   $currentStock->get()->first()->current_quantity;
                    $diff_qty = $requestQty - $refUomQtyFromStock;
                    $current_qty = $current_qty_from_db + $diff_qty;



                    stock_history::where('transaction_details_id', $opening_stock_detail_id)->where('transaction_type', 'opening_stock')->update([
                        'increase_qty' => $requestQty,
                        "business_location_id" => $request->business_location_id,
                    ]);
                    $currentStock->update([
                        "ref_uom_id" => $refUnitId,
                        "business_location_id" => $request->business_location_id,
                        "ref_uom_quantity" => $requestQty,
                        "ref_uom_price" => $updateOpeningStockData['ref_uom_price'],
                        "current_quantity" => $current_qty >= 0 ? $current_qty : '0',
                    ]);
                }


                //get added purchase_details_ids from database
                $fetch_opening_stock_details = openingStockDetails::where('opening_stock_id', $id)->where('is_delete', 0)->select('id')->get()->toArray();
                $get_fetched_opening_stock_details_id = array_column($fetch_opening_stock_details, 'id');

                //to remove edited purchase_detais that are already created
                $old_opening_stock_details_id_for_delete = array_diff($get_fetched_opening_stock_details_id, $old_opening_stock_details_ids); //for delete row
                foreach ($old_opening_stock_details_id_for_delete as $os_id) {
                    openingStockDetails::where('id', $os_id)->update([
                        'is_delete' => 1,
                        'deleted_at' => now(),
                        'deleted_by' => Auth::user()->id,
                    ]);
                    CurrentStockBalance::where('transaction_detail_id', $os_id)->where('transaction_type', 'opening_stock')->delete();
                }

                //to create purchase details
                $new_opening_stock_details = array_filter($request_opening_stock_details, function ($item) {
                    return !isset($item['opening_stock_detail_id']);
                });
                if (count($new_opening_stock_details) > 0) {
                    foreach ($new_opening_stock_details as $detail) {
                        $data=$this->dataForOpeningStockDetails($detail);
                        $data['created_by']=Auth::user()->id;
                        $data['updated_at']= null;
                        $data['opening_stock_id']= $id;
                        $createdOpeningStock=openingStockDetails::create($data);

                        $packagingService = new packagingServices();
                        $packagingService->packagingForTx($detail, $createdOpeningStock['id'], 'opening_stock');
                        $updatedStock = $selectOpeningStock->first();
                        $data = $this->currentStockBalanceData($createdOpeningStock, $updatedStock, 'opening_stock');
                        CurrentStockBalance::create($data);
                    }
                }
            } else {
                $fetch_opening_stock_details = openingStockDetails::where('opening_stock_id', $id)->where('is_delete', 0)->select('id')->get();
                foreach ($fetch_opening_stock_details as $p) {
                    CurrentStockBalance::where('transaction_detail_id', $p->id)->where('transaction_type', 'opening_stock')->delete();
                }
                openingStockDetails::where('opening_stock_id', $id)->update([
                    'is_delete' => 1,
                    'deleted_at' => now(),
                    'deleted_by' => Auth::user()->id,
                ]);
            }
            // dd('herde');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect()->route('opening_stock_list')->with(['success' => 'Successfully Updated Purchase']);
    }

    public function dataForOpeningStock($request){
        $opening_stock_count=openingStocks::count();
        return [
            'business_location_id'=>$request->business_location_id,
            'opening_stock_voucher_no'=>sprintf('OS-'.'%06d', ($opening_stock_count + 1)),
            'opening_date' => $request->opening_date,
            'opening_person'=>Auth::user()->id,
            'total_opening_amount'=>$request->total_opening_amount,
            'note' => $request->note,
        ];
    }
    public function dataForOpeningStockDetails($detail){
        $referencteUom = UomHelper::getReferenceUomInfoByCurrentUnitQty($detail['quantity'], $detail['uom_id']);
        return [
            'product_id'=> $detail['product_id'],
            'variation_id'=>$detail['variation_id'],
            'uom_id' => $detail['uom_id'],
            'quantity' => $detail['quantity'],
            'uom_price' => $detail['uom_price'],
            'subtotal' => $detail['subtotal'],
            'ref_uom_id' =>  $referencteUom['referenceUomId'],
            'ref_uom_price' =>$detail['uom_price'] ?? 0 /$referencteUom['qtyByReferenceUom'] ?? 1,
            'remark' => $detail['remark']
        ];
    }

    public function view($id)
    {
        $openingStock = openingStocks::with('business_location_id', 'opening_person', 'confirm_by', 'updated_by','created_by')->where('id', $id)->first()->toArray();

        $location = businessLocation::where('id', $openingStock['business_location_id'])->first();

        $openingStockDetails = $this->openingStockDetail($id);
        $address=locationAddress::where('id',$location->id)->first();
        return view('App.openingStock.view', compact(
            'openingStock',
            'location',
            'openingStockDetails',
            'address'
        ));
    }

    public function printInvoice($id)
    {
        $openingStocks = openingStocks::with('business_location_id', 'opening_person', 'confirm_by', 'updated_by')->where('id', $id)->first()->toArray();

        $location = $openingStocks['business_location_id'];

        $openingStockDetail = $this->openingStockDetail($id);

        $invoiceHtml = view('App.openingStock.invoice.invoice', compact(
            'openingStocks',
            'location',
            'openingStockDetail'
        ))->render();
        return response()->json(['html' => $invoiceHtml]);
    }

    public function openingStockDetail($id)
    {
        return openingStockDetails::with(['productVariation' => function ($q) {
            $q->select('id', 'product_id', 'variation_template_value_id')
                ->with([
                    'product' => function ($q) {
                        $q->select('id', 'name', 'product_type');
                    },
                    'variationTemplateValue' => function ($q) {
                        $q->select('id', 'name');
                    }
                ]);
        },'product'])->where('opening_stock_id', $id)->where('is_delete', 0)->get();

    }

    public function import()
    {
        $locations = businessLocation::all();
        return view('App.openingStock.importStock',compact('locations'));
    }

    public function softOneItemDelete($id)
    {
        openingStocks::where('id', $id)->update([
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
                logger(Carbon::now()->format('Y-m-d H:i:s'));
                openingStocks::where('id', $id)->update([
                    'is_delete' => 1,
                    'deleted_by' => Auth::user()->id,
                    'deleted_at' =>now(),
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


    private function UOM_unit($id)
    {
        return UOM::where('uomset_id', $id)
            ->leftJoin('units', 'uoms.unit_id', '=', 'units.id')
            ->select('units.id', 'name as text')
            ->get();
    }

    public function currentStockBalanceData($opening_stock_detail, $openingStock, $type)
    {
        $refUomInfo=UomHelper::getReferenceUomInfoByCurrentUnitQty($opening_stock_detail->quantity,$opening_stock_detail->uom_id);
        $smallestQty = $refUomInfo['qtyByReferenceUom'];
        $refUomId=$refUomInfo['referenceUomId'];
        stock_history::create([
            'business_location_id' =>  $openingStock->business_location_id,
            'product_id' => $opening_stock_detail->product_id,
            'variation_id' =>  $opening_stock_detail->variation_id,
            'transaction_type' => $type,
            'transaction_details_id' => $opening_stock_detail->id,
            'increase_qty' =>  $smallestQty,
            'decrease_qty' => 0,
            'ref_uom_id' => $refUomId,
        ]);
        return [
            "business_location_id" => $openingStock->business_location_id,
            "product_id" => $opening_stock_detail->product_id,
            "variation_id" => $opening_stock_detail->variation_id,
            "transaction_type" => $type,
            "transaction_detail_id" => $opening_stock_detail->id,
            // "lot_serial_numbers" => UomHelper::generateBatchNo('BSN',6),
            "ref_uom_id" => $opening_stock_detail->ref_uom_id,
            "ref_uom_quantity" => $smallestQty,
            "ref_uom_price" => $opening_stock_detail->ref_uom_price,
            "current_quantity" => $smallestQty,
        ];

    }

}
