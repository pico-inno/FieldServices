<?php

namespace App\Jobs\RoleAndPermission;

use App\Models\Role;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RolePermissionUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $role_id;
    protected $role_name;
    protected $permissions;

    /**
     * Create a new job instance.
     */
    public function __construct($role_id, $role_name, $permissions)
    {
        $this->role_id = $role_id;
        $this->role_name = $role_name;
        $this->permissions = $permissions;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $role = Role::find($this->role_id);
        if ($role) {
            $role->update([
                'name' => $this->role_name,
            ]);

            $role->permissions()->sync($this->permissions);
        }
    }
}
