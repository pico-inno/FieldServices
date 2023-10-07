<?php

namespace App\Http\Controllers\settings;

use App\Models\Currencies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\settings\businessSettings;
use Illuminate\Support\Facades\Date;

class businessSettingController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }
    public function index()
    {
        $settingData = businessSettings::where('id', Auth::user()->business_id)->firstorFail();
        $currencies = Currencies::where('business_id', $settingData->id)->get();

        return view('App.businessSetting.businessSetting', compact('settingData', 'currencies'));
    }
    public function create(Request $request)
    {
        if ($request->sms_service == 'smsPOH') {
            $newEnv = $request->only([
                'SMSPOH_SENDER', 'SMSPOH_AUTH_TOKEN'
            ]);
            updenv($newEnv);
        };
        $data = [
            'name' => $request->name,
            'lot_control' => $request->lot_control ? 1 : 0,
            'currency_id' => $request->currency_id,
            'currency_decimal_places' => $request->currency_decimal_places,
            'quantity_decimal_places' => $request->quantity_decimal_places,
            'accounting_method' => $request->accounting_method,
            'enable_line_discount_for_purchase' => $request->enable_line_discount_for_purchase ? '1' : '0',
            'enable_line_discount_for_sale' => $request->enable_line_discount_for_sale ? '1' : '0',
            'currency_symbol_placement' => $request->currency_symbol_placement,
            'use_paymentAccount' => $request->use_paymentAccount ? '1' : '0',
            'finanical_year_start_month' => $request->finanical_year_start_month,
            'start_date' => $request->start_date,

        ];

        if (businessSettings::exists()) {
            businessSettings::first()->update($data);
            return redirect()->back()->with(['success' => 'Successfully updated setting']);
        } else {
            businessSettings::create($data);
            return redirect()->back()->with(['success' => 'Successfully activate setting']);
        };
    }
    public function update(Request $request)
    {
        $timestamp = strtotime($request->start_date);
        $formattedDate = date("Y-m-d", $timestamp);
        $data = [
            'name' => $request->name,
            'lot_control' => $request->lot_control ? 'on' : 'off',
            'currency_id' => $request->currency_id,
            'currency_decimal_places' => $request->currency_decimal_places,
            'quantity_decimal_places' => $request->quantity_decimal_places,
            'accounting_method' => $request->stock_accounting_method,
            'enable_line_discount_for_purchase' => $request->enable_line_discount_for_purchase ? '1' : '0',
            'enable_line_discount_for_sale' => $request->enable_line_discount_for_sale ? '1' : '0',
            'currency_symbol_placement' => $request->currency_symbol_placement,
            'use_paymentAccount' => $request->use_paymentAccount ? '1' : '0',
            'finanical_year_start_month' => $request->finanical_year_start_month,
            'enable_row' => $request->enable_row ?? 0,
            'date_format' => $request->date_format,
            'time_format' => $request->time_format,
            'start_date' => $formattedDate,
            'transaction_edit_days'=>$request->transaction_edit_days,
            'sale_prefix'=>$request->sale_prefix,
            'purchase_prefix'=>$request->purchase_prefix,
            'stock_transfer_prefix'=>$request->stock_transfer_prefix,
            'stock_adjustment_prefix'=>$request->stock_adjustment_prefix,
            'expense_prefix' => $request->expense_prefix,
            'purchase_payment_prefix' => $request->purchase_payment_prefix,
            'expense_payment_prefix' => $request->expense_payment_prefix,
            'sale_payment_prefix' => $request->sale_payment_prefix,
            'expense_report_prefix'=>$request->expense_report_prefix,
            'business_location_prefix' => $request->business_location_prefix,
        ];

        $oldData = businessSettings::where('id', Auth::user()->business_id)->first();
        $logoPath = $this->saveLogo($request, $oldData->logo);
        $data['logo'] = $logoPath;
        $oldData->update($data);
        if ($request->sms_service == 'smsPOH') {
            $newEnv = $request->only([
                'SMSPOH_SENDER', 'SMSPOH_AUTH_TOKEN'
            ]);
            if ($newEnv['SMSPOH_SENDER'] && $newEnv['SMSPOH_AUTH_TOKEN']) {
                updenv($newEnv);
            }
        };
        return redirect()->back()->with(['success' => 'Successfully updated setting']);
    }

    private function saveLogo($request, $existingImagePath = null)
    {
        if ($request->hasFile('logo')) {
            if ($existingImagePath) {
                Storage::delete('logo/' . $existingImagePath);
            }

            $file = $request->file('logo');
            $img_name = time() . '_' . $file->getClientOriginalName();
            Storage::put('logo/' . $img_name, file_get_contents($file));

            return $img_name;
        } else {
            if (!$request->logo && $existingImagePath) {
                Storage::delete('logo/' . $existingImagePath);
                return null;
            } else {
                return $existingImagePath;
            }
        }
    }
}
