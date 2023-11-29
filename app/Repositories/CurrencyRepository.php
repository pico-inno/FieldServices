<?php

namespace App\Repositories;

use App\Models\Currencies;
use App\Repositories\interfaces\CurrencyRepositoryInterface;

class CurrencyRepository implements CurrencyRepositoryInterface
{
    public function getAll()
    {
        return Currencies::all();
    }
    public function find($id)
    {
        return Currencies::where('id', $id)->first();
    }
    public function defaultCurrency()
    {
        $settingRepo =new SettingRepository();
        $setting= $settingRepo->getByUser();
        $defaultCurrencyId= $setting->currency_id;
        return $this->find($defaultCurrencyId);
    }
}
