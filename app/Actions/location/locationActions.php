<?php

namespace App\Actions\location;

use App\Models\locationAddress;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\settings\businessLocation;
use App\Models\settings\businessSettings;

class locationActions
{
    public function createLocation($data){

        try {
            DB::beginTransaction();
            $location_count = businessLocation::count();
            $location_code = $data->location_id ?? sprintf('BL' . '%03d', ($location_count + 1));
            // dd(Auth::user());
            $locationData = [
                'business_id' => Auth::user()->business_id,
                'location_code' => $location_code,
                'name' => $data['name'],
                'is_acitve' => $data['is_acitve'] ?? 0,
                'allow_purchase_order' => $data['allow_purchase_order'] ?? 0,
                'allow_sale_order' => $data['allow_sale_order'] ?? 0,
                'parent_location_id' => $data['parent_location_id'],
                'location_type' => $data['location_type'],
                'inventory_flow' => $data['inventory_flow'],

            ];
            $location=businessLocation::create($locationData);
            DB::commit();
            return $location;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception($th->getMessage());
        }
    }

    public function createLocationAddress($data,$location){
        try {
            DB::beginTransaction();
            $address = [
                'location_id' => $location->id,
                'mobile' => $data['mobile'],
                'alternate_number' => $data['alternate_number'],
                'email' => $data['email'],
                'address' => $data['address'] ,
                'country' => $data['country'],
                'state' => $data['state'],
                'city' => $data['city'],
                'zip_postal_code' => $data['zip_postal_code'],

            ];
            locationAddress::create($address);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception($th->getMessage());
        }
    }
}
