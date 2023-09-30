<?php

namespace Database\Seeders;

use App\Models\Role;
use Database\Factories\FeaturePermissionFactory;
use Database\Factories\RoleFactory;
use Database\Factories\RolePermissionFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicePermissionSeeder extends Seeder
{

    public function run(): void
    {

        $rolePermissionFactory = new RolePermissionFactory();
        $featurePermissionFactory = new FeaturePermissionFactory();


        $features = ['Module'];
        $permissions = ['view', 'create', 'update', 'delete', 'import', 'export', 'print'];


        $featureWithPermission = $featurePermissionFactory->createFeatureWithPermissions($features, $permissions);
        if ($featureWithPermission){
            $defaultRole = Role::where('name', 'Administrator')->first();
            $rolePermissionFactory->attachPermissions($defaultRole);
        }
    }

}
