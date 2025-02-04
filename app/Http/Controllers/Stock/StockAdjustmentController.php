<?php

namespace App\Http\Controllers\Stock;


use App\Helpers\UomHelper;
use App\Http\Requests\Stock\StoreStockAdjustmentRequest;
use App\Models\BusinessUser;
use App\Models\Contact\Contact;
use App\Models\CurrentStockBalance;
use App\Models\Stock\StockAdjustment;
use App\Models\Stock\StockAdjustmentDetail;
use App\Repositories\LocationRepository;
use App\Repositories\Stock\StockAdjustmentRepository;
use App\Services\Stock\StockAdjustmentServices;
use Exception;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Models\Stock\StockTransfer;
use App\Http\Controllers\Controller;
use App\Models\settings\businessLocation;
use App\Models\settings\businessSettings;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{
    private $locationRepository;
    private $stockAdjustmentRepository;
    public function __construct(
        LocationRepository $locationRepository,
        StockAdjustmentRepository $stockAdjustmentRepository
    )
    {
        $this->middleware(['auth', 'isActive']);
        $this->locationRepository = $locationRepository;
        $this->stockAdjustmentRepository = $stockAdjustmentRepository;

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
//        return $request;
        try {
            DB::beginTransaction();
            $adjustmentServices->create($request);
            DB::commit();
            activity('stock-adjustment')
                ->log('Stock Adjustment creation has been success')
                ->event('create')
                ->status('success')
                ->save();
            return redirect(route('stock-adjustment.index'))->with(['success' => 'Stock Adjustment successfully']);
        }catch (Exception $e){
            DB::rollBack();
            activity('stock-adjustment')
                ->log('Stock Adjustment creation has been fail')
                ->event('create')
                ->status('fail')
                ->save();
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
                $q->where('business_location_id', $business_location_id);
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
//return $stockAdjustment;
        return view('App.stock.adjustment.edit', [
            'stockAdjustment' => $stockAdjustment,
            'adjustment_details' => $adjustment_details,
            'transfer_persons' => $transfer_persons,
            'locations' => $locations,
            'setting' => $setting,

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, StockAdjustmentServices $stockAdjustmentServices)
    {
//return $request;
        try {
            DB::beginTransaction();
                $stockAdjustmentServices->update($id, $request);
            DB::commit();
            activity('stock-adjustment')
                ->log('Stock Adjustment update has been success')
                ->event('update')
                ->status('success')
                ->save();
            return redirect(route('stock-adjustment.index'))->with(['success' => 'Adjustment successfully edited']);
        }catch(\Exception $e){
            DB::rollBack();
            activity('stock-adjustment')
                ->log('Stock Adjustment update has been fail')
                ->event('update')
                ->status('fail')
                ->save();
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    }

    public function softDelete(string $id, StockAdjustmentServices $stockAdjustmentServices){
        try {
            DB::beginTransaction();
            $restore = request()->query('restore');

            if ($restore == 'true') {
                $stockAdjustmentServices->deleteWithRestore($id);
                $data = ['success' => 'Adjustment was removed, and the quantity was returned.'];

            }else{
                $stockAdjustmentServices->delete($id);
                $data = ['success' => 'Adjustment was removed'];
            }
            DB::commit();
            activity('stock-adjustment')
                ->log('Stock Adjustment deletion has been success')
                ->event('delete')
                ->status('success')
                ->save();
            return response()->json($data, 200);
        }catch (Exception $exception){
            DB::rollBack();

            activity('stock-adjustment')
                ->log('Stock Adjustment deletion has been fail')
                ->event('delete')
                ->status('fail')
                ->save();
            $data = ['error' => 'Adjustment deletion failed'];
            return response()->json($data, 200);
        }
    }

    public function listData(Request $request,StockAdjustmentServices $stockAdjustmentServices)
    {
        $pageLength = $request->input('pageLength', 25);

        $adjustmentResults = $this->stockAdjustmentRepository->query()
            ->where('is_delete',0)
            ->with(['businessLocation:id,name', 'createdPerson:id,username'])
            ->OrderBy('id','desc')->paginate($pageLength);

        return response()->json($adjustmentResults);

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

        // logger($adjustment_details);

        $invoiceHtml = view('App.stock.adjustment.print',compact('adjustment','location','adjustment_details'))->render();
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

        $stockAdjustments = $query->get();


        return response()->json($stockAdjustments, 200);
    }

    public function getProductV3(Request $request)
    {


        $data = $request->data;
        $search_type = $data['search_type'];
        $business_location_id = $data['business_location_id'];
        $keyword = $data['query'];
        $variation_id = $data['variation_id'] ?? null;
        $psku_kw = $data['psku_kw'] ?? false;
        $vsku_kw = $data['vsku_kw'] ?? false;
        $pgbc_kw = $data['pgbc_kw'] ?? false;

        $results = null;


        if ($search_type == "Keyword"){
            $relations = [
                'product_packaging' => function ($query) use ($keyword) {
                    $query->where('package_barcode', $keyword);
                },
                'uom:id,name,short_name,unit_category_id,unit_type,value,rounded_amount',
                'uom.unit_category:id,name',
                'uom.unit_category.uomByCategory:id,name,short_name,unit_type,unit_category_id,value,rounded_amount',
                'product_variations.packaging.uom',
                'product_variations.additionalProduct.productVariation.product',
                'product_variations.additionalProduct.uom',
                'product_variations.additionalProduct.productVariation.variationTemplateValue',
                'product_variations.variation_values.variation_template_value',
                'stock' => function ($query) use ($business_location_id, $keyword) {
                    $locationIds = childLocationIDs($business_location_id);
                    $query->select('current_quantity', 'business_location_id', 'product_id','id', 'lot_serial_type', 'lot_serial_no', 'ref_uom_price', 'ref_uom_id')
                        ->whereIn('business_location_id', $locationIds);
                }
            ];
            if (hasModule('ComboKit') && isEnableModule('ComboKit')) {
                $relations = [
                    'rom.uom.unit_category.uomByCategory',
                    'rom.rom_details.productVariation.product',
                    'rom.rom_details.uom',
                    ...$relations
                ];
            }
            $results = Product::select(
                'products.name as name',
                'products.id as id',
                'products.product_code',
                'products.sku',
                'products.product_type',
                'products.has_variation',
                'products.lot_count',
                'products.uom_id',
                'products.purchase_uom_id',
                'products.can_sale',
                'products.is_recurring',
                'products.receipe_of_material_id',

                'product_variations.product_id',
                'product_variations.variation_sku',
                'product_variations.variation_template_value_id',
                'product_variations.default_selling_price',
                'product_variations.id as variation_id',

                'variation_template_values.variation_template_id',
                'variation_template_values.name as variation_name',
                'variation_template_values.id as variation_template_values_id'
            )->whereNull('products.deleted_at')
                ->leftJoin('product_variations', 'products.id', '=', 'product_variations.product_id')
                ->leftJoin('variation_template_values', 'product_variations.variation_template_value_id', '=', 'variation_template_values.id')
                ->where(function ($query) use ($keyword, $psku_kw, $vsku_kw, $pgbc_kw) {
                    $query
                        ->where('can_sale', 1)
                        ->where('products.name', 'like', '%' . $keyword . '%')
                        ->when($psku_kw == 'true', function ($q) use ($keyword) {
                            $q->orWhere('products.sku', 'like', '%' . $keyword . '%');
                        })
                        ->when($vsku_kw == 'true', function ($q) use ($keyword) {
                            $q->orWhere('variation_sku', 'like', '%' . $keyword . '%');
                        })
                        ->when($pgbc_kw == 'true', function ($q) use ($keyword) {
                            $q->orWhereHas('varPackaging', function ($query) use ($keyword) {
                                $query->where('package_barcode', $keyword);
                            });
                        });
                })
                ->when($variation_id, function ($query) use ($variation_id) {
                    $query->where('product_variations.id', $variation_id);
                })
                ->with($relations)
                ->withSum(['stock' => function ($query) use ($business_location_id, $variation_id) {
                    $locationIds = childLocationIDs($business_location_id);
                    $query->whereIn('business_location_id', $locationIds);
                    $query->where('variation_id', '=', DB::raw('product_variations.id'));
                }], 'current_quantity')
                ->get();

            foreach ($results as $result){
                $value_names = '';
                foreach ($result['product_variations']['variation_values'] as $value) {
                    $value_names .= $value['variation_template_value']['name'] . '-';
                }
                $value_names = rtrim($value_names, '-');

                $result['variation_name'] = $value_names;

                $result['aa'] = $result['product_variations']['variation_values'];

            }
        }

        if ($search_type == "Serial"){
            $relations = [
                'product:id,name',
                  'variation.variationTemplateValue',
                'uom:id,name,short_name,unit_category_id,unit_type,value,rounded_amount',
                'uom.unit_category:id,name',
                'uom.unit_category.uomByCategory:id,name,short_name,unit_type,unit_category_id,value,rounded_amount',
            ];

            $results = CurrentStockBalance::where('lot_serial_type', 'serial')
                ->where('lot_serial_no', 'like', '%' . $keyword . '%')
                ->with($relations);

//            $resultsCount = $results->count();
//
//            if ($resultsCount > 1) {
//                $results->where('current_quantity', '>', 0);
//            }

            $results = $results->get();





            $results = $results->map(function ($result) {

                $stock = [
                    'business_location_id' => $result['business_location_id'],
                    'current_quantity' => $result['current_quantity'],
                    'id' => $result['id'],
                    'lot_serial_no' => $result['lot_serial_no'],
                    'lot_serial_type' => $result['lot_serial_type'],
                    'product_id' => $result['product_id'],
                    'ref_uom_id' => $result['ref_uom_id'],
                ];

                $product_variations = [
                    'id' => $result['variation_id'],
                  'packaging' => [],
                ];

                $result['id'] = $result['product_id'];
                $result['serial_data'] = true;
                $result['name'] = $result['product']['name'];
//                $result['variation_name'] = optional($result['variation']['variationTemplateValue'])['name'];
                $result['uom_id'] = $result['ref_uom_id'];
                $result['product_variations'] = $product_variations;
                $result['stock'] = [$stock];
                $result['total_current_stock_qty'] = $result['current_quantity'];
                $result['smallest_unit'] = $result['uom']['name'];
                $result['stock_sum_current_quantity'] = $result['current_quantity'];
                unset($result['business_location_id']);
//                unset($result['current_quantity']);
                return $result;
            });

            $results = $results->toArray();

        }
        return response()->json($results, 200);
    }

    public function quickStoreAdjustment(Request $request, StockAdjustmentServices $stockAdjustmentServices)
    {
        try {
            DB::beginTransaction();
            $data = $stockAdjustmentServices->quickCreate($request);
            DB::commit();
            return response()->json([
                'data' => $data,
                'message' => 'Adjustment successfully created'
            ], 201);
        }catch (Exception $e){
            logger($e);
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
