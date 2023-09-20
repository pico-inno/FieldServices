<?php

namespace App\Http\Controllers\import;

use Illuminate\Http\Request;
use App\Models\Product\PriceLists;
use App\Http\Controllers\Controller;
use App\Imports\priceList\priceListImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\settings\businessSettings;
use Illuminate\Support\Facades\DB;
use PDO;

class priceListImportController extends Controller
{
   public function import(Request $request,$action='create',$id=null){
        try {

            request()->validate([
                'importPriceList' => 'required|mimes:xlx,xls,xlsx,csv|max:2048'
            ], [
                'importPriceList' => 'Import file is required!'
            ]);
            $file = $request->file('importPriceList');
            $priceListData = $this->priceListData($request);
            $import = new priceListImport();
            Excel::import($import, $file);
            $processedData = $import->getProcessedData();
            if($action=='update') {
                return redirect()->route('price-list-detail.edit', ['PriceListDetaildataFromExcel' => $processedData, 'priceListData' => $priceListData, 'priceList' => $id])->with(['noti'=> 'Greate ! You Successfully Imported. Check and Save the data']);
            }else{
                return redirect()->route('price-list-detail.add', ['PriceListDetaildata' => $processedData, 'priceListData' => $priceListData])->with(['noti'=> 'Greate ! You Successfully Imported. Check and Save the data']);
            }
            //  return back()->with(['success'=>'Successfully Imported']);
        } catch (\Throwable $th) {
             return back()->with(['error'=>$th->getMessage()]);
        }
   }
   public function priceListData($request){
        $business_id = businessSettings::first()->id;
       return[
            'price_list_type' => $request->price_list_type,
            'business_id' => $business_id,
            'currency_id' => $request->currency_id,
            'name' => $request->name,
            'description' => $request->description,
            'base_price' => $request->base_price,
        ];
   }
   public function dowloadDemoExcel(){
        $path = public_path('Excel/priceList.xls');
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
