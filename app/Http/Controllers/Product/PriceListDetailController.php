<?php

namespace App\Http\Controllers\Product;

use Exception;
use App\Models\Currencies;
use Hamcrest\Arrays\IsArray;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Models\Product\Category;
use App\Models\Product\PriceLists;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Product\ProductVariation;

use App\Models\settings\businessLocation;
use App\Models\settings\businessSettings;
use App\Http\Requests\Product\PriceList\PriceListCreateRequest;
use App\Http\Requests\Product\PriceList\PriceListUpdateRequest;
use App\Models\Product\PriceListDetails;

class PriceListDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }
    
    public function priceListDetailDatas()
    {
        $priceLists = PriceLists::with('priceListDetails', 'businessSetting', 'currency')->get();

        return DataTables::of($priceLists)
        ->addColumn('business', function($priceList) {
            return $priceList->businessSetting->name ?? '';
        })
        ->addColumn('currency', function($priceList) {
            return $priceList->currency->name ?? '';
        })
        ->addColumn('action', function($priceList) {
            return $priceList->id;
        })
        ->rawColumns(['business', 'location', 'currency', 'action'])
        ->make(true);
    }

    public function index()
    {
        return view('App.product.PriceListDetail.index');
    }

    public function add()
    {
        $currencies = Currencies::all();
        $price_lists = PriceLists::all();
        
        return view('App.product.PriceListDetail.add', compact('currencies', 'price_lists'));
    }

    public function create(PriceListCreateRequest $request)
    {
        DB::beginTransaction();
        try{
            $business_id = businessSettings::first()->id;
            $price_list_id = PriceLists::create([
                'business_id' => $business_id,
                'currency_id' => $request->currency_id,
                'name' => $request->name,
                'description' => $request->description
            ])->id;
    
            if(empty($request->apply_type)){
                return;
            }
            
            $format_to_create = [];
            foreach($request->apply_type as $index => $value){
                $format_to_create[] = [
                    'pricelist_id' => $price_list_id,
                    'applied_type' => $value ?? null,
                    'applied_value' => $request->apply_value[$index] ?? null,
                    'min_qty' => $request->min_qty[$index] ?? null,
                    'from_date' => $request->start_date[$index] ?? null,
                    'to_date' => $request->end_date[$index] ?? null,
                    'cal_type' => 'fixed',
                    'cal_value' => $request->price[$index] ?? null,
                    'base_price' => $request->base_price
                ];
            }
            PriceListDetails::insert($format_to_create);

            DB::commit();

            return redirect('/price-list-detail')->with('message', 'Created Sucessfully PriceList');
        }catch(Exception $e){
            DB::rollBack();
            Log::error($e->getMessage());
            return back()->with('message', $e->getMessage());
        }

    }

    public function edit(PriceLists $priceList)
    {
        $currencies = Currencies::all();
        $price_lists = PriceLists::all();
        $price_list_details = $priceList->priceListDetails;

        return view('App.product.PriceListDetail.edit', compact('priceList', 'currencies', 'price_lists', 'price_list_details'));
    }

    public function update(PriceListUpdateRequest $request, PriceLists $priceList)
    {
        DB::beginTransaction();
        try{
            $business_id = businessSettings::first()->id;
            $priceList->business_id = $business_id;
            $priceList->currency_id = $request->currency_id;
            $priceList->name = $request->name;
            $priceList->description = $request->description;
            $priceList->update();
            
            if(!empty($request->apply_type)){
                // to delete pricelist detail
                $db_pricelist_details_id = PriceListDetails::where('pricelist_id', $priceList->id)->get()->pluck('id')->toArray();
                $array_diff_to_del = array_diff($db_pricelist_details_id, $request->price_list_detail_id);        
                if(isset($array_diff_to_del)){
                    PriceListDetails::destroy($array_diff_to_del);
                }
    
                $format_data = [];
                foreach($request->price_list_detail_id as $key => $value){
                    $format_data[] = [
                        'id' => $value,
                        'pricelist_id' => $priceList->id,
                        'applied_type' => $request->apply_type[$key] ?? null,
                        'applied_value' => $request->apply_value[$key] ?? null,
                        'min_qty' => $request->min_qty[$key] ?? null,
                        'from_date' => $request->start_date[$key] ?? null,
                        'to_date' => $request->end_date[$key] ?? null,
                        'cal_type' => 'fixed',
                        'cal_value' => $request->price[$key] ?? null,
                        'base_price' => $request->base_price
                    ];
                }
                foreach($format_data as $value){
                    if(isset($value['id'])){
                        PriceListDetails::where('id', $value['id'])->update($value);
                    }else{
                        PriceListDetails::create($value);
                    }
                }
            }else{
                $db_pricelist_details_id = PriceListDetails::where('pricelist_id', $priceList->id)->get()->pluck('id')->toArray();
                PriceListDetails::destroy($db_pricelist_details_id);
            }

            DB::commit();

            return redirect('/price-list-detail')->with('message', 'Updated Sucessfully PriceList');
        }catch(Exception $e){
            DB::rollBack();
            Log::error($e->getMessage());
            return back()->with('message', $e->getMessage());
        }
    }

    public function delete(PriceLists $priceList)
    {
        DB::beginTransaction();
        try{
            $pricelist_details_id = $priceList->priceListDetails->pluck('id')->toArray();
            PriceListDetails::destroy($pricelist_details_id);

            $priceList->delete();

            DB::commit();
            return response()->json(['message' => 'Deleted Sucessfully PriceList', 'id' => $pricelist_details_id]);
        }catch(Exception $e){
            DB::rollBack();
            Log::error($e->getMessage());
            return back()->with('message', $e->getMessage());
        }
    }

    public function searchAppliedValue(Request $request)
    {
        $applied_type = $request->applied_type;

        $toResponseData = null;
        if($applied_type === 'Category'){
            $categories = Category::select('id', 'name')->get();
            $toResponseData = $categories;
        }

        if($applied_type === 'Product'){
            $products = Product::select('id', 'name')->get();
            $toResponseData = $products;
        }

        if($applied_type === 'Variation'){
            $product_with_variations = ProductVariation::whereNotNull('variation_template_value_id')->select('id', 'product_id', 'variation_template_value_id')->get();
            
            $allVariations = [];
            foreach($product_with_variations as $variation) {
                $product_name = $variation->product->name;
                $variation_template_name = $variation->variationTemplateValue->variationTemplate->name ?? '';
                $variation_template_value_name = $variation->variationTemplateValue->name ?? '';
                $variation['product_variation_name'] ="$product_name - ($variation_template_name - $variation_template_value_name)";
                ;
                $allVariations[] = $variation;
            };
            $toResponseData = $allVariations;
        }

        return response()->json($toResponseData);
    }
}
