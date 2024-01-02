<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Database\Factories\FeaturePermissionFactory;
use Database\Factories\RolePermissionFactory;

class NewFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolePermissionFactory = new RolePermissionFactory();
        $featurePermissionFactory = new FeaturePermissionFactory();

        $standardPermission = ['view','create', 'update', 'delete'];
        $fpf = $featurePermissionFactory->createFeatureWithPermissions(['sms','mail','printer','business setting','business location'], $standardPermission);
        // if ($fpf) {
        $defaultRole = Role::where('name', 'Administrator')->first();
        $rolePermissionFactory->attachPermissions($defaultRole);
        // }
    }
}
