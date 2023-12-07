<?php

namespace App\Repositories\UserManagement;

use App\Models\BusinessUser;
use App\Models\PersonalInfo;

class BusinessUserRepository
{
    public function query()
    {
        return BusinessUser::query();
    }
    public function getAll()
    {
        return BusinessUser::all();
    }

    public function getById($id)
    {
        return BusinessUser::find($id);
    }

    public function getPersonalInfoById($id)
    {
        return PersonalInfo::where('id', $id)->first();
    }

    public function create(array $data)
    {
        return BusinessUser::create($data);
    }
    public function createPersonalInfo(array $data)
    {
        return PersonalInfo::create($data);
    }

    public function update($id, array $data)
    {
        return BusinessUser::where('id', $id)->update($data);
    }

    public function updatePersonalInfo($id, array $data)
    {
        return PersonalInfo::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        return BusinessUser::destroy($id);
    }

    public function deletePersonalInfo($id)
    {
        return PersonalInfo::destroy($id);
    }

    public function getAllWithRelationships($relations = []){
        return BusinessUser::with($relations)->get();
    }



}
