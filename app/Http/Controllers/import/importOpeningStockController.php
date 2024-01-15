<?php

namespace App\Http\Controllers\import;

use Illuminate\Http\Request;
use App\Models\openingStocks;
use Illuminate\Support\Facades\DB;
use App\Models\openingStockDetails;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\openingStocks\Import;
use App\Imports\openingStocks\OpeningImport;

class importOpeningStockController extends Controller
{
    public function __construct()
    {

        $this->middleware(['auth', 'isActive']);
    }
    public function import(Request $request)
    {

        try {

        ini_set('max_execution_time', '0');
            ini_set("memory_limit", "-1");
            DB::beginTransaction();
            $file = $request->file('ImportedFile');
            request()->validate([
                'ImportedFile' => 'required|mimes:xlx,xls,xlsx,csv|max:2048',
                'business_location_id' => 'required',
                'opening_date' => 'required',
            ], [
                'ImportedFile' => 'Import file is required!',
                'business_location_id.required' => 'Bussiness Location is required!',
                'opening_date.required' => 'Opening Date is required!',
            ]);
            $opening_stock_count = openingStocks::count();
            $opening_stock_data = [
                'business_location_id' => $request->business_location_id,
                'opening_stock_voucher_no' => sprintf('OS-' . '%06d', ($opening_stock_count + 1)),
                'opening_date' => now(),
                'opening_person' => Auth::user()->id,
                'total_opening_amount' => $request->total_opening_amount,
                'note' => $request->note,
                'created_by' => Auth::user()->id,
                'updated_at' => null,
            ];

            $openingStocks = openingStocks::create($opening_stock_data);
            $status = Excel::import(new OpeningImport($openingStocks), $file);
            DB::commit();
            if ($status) {
                $total_opening_amount = openingStockDetails::where('opening_stock_id', $openingStocks->id)->sum('subtotal');
                $openingStocks->update([
                    'total_opening_amount' => $total_opening_amount
                ]);
            } else {
                DB::rollBack();
                return back()->with(['warning' => 'Something Went Wrong!']);
            }
            activity('opening-stock')
                ->log('Opening Stock import has been success')
                ->event('import')
                ->status('success')
                ->save();
            return redirect()->route('opening_stock_list')->with(['success' => 'Successfully imported!']);
        } catch (\Throwable $th) {

            DB::rollBack();
            activity('opening-stock')
                ->log('Opening Stock import has been fail')
                ->event('import')
                ->status('fail')
                ->save();
            $failures = null;
            if($th instanceof \Illuminate\Validation\ValidationException){
                $failures = $th->failures();
            }
            $error = ['error' => $th->getMessage(), 'failures' => $failures];
            return back()->with($error)->withInput();
        }

    }
    public function dowloadDemoExcel()
    {
        $path = public_path('Excel/importOpeningStock.xls');
        if (!file_exists($path)) {
            abort(404);
        }

        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="importOpeningStock.xls"',
        ];

        activity('opening-stock')
            ->log('Opening Stock template download has been success')
            ->event('download')
            ->status('success')
            ->save();
        return response()->download($path, 'importOpeningStock.xls', $headers);
    }

}
