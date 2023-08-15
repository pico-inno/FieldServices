<?php

namespace Modules\OrderDisplay\Http\Controllers;

use App\Models\posRegisters;
use App\Models\posSession\posRegisterSessions;
use App\Models\resOrders;
use App\Models\sale\sale_details;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use PDO;

class ResOrderController extends Controller
{
    public function restOrderData(Request $request){
        $displayData=$request->displayData;
        $order_status=$request->orderStatus;
        $location_id=$displayData['location_id'];
        $posRegisterId=json_decode($displayData['pos_register_id']);
        $posSessions=[];
        if($posRegisterId != 0 && $posRegisterId !=null){
            $posSessions = posRegisterSessions::whereIn('pos_register_id', $posRegisterId)->where('status', 'open')->pluck('id');
        }
        $product_category_id=json_decode($displayData['product_category_id']);
        $resOrders=resOrders::where('order_status',$order_status)
                            ->when($location_id,function($q,$location_id){
                                $q->where('location_id',$location_id);
                            })
                            ->when($posRegisterId,function($q,$posRegisterId){
                               if($posRegisterId==0){
                                    $q;
                               }else{
                                    $q->whereIn('pos_register_id',$posRegisterId);
                               }
                            })
                            ->with(['saleDetail'=>function($q) use ($product_category_id){
                                $q->with(['uom',
                                    'product'=>function($pq)use ($product_category_id){
                                        if($product_category_id==0){
                                            $pq;
                                        }else{
                                            $pq->whereIn('category_id',$product_category_id);
                                        }
                                    }
                                ]);
                            }])->get();
        // $resOrders=resOrders::where('order_status',$order_status)->with('saleDetail')->get();
        return response()->json($resOrders, 200);
    }

    public function changeOrderStatus(Request $request){
        $order_status=$request->status;
        $id=$request->id;
        $saleDetailId = $request->saleDetailId ;
        logger($request->toArray());
        if($saleDetailId != 0){
            sale_details::where('rest_order_id', $id)->where('id', $saleDetailId)->update([
                'rest_order_status' => $order_status,
            ]);
            return response()->json([
            ], 200);
        }
        resOrders::where('id', $id)->first()->update([
            'order_status' => $order_status,
        ]);
        sale_details::where('rest_order_id', $id)->update([
            'rest_order_status' => $order_status,
        ]);
        return response()->json([
            'success' => 'Successfully updated'
        ], 200);
    }
}
