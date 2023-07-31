<?php

namespace Modules\Service\Http\Controllers;

use Exception;
use App\Models\Product\UOM;
use App\Models\Product\Unit;
use App\Models\Contact\Contact;
use App\Models\Product\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Modules\Service\Entities\Services;
use App\Models\Product\ProductVariation;
use Yajra\DataTables\Facades\DataTables;
use App\Models\settings\businessLocation;
use Modules\Service\Entities\ServiceSale;
use Modules\Service\Entities\ServiceType;
use Modules\Service\Entities\ServiceProducts;
use App\Models\Product\VariationTemplateValues;
use Modules\Service\Entities\ServiceSaleDetail;
use Modules\Service\Entities\ServiceUsedProducts;
use Modules\Service\Http\Requests\ServiceSale\ServiceSaleCreateRequest;
use Modules\Service\Http\Requests\ServiceSale\ServiceSaleUpdateRequest;

class ServiceSalesController extends Controller
{
    public function datas()
    {
        $service_sales = ServiceSale::with('businessLocation', 'contact')->get();

        return DataTables::of($service_sales)
        ->addColumn('action', function($service_sale){
            return $service_sale->id;
        })
        ->editColumn('contact_id', function($service_sale){
            return $service_sale->contact->getFullNameAttribute();
        })
        ->editColumn('business_location_id', function($service_sale){
            return $service_sale->businessLocation->name;
        })
        ->editColumn('created_at', function($service_sale){
            return $service_sale->created_at->toDayDateTimeString();
        })
        ->editColumn('total_sale_amount', function($service_sale) {
            return 'Ks ' . $service_sale->total_sale_amount * 1;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function index()
    {
        return view('service::service-sales.service-saleList');
    }

    public function view(ServiceSale $serviceSale)
    {
        $customer_name = $serviceSale->contact->getFullNameAttribute();
        $customer_phone = $serviceSale->contact()->select('mobile')->get();
        $business = $serviceSale->businessLocation()->select('name', 'state', 'city', 'country', 'zip_code')->get();
        $date = $serviceSale->created_at->toDayDateTimeString();
        $service_sale_details = $serviceSale->serviceSaleDetails()->get();
        $services = Services::select('id', 'name')->get();
        $units = UOM::all();
        // dd($business);
        return response()->json(['customer_name' => $customer_name, 'customer_phone' => $customer_phone,
                                'business' => $business, 'serviceSale' => $serviceSale , 'date' => $date,
                                'service_sale_details' => $service_sale_details, 'services' => $services, 'units' => $units]);
    }

    public function add()
    {
        $service_types = ServiceType::all();
        $uoms = UOM::all();
        $locations = businessLocation::select('id', 'name')->get();
        $customers = Contact::where('type', 'Customer')
                            ->select('id', 'first_name', 'middle_name','last_name')
                            ->get();
        $services = Services::all();

        return view('service::service-sales.service-saleAdd', compact('locations', 'customers', 'service_types', 'uoms', 'services'));
    }

    public function create(ServiceSaleCreateRequest $request)
    {
        DB::beginTransaction();
        try{
            // dd($request->all());
            $service_sale = ServiceSale::create([
                'business_location_id' => $request->business_location_id,
                'contact_id' => $request->contact_id,
                'service_voucher_no' => $request->service_voucher_no,
                'service_status' => $request->status,
                'sale_amount' => $request->sale_amount,
                'service_discount_type' => $request->parent_discount_type,
                'discount_amount' => $request->total_discount_amount,
                'total_sale_amount' => $request->total_sale_amount,
                'paid_amount' => $request->paid_amount,
                'balance' => $request->balance_amount,
                'remark' => $request->remark,
                'confirm_at' => now(),
                'confirm_by' => auth()->id(),
                'created_by' => auth()->id()
            ]);

            $formatData = [];
            $serviceId_sup = $request->service_used_id; // sup = service used product
            $innerFormatData = [];
            // print_r($serviceId_sup);
            foreach($request->service_id as $key => $value){
                $serviceSaleDetail = ServiceSaleDetail::create([
                    'service_sale_id' => $service_sale->id,
                    'service_id' => $value,
                    'uom_id' => $request->uom_id[$key],
                    'quantity' => $request->quantity[$key],
                    'sale_price_without_discount' => $request->price[$key],
                    'service_detail_discount_type' => $request->child_dis_type[$key],
                    'discount_amount' => $request->dis_amount[$key],
                    'sale_price' => $request->sale_price[$key],
                    'created_at' => now(),
                    'created_by' => auth()->id()
                ]);
                if($serviceId_sup){
                    foreach($serviceId_sup as $innerKey => $innerValue){
                        if($innerValue !== $value) continue;
                        $innerFormatData[] = [
                            'service_sale_detail_id' => $serviceSaleDetail->id,
                            'service_id' => $innerValue,
                            'product_id' => $request->service_used_product_id[$innerKey],
                            'variation_id' => $request->service_used_vari_id[$innerKey],
                            'quantity' => $request->service_used_quantity[$innerKey],
                            'uom_id' => $request->service_used_unit_id[$innerKey]
                        ];
                    }
                }
            }
            // dd($innerFormatData);
            DB::table('service_used_products')->insert($innerFormatData);
            // dd($request->all());
            DB::commit();
            return redirect(route('service-sale'))->with('message', 'Created sucessfully service sale');
        }catch (Exception $e){
            Log::error($e->getMessage());
            DB::rollBack();
        }
    }

    public function edit(ServiceSale $serviceSale)
    {
        $service_types = ServiceType::all();
        $uoms = UOM::all();
        $locations = businessLocation::select('id', 'name')->get();
        $customers = Contact::where('type', 'Customer')
                            ->select('id', 'first_name', 'middle_name','last_name')
                            ->get();

        $services = Services::all();
        $products = Product::select('id', 'name')->get();
        $productVariations = ProductVariation::query();

        $serviceUsedProducts = [];

        foreach($serviceSale->serviceSaleDetails as $value){
            if($value->serviceUsedProducts->isEmpty()) continue;

            // Get unique service IDs and their names
            $serviceIds = $value->serviceUsedProducts->pluck('service_id')->unique()->toArray();
            $innerServices = $services->whereIn('id', $serviceIds)->pluck('name', 'id');

            // Get unique product IDs and their names
            $productIds = $value->serviceUsedProducts->pluck('product_id')->unique()->toArray();
            $innerProducts = $products->whereIn('id', $productIds)->pluck('name', 'id');

            // Get unique product variation IDs and their details with variationTemplateValue
            $productVariationIds = $value->serviceUsedProducts->pluck('variation_id')->unique()->toArray();
            $innerProductVariations = ProductVariation::whereIn('id', $productVariationIds)->with('variationTemplateValue')->get();

            foreach($value->serviceUsedProducts as $innerValue){
                // Get the service name based on the service ID
                $serviceName = $innerServices[$innerValue->service_id] ?? null;

                // Get the product name based on the product ID
                $productName = $innerProducts[$innerValue->product_id] ?? null;

                // Get the variation value name based on the variation ID
                $variationValueName = $innerProductVariations->firstWhere('id', $innerValue->variation_id)->variationTemplateValue->name ?? 'single product';

                $serviceUsedProducts[] = [
                    'service_product_id' => $innerValue->product_id,
                    'service_name' => $serviceName,
                    'product_id' => $innerValue->product_id,
                    'product_name' => $productName,
                    'quantity' => $innerValue->quantity,
                    'service_id' => $innerValue->service_id,
                    'uom_id' => $innerValue->uom_id,
                    'product_variation_id' => $innerValue->variation_id,
                    'variation_value' => $variationValueName
                ];
            }
        }

        return view('service::service-sales.service-saleEdit',
        compact('serviceSale', 'uoms', 'locations', 'customers', 'services', 'service_types', 'serviceUsedProducts'));
    }

    public function update(ServiceSaleUpdateRequest $request, ServiceSale $serviceSale)
    {
        DB::beginTransaction();
        try{
            // dd($request->all());
            $serviceSale->business_location_id = $request->business_location_id;
            $serviceSale->contact_id = $request->contact_id;
            $serviceSale->service_voucher_no = $request->service_voucher_no;
            $serviceSale->service_status = $request->status;
            $serviceSale->sale_amount = $request->sale_amount;
            $serviceSale->service_discount_type = $request->parent_discount_type;
            $serviceSale->discount_amount = $request->total_discount_amount;
            $serviceSale->total_sale_amount = $request->total_sale_amount;
            $serviceSale->paid_amount = $request->paid_amount;
            $serviceSale->balance = $request->balance_amount;
            $serviceSale->remark = $request->remark;
            $serviceSale->confirm_at = now();
            $serviceSale->confirm_by = auth()->id();
            $serviceSale->updated_by = auth()->id();

            $serviceSale->update();

            $serviceSaleDetail = ServiceSaleDetail::query();
            $serviceSaleDetailId = $serviceSaleDetail->where('service_sale_id', $serviceSale->id)->pluck('id')->toArray();
            $array_diff_to_del = array_diff($serviceSaleDetailId, $request->service_detail_id);
            if(isset($array_diff_to_del)){
                // Delete Service Used Product
                $serviceUsedProductIds = ServiceUsedProducts::whereIn('service_sale_detail_id', $array_diff_to_del)->get()->pluck('id');
                ServiceUsedProducts::destroy($serviceUsedProductIds);

                // Delete Service Sale Detail
                ServiceSaleDetail::whereIn('id', $array_diff_to_del)->update(['deleted_by' => auth()->id()]);
                ServiceSaleDetail::whereIn('id', $array_diff_to_del)->update(['updated_by' => auth()->id()]);
                ServiceSaleDetail::destroy($array_diff_to_del);
            }

            $formatData = [];
            foreach($request->service_id as $key => $value){
                $formatData[] = [
                    'id' => $request->service_detail_id[$key],
                    'service_sale_id' => $serviceSale->id,
                    'service_id' => $request->service_id[$key],
                    'uom_id' => $request->uom_id[$key],
                    'quantity' => $request->quantity[$key],
                    'sale_price_without_discount' => $request->price[$key],
                    'service_detail_discount_type' => $request->child_dis_type[$key],
                    'discount_amount' => $request->dis_amount[$key],
                    'sale_price' => $request->sale_price[$key],
                    'updated_at' => now(),
                    'updated_by' => auth()->id()
                ];
            }

            $createServiceUsedProductFormat = [];
            foreach ($formatData as $v) {
                if (isset($v['id'])) {
                    ServiceSaleDetail::where('id', $v['id'])->update($v);
                } else {
                    $newServiceSaleDetail = ServiceSaleDetail::create($v);
                    // service use product အတွက် data အသစ်ပါလာရင် create လုပ်ဖို့ format ချမယ်
                    foreach($request->service_used_id as $key => $value){
                        if($value !== $v['service_id']) continue;

                        $createServiceUsedProductFormat[] = [
                            'service_sale_detail_id' => $newServiceSaleDetail->id,
                            'service_id' => $request->service_used_id[$key],
                            'product_id' => $request->service_used_product_id[$key],
                            'variation_id' => $request->service_used_vari_id[$key],
                            'quantity' => $request->service_used_quantity[$key],
                            'uom_id' => $request->service_used_unit_id[$key]
                        ];
                    }
                }
            }
            if(!empty($createServiceUsedProductFormat)){
                DB::table('service_used_products')->insert($createServiceUsedProductFormat);
            }

            DB::commit();
            return redirect(route('service-sale'))->with('message', 'Updated sucessfully service sale');
        }catch (Exception $e){
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }

    public function delete(ServiceSale $serviceSale)
    {
        DB::beginTransaction();
        try{
            $serviceSaleDetailIds = ServiceSaleDetail::where('service_sale_id', $serviceSale->id)->get()->pluck('id');
            ServiceSaleDetail::whereIn('id', $serviceSaleDetailIds)->update(['deleted_by' => auth()->id()]);

            $serviceSale->deleted_by = auth()->id();
            $serviceSale->save();

            $serviceUsedProductIds = ServiceUsedProducts::whereIn('service_sale_detail_id', $serviceSaleDetailIds)->get()->pluck('id');

            $serviceSale->delete();
            ServiceSaleDetail::destroy($serviceSaleDetailIds);
            ServiceUsedProducts::destroy($serviceUsedProductIds);

            DB::commit();
            return response()->json(['message' => 'Deleted Sucessfully Servic Sale']);
        }catch (Exception $e){
            Log::error($e->getMessage());
            DB::rollBack();
        }
    }

    public function getServiceProducts($id)
    {

        $serviceProducts = ServiceProducts::where('service_id', $id)->get();

        $variousDatas = $serviceProducts->map(function($item) {
            $productVariation = ProductVariation::with('variationTemplateValue', 'product')->where('id', $item->variation_id)->get();

            return [
                'service_product_id' => $item->id,
                'service_name' => $item->service->name,
                'product_id' => $item->product_id,
                'product_name' => $productVariation[0]->product->name ?? null,
                'quantity' => $item->quantity,
                'service_id' => $item->service_id,
                'uom_id' => $item->uom_id,
                'product_variation_id' => $item->variation_id,
                'variation_value' => $productVariation[0]->variationTemplateValue->name ?? 'single product'
            ];
        });

        return response()->json(['variousDatas' => $variousDatas]);
    }
}
