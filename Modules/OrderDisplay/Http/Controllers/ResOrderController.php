<?php

namespace Modules\OrderDisplay\Http\Controllers;

use App\Models\resOrders;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;

class ResOrderController extends Controller
{
    public function restOrderData(){
        $resOrders=resOrders::with('saleDetail')->get();
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
