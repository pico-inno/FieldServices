<?php

namespace App\Http\Controllers\import;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\location\locationImport;
use Illuminate\Support\Facades\DB;

class locationImportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }
    public function index(){
        return view('App.businessSetting.location.import.locationImport');
    }
    public function import(Request $request)
    {
        try {
            request()->validate([
                'import-locations' => 'required|mimes:xlx,xls,xlsx,csv|max:2048'
            ], [
                'import-locations' => 'Import file is required!'
            ]);

            $file = $request->file('import-locations');
            $import = new locationImport();
            Excel::import($import, $file);
            return redirect()->back()->with('success','Successfully Imported');
        } catch (\Throwable $th) {
            // dd($th);
            DB::rollBack();

            $failures = null;
            if ($th instanceof \Illuminate\Validation\ValidationException) {
                $failures = $th->failures();
            }
            $error = ['error-swal' => $th->getMessage(), 'failures' => $failures];

            return back()->with($error);
        }
    }
    public function dowloadDemoExcel()
    {
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
