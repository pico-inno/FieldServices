<?php

namespace App\Services\UserManagement;

use App\Models\BusinessUser;
use App\Models\PersonalInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public static function getUsers($id = null)
    {
        $query = BusinessUser::with('personal_info', 'role');

        if ($id !== null) {
            return $query->find($id);
        }

        return $query->get();
    }
}
