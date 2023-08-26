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
                'price_list_type' => $request->price_list_type,
                'business_id' => $business_id,
                'currency_id' => $request->currency_id,
                'name' => $request->name,
                'description' => $request->description
            ])->id;
    
            $hasCreation = $this->hasCreatePricelistDetail($request);
            if($hasCreation){
                $this->insertPricelistDetail($request, $price_list_id);
            }            

            DB::commit();

            return redirect('/price-list-detail')->with('message', 'Created Sucessfully PriceList');
        }catch(Exception $e){
            DB::rollBack();
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
            
            $hasCreation = $this->hasCreatePricelistDetail($request);
            if($hasCreation){
                $this->insertPricelistDetail($request, $priceList->id, false);
            }else{
                $dbPricelistDetailIds = PriceListDetails::where('pricelist_id', $priceList->id)->get()->pluck('id')->toArray();
                if(!empty($dbPricelistDetailIds)){
                    PriceListDetails::destroy($dbPricelistDetailIds);
                }
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

    private function hasCreatePricelistDetail($request)
    {
        $applyType = $request->apply_type[0];
        $applyValue = $request->apply_value;
        $minQty = $request->min_qty[0];
        $calType = $request->cal_type[0];
        $calValue = $request->cal_val[0];
        
        if($applyType == null || $applyValue == null || $minQty == null || $calType == null || $calValue == null){
            return false;
        }else{
            return true;
        }
    }

    private function insertPricelistDetail($request, $pricelistId, $isCreating = true)
    {
        if(!$isCreating){
            // Get all pricelist-detail IDs of the pricelist from the database
            $dbPricelistDetailIds = PriceListDetails::where('pricelist_id', $pricelistId)->pluck('id');

            // Find pricelist-detail IDs to delete
            $pricelistDetailsToDelete = array_diff($dbPricelistDetailIds->toArray(), $request->price_list_detail_id);        
            if(!empty($pricelistDetailsToDelete)){
                PriceListDetails::destroy($pricelistDetailsToDelete);
            }
        }

        $pricelistDetails = [];
        foreach($request->apply_type as $index => $value){
            $pricelistData = [
                'pricelist_id' => $pricelistId,
                'applied_type' => $value,
                'applied_value' => $request->apply_value[$index],
                'min_qty' => $request->min_qty[$index],
                'from_date' => $request->start_date[$index],
                'to_date' => $request->end_date[$index],
                'cal_type' => $request->cal_type[$index],
                'cal_value' => $request->cal_val[$index],
                'base_price' => $request->base_price
            ];
            if(!$isCreating){
                $pricelistData['id'] = $request->price_list_detail_id[$index] ?? null;
            }
            $pricelistDetails[] = $pricelistData;
        }

        if($isCreating){
            PriceListDetails::insert($pricelistDetails);
        }
        if(!$isCreating){
            foreach ($pricelistDetails as $detail) {
                PriceListDetails::updateOrCreate(['id' => $detail['id']], $detail);
            }
        }
    }
}
