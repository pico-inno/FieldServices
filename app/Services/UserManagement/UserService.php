<?php

namespace App\Services\UserManagement;

use App\Models\BusinessUser;
use App\Models\PersonalInfo;
use App\Repositories\UserManagement\BusinessUserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public static function getUsers($id = null)
    {
        $businessUserRepository = new BusinessUserRepository;

        $query = $businessUserRepository->query()->with('personal_info', 'role');

        if ($id !== null) {
            return $query->find($id);
        }

        return $query->get();
    }
}
