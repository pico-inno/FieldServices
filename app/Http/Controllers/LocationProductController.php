<?php

namespace App\Http\Controllers;

use App\Models\locationProduct;
use App\Models\settings\businessLocation;
use Illuminate\Http\Request;

class LocationProductController extends Controller
{
    public function store(Request $request){
        $locationIds = $request->locationIds;
        $productIds = $request->productIds;
        foreach ($locationIds as $locationId) {
            if($locationId){
                foreach ($productIds as $productId) {
                    if($productId){
                        $check = locationProduct::where('location_id', $locationId)->where('product_id', $productId)->exists();
                        if (!$check) {
                            locationProduct::create([
                                'location_id' => $locationId,
                                'product_id' => $productId
                            ]);
                        }
                    }
                }
            }
        }
        return response()->json([
            'success'=>'Successfully Assigned'
        ], 200);


    }
    public function remove(Request $request)
    {
        $locationIds = $request->locationIds;
        $productIds = $request->productIds;
        foreach ($locationIds as $locationId) {
            if($locationId){
                foreach ($productIds as $productId) {
                    if($productId){
                        locationProduct::where('location_id', $locationId)
                        ->where('product_id', $productId)
                        ->delete();
                    }
                }
            }
        }
        return response()->json([
            'success' => 'Successfully Assigned'
        ], 200);
    }
}
