<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Currencies;
use Database\Seeders\UoMSeeder;
use Illuminate\Database\Seeder;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\SettingTableSeeder;
use Database\Seeders\ContactsTableSeeder;
use Database\Seeders\CurrenciesTableSeeder;
use Database\Seeders\ContactWalkInTableSeeder;
use Modules\StockInOut\Database\Seeders\StockInOutDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // \App\Models\User::factory(10)->create();
        // \App\Models\Product\Brand::factory(30)->create();
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(SettingTableSeeder::class);
        $this->call(ContactsTableSeeder::class);
        $this->call(ContactWalkInTableSeeder::class);
        $this->call(CurrenciesTableSeeder::class);
        $this->call(LocationTableSeeder::class);
        $this->call(BrandTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        // $this->call(UoMSeeder::class);
        // $this->call(Test::class);
        $this->call(UoMSeeder::class);
//        $this->call(StockInOutDatabaseSeeder::class);

    }
}

