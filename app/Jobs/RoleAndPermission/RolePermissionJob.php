<?php

namespace App\Jobs\RoleAndPermission;

use App\Models\RolePermission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RolePermissionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $role_id;
    protected $permissions;

    public function __construct($role_id, $permissions)
    {
        $this->role_id = $role_id;
        $this->permissions = $permissions;
    }

    public function handle()
    {
        foreach ($this->permissions as $permission) {
            RolePermission::create([
                'role_id' => $this->role_id,
                'permission_id' => $permission
            ]);
        }
    }
}
