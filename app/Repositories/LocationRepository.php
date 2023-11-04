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
    public function getAllPermitted(){
        $accessLocation= old('access_location_ids', unserialize(Auth::user()->access_location_ids));
        return  businessLocation::where('is_active', 1)
            ->whereNotIn('location_type', [2])
            ->when($accessLocation[0] != 0,function($query) use($accessLocation){
                $query->whereIn('id',$accessLocation);
            })
            ->get();
    }
    public static function getTransactionLocation(){
        return  businessLocation::where('is_active', 1)
                ->whereNotIn('location_type',[2])->get();
    }
}
