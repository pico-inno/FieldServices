<?php

namespace Database\Seeders;

use App\Models\LocalRegion;
use App\Models\LocalTownship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class LocalAddressSeeder extends Seeder
{

    public function run() : void
    {
        $regions = [
            'Naypyitaw Union Territory',
            'Mandalay LocalRegion',
            'Rakhine State',
            'Shan State (South)',
            'Tanintharyi LocalRegion',
            'Yangon LocalRegion',
            'Chin State',
            'Bago LocalRegion (East)',
            'Kayah state',
            'Kayin State',
            'Shan State (East)',
            'Mon State',
            'Ayeyarwady LocalRegion',
            'Bago LocalRegion (West)',
            'Magway LocalRegion',
            'Kachin State',
            'Sagaing LocalRegion',
            'Shan State (North)',
        ];

        $regions_mm = [
            'နေပြည်တော် (ပြည်ထောင်စုနယ်မြေ)',
            'မန္တလေးတိုင်းဒေသကြီး',
            'ရခိုင်ပြည်နယ်',
            'ရှမ်းပြည်နယ် (တောင်ပိုင်း)',
            'တနင်္သာရီတိုင်းဒေသကြီး',
            'ရန်ကုန်တိုင်းဒေသကြီး',
            'ချင်းပြည်နယ်',
            'ပဲခူးတိုင်းဒေသကြီး (အရှေ့)',
            'ကယားပြည်နယ်',
            'ကရင်ပြည်နယ်',
            'ရှမ်းပြည်နယ် (အရှေ့)',
            'မွန်ပြည်နယ်',
            'ဧရာဝတီတိုင်းဒေသကြီး',
            'ပဲခူးတိုင်းဒေသကြီး (အနောက်)',
            'မကွေးတိုင်းဒေသကြီး',
            'ကချင်ပြည်နယ်',
            'စစ်ကိုင်းတိုင်းဒေသကြီး',
            'ရှမ်းပြည်နယ် (မြောက်ပိုင်း)',
        ];

        DB::transaction(function () use ($regions, $regions_mm) {
            // Create or retrieve existing regions
            foreach ($regions as $index => $region) {
                $region_mm = $regions_mm[$index];

                $localRegion = LocalRegion::firstOrCreate([
                    'en_name' => $region,
                ], [
                    'mm_name' => $region_mm,
                ]);
            }

            // Process township data
            $enFilePath = public_path('general/myanmar_locations_en.json');
            $mmFilePath = public_path('general/myanmar_locations_mm.json');

            $enJsonContent = File::get($enFilePath);
            $mmJsonContent = File::get($mmFilePath);

            $enData = json_decode($enJsonContent, true);
            $mmData = json_decode($mmJsonContent, true);

            foreach ($enData as $index => $enTownship) {
                // Check if the index exists in the $mmData array
                if (isset($mmData[$index])) {
                    $mmTownship = $mmData[$index];

                    // Query LocalRegion by 'en_name'
                    $localRegion = LocalRegion::where('en_name', $enTownship['Region'])->first();

                    // Check if LocalRegion exists
                    if ($localRegion) {
                        // LocalRegion found, get its ID
                        $regionId = $localRegion->id;

                        // Create or update LocalTownship
                        LocalTownship::updateOrCreate(
                            ['en_name' => $enTownship['Town_Township']],
                            ['region_id' => $regionId, 'mm_name' => $mmTownship['Town_Township']]
                        );
                    } else {
                        // Log error or message indicating the missing LocalRegion
                        \Log::error('LocalRegion not found for Region: ' . $enTownship['Region']);
                    }
                } else {
                    // Log error or message indicating missing MM township data for the given index
                    \Log::error('MM township data not found for index: ' . $index);
                }
            }
        });
    }
}
