<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
use App\Models\settings\businessLocation;
use App\Repositories\interfaces\LocationRepositoryInterface;

class LocationRepository implements LocationRepositoryInterface
{
    public function getAll(){
        return businessLocation::all();
    }
    public function find($id){
        return businessLocation::where('id',$id)->first();
    }
    public function locationWithAccessControlQuery(){
        $accessLocationIds=[];
        $accessLocations = old('access_location_ids', unserialize(Auth::user()->access_location_ids));
        foreach ($accessLocations as $accessLocation) {
            $childLocationIDs = childLocationIDs($accessLocation);
            $accessLocationIds= [...$accessLocationIds,...$childLocationIDs];
        }
        $locationQuery =  businessLocation::where('is_active', 1)
        ->when($accessLocation[0] != 0, function ($query) use ($accessLocationIds) {
            $query->whereIn('id', $accessLocationIds);
        });
        return $locationQuery;
    }
    public function getWithAC(){
        $location=$this->locationWithAccessControlQuery()->get();
        return $location;
    }
    public function getforTx(){
        return $this->locationWithAccessControlQuery()->whereNotIn('location_type', [3])->get();
    }
    public static function getTransactionLocation(){
        $instance = new self();
        return  $instance->locationWithAccessControlQuery()->whereNotIn('location_type',[3])->with('locationAddress')->get();
    }
}
