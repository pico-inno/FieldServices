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
        try {
            DB::beginTransaction();
            $unitAction->create($request);
            DB::commit();
            activity('unit')
                ->log('New unit creation has been success')
                ->event('create')
                ->status('success')
                ->save();
            return redirect(route('unit-category'))
                ->with(['message', 'Unit Category created successfully',
                    'toUnitCate' => 'to unit cate tab']);
        }catch (Exception $exception){
            DB::rollBack();
            activity('unit')
                ->log('New unit creation has been fail')
                ->event('create')
                ->status('fail')
                ->save();
            return redirect(route('unit-category'))
                ->with(['message', 'Unit Category creation failed',
                    'toUnitCate' => 'to unit cate tab']);
        }
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

        try {
            DB::beginTransaction();
            $unitAction->update($unitCategory->id, $request);
            DB::commit();
            activity('unit')
                ->log('Unit update has been success')
                ->event('update')
                ->status('success')
                ->save();
            return redirect()->route('unit-category')
                ->with(['message', 'Unit Category updated successfully',
                    'toUnitCate' => 'to unit cate tab']);
        }catch (Exception $exception){
            DB::rollBack();
            activity('unit')
                ->log('Unit update has been fail')
                ->event('update')
                ->status('fail')
                ->save();
            return redirect()->route('unit-category')
                ->with(['message', 'Unit Category update failed',
                    'toUnitCate' => 'to unit cate tab']);
        }
    }

    public function delete(UnitCategory $unitCategory, UnitAction $unitAction)
    {
        try {
            DB::beginTransaction();
            $unitAction->delete($unitCategory->id);
            DB::commit();
            activity('unit')
                ->log('Unit deletion has been success')
                ->event('delete')
                ->status('success')
                ->save();
            return response()->json(['message' => 'Unit Category deleted successfully']);
        }catch (Exception $exception){
            DB::rollBack();
            activity('unit')
                ->log('Unit deletion has been fail')
                ->event('delete')
                ->status('fail')
                ->save();
            return response()->json(['message' => 'Unit Category deletion fail']);
        }
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
