<?php

namespace App\Http\Controllers\Product;

use App\Actions\product\BrandAction;
use App\Models\Product\Brand;
use App\Models\Product\UOMSet;
use App\Http\Controllers\Controller;
use App\repositories\BrandRepository;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Product\Brand\BrandCreateRequest;
use App\Http\Requests\Product\Brand\BrandUpdateRequest;

class BrandController extends Controller
{
    protected $brandRepository;
    public function __construct(BrandRepository $brandRepository)
    {
        $this->middleware(['auth', 'isActive']);
        $this->middleware('canView:brand')->only(['index', 'unitDatas', 'uomDatas']);
        $this->middleware('canCreate:brand')->only(['add', 'create']);
        $this->middleware('canUpdate:brand')->only(['edit', 'update']);
        $this->middleware('canDelete:brand')->only('delete');

        $this->brandRepository = $brandRepository;
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

    public function create(BrandCreateRequest $request, BrandAction $brandAction)
    {
        $brandAction->create($request);

        if($request->form_type === "from_product"){
            return response()->json([
                'message' => 'Brand created successfully',
                'brands' => $this->brandRepository->getAll()
            ]);
        }else{
            return redirect()->route('brands')->with('message', 'Brand created successfully');
        }

    }

    public function edit(Brand $brand)
    {
        return view('App.product.brand.brandEdit', [
            'brand' => $brand
        ]);
    }

    public function update(BrandUpdateRequest $request, Brand $brand, BrandAction $brandAction)
    {
        $brandAction->update($brand->id, $request);

        return redirect()->route('brands')->with('message', 'Brand updated successfully');
    }

    public function delete(Brand $brand, BrandAction $brandAction)
    {

        $brandAction->delete($brand->id);

        return response()->json(['message' => 'Brand deleted successfully']);
    }
}
