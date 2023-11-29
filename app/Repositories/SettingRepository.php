<?php

namespace App\Repositories;

use App\Models\settings\businessSettings;
use App\Repositories\interfaces\SettingRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class SettingRepository implements SettingRepositoryInterface
{
    public function getAll()
    {
        return businessSettings::all();
    }
    public function find($id)
    {
        return businessSettings::where('id', $id)->first();
    }
    public function getByUser()
    {
        $businessId=Auth::user()->business_id;
        return $this->find($businessId);
    }
}
