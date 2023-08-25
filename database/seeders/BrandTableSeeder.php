<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BrandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Nextron',
            ],
            [
                'name' => 'Innovatech',
            ],
            [
                'name' => 'SerenityHome',
            ],
            [
                'name' => 'InfinityMobile',
            ],
            [
                'name' => 'Samsung',
            ],
        ];

        DB::table('brands')->insert($brands);
    }
}
