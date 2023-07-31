<?php

namespace Modules\ExchangeRate\Database\Seeders;

use App\Models\Feature;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ExchangeRateDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");

        $features = ['exchange rate'];
        $permissions = ['view', 'create', 'update', 'delete', 'import', 'export', 'print'];

        foreach ($features as $feature) {
            $featureData = Feature::create(['name' => $feature,]);

            foreach ($permissions as $permission) {
                Permission::create([
                    'name' => $permission,
                    'feature_id' => $featureData->id,
                ]);
            }
        }

        $rolePermissions = Permission::whereIn('feature_id', Feature::whereIn('name', $features)->pluck('id'))->pluck('id');

        Role::find(1)->permissions()->attach($rolePermissions);

    }
}
