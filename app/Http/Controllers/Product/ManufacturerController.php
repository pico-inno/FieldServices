<?php

namespace App\Http\Controllers\Product;

use App\Actions\product\ManufacturerAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\Manufacturer\ManufacturerCreateRequest;
use App\Http\Requests\Product\Manufacturer\ManufacturerUpdateRequest;
use App\Models\Product\Manufacturer;
use App\Repositories\Product\ManufacturerRepository;
use Yajra\DataTables\Facades\DataTables;

class ManufacturerController extends Controller
{
    protected $manufacturerRepository;

    public function __construct(ManufacturerRepository $manufacturerRepository)
    {
        $this->middleware(['auth', 'isActive']);
        $this->middleware('canView:manufacture')->only(['index', 'unitDatas', 'uomDatas']);
        $this->middleware('canCreate:manufacture')->only(['add', 'create']);
        $this->middleware('canUpdate:manufacture')->only(['edit', 'update']);
        $this->middleware('canDelete:manufacture')->only('delete');

        $this->manufacturerRepository = $manufacturerRepository;
    }

    public function datas()
    {
        $manufacturers = $this->manufacturerRepository->getAll();

        return DataTables::of($manufacturers)
            ->addColumn('action', function ($manufacturer) {
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

    public function create(ManufacturerCreateRequest $request, ManufacturerAction $manufacturerAction)
    {
        $manufacturerAction->create($request);

        if ($request->form_type === "from_product") {
            return response()->json([
                'message' => 'Manufacturer created successfully',
                'manufacturers' => $this->manufacturerRepository->getAll()
            ]);
        } else {
            return redirect()->route('manufacturer')->with('message', 'Manufacturer created successfully');
        }
    }

    public function edit(Manufacturer $manufacturer)
    {
        return view('App.product.manufacturer.manufacturerEdit', [
            'manufacturer' => $manufacturer
        ]);
    }

    public function update(ManufacturerUpdateRequest $request, Manufacturer $manufacturer, ManufacturerAction $manufacturerAction)
    {
        $manufacturerAction->update($manufacturer->id, $request);

        return redirect()->route('manufacturer')->with('message', 'Manufacturer updated successfully');
    }

    public function delete(Manufacturer $manufacturer, ManufacturerAction $manufacturerAction)
    {
        $manufacturerAction->delete($manufacturer->id);

        return response()->json(['message' => 'Manufacturer deleted successfully']);
    }
}
