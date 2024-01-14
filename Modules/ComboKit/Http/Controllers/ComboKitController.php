<?php

namespace Modules\ComboKit\Http\Controllers;

use App\Models\Product\Product;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ComboKit\Http\Requests\RoMRequest;
use Modules\ComboKit\Services\RoMService;

class ComboKitController extends Controller
{
    protected $romService;

    public function __construct(RoMService $romService)
    {
        $this->middleware(['auth', 'isActive']);
        $this->romService = $romService;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $roms = $this->romService->getAllRoM();
        foreach ($roms as $rom){
            $rom->deleted_pd_name = Product::withTrashed()->where('id', $rom->product_id)->pluck('name')->first();
        }


        return view('combokit::RoM.index',[
            'roms' => $roms,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('combokit::RoM.create',[
            'products' => $this->romService->getAllProducts(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(RoMRequest $request)
    {
        RoMService::createRomWithDetails($request);

        return redirect(route('combokit.index'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $rom = $this->romService->getAllRoM($id);

        $data = $this->romService->getAllProducts();
        $foundProduct = array_filter($data, function ($product) use ($rom)  {
            return $product['id'] == $rom[0]->product_id;
        });
        $filteredProduct = array_values($foundProduct);
        $product_variations = optional($filteredProduct)[0];
        $romdetails = $this->romService->getAllRoMDetail($id);

        if ($romdetails[0]->productVariation === null && optional($rom[0])->product === null){
            return back()->with('error-swal', 'Cannot View. Included products and template products are no longer available.')->with('kit_id', $id);
        }

        return view('combokit::RoM.show',[
            'rom' => $rom[0],
            'romdetails' => $romdetails,
            'products' => $this->romService->getAllProducts(),
            'applied_variation_data' => optional($product_variations)['product_variations'],
        ]);
    }


    public function edit($id)
    {

        $rom = $this->romService->getAllRoM($id);
        $data = $this->romService->getAllProducts();

        $foundProduct = array_filter($data, function ($product) use ($rom) {
            return $product['id'] == $rom[0]->product_id;
        });

        $filteredProduct = array_values($foundProduct);
        $romdetails = $this->romService->getAllRoMDetail($id);
        $product = $this->romService->getAllProducts();

        if ($romdetails[0]->productVariation === null && optional($rom[0])->product === null){
            return back()->with('error-swal', 'Cannot Edit. Included products and template products are no longer available.')->with('kit_id', $id);
        }

        return view('combokit::RoM.edit',[

            'update_id' => $id,
            'rom' => $rom[0],
            'romdetails' => $romdetails,
            'products' => $product,
            'applied_variation_data' => optional($filteredProduct)[0],
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {

        RoMService::updateRomWithDetails($id, $request);

        return redirect(route('combokit.index'));

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id){
        $this->romService->destroy($id);
        return back();
    }

    public function makeDefault($id){
        RoMService::makeDefault($id);
        return back();

    }
}
