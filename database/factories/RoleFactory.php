<?php

namespace Database\Factories;

use App\Models\Role;

class RoleFactory
{
    public function createRole($name)
    {
        return Role::create(['name' => $name]);
    }


    public function createRoleIfNotExists($roleName)
    {

        $existingRole = Role::where('name', $roleName)->first();

        if (!$existingRole) {

            return Role::create([
                'name' => $roleName,
                'created_at' => now(),
                'updated_at' => now(),
                ]);
        }

        return $existingRole;
    }
}
