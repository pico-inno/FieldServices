<?php

namespace App\Http\Controllers\Product;

use App\Imports\Product\ProductsImportV2;
use Illuminate\Http\Request;
use App\Models\Product\Brand;
use App\Models\Product\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ImportProduct\ImportProductCreateRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Product\ProductsImport;
use Maatwebsite\Excel\Validators\ValidationException;

class ImportProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
        $this->middleware('canView:product')->only('index');
        $this->middleware('canCreate:product')->only('create');
    }

    public function index()
    {
        return view('App.product.import-product.importProduct');
    }

    public function create(ImportProductCreateRequest $request)
    {
        // Excel::import(new ProductsImport, $request->file('import-products'));

        try {
            DB::beginTransaction();
            $file = $request->file('import-products');
            // $status = Excel::import(new ProductsImport(), $file);
            $import = new ProductsImportV2();
            $importMessage=$import->import($file);
            DB::commit();
            activity('product-transaction')
                ->log('Products import has been success')
                ->event('import')
                ->status('success')
                ->save();

            return back()->with(['success-swal' => 'Successfully Imported']);
        } catch (\Throwable $th) {
            DB::rollBack();

            $failures = null;
            if($th instanceof \Illuminate\Validation\ValidationException){
                $failures = $th->failures();
            }
            $error = ['error-swal' => $th->getMessage(), 'failures' => $failures];
            activity('product-transaction')
                ->log('Product import has been fail')
                ->event('import')
                ->status('fail')
                ->save();
            return back()->with($error);
        }
    }
    public function dowloadDemoExcel()
    {
        $path = public_path('Excel/import_products_csv_template.xls');
        if (!file_exists($path)) {
            abort(404);
        }

        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="import_products_csv_template.xls"',
        ];

        activity('product-transaction')
            ->log('Product template download has been success')
            ->event('download')
            ->status('success')
            ->save();

        return response()->download($path, 'import_products_csv_template.xls', $headers);
    }
}
