<?php

namespace Database\Seeders;

use App\Models\Currencies;
use App\Models\Role;
use App\Models\Feature;
use App\Models\Permission;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;
use App\Models\settings\businessSettings;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        $bs=businessSettings::get()->first();
        $currencies=[
            [
                'name'=>'Kyat',
                'country' => 'Myanmar',
                'code'=>'MMK',
                'symbol'=>'Ks',
            ],
            [
                'name' => 'Dollar',
                'country' => 'United States',
                'code' => 'USD',
                'symbol' => '$',
            ],
            [
                'name' => 'Baht',
                'country' => 'Thailand',
                'code' => 'THB',
                'symbol' => '₿',
            ],            [
                'name' => 'Yuan',
                'country' => 'China',
                'code' => 'CNY',
                'symbol' => '¥',
            ]
        ];
         foreach($currencies as$c) {
            Currencies::create([
                'business_id' => $bs->id,
                'currency_type' => 'fiat',
                'name' => $c['name'],
                'country' => $c['country'],
                'code' => $c['code'],
                'symbol' => $c['symbol'],
                'thoundsand_seprator' => ',',
                // 'decimal_sepearator' => '.',
            ]);
        }
        return redirect()->back()->with(['success' => 'Successfully activate setting']);
    }
}
