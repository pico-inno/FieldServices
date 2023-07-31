<?php

namespace Modules\Service\Http\Controllers;

use Exception;
use App\Models\Product\UOM;
use Illuminate\Support\Str;
use App\Models\Product\Unit;
use App\Models\Product\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Modules\Service\Entities\Services;
use App\Models\Product\ProductVariation;
use Yajra\DataTables\Facades\DataTables;
use Modules\Service\Entities\ServiceType;
use Modules\Service\Entities\ServiceProducts;
use App\Models\Product\VariationTemplateValues;
use Modules\Service\Http\Requests\Service\ServiceCreateRequest;
use Modules\Service\Http\Requests\Service\ServiceUpdateRequest;

class ServiceController extends Controller
{
    public function datas()
    {
        $services = Services::all();

        return DataTables::of($services)
        ->addColumn('service_type', function($service){
            return $service->serviceType->name ?? null;
        })
        ->addColumn('uom', function($service){
            return $service->uom->name ?? null;
        })
        ->addColumn('action', function($service){
            return $service->id;
        })
        ->rawColumns(['action', 'service_type','uom'])
        ->make(true);
    }

    public function index()
    {
       return view('service::services.serviceList');
    }

    public function add()
    {
        $service_types = ServiceType::all();
        $uoms = UOM::all();

       return view('service::services.serviceAdd', compact('service_types', 'uoms'));
    }

    public function create(ServiceCreateRequest $request)
    {
        DB::beginTransaction();
        try{
            $service = Services::create([
                'name' => $request->name,
                'service_code' => $request->service_code,
                'service_type_id' => $request->service_type_id,
                'active' => $request->active ?? 0,
                'uom_id' => $request->uom_id,
                'price' => $request->price
            ]);

            if($request->product_id){
                $formatData = [];
                foreach($request->product_id as $index => $value){
                    $formatData[] = [
                        'service_id' => $service->id,
                        'product_id' => $value,
                        'variation_id' => $request->variation_id[$index],
                        'uom_id' => $request->product_uom_id[$index],
                        'quantity' => $request->product_quantity[$index]
                    ];
                }
                DB::table('service_products')->insert($formatData);
            }

            DB::commit();

            if($request->close_modal){
                return back();
            }

            if(!$request->close_modal){
                return redirect(route('service'))->with('message', 'Created sucessfully service');
            }
        }catch (Exception $e){
            Log::error($e->getMessage());
            DB::rollBack();
        }

    }

    public function edit(Services $service)
    {
        $service_types = ServiceType::all();
        $uoms = UOM::all();

        // FOR SERVICE PRODUCTS
        $serviceProducts = ServiceProducts::where('service_id', $service->id)->get();

        $products = [];
        $variations = [];
        if(!$serviceProducts->isEmpty()){
            $productIds = $serviceProducts->pluck('product_id');
            $products = Product::whereIn('id', $productIds)->select('id', 'name')->get();

            $productVariationIds = $serviceProducts->pluck('variation_id');
            $productVariation = ProductVariation::with('variationTemplateValue')->whereIn('id', $productVariationIds)->get();
            $variations = $productVariation->map(function($item){
                return [
                    'id' => $item->id,
                    'name' => $item->variationTemplateValue->name ?? null
                ];
            });
        }

       return view('service::services.serviceEdit',
                compact('service', 'service_types', 'uoms', 'serviceProducts', 'products', 'variations'));
    }

    public function update(Services $service, ServiceUpdateRequest $request)
    {
        DB::beginTransaction();
        try{
            $service->name = $request->name;
            $service->service_code = $request->service_code;
            $service->service_type_id = $request->service_type_id;
            $service->active = $request->active ?? 0;
            $service->uom_id = $request->uom_id;
            $service->price = $request->price;

            $service->update();

            $serviceProducts = ServiceProducts::query();

            if(!$request->service_product_id){
                $serviceProductIds = $serviceProducts->where('service_id', $service->id)->pluck('id')->toArray();
                ServiceProducts::destroy($serviceProductIds);
            }

            if($request->service_product_id){
                $serviceProductIds = $serviceProducts->where('service_id', $service->id)->pluck('id')->toArray();
                $array_diff_to_del = array_diff($serviceProductIds, $request->service_product_id);
                if(isset($array_diff_to_del)){
                    ServiceProducts::destroy($array_diff_to_del);
                }

                $formatData = [];
                foreach($request->product_id as $key => $value){
                    $formatData[] = [
                        'id' => $request->service_product_id[$key],
                        'service_id' => $service->id,
                        'product_id' => $value,
                        'variation_id' => $request->variation_id[$key],
                        'uom_id' => $request->product_uom_id[$key],
                        'quantity' => $request->product_quantity[$key]
                    ];
                }

                foreach ($formatData as $v) {
                    if (isset($v['id'])) {
                        ServiceProducts::where('id', $v['id'])->update($v);
                    } else {
                        ServiceProducts::create($v);
                    }
                }
            }
            DB::commit();

            return redirect(route('service'))->with('message', 'Updated sucessfully service');
        }catch (Exception $e){
            DB::rollBack();
        }
    }

    public function delete(Services $service)
    {
        DB::beginTransaction();
        try{
            $serviceProductIds = ServiceProducts::where('service_id', $service->id)->get()->pluck('id');

            ServiceProducts::destroy($serviceProductIds);
            $service->delete();

            DB::commit();
            return response()->json(['message' => 'Deleted sucessfully service']);
        }catch (Exception $e){
            DB::rollBack();
        }
    }

    public function getProducts()
    {
        $text = request('search') ?? null;
        $searchProduct = [];
        if($text !== null){
            $searchProduct = Product::with('productVariations')
                                    ->where('name', 'Like', '%' . request('search') . '%')
                                    ->orWhere('sku', 'Like', '%' . request('search') . '%')->get();
        }

        $raw_variationTemplateId = [];
        foreach($searchProduct as $value){
            $raw_variationTemplateId[] = $value->productVariations->map( fn($item) => $item['variation_template_value_id']);
        }
        $variationTemplateValueId = collect($raw_variationTemplateId)->flatten()->unique()->values()->filter();
        $variationTemplateValue = VariationTemplateValues::whereIn('id', $variationTemplateValueId)->select('id','name')->get();

        return response()->json(['search_products' => $searchProduct, 'variation_template' => $variationTemplateValue]);
    }
}
