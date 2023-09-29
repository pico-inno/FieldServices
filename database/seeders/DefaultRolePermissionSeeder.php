<?php

namespace Database\Seeders;

use Database\Factories\FeatureFactory;
use Database\Factories\FeaturePermissionFactory;
use Database\Factories\PermissionFactory;
use Database\Factories\RoleFactory;
use Database\Factories\RolePermissionFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultRolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roleFactory = new RoleFactory();
        $rolePermissionFactory = new RolePermissionFactory();
        $featurePermissionFactory = new FeaturePermissionFactory();

        //Features
        $basic_feature = ['user', 'role'];

        $contactFeatures = ['supplier', 'customer'];
        $productFeatures = ['product', 'variation', 'selling price groups', 'unit', 'uom', 'category', 'brand', 'warranty', 'manufacture', 'generic'];
        $tradeFeatures = ['pos', 'purchase', 'sell'];
        $inventoryFeatures = ['stock transfer', 'stock adjustment', 'opening stock'];
        $billingFeatures1 = ['Cash & Payment'];
        $billingFeatures2 = ['Expense'];
        $system = ['Module'];

        //Permissions
        $basic_permissions = ['view', 'create', 'update', 'delete', 'import', 'export', 'print'];
        $systemPermissions = ['install', 'uninstall', 'upload'];
        $vds_permissions = [$basic_permissions[0], $basic_permissions[3], ...$systemPermissions];
        $billingPermissions =  [...$basic_permissions, 'transfer'];

        $rolesToCreate = ['Administrator', 'Cashier', 'Store Keeper'];

        //Create Features and Permissions
        $featurePermissionFactory->createFeatureWithPermissions($basic_feature, $basic_permissions);
        $featurePermissionFactory->createFeatureWithPermissions($contactFeatures, $basic_permissions);
        $featurePermissionFactory->createFeatureWithPermissions($productFeatures, $basic_permissions);
        $featurePermissionFactory->createFeatureWithPermissions($tradeFeatures, $basic_permissions);
        $featurePermissionFactory->createFeatureWithPermissions($inventoryFeatures, $basic_permissions);
        $featurePermissionFactory->createFeatureWithPermissions($billingFeatures1, $billingPermissions);
        $featurePermissionFactory->createFeatureWithPermissions($billingFeatures2, $basic_permissions);
        $featurePermissionFactory->createFeatureWithPermissions($system, $vds_permissions);



        foreach ($rolesToCreate as $roleName) {
            $role = $roleFactory->createRoleIfNotExists($roleName);

            if ($roleName === 'Administrator') {
                $rolePermissionFactory->attachPermissions($role);
            } elseif ($roleName === 'Cashier') {
                $rolePermissionFactory->attachPermissions($role, $tradeFeatures);
            } elseif ($roleName === 'Store Keeper') {
                $stockKeeperFeature = array_merge($productFeatures, $inventoryFeatures);
                $rolePermissionFactory->attachPermissions($role, $stockKeeperFeature);
            }

        }

        //Call another roel permissions for default administrator
//        $this->call(ServicePermissionSeeder::class);


        //

    }
}
