<?php

namespace App\Services\UserManagement;

use App\Jobs\RoleAndPermission\RolePermissionJob;
use App\Models\BusinessUser;
use App\Models\Feature;
use App\Models\RolePermission;
use Illuminate\Support\Facades\DB;

class RoleService
{
    public static function getFeatureWithPermissions(){
        return Feature::with('permissions')->get();
    }

    public static function getRolePermissions($roleId)
    {
        $permission_records = RolePermission::all()->where('role_id', $roleId);
        foreach ($permission_records as $permission_record) {
            $role_permissions[] = $permission_record->permission_id;
        }

        return $role_permissions;
    }

    public static function checkRoleUsed($roleId){
        return optional(BusinessUser::where('role_id', $roleId)->first())->role_id ?? true;
    }

    public static function createRolePermission($request, $createRoleAction) {
        $roleServices = new RoleService();
        return $roleServices->saveRolePermissionProcess($request, $createRoleAction);
    }

    private function saveRolePermissionProcess($request, $createRoleAction)
    {

            $permissionsId = $request->except('_token', 'name');
            $permissions = array_values($permissionsId);

            if (count($permissionsId) == 0) {
                return back()->with(['permissionError' => 'At least one permission must be specified']);
            }

            $role = $createRoleAction->create(['name' => $request->name]);
            $role_id = $role->id;

            dispatch(new RolePermissionJob($role_id, $permissions));

            return $role;

    }
}
