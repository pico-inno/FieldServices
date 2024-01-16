<?php

namespace App\Http\Controllers\userManagement\users;

use App\Http\Controllers\Controller;
use App\Imports\Product\ProductsImport;
use App\Imports\userManagement\BusinessUserImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }

    public function index()
    {
        return view('App.userManagement.users.import');
    }

    public function download() {

        $path = public_path('Excel/userImportTemplate.xls');

        if (!file_exists($path)) {
            activity('user')
                ->log('User import template has been not found')
                ->event('download')
                ->status('warn')
                ->save();
            abort(404);
        }

        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="userImportTemplate.xls"',
        ];

        activity('user')
            ->log('User import template download has been success')
            ->event('download')
            ->status('success')
            ->save();

        return response()->download($path, 'userImportTemplate.xls', $headers);
    }

    public function store(Request $request)
    {

        try {
            DB::beginTransaction();
            $file = $request->file('user_template_file');

            $import = new BusinessUserImport();
            $import->import($file);
            DB::commit();
            activity('user')
                ->log('Business user import has been success')
                ->event('import')
                ->status('success')
                ->save();

            return back()->with(['success-swal' => 'Successfully Imported']);
        } catch (\Throwable $th) {
            Db::rollBack();

            $failures = null;
            if($th instanceof \Illuminate\Validation\ValidationException){
                $failures = $th->failures();
            }
            $error = ['error-swal' => $th->getMessage(), 'failures' => $failures];
            activity('product-transaction')
                ->log('Business user import has been fail')
                ->event('import')
                ->status('fail')
                ->save();
            return back()->with($error);
        }
    }
}
