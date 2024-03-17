<?php

namespace App\Http\Controllers\import;

use PDO;
use Illuminate\Http\Request;
use App\Models\Product\PriceLists;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Product\PriceListDetails;
use App\Models\settings\businessSettings;
use Illuminate\Support\Facades\Validator;
use App\Imports\priceList\priceListImport;

class priceListImportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }
   public function import(Request $request,$action='create',$id=null){

        request()->validate([
            'importPriceList' => 'required|mimes:xlx,xls,xlsx,csv|max:2048',
            'name'=>'required',
            'base_price'=>'required',
            'currency_id'=>'required',
        ], [
            'importPriceList' => 'Import file is required!',
            'name'=>"Price List Name is Requried",
            'currency'=>"Currency is requried"
        ]);
        try {
            $file = $request->file('importPriceList');
            $priceListData = $this->priceListData($request);
            $id=PriceLists::create($priceListData)->id;
            $import = new priceListImport($id);

            Excel::import($import, $file);
            $processedData = $import->getProcessedData();
            // dd($processedData);
            PriceListDetails::insert($processedData);
            // if($action=='update') {
            //     return redirect()->route('price-list-detail.edit', ['PriceListDetaildataFromExcel' => $processedData, 'priceListData' => $priceListData, 'priceList' => $id])->with(['noti'=> 'Greate ! You Successfully Imported. Check and Save the data']);
            // }else{

            //     return redirect()->route('price-list-detail.add', ['PriceListDetaildata' => $processedData, 'priceListData' => $priceListData])->with(['noti'=> 'Greate ! You Successfully Imported. Check and Save the data']);
            // }
             return redirect()->route('price-list-detail')->with(['success'=>'Successfully Imported']);
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);

            $failures = [];
            if(method_exists($th, 'failures') && $th instanceof \Illuminate\Validation\ValidationException ){
                $failures = $th->failures();
            }
            $error = ['error-swal' => $th->getMessage(), 'failures' => $failures];
            activity('PriceList-transaction')
                ->log('Price List import has been fail')
                ->event('import')
                ->status('fail')
                ->save();
            return back()->with($error);
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
