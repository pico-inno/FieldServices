<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            // [
            //     'name' => 'Company',
            // ],
            [
                'name' => 'Supplier Location',

            ],
            [
                'name' => 'Customer Location',
            ],
            [
                'name' => 'View',
            ],
            [
                'name' => 'Internal Location',
            ],
            [
                'name' => 'Transit Location',
            ],
        ];
    DB::table('location_types')->insert($types);
    }
}
