<?php

namespace Database\Seeders;

use App\Models\settings\businessLocation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Test extends Seeder
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


        DB::table('unit_categories')->insert(['name' => 'mass']);

        $uomdata = [
            [
                'name' => 'Pieces',
                'short_name' => 'pic',
                'unit_category_id' => 1,
                'unit_type' => 'reference',
                'value' => 1,
                'rounded_amount' => 4,

            ],
            [
                'name' => 'Box',
                'short_name' => 'bx',
                'unit_category_id' => 1,
                'unit_type' => 'bigger',
                'value' => 10,
                'rounded_amount' => 4,

            ]
        ];

        DB::table('uom')->insert($uomdata);

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

        $suppliers = [
          [
              'business_id' => 1,
              'type' => 'Supplier',
              'company_name' => 'AliExpress',
              'mobile' => '0912345678',
          ],
          [
              'business_id' => 1,
              'type' => 'Supplier',
              'company_name' => 'Oracle',
              'mobile' => '0912345678',
          ]
        ];

        DB::table('contacts')->insert($suppliers);

        // $buildings = [
        //     [
        //         'name' => 'NH Building',
        //     ],
        // ];

        // DB::table('buildings')->insert($buildings);



        // $floors = [
        //     [
        //         'building_id' => 1,
        //         'business_location_id' => 1,
        //         'name' => '1st floor',
        //     ],
        //     [
        //         'building_id' => 1,
        //         'business_location_id' => 1,
        //         'name' => '2nd floor',
        //     ]
        // ];

        // DB::table('floors')->insert($floors);



        // $roomTypes = [
        //     [
        //         'name' => 'standard',
        //     ],
        //     [
        //         'name' => 'Luxry',
        //     ],
        //     [
        //         'name' => 'double',
        //     ],
        // ];

        // DB::table('room_types')->insert($roomTypes);

        // $rooms = [
        //     [
        //         'floor_id' => 1,
        //         'room_type_id'=>1,
        //         'name'=> 'R-0001'
        //     ],
        //     [
        //         'floor_id' => 1,
        //         'room_type_id' => 1,
        //         'name' => 'R-0002'
        //     ],
        //     [
        //         'floor_id' => 1,
        //         'room_type_id' => 2,
        //         'name' => 'R-0003'
        //     ],
        //     [
        //         'floor_id' => 1,
        //         'room_type_id' => 2,
        //         'name' => 'R-0004'
        //     ],
        //     [
        //         'floor_id' => 1,
        //         'room_type_id' => 3,
        //         'name' => 'R-0005'
        //     ],
        //     [
        //         'floor_id' => 1,
        //         'room_type_id' => 3,
        //         'name' => 'R-0006'
        //     ],
        //     [
        //         'floor_id' => 2,
        //         'room_type_id' => 1,
        //         'name' => 'R-0007'
        //     ],
        //     [
        //         'floor_id' => 2,
        //         'room_type_id' => 1,
        //         'name' => 'R-0008'
        //     ],
        //     [
        //         'floor_id' => 2,
        //         'room_type_id' => 2,
        //         'name' => 'R-0009'
        //     ],
        //     [
        //         'floor_id' => 2,
        //         'room_type_id' => 2,
        //         'name' => 'R-00010'
        //     ],
        //     [
        //         'floor_id' => 2,
        //         'room_type_id' => 3,
        //         'name' => 'R-00011'
        //     ],
        //     [
        //         'floor_id' => 2,
        //         'room_type_id' => 3,
        //         'name' => 'R-00012'
        //     ],
        // ];

        // DB::table('rooms')->insert($rooms);

        // $roomRates = [
        //     [
        //         'room_type_id' => 1,
        //         'rate_name' => 'standard',
        //         'rate_amount'=>50000
        //     ],
        //     [
        //         'room_type_id' => 2,
        //         'rate_name' => 'luxry',
        //         'rate_amount' => 350000
        //     ],
        //     [
        //         'room_type_id' => 3,
        //         'rate_name' => 'dobule',
        //         'rate_amount' => 100000
        //     ],
        // ];

        // DB::table('room_rates')->insert($roomRates);
    }
}
