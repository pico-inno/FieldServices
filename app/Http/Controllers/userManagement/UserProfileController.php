<?php

namespace App\Http\Controllers\userManagement;

use App\Http\Controllers\Controller;
use App\Models\BusinessUser;
use App\Models\PersonalInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','isActive']);
    }

    public function index(){
        $current_user_id = Auth::user()->id;
        $current_user = BusinessUser::with('personal_info','role')->find($current_user_id);

        return view('App.userManagement.userProfile.index', ['current_user' => $current_user]);
    }

    public function logs(){
        $current_user_id = Auth::user()->id;
        $current_user = BusinessUser::with('personal_info','role')->find($current_user_id);

        return view('App.userManagement.userProfile.logs', ['current_user' => $current_user]);

    }
    public function settings(){
        $current_user_id = Auth::user()->id;
        $current_user = BusinessUser::with('personal_info','role')->find($current_user_id);

        return view('App.userManagement.userProfile.settings', ['current_user' => $current_user]);

    }

    public function update_info(Request $request, $id){

        $request->validate([
            'first_name' => 'required|string',
            'username' => [
                'required',
                'string',
                Rule::unique('business_users')->ignore($id)]
        ]);

        try {
            DB::beginTransaction();
            //Profile Photo Save to local storage
            $image = $request->file('profile_photo');
            $path = $image == null ? null : Storage::url($image->store('/images/profile_picture'));




            $user = BusinessUser::where('id',$id)->first();

            //Business User Update
            $user->username = $request->username;
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
                'profile_photo' => $path,
            ]);
            DB::commit();
            activity('user')
                ->log('Business user profile update has been success')
                ->event('update')
                ->status('success')
                ->save();
            return back()->with(['success-swal'=>'Profile updated successfully']);
        }catch (\Exception $exception){
            DB::rollBack();
            activity('user')
                ->log('Business user profile update has been fail')
                ->event('update')
                ->status('fail')
                ->save();
            return back()->with(['error'=>'Profile update failed']);
        }


    }

    public function update_email(Request $request, $id){

        $user = BusinessUser::find($id);

        // Check the password
        if (Hash::check($request->password, $user->password)) {
            try {
                DB::beginTransaction();
                $user->email = $request->email;
                $user->update();
                DB::commit();
                activity('user')
                    ->log('Business user email update has been success')
                    ->event('update')
                    ->status('success')
                    ->save();

                return back()->with(['success-swal'=>'Email updated successfully']);
            }catch (\Exception $exception){
                DB::rollBack();
                activity('user')
                    ->log('Business user email update has been fail')
                    ->event('update')
                    ->status('fail')
                    ->save();
                return back()->with(['error-swal'=>'Email update failed']);
            }
        } else {
            activity('user')
                ->log('Business user email update has been warn due to incorrect password')
                ->event('update')
                ->status('warn')
                ->save();
            return back()->with(['error-swal'=>'Incorrect Password']);
        }
    }


    public function update_password(Request $request, $id){

        $user = BusinessUser::find($id);

        // Check the password
        if (Hash::check($request->currentpassword, $user->password)) {
            try {
                DB::beginTransaction();
                $user->password = Hash::make($request->newpassword);
                $user->update();

                DB::commit();
                activity('user')
                    ->log('Business user password update has been success')
                    ->event('update')
                    ->status('success')
                    ->save();

                return back()->with(['success-swal'=>'Password updated successfully']);
            }catch (\Exception $exception){
                DB::rollBack();
                activity('user')
                    ->log('Business user password update has been fail')
                    ->event('update')
                    ->status('fail')
                    ->save();
                return back()->with(['error-swal'=>'Password update failed']);
            }
        } else {
            activity('user')
                ->log('Business user password update has been warn due to incorrect old password')
                ->event('update')
                ->status('warn')
                ->save();
            return back()->with(['error-swal'=>'Incorrect Password']);
        }
    }

    public function deactivate(Request $request, $id){

        try {
            DB::beginTransaction();
            $user = BusinessUser::find($id);
            $user->is_active = $request->deactivate;
            $user->update();

            DB::commit();
            activity('account')
                ->log('Business user account deactivate has been success')
                ->event('deactivate')
                ->status('success')
                ->save();

            return back()->with(['account_inactive'=>'Your account has been deactivated.']);
        }catch (\Exception $exception){
            DB::rollBack();
            activity('account')
                ->log('Business user account deactivate has been fail')
                ->event('deactivate')
                ->status('fail')
                ->save();
            return back()->with(['error-swal'=>'Your account deactivation has been fail']);
        }

    }


}
