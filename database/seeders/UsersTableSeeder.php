<?php

namespace Database\Seeders;

use App\Models\BusinessUser;
use App\Models\PersonalInfo;
use App\Models\settings\businessLocation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaultLocaitonId = businessLocation::create([
            'name' => 'Main Branch',
            'allow_purchase_order' => 0,
            'location_type' => 5,
        ]);
        $locationIds = [0];
        BusinessUser::create([
           'username' => 'admin',
           'role_id' => 1,
           'business_id' => 1,
           'default_location_id' => $defaultLocaitonId->id,
           'access_location_ids' => serialize($locationIds),
           'email' => 'admin@pico.com',
           'password' => Hash::make('password'),
            'personal_info_id' => 1,
            'is_active' => true
        ]);

        PersonalInfo::create([
           'first_name' => 'PICO',
            'last_name' => 'ERP POS'
        ]);

    }
}
