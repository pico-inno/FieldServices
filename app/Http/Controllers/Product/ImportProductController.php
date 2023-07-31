<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Models\Product\Brand; 
use App\Models\Product\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ImportProduct\ImportProductCreateRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Product\ProductsImport;

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

        $file = $request->file('import-products');
        $import = new ProductsImport;
        $import->import($file);

        return back();
    }
}
