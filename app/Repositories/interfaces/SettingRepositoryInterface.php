<?php

namespace App\Repositories\interfaces;

interface SettingRepositoryInterface
{

    public function getAll();
    public function find($id);
    public function getByUser();
}
