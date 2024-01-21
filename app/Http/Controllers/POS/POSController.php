<?php

namespace App\Http\Controllers\POS;

use Exception;

use App\Models\resOrders;
use App\Models\sale\sales;
use App\Models\Product\UOM;
use App\Models\posRegisters;
use Illuminate\Http\Request;
use App\Models\Product\Brand;
use App\Models\Product\UOMSet;
use App\Models\Contact\Contact;
use App\Models\paymentAccounts;
use App\Models\Product\Generic;
use App\Models\Product\Product;
use App\Models\Product\Category;
use App\Models\sale\sale_details;
use App\Models\Product\PriceGroup;
use App\Models\Product\PriceLists;
use Illuminate\Support\Facades\DB;
use App\Models\CurrentStockBalance;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Controllers\networkPrinterController;
use App\Models\InvoiceTemplate;
use App\Models\Product\Manufacturer;
use Illuminate\Support\Facades\Auth;
use Modules\Restaurant\Entities\table;
use App\Models\posRegisterTransactions;
use function PHPUnit\Framework\isEmpty;

use Maatwebsite\Excel\Concerns\ToArray;
use App\Models\Product\ProductVariation;
use App\Models\settings\businessLocation;
use App\Models\settings\businessSettings;
use App\Models\Product\VariationTemplates;
use Modules\Reservation\Entities\Reservation;
use App\Models\posSession\posRegisterSessions;
use Modules\Reservation\Entities\FolioInvoice;
use App\Models\Product\ProductVariationsTemplates;
use Modules\Reservation\Entities\FolioInvoiceDetail;

class POSController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }
    public function create()
    {
        if (request('pos_register_id') && request('sessionId')) {
            try {

                $walkInCustomer = optional(Contact::where('type', 'Customer')->orWhere('type', 'Both')->where('first_name', 'Walk-In Customer')->first());
                $sessionId = request('sessionId');
                $posRegisterId = decrypt(request('pos_register_id'));
                $posSession = posRegisterSessions::where('id', $sessionId)->where('pos_register_id', $posRegisterId)->where('status', 'open')->firstOrFail();
                $posRegisterQry = posRegisters::where('id', $posRegisterId);
                $setting = businessSettings::where('id', Auth::user()->business_id)->first();
                $checkPos = $posRegisterQry->exists();
                $currencySymbol = $setting->currency->symbol;

                if (!$checkPos) {
                    return back()->with(['warning' => 'This POS is not in Register List']);
                }
                $posRegisterQry = posRegisters::whereJsonContains('employee_id', Auth::user()->id)->where('id', $posRegisterId);
                $checkAccess = $posRegisterQry->exists();
                if (!$checkAccess) {
                    return redirect()->route('pos.selectPos')->with(['error' => 'Access Denied']);
                }
                $posRegister = $posRegisterQry->first();
                $paymentAccIds = json_decode($posRegister->payment_account_id);
                $paymentAcc = [];
                if ($paymentAccIds != 0) {
                    $paymentAcc = paymentAccounts::whereIn('id', $paymentAccIds)->get();
                }
            } catch (\Throwable $th) {
                return back()->with(['warning' => 'something went wrong']);
            }
        } else {
            return back()->with(['warning' => 'something went wrong']);
        }
        $locations = businessLocation::all();

        // $priceGroups = PriceGroup::select('id', 'name', 'description')->get();
        $price_lists = PriceLists::all();
        $currentStockBalance = CurrentStockBalance::all();
        $uoms = UOM::all();

        $categories = Category::all();
        $brands = Brand::all();
        $generics = Generic::all();
        $manufacturers = Manufacturer::all();
        $variations = VariationTemplates::all();
        $tables = null;
        $reservations = [];

        $reservations = [];

        if (hasModule('Reservation') && isEnableModule('Reservation')) {
            $reservations = Reservation::with('contact', 'company')->where('is_delete', 0)->get();
        }
        try {
            $tables = table::get();
        } catch (\Throwable $th) {
            $table = null;
        }
        return view('App.pos.create', compact('locations', 'paymentAcc', 'price_lists',  'currentStockBalance', 'categories', 'generics', 'manufacturers', 'brands', 'uoms', 'variations', 'posRegisterId', 'posRegister', 'tables', 'reservations', 'setting', 'currencySymbol', 'walkInCustomer'));
    }

    public function edit($posRegisterId)
    {
        try {
            $posRegisterQry = posRegisters::where('id', $posRegisterId);
            $checkPos = $posRegisterQry->exists();
            if (!$checkPos) {
                return back()->with(['warning' => 'something went wrong']);
            }
            $posRegister = $posRegisterQry->first();
            $posSession = posRegisterSessions::where('pos_register_id', $posRegisterId)->where('status', 'open')->first();

            $paymentAccIds = json_decode($posRegister->payment_account_id);
            $paymentAcc = [];
            if ($paymentAccIds != 0) {
                $paymentAcc = paymentAccounts::whereIn('id', $paymentAccIds)->get();
            }
        } catch (\Throwable $th) {
            return back()->with(['warning' => 'something went wrong']);
        }
        $saleId = request('saleId');
        $sale = sales::where('id', $saleId)->where('pos_register_id', $posRegisterId)->first();

        $business_location_id = $sale->business_location_id;
        $sale_details_query = sale_details::with([
            'currency',
            'packagingTx.packaging',
            'productVariation' => function ($q) {
                $q->select('id', 'product_id', 'variation_template_value_id', 'default_selling_price')
                    ->with([
                        'packaging',
                        'product' => function ($q) {
                            $q->select('id', 'name', 'has_variation');
                        },
                        'variationTemplateValue' => function ($q) {
                            $q->select('id', 'name');
                        }, 'additionalProduct.productVariation.product', 'additionalProduct.uom', 'additionalProduct.productVariation.variationTemplateValue'

                    ]);
            },
            'stock' => function ($q) use ($business_location_id) {
                $q->where('current_quantity', '>', 0)
                    ->where('business_location_id', $business_location_id);
            },
            'Currentstock',  'product' => function ($q) {
                $q->with(['uom' => function ($q) {
                    $q->with(['unit_category' => function ($q) {
                        $q->with('uomByCategory');
                    }]);
                }]);
            }
        ])
            ->where('sales_id', $saleId)->where('is_delete', 0)
            ->withSum(['stock' => function ($q) use ($business_location_id) {
                $q->where('business_location_id', $business_location_id);
            }], 'current_quantity');
        $saleDetails = $sale_details_query->get();
        // dd($saleDetails->toArray());

        $locations = businessLocation::all();
        $price_lists = PriceLists::all();
        $currentStockBalance = CurrentStockBalance::all();

        $setting = businessSettings::where('id', Auth::user()->business_id)->first();
        $currencySymbol = $setting->currency->symbol;
        $uoms = UOM::all();

        $categories = Category::all();
        $brands = Brand::all();
        $generics = Generic::all();
        $manufacturers = Manufacturer::all();
        $variations = VariationTemplates::all();
        $tables = null;
        try {
            $tables = table::get();
        } catch (\Throwable $th) {
            $table = null;
        }
        // dd($saleDetails->toArray());
        return view('App.pos.edit', compact('sale', 'setting', 'currencySymbol', 'paymentAcc', 'saleDetails', 'locations', 'price_lists',  'currentStockBalance', 'categories', 'generics', 'manufacturers', 'brands', 'uoms', 'variations', 'posRegisterId', 'posRegister', 'tables', 'posSession'));
    }
    public function productVariationsGet()
    {
        $products = Product::with('productVariations')
            ->where('can_sale', 1)
            ->select('id', 'name', 'sku', 'has_variation', 'category_id', 'sub_category_id', 'manufacturer_id', 'generic_id', 'brand_id', 'image', 'receipe_of_material_id')->get();

        $product_with_variations = $products->map(function ($item, $key) {

            $to_return_data = [];
            if ($item->has_variation === "single") {
                $item['product_variation_id'] = $item->productVariations[0]->id;
                $item['default_selling_price'] = $item->productVariations[0]->default_selling_price;
                $to_return_data[] = $item;
            }

            if ($item->has_variation === "variable") {
                foreach ($item->productVariations as $variation) {
                    $variation['product_variation_id'] = $variation->id;
                    $variation['name'] = $item->name;
                    $variation['image'] = $item->image ?? null;
                    $variation['vari_tem_name'] = $variation->variationTemplateValue->variationTemplate->name ?? null;
                    $variation['vari_tem_val_name'] = $variation->variationTemplateValue->name ?? null;
                    $variation['parent_category_id'] = $item->category_id ?? null;
                    $variation['sub_category_id'] = $item->sub_category_id ?? null;
                    $variation['manufacturer_id'] = $item->manufacturer_id ?? null;
                    $variation['brand_id'] = $item->brand_id ?? null;
                    $variation['generic_id'] = $item->generic_id ?? null;
                    $to_return_data[] = $variation;
                }
            }
            return $to_return_data;
        })->flatten();

        // return response()->json($product_with_variations);
        $product_with_variations->chunk(1000)->each(function ($chunk) {
            $response = response()->json($chunk);
            $response->send();
        });
    }

    public function paymentPrintLayout($id, $layout_id)
    {
        // $InvoiceTemplate = InvoiceTemplate::find($request->InvoiceTemplateId);
        $sale = sales::with('sold_by', 'confirm_by', 'customer', 'updated_by', 'currency')->where('id', $id)->first();
        // dd($sale);
        $location = businessLocation::where('id', $sale['business_location_id'])->first();
        $address = $location->locationAddress;
        $sale_details = sale_details::with('productVariation.product', 'productVariation.variationTemplateValue', 'product', 'uom', 'currency')
            ->where('sales_id', $id)->where('is_delete', 0)->get();

        // $html = view('App.pos.print.payment2', compact('sale_details','address','location','sale'))->render();
        $layout = InvoiceTemplate::find($layout_id);
        // dd($layout);
        $type = "sale";
        if (!$layout) {
            $invoiceHtml = view('App.sell.print.saleInvoice3', compact('sale', 'location', 'sale_details', 'address', 'layout'))->render();
        } else if ($layout->layout  == "80mm") {

            $table_text = json_decode($layout->table_text);
            $data_text = json_decode($layout->data_text);
            // dd($sale_details->toArray());
            $invoiceHtml = view('App.sell.print.pos.80mmFixLayout', compact('sale', 'location', 'sale_details', 'address', 'table_text', 'data_text', 'layout'))->render();
        } else {
            $table_text = json_decode($layout->table_text);
            $data_text = json_decode($layout->data_text);
            $invoiceHtml = view('components.invoice.sell-layout', compact('sale', 'sale_details', 'table_text', 'data_text', 'location', 'table_text', 'address', 'layout', 'type'))->render();
        }
        return response()->json(['html' => $invoiceHtml]);
    }

    public function contactGet()
    {
        $raw_customers = Contact::where('type', 'Customer')->get();
        $customers = [];
        foreach ($raw_customers as $customer) {
            $customer['full_name'] = $customer->getFullNameAttribute();
            $customers[] = $customer;
        }

        return response()->json($customers);
    }

    public function contactAdd()
    {
        return view('App.pos.contactAdd');
    }

    public function contactEdit($id)
    {
        $customer = Contact::find($id);

        return view('App.pos.contactEdit')->with(compact('customer'));
    }

    public function phoneOnlyUpdate($id, Request $request)
    {
        Contact::where('id', $id)->update(['mobile' => $request->customer_phone]);

        return response()->json(['success' => true, 'msg' => 'Phone number updated sucessfully', 'id' => $id]);
    }

    // PRICELIST
    public function checkByContact($id)
    {
        try {
            // \DB::enableQueryLog();
            $default_price_list = Contact::with('pricelist')->find($id)->pricelist ?? null;
            $receivable_amount = Contact::whereId($id)->first()->receivable_amount;
            $credit_limit = Contact::whereId($id)->first()->credit_limit ?? 0;

            return response()->json([
                'status' => 200,
                'default_price_list' => $default_price_list,
                'receivable_amount' => $receivable_amount,
                'credit_limit' => $credit_limit
            ]);
            // $queries = \DB::getQueryLog();
            // Log::error(count($queries));

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    // public function checkByLocation($id)
    // {
    //     try {
    //         // \DB::enableQueryLog();
    //         $default_price_list = businessLocation::with('pricelist')->find($id)->pricelist ?? null;

    //         $price_lists = PriceLists::with('priceListDetails')->get();

    //         $pricelist_details = $price_lists->map->priceListDetails->flatten()->all();

    //         $raw_products_with_pricelist = [];
    //         foreach($pricelist_details as $index => $value){
    //             $apply_type = $value['applied_type'];
    //             $apply_value = $value['applied_value'];

    //             if($apply_type === 'All'){
    //                 $product_with_variations = Product::with('productVariations')->get();
    //                 $products = [];
    //                 foreach($product_with_variations as $inner_val){
    //                     if($inner_val->has_variation === 'single'){
    //                         $inner_val['product_variation_id'] = $inner_val->productVariations[0]->id;
    //                     }
    //                     if($inner_val->has_variation === 'variable'){
    //                         $inner_val['product_variation_id'] = $inner_val->productVariations->pluck('id')->toArray();
    //                     }
    //                     $inner_val['pricelist_detail_id'] = $value['id'];
    //                     $inner_val['pricelist_id'] = $value['pricelist_id'];
    //                     $inner_val['applied_type'] = $value['applied_type'];
    //                     $inner_val['applied_value'] = $value['applied_value'];
    //                     $inner_val['min_qty'] = $value['min_qty'];
    //                     $inner_val['from_date'] = $value['from_date'];
    //                     $inner_val['to_date'] = $value['to_date'];
    //                     $inner_val['price'] = $value['cal_value'];
    //                     $inner_val['base_price'] = $value['base_price'];
    //                     $products[] = $inner_val;
    //                 }
    //                 $raw_products_with_pricelist[] = collect($products)->toArray();
    //             }
    //             if($apply_type === 'Product'){
    //                 $product = Product::with('productVariations')->where('id', $apply_value)->first();
    //                 if($product && $product->has_variation === 'single'){
    //                     $product['product_variation_id'] = $product->productVariations[0]->id;
    //                     $product['pricelist_detail_id'] = $value['id'];
    //                     $product['pricelist_id'] = $value['pricelist_id'];
    //                     $product['applied_type'] = $value['applied_type'];
    //                     $product['applied_value'] = $value['applied_value'];
    //                     $product['min_qty'] = $value['min_qty'];
    //                     $product['from_date'] = $value['from_date'];
    //                     $product['to_date'] = $value['to_date'];
    //                     $product['price'] = $value['cal_value'];
    //                     $product['base_price'] = $value['base_price'];
    //                     $raw_products_with_pricelist[] = $product;
    //                 }
    //                 if($product->has_variation === 'variable'){
    //                     $product['product_variation_id'] = $product->productVariations->pluck('id')->toArray();
    //                     $product['pricelist_detail_id'] = $value['id'];
    //                     $product['pricelist_id'] = $value['pricelist_id'];
    //                     $product['applied_type'] = $value['applied_type'];
    //                     $product['applied_value'] = $value['applied_value'];
    //                     $product['min_qty'] = $value['min_qty'];
    //                     $product['from_date'] = $value['from_date'];
    //                     $product['to_date'] = $value['to_date'];
    //                     $product['price'] = $value['cal_value'];
    //                     $product['base_price'] = $value['base_price'];
    //                     $raw_products_with_pricelist[] = $product;
    //                 }
    //             }
    //             if($apply_type === 'Variation'){
    //                 $variation = ProductVariation::with('product')->where('id', $apply_value)->first();
    //                 if($variation){
    //                     $variationData = $variation->toArray();
    //                     $variationData['product_variation_id'] = $variation->id;
    //                     $variationData['uom_id'] = $variation->product->uom_id;
    //                     $variationData['purchase_uom_id'] = $variation->product->purchase_uom_id;
    //                     $variationData['pricelist_detail_id'] = $value['id'];
    //                     $variationData['pricelist_id'] = $value['pricelist_id'];
    //                     $variationData['applied_type'] = $value['applied_type'];
    //                     $variationData['applied_value'] = $value['applied_value'];
    //                     $variationData['min_qty'] = $value['min_qty'];
    //                     $variationData['from_date'] = $value['from_date'];
    //                     $variationData['to_date'] = $value['to_date'];
    //                     $variationData['price'] = $value['cal_value'];
    //                     $variationData['base_price'] = $value['base_price'];
    //                 }

    //                 $raw_products_with_pricelist[][0] = $variationData;
    //             }
    //             if($apply_type === 'Category'){
    //                 $raw_products = Product::with('productVariations')->where('category_id', $apply_value)->get();
    //                 $products = [];
    //                 foreach($raw_products as $inner_val){
    //                     if($inner_val->has_variation === 'single'){
    //                         $inner_val['product_variation_id'] = $inner_val->productVariations[0]->id;
    //                     }
    //                     if($inner_val->has_variation === 'variable'){
    //                         $inner_val['product_variation_id'] = $inner_val->productVariations->pluck('id')->toArray();
    //                     }
    //                     $inner_val['pricelist_detail_id'] = $value['id'];
    //                     $inner_val['pricelist_id'] = $value['pricelist_id'];
    //                     $inner_val['applied_type'] = $value['applied_type'];
    //                     $inner_val['applied_value'] = $value['applied_value'];
    //                     $inner_val['min_qty'] = $value['min_qty'];
    //                     $inner_val['from_date'] = $value['from_date'];
    //                     $inner_val['to_date'] = $value['to_date'];
    //                     $inner_val['price'] = $value['cal_value'];
    //                     $inner_val['base_price'] = $value['base_price'];
    //                     $products[] = $inner_val;
    //                 }
    //                 $raw_products_with_pricelist[] = collect($products)->toArray();
    //             }

    //         }
    //         $products_with_pricelist = collect($raw_products_with_pricelist)->flatten(1)->toArray();


    //         return response()->json([
    //             'status' => 200,
    //             'default_price_list' => $default_price_list,
    //             'price_lists' => $price_lists,
    //             'pricelist_details' => $pricelist_details,
    //             'products_with_pricelist' => $products_with_pricelist
    //         ]);
    //         // $queries = \DB::getQueryLog();
    //         // Log::error(count($queries));

    //     } catch (\Exception $e){
    //         return response()->json(['error' => $e->getMessage()], 404);
    //     }
    // }

    public function checkByLocation($id)
    {
        try {
            // \DB::enableQueryLog();
            $default_price_list = businessLocation::with('pricelist')->find($id)->pricelist ?? null;
            $price_lists = PriceLists::with('priceListDetails')->get();
            return response()->json([
                'status' => 200,
                'default_price_list' => $default_price_list,
                'price_lists' => $price_lists,
            ]);
            // $queries = \DB::getQueryLog();
            // Log::error(count($queries));
        } catch (\Exception $e) {
            logger($e);
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
    public function recentSale($id)
    {
        $posRegisterCheck = posRegisters::where('id', $id)->exists();
        $restaurantOrder = null;
        if ($posRegisterCheck) {

            $posRegister = posRegisters::where('id', $id)->first();
            if ($posRegister->use_for_res == 1) {
                $restaurantOrder = resOrders::where('pos_register_id', $id)

                    ->orderBy('id', 'DESC')
                    ->limit(20)->get();
            }
        }
        $posRegisterId = $id;
        $saleOrders = sales::where('pos_register_id', $id)
            ->orderBy('id', 'DESC')
            ->where('status', 'order')
            ->limit(5)
            ->get();
        $saleDelivered = sales::where('pos_register_id', $id)
            ->orderBy('id', 'DESC')
            ->where('status', 'delivered')
            ->limit(5)
            ->get();
        $saleDrafts = sales::where('pos_register_id', $id)
            ->orderBy('id', 'DESC')
            ->where('status', 'draft')
            ->limit(5)
            ->get();


        return view('App.pos.recentTransactions', compact('saleOrders', 'saleDelivered', 'saleDrafts', 'posRegisterId', 'restaurantOrder', 'posRegister'));
    }
    public function closeSession($posRegisterId)
    {
        $posRegister = posRegisters::where('id', $posRegisterId)->first();
        $sessionId = request('sessionId');
        $posSession = posRegisterSessions::where('id', $sessionId)->first();
        $saleTransactions = posRegisterTransactions::where('register_session_id', $sessionId)
            ->where('transaction_type', 'sale')
            ->where('transaction_type', 'sale')
            ->with('sale')
            ->get();
        $paymentTransactions = posRegisterTransactions::where('register_session_id', $sessionId)
            ->whereNotNull('payment_transaction_id')
            ->with('paymentTransaction')
            ->get();

        $sumAmountOnPaymentAcc = posRegisterTransactions::select('payment_account_id', DB::raw('SUM(transaction_amount) as total_amount'))
            ->where('register_session_id', $sessionId)
            ->with('paymentAccount')
            ->groupBy('payment_account_id', 'currency_id')
            ->get();
        // dd($sumAmountOnPaymentAcc->toArray());
        return view('App.pos.closeSession', compact('posRegister', 'posSession', 'saleTransactions', 'paymentTransactions', 'sumAmountOnPaymentAcc'));
    }



    // GET SOLD PRODUCT
    public function getSoldProduct($posId)
    {
        try {
            $salesQuery = sales::with('contact')->wherePosRegisterId(1)->select('id', 'sales_voucher_no', 'contact_id', 'status', 'total_sale_amount')->get();

            $saleDataCollection = $salesQuery->map(function ($item) {
                $item['contact_name'] = $item->contact->getFullNameAttribute();
                return $item;
            });

            $sales = $saleDataCollection->chunk(40)->flatten();

            return response()->json(['saleDatas' => $sales]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching data.'], 500);
        }
    }
}
