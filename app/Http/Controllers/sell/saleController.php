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
use App\Services\SaleServices;
use Illuminate\Support\Carbon;
use App\Models\Contact\Contact;
use App\Models\InvoiceTemplate;
use App\Models\locationAddress;
use App\Models\paymentAccounts;
use App\Models\Product\Product;
use App\Models\lotSerialDetails;
use App\Helpers\generatorHelpers;
use App\Models\sale\sale_details;
use App\Services\paymentServices;
use App\Models\Product\PriceGroup;
use App\Models\Product\PriceLists;
use Illuminate\Support\Facades\DB;
use App\Models\CurrentStockBalance;
use App\Models\purchases\purchases;
use App\Http\Controllers\Controller;
use App\Models\paymentsTransactions;
use Illuminate\Support\Facades\Auth;
use App\Models\hospitalRoomSaleDetails;
use App\Models\posRegisterTransactions;
use App\Models\Product\PriceListDetails;
use App\Models\Product\ProductVariation;
use App\Repositories\LocationRepository;
use Yajra\DataTables\Facades\DataTables;
use App\Models\settings\businessLocation;
use App\Models\settings\businessSettings;
use Illuminate\Support\Facades\Validator;
use Modules\ComboKit\Services\RoMService;
use App\Models\purchases\purchase_details;
use App\Services\packaging\packagingServices;
use Modules\Reservation\Entities\Reservation;
use App\Models\posSession\posRegisterSessions;
use Modules\Reservation\Entities\FolioInvoice;
use App\Http\Requests\location\locationRequest;
use Modules\ExchangeRate\Entities\exchangeRates;
use Modules\Reservation\Entities\FolioInvoiceDetail;
use App\Http\Controllers\posSession\posSessionController;
use App\Repositories\interfaces\LocationRepositoryInterface;
use Modules\HospitalManagement\Entities\hospitalFolioInvoices;
use Modules\HospitalManagement\Entities\hospitalRegistrations;
use Modules\HospitalManagement\Entities\hospitalFolioInvoiceDetails;

class saleController extends Controller
{
    private $setting;
    private $currency;
    private $accounting_method;
    public $locations;
    public function __construct(
        LocationRepositoryInterface $locationRepository,
    )
    {
        $this->middleware(['auth', 'isActive']);
        $this->middleware('canView:sell')->only(['index', 'saleItemsList']);
        $this->middleware('canCreate:sell')->only(['createPage']);
        $this->middleware('canUpdate:sell')->only(['saleEdit', 'update']);
        $this->middleware('canDelete:sell')->only('softDelete', 'softSelectedDelete');
        $settings = businessSettings::select('lot_control', 'currency_id', 'accounting_method', 'enable_line_discount_for_sale', 'invoice_layout')->with('currency')->first();
        $this->setting = $settings;
        $this->currency = $settings->currency ?? null;
        $this->accounting_method = $settings->accounting_method ?? null;
        $this->locations=$locationRepository;
    }


    public function index($saleType = 'allSales')
    {
        $locations = businessLocation::select('name', 'id', 'parent_location_id')->get();
        $customers = contact::where('type', 'Customer')->orWhere('type', 'Both')->get();
        return view('App.sell.sale.allSalesTable', compact('locations', 'customers', 'saleType'));
    }


    public function saleItemsList(Request $request)
    {

        $accessUserLocation = getUserAccesssLocation();
        $saleItems = sales::query()
            ->orderBy('id', 'DESC')
            ->select(
                'sales.id',
                'sales.sold_at',
                'sales.contact_id',
                'sales.status',
                'sales.table_id',
                'sales.sale_amount',
                'sales.total_sale_amount',
                'sales.paid_amount',
                'sales.balance_amount',
                'sales.business_location_id',
                'sales.sales_voucher_no',
                'sales.currency_id',
                'contacts.prefix',
                'contacts.first_name',
                'contacts.last_name',
                'contacts.middle_name',
                'contacts.company_name',
                'business_locations.name'
            )
            ->where('sales.is_delete', 0)
            ->when($accessUserLocation[0] != 0, function ($query) use ($accessUserLocation) {
                $query->whereIn('business_location_id', $accessUserLocation);
            })
            ->leftJoin('contacts', 'sales.contact_id', '=', 'contacts.id')
            ->leftJoin('business_locations', 'sales.business_location_id', '=', 'business_locations.id')
            ->with('businessLocation:id,name,parent_location_id', 'customer:id,prefix,company_name,first_name,last_name,middle_name', 'currency:symbol,id');
        if ($request->saleType == 'posSales') {
            $saleItems = $saleItems->whereNotNull('pos_register_id');
        }
        if ($request->saleType == 'sales') {
            $saleItems = $saleItems->whereNull('pos_register_id');
        }
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $saleItems = $saleItems->whereDate('sales.created_at', '>=', $request->from_date)->whereDate('sales.created_at', '<=', $request->to_date);
        }
        $saleItems = $saleItems;

        return DataTables::of($saleItems)
            ->editColumn('saleItems', function ($saleItems) {
                return '';
            })
            // ->orderColumn('name', function ($data, $order) {
            //     $data->orderBy('products.name', $order);
            // })
            ->filterColumn('saleItems', function ($data, $keyword) {
                $data->when(rtrim($keyword),function($mainquery)use($keyword){
                    $mainquery->where("sales.sales_voucher_no", 'like', '%' . $keyword . '%')
                        ->orWhere(function ($subQuery) use ($keyword) {
                            $subQuery->where("contacts.first_name", 'like', '%' . $keyword . '%')
                                ->orWhere("contacts.last_name", 'like', '%' . $keyword . '%')
                                ->orWhere("contacts.middle_name", 'like', '%' . $keyword . '%')
                                ->orWhere("contacts.company_name", 'like', '%' . $keyword . '%');
                        })
                        ->orWhere(function ($subQuery) use ($keyword) {
                            $subQuery->where("business_locations.name", 'like', '%' . $keyword . '%');
                        });
                });
            })

            ->filterColumn('businessLocation', function ($data, $keyword) {
                $data->where("sales.business_location_id",$keyword);
            })

            ->filterColumn('status', function ($data, $keyword) {
                $data->where("sales.status", $keyword);
            })

            ->filterColumn('customer', function ($data, $keyword) {
                if($keyword != 'all'){
                    $data->where("sales.contact_id", $keyword);
                }
            })
            ->addColumn('checkbox', function ($saleItem) {
                return
                    '
                    <div class="form-check form-check-sm form-check-custom ">
                        <input class="form-check-input" type="checkbox" data-checked="delete" value=' . $saleItem->id . ' />
                    </div>
                ';
            })
            ->addColumn('businessLocation', function ($saleItem) {
                return businessLocationName($saleItem->businessLocation);
            })
            ->addColumn('customer', function ($saleItem) {
                if ($saleItem->customer) {
                    return $saleItem->customer['company_name'] ?? $saleItem->customer->getFullNameAttribute();
                }
                return '';
            })
            ->editColumn('sold_at', function ($saleItem) {
                return fDate($saleItem->sold_at, true);
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
            ->addColumn('action', function ($saleItem) use ($request) {
                $postTo = '';
                if (hasModule('HospitalManagement') && isEnableModule('HospitalManagement')) {
                    $postTo = '<a type="button" class="dropdown-item p-2 edit-unit  postToRegisterationFolio" data-href="' . route('postToRegistrationFolio', $saleItem->id) . '">Post to Folio</a>';
                }
                $html = '
                    <div class="dropdown text-center">
                        <button class="btn btn-sm btn-light btn-primary fw-semibold fs-7  dropdown-toggle " type="button" id="saleItemDropDown" data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                        </button>
                        <div class="z-3">
                        <ul class="dropdown-menu z-10 p-5 " aria-labelledby="saleItemDropDown" role="menu">';
                if (hasView('sell')) {
                    $html .= ' <a class="dropdown-item p-2   view_detail"   type="button" data-href="' . route('saleDetail', $saleItem->id) . '">
                                View
                            </a>';
                }
                if (hasUpdate('sell')) {
                    if ($request->saleType == 'posSales') {
                        $html .= '<a class="dropdown-item p-2" href=" ' . route('pos.edit', ['posRegisterId' => $saleItem->pos_register_id, 'saleId' => $saleItem->id]) . ' ">Edit</a>';
                    } else {
                        $html .= ' <a href="' . route('saleEdit', $saleItem->id) . '" class="dropdown-item p-2   edit-unit " >Edit</a>';
                    }
                }
                if (hasPrint('sell')) {
                    $html .= '<a class="dropdown-item p-2  cursor-pointer  print-invoice"  data-href="' . route('print_sale', $saleItem->id) . '"     data-layoutId="' . $saleItem->businessLocation->invoice_layout . '">Print</a>';
                }


                $html .= '<a class="dropdown-item p-2  cursor-pointer download-image" data-name="' . $saleItem->sales_voucher_no . '" data-layoutId="' . $saleItem->businessLocation->invoice_layout . '"  data-href=" ' . route('print_sale', $saleItem->id) . ' ">Download Image</a>';


                if ($saleItem->balance_amount > 0) {
                    $html .= '<a class="dropdown-item p-2 cursor-pointer " id="paymentCreate"   data-href="' . route('paymentTransaction.createForSale', ['id' => $saleItem->id, 'currency_id' => $saleItem->currency_id]) . '">Add Payment</a>';
                }
                $html .= '<a class="dropdown-item p-2 cursor-pointer " id="viewPayment"   data-href="' . route('paymentTransaction.viewForSell', $saleItem->id) . '">View Payment</a>';
                $html .= $postTo;

                $html .= '<a type="button" class="dropdown-item p-2  post-to-reservation" data-href="' . route('postToReservationFolio', $saleItem->id) . '">Post to Reservation</a>';


                if (hasDelete('sell')) {
                    $html .= ' <a class="dropdown-item p-2  cursor-pointer bg-active-danger text-danger"  data-id="' . $saleItem->id . '" data-kt-saleItem-table="delete_row">Delete</a>';
                }

                $html .= '</ul></div></div>';
                return (hasView('sell') && hasPrint('sell') && hasUpdate('sell') && hasDelete('sell') ? $html : 'No Access');
            })
            ->rawColumns(['action', 'checkbox', 'status', 'sold_at'])
            ->make(true);
    }

    public function saleDetail($id)
    {
        $relations = [
            'sold_by', 'confirm_by', 'customer', 'updated_by', 'currency'
        ];
        if(hasModule('restaurant') && isEnableModule('restaurant')){
            $relations[] = 'table';
        }
        $sale = sales::with(...$relations)->where('id', $id)->first()->toArray();

        $location = businessLocation::where("id", $sale['business_location_id'])->first();
        $address = locationAddress::where("location_id", $location->id)->first();
        $setting = $this->setting;
        $sale_details_query = sale_details::with([
            'productVariation' => function ($q) {
                $q->select('id', 'product_id', 'variation_template_value_id')
                    ->with([
                        'product' => function ($q) {
                            $q->select('id', 'name', 'has_variation');
                        },
                        'variationTemplateValue' => function ($q) {
                            $q->select('id', 'name');
                        }
                    ]);
            },
            'product', 'uom', 'currency', 'packagingTx'
        ])
            ->where('sales_id', $id)->where('is_delete', 0);

        $sale_details = $sale_details_query->get();
        return view('App.sell.sale.details.saleDetail', compact(
            'sale',
            'location',
            'sale_details',
            'setting',
            'address'
        ));
    }
    // sale create page
    public function createPage()
    {
        $locations = LocationRepository::getTransactionLocation();
        // dd($locations->toArray());
        $products = Product::with('productVariations')->get();
        $walkInCustomer = optional(Contact::where('type', 'Customer')->orWhere('type', 'Both')->where('first_name', 'Walk-In Customer')->first());
        $priceLists = PriceLists::select('id', 'name', 'description', 'currency_id')->get();
        $paymentAccounts = paymentAccounts::get();
        $setting = businessSettings::where('id', Auth::user()->business_id)->first();
        $defaultCurrency = $this->currency;
        $currencies = Currencies::get();
        $defaultPriceListId = getSystemData('defaultPriceListId');
        $exchangeRates = [];

        if (class_exists('Modules\ExchangeRate\Entities\exchangeRates') && hasModule('ExchangeRate') && isEnableModule('ExchangeRate')) {
            $exchangeRates = exchangeRates::get();
        }
        return view('App.sell.sale.addSale', compact('defaultPriceListId', 'walkInCustomer', 'locations', 'products', 'priceLists', 'setting', 'defaultCurrency', 'paymentAccounts', 'currencies', 'exchangeRates'));
    }
    // for edit page
    public function saleEdit($id)
    {

        try {
            $sale = sales::with('currency')->where('id', $id)->get()->first();
            $voucherLocation = $sale->business_location_id;
            checkLocationAccessForTx($voucherLocation);
            $locations = LocationRepository::getTransactionLocation();
            $products = Product::with('productVariations')->get();
            $customers = Contact::where('type', 'Customer')->orWhere('type', 'Both')->get();
            $priceLists = PriceLists::select('id', 'name', 'description')->get();
            $defaultPriceListId = getSystemData('defaultPriceListId');
            // $priceGroups = PriceGroup::select('id', 'name', 'description')->get();
            // $locations = businessLocation::all();
            $setting = businessSettings::first();
            $currency = $this->currency;

            $exchangeRates = [];
            if (class_exists('exchangeRates') && hasModule('ExchangeRate') && isEnableModule('ExchangeRate')) {
                $exchangeRates = exchangeRates::get();
            }

            $business_location_id = $sale->business_location_id;
            $ckRelations = [];
            if (hasModule('ComboKit') && isEnableModule('ComboKit')) {
                $ckRelations = [
                    'kitSaleDetails.product',
                    'kitSaleDetails.uom',
                ];
            }
            $sale_details_query = sale_details::with([
                ...$ckRelations,
                'packagingTx',
                'currency',
                'productVariation' => function ($q) {
                    $q->select('id', 'product_id', 'variation_template_value_id', 'default_selling_price')
                    ->with([
                        'packaging.uom',
                        'product' => function ($q) {
                            $q->select('id', 'name', 'has_variation');
                        },
                        'variationTemplateValue' => function ($q) {
                            $q->select('id', 'name');
                        }, 'additionalProduct.productVariation.product', 'additionalProduct.uom', 'additionalProduct.productVariation.variationTemplateValue'
                    ]);
                },
                'stock' => function ($q) use ($business_location_id) {
                    $locationIds = childLocationIDs($business_location_id);
                    $q->where('current_quantity', '>', 0)
                    ->whereIn('business_location_id', $locationIds);
                },
                'Currentstock',  'product' => function ($q) {
                    $q->with(['uom' => function ($q) {
                        $q->with(['unit_category' => function ($q) {
                            $q->with('uomByCategory');
                        }]);
                    }]);
                },
            ])
                ->where('sales_id', $id)->where('is_delete', 0)
                ->withSum(['stock' => function ($q) use ($business_location_id) {
                    $locationIds = childLocationIDs($business_location_id);
                    $q->whereIn('business_location_id', $locationIds);
                }], 'current_quantity');
            $sale_details = $sale_details_query->get();
            $currencies = Currencies::get();
            $defaultCurrency = $this->currency;
            return view('App.sell.sale.edit', compact('products', 'defaultPriceListId', 'customers', 'priceLists', 'sale', 'sale_details', 'setting', 'currency', 'currencies', 'defaultCurrency', 'locations', 'exchangeRates'));
        } catch (\Throwable $th) {
            return back()->with('error',$th->getMessage());
        }
    }
    // sale store
    public function store(Request $request, SaleServices $saleService, paymentServices $paymentServices)
    {
        $location = businessLocation::find($request->business_location_id);
        $layoutId = InvoiceTemplate::find($location->invoice_layout);
        $sale_details = $request->sale_details;
        Validator::make($request->toArray(), [
            'sale_details' => 'required',
            'business_location_id' => 'required',
            'contact_id' => 'required',
            'status' => 'required',
        ], [
            'sale_details.required' => 'Sale Items are required!',
            'business_location_id.required' => 'Bussiness Location is required!',
            'contact_id.required' => 'Contact is required!',
        ])->validate();
        $request['channel_type'] = 'sale';
        if ($request->type == 'pos') {
            $registeredPos = posRegisters::where('id', $request->pos_register_id)->select('id', 'payment_account_id', 'use_for_res')->first();
            $paymentAccountIds = json_decode($registeredPos->payment_account_id);
            $request['payment_account'] = $paymentAccountIds[0] ?? null;
            $request['currency_id'] = $this->currency->id ?? null;
            $request['channel_type'] = 'pos';
            $request['channel_id'] = $request->pos_register_id;
            $sold_at=$request['sold_at'] ?? now();
            $request['sold_at']=$sold_at;
        } elseif ($request->type == 'campaign') {
            $request['channel_type'] = 'campaign';
            $request['channel_id'] = $request->channel_id;
            $sold_at = now();
            $request['sold_at'] = $sold_at;
        }
        DB::beginTransaction();
        try {

            // checkLocationAccessForTx($request->business_location_id);
            // get payment status
            if ($request->paid_amount == 0) {
                $payment_status = 'due';
            } elseif ($request->paid_amount >= $request->total_sale_amount) {
                $payment_status = 'paid';
            } else {
                $payment_status = 'partial';
            }
            $request['payment_status'] = $payment_status;
            $sale_data = $saleService->create($request);
            // dd($sale_data->toArray());
            if ($request->reservation_id) {
                $request['sale_id'] = $sale_data->id;
                $this->addToReservationFolio($request, true);
            }


            // create payment
            if ($request->paid_amount > 0) {
                if ($request->type == 'pos') {
                    $multiPayment = $request->multiPayment;
                    $data = [
                        'sessionId' => $request->sessionId,
                        'saleId' => $sale_data->id,
                        'currency_id' =>  $request->currency_id,
                    ];
                    $paymentServices->multiPayment($multiPayment, $data, $sale_data);
                } else {
                    $payemntTransaction = $paymentServices->makePayment($sale_data, $request->payment_account, 'sale');
                }
            } else {
                $suppliers = Contact::where('id', $request->contact_id)->first();
                $suppliers_receivable = $suppliers->receivable_amount;
                $suppliers->update([
                    'receivable_amount' => $suppliers_receivable + $request->balance_amount
                ]);
            }
            $resOrderData = null;
            // for pos
            if ($request->type == 'pos' && $registeredPos->use_for_res == 1) {
                $resOrderData = $this->resOrderCreation($sale_data, $request);
                $sdcStatus = $saleService->saleDetailCreation($request, $sale_data, $sale_details, $resOrderData);
                if ($sdcStatus == 'outOfStock') {
                    return response()->json([
                        'status' => '422',
                        'message' => 'Product Out of Stock'
                    ], 422);
                }
            } else {
                $sdcStatus = $saleService->saleDetailCreation($request, $sale_data, $sale_details);
                if ($sdcStatus == 'outOfStock') {
                    if ($request->type == 'pos' || $request->type == 'campaign') {
                        return response()->json([
                            'status' => '404',
                            'message' => 'Product Out of Stock'
                        ], 200);
                    } else {
                        return back()->withInput()->with(['error' => 'Product Out Of Stock']);
                    }
                }
            }
            DB::commit();
            activity('sale-transaction')
                ->log('New Sale creation has been success')
                ->event('create')
                ->status('success')
                ->properties([
                    'id'=> $sale_data->id,
                    'status' => $sale_data->status,
                    'total_amount' => $sale_data->sale_amount,
                    'payment_status' => $sale_data->payment_status,
                ])
                ->save();
            // response
            if ($request->type == 'pos' || $request->type == "campaign") {
                return response()->json([
                    'data' => $sale_data['id'],
                    'status' => '200',
                    'message' => 'successfully Created'
                ], 200);
            } else {
                if ($request->save == 'save_&_print') {
                    return redirect()->route('all_sales', 'allSales')->with([
                        'success' => 'Successfully Created Sale',
                        'print' => $sale_data->id,
                        'layoutId' => $layoutId,
                    ]);
                } elseif ($request->save == "save_&_download_image") {
                    return redirect()->route('all_sales', 'allSales')->with([
                        'success' => 'Successfully Created Sale',
                        'print' => $sale_data->id,
                        'layoutId' => $layoutId,
                        'name' => $sale_data->sales_voucher_no
                    ]);
                } else {
                    return redirect()->route('all_sales', 'allSales')->with(['success' => 'Successfully Created Sale']);
                }
            }
        } catch (Exception $e) {
            logger($e);
            DB::rollBack();
            if ($request->type == 'pos') {
                return response()->json([
                    'status' => '422',
                    'message' => $e->getMessage()
                ], 422);
            } else {
                activity('sale-transaction')
                    ->log('New Sale creation has been fail')
                    ->event('create')
                    ->status('fail')
                    ->save();
                return back()->with(['warning' => 'Something Went Wrong While creating sale']);
            }
        }
    }







    public function resOrderCreation($sale_data, $request)
    {
        return resOrders::create([
            'table_id'=>$sale_data['table_id'],
            'order_voucher_no' => generatorHelpers::resOrderVoucherNo(),
            'order_status' => 'order',
            'location_id' => $sale_data->business_location_id,
            'services' => $request->services,
            'pos_register_id' => $request->pos_register_id
        ]);
    }

    public function update2($id, Request $request, SaleServices $saleService)
    {
        $requestSaleDetailsData = $request->sale_details ?? [];
        // I fetch  sales data ,one for store as old data that fetch form database and one is to update data and after updated ,if you call slaes the will be updated!!
        $saleBefUpdate = sales::where('id', $id)->first();
        $businessLocationId= $saleBefUpdate->business_location_id;
        if ($request->type == 'pos') {
            $registeredPos = posRegisters::where('id', $request->pos_register_id)->select('id', 'payment_account_id', 'use_for_res')->first();
        }
        try {
            DB::beginTransaction();

            //
            // -----------------------------update sale data -------------
            //
            $updatedSaleData=$saleService->update($id,$request);
            $this->txUpdateForPos($request, $saleBefUpdate,$updatedSaleData);


            $requestToUpdateSaleDetails = array_filter($requestSaleDetailsData, function ($item) {
                return isset($item['sale_detail_id']);
            });
            //
            // ----------------------------- remove sale detail data -------------
            //
            $this->removeSaleDetialsData($id, $requestToUpdateSaleDetails);
            if (count($requestSaleDetailsData) > 0) {
                //request to update sale detail



                //
                // ----------------------------- createNew Sale Detail Data -------------
                //
                $newSaleDetailsData = array_filter($requestSaleDetailsData, function ($item) {
                    return !isset($item['sale_detail_id']);
                });
                if (count($newSaleDetailsData) > 0) {
                    $resOrderData = null;
                    if ($request->type == 'pos' && $registeredPos->use_for_res == 1) {
                        $resOrderData = $this->resOrderCreation($updatedSaleData, $request);
                        $saleService->saleDetailCreation($request, $updatedSaleData, $newSaleDetailsData, $resOrderData);
                    } else {
                        $saleService->saleDetailCreation($request, $updatedSaleData, $newSaleDetailsData, $resOrderData);
                    }
                }

                //
                // ----------------------------- Update Sale Detail Data -------------
                //
                foreach ($requestToUpdateSaleDetails as $requestToUpdateSaleDetail) {
                    if (count($requestToUpdateSaleDetail) <= 1) {
                        continue;
                    };
                    //
                    //  Init Data Sections
                    //
                    $requestToUpdateSaleDetailId = $requestToUpdateSaleDetail['sale_detail_id'];


                    //fetching
                    $beforeUpdateSaleDetailData = sale_details::where('id', $requestToUpdateSaleDetailId)->with('kitSaleDetails')->where('is_delete', 0)->first();
                    $productId = $beforeUpdateSaleDetailData['product_id'];
                    $variation_id = $beforeUpdateSaleDetailData['variation_id'];
                    $businessLocation = businessLocation::where('id', $request->business_location_id)->first();
                    $product = Product::where('id', $productId)->select('product_type', 'id', 'uom_id')->first();
                    $requestToUpdateSaleDetail['id']= $requestToUpdateSaleDetailId;
                    //
                    // checking
                    //
                    $lotSerialCheck = lotSerialDetails::where('transaction_type', 'sale')->where('transaction_detail_id', $requestToUpdateSaleDetailId)->exists();


                    $refUomInfoForRequestToUpdate= UomHelper::getReferenceUomInfoByCurrentUnitQty($requestToUpdateSaleDetail['quantity'], $requestToUpdateSaleDetail['uom_id']);
                    $requestToUpdateQtyByRef = $refUomInfoForRequestToUpdate['qtyByReferenceUom'];
                    $requestToUpdateUomIdByRef = $refUomInfoForRequestToUpdate['referenceUomId'];

                    $beforeUpdateSaleDetailQtyByRef = UomHelper::getReferenceUomInfoByCurrentUnitQty($beforeUpdateSaleDetailData['quantity'], $beforeUpdateSaleDetailData['uom_id'])['qtyByReferenceUom'];
                    if ($product->product_type == 'storable') {
                        // stock adjustment
                        if ($saleBefUpdate['status'] != 'delivered' && $request->status == "delivered" && !$lotSerialCheck) {
                            $changeQtyStatus = $saleService->changeStockQty($requestToUpdateQtyByRef, $requestToUpdateUomIdByRef, $request->business_location_id, $requestToUpdateSaleDetail, [], $updatedSaleData);
                            if ($changeQtyStatus == false) {
                                return back()->with(['error' => "product Out of Stock"]);
                            } else {
                                $stocksTracks = $changeQtyStatus;
                                foreach ($stocksTracks as $stocksTrack) {
                                    $sale_uom_qty = UomHelper::changeQtyOnUom($stocksTrack['ref_uom_id'], $requestToUpdateSaleDetail['uom_id'], $stocksTrack['stockQty']);
                                    $bsd = lotSerialDetails::create([
                                        'transaction_type' => 'sale',
                                        'transaction_detail_id' => $requestToUpdateSaleDetailId,
                                        'current_stock_balance_id' => $stocksTrack['stock_id'],
                                        'lot_serial_numbers' => $stocksTrack['lot_serial_no'],
                                        'uom_quantity' => $sale_uom_qty,
                                        'uom_id' => $requestToUpdateSaleDetail['uom_id'],
                                        'ref_uom_quantity'=> $stocksTrack['stockQty'],
                                    ]);
                                }
                            }
                        } elseif ($saleBefUpdate->status == 'delivered' && $request->status != "delivered") {
                            $lotSerials = lotSerialDetails::where('transaction_type', 'sale')->where('transaction_detail_id', $requestToUpdateSaleDetailId);
                            stock_history::where('transaction_details_id', $requestToUpdateSaleDetailId)->where('transaction_type', 'sale')->delete();
                            if ($lotSerials->exists()) {
                                $this->adjustStock($lotSerials->get());
                                foreach ($lotSerials->get() as $lotSerial) {
                                    $lotSerial->delete();
                                }
                            };
                        } else {
                            if ($requestToUpdateQtyByRef > $beforeUpdateSaleDetailQtyByRef) {
                                $locationIds = childLocationIDs($businessLocation->id);
                                $current_stock = CurrentStockBalance::where('product_id', $productId)
                                    ->whereIn('business_location_id', $locationIds)
                                    ->where('variation_id', $variation_id)
                                    ->where('current_quantity', '>', '0');
                                $availableStocks = $current_stock->get();
                                $availableQty = $current_stock->sum('current_quantity');

                                $qtyToRemoveByRefUom = $requestToUpdateQtyByRef - $beforeUpdateSaleDetailQtyByRef;

                                if ($qtyToRemoveByRefUom > $availableQty) {
                                    return redirect()->route('all_sales', 'allSales')->with(['warning' => 'out of stock']);
                                }
                                // $qtyToRemove = $requestToUpdateSaleDetail['quantity'] - $beforeUpdateSaleDetailData['quantity'];

                                foreach ($availableStocks as $stock) {

                                    $stockQtyBySdUom = round(UomHelper::changeQtyOnUom($stock['ref_uom_id'], $requestToUpdateSaleDetail['uom_id'], $stock['current_quantity']), 2);
                                    $stockQtyByRef = $stock['current_quantity'];

                                    if ($qtyToRemoveByRefUom >= $stockQtyByRef) {
                                        $csbQuery=CurrentStockBalance::where('id', $stock['id'])->first();
                                        $lotSerialDetailsByStock = lotSerialDetails::where('transaction_type', 'sale')
                                                                    ->where('transaction_detail_id', $requestToUpdateSaleDetailId)
                                                                    ->where('current_stock_balance_id', $stock['id']);
                                        $lotSerialDetailsByStockQuery= $lotSerialDetailsByStock->first();

                                        if ($lotSerialDetailsByStock->exists()) {
                                            $lotSerialDetailsByStockQuery->update([
                                                'uom_id' => $requestToUpdateSaleDetail['uom_id'],
                                                'uom_quantity' => $stockQtyBySdUom+ $lotSerialDetailsByStockQuery['uom_quantity'],
                                                'ref_uom_quantity'=> $stockQtyByRef + $lotSerialDetailsByStockQuery['ref_uom_quantity'],
                                            ]);
                                        } else {
                                            lotSerialDetails::create([
                                                'transaction_type' => 'sale',
                                                'transaction_detail_id' => $requestToUpdateSaleDetailId,
                                                'current_stock_balance_id' =>  $stock['id'],
                                                'lot_serial_numbers' => $stock->lot_serial_no,
                                                'uom_quantity' => $stockQtyBySdUom,
                                                'uom_id' =>  $requestToUpdateSaleDetail['uom_id'],
                                                'ref_uom_quantity' => $stockQtyByRef,
                                            ]);
                                        }
                                        $csbQuery->update([
                                            'current_quantity' => 0,
                                        ]);
                                        $qtyToRemoveByRefUom -= $stockQtyBySdUom;
                                    } else {
                                        $qtyToRemoveByRequestUpdateUom = UomHelper::changeQtyOnUom($requestToUpdateUomIdByRef,$requestToUpdateSaleDetail['uom_id'], $qtyToRemoveByRefUom);
                                        $leftStockQtyByRef = $stockQtyByRef - $qtyToRemoveByRefUom;

                                        $csbQuery = CurrentStockBalance::where('id', $stock->id)->first();
                                        $csbQuery->update([
                                            'current_quantity' => $leftStockQtyByRef,
                                        ]);


                                        $lotSerialDetails = lotSerialDetails::where('transaction_type', 'sale')->where('transaction_detail_id', $requestToUpdateSaleDetailId)
                                                            ->where('current_stock_balance_id', $stock['id']);


                                        if ($lotSerialDetails->exists()) {
                                            $lotSerialDetailUomQty = $lotSerialDetails->first()->uom_quantity;
                                            $lotSerialDetailUomQtyByRef = $lotSerialDetails->first()->ref_uom_quantity;
                                            $lotSerialDetails->update([
                                                'uom_quantity' => $lotSerialDetailUomQty + $qtyToRemoveByRequestUpdateUom,
                                                'ref_uom_quantity' => $lotSerialDetailUomQtyByRef + $qtyToRemoveByRefUom,
                                            ]);
                                        } else {
                                            lotSerialDetails::create([
                                                'transaction_type' => 'sale',
                                                'transaction_detail_id' =>  $requestToUpdateSaleDetailId,
                                                'current_stock_balance_id' => $stock->id,
                                                'lot_serial_numbers' => $stock->lot_serial_no,
                                                'uom_quantity' =>  $qtyToRemoveByRequestUpdateUom,
                                                'ref_uom_quantity' =>  $qtyToRemoveByRefUom,
                                                'uom_id' => $qtyToRemoveByRefUom,
                                            ]);
                                        }
                                        $qtyToRemoveByRefUom = 0;
                                        break;
                                    }
                                }
                            } elseif ($requestToUpdateQtyByRef <= $beforeUpdateSaleDetailQtyByRef) {
                                $qtyToReplaceByRefUom = $beforeUpdateSaleDetailQtyByRef- $requestToUpdateQtyByRef;

                                $lotSerialDetails = lotSerialDetails::where('transaction_type', 'sale')
                                                ->where('transaction_detail_id', $requestToUpdateSaleDetailId)->OrderBy('id', 'DESC')->get();
                                foreach ($lotSerialDetails as $bsd) {

                                    if ($qtyToReplaceByRefUom > $bsd->ref_uom_quantity) {
                                        lotSerialDetails::where('id', $bsd->id)->first()->delete();
                                        $current_stock = CurrentStockBalance::where('id', $bsd->current_stock_balance_id)->first();
                                        $current_stock->update([
                                            'current_quantity' =>  $current_stock->current_quantity + $bsd->ref_uom_quantity,
                                        ]);
                                        $qtyToReplaceByRefUom -= $bsd->ref_uom_quantity;
                                        if ($qtyToReplaceByRefUom <= 0) {
                                            break;
                                        }
                                    } elseif ($qtyToReplaceByRefUom <= $bsd->ref_uom_quantity) {

                                        $current_stock = CurrentStockBalance::where('id', $bsd->current_stock_balance_id)->first();
                                        $refUomQtyForLs= $bsd->ref_uom_quantity - $qtyToReplaceByRefUom;
                                        $qtyToReplaceByRequestUpdateUom=round(UomHelper::changeQtyOnUom($requestToUpdateUomIdByRef, $requestToUpdateSaleDetail['uom_id'], $refUomQtyForLs),4);

                                        // dd($requestToUpdateUomIdByRef, $requestToUpdateSaleDetail['uom_id'], $qtyToReplaceByRefUom);
                                        if ($qtyToReplaceByRequestUpdateUom == 0) {
                                            lotSerialDetails::where('id', $bsd->id)->first()->delete();
                                        } else {
                                            lotSerialDetails::where('id', $bsd->id)->first()->update([
                                                'uom_quantity' => $qtyToReplaceByRequestUpdateUom ,
                                                'ref_uom_quantity' => $refUomQtyForLs,
                                            ]);
                                        }
                                        $current_stock->update([
                                            'current_quantity' =>  $current_stock->current_quantity + $qtyToReplaceByRefUom,
                                        ]);
                                        $qtyToReplaceByRefUom = 0;
                                        break;
                                    }
                                }

                                // dd($saleDetailsByParent->toArray());
                            }
                            // dd('wh');
                        };
                    }

                    //
                    // updated Detail Item Data
                    //
                    $this->updateSaleDetailData($requestToUpdateSaleDetailId, $requestToUpdateSaleDetail, $businessLocationId);

                }
            }
            DB::commit();

            activity('sale-transaction')
            ->log('New Sale update has been success')
            ->event('update')
                ->status('success')
                ->properties([
                    'id' => $updatedSaleData->id,
                    'status' => $updatedSaleData->status,
                    'total_amount' => $updatedSaleData->sale_amount,
                    'payment_status' => $updatedSaleData->payment_status,
                ])
                ->save();
            if ($request->type == 'pos') {
                return response()->json([
                    'status' => '200',
                    'message' => 'successfully Updated'
                ], 200);
            } else {
                // return redirect()->back()->with(['success' => 'successfully updated']);
                return redirect()->route('all_sales', 'allSales')->with(['success' => 'successfully updated']);
            }
        } catch (Exception $e) {
            Db::rollBack();
            activity('sale-transaction')
                ->log('New Sale update has been fail')
                ->event('update')
                ->status('fail')
                ->save();
            return back()->with(['warning' => 'Something Went Wrong While update sale voucher']);
        }

    }

    public function txUpdateForPos($request,$saleBefUpdate,$saleData){
        if ($request->paid_amount > 0) {
            if ($request->type == 'pos') {
                $multiPayment = $request->multiPayment;
                foreach ($multiPayment as $mp) {
                    $data = [
                        'payment_voucher_no' => generatorHelpers::paymentVoucher(),
                        'payment_date' => now(),
                        'transaction_type' => 'sale',
                        'transaction_id' => $saleData->id,
                        'transaction_ref_no' => $saleData->sales_voucher_no,
                        'payment_method' => 'card',
                        'payment_account_id' => $mp['payment_account_id'] ?? null,
                        'payment_type' => 'debit',
                        'payment_amount' => $mp['payment_amount'],
                        'currency_id' => $saleData->currency_id,
                    ];
                    $paymentTransaction = paymentsTransactions::create($data);
                    $pRSQry = posRegisterTransactions::where('transaction_type', 'sale')->where('transaction_id', $saleData->id)->select('register_session_id')->first();
                    if ($pRSQry) {
                        $sessionId = $pRSQry->register_session_id;
                    }
                    posRegisterTransactions::create([
                        'register_session_id' => $sessionId ?? null,
                        'payment_account_id' => $mp['payment_account_id'] ?? null,
                        'transaction_type' => 'sale',
                        'transaction_id' => $saleData->id,
                        'transaction_amount' =>  $mp['payment_amount'],
                        'currency_id' => $request->currency_id,
                        'payment_transaction_id' => $paymentTransaction->id ?? null,
                    ]);

                    $suppliers = Contact::where('id', $saleData->contact_id)->first();
                    $suppliers_receivable = $suppliers->receivable_amount;
                    $suppliers->update([
                        'receivable_amount' => ($suppliers_receivable - $saleBefUpdate->balance_amount) +  $saleBefUpdate->balance_amount
                    ]);
                }
                $suppliers = Contact::where('id', $saleBefUpdate->contact_id)->first();
                $suppliers_receivable = $suppliers->receivable_amount;
                $suppliers->update([
                    'receivable_amount' => ($suppliers_receivable - $saleBefUpdate->balance_amount) + $request->balance_amount,
                ]);
            }
        }
    }

    public function removeSaleDetialsData($id, $requestToUpdateSaleDetails)
    {
        // dd($requestToUpdateSaleDetails);
        $requestToUpdateSaleDetails = array_column($requestToUpdateSaleDetails, 'sale_detail_id');
        //get added sale_details_ids from database
        $fetch_sale_details = sale_details::where('sales_id', $id)->where('is_delete', 0)->select('id')->get()->toArray();
        $get_fetched_sale_details_id = array_column($fetch_sale_details, 'id');
        //to remove edited sale_detais that are already created
        $idToRemoveSaleDetails = array_diff($get_fetched_sale_details_id, $requestToUpdateSaleDetails); //for delete row

        foreach ($idToRemoveSaleDetails as $sale_detail_id) {

            $sale_details = sale_details::where('id', $sale_detail_id)->where('is_delete', 0);
            $sale_details_count = count($sale_details->get()->toArray());
            if ($sale_details_count > 0) {
                $get_sale_details = $sale_details->first();
                $lotSerials = lotSerialDetails::where('transaction_type', 'sale')->where('transaction_detail_id', $get_sale_details->id);

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
    }
    public function updateSaleDetailData($requestToUpdateSaleDetailId,$requestToUpdateSaleDetail, $businessLocationId){
        $sale_details = sale_details::where('id', $requestToUpdateSaleDetailId)->with('kitSaleDetails')->where('is_delete', 0)->first();
        // smallest qty from client
        $refUomInfoForSd = UomHelper::getReferenceUomInfoByCurrentUnitQty($requestToUpdateSaleDetail['quantity'], $requestToUpdateSaleDetail['uom_id']);
        $requestQtyByRef = $refUomInfoForSd['qtyByReferenceUom'];

        $prepareRequestToUpdateSaleDetail = [
            'uom_id' => $requestToUpdateSaleDetail['uom_id'],
            'quantity' => $requestToUpdateSaleDetail['quantity'],
            'uom_price' => $requestToUpdateSaleDetail['uom_price'],
            'subtotal' =>  $requestToUpdateSaleDetail['subtotal'],
            'discount_type' => $requestToUpdateSaleDetail['discount_type'],
            'per_item_discount' => $requestToUpdateSaleDetail['per_item_discount'],
            'subtotal_with_discount' => $requestToUpdateSaleDetail['subtotal'] - ($requestToUpdateSaleDetail['line_subtotal_discount'] ?? 0),
            'currency_id' => $sale_details['currency_id'],
            'price_list_id' => $requestToUpdateSaleDetail['price_list_id'] == "default_selling_price" ? null :   $requestToUpdateSaleDetail['price_list_id'],
            'subtotal_with_tax' => $requestToUpdateSaleDetail['subtotal'] - ($requestToUpdateSaleDetail['line_subtotal_discount'] ?? 0),
            'note' => $requestToUpdateSaleDetail['item_detail_note'] ?? null,
            'updated_by'=> Auth::user()->id,
        ];
        if (hasModule('ComboKit') && isEnableModule('ComboKit')) {
            RoMService::updateRomTransactions(
                $requestToUpdateSaleDetailId,
                'kit_sale_detail',
                $businessLocationId,
                $requestToUpdateSaleDetail['product_id'],
                $requestToUpdateSaleDetail['variation_id'],
                $requestToUpdateSaleDetail['quantity'],
                $requestToUpdateSaleDetail['uom_id'],
                $sale_details['quantity'],
                $sale_details['uom_id']
            );
        }
        $packagingService = new packagingServices();
        $packagingService->updatePackagingForTx($requestToUpdateSaleDetail, $requestToUpdateSaleDetailId, 'sale');
        if ($requestToUpdateSaleDetail['quantity'] <= 0) {
            $prepareRequestToUpdateSaleDetail['is_delete']  = 1;
            $prepareRequestToUpdateSaleDetail['deleted_at']  = now();
            $prepareRequestToUpdateSaleDetail['is_delete']  = Auth::user()->id;
            $sale_details->update($prepareRequestToUpdateSaleDetail);
        } else {
            $sale_details->update($prepareRequestToUpdateSaleDetail);
        };

        if ($requestQtyByRef > 0) {
            stock_history::where('transaction_details_id', $requestToUpdateSaleDetailId)->where('transaction_type', 'sale')->update([
                'decrease_qty' => $requestQtyByRef,
                'created_at' => $sales['sold_at'] ?? now(),
            ]);
        } else {
            stock_history::where('transaction_details_id', $requestToUpdateSaleDetailId)->where('transaction_type', 'sale')->delete();
        }
        return [
            'preapredSaleDetailData'=> $requestToUpdateSaleDetail,
            'updatedSaleDetailData'=> $sale_details
        ];
    }


    public function update($id, Request $request, SaleServices $saleService)
    {
        $request_sale_details = $request->sale_details;
        // $lot_control = $this->setting->lot_control;

        // I fetch  sales data ,one for store as old data that fetch form database and one is to update data and after updated ,if you call slaes the will be updated!!
        $saleBeforeUpdate = sales::where('id', $id)->first();
        $sales = sales::where('id', $id)->first();
        DB::beginTransaction();
        if ($request->type == 'pos') {
            $registeredPos = posRegisters::where('id', $request->pos_register_id)->select('id', 'payment_account_id', 'use_for_res')->first();
        }
        try {
            $saleData = [
                'contact_id' => $request->contact_id,
                'status' => $request->status,
                'sale_amount' => $request->sale_amount,
                'total_item_discount' => $request->total_item_discount,
                'extra_discount_type' => $request->extra_discount_type,
                'extra_discount_amount' => $request->extra_discount_amount,
                'total_sale_amount' => $request->total_sale_amount,
                'paid_amount' => $saleBeforeUpdate->paid_amount,
                'balance_amount' => $request->total_sale_amount - $saleBeforeUpdate->paid_amount,
                'currency_id' => $request->currency_id,
                'updated_by' => Auth::user()->id,
                'sold_at' => $request->sold_at,
            ];
            if ($request->type == 'pos') {
                $saleData['paid_amount'] = $request->paid_amount;
                $saleData['balance_amount'] = $request->balance_amount;
            }
            $sales->update($saleData);
            if ($request->paid_amount > 0) {
                if ($request->type == 'pos') {
                    $multiPayment = $request->multiPayment;
                    foreach ($multiPayment as $mp) {
                        $data = [
                            'payment_voucher_no' => generatorHelpers::paymentVoucher(),
                            'payment_date' => now(),
                            'transaction_type' => 'sale',
                            'transaction_id' => $sales->id,
                            'transaction_ref_no' => $sales->sales_voucher_no,
                            'payment_method' => 'card',
                            'payment_account_id' => $mp['payment_account_id'] ?? null,
                            'payment_type' => 'debit',
                            'payment_amount' => $mp['payment_amount'],
                            'currency_id' => $sales->currency_id,
                        ];
                        $paymentTransaction = paymentsTransactions::create($data);
                        $pRSQry = posRegisterTransactions::where('transaction_type', 'sale')->where('transaction_id', $sales->id)->select('register_session_id')->first();
                        if ($pRSQry) {
                            $sessionId = $pRSQry->register_session_id;
                        }
                        posRegisterTransactions::create([
                            'register_session_id' => $sessionId ?? null,
                            'payment_account_id' => $mp['payment_account_id'] ?? null,
                            'transaction_type' => 'sale',
                            'transaction_id' => $sales->id,
                            'transaction_amount' =>  $mp['payment_amount'],
                            'currency_id' => $request->currency_id,
                            'payment_transaction_id' => $paymentTransaction->id ?? null,
                        ]);

                        $suppliers = Contact::where('id', $sales->contact_id)->first();
                        $suppliers_receivable = $suppliers->receivable_amount;
                        $suppliers->update([
                            'receivable_amount' => ($suppliers_receivable - $saleBeforeUpdate->balance_amount) +  $sales->balance_amount
                        ]);
                    }
                    $suppliers = Contact::where('id', $sales->contact_id)->first();
                    $suppliers_receivable = $suppliers->receivable_amount;
                    $suppliers->update([
                        'receivable_amount' => ($suppliers_receivable - $sales->balance_amount) + $request->balance_amount,
                    ]);
                }
            }
            // $this->changeTransaction($saleBeforeUpdate, $sales, $request);
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

                    if (count($request_old_sale) <= 1) {
                        continue;
                    };

                    $sale_detail_id = $request_old_sale['sale_detail_id'];
                    unset($request_old_sale["sale_detail_id"]);
                    $request_old_sale['updated_by'] = Auth::user()->id;

                    $sale_details = sale_details::where('id', $sale_detail_id)->with('kitSaleDetails')->where('is_delete', 0)->first();
                    $request_old_sale['id'] = $sale_details->id;


                    //get old sale_detail qty from db
                    // dd($sale_details->toArray());
                    $sale_detial_qty_from_db = UomHelper::getReferenceUomInfoByCurrentUnitQty($sale_details->quantity, $sale_details->uom_id)['qtyByReferenceUom'];

                    // smallest qty from client
                    $UoMHelper = UomHelper::getReferenceUomInfoByCurrentUnitQty($request_old_sale['quantity'], $request_old_sale['uom_id']);
                    $requestQty = $UoMHelper['qtyByReferenceUom'];
                    $refUoMId = $UoMHelper['referenceUomId'];


                    $dif_sale_qty = $requestQty - $sale_detial_qty_from_db;
                    // dd($dif_sale_qty);

                    $lotSerialCheck = lotSerialDetails::where('transaction_type', 'sale')->where('transaction_detail_id', $sale_details->id)->exists();
                    $businessLocation = businessLocation::where('id', $request->business_location_id)->first();

                    $product = Product::where('id', $request_old_sale['product_id'])->select('product_type', 'id', 'uom_id')->first();

                    if ($product->product_type == 'storable') {
                        // stock adjustment
                        if ($saleBeforeUpdate->status != 'delivered' && $request->status == "delivered" && !$lotSerialCheck && $businessLocation->allow_sale_order == 0) {
                            $changeQtyStatus = $saleService->changeStockQty($requestQty, $refUoMId, $request->business_location_id, $request_old_sale,[], $sales);
                            if ($changeQtyStatus == false) {
                                return back()->with(['error' => "product Out of Stock"]);
                            } else {
                                // if ($this->setting->lot_control == "off") {
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
                                // }
                            }
                        } elseif ($saleBeforeUpdate->status == 'delivered' && $request->status != "delivered" && $businessLocation->allow_sale_order == 0) {
                            $lotSerials = lotSerialDetails::where('transaction_type', 'sale')->where('transaction_detail_id', $sale_details->id);
                            stock_history::where('transaction_details_id', $sale_detail_id)->where('transaction_type', 'sale')->delete();
                            if ($lotSerials->exists()) {
                                $this->adjustStock($lotSerials->get());
                                foreach ($lotSerials->get() as $lotSerial) {
                                    $lotSerial->delete();
                                }
                            };
                        } else {
                            $referecneQty = UomHelper::getReferenceUomInfoByCurrentUnitQty($sale_details->quantity, $sale_details->uom_id)['qtyByReferenceUom'];
                            if ($request_old_sale['quantity'] > $sale_details->quantity) {
                                $locationIds = childLocationIDs($businessLocation->id);
                                $current_stock = CurrentStockBalance::where('product_id', $sale_details->product_id)
                                    ->whereIn('business_location_id', $locationIds)
                                    ->where('variation_id', $sale_details->variation_id)
                                    ->where('current_quantity', '>', '0');
                                $availableStocks = $current_stock->get();
                                $availableQty = $current_stock->sum('current_quantity');
                                $newQty = round($requestQty - $sale_detial_qty_from_db, 2);

                                if ($newQty > $availableQty) {
                                    return redirect()->route('all_sales', 'allSales')->with(['warning' => 'out of stock']);
                                }
                                $qtyToRemove = $request_old_sale['quantity'] - $sale_details->quantity;

                                foreach ($availableStocks as $stock) {
                                    $stockQty = round(UomHelper::changeQtyOnUom($stock->ref_uom_id, $sale_details->uom_id, $stock->current_quantity), 2);
                                    // $stockQty = UomHelper::getReferenceUomInfoByCurrentUnitQty( $stock->current_quantity, $stock->ref_uom_id)['qtyByReferenceUom'];
                                    // dd($qtyToRemove , $stockQty);
                                    if ($qtyToRemove >= $stockQty) {
                                        CurrentStockBalance::where('id', $stock->id)->first()->update([
                                            'current_quantity' => 0,
                                        ]);

                                        $lotSerialDetailsByStock = lotSerialDetails::where('transaction_type', 'sale')->where('transaction_detail_id', $sale_details['id'])->where('current_stock_balance_id', $stock->id);
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
                                        $stock_for_update = CurrentStockBalance::where('id', $stock->id)->first();
                                        $smallest_leftStockQty = UomHelper::getReferenceUomInfoByCurrentUnitQty($leftStockQty, $sale_details->uom_id)['qtyByReferenceUom'];
                                        $stock_for_update->update([
                                            'current_quantity' => $smallest_leftStockQty,
                                        ]);

                                        $lotSerialDetails = lotSerialDetails::where('transaction_type', 'sale')->where('transaction_detail_id', $sale_detail_id)->where('current_stock_balance_id', $stock->id);
                                        if ($lotSerialDetails->exists()) {
                                            $sale_detial_qty = $lotSerialDetails->first()->uom_quantity;
                                            $lotSerialDetails->update([
                                                'uom_quantity' => $sale_detial_qty + $qtyToRemove,
                                            ]);
                                        } else {
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
                            } elseif ($request_old_sale['quantity'] < $sale_details->quantity) {
                                // dd('junk');
                                $qty_to_replace = $sale_details->quantity - $request_old_sale['quantity'];
                                $lotSerialDetails = lotSerialDetails::where('transaction_type', 'sale')->where('transaction_detail_id', $sale_details->id)->OrderBy('id', 'DESC')->get();
                                foreach ($lotSerialDetails as $bsd) {
                                    // dd($bsd->toArray(), $qty_to_replace, $bsd->uom_quantity);
                                    if ($qty_to_replace > $bsd->uom_quantity) {
                                        lotSerialDetails::where('id', $bsd->id)->first()->delete();
                                        $referecneQty = UomHelper::getReferenceUomInfoByCurrentUnitQty($bsd->uom_quantity, $bsd->uom_id)['qtyByReferenceUom'];
                                        $current_stock = CurrentStockBalance::where('id', $bsd->current_stock_balance_id)->first();
                                        $current_stock->update([
                                            'current_quantity' =>  $current_stock->current_quantity + $referecneQty,
                                        ]);
                                        $qty_to_replace -= $bsd->uom_quantity;
                                        if ($qty_to_replace <= 0) {
                                            break;
                                        }
                                    } elseif ($qty_to_replace <= $bsd->uom_quantity) {
                                        $referenceUomToReplace = (int) UomHelper::getReferenceUomInfoByCurrentUnitQty($qty_to_replace, $bsd->uom_id)['qtyByReferenceUom'];
                                        $current_stock = CurrentStockBalance::where('id', $bsd->current_stock_balance_id)->first();
                                        $resultForBsd = $bsd->uom_quantity - $qty_to_replace;
                                        if ($resultForBsd == 0) {
                                            lotSerialDetails::where('id', $bsd->id)->first()->delete();
                                        } else {
                                            lotSerialDetails::where('id', $bsd->id)->first()->update([
                                                'uom_quantity' => $resultForBsd,
                                            ]);
                                        }
                                        $current_stock->update([
                                            'current_quantity' =>  $current_stock->current_quantity + $referenceUomToReplace,
                                        ]);
                                        $qty_to_replace = 0;
                                        break;
                                    }
                                }

                                // dd($saleDetailsByParent->toArray());
                            }
                        };
                    }
                    // dd('d');
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
                        'price_list_id' => $request_old_sale['price_list_id'] == "default_selling_price" ? null :   $request_old_sale['price_list_id'],
                        'updated_by' => $request_old_sale['updated_by'],
                        'subtotal_with_tax' => $request_old_sale['subtotal'] - ($request_old_sale['line_subtotal_discount'] ?? 0),
                        'note' => $request_old_sale['item_detail_note'] ?? null,
                    ];
                    // dd($request_sale_details_data);
                    // updateQTy
                    // $requestQty = $request_sale_details_data['quantity'];
                    // if ($request_sale_details_data['uom_id'] != $product['uom_id']) {
                    //     $requestQty = UomHelper::changeQtyOnUom($request_sale_details_data['uom_id'], $product['uom_id'], $requestQty);
                    // };

                    //oldQty
                    // $quantity = $sale_details['quantity'];
                    // if ($sale_details['uom_id'] != $product['uom_id']) {
                    //     $quantity = UomHelper::changeQtyOnUom($sale_details['uom_id'], $product['uom_id'], $sale_details['quantity']);
                    // };
                    if (hasModule('ComboKit') && isEnableModule('ComboKit')) {
                        RoMService::updateRomTransactions(
                            $sale_details->id,
                            'kit_sale_detail',
                            $sales->business_location_id,
                            $request_old_sale['product_id'],
                            $request_old_sale['variation_id'],
                            $request_sale_details_data['quantity'],
                            $request_sale_details_data['uom_id'],
                            $sale_details['quantity'],
                            $sale_details['uom_id']
                        );
                    }
                    $packagingService = new packagingServices();
                    $packagingService->updatePackagingForTx($request_old_sale, $sale_details->id, 'sale');
                    if ($request_old_sale['quantity'] <= 0) {
                        $request_sale_details_data['is_delete']  = 1;
                        $request_sale_details_data['deleted_at']  = now();
                        $request_sale_details_data['is_delete']  = Auth::user()->id;
                        $sale_details->update($request_sale_details_data);
                    } else {
                        $sale_details->update($request_sale_details_data);
                    };

                    if ($requestQty > 0) {
                        stock_history::where('transaction_details_id', $sale_detail_id)->where('transaction_type', 'sale')->update([
                            'decrease_qty' => $requestQty,
                            'created_at' => $sales['sold_at'] ?? now(),
                        ]);
                    } else {
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
                    $sale_details_count = count($sale_details->get()->toArray());
                    if ($sale_details_count > 0) {
                        $get_sale_details = $sale_details->first();
                        $lotSerials = lotSerialDetails::where('transaction_type', 'sale')->where('transaction_detail_id', $get_sale_details->id);

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
                    $resOrderData = null;
                    if ($request->type == 'pos' && $registeredPos->use_for_res == 1) {
                        $resOrderData = $this->resOrderCreation($sales, $request);
                        $saleService->saleDetailCreation($request, $sales, $new_sale_details, $resOrderData);
                    } else {
                        $saleService->saleDetailCreation($request, $sales, $new_sale_details, $resOrderData);
                    }
                }
            } else {
                $saleDetailQuery = sale_details::where('sales_id', $id);
                $allSaleDetailIdToRemove = $saleDetailQuery->get();
                foreach ($allSaleDetailIdToRemove as   $sd) {
                    // dd($sd['product_id']);
                    $romCheck = '';
                    if (hasModule('ComboKit') && isEnableModule('ComboKit')) {
                        $romCheck = RoMService::isKit($sd['product_id']);
                    }
                    if ($romCheck == 'kit') {
                        RoMService::removeRomTransactions($sd->id, 'kit_sale_detail');
                    } else {
                        $lotSerials = lotSerialDetails::where('transaction_type', 'sale')->where('transaction_detail_id', $sd->id);
                        if ($lotSerials->exists()) {
                            $this->adjustStock($lotSerials->get());
                            foreach ($lotSerials->get() as $lotSerial) {
                                $lotSerial->delete();
                            }
                        };
                    }
                }

                stock_history::where('transaction_details_id', $id)->where('transaction_type', 'sale')->delete();
                $saleDetailQuery->update([
                    'is_delete' => 1,
                    'deleted_at' => now(),
                    'deleted_by' => Auth::user()->id,
                ]);
                sales::where('id', $id)->update([
                    'is_delete' => 1,
                    'deleted_at' => now(),
                    'deleted_by' => Auth::user()->id,
                ]);
            }


            DB::commit();
            activity('sale-transaction')
                ->log('New Sale update has been success')
                ->event('update')
                ->status('success')
                ->properties([
                    'id'=> $sales->id,
                    'status' => $sales->status,
                    'total_amount' => $sales->sale_amount,
                    'payment_status' => $sales->payment_status,
                ])
                ->save();
        } catch (Exception $e) {

            DB::rollBack();

            logger($e);
            activity('sale-transaction')
                ->log('New Sale update has been fail')
                ->event('update')
                ->status('fail')
                ->save();
            return back()->with(['warning' => 'Something Went Wrong While update sale voucher']);
        }
        // dd($request->toArray());
        if ($request->type == 'pos') {
            return response()->json([
                'status' => '200',
                'message' => 'successfully Updated'
            ], 200);
        } else {
            return redirect()->route('all_sales', 'allSales')->with(['success' => 'successfully updated']);
        }
    }


    public function softDelete($id)
    {
        try {
            DB::beginTransaction();
            $this->softDeletion($id);
            $data = [
                'success' => 'Successfully Deleted'
            ];
            DB::commit();
            activity('sale-transaction')
                ->log('Sale single deletion has been success')
                ->autoEvent()
                ->status('success')
                ->save();

            return response()->json($data, 200);
        }catch (Exception $exception){
            DB::rollBack();
            activity('sale-transaction')
                ->log('Sale single deletion has been fail')
                ->event('delete')
                ->status('fail')
                ->save();

            return response()->json($exception->getMessage(), 200);
        }
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
            activity('sale-transaction')
                ->log('Sale multi-select deletion has been success')
                ->event('delete')
                ->status('success')
                ->save();
            return response()->json($data, 200);
        } catch (Exception $e) {
            DB::rollBack();
            activity('sale-transaction')
                ->log('Sale multi-select deletion has been fail')
                ->event('delete')
                ->status('fail')
                ->save();
            throw $e;
            return response()->json($e, 200);
        }
    }

    private function softDeletion($id)
    {
        $saleDetailQuery = sale_details::where('sales_id', $id);
        $allSaleDetailIdToRemove = $saleDetailQuery->get();


        foreach ($allSaleDetailIdToRemove as   $sd) {
            $romCheck = '';
            if (hasModule('ComboKit') && isEnableModule('ComboKit')) {
                $romCheck = RoMService::isKit($sd['product_id']);
            }
            if ($romCheck == 'kit') {
                RoMService::removeRomTransactions($sd->id, 'kit_sale_detail');
            } else {
                $lotSerials = lotSerialDetails::where('transaction_type', 'sale')->where('transaction_detail_id', $sd->id)->OrderBy('id', 'DESC');
                if ($lotSerials->exists()) {
                    $this->adjustStock($lotSerials->get());
                    foreach ($lotSerials->get() as $lotSerial) {
                        $lotSerial->delete();
                    }
                };
            }
            stock_history::where('transaction_details_id', $sd->id)->where('transaction_type', 'sale')->delete();
        }
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
        $variation_id = $request->data['variation_id'] ?? null;

        $products = Product::where('can_sale', 1)
            ->whereNull('deleted_at')
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', '%' . $q . '%')
                    ->orWhere('sku', 'like', '%' . $q . '%');
            })
            ->with([
                'productVariations' => function ($query) use ($variation_id) {
                    $query->select('id', 'product_id', 'variation_template_value_id', 'default_purchase_price', 'default_selling_price')
                        ->when($variation_id, function ($query) use ($variation_id) {
                            $query->where('id', $variation_id);
                        })
                        ->with([
                            'packaging' => function ($q) {
                                $q->where('for_purchase', 1);
                            },
                            'variationTemplateValue:id,name', 'additionalProduct.productVariation.product', 'additionalProduct.uom', 'additionalProduct.productVariation.variationTemplateValue'
                        ]);
                },
                'stock' => function ($query) use ($business_location_id) {
                    $locationIds = childLocationIDs($business_location_id);
                    $query->where('current_quantity', '>', 0)
                        ->whereIn('business_location_id', $locationIds);
                },
                'uom.unit_category.uomByCategory'
            ])
            ->withSum(['stock' => function ($query) use ($business_location_id) {
                $locationIds = childLocationIDs($business_location_id);
                $query->whereIn('business_location_id', $locationIds);
            }], 'current_quantity')
            ->get();

        $result = [];

        foreach ($products as $product) {
            $batch_nos = [];
            foreach ($product->stock as $stock) {
                $no = $stock['batch_no'];
                $lot_id = $stock['id'];
                $batch_nos[] = ['id' => $lot_id, 'no' => $no];
            }

            $result[] = [
                'id' => $product->id,
                'name' => $product->name,
                'product_code' => $product->product_code,
                'category_id' => $product->category_id,
                'sku' => $product->sku,
                'has_variation' => $product->has_variation,
                'product_type' => $product->product_type,
                'uom_id' => $product->uom_id,
                'uom' => $product->uom,
                'purchase_uom_id' => $product->purchase_uom_id,
                'product_variations' => $product->has_variation == 'single' ? $product->productVariations->toArray()[0] : $product->productVariations->toArray(),
                'total_current_stock_qty' => $product->stock_sum_current_quantity ?? 0,
                'batch_nos' => $batch_nos,
                'stock' => $product->stock->toArray(),
            ];

            if ($product->has_variation == 'variable') {
                foreach ($product->productVariations as $variation) {
                    $batch_nos = [];
                    $reference_uom_id = [];
                    $total_current_stock_qty = 0;
                    $stocks = array_filter($product->stock->toArray(), function ($s) use ($variation) {
                        return $s['variation_id'] == $variation['id'] && $s['current_quantity'] > 0;
                    });
                    foreach ($stocks as $stock) {
                        $total_current_stock_qty += $stock['current_quantity'];
                        $no = $stock['batch_no'];
                        $lot_id = $stock['id'];
                        $batch_nos[] = ['no' => $no, 'id' => $lot_id];
                        $reference_uom_id = $stock['ref_uom_id'];
                    }
                    $variation_product = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'total_current_stock_qty' => $total_current_stock_qty,
                        'stock' => $stocks,
                        'uom_id' => $reference_uom_id,
                        'product_variations' => $variation,
                        'uom_id' => $product->uom_id,
                        'uom' => $product->uom,
                        'batch_nos' => $batch_nos,
                        'variation_id' => $variation['id'],
                        'category_id' => $product->category_id,
                        'has_variation' => 'sub_variable',
                        'product_type' => $product->product_type,
                        'variation_name' => $variation['variationTemplateValue']['name'],
                    ];
                    $result[] = $variation_product;
                }
            }
        }

        return response()->json($result, 200);
    }
    public function getProductV2(Request $request)
    {
        $business_location_id = $request->data['business_location_id'];
        $q = $request->data['query'];
        $variation_id = $request->data['variation_id'] ?? null;
        $relations = [
            'product_packaging' => function ($query) use ($q) {
                $query->where('package_barcode', $q);
            },
            'uom',
            'uom.unit_category.uomByCategory',
            'product_variations.packaging.uom',
            'product_variations.additionalProduct.productVariation.product',
            'product_variations.additionalProduct.uom',
            'product_variations.additionalProduct.productVariation.variationTemplateValue',
            'stock' => function ($query) use ($business_location_id) {
                $locationIds = childLocationIDs($business_location_id);
                $query->where('current_quantity', '>', 0)
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
        $products = Product::select(
            'products.*',
            'product_variations.*',
            'variation_template_values.*',
            'variation_template_values.name as variation_name',
            'products.name as name',
            'products.id as id',
            'product_variations.id as variation_id',
            'variation_template_values.id as variation_template_values_id'
        )->leftJoin('product_variations', 'products.id', '=', 'product_variations.product_id')
            ->leftJoin('variation_template_values', 'product_variations.variation_template_value_id', '=', 'variation_template_values.id')
            ->where(function ($query) use ($q) {
                $query->where('can_sale', 1)
                    ->where('products.name', 'like', '%' . $q . '%')
                    ->orWhere('products.sku', 'like', '%' . $q . '%')
                    ->whereNull('products.deleted_at')
                    ->orWhere('variation_sku', 'like', '%' . $q . '%')
                    ->orWhereHas('varPackaging', function ($query) use ($q) {
                        $query->where('package_barcode', $q);
                    });
            })
            ->when($variation_id, function ($query) use ($variation_id) {
                $query->where('product_variations.id', $variation_id);
            })
            ->with($relations)
            ->withSum(['stock' => function ($query) use ($business_location_id) {
                $locationIds = childLocationIDs($business_location_id);
                $query->whereIn('business_location_id', $locationIds);
            }], 'current_quantity')
            ->get()->toArray();
        return response()->json($products, 200);
    }
    public function getProductV3ef(Request $request)
    {
        $data = $request->data;
        $business_location_id = $data['business_location_id'];
        $keyword = $data['query'];
        $variation_id = $data['variation_id'] ?? null;
        $psku_kw = $data['psku_kw'] ?? false;
        $vsku_kw = $data['vsku_kw'] ?? false;
        $pgbc_kw = $data['pgbc_kw'] ?? false;

        $products = Product::select(
            'products.name as name',
            'products.id as id',
            'products.product_code',
            'products.sku',
            'products.product_type',
            'products.has_variation',
            'products.uom_id',

            'product_variations.product_id',
            'product_variations.variation_sku',
            'product_variations.variation_template_value_id',

            'variation_template_values.variation_template_id',
            'variation_template_values.name as variation_name',
            'variation_template_values.id as variation_template_values_id'
        )->whereNull('products.deleted_at')
            ->leftJoin('product_variations', 'products.id', '=', 'product_variations.product_id')
            ->leftJoin('variation_template_values', 'product_variations.variation_template_value_id', '=', 'variation_template_values.id')
            ->where(function ($query) use ($keyword, $psku_kw, $vsku_kw, $pgbc_kw) {
                $query
                    ->where('products.can_sale', 1)
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
            ->withSum(['stock' => function ($query) use ($business_location_id) {
                $locationIds = childLocationIDs($business_location_id);
                $query->whereIn('business_location_id', $locationIds);
            }], 'current_quantity')->paginate(10);
        // ->get()->toArray();
        // dd($products);
        return response()->json($products, 200);
    }
    public function getProductV3(Request $request)
    {

        $data = $request->data;
        $business_location_id = $data['business_location_id'];
        $keyword = $data['query'];
        $variation_id = $data['variation_id'] ?? null;
        $psku_kw = $data['psku_kw'] ?? false;
        $vsku_kw = $data['vsku_kw'] ?? false;
        $pgbc_kw = $data['pgbc_kw'] ?? false;
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
            'stock' => function ($query) use ($business_location_id) {
                $locationIds = childLocationIDs($business_location_id);
                $query->select('current_quantity', 'business_location_id', 'product_id', 'id')
                    ->where('current_quantity', '>', 0)
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
        $products = Product::select(
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
            ->withSum(['stock' => function ($query) use ($business_location_id) {
                $locationIds = childLocationIDs($business_location_id);
                $query->whereIn('business_location_id', $locationIds);
            }], 'current_quantity')
            ->get()->toArray();
            // dd($products);
        return response()->json($products, 200);
    }
    public function getSuggestionProduct(Request $request)
    {
        $locationId = $request['locationId'];
        $productId = $request['productId'];
        $variationId = $request['variationId'];
        $product = Product::where('id', $productId)->with('uom.unit_category.uomByCategory')->first();
        $variation = ProductVariation::where('id', $variationId)
            ->where('product_id', $productId)
            ->with(
                'packaging.uom',
                'additionalProduct.productVariation.product',
                'additionalProduct.uom',
                'additionalProduct.productVariation.variationTemplateValue',
                'variationTemplateValue'
            )
            ->first();
        $stockQuery = CurrentStockBalance::where('variation_id', $variationId)->where('current_quantity', '>', 0)
            ->where('business_location_id', $locationId);
        $total_current_stock_qty = $stockQuery->sum('current_quantity');
        $stocks = $stockQuery->get();
        $batch_nos = [];
        foreach ($stocks as $stock) {
            $no = $stock['batch_no'];
            $lot_id = $stock['id'];
            $batch_nos[] = ['id' => $lot_id, 'no' => $no];
        }
        $result = [
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'total_current_stock_qty' => $total_current_stock_qty,
            'stock_sum_current_quantity' => $total_current_stock_qty,
            'variation_name' => $variation['variationTemplateValue'] ? $variation['variationTemplateValue']['name'] : '',
            'stock' => $stocks,
            'uom_id' => $product->uom_id,
            'product_type' => $product->product_type,
            'product_variations' => $variation,
            'uom_id' => $product->uom_id,
            'uom' => $product->uom,
            'batch_nos' => $batch_nos,
            'variation_id' => $variation['id'],
            'category_id' => $product->category_id,
            'has_variation' => 'sub_variable',
            'product_type' => $product->product_type,
        ];
        // dd($result);
        return response()->json($result, 200);
    }

    public function saleInvoice($id)
    {
        $sale = sales::with('sold_by', 'sold', 'confirm_by', 'customer', 'updated_by', 'currency')->where('id', $id)->first();
        // dd($sale->toArray());

        $location = businessLocation::where('id', $sale['business_location_id'])->first();
        $address = $location->locationAddress;

        $sale_details = sale_details::with(['productVariation' => function ($q) {
            $q->select('id', 'product_id', 'variation_template_value_id')
                ->with([
                    'product' => function ($q) {
                        $q->select('id', 'name', 'has_variation');
                    },
                    'variationTemplateValue' => function ($q) {
                        $q->select('id', 'name');
                    }
                ]);
        }, 'product', 'uom', 'currency'])->where('sales_id', $id)->where('is_delete', 0)->get();

        $layout = InvoiceTemplate::find($location->invoice_layout);
        $type = "sale";
        if (!$layout) {
            $invoiceHtml = view('App.sell.print.saleInvoice3', compact('sale', 'location', 'sale_details', 'address', 'layout'))->render();
        } else if ($layout->layout  == "80mm") {

            $table_text = json_decode($layout->table_text);
            $data_text = json_decode($layout->data_text);
            // dd($data_text);
            // dd($sale_details->toArray());
            // $invoiceHtml = view('App.sell.print.pos.80mmLayout', compact('sale', 'location', 'sale_details','address', 'table_text', 'data_text', 'layout'))->render();
            $invoiceHtml = view('App.sell.print.pos.80mmFixLayout', compact('sale', 'location', 'sale_details', 'address', 'table_text', 'data_text', 'layout'))->render();
        } else {
            $table_text = json_decode($layout->table_text);
            $data_text = json_decode($layout->data_text);
            $invoiceHtml = view('components.invoice.sell-layout', compact('sale', 'sale_details', 'table_text', 'data_text', 'location', 'table_text', 'address', 'layout', 'type'))->render();
        }
        // return response()->json(['html' => $invoiceHtml]);
        return response()->json(['html' => mb_convert_encoding($invoiceHtml, 'UTF-8', 'UTF-8')]);
    }


    // stock out
    private function adjustStock($lotSerials)
    {
        foreach ($lotSerials as $lotSerial) {
            $sale_detail_qty = UomHelper::getReferenceUomInfoByCurrentUnitQty($lotSerial->uom_quantity, $lotSerial->uom_id)['qtyByReferenceUom'];

            $currentStock = CurrentStockBalance::where('id', $lotSerial->current_stock_balance_id);
            if($currentStock->exists()){
                $current_stock_qty = $currentStock->get()->first()->current_quantity;
                $result = $current_stock_qty + $sale_detail_qty;
                $currentStock->update(['current_quantity' => $result]);
            }
        }
    }



    public function postToRegistrationFolio($id)
    {
        if (hasModule('HospitalManagement') && isEnableModule('HospitalManagement')) {
            $folioDetailQuery = hospitalFolioInvoiceDetails::where('transaction_type', 'sale')
                ->where('transaction_id', $id);
            $checkFolioDetails = $folioDetailQuery->exists();
            $postedRegistration = [];
            if ($checkFolioDetails) {
                $folioDetail = $folioDetailQuery->select('id', 'folio_invoice_id')->get()->first();
                $registrationId = hospitalFolioInvoices::where('id', $folioDetail->folio_invoice_id)->select('registration_id')->first()->registration_id;
                $postedRegistration = hospitalRegistrations::where('id', $registrationId)->get()->first();
            }
            $SaleVoucher = sales::where('id', $id)->select('id', 'sales_voucher_no')->first();
            $registrations = hospitalRegistrations::with('patient')->where('is_delete', 0)->get();
        }

        return view('App.sell.modal.joinToRegistrationFolioModal', compact('registrations', 'SaleVoucher', 'postedRegistration'));
    }
    public function addToRegistrationFolio(Request $request)
    {
        try {


            $sale_id = $request->sale_id;
            $registration_id = $request->registration_id;

            $folioDetailQuery = hospitalFolioInvoiceDetails::where('transaction_type', 'sale')
                ->where('transaction_id', $sale_id);
            $checkFolioDetails = $folioDetailQuery->exists();
            if ($checkFolioDetails) {
                $folio = hospitalFolioInvoices::where('registration_id', $registration_id)->select('id')->first();
                $oldFolioDetail = $folioDetailQuery->first();
                hospitalFolioInvoiceDetails::where('id', $oldFolioDetail->id)->update([
                    'folio_invoice_id' => $folio->id,
                    'transaction_type' => 'sale',
                    'transaction_id' => $sale_id,
                ]);
            } else {
                $folio = hospitalFolioInvoices::where('registration_id', $registration_id)->select('id')->first();
                hospitalFolioInvoiceDetails::create([
                    'folio_invoice_id' => $folio->id,
                    'transaction_type' => 'sale',
                    'transaction_id' => $sale_id,
                ]);
            }
            return response()->json(['success' => true, 'msg' => ' Successfully Joined Registration']);
        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'msg' => ` Something Wrong While Posting Registration's folio`]);
        }
    }

    public function postToReservationFolio($id)
    {
        $folioInvoiceDetail = FolioInvoiceDetail::where('transaction_type', 'sale')
            ->where('transaction_id', $id);

        $checkFolioDetails = $folioInvoiceDetail->exists();
        $postedReservation = [];
        if ($checkFolioDetails) {
            $folioDetail = $folioInvoiceDetail->select('id', 'folio_invoice_id')->get()->first();
            $reservationId = FolioInvoice::where('id', $folioDetail->folio_invoice_id)->select('reservation_id')->first()->reservation_id;
            $postedReservation = Reservation::where('id', $reservationId)->get()->first();
        }
        $saleVoucher = sales::where('id', $id)->select('id', 'sales_voucher_no')->first();
        $reservations = Reservation::with('contact', 'company')->where('is_delete', 0)->get();
        return view('App.sell.modal.joinToReservationFolioModal')->with(compact('saleVoucher', 'reservations', 'postedReservation'));
    }

    public function addToReservationFolio(Request $request, $notReturnToBlade = false)
    {
        try {
            $sale_id = $request->sale_id;
            $reservation_id = $request->reservation_id;

            $folioInvoiceDetail = FolioInvoiceDetail::where('transaction_type', 'sale')
                ->where('transaction_id', $sale_id);

            $checkFolioDetails = $folioInvoiceDetail->exists();

            if ($checkFolioDetails) {
                $folio = FolioInvoice::where('reservation_id', $reservation_id)->select('id')->first();
                $oldFolioDetail = $folioInvoiceDetail->first();
                FolioInvoiceDetail::where('id', $oldFolioDetail->id)->update([
                    'folio_invoice_id' => $folio->id,
                    'transaction_type' => 'sale',
                    'transaction_id' => $sale_id,
                ]);
            } else {
                $folio = FolioInvoice::where('reservation_id', $reservation_id)->select('id')->first();
                FolioInvoiceDetail::create([
                    'folio_invoice_id' => $folio->id,
                    'transaction_type' => 'sale',
                    'transaction_id' => $sale_id,
                ]);
            }
            if ($notReturnToBlade) {
                return true;
            }
            return response()->json(['success' => true, 'msg' => ' Successfully Joined Reservation']);
        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'msg' => ` Something Wrong While Posting Reservation's folio`]);
        }
    }






    protected function changeTransaction($saleBeforeUpdate, $updatedSale, $request, paymentServices $paymentServices)
    {
        $transaction = paymentsTransactions::orderBy('id', 'DESC')
            ->where('transaction_ref_no', $saleBeforeUpdate->sales_voucher_no)
            ->where('transaction_id', $saleBeforeUpdate->id)
            ->where('payment_type', 'debit');
        //  dd($transaction->first()->toArray());
        $oldTransaction = $transaction->first();
        if ($oldTransaction) {
            if ($oldTransaction->payment_account_id != $request->payment_account && isset($request->payment_account)) {
                $this->depositeToBeforeChangeAcc($oldTransaction, $saleBeforeUpdate);
                $paymentServices->makePayment($updatedSale, $request->payment_account, 'sale');
            } elseif ($updatedSale->paid_amount > $saleBeforeUpdate->paid_amount) {

                $increaseAmount = $updatedSale->paid_amount - $saleBeforeUpdate->paid_amount;
                $paymentServices->makePayment($updatedSale, $request->payment_account, 'sale', true, $increaseAmount);
            } elseif ($updatedSale->paid_amount <= $saleBeforeUpdate->paid_amount) {

                $decreaseAmount = $saleBeforeUpdate->paid_amount - $updatedSale->paid_amount;
                $this->depositeToBeforeChangeAcc($oldTransaction, $saleBeforeUpdate, true, $decreaseAmount);
            }
        }
    }

    public function depositeToBeforeChangeAcc($oldTransaction, $saleBeforeUpdate, $decreasePayment = false, $decreaseAmount = 0)
    {
        $paymentAmount = $decreasePayment ? $decreaseAmount : $saleBeforeUpdate->paid_amount;
        if ($paymentAmount > 0) {
            $data = [
                'payment_voucher_no' => generatorHelpers::paymentVoucher(),
                'payment_date' => now(),
                'transaction_type' => 'sale',
                'transaction_id' => $saleBeforeUpdate->id,
                'transaction_ref_no' => $saleBeforeUpdate->sales_voucher_no,
                'payment_method' => 'card',
                'payment_account_id' => $oldTransaction->payment_account_id,
                'payment_type' => 'credit',
                'payment_amount' => $paymentAmount,
                'currency_id' => $saleBeforeUpdate->currency_id,
            ];

            paymentsTransactions::create($data);
            $accountInfo = paymentAccounts::where('id', $oldTransaction->payment_account_id);
            if ($accountInfo) {
                $currentBalanceFromDb = $accountInfo->first()->current_balance;
                $finalCurrentBalance = $currentBalanceFromDb - $paymentAmount;
                $accountInfo->update([
                    'current_balance' => $finalCurrentBalance,
                ]);
            }
        }
    }

    public function getPriceList($id)
    {
        $datas = PriceListDetails::where('pricelist_id', $id)->get();
        $priceData = [];
        if (count($datas) > 0) {
            $priceData = [
                'mainPriceList' => $datas ? $datas->toArray() : '',
                'basePriceList' => $this->getBasePrice($datas[0] ? $datas[0]->base_price : null)
            ];
        }
        return response()->json($priceData, 200);
    }

    private function getBasePrice($base_price_id)
    {
        if ($base_price_id) {
            $data = PriceListDetails::where('pricelist_id', $base_price_id)->get();
            $descendants = collect([]);
            if ($data !== null) {
                $descendants->push($data);
                $descendants = $descendants->merge($this->getBasePrice($data[0] ? $data[0]->base_price : null));
            }
            return $descendants->toArray();
        }
        return collect([]);
    }

    public function saleSplitForPos(Request $request)
    {
        try {
            DB::beginTransaction();
            $detailToSplit = $request->detailToSplit;
            $ids = array(); // Initialize an array to store the extracted ids

            foreach ($detailToSplit as $subArray) {
                if (isset($subArray["id"])) {
                    $ids[] = $subArray["id"];
                }
            }
            if (count($ids)  <= 0) {
                return back()->with([
                    'warning' => 'Add at least one item to split'
                ]);
            }


            $lastSaleId = sales::orderBy('id', 'DESC')->select('id')->first()->id ?? 0;
            $saleId = $request->saleId;

            $originalSale = sales::find($saleId);
            if (!$originalSale) {
                return back()->with('error', 'Sales voucher not found.');
            }

            $currentDetailCount = sale_details::where('sales_id', $saleId)->count();
            if (count($ids) >= $currentDetailCount) {
                $splitLineCount = 0;
                foreach ($detailToSplit as $d) {
                    $check = sale_details::where('id', $d['id'])->where('quantity', $d['quantity'])->exists();
                    if ($check) {
                        $splitLineCount++;
                    }
                }
                if ($currentDetailCount == $splitLineCount) {
                    return back()->with('warning', "Can't Split All Item.");
                }
            }

            // Clone the original item
            $clonedSale = $originalSale->replicate();

            $clonedSale->sales_voucher_no = sprintf('SVN-' . '%06d', ($lastSaleId + 1));
            $clonedSale->created_at = now();
            $clonedSale->created_by = Auth::user()->id;
            $clonedSale->updated_at = now();
            $clonedSale->save();

            $totalSplitAmount = 0;
            $totalSplitAmountwithDis = 0;

            $totalLeftAmount = 0;
            $totalLeftAmountwithDis = 0;

            $totalSplitDiscount = 0;
            foreach ($detailToSplit as $ds) {
                if (isset($ds['id'])) {
                    $sd = sale_details::where('id', $ds['id'])->first();
                    $dsq = $ds['quantity']; //$dop=detail to split quanity
                    $sdq = $sd->quantity;
                    if ($sd->quantity == $dsq) {
                        $totalSplitAmount += $sd->subtotal;
                        $totalSplitAmountwithDis += $sd->subtotal_with_discount;
                        $totalSplitDiscount += ($sd->subtotal - $sd->subtotal_with_discount) * $dsq;

                        sale_details::where('id', $ds['id'])->update([
                            'sales_id' => $clonedSale->id
                        ]);
                    } elseif ($sdq > $dsq) {
                        $leftQty = $sdq - $dsq;
                        $splitQTy = $dsq;
                        $totalSplitDiscount += ($sd->subtotal - $sd->subtotal_with_discount) / $sdq * $splitQTy;
                        $toSplitPrice = $sd->uom_price * $splitQTy;
                        $totalSplitAmount += $toSplitPrice;
                        $leftSubTotal = $sd->subtotal - $toSplitPrice;
                        $leftSbutotalAmount = $leftSubTotal;
                        $totalLeftAmount += $leftSbutotalAmount;

                        $splitSubtotalWithDis = ($sd->subtotal_with_discount / $sdq) * $splitQTy;
                        $leftSubtotalWithDis = $sd->subtotal_with_discount - $splitSubtotalWithDis;
                        $totalLeftAmountwithDis += $leftSubtotalWithDis;
                        $totalSplitAmountwithDis += $splitSubtotalWithDis;

                        $clonedSaleDetail = $sd->replicate();
                        $clonedSaleDetail->sales_id = $clonedSale->id;
                        $clonedSaleDetail->quantity = $splitQTy;
                        $clonedSaleDetail->subtotal = $toSplitPrice;
                        $clonedSaleDetail->subtotal_with_discount = $splitSubtotalWithDis;
                        $clonedSaleDetail->subtotal_with_tax = $splitSubtotalWithDis;
                        $clonedSaleDetail->save();


                        $sd->update([
                            'quantity' => $leftQty,
                            'subtotal' => $leftSubTotal,
                            'subtotal_with_discount' => $leftSubtotalWithDis,
                            'subtotal_with_tax' => $leftSubtotalWithDis,
                        ]);
                    }
                }
            }
            $clonedSale->sale_amount = $totalSplitAmount;
            $clonedSale->total_sale_amount = $totalSplitAmountwithDis;
            $clonedSale->total_item_discount = $totalSplitDiscount;
            $clonedSale->balance_amount = $totalSplitAmountwithDis;
            $clonedSale->update();


            $originalSale->sale_amount = $originalSale->sale_amount - $totalSplitAmount;
            $originalSale->total_sale_amount = $originalSale->total_sale_amount - $totalSplitAmountwithDis;
            $originalSale->total_item_discount = $originalSale->total_item_discount - $totalSplitDiscount;
            $originalSale->balance_amount =  $originalSale->balance_amount - $totalSplitAmountwithDis;
            $originalSale->update();
            DB::commit();
            return back()->with([
                'success' => 'Successfully Splited'
            ]);
        } catch (\Throwable $th) {
            dd($th);
            DB::rollBack();
            return back()->with([
                'error' => 'Something Went Wrongs'
            ]);
        }
    }
    public function romAviaQtyCheck(Request $request)
    {

        if (hasModule('ComboKit') && isEnableModule('ComboKit')) {
            return RoMService::getKitAvailableQty($request->locationId, $request->productId);
        }
        return '';
    }
    public function addChunkData($saleId, Request $request)
    {
    }
}
