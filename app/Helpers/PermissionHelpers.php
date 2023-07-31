<?php

namespace App\Helpers;

use App\Models\RolePermission;
use Illuminate\Support\Facades\Auth;

class PermissionHelpers{
    // get user permission
    public static function permissions()
    {
        $permissions = RolePermission::select('permissions.name as permissions','features.name as feature')
            ->where('role_id',Auth::user()->role_id)
            ->leftJoin('permissions','permissions.id','role_permissions.permission_id')
            ->leftJoin('features','features.id','permissions.feature_id')
            ->get();
        return $permissions;

    }
    public static function checkPermission(String $feature,String $permission): bool
    {

        $AuthUserpermissions= self::permissions();
        foreach($AuthUserpermissions as $p){
            if($p->permissions==$permission && $p->feature==$feature ){
                return true;
            }
        }
        return false;
    }
}
