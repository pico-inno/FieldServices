<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Models\Product\Generic;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Product\Generic\GenericCreateRequest;
use App\Http\Requests\Product\Generic\GenericUpdateRequest;

class GenericController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
        $this->middleware('canView:generic')->only(['index', 'unitDatas', 'uomDatas']);
        $this->middleware('canCreate:generic')->only(['add', 'create']);
        $this->middleware('canUpdate:generic')->only(['edit', 'update']);
        $this->middleware('canDelete:generic')->only('delete');
    }

    public function datas()
    {
        $generics = Generic::all();

        return DataTables::of($generics)
        ->addColumn('action', function($generic){
            return $generic->id;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function index()
    {
        return view('App.product.generic.genericList');
    }

    public function add()
    {
        return view('App.product.generic.genericAdd');
    }

    public function create(GenericCreateRequest $request)
    {
        $generic = new Generic();
        $generic->name = $request->generic_name;
        $generic->created_by = Auth::user()->id;

        $generic->save();

        if($request->generic_create){
            return back()->with('message', 'Created sucessfully generic');
        }

        return redirect('/generic')->with('message', 'Created sucessfully generic');
    }

    public function edit(Generic $generic)
    {
        return view('App.product.generic.genericEdit', compact('generic'));
    }

    public function update(GenericUpdateRequest $request, Generic $generic)
    {
        $generic->name = $request->generic_name;
        $generic->updated_by = Auth::user()->id;

        $generic->save();

        return redirect('/generic')->with('message', 'Updated sucessfully generic');
    }

    public function delete(Generic $generic)
    {
        $generic->deleted_by = Auth::user()->id;
        $generic->save();
        $generic->delete();

        return response()->json(['message' => 'Deleted sucessfully generic']);
    }
}
