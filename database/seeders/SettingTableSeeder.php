<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Feature;
use App\Models\Permission;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;
use App\Models\settings\businessSettings;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        $data = [
            'name' => 'demo business',
            'lot_control' =>  'off',
            'currency_id' =>  1,
            'use_paymentAccount'=>0
        ];
        businessSettings::create($data);
        return redirect()->back()->with(['success' => 'Successfully activate setting']);
    }
}
