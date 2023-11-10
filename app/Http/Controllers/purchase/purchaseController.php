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
use App\Models\exchangeRates;
use App\Models\stock_history;
use App\Models\Product\UOMSet;
use App\Models\Contact\Contact;
use App\Models\locationAddress;
use App\Models\paymentAccounts;
use App\Models\Product\Product;
use App\Models\productPackaging;
use App\Helpers\generatorHelpers;
use Illuminate\Support\Facades\DB;
use App\Models\CurrentStockBalance;
use App\Models\purchases\purchases;
use App\Http\Controllers\Controller;
use App\Models\paymentsTransactions;
use Illuminate\Support\Facades\Auth;
use App\Models\Product\ProductVariation;
use Yajra\DataTables\Facades\DataTables;
use App\Actions\purchase\purchaseActions;
use App\Models\settings\businessLocation;
use App\Models\settings\businessSettings;
use Illuminate\Support\Facades\Validator;
use App\Models\purchases\purchase_details;
use App\Services\purchase\purchasingService;
use App\Services\packaging\packagingServices;
use App\Models\Product\VariationTemplateValues;
use App\Repositories\interfaces\CurrencyRepositoryInterface;
use App\Repositories\interfaces\LocationRepositoryInterface;
use App\Repositories\interfaces\SettingRepositoryInterface;
use App\Repositories\locationRepository;
use App\Respoistories\interfaces\LocationInterface;
use App\Services\purchase\purchaseDetailServices;
use App\Services\stockhistory\stockHistoryServices;

class purchaseController extends Controller
{
    private $setting;
    private $currency;
    private $locations;
    public function __construct(
        LocationRepositoryInterface $locationRepository,
        SettingRepositoryInterface $setting,
        CurrencyRepositoryInterface $currency
    ) {
        $this->middleware(['auth', 'isActive']);
        $this->middleware('canView:purchase')->only(['index', 'listData']);
        $this->middleware('canCreate:purchase')->only(['add', 'store']);
        $this->middleware('canUpdate:purchase')->only(['edit', 'update']);
        $this->middleware('canDelete:purchase')->only('softOneItemDelete', 'softSelectedDelete');
        $this->setting = $setting;
        $this->locations = $locationRepository;
        $this->currency = $currency;
    }

    public function index()
    {
        logger('this is loggin from localhost');
        $locations = businessLocation::all();
        $suppliers = Contact::where('type', 'Supplier')
            ->orWhere('type', 'Both')
            ->select('id', 'company_name', 'prefix', 'first_name', 'last_name', 'middle_name')
            ->get();
        return view('App.purchase.listPurchase', compact('locations', 'suppliers'));
    }

    public function add()
    {
        $locations = $this->locations->getAllPermitted();
        $currency = $this->currency->defaultCurrency();
        $suppliers = Contact::where('type', 'Supplier')
            ->orWhere('type', 'Both')
            ->select('id', 'company_name', 'prefix', 'first_name', 'last_name', 'middle_name', 'address_line_1', 'address_line_2', 'zip_code', 'city', 'state', 'country')
            ->get();
        $currencies = Currencies::get();
        $setting = $this->setting->getByUser();

        return view('App.purchase.addPurchase', compact('locations', 'suppliers', 'setting', 'currency', 'currencies'));
    }

    // public function purchase_new_add()
    // {
    //     $locations = businessLocation::all();
    //     $currency = $this->currency->defaultCurrency();
    //     $suppliers = Contact::where('type', 'Supplier')
    //         ->orWhere('type', 'Both')
    //         ->select('id', 'company_name', 'prefix', 'first_name', 'last_name', 'middle_name', 'address_line_1', 'address_line_2', 'zip_code', 'city', 'state', 'country')
    //         ->get();
    //     $currencies = Currencies::get();
    //     $setting = $this->setting->getByUser();
    //     return view('App.purchase.addNewPurchase', compact('locations', 'suppliers', 'setting', 'currency', 'currencies'));
    // }
    public function store(Request $request, purchasingService $purchasingService)
    {
        Validator::make(['details' => $request->purchase_details], ['details' => 'required'])->validate();
        try {
            // create purchase from service
            // purchase detail ,opening stock,stock history actions are use in purchase service
            $purchase = $purchasingService->createPurchase($request);

            // redirect
            if ($request->save == 'save_&_print') {
                return redirect()->route('purchase_list')->with([
                    'success' => 'Successfully Created Purchase',
                    'print' => $purchase['id'],
                ]);
            } else {
                return redirect()->route('purchase_list')->with(['success' => 'Successfully Created Purchase']);
            }
        } catch (\Exception $e) {
            // handle error
            $filePath = $e->getFile();
            $fileName = basename($filePath);
            if ($fileName == 'UomHelpers.php') {
                return redirect()->back()->with(['error' => 'Something Wrong with UOM ! Check UOM category and UOM'])->withInput($request->toArray());
            }
            return redirect()->back()->with(['warning' => 'An error occurred while creating the purchasse'])->withInput();
        }
    }

    public function listData(Request $request, purchasingService $purchasingService)
    {
        return $purchasingService->listData($request);
    }

    public function edit($id)
    {
        $purchase = purchases::where('id', $id)->first();
        if (!checkTxEditable($purchase->created_at)) {
            return back()->with([
                'error' => 'This transaction is not editable.'
            ]);
        };
        $locations = locationRepository::getTransactionLocation();
        $currency = $this->currency->defaultCurrency();
        $suppliers = Contact::where('type', 'Supplier')
            ->orWhere('type', 'Both')
            ->select('id', 'company_name', 'prefix', 'first_name', 'last_name', 'middle_name', 'address_line_1', 'address_line_2', 'zip_code', 'city', 'state', 'country')
            ->get();
        $currencies = Currencies::get();
        $purchase_detail = purchase_details::with([
            'packagingTx',
            'productVariation',
            'productVariation.variationTemplateValue',
            'productVariation.packaging',
            'product.uom.unit_category.uomByCategory'
        ])->where('purchases_id', $id)->where('is_delete', 0)->get();
        $setting = $this->setting->getByUser();
        return view('App.purchase.editPurchase', compact('purchase', 'locations', 'purchase_detail', 'suppliers', 'setting', 'currency', 'currencies'));
    }




    public function update($id, Request $request, purchasingService $service)
    {
        Validator::make([
            'details' => $request->purchase_details,
        ], [
            'details' => 'required',
        ])->validate();
        DB::beginTransaction();
        try {
            $service->update($id, $request);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->route('purchase_list')->with(['warning' => 'Something wrong on Updating Purchase']);
            throw $e;
        }
        // return back()->with(['success' => 'Successfully Updated Purchase']);
        return redirect()->route('purchase_list')->with(['success' => 'Successfully Updated Purchase']);
    }


    public function purhcaseInvoice($id)
    {
        $purchase = purchases::with('supplier', 'purchased_by', 'currency')->where('id', $id)->first()->toArray();
        $location = businessLocation::where('id', $purchase['business_location_id'])->first();
        $address = $location->locationAddress;
        $purchase_details = purchase_details::where('purchases_id', $purchase['id'])
            ->where('is_delete', '0')
            ->with(['product', 'currency', 'purchaseUom', 'productVariation' => function ($q) {
                $q->with('variationTemplateValue');
            }])
            ->get();
        $invoiceHtml = view('App.purchase.invoice.invoice', compact('purchase', 'location', 'purchase_details', 'address'))->render();
        return response()->json(['html' => $invoiceHtml]);
    }
    public function purchaseDetail($id)
    {

        $purchase = purchases::with('business_location_id', 'purchased_by', 'confirm_by', 'supplier', 'updated_by', 'currency')->where('id', $id)->first()->toArray();
        $location = businessLocation::where("id", $purchase['business_location_id'])->first();
        $addresss = locationAddress::where('location_id', $location['id'])->first();
        $purchase_details = purchase_details::with(['productVariation' => function ($q) {
            $q->select('id', 'product_id', 'variation_template_value_id')
                ->with([
                    'product' => function ($q) {
                        $q->select('id', 'name', 'has_variation', 'uom_id');
                    },
                    'variationTemplateValue' => function ($q) {
                        $q->select('id', 'name');
                    }
                ]);
        }, 'product', 'purchaseUom', 'currency', 'packagingTx'])
            ->where('purchases_id', $id)->where('is_delete', 0)->get();
        $setting = $this->setting->getByUser();
        return view('App.purchase.DetailView.purchaseDetail', compact(
            'purchase',
            'location',
            'purchase_details',
            'setting',
            'addresss',
        ));
    }



    public function softOneItemDelete($id)
    {
        purchases::where('id', $id)->update([
            'is_delete' => 1,
            'deleted_by' => Auth::user()->id,
            'deleted_at' => now()
        ]);
        $purchaseDetails = purchase_details::where('purchases_id', $id);
        foreach ($purchaseDetails->get() as $pd) {
            CurrentStockBalance::where('transaction_type', 'purchase')->where('transaction_detail_id', $pd->id)->delete();
        }
        $purchaseDetails->update([
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
                purchases::where('id', $id)->update([
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

    public function getUnits($id)
    {
        $uoms = $this->UOM_unit($id);
        return response()->json($uoms->toArray(), 200);
    }

    // private function variation_
    private function UOM_unit($id)
    {
        return UOM::where('uomset_id', $id)
            ->leftJoin('units', 'uoms.unit_id', '=', 'units.id')
            ->select('units.id', 'name as text')
            ->get();
    }




    public function changeDefaultPurchasePrice($variation_id, $default_selling_price)
    {
        $variation_product = ProductVariation::where('id', $variation_id)->first();
        if ($variation_product) {
            $variation_product->update(['default_purchase_price' => $default_selling_price]);
        }
    }

    protected function currentStockBalanceAndStockHistoryCreation($purchase_detail_data, $purchase, $type)
    {
        $data = $this->currentStockBalanceData($purchase_detail_data, $purchase, $type);
        $businessLocation = businessLocation::where('id', $data['business_location_id'])->first();
        if ($purchase->status == 'received') {
            if ($businessLocation->allow_purchase_order == 1) {
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

    protected function currentStockBalanceData($purchase_detail_data, $purchase, $type)
    {
        $referencUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($purchase_detail_data->quantity, $purchase_detail_data->purchase_uom_id);
        $batchNo = UomHelper::generateBatchNo($purchase_detail_data['variation_id']);
        $per_ref_uom_price_by_default_currency = exchangeCurrency($purchase_detail_data->per_ref_uom_price, $purchase->currency_id, $this->currency->defaultCurrency()->id) ?? 0;
        return [
            "business_location_id" => $purchase->business_location_id,
            "product_id" => $purchase_detail_data->product_id,
            "variation_id" => $purchase_detail_data->variation_id,
            "transaction_type" => $type,
            "transaction_detail_id" => $purchase_detail_data->id,
            "batch_no" => $purchase_detail_data->batch_no,
            "expired_date" => $purchase_detail_data->expired_date,
            "uomset_id" => $purchase_detail_data->uomset_id,
            'batch_no' => $batchNo,
            "ref_uom_id" => $referencUomInfo['referenceUomId'],
            "ref_uom_quantity" => $referencUomInfo['qtyByReferenceUom'],
            "ref_uom_price" => $per_ref_uom_price_by_default_currency,
            "current_quantity" => $referencUomInfo['qtyByReferenceUom'],
            'currency_id' => $purchase->currency_id,
        ];
    }

    protected function getProductForPurchase(Request $request)
    {
        $q = $request->data;
        $products = Product::select('id', 'name', 'product_code', 'sku', 'has_variation', 'uom_id', 'purchase_uom_id')
            ->where('can_purchase', 1)
            ->where('name', 'like', '%' . $q . '%')
            ->orWhere('sku', 'like', '%' . $q . '%')
            ->with(
                [
                    'productVariations' => function ($query) {
                        $query->select('id', 'product_id', 'variation_template_value_id', 'variation_sku', 'default_purchase_price', 'default_selling_price')
                            ->with([
                                'variationTemplateValue:id,name',
                                'packaging' => function ($q) {
                                    $q->where('for_purchase', 1);
                                }
                            ]);
                    }, 'uom' => function ($q) {
                        $q->with(['unit_category' => function ($q) {
                            $q->with('uomByCategory');
                        }]);
                    }
                ]
            )->get()->toArray();
        if (count($products) > 0) {
            foreach ($products as $i => $product) {
                if ($product['has_variation'] == 'variable') {
                    $p = $product['product_variations'];
                    foreach ($p as $variation) {
                        $variation_id = $variation['id'];
                        $lastPurchase = purchase_details::where('per_ref_uom_price', '!=', '0')
                            ->where('variation_id', $variation_id)
                            ->orderBy('id', 'DESC')
                            ->first();
                        if ($lastPurchase) {
                            $lastPurchasePrice = priceChangeByUom($lastPurchase->ref_uom_id, $lastPurchase->per_ref_uom_price, $product['purchase_uom_id']);
                            // logger($lastPurchasePrice);
                        }
                        $variation_product = [
                            'id' => $product['id'],
                            'name' => $product['name'],
                            'sku' => $variation['variation_sku'],
                            'purchase_uom_id' => $product['purchase_uom_id'],
                            'uom_id' => $product['uom_id'],
                            'uom' => $product['uom'],
                            'variation_id' => $variation['id'],
                            'has_variation' => 'sub_variable',
                            'variation_name' => $variation['variation_template_value']['name'],
                            'default_purchase_price' => $variation['default_purchase_price'],
                            'default_selling_price' => $variation['default_selling_price'],
                            'lastPurchasePrice' => $lastPurchase ? $lastPurchasePrice : 0,
                            'packaging' => $variation['packaging'],
                            'product_variations' => $variation
                        ];
                        // dd($variation_product)
                        array_push($products, $variation_product);
                    }
                } else {
                    $variation_id = $product['product_variations'][0]['id'];
                    $lastPurchase = purchase_details::where('per_ref_uom_price', '!=', '0')->where('variation_id', $variation_id)->orderBy('id', 'DESC')->first();
                    $products[$i]['variation_id'] = $variation_id;
                    $products[$i]['default_selling_price'] = $product['product_variations'][0]['default_selling_price'];
                    if ($lastPurchase) {
                        $lastPurchasePrice = priceChangeByUom($lastPurchase->ref_uom_id, $lastPurchase->per_ref_uom_price, $product['purchase_uom_id']);
                        $products[$i]['lastPurchasePrice'] = $lastPurchasePrice;
                    }
                }
            }
        }
        return response()->json($products, 200);
    }

    public function getProductForPurchaseV2(Request $request)
    {
        $q = $request->data;
        $products = Product::select(
            'products.*',
            'product_variations.*',
            'variation_template_values.*',
            'variation_template_values.name as variation_name',
            'products.name as name',
            'products.id as id',
            'product_variations.id as variation_id'
        )->leftJoin('product_variations', 'products.id', '=', 'product_variations.product_id')->leftJoin('variation_template_values', 'product_variations.variation_template_value_id', '=', 'variation_template_values.id')
            ->where('products.name', 'like', '%' . $q . '%')
            ->orWhere('sku', 'like', '%' . $q . '%')
            ->orWhere('variation_sku', 'like', '%' . $q . '%')
            ->orWhereHas('varPackaging', function ($query) use ($q) {
                $query->where('package_barcode', $q);
            })
            ->with([
                'product_packaging' => function ($query) use ($q) {
                    $query->where('package_barcode', $q);
                },
                'uom' => function ($q) {
                    $q->with('unit_category.uomByCategory');
                },
                'product_variations.packaging.uom'
            ])
            ->get()->toArray();
        // dd(productPackaging::with('product_variations')->get()->toArray());
        return response()->json($products, 200);
    }
}
