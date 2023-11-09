<?php

namespace App\Http\Controllers\Product;

use App\Models\Product\UOM;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Product\UnitCategory;
use App\Http\Requests\Product\UoM\UoMCreateRequest;
use App\Http\Requests\Product\UoM\UoMUpdateRequest;

class UoMController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }
    public function index()
    {

    }

    public function add()
    {
        $unitCategories = UnitCategory::select('id', 'name')->get();

        return view('App.product.unit.uomAdd', compact('unitCategories'));
    }

    public function create(UoMCreateRequest $request)
    {
        UOM::create([
            'name' => $request->name,
            'short_name' => $request->short_name,
            'unit_category_id' => $request->unit_category,
            'unit_type' => $request->unit_type,
            'value' => $request->value,
            'rounded_amount' => $request->rounded_amount,
            'created_by' => auth()->id()
        ]);

        return redirect(route('unit-category'))->with(['message' => 'Created sucessfully UoM', 'toUOM' => 'to uom tab']);
    }

    public function edit(UOM $uom)
    {
        $unitCategories = UnitCategory::select('id', 'name')->get();

        return view('App.product.unit.uomEdit', compact('uom', 'unitCategories'));
    }

    public function update(UoMUpdateRequest $request, UOM $uom)
    {
        $uom->update([
            'name' => $request->name,
            'short_name' => $request->short_name,
            'unit_category_id' => $request->unit_category,
            'unit_type' => $request->unit_type,
            'value' => $request->value,
            'rounded_amount' => $request->rounded_amount,
            'updated_by' => auth()->id()
        ]);

        return redirect(route('unit-category'))->with(['message' => 'Updated sucessfully UoM', 'toUOM' => 'to uom tab']);
    }

    public function delete(UOM $uom)
    {
        $uom->deleted_by = auth()->id();
        $uom->save();

        $uom->delete();

        return response()->json(['message' => 'Deleted sucessfully UoM']);
    }

    public function checkUoM($id)
    {
        $uom = UOM::where('unit_category_id', $id)->where('unit_type', 'reference')->get();

        return response()->json($uom);
    }

    public function getUomCategoryByUomId($id){
        $uoms = UOM::where('unit_category_id', $id)->get();

        return response()->json($uoms);
    }

    public function getUomByUomId($id)
    {
        $unitCategoryId = UOM::whereId($id)->first()->unit_category_id;
        $uomByUnitCategoryId = UOM::whereUnitCategoryId($unitCategoryId)->get();

        return response()->json($uomByUnitCategoryId);
    }
}
