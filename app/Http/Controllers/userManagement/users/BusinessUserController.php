<?php

namespace App\Http\Controllers\userManagement\users;

use App\Http\Controllers\Controller;
use App\Models\BusinessUser;
use App\Http\Requests\StoreBusinessUserRequest;
use App\Http\Requests\UpdateBusinessUserRequest;
use App\Models\PersonalInfo;
use App\Models\Role;
use App\Models\settings\businessLocation;
use Illuminate\Support\Facades\Bus;
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
    /**
     * This method return all users list
     * Use for another controller
     */
    public function getAllUsers()
    {
       return BusinessUser::with('personal_info','role')->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('App.userManagement.users.index', [
            'users' => BusinessUser::with('personal_info','role')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        $user = BusinessUser::findOrFail(1);
//        return unserialize($user->access_location_ids);
        return view('App.userManagement.users.add', [
            'roles' => Role::all(),
            'locations' => businessLocation::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBusinessUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBusinessUserRequest $request)
    {

        PersonalInfo::create([
            'initname' => $request->initname,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'marital_status' => $request->marital_status,
            'blood_group' => $request->blood_group,
            'contact_number' => $request->contact_number,
            'alt_number' => $request->alt_number,
            'family_number' => $request->family_number,
            'fb_link' => $request->fb_link,
            'twitter_link' => $request->twitter_link,
            'social_media_1' => $request->social_media_1,
            'social_media_2' => $request->social_media_2,
            'custom_field_1' => $request->custom_field_1,
            'custom_field_2' => $request->custom_field_2,
            'custom_field_3' => $request->custom_field_3,
            'custom_field_4' => $request->custom_field_4,
            'guardian_name' => $request->guardian_name,
            'id_proof_name' => $request->id_proof_name,
            'id_proof_number' => $request->id_proof_number,
            'language' => $request->language,
            'permanent_address' => $request->permanent_address,
            'current_address' => $request->current_address,
            'bank_details' => $request->bank_details,
        ]);

        $personal_info_id = PersonalInfo::where('first_name', $request->first_name)->first()->id;

        BusinessUser::create([
            'username' => $request->username,
            'role_id' => $request->role_id,
            'business_id' => 1,
            'default_location_id' => $request->default_location_id,
            'access_location_ids' => serialize($request->access_location_ids),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'personal_info_id' => $personal_info_id,
            'is_active' => $request->is_active == null ? 0 : $request->is_active
        ]);

        return redirect(route('users.index'))->with(['success-swal'=>'User created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BusinessUser  $businessUser
     * @return \Illuminate\Http\Response
     */
    public function show(BusinessUser $businessUser, $id)
    {

        $user = BusinessUser::with('personal_info','role')->get()->find($id);

        return view('App.userManagement.users.view', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BusinessUser  $businessUser
     * @return \Illuminate\Http\Response
     */
    public function edit(BusinessUser $businessUser, $id)
    {

        $user = BusinessUser::with('personal_info')->get()->find($id);
        $roles = Role::all();

        return view('App.userManagement.users.edit', [
            'user' => $user,
            'roles' =>$roles,
            'locations' => businessLocation::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBusinessUserRequest  $request
     * @param  \App\Models\BusinessUser  $businessUser
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBusinessUserRequest $request, BusinessUser $businessUser, $id)
    {
        $request->validate([
            'username' => [
                'required',
                'string',
                Rule::unique('business_users')->ignore($id)]
        ]);

         $user = BusinessUser::where('id',$id)->first();

         //Business User Update
         $user->username = $request->username;
         $user->role_id = $request->role_id;
         $user->default_location_id = $request->default_location_id;
         $user->access_location_ids = serialize($request->access_location_ids);
         $user->email =$request->email;
         if (isset($request->password)) {
            $user->password = Hash::make($request->password);
         }
         $user->is_active = $request->is_active == null ? 0 : $request->is_active;
         $user->update();


        PersonalInfo::where('id', $user->personal_info_id)->first()->update([
            'initname' => $request->initname,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'marital_status' => $request->marital_status,
            'blood_group' => $request->blood_group,
            'contact_number' => $request->contact_number,
            'alt_number' => $request->alt_number,
            'family_number' => $request->family_number,
            'fb_link' => $request->fb_link,
            'twitter_link' => $request->twitter_link,
            'social_media_1' => $request->social_media_1,
            'social_media_2' => $request->social_media_2,
            'custom_field_1' => $request->custom_field_1,
            'custom_field_2' => $request->custom_field_2,
            'custom_field_3' => $request->custom_field_3,
            'custom_field_4' => $request->custom_field_4,
            'guardian_name' => $request->guardian_name,
            'id_proof_name' => $request->id_proof_name,
            'id_proof_number' => $request->id_proof_number,
            'language' => $request->language,
            'permanent_address' => $request->permanent_address,
            'current_address' => $request->current_address,
            'bank_details' => $request->bank_details,
        ]);


        return redirect(route('users.index'))->with(['scuuess-toastr'=>'User updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BusinessUser  $businessUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(BusinessUser $businessUser, $id)
    {
        $user = BusinessUser::findOrFail($id);
        $user_info = PersonalInfo::findOrFail($user->personal_info_id);
        $user->delete();
        $user_info->delete();

        return redirect()->route('users.index')->with(['scuuess-toastr'=>'User Deleted Successfully']);
    }


}
