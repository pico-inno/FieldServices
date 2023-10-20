<?php

namespace Modules\Barcode\Http\Controllers;

use App\Models\Product\PriceLists;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Barcode\Actions\Barcode\Barcode;
use Modules\Barcode\Entities\BarcodeTemplate;

class BarcodeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return data
     */
    public function listData()
    {
        $templates= BarcodeTemplate::all();
        return DataTables::of($templates)
            ->addColumn('checkbox', function ($template) {
                return
                    '
                    <div class="form-check form-check-sm form-check-custom ">
                        <input class="form-check-input" type="checkbox" data-checked="delete" value=' . $template->id . ' />
                    </div>
                ';
            })
            ->addColumn('action', function ($template) {
                $editBtn ='<a href=" ' . route('barcode.edit', $template->id) . ' " class="dropdown-item p-2 edit-unit bg-active-primary fw-semibold" >Edit</a>' ;
                $html = '
                    <div class="dropdown ">
                        <button class="btn m-2 btn-sm btn-light btn-primary fw-semibold fs-7  dropdown-toggle " type="button" id="purchaseDropDown" data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                        </button>
                        <div class="z-3">
                        <ul class="dropdown-menu z-10 p-5 " aria-labelledby="purchaseDropDown" role="menu">';

                $html .= $editBtn;
                    $html .= '   <a class="dropdown-item p-2  cursor-pointer bg-active-danger fw-semibold text-danger"  data-id="' . $template->id . '" data-kt-barcode-table="delete_row">Delete</a>';
                $html .= '</ul></div></div>';
                return $html;
            })
            ->rawColumns(['action', 'checkbox', 'status', 'date', 'payment_status'])
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('barcode::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('barcode::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request,Barcode $barcode)
    {
        $barcode->create($request);
        return redirect()->route('barcode.index')->with(['success'=>'Successfully Created']);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('barcode::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $template=BarcodeTemplate::where('id',$id)->firstOrFail();
        $templateData= json_decode($template->template_data);
        return view('barcode::edit',compact('template', 'templateData'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id,Barcode $barcode)
    {
        $barcode->update($id,$request);
        return redirect()->route('barcode.index')->with(['success' => 'Successfully Updated']);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        BarcodeTemplate::where('id',$id)->first()->delete();
        return response()->json(['success'=>'Successfully Deleted'], 200);
    }

    /**
     * prepare to print product barcode page
     *
     * @return void
     */
    public function prepare(){
        $barcodeTemplates=BarcodeTemplate::get();
        $priceLists=PriceLists::get();
        return view('barcode::layouts.prepare',compact('barcodeTemplates', 'priceLists'));
    }

    public function print(Request $request){
        // dd($request->toArray());
        $data=$request;
        $template=BarcodeTemplate::where('id',$request->template_type)->first();
        $templateData = json_decode($template->template_data);
        // dd($templateData);
        return view('barcode::layouts.print',compact('data', 'template', 'templateData'));
        dd($template);
        dd($request->toArray());
    }
}
