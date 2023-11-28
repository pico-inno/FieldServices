<?php

namespace App\Http\Controllers\Product;

use App\Actions\product\GenericAction;
use App\repositories\GenericRepository;
use Illuminate\Http\Request;
use App\Models\Product\Generic;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Product\Generic\GenericCreateRequest;
use App\Http\Requests\Product\Generic\GenericUpdateRequest;

class GenericController extends Controller
{
    protected $genericRepository;

    public function __construct(GenericRepository $genericRepository)
    {
        $this->middleware(['auth', 'isActive']);
        $this->middleware('canView:generic')->only(['index', 'unitDatas', 'uomDatas']);
        $this->middleware('canCreate:generic')->only(['add', 'create']);
        $this->middleware('canUpdate:generic')->only(['edit', 'update']);
        $this->middleware('canDelete:generic')->only('delete');

        $this->genericRepository = $genericRepository;
    }

    public function datas()
    {
        $generics = $this->genericRepository->getAll();

        return DataTables::of($generics)
            ->addColumn('action', function ($generic) {
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

    public function create(GenericCreateRequest $request, GenericAction $genericAction)
    {
        $genericAction->create($request);

        if ($request->form_type === "from_product") {
            return response()->json([
                'message' => 'Generic created successfully',
                'generics' => $this->genericRepository->getAll()
            ]);
        } else {
            return redirect()->route('generic')->with('message', 'Generic created successfully');
        }

    }

    public function edit(Generic $generic)
    {
        return view('App.product.generic.genericEdit', [
            'generic' => $generic
        ]);
    }

    public function update(GenericUpdateRequest $request, Generic $generic, GenericAction $genericAction)
    {
        $genericAction->update($generic->id, $request);

        return redirect()->route('generic')->with('message', 'Generic updated successfully');
    }

    public function delete(Generic $generic, GenericAction $genericAction)
    {
        $genericAction->delete($generic->id);

        return response()->json(['message' => 'Generic deleted successfully']);
    }
}
