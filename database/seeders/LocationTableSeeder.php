<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            [
                'name' => 'Main Location',
                'allow_purchase_order' => 0,
                'location_type' => 3,
                'parent_location_id' => null,
            ],
            [
                'name' => 'Sub Location',
                'allow_purchase_order' => 0,
                'parent_location_id'=>1,
                'location_type' => 5,
            ],
        ];
        DB::table('business_locations')->insert($locations);

    }
}
