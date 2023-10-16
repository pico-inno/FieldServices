<?php

namespace App\Http\Controllers\userManagement;

use App\Actions\userManagement\RoleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Jobs\RoleAndPermission\RolePermissionJob;
use App\Models\BusinessUser;
use App\Models\Feature;
use App\Models\Role;
use App\Models\RolePermission;

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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('App.userManagement.roles.index', [
            'roles' => Role::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('App.userManagement.roles.add', [
            'features' =>  Feature::with('permissions')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreRoleRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoleRequest $request, RoleAction $createRoleAction)
    {
        $permissionsId = $request->except('_token', 'name');
        $permissions = array_values($permissionsId);

        //role count validation
        if (count($permissionsId) == 0) {
            return back()->with(['permissionError' => 'At least one permission must be specified']);
        }

        $role = $createRoleAction->create(['name' => $request->name]);
        $role_id = $role->id;

        dispatch(new RolePermissionJob($role_id, $permissions));

        return redirect(route('roles.index'))->with('scuuess-toastr', 'New role created successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $permission_records = RolePermission::all()->where('role_id', $role->id);
        foreach ($permission_records as $permission_record) {
            $role_permissions[] = $permission_record->permission_id;
        }

        $features = Feature::with('permissions')->get();

        return view('App.userManagement.roles.edit', [
            "role" => Role::find($role->id),
            "role_permissions" => $role_permissions,
            "features" => $features,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateRoleRequest $request
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, Role $role, RoleAction $roleAction)
    {
        $permissionsId = $request->except('_token', '_method', 'key', 'name');
        $roleAction->update($role->id, $request->name, $permissionsId);

        return redirect(route('roles.index'))->with(['scuuess-toastr' => 'Role Successfully Updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role, RoleAction $roleAction)
    {

        $role_id = isset(BusinessUser::where('role_id', $role->id)->first()->role_id) ? BusinessUser::where('role_id', $role->id)->first()->role_id : true;

        if ($role_id === true) {
            $roleAction->delete($role->id);
            return back()->with('success', 'Role deleted successfully');
        } else {
            return back()->with('error-message', 'This role is associated with one or more user accounts. Delete the user accounts or associate them with a different role.');
        }

    }
}
