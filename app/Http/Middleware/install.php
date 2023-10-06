<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\BusinessUser;
use App\Models\Contact\Contact;
use App\Models\Product\UnitCategory;
use App\Models\Product\UOM;
use Illuminate\Http\Request;
use App\Models\RolePermission;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use App\Models\settings\businessSettings;
use Symfony\Component\HttpFoundation\Response;

class install
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (Schema::hasTable('business_users') && Schema::hasTable('permissions') && Schema::hasTable('role_permissions') && Schema::hasTable('business_settings')) {
                $businessUserCount = BusinessUser::count();
                $businessCount = businessSettings::count();
                if ($businessUserCount == 0 && $businessCount == 0) {
                    $this->seeding();
                    return redirect()->route('activationForm');
                } else {
                    return redirect('/');
                };
            } else {
                return $next($request);
            };
        } catch (\Throwable $th) {
            return $next($request);
        }
    }
    public function seeding(){
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
    }
}
