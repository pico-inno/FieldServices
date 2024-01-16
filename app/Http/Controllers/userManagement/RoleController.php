<?php

namespace App\Http\Controllers\userManagement;

use App\Actions\userManagement\RoleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use App\Services\UserManagement\RoleService;
use Illuminate\Support\Facades\DB;

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
        try {
            DB::beginTransaction();
            RoleService::createRolePermission($request, $createRoleAction);
            DB::commit();
            activity('role-permission')
                ->log('New role creation has been success')
                ->event('create')
                ->status('success')
                ->save();

            return redirect(route('roles.index'))->with('scuuess-toastr', 'New role created successfully');
        }catch (\Exception $exception){
            DB::rollBack();
            activity('role-permission')
                ->log('New role creation has been fail')
                ->event('create')
                ->status('fail')
                ->save();

            return redirect(route('roles.index'))->with('error', 'New role creation failed');
        }
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
        try {
            DB::beginTransaction();
            $permissionsId = $request->except('_token', '_method', 'key', 'name');
            $roleAction->update($role->id, $request->name, $permissionsId);
            DB::commit();
            activity('role-permission')
                ->log('Role update has been success')
                ->event('update')
                ->status('success')
                ->save();
            return redirect(route('roles.index'))->with(['scuuess-toastr' => 'Role Successfully Updated']);
        }catch (\Exception $exception){
            DB::rollBack();
            activity('role-permission')
                ->log('Role update has been fail')
                ->event('update')
                ->status('fail')
                ->save();
            return redirect(route('roles.index'))->with(['error' => 'Role update failed']);
        }
    }

    public function destroy(Role $role, RoleAction $roleAction)
    {
        if (RoleService::checkRoleUsed($role->id) === true) {
            try {
                DB::beginTransaction();
                $roleAction->delete($role->id);
                DB::commit();
                activity('role-permission')
                    ->log('Role deletion has been success')
                    ->event('delete')
                    ->status('success')
                    ->save();
                return back()->with('success', 'Role deleted successfully');
            }catch (\Exception $exception){
                DB::rollBack();
                activity('role-permission')
                    ->log('Role deletion has been fail')
                    ->event('delete')
                    ->status('fail')
                    ->save();
                return back()->with('error', 'Role deletion failed');
            }
        } else {
            activity('role-permission')
                ->log('Role deletion has been warn due to associated with one or more user accounts')
                ->event('delete')
                ->status('warn')
                ->save();
            return back()->with('error-message', 'This role is associated with one or more user accounts. Delete the user accounts or associate them with a different role.');
        }

    }
}
