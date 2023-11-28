<?php

namespace App\Actions\userManagement;

use App\Models\BusinessUser;
use App\Models\PersonalInfo;
use App\repositories\UserManagement\BusinessUserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserAction
{
    protected $businessUserRepository;

    public function __construct(BusinessUserRepository $businessUserRepository)
    {
        $this->businessUserRepository = $businessUserRepository;
    }

    public function create($requestData)
    {
        return DB::transaction(function () use ($requestData) {
            $preparedPersonalInfoData = $this->preparePersonalInfoData($requestData);
            $createdPersonalInfo = $this->businessUserRepository->createPersonalInfo($preparedPersonalInfoData);

            $preparedBusinessUserData = $this->prepareBusinessUserData($requestData);
            $preparedBusinessUserData['password'] = Hash::make($requestData->password);
            $preparedBusinessUserData['personal_info_id'] = $createdPersonalInfo->id;

            $this->businessUserRepository->create($preparedBusinessUserData);
        });
    }

    public function update($requestData, $id)
    {
        return DB::transaction(function () use ($id, $requestData) {
            $businessUser = $this->businessUserRepository->getById($id);
            $preparedBusinessUserData = $this->prepareBusinessUserData($requestData);
            $preparedBusinessUserData['password'] = $requestData->password ? Hash::make($requestData->password) : $businessUser->password;

            $this->businessUserRepository->update($id, $preparedBusinessUserData);

            $preparedPersonalInfoData = $this->preparePersonalInfoData($requestData);
            $this->businessUserRepository->updatePersonalInfo($businessUser->personal_info_id, $preparedPersonalInfoData);
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id){
            $businessUser = $this->businessUserRepository->getById($id);
            $this->businessUserRepository->deletePersonalInfo($businessUser->personal_info_id);
            $this->businessUserRepository->delete($id);
        });
    }

    private function preparePersonalInfoData($data)
    {
        return [
            'initname' => $data->initname,
            'first_name' => $data->first_name,
            'last_name' => $data->last_name,
            'dob' => $data->dob,
            'gender' => $data->gender,
            'marital_status' => $data->marital_status,
            'blood_group' => $data->blood_group,
            'contact_number' => $data->contact_number,
            'alt_number' => $data->alt_number,
            'family_number' => $data->family_number,
            'fb_link' => $data->fb_link,
            'twitter_link' => $data->twitter_link,
            'social_media_1' => $data->social_media_1,
            'social_media_2' => $data->social_media_2,
            'custom_field_1' => $data->custom_field_1,
            'custom_field_2' => $data->custom_field_2,
            'custom_field_3' => $data->custom_field_3,
            'custom_field_4' => $data->custom_field_4,
            'guardian_name' => $data->guardian_name,
            'id_proof_name' => $data->id_proof_name,
            'id_proof_number' => $data->id_proof_number,
            'language' => $data->language,
            'permanent_address' => $data->permanent_address,
            'current_address' => $data->current_address,
            'bank_details' => $data->bank_details,
        ];
    }

    private function prepareBusinessUserData($data)
    {
        return [
            'username' => $data->username,
            'role_id' => $data->role_id,
            'business_id' => 1,
            'default_location_id' => $data->default_location_id,
            'access_location_ids' => serialize($data->access_location_ids),
            'email' => $data->email,
            'is_active' => $data->is_active == null ? 0 : $data->is_active
        ];
    }
}
