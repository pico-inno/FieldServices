<?php

namespace App\Http\Controllers\Product;

use App\Models\Product\Brand;
use App\Models\Product\UOMSet;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Product\Brand\BrandCreateRequest;
use App\Http\Requests\Product\Brand\BrandUpdateRequest;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
        $this->middleware('canView:brand')->only(['index', 'unitDatas', 'uomDatas']);
        $this->middleware('canCreate:brand')->only(['add', 'create']);
        $this->middleware('canUpdate:brand')->only(['edit', 'update']);
        $this->middleware('canDelete:brand')->only('delete');
    }

    public function datas()
    {
        $brands = Brand::all();

        return DataTables::of($brands)
        ->addColumn('action', function($brand){
            return $brand->id;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function index()
    {
        return view('App.product.brand.brandList');
    }

    public function add()
    {
        return view('App.product.brand.brandAdd');
    }

    public function create(BrandCreateRequest $request)
    {
        $brand = new Brand();
        $brand->name = $request->brand_name;
        $brand->description = $request->brand_desc;
        $brand->created_by = Auth::user()->id;

        $brand->save();

        if($request->brand_create){
            return back()->with('message', 'Created sucessfully brand');
        }

        return redirect('/brands')->with('message', 'Created sucessfully brand');
    }

    public function edit(Brand $brand)
    {
        return view('App.product.brand.brandEdit', compact('brand'));
    }

    public function update(BrandUpdateRequest $request, Brand $brand)
    {
        $brand->name = $request->brand_name;
        $brand->description = $request->brand_desc;
        $brand->updated_by = Auth::user()->id;

        $brand->save();

        return redirect('/brands')->with('message', 'Updated sucessfully brand');
    }

    public function delete(Brand $brand)
    {
        $brand->deleted_by = Auth::user()->id;
        $brand->save();
        $brand->delete();

        return response()->json(['message' => 'Deleted sucessfully brand']);
    }
}
