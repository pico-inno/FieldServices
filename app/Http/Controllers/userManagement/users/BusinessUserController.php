<?php

namespace App\Http\Controllers\userManagement\users;

use App\Actions\userManagement\UserAction;
use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\BusinessUser;
use App\Http\Requests\StoreBusinessUserRequest;
use App\Http\Requests\UpdateBusinessUserRequest;
use App\Models\PersonalInfo;
use App\Models\Role;
use App\Models\settings\businessLocation;
use App\Services\StockReserveServices;
use App\Services\UserManagement\UserService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class BusinessUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','isActive']);
        $this->middleware('canView:user')->only(['index', 'show']);
        $this->middleware('canCreate:user')->only(['create', 'store']);
        $this->middleware('canUpdate:user')->only(['edit', 'update']);
        $this->middleware('canDelete:user')->only('destroy');
    }

//    public function getAllUsers()
//    {
//       return BusinessUser::with('personal_info','role')->get();
//    }


    public function index()
    {
        return view('App.userManagement.users.index', [
            'users' => UserService::getUsers(),
        ]);
    }


    public function create()
    {
        return view('App.userManagement.users.add', [
            'roles' => Role::all(),
            'locations' => businessLocation::all(),
        ]);
    }


    public function store(StoreBusinessUserRequest $request, UserAction $userAction)
    {
        try {
            DB::beginTransaction();
            $userAction->create($request);
            DB::commit();
            activity('user')->log('New business user creation has been success')
                ->event('create')
                ->status('success')
                ->save();
            return redirect(route('users.index'))->with(['success-swal'=>'User created successfully']);
        }catch (\Exception $exception){
            activity('user')->log('New user creation has been fail')
                ->event('create')
                ->status('fail')
                ->save();
            return redirect(route('users.index'))->with(['error'=>'User creation failed']);
        }
    }


    public function show($id)
    {
        return view('App.userManagement.users.view', [
            'user' => UserService::getUsers($id)
        ]);
    }


    public function edit($id)
    {
        return view('App.userManagement.users.edit', [
            'user' => UserService::getUsers($id),
            'roles' => Role::all(),
            'locations' => businessLocation::all(),
        ]);
    }

    public function update(UpdateBusinessUserRequest $request, $id, UserAction $userAction)
    {
        $request->validate([
            'username' => [
                'required',
                'string',
                Rule::unique('business_users')->ignore($id)]
        ]);

        try {
            DB::beginTransaction();
            $userAction->update($request, $id);
            DB::commit();
            activity('user')->log('Business user update has been success')
                ->event('update')
                ->status('success')
                ->save();

            return redirect(route('users.index'))->with(['scuuess-toastr'=>'User updated successfully']);
        }catch (\Exception $exception){
            DB::rollBack();
            activity('user')->log('Business user update has been fail')
                ->event('update')
                ->status('fail')
                ->save();
            return redirect(route('users.index'))->with(['error'=>'User update failed']);
        }
    }

    public function destroy($id, UserAction $userAction)
    {
        try {
            DB::beginTransaction();
            $userAction->delete($id);
            DB::commit();
            activity('user')->log('Business user deletion has been success')
                ->event('delete')
                ->status('success')
                ->save();
            return redirect()->route('users.index')->with(['scuuess-toastr'=>'User Deleted Successfully']);
        }catch (\Exception $exception){
            DB::rollBack();
            activity('user')->log('Business user deletion has been fail')
                ->event('delete')
                ->status('fail')
                ->save();
            return redirect()->route('users.index')->with(['error'=>'User deletion failed']);
        }
    }


}
