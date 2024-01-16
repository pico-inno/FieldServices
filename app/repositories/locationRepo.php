<?php

namespace App\repositories;

use App\Models\settings\businessLocation;

class locationRepo
{
    public static function getTransactionLocation(){
        return  businessLocation::where('is_active', 1)
                ->whereNotIn('location_type',[3])->get();
    }
}
