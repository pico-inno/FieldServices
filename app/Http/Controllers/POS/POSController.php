<?php

namespace App\Http\Controllers\POS;

use DB;

use Exception;
use App\Models\sale\sales;
use App\Models\Product\UOM;
use App\Models\posRegisters;
use Illuminate\Http\Request;
use App\Models\Product\Brand;
use App\Models\Product\UOMSet;
use App\Models\Contact\Contact;
use App\Models\Product\Generic;
use App\Models\Product\Product;
use App\Models\Product\Category;
use App\Models\Product\PriceGroup;
use App\Models\Product\PriceLists;
use App\Models\CurrentStockBalance;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Product\Manufacturer;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isEmpty;
use Maatwebsite\Excel\Concerns\ToArray;
use App\Models\Product\ProductVariation;

use App\Models\settings\businessLocation;
use App\Models\settings\businessSettings;
use App\Models\Product\VariationTemplates;
use App\Models\Product\ProductVariationsTemplates;

class POSController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }
    public function create()
    {
        if(request('pos_register_id')){
           try {
                $posRegisterId=decrypt(request('pos_register_id'));
                $posRegisterQry=posRegisters::where('id',$posRegisterId);
                $checkPos=$posRegisterQry->exists();
                if(!$checkPos){
                    return back()->with(['warning'=>'something went wrong']);
                }
                $posRegister=$posRegisterQry->first();
           } catch (\Throwable $th) {
                return back()->with(['warning'=>'something went wrong']);
           }
        }else{
            return back()->with(['warning'=>'something went wrong']);
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

        return view('App.pos.create', compact('locations', 'price_lists',  'currentStockBalance', 'categories', 'generics', 'manufacturers', 'brands', 'uoms', 'variations','posRegisterId','posRegister'));
    }

    public function productVariationsGet()
    {
        $products = Product::with('productVariations')->select('id', 'name', 'sku', 'product_type','category_id', 'sub_category_id', 'manufacturer_id', 'generic_id', 'brand_id', 'image')->get();

        $product_with_variations = $products->map(function ($item, $key){

            $to_return_data = [];
            if($item->product_type === "single"){
                $item['product_variation_id'] = $item->productVariations[0]->id;
                $item['default_selling_price'] = $item->productVariations[0]->default_selling_price;
                $to_return_data[] = $item;
            }

            if($item->product_type === "variable"){
                foreach($item->productVariations as $variation){
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
        $product_with_variations->chunk(100)->each(function ($chunk) {
            $response = response()->json($chunk);
            $response->send();
        });
    }

    public function paymentPrintLayout(Request $request)
    {
        $business_name = businessSettings::first()->name;
        $user_name = Auth::user()->username;
        $invoice_row = $request->invoice_row_data;
        $invoice_no = $request->invoice_no;
        $totalPriceAndOtherData = $request->totalPriceAndOtherData;

        $html = view('App.pos.print.payment', compact('business_name', 'user_name', 'invoice_row', 'totalPriceAndOtherData', 'invoice_no'))->render();

        return response()->json(['html' => $html]);
    }

    public function contactGet()
    {
        $raw_customers = Contact::where('type', 'Customer')->get();
        $customers = [];
        foreach($raw_customers as $customer){
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

            return response()->json([
                'status' => 200,
                'default_price_list' => $default_price_list,
                'receivable_amount' => $receivable_amount
            ]);
            // $queries = \DB::getQueryLog();
            // Log::error(count($queries));

        } catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function checkByLocation($id)
    {
        try {
            // \DB::enableQueryLog();
            $default_price_list = businessLocation::with('pricelist')->find($id)->pricelist ?? null;

            $price_lists = PriceLists::with('priceListDetails')->get();

            $pricelist_details = $price_lists->map->priceListDetails->flatten()->all();

            $raw_products_with_pricelist = [];
            foreach($pricelist_details as $index => $value){
                $apply_type = $value['applied_type'];
                $apply_value = $value['applied_value'];

                if($apply_type === 'All'){
                    $product_with_variations = Product::with('productVariations')->get();
                    $products = [];
                    foreach($product_with_variations as $inner_val){
                        if($inner_val->product_type === 'single'){
                            $inner_val['product_variation_id'] = $inner_val->productVariations[0]->id;
                        }
                        if($inner_val->product_type === 'variable'){
                            $inner_val['product_variation_id'] = $inner_val->productVariations->pluck('id')->toArray();
                        }
                        $inner_val['pricelist_detail_id'] = $value['id'];
                        $inner_val['pricelist_id'] = $value['pricelist_id'];
                        $inner_val['applied_type'] = $value['applied_type'];
                        $inner_val['applied_value'] = $value['applied_value'];
                        $inner_val['min_qty'] = $value['min_qty'];
                        $inner_val['from_date'] = $value['from_date'];
                        $inner_val['to_date'] = $value['to_date'];
                        $inner_val['price'] = $value['cal_value'];
                        $inner_val['base_price'] = $value['base_price'];
                        $products[] = $inner_val;
                    }
                    $raw_products_with_pricelist[] = collect($products)->toArray();
                }
                if($apply_type === 'Product'){
                    $product = Product::with('productVariations')->where('id', $apply_value)->first();
                    if($product && $product->product_type === 'single'){
                        $product['product_variation_id'] = $product->productVariations[0]->id;
                        $product['pricelist_detail_id'] = $value['id'];
                        $product['pricelist_id'] = $value['pricelist_id'];
                        $product['applied_type'] = $value['applied_type'];
                        $product['applied_value'] = $value['applied_value'];
                        $product['min_qty'] = $value['min_qty'];
                        $product['from_date'] = $value['from_date'];
                        $product['to_date'] = $value['to_date'];
                        $product['price'] = $value['cal_value'];
                        $product['base_price'] = $value['base_price'];
                        $raw_products_with_pricelist[] = $product;
                    }
                    if($product->product_type === 'variable'){
                        $product['product_variation_id'] = $product->productVariations->pluck('id')->toArray();
                        $product['pricelist_detail_id'] = $value['id'];
                        $product['pricelist_id'] = $value['pricelist_id'];
                        $product['applied_type'] = $value['applied_type'];
                        $product['applied_value'] = $value['applied_value'];
                        $product['min_qty'] = $value['min_qty'];
                        $product['from_date'] = $value['from_date'];
                        $product['to_date'] = $value['to_date'];
                        $product['price'] = $value['cal_value'];
                        $product['base_price'] = $value['base_price'];
                        $raw_products_with_pricelist[] = $product;
                    }
                }
                if($apply_type === 'Variation'){
                    $variation = ProductVariation::with('product')->where('id', $apply_value)->first();
                    if($variation){
                        $variationData = $variation->toArray();
                        $variationData['product_variation_id'] = $variation->id;
                        $variationData['uom_id'] = $variation->product->uom_id;
                        $variationData['purchase_uom_id'] = $variation->product->purchase_uom_id;
                        $variationData['pricelist_detail_id'] = $value['id'];
                        $variationData['pricelist_id'] = $value['pricelist_id'];
                        $variationData['applied_type'] = $value['applied_type'];
                        $variationData['applied_value'] = $value['applied_value'];
                        $variationData['min_qty'] = $value['min_qty'];
                        $variationData['from_date'] = $value['from_date'];
                        $variationData['to_date'] = $value['to_date'];
                        $variationData['price'] = $value['cal_value'];
                        $variationData['base_price'] = $value['base_price'];
                    }

                    $raw_products_with_pricelist[][0] = $variationData;
                }
                if($apply_type === 'Category'){
                    $raw_products = Product::with('productVariations')->where('category_id', $apply_value)->get();
                    $products = [];
                    foreach($raw_products as $inner_val){
                        if($inner_val->product_type === 'single'){
                            $inner_val['product_variation_id'] = $inner_val->productVariations[0]->id;
                        }
                        if($inner_val->product_type === 'variable'){
                            $inner_val['product_variation_id'] = $inner_val->productVariations->pluck('id')->toArray();
                        }
                        $inner_val['pricelist_detail_id'] = $value['id'];
                        $inner_val['pricelist_id'] = $value['pricelist_id'];
                        $inner_val['applied_type'] = $value['applied_type'];
                        $inner_val['applied_value'] = $value['applied_value'];
                        $inner_val['min_qty'] = $value['min_qty'];
                        $inner_val['from_date'] = $value['from_date'];
                        $inner_val['to_date'] = $value['to_date'];
                        $inner_val['price'] = $value['cal_value'];
                        $inner_val['base_price'] = $value['base_price'];
                        $products[] = $inner_val;
                    }
                    $raw_products_with_pricelist[] = collect($products)->toArray();
                }

            }
            $products_with_pricelist = collect($raw_products_with_pricelist)->flatten(1)->toArray();


            return response()->json([
                'status' => 200,
                'default_price_list' => $default_price_list,
                'price_lists' => $price_lists,
                'pricelist_details' => $pricelist_details,
                'products_with_pricelist' => $products_with_pricelist
            ]);
            // $queries = \DB::getQueryLog();
            // Log::error(count($queries));

        } catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    // GET SOLD PRODUCT
    public function getSoldProduct($posId)
    {
        try {
            $salesQuery = sales::with('contact')->wherePosRegisterId(1)->select('id', 'sales_voucher_no', 'contact_id', 'status', 'total_sale_amount')->get();

            $saleDataCollection = $salesQuery->map(function ($item){
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
