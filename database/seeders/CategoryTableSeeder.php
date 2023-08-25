<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $categories = [
            [
                'name' => 'Home Product',
                'short_code' => 001,
            ],
            [
                'name' => 'Tech Product',
                'short_code' => 002,
            ],
            [
                'name' => 'Electric Product',
                'short_code' => 003,
            ]
        ];

        DB::table('categories')->insert($categories);
    }
}
