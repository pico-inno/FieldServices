<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;


class RolesTableSeeder extends Seeder
{
    private $basic_feature = ['user', 'role'];
    private $basic_permissions = ['view', 'create', 'update', 'delete', 'import', 'export', 'print'];

    private function createFeatureAndPermissions($features, $permissions)
    {

        foreach ($features as $feature) {
            $featureData = Feature::create([
                'name' => $feature,
            ]);

            foreach ($permissions as $permission) {
                Permission::create([
                    'name' => $permission,
                    'feature_id' => $featureData->id,
                ]);
            }
        }
    }
    public function run()
    {
        $systemPermissions = ['install', 'uninstall', 'upload'];
        $vds_permissions = [$this->basic_permissions[0], $this->basic_permissions[3], ...$systemPermissions];
        $billingPermissions =  [...$this->basic_permissions, 'transfer'];


        // Default role creation
        $defaultRole = Role::create(['name' => 'Administrator']);
        $catcherRole = Role::create(['name' => 'Cashier']);
        $storeKeeperRole = Role::create(['name' => 'Store Keeper']);


        // Additional features and permissions
        $contactFeatures = ['supplier', 'customer'];
        $productFeatures = ['product', 'variation', 'selling price groups', 'unit', 'uom', 'category', 'brand', 'warranty', 'manufacture', 'generic'];
        $tradeFeatures = ['pos', 'purchase', 'sell'];
        $inventoryFeatures = ['stock transfer', 'stock adjustment', 'opening stock'];
        $billingFeatures1 = ['Cash & Payment'];
        $billingFeatures2 = ['Expense'];
        $system = ['Module'];


        $this->createFeatureAndPermissions($this->basic_feature, $this->basic_permissions);
        $this->createFeatureAndPermissions($contactFeatures, $this->basic_permissions);
        $this->createFeatureAndPermissions($productFeatures, $this->basic_permissions);
        $this->createFeatureAndPermissions($tradeFeatures, $this->basic_permissions);
        $this->createFeatureAndPermissions($inventoryFeatures, $this->basic_permissions);
        $this->createFeatureAndPermissions($billingFeatures1, $billingPermissions);
        $this->createFeatureAndPermissions($billingFeatures2, $this->basic_permissions);
        $this->createFeatureAndPermissions($system, $vds_permissions);
        $this->createFeatureAndPermissions($system, $systemPermissions);




        // Associate permissions with the default role
        $permissions = Permission::pluck('id');
        $defaultRole->permissions()->sync($permissions);



        // Associate permissions with the default catcher role
        $catcherPermissions = Permission::whereIn('feature_id', function ($query) use ($tradeFeatures) {
            $query->select('id')
                ->from('features')
                ->whereIn('name', $tradeFeatures);
        })->pluck('id');

        $catcherRole->permissions()->sync($catcherPermissions);

        // Associate permissions with the default stockkeeper role
        $stockkeeperPermissions = Permission::whereIn('feature_id', function ($query) use ($productFeatures, $inventoryFeatures) {
            $query->select('id')
                ->from('features')
                ->whereIn('name', array_merge($productFeatures, $inventoryFeatures));
        })->pluck('id');

        $storeKeeperRole->permissions()->sync($stockkeeperPermissions);
    }
}
