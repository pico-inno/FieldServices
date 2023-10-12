<?php

namespace Database\Factories\default;

use App\Models\Product\UOM;
use App\Models\locationType;
use App\Models\RolePermission;
use App\Models\Contact\Contact;
use App\Models\Product\UnitCategory;
use Illuminate\Support\Facades\Artisan;
use App\Models\settings\businessLocation;

class defaultDataFactory
{

    public function seed()
    {
        $permissionCount = RolePermission::count();
        if ($permissionCount <= 0) {
            Artisan::call('db:seed --class=RolesTableSeeder');
        }

        $uomCount = UOM::count();
        $unitCategoryCount = UnitCategory::count();
        if ($uomCount <= 0 && $unitCategoryCount <= 0) {
            Artisan::call('db:seed --class=UoMSeeder');
        }

        $contactCount = Contact::count();
        if ($contactCount <= 0) {
            Artisan::call('db:seed --class=ContactWalkInTableSeeder');
        }

        $contactCount = locationType::count();
        if ($contactCount <= 0) {
            Artisan::call('db:seed --class=LocationTypeSeeder');
        }
        $contactCount = businessLocation::count();
        if ($contactCount <= 0) {
            Artisan::call('db:seed --class=LocationTableSeeder');
        }

        $contactCount = businessLocation::count();
        if ($contactCount <= 0) {
            Artisan::call('db:seed --class=LocationTableSeeder');
        }
        $defaultPriceListCheck = getData('defaultPriceListId');
        if (!$defaultPriceListCheck) {
            Artisan::call('db:seed --class=defaultPriceListSeeder');
        }
    }
}
