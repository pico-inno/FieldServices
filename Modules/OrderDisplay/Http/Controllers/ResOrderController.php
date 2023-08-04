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

}
