<?php

namespace App\Actions\userManagement;

use App\Models\BusinessUser;
use App\Models\PersonalInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserAction
{
    public function create($requestData)
    {
        try {
            DB::beginTransaction();

            $personalInfo = PersonalInfo::create([
                'initname' => $requestData->initname,
                'first_name' => $requestData->first_name,
                'last_name' => $requestData->last_name,
                'dob' => $requestData->dob,
                'gender' => $requestData->gender,
                'marital_status' => $requestData->marital_status,
                'blood_group' => $requestData->blood_group,
                'contact_number' => $requestData->contact_number,
                'alt_number' => $requestData->alt_number,
                'family_number' => $requestData->family_number,
                'fb_link' => $requestData->fb_link,
                'twitter_link' => $requestData->twitter_link,
                'social_media_1' => $requestData->social_media_1,
                'social_media_2' => $requestData->social_media_2,
                'custom_field_1' => $requestData->custom_field_1,
                'custom_field_2' => $requestData->custom_field_2,
                'custom_field_3' => $requestData->custom_field_3,
                'custom_field_4' => $requestData->custom_field_4,
                'guardian_name' => $requestData->guardian_name,
                'id_proof_name' => $requestData->id_proof_name,
                'id_proof_number' => $requestData->id_proof_number,
                'language' => $requestData->language,
                'permanent_address' => $requestData->permanent_address,
                'current_address' => $requestData->current_address,
                'bank_details' => $requestData->bank_details,
            ]);


            BusinessUser::create([
                'username' => $requestData->username,
                'role_id' => $requestData->role_id,
                'business_id' => 1,
                'default_location_id' => $requestData->default_location_id,
                'access_location_ids' => serialize($requestData->access_location_ids),
                'email' => $requestData->email,
                'password' => Hash::make($requestData->password),
                'personal_info_id' => $personalInfo->id,
                'is_active' => $requestData->is_active == null ? 0 : $requestData->is_active
            ]);


            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($requestData, $id)
    {
        try {
            DB::beginTransaction();

            $user = BusinessUser::findOrFail($id);

            $user->update([
                'username' => $requestData->username,
                'role_id' => $requestData->role_id,
                'default_location_id' => $requestData->default_location_id,
                'access_location_ids' => serialize($requestData->access_location_ids),
                'email' => $requestData->email,
                'password' => $requestData->password ? Hash::make($requestData->password) : $user->password,
                'is_active' => $requestData->is_active == null ? 0 : $requestData->is_active,
            ]);

            $personalInfo = PersonalInfo::findOrFail($user->personal_info_id);

            $personalInfo->update([
                'initname' => $requestData->initname,
                'first_name' => $requestData->first_name,
                'last_name' => $requestData->last_name,
                'dob' => $requestData->dob,
                'gender' => $requestData->gender,
                'marital_status' => $requestData->marital_status,
                'blood_group' => $requestData->blood_group,
                'contact_number' => $requestData->contact_number,
                'alt_number' => $requestData->alt_number,
                'family_number' => $requestData->family_number,
                'fb_link' => $requestData->fb_link,
                'twitter_link' => $requestData->twitter_link,
                'social_media_1' => $requestData->social_media_1,
                'social_media_2' => $requestData->social_media_2,
                'custom_field_1' => $requestData->custom_field_1,
                'custom_field_2' => $requestData->custom_field_2,
                'custom_field_3' => $requestData->custom_field_3,
                'custom_field_4' => $requestData->custom_field_4,
                'guardian_name' => $requestData->guardian_name,
                'id_proof_name' => $requestData->id_proof_name,
                'id_proof_number' => $requestData->id_proof_number,
                'language' => $requestData->language,
                'permanent_address' => $requestData->permanent_address,
                'current_address' => $requestData->current_address,
                'bank_details' => $requestData->bank_details,
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $user = BusinessUser::findOrFail($id);
            $personalInfo = PersonalInfo::findOrFail($user->personal_info_id);

            $user->delete();
            $personalInfo->delete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
