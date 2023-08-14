<?php

namespace Modules\OrderDisplay\Http\Controllers;

use App\Models\resOrders;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;

class ResOrderController extends Controller
{
    public function restOrderData(Request $request){
        $displayData=$request->displayData;
        $order_status=$request->orderStatus;
        $location_id=$displayData['location_id'];
        $posRegisterId=json_decode($displayData['pos_register_id']);
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
        resOrders::where('id',$id)->first()->update([
            'order_status'=>$order_status,
        ]);
        return response()->json([
            'success'=>'Successfully updated'
        ], 200);
    }
}
