<?php

namespace App\Imports\userManagement;


use App\Actions\userManagement\UserAction;
use App\Models\Product\Brand;
use App\Models\Role;
use App\Models\settings\businessLocation;
use App\Repositories\UserManagement\BusinessUserRepository;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;



class BusinessUserImport implements
    WithValidation,
    ToCollection,
    WithHeadingRow,
    WithChunkReading,
    WithBatchInserts
{
    use Importable;

    protected $userAction;
    protected $businessUserRepository;

    public function __construct()
    {
        ini_set('max_execution_time', '0');
        ini_set("memory_limit", "-1");
        ini_set("max_allowed_packet", "-1");

        $this->businessUserRepository = new BusinessUserRepository();
    }



    public function collection(Collection $rows)
    {
        foreach ($rows as $row){
            if (isset($row['role_name'])){
                $role =  Role::where('name', $row['role_name'])->first();
            }else{
                return throw new Exception("Role Name column not found in template");
            }

            if (isset($row['default_location_name'])){
                $default_location =  businessLocation::where('name', $row['default_location_name'])->first();
            }else{
                return throw new Exception("Default Location Name column not found in template");
            }

            if (isset($row['access_location_name_name1_name2'])) {
                $locations = explode(',', $row['access_location_name_name1_name2']);

                $access_locations = [];
                foreach ($locations as $location_name) {
                    $access_location =  businessLocation::where('name', trim($location_name))->first();
                    $access_locations[] = $access_location->id;

                }
            }else{
                return throw new Exception("Access Location Name column not found in template");
            }

            if ($access_locations && $default_location){
                if (!in_array($default_location->id, $access_locations)) {
                    return throw new Exception("The default location name must be the same as one of the access locations name");
                }
            }

            $preparedPersonalInfoData = $this->preparePersonalData($row);

            $createdPersonalInfo = $this->businessUserRepository->createPersonalInfo($preparedPersonalInfoData);

            $preparedBusinessUserData =  [
                'username' => $row['username'],
                'role_id' => $role->id,
                'business_id' => 1,
                'default_location_id' => $default_location->id,
                'access_location_ids' => serialize($access_locations),
                'email' => $row['email'],
                'is_active' => 1
            ];


            $preparedBusinessUserData['password'] = Hash::make($row['password']);
            $preparedBusinessUserData['personal_info_id'] = $createdPersonalInfo->id;

            $this->businessUserRepository->create($preparedBusinessUserData);

        }
    }


    protected function preparePersonalData($row)
    {
        return [
            'initname' => $row['prefix'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'dob' => $row['date_of_birth'],
            'gender' => $row['gender'],
            'marital_status' => $row['marital_status'],
            'blood_group' => $row['blood_group'],
            'contact_number' => $row['phone'],
            'alt_number' => $row['contact_number'],
            'family_number' => $row['family_contact_number'],
            'fb_link' => $row['facebook_link'],
            'twitter_link' => $row['twitter_link'],
            'social_media_1' => $row['social_media_1'],
            'social_media_2' => $row['social_media_2'],
            'custom_field_1' => $row['custom_field_1'],
            'custom_field_2' => $row['custom_field_2'],
            'custom_field_3' => $row['custom_field_3'],
            'custom_field_4' => $row['custom_field_4'],
            'guardian_name' => $row['guardian_name'],
            'id_proof_name' => $row['id_proof_name'],
            'id_proof_number' => $row['id_proof_number'],
            'language' => $row['language'],
            'permanent_address' => $row['permanent_address'],
            'current_address' => $row['current_address'],
        ];
    }


    public function chunkSize(): int
    {
        return 100;
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function rules(): array
    {
        return [
            '*.username' => [
                'nullable',
                Rule::unique('business_users', 'username'),
            ],

            '*.email' => [
                Rule::unique('business_users', 'username'),
            ],

            '*.role_name' => [
                Rule::exists('roles', 'name'),
            ],

        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.username' => 'Username has already been taken.',
            '*.email' => 'Username has already been taken.',
            '*.role_name' => 'Role name not found!',
        ];
    }

}
