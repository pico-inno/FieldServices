<?php

namespace App\Repositories\UserManagement;

use App\Models\BusinessUser;
use App\Models\PersonalInfo;

class BusinessUserRepository
{
    protected $model;
    protected $personalInfoModel;

    public function __construct(BusinessUser $model, PersonalInfo $personalInfoModel)
    {
        $this->model = $model;
        $this->personalInfoModel = $personalInfoModel;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->model->find($id);
        if ($record) {
            $record->update($data);
            return $record;
        }
        return false;
    }

    public function delete($id)
    {
        $record = $this->model->find($id);
        if ($record) {
            $record->delete();
            return true;
        }
        return false;
    }

    public function query()
    {
        return $this->model->query();
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function getPersonalInfoById($id)
    {
        return PersonalInfo::where('id', $id)->first();
    }

    public function createPersonalInfo(array $data)
    {
        return $this->personalInfoModel->create($data);
    }

    public function updatePersonalInfo($id, array $data)
    {
        return PersonalInfo::where('id', $id)->update($data);
    }


    public function deletePersonalInfo($id)
    {
        return PersonalInfo::destroy($id);
    }

    public function getAllWithRelationships($relations = []){
        return $this->model->with($relations)->get();
    }

}
