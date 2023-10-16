<?php
namespace App\Actions\userManagement;

use App\Jobs\RoleAndPermission\DeleteRoleJob;
use App\Jobs\RoleAndPermission\RolePermissionUpdateJob;
use App\Models\BusinessUser;
use App\Models\Role;

class RoleAction
{
    public function create($roleData)
    {
        return Role::create($roleData);
    }

    public function update($role_id, $role_name, $permissions)
    {
        dispatch(new RolePermissionUpdateJob($role_id, $role_name, $permissions));
    }

    public function delete($role_id)
    {
        $associatedUser = BusinessUser::where('role_id', $role_id)->first();

        if (!$associatedUser) {
            dispatch(new DeleteRoleJob($role_id));
            return true;
        } else {
            return false;
        }
    }
}
