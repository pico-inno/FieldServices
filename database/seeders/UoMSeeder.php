<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Log;

class UoMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try{
            $unit_categories = [
                [
                    'name' => 'Unit',
                ],
                [
                    'name' => 'Weight',
                ],
                [
                    'name' => 'Working Time',
                ],
                [
                    'name' => 'Length / Distance',
                ],
                [
                    'name' => 'Surface',
                ],
                [
                    'name' => 'Volume',
                ]
            ];
            DB::table('unit_categories')->insert($unit_categories);

            $unitCategoryId = DB::table('unit_categories')->where('name', 'Unit')->value('id');
            $weightCategoryId = DB::table('unit_categories')->where('name', 'Weight')->value('id');
            $timeCategoryId = DB::table('unit_categories')->where('name', 'Working Time')->value('id');
            $distanceCategoryId = DB::table('unit_categories')->where('name', 'Length / Distance')->value('id');
            $surfaceCategoryId = DB::table('unit_categories')->where('name', 'Surface')->value('id');
            $volumeCategoryId = DB::table('unit_categories')->where('name', 'Volume')->value('id');

            $uoms = [
                [
                    'name' => 'Pieces',
                    'short_name' => 'pcs',
                    'unit_category_id' => $unitCategoryId,
                    'unit_type' => 'reference',
                    'value' => 1,
                    'rounded_amount' => 4
                ],
                [
                    'name' => 'Dozens',
                    'short_name' => 'doz',
                    'unit_category_id' => $unitCategoryId,
                    'unit_type' => 'bigger',
                    'value' => 12,
                    'rounded_amount' => 4
                ],
                [
                    'name' => 'Kilogram',
                    'short_name' => 'kg',
                    'unit_category_id' => $weightCategoryId,
                    'unit_type' => 'reference',
                    'value' => 1,
                    'rounded_amount' => 4
                ],
                [
                    'name' => 'Gram',
                    'short_name' => 'g',
                    'unit_category_id' => $weightCategoryId,
                    'unit_type' => 'smaller',
                    'value' => 1000,
                    'rounded_amount' => 4
                ],
                [
                    'name' => 'Ounces',
                    'short_name' => 'oz',
                    'unit_category_id' => $weightCategoryId,
                    'unit_type' => 'smaller',
                    'value' => 35,
                    'rounded_amount' => 4
                ],
                [
                    'name' => 'Pound',
                    'short_name' => 'lb',
                    'unit_category_id' => $weightCategoryId,
                    'unit_type' => 'smaller',
                    'value' => 2,
                    'rounded_amount' => 4
                ],
                [
                    'name' => 'Ton',
                    'short_name' => 't',
                    'unit_category_id' => $weightCategoryId,
                    'unit_type' => 'bigger',
                    'value' => 0.001,
                    'rounded_amount' => 4
                ],
                [
                    'name' => 'Day',
                    'short_name' => 'd',
                    'unit_category_id' => $timeCategoryId,
                    'unit_type' => 'reference',
                    'value' => 1,
                    'rounded_amount' => 4
                ],
                [
                    'name' => 'Hours',
                    'short_name' => 'hr',
                    'unit_category_id' => $timeCategoryId,
                    'unit_type' => 'smaller',
                    'value' => 24,
                    'rounded_amount' => 4
                ],
                [
                    'name' => 'Month',
                    'short_name' => 'mon',
                    'unit_category_id' => $timeCategoryId,
                    'unit_type' => 'bigger',
                    'value' => 30,
                    'rounded_amount' => 4
                ],
                [
                    'name' => 'Meter',
                    'short_name' => 'm',
                    'unit_category_id' => $distanceCategoryId,
                    'unit_type' => 'reference',
                    'value' => 1,
                    'rounded_amount' => 4
                ],
                [
                    'name' => 'Millimeter',
                    'short_name' => 'mm',
                    'unit_category_id' => $distanceCategoryId,
                    'unit_type' => 'smaller',
                    'value' => 1000,
                    'rounded_amount' => 4
                ],
                [
                    'name' => 'Centimeter',
                    'short_name' => 'cm',
                    'unit_category_id' => $distanceCategoryId,
                    'unit_type' => 'smaller',
                    'value' => 100,
                    'rounded_amount' => 4
                ],
                [
                    'name' => 'Inches',
                    'short_name' => 'in',
                    'unit_category_id' => $distanceCategoryId,
                    'unit_type' => 'smaller',
                    'value' => 39.37,
                    'rounded_amount' => 4
                ],
                [
                    'name' => 'Feet',
                    'short_name' => 'ft',
                    'unit_category_id' => $distanceCategoryId,
                    'unit_type' => 'smaller',
                    'value' => 3.28,
                    'rounded_amount' => 4
                ],
                [
                    'name' => 'Yard',
                    'short_name' => 'yd',
                    'unit_category_id' => $distanceCategoryId,
                    'unit_type' => 'smaller',
                    'value' => 1.09,
                    'rounded_amount' => 4
                ],
                [
                    'name' => 'Kilometer',
                    'short_name' => 'km',
                    'unit_category_id' => $distanceCategoryId,
                    'unit_type' => 'bigger',
                    'value' => 0.001,
                    'rounded_amount' => 4
                ],
                [
                    'name' => 'Mile',
                    'short_name' => 'mi',
                    'unit_category_id' => $distanceCategoryId,
                    'unit_type' => 'bigger',
                    'value' => 0.00062,
                    'rounded_amount' => null
                ],
                [
                    'name' => 'Square Meter',
                    'short_name' => 'm²',
                    'unit_category_id' => $surfaceCategoryId,
                    'unit_type' => 'reference',
                    'value' => 1,
                    'rounded_amount' => 4
                ],
                [
                    'name' => 'Square Feet',
                    'short_name' => 'ft²',
                    'unit_category_id' => $surfaceCategoryId,
                    'unit_type' => 'smaller',
                    'value' => 10.76,
                    'rounded_amount' => 4
                ],
                [
                    'name' => 'Liter',
                    'short_name' => 'l',
                    'unit_category_id' => $volumeCategoryId,
                    'unit_type' => 'reference',
                    'value' => 1,
                    'rounded_amount' => 4
                ],
                [
                    'name' => 'Cubic Inch',
                    'short_name' => 'in³',
                    'unit_category_id' => $volumeCategoryId,
                    'unit_type' => 'smaller',
                    'value' => 61.02,
                    'rounded_amount' => 4
                ],
                [
                    'name' => 'Fluid Ounces (US)',
                    'short_name' => 'fl oz (US)',
                    'unit_category_id' => $volumeCategoryId,
                    'unit_type' => 'smaller',
                    'value' => 33.81,
                    'rounded_amount' => 4
                ],
                [
                    'name' => 'Fluid Ounces (US)',
                    'short_name' => 'fl oz (US)',
                    'unit_category_id' => $volumeCategoryId,
                    'unit_type' => 'smaller',
                    'value' => 33.81,
                    'rounded_amount' => 4
                ],
                [
                    'name' => 'Quarts (US)',
                    'short_name' => 'qt (US)',
                    'unit_category_id' => $volumeCategoryId,
                    'unit_type' => 'smaller',
                    'value' => 1.06,
                    'rounded_amount' => 4
                ],
                [
                    'name' => 'Gallon (US)',
                    'short_name' => 'gal (US)',
                    'unit_category_id' => $volumeCategoryId,
                    'unit_type' => 'bigger',
                    'value' => 0.264,
                    'rounded_amount' => 4
                ],
                [
                    'name' => 'Cubic Feet',
                    'short_name' => 'ft³',
                    'unit_category_id' => $volumeCategoryId,
                    'unit_type' => 'bigger',
                    'value' => 0.035,
                    'rounded_amount' => 4
                ],
                [
                    'name' => 'Cubic Meter',
                    'short_name' => 'm³',
                    'unit_category_id' => $volumeCategoryId,
                    'unit_type' => 'bigger',
                    'value' => 0.001,
                    'rounded_amount' => 4
                ],
            ];

            DB::table('uom')->insert($uoms);
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }
}
