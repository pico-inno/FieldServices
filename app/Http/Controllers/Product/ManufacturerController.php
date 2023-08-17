<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Manufacturer;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Product\Manufacturer\ManufacturerCreateRequest;
use App\Http\Requests\Product\Manufacturer\ManufacturerUpdateRequest;

class ManufacturerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
        $this->middleware('canView:manufacture')->only(['index', 'unitDatas', 'uomDatas']);
        $this->middleware('canCreate:manufacture')->only(['add', 'create']);
        $this->middleware('canUpdate:manufacture')->only(['edit', 'update']);
        $this->middleware('canDelete:manufacture')->only('delete');
    }
    public function datas()
    {
        $manufacturers = Manufacturer::all();

        return DataTables::of($manufacturers)
        ->addColumn('action', function($manufacturer){
            return $manufacturer->id;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function index()
    {
        return view('App.product.manufacturer.manufacturerList');
    }

    public function add()
    {
        return view('App.product.manufacturer.manufacturerAdd');
    }

    public function create(ManufacturerCreateRequest $request)
    {
        $manufacturer = new Manufacturer();
        $manufacturer->name = $request->manufacturer_name;
        $manufacturer->created_by = Auth::user()->id;

        $manufacturer->save();
        $manufacturers = Manufacturer::all();

        if($request->form_type === "from_product"){
            return response()->json([
                'message' => 'Manufacturer created sucessfully',
                'manufacturers' => $manufacturers
            ]);
        }

        return redirect('/manufacturer')->with('message', 'Created sucessfully manufacturer');
    }

    public function edit(Manufacturer $manufacturer)
    {
        return view('App.product.manufacturer.manufacturerEdit', compact('manufacturer'));
    }

    public function update(ManufacturerUpdateRequest $request, Manufacturer $manufacturer )
    {
        $manufacturer->name = $request->manufacturer_name;
        $manufacturer->updated_by = Auth::user()->id;

        $manufacturer->save();

        return redirect('/manufacturer')->with('message', 'Updated sucessfully manufacturer');
    }

    public function delete(Manufacturer $manufacturer)
    {
        $manufacturer->deleted_by = Auth::user()->id;
        $manufacturer->save();
        $manufacturer->delete();

        return response()->json(['message' => 'Deleted sucessfully manufacturer']);
    }
}
