<?php

namespace App\Http\Controllers;

use PDO;
use DateTime;
use App\Models\Currencies;
use App\Models\BusinessUser;
use App\Models\PersonalInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Models\settings\businessSettings;

class businessActivationController extends Controller
{
    public function activationForm(){
        return view('App.business.activationForm');
    }
    public function store(Request $request){
        try {
            DB::beginTransaction();
            $businessUser=$request->businessUser;
            $businessData = $request->business;

            $logoPath=$this->saveLogo($request);
            $business=businessSettings::create([
                'name'=> $businessData['name'],
                'start_date' =>DateTime::createFromFormat('d-m-Y', $businessData['start_date']),
                'accounting_method' => $businessData['accounting_method'],
                'use_paymentAccount' => isset($businessData['use_paymentAccount']) ? '1' : '0',
                'currency_id' =>  $businessData['currency'],
                'logo'=> $logoPath,
            ]);
            $personalInfo=PersonalInfo::create([
                'initname' => $businessUser['prefix'],
                'first_name' => $businessUser['firstName'],
                'last_name' => $businessUser['lastName'],
            ]);
            $businessUser=BusinessUser::create([
                'username'=> $businessUser['username'],
                'personal_info_id'=>$personalInfo->id,
                'role_id'=>1,
                'business_id'=> $business->id,
                'first_name'=>$businessUser['firstName'],
                'last_name' => $businessUser['lastName'],
                'default_location_id' => 1,
                'access_location_ids'=> 'a:1:{i:0;i:0;}',
                'email' => $businessUser['email'],
                'password' => Hash::make($businessUser['password']),
            ]);
            $business->owner_id= $businessUser->id;
            $business->update();
            $this->currencyTableSeed($business->id);
            DB::commit();
            $logoPath= asset("/public/storage/logo/$business->logo");
            logger($logoPath);
            return response()->json(['success' => 'Successfully Activated Your Business', 'business'=>$business,'businessLogo'=> $logoPath], 200);
        } catch (\Throwable $e) {
            logger($e);
            return response()->json(['error' => $e->getMessage()], 200);
            //throw $th;
        }
    }

    private function saveLogo($request, $existingImagePath = null)
    {
        if ($request->hasFile('business.logo')) {
            if ($existingImagePath) {
                Storage::delete('logo/' . $existingImagePath);
            }

            $file = $request->file('business.logo');
            $img_name = time() . '_' . $file->getClientOriginalName();
            Storage::put('logo/' . $img_name, file_get_contents($file));

            return $img_name;
        } else {
            if ($request->avatar_remove == 1 && $existingImagePath) {
                Storage::delete('logo/' . $existingImagePath);
                return null;
            } else {
                return $existingImagePath;
            }
        }
    }


    public function currencyTableSeed($businessId){
        $currencies = [
            [
                'name' => 'Kyat',
                'country' => 'Myanmar',
                'code' => 'MMK',
                'symbol' => 'Ks',
            ],
            [
                'name' => 'Dollar',
                'country' => 'United States',
                'code' => 'USD',
                'symbol' => '$',
            ],
            [
                'name' => 'Baht',
                'country' => 'Thailand',
                'code' => 'THB',
                'symbol' => '₿',
            ],            [
                'name' => 'Yuan',
                'country' => 'China',
                'code' => 'CNY',
                'symbol' => '¥',
            ]
        ];
        foreach ($currencies as $c) {
            Currencies::create([
                'business_id' => $businessId,
                'currency_type' => 'fiat',
                'name' => $c['name'],
                'country' => $c['country'],
                'code' => $c['code'],
                'symbol' => $c['symbol'],
                'thoundsand_seprator' => 'comma',
                // 'decimal_sepearator' => 'dot',
            ]);
        }
    }
}
