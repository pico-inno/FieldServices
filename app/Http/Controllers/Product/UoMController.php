<?php

namespace App\Http\Controllers\Product;

use App\Actions\product\unit\UOMAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\UoM\UoMCreateRequest;
use App\Http\Requests\Product\UoM\UoMUpdateRequest;
use App\Models\Product\UOM;
use App\Repositories\Product\UnitCategoryRepository;
use App\Repositories\Product\UOMRepository;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Mime\DraftEmail;

class UoMController extends Controller
{
    protected $uomRepository;
    protected $unitCategoryRepository;
    public function __construct(UOMRepository $uomRepository, UnitCategoryRepository $unitCategoryRepository)
    {
        $this->middleware(['auth', 'isActive']);
        $this->uomRepository = $uomRepository;
        $this->unitCategoryRepository = $unitCategoryRepository;
    }

    public function add()
    {
        return view('App.product.unit.uomAdd', [
            'unitCategories' => $this->unitCategoryRepository->getAll(),
        ]);
    }

    public function create(UoMCreateRequest $request, UOMAction $uomAction)
    {
        try {
            DB::beginTransaction();
            $uomAction->create($request);
            DB::commit();
            activity('uom')
                ->log('New uom creation has been success')
                ->event('create')
                ->status('success')
                ->save();
            return redirect()->route('unit-category')->with(['message' => 'UOM created successfully', 'toUOM' => 'to uom tab']);
        }catch (\Exception $exception){
            DB::rollBack();
            activity('uom')
                ->log('New uom creation has been fail')
                ->event('create')
                ->status('fail')
                ->save();
            return redirect()->route('unit-category')->with(['message' => 'UOM creation failed', 'toUOM' => 'to uom tab']);
        }
    }

    public function edit(UOM $uom)
    {
        return view('App.product.unit.uomEdit', [
            'uom' => $uom,
            'unitCategories' => $this->unitCategoryRepository->getAll(),
        ]);
    }

    public function update(UoMUpdateRequest $request, UOM $uom, UOMAction $uomAction)
    {
        try {
            DB::beginTransaction();
            $uomAction->update($uom->id, $request);
            DB::commit();
            activity('uom')
                ->log('Uom update has been success')
                ->event('update')
                ->status('success')
                ->save();
            return redirect(route('unit-category'))->with(['message' => 'UOM updated successfully', 'toUOM' => 'to uom tab']);
        }catch (\Exception $exception){
            DB::rollBack();
            activity('uom')
                ->log('Uom update has been fail')
                ->event('update')
                ->status('fail')
                ->save();
            return redirect(route('unit-category'))->with(['message' => 'UOM update failed', 'toUOM' => 'to uom tab']);
        }
    }

    public function delete(UOM $uom, UOMAction $uomAction)
    {
        try {
            DB::beginTransaction();
            $uomAction->delete($uom->id);
            DB::commit();
            activity('uom')
                ->log('Uom deletion has been success')
                ->event('delete')
                ->status('success')
                ->save();
            return response()->json(['message' => 'UOM deleted successfully']);
        }catch (\Exception $exception){
            DB::rollBack();
            activity('uom')
                ->log('Uom deletion has been fail')
                ->event('delete')
                ->status('fail')
                ->save();
            return response()->json(['message' => 'UOM deleted successfully']);
        }
    }

    public function checkUoM($id)
    {
        $uom = UOM::where('unit_category_id', $id)->where('unit_type', 'reference')->get();

        return response()->json($uom);
    }

    public function getUomCategoryByUomId($id){
        $uoms = $this->uomRepository->getByCategoryId($id);

        return response()->json($uoms);
    }

    public function getUomByUomId($id)
    {
        $unitCategoryId = $this->uomRepository->getById($id)->unit_category_id;
        $uoms = $this->uomRepository->getByCategoryId($unitCategoryId);

        return response()->json($uoms);
    }
}
