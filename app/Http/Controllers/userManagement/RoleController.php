<?php

namespace App\Http\Controllers\userManagement;

use App\Http\Controllers\Controller;
use App\Models\BusinessUser;
use App\Models\Feature;
use App\Models\Permission;
use App\Models\Role;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\RolePermission;
use App\Rules\CheckAtLeastOneCheckbox;
use Ramsey\Uuid\Rfc4122\Validator;

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

        $roles = Role::all();

        return view('App.userManagement.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        $features = Feature::with('permissions')->get();

        return view('App.userManagement.roles.add', compact('features'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreRoleRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoleRequest $request)
    {
        $validatedData = $request->validated();
        $permissionsId = $request->except('_token', 'name');
        $permissions = array_values($permissionsId);

        //role count validation
        if (count($permissionsId) == 0) {
            return back()->with(['permissionError' => 'At least one permission must be specified']);
        }

        //role name create and return role_id
        $role_id = Role::create([
            'name' => $request->name
        ])->id;

        // permission create
        foreach ($permissions as $permission) {
            RolePermission::create([
                'role_id' => $role_id,
                'permission_id' => $permission
            ]);
        }

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
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $permissionsId = $request->except('_token', '_method', 'key', 'name');

        //update role name
        $role = Role::where('id', $role->id)->first();
        $role->update([
            'name' => $request->name,
        ]);

        //update role permissions
        $role = Role::where('id', $role->id)->first();
        $role->permissions()->sync($permissionsId);

        return redirect(route('roles.index'))->with(['scuuess-toastr' => 'Role Successfully Updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {

        $role_id = isset(BusinessUser::where('role_id', $role->id)->first()->role_id) ? BusinessUser::where('role_id', $role->id)->first()->role_id : true;

        if ($role_id === true) {
            RolePermission::where('role_id', $role->id)->delete();
            Role::find($role->id)->delete();
            return back()->with('success', 'Role delete successfully');
        } else {
            return back()->with('error', 'This role is associated with one or more user accounts. Delete the user accounts or associate them with different role.');
        }
    }
}
