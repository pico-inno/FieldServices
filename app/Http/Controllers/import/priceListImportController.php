<?php

namespace App\Http\Controllers\import;

use Illuminate\Http\Request;
use App\Models\Product\PriceLists;
use App\Http\Controllers\Controller;
use App\Imports\priceList\priceListImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\settings\businessSettings;
use Illuminate\Support\Facades\DB;

class priceListImportController extends Controller
{
   public function import(Request $request){
        try {

            request()->validate([
                'importPriceList' => 'required|mimes:xlx,xls,xlsx,csv|max:2048',
                'name' => 'required',
                'base_price' => 'required',
                'currency_id' => 'required',
            ], [
                'importPriceList' => 'Import file is required!',
                'name.required' => 'Price List name is required!',
                'currency_id.required' => 'Currency is required!',
            ]);
            // DB::beginTransaction();
            $file = $request->file('importPriceList');
            // dd($file);

            $priceList = $this->createPriceList($request)->toArray();
            $priceList['base_price']=$request['base_price'];
             Excel::import(new priceListImport($priceList), $file);
             return back()->with(['success'=>'Successfully Imported']);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }



   }
   public function createPriceList($request){
        $business_id = businessSettings::first()->id;
       return PriceLists::create([
            'price_list_type' => $request->price_list_type,
            'business_id' => $business_id,
            'currency_id' => $request->currency_id,
            'name' => $request->name,
            'description' => $request->description
        ]);
   }
   public function dowloadDemoExcel(){
        $path = public_path('Excel/priceList1.xls');
        if (!file_exists($path)) {
            abort(404);
        }
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="importPriceList1.xls"',
        ];
        return response()->download($path, 'importPriceList.xls', $headers);
   }
}
