<?php

namespace App\Http\Controllers\Product;

use App\Actions\product\unit\UnitAction;
use App\Models\Product\UOM;
use App\Services\UOM\UnitService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Product\UnitCategory;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Product\UnitCategory\UnitCategoryCreateRequest;
use App\Http\Requests\Product\UnitCategory\UnitCategoryUpdateRequest;
use Exception;

class UnitController extends Controller
{
    protected $unitService;
    public function __construct(UnitService $unitService)
    {
        $this->middleware(['auth', 'isActive']);
        $this->middleware('canCreate:unit')->only(['add', 'create']);
        $this->middleware('canUpdate:unit')->only(['edit', 'update']);
        $this->middleware('canDelete:unit')->only('delete');

        $this->unitService = $unitService;
    }


    public function index()
    {
        return view('App.product.unit.index', [
            'unitCategories' => UnitCategory::select('id','name')->get(),
        ]);
    }

    public function add()
    {
        return view('App.product.unit.unitCategoryAdd');
    }

    public function create(UnitCategoryCreateRequest $request, UnitAction $unitAction)
    {
        $unitAction->create($request->name);

        return redirect(route('unit-category'))->with(['message', 'Unit Category created successfully', 'toUnitCate' => 'to unit cate tab']);
    }

    public function edit(UnitCategory $unitCategory)
    {
        return view('App.product.unit.unitCategoryEdit', [
            'unitCategory' => $unitCategory,
        ]);
    }

    public function update(UnitCategoryUpdateRequest $request, UnitCategory $unitCategory, UnitAction $unitAction)
    {

        if($request->has('cancel')){
            return redirect()->route('unit-category')->with('toUnitCate', 'cancel');
        }

        $unitAction->update($unitCategory->id, $request->name);

        return redirect()->route('unit-category')->with(['message', 'Unit Category updated successfully', 'toUnitCate' => 'to unit cate tab']);
    }

    public function delete(UnitCategory $unitCategory, UnitAction $unitAction)
    {
        $unitAction->delete($unitCategory->id);

        return response()->json(['message' => 'Unit Category deleted successfully']);

    }

    public function unitCategoryDatas()
    {
        return $this->unitService->getUnitCategoryDataForDataTable();
    }

    public function uomDatas()
    {
        return $this->unitService->getUomDataForDatatbale();
    }
}
