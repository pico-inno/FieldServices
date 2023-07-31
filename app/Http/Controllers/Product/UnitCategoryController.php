<?php

namespace App\Http\Controllers\Product;

use App\Models\Product\UOM;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Product\UnitCategory;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Product\UnitCategory\UnitCategoryCreateRequest;
use App\Http\Requests\Product\UnitCategory\UnitCategoryUpdateRequest;
use Exception;

class UnitCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
        $this->middleware('canCreate:unit')->only(['add', 'create']);
        $this->middleware('canUpdate:unit')->only(['edit', 'update']);
        $this->middleware('canDelete:unit')->only('delete');
    }

    public function unitCategoryDatas()
    {
        $unitCategories = UnitCategory::query();

        return DataTables::of($unitCategories)
        ->addColumn('uom', function($unitCategory){
            $uoms = $unitCategory->uomByCategory;
            $uomLists = '';

            if($uoms){
                foreach($uoms as $uom){
                    if($uom->unit_type === "reference"){
                        $uomLists .= '<span class="badge badge-light-success">' . $uom->short_name . '</span>&nbsp;';
                    }else{
                        $uomLists .= '<span class="badge badge-light-dark">' . $uom->short_name . '</span>&nbsp;';
                    }
                }
            }
            return $uomLists;
        })
        ->addColumn('action', function($unitCategory){
            return $unitCategory->id;
        })
        ->rawColumns(['uom', 'action'])
        ->make(true);
    }

    public function uomDatas()
    {
        $uoms = UOM::query();

        return DataTables::of($uoms)
        ->addColumn('unit_category', function($uom){
            return $uom->unit_category->name;
        })
        ->addColumn('action', function($uom){
            return $uom->id;
        })
        ->rawColumns(['unit_category', 'action'])
        ->make(true);
    }

    public function index()
    {
        $unitCategories = UnitCategory::all();
        if (hasView('unit') || hasView('uom')){
            return view('App.product.unit.unitCategoryList', compact('unitCategories'));
        }else{
            return abort(401);
        }
        $uom = UOM::whereId(1)->first();
        $uomByUnitCategoryId = UOM::whereUnitCategoryId($uom->id)->get();

        return view('App.product.unit.unitCategoryList', compact('unitCategories'));
    }

    public function add()
    {
        return view('App.product.unit.unitCategoryAdd');
    }

    public function create(UnitCategoryCreateRequest $request)
    {

        UnitCategory::create([
            'name' => $request->name,
            'created_by' => auth()->id(),
        ]);

        return redirect(route('unit-category'))->with(['message', 'Created sucessfully unit category', 'toUnitCate' => 'to unit cate tab']);
    }

    public function edit(UnitCategory $unitCategory)
    {
        return view('App.product.unit.unitCategoryEdit', compact('unitCategory'));
    }

    public function update(UnitCategoryUpdateRequest $request, UnitCategory $unitCategory)
    {
        if($request->has('cancle')){
            return redirect(route('unit-category'))->with('toUnitCate', 'cancel');
        }

        $unitCategory->update([
            'name' => $request->name,
            'updated_by' => auth()->id(),
        ]);

        return redirect(route('unit-category'))->with(['message', 'Updated sucessfully unit category', 'toUnitCate' => 'to unit cate tab']);
    }

    public function delete(UnitCategory $unitCategory)
    {
        DB::beginTransaction();
        try{
            $uomIds = UOM::where('unit_category_id', $unitCategory->id)->get()->pluck('id');
            UOM::whereIn('id', $uomIds)->update(['deleted_by' => auth()->id()]);
            UOM::destroy($uomIds);

            $unitCategory->deleted_by = auth()->id();
            $unitCategory->save();
            $unitCategory->delete();

            DB::commit();
            return response()->json(['message' => 'Deleted sucessfully unit category']);
        }catch (Exception $e){
            DB::rollBack();
        }

    }
}
