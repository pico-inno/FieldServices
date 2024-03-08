<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\Township;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AddressSeeder extends Seeder
{

    public function run() : void
    {
        $regions = [
            'Naypyitaw Union Territory',
            'Mandalay Region',
            'Rakhine State',
            'Shan State (South)',
            'Tanintharyi Region',
            'Yangon Region',
            'Chin State',
            'Bago Region (East)',
            'Kayah state',
            'Kayin State',
            'Shan State (East)',
            'Mon State',
            'Ayeyarwady Region',
            'Bago Region (West)',
            'Magway Region',
            'Kachin State',
            'Sagaing Region',
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

       DB::transaction(function () use($regions, $regions_mm) {
           $existingRegions = Region::all()->pluck('en_name', 'mm_name');

           foreach ($regions as $index => $region) {
               $region_mm = $regions_mm[$index];

               if (!isset($existingRegions[$region])) {
                   Region::firstOrCreate([
                       'mm_name' => $region_mm,
                       'en_name' => $region,
                   ]);
               }
           }


           $enFilePath = public_path('general/myanmar_locations_en.json');
           $mmFilePath = public_path('general/myanmar_locations_mm.json');

           $enJsonContent = File::get($enFilePath);
           $mmJsonContent = File::get($mmFilePath);

           $enData = json_decode($enJsonContent, true);
           $mmData = json_decode($mmJsonContent, true);

           foreach ($enData as $index => $enTownship){
               $mmTownship = $mmData[$index];
               $id = Region::where('en_name', $enTownship['Region'])->first()->id;
               Township::firstOrCreate([
                   'region_id' => $id,
                   'mm_name' => $mmTownship['Town_Township'],
                   'en_name' => $enTownship['Town_Township'],
               ]);
           }
       });

    }
}
