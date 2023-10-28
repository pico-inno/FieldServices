<?php

namespace App\Jobs\RoleAndPermission;

use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteRoleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $roleId;

    public function __construct($roleId)
    {
        $this->roleId = $roleId;
    }

    public function handle()
    {
        RolePermission::where('role_id', $this->roleId)->delete();
        Role::find($this->roleId)->delete();
    }
}
