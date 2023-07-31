<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use App\Models\Currencies;
use App\Models\settings\businessSettings;
use Illuminate\Http\Request;

class businessSettingController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }
    public function index(){
        $settingData=businessSettings::firstorFail();
        $currencies=Currencies::all();
        return view('App.businessSetting.businessSetting',compact('settingData', 'currencies'));
    }
    public function create(Request $request){
        $data=[
            'name' => $request->name,
            'lot_control' => 'off',
            'currency_id'=>$request->currency_id,
            'currency_decimal_places'=>$request->currency_decimal_places,
            'quantity_decimal_places'=>$request->quantity_decimal_places,
            'accounting_method'=>$request->accounting_method,
            'enable_line_discount_for_purchase'=>$request->enable_line_discount_for_purchase ? '1':'0',
            'enable_line_discount_for_sale'=>$request->enable_line_discount_for_sale ? '1':'0',
        ];
        if(businessSettings::exists()){
            businessSettings::first()->update($data);
            return redirect()->back()->with(['success'=>'Successfully updated setting']);
        }else{
            businessSettings::create($data);
            return redirect()->back()->with(['success' => 'Successfully activate setting']);
        };
    }
}
