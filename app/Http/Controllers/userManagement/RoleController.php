<?php

namespace App\Http\Controllers\userManagement;

use App\Actions\userManagement\RoleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use App\Services\UserManagement\RoleService;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
        $this->middleware('canView:role')->only(['index', 'show']);
        $this->middleware('canCreate:role')->only(['create', 'store']);
        $this->middleware('canUpdate:role')->only(['edit', 'update']);
        $this->middleware('canDelete:role')->only('destroy');
    }

    public function index()
    {
        return view('App.userManagement.roles.index', [
            'roles' => Role::all(),
        ]);
    }

    public function create()
    {
        return view('App.userManagement.roles.add', [
            'features' =>  RoleService::getFeatureWithPermissions(),
        ]);
    }

    public function store(StoreRoleRequest $request, RoleAction $createRoleAction)
    {
        RoleService::createRolePermission($request, $createRoleAction);

        return redirect(route('roles.index'))->with('scuuess-toastr', 'New role created successfully');

    }

    public function show(Role $role)
    {
        //
    }

    public function edit(Role $role)
    {
        if ($role->id === 1){ return abort(401); } //for default role

        return view('App.userManagement.roles.edit', [
            "role" => $role,
            "role_permissions" => RoleService::getRolePermissions($role->id),
            "features" => RoleService::getFeatureWithPermissions(),
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role, RoleAction $roleAction)
    {
        $permissionsId = $request->except('_token', '_method', 'key', 'name');
        $roleAction->update($role->id, $request->name, $permissionsId);

        return redirect(route('roles.index'))->with(['scuuess-toastr' => 'Role Successfully Updated']);
    }

    public function destroy(Role $role, RoleAction $roleAction)
    {
        if (RoleService::checkRoleUsed($role->id) === true) {
            $roleAction->delete($role->id);
            return back()->with('success', 'Role deleted successfully');
        } else {
            return back()->with('error-message', 'This role is associated with one or more user accounts. Delete the user accounts or associate them with a different role.');
        }

    }
}
