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
                'name' => 'Yangon Branch',
                'allow_purchase_order' => 0,

            ],
            [
                'name' => 'Mandalay Branch',
                'allow_purchase_order' => 1,

            ],
            [
                'name' => 'NayPyiTaw Branch',
                'allow_purchase_order' => 1,

            ],
        ];
        DB::table('business_locations')->insert($locations);

    }
}
