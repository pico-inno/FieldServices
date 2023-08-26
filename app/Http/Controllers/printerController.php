<?php

namespace App\Http\Controllers;

use App\Models\printers;
use Illuminate\Http\Request;
use App\Models\Product\Category;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\settings\businessLocation;

class printerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }
    public function index() {
        return view('App.restaurants.printers.list');
    }
    public function create() {
        $categories=Category::get();
        return view('App.restaurants.printers.create',compact('categories'));
    }
    public function DataForList(){
        $printers=printers::get();
        return DataTables::of($printers)
        ->addColumn('checkbox',function($printer){
            return
            '
                <div class="form-check form-check-sm form-check-custom ">
                    <input class="form-check-input" type="checkbox" data-checked="delete" value='.$printer->id.' />
                </div>
            ';
        })
        ->addColumn('action', function ($printer) {
            $html = '
                <div class="dropdown ">
                    <button class="btn m-2 btn-sm btn-light btn-primary fw-semibold fs-7  dropdown-toggle " type="button" id="exchangeRateDropDown" data-bs-toggle="dropdown" aria-expanded="false">
                        Actions
                    </button>
                    <div class="z-3">
                    <ul class="dropdown-menu z-10 p-5 " aria-labelledby="exchangeRateDropDown" role="menu">';
                    $html.='<a class="dropdown-item cursor-pointer openModal" data-href="'.route('printerEdit',$printer->id).'">Edit</a>';
                    $html.='<a class="dropdown-item cursor-pointer" id="delete" data-id="'.$printer->id.'"  data-kt-exchangeRate-table="delete_row" data-href="'.route('printerStore',$printer->id).'">Delete</a>';
                    // $html .= $editBtn;
                $html .= '</ul></div></div>';
                return $html;
        })
        ->editColumn('productCategory',function($printer){
            return  $printer->category->name;
        })
        ->rawColumns(['checkbox','action'])
        ->make('true');
    }
    public function store(Request $request){
        try {
            DB::beginTransaction();
            $data=$request->only(
                'name',
                'printer_type',
                'ip_address',
                'product_category_id'
            );
            printers::create($data);
            DB::commit();
            return back()->with(['success'=>'Successfully Created']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with(['warning'=>'Something Went Wrong']);
        }
    }

    public function edit($id) {
        $categories=Category::get();
        $printer=printers::where('id',$id)->first();
        return view('App.restaurants.printers.edit',compact('categories','printer'));
    }
    public function update($id,Request $request){
        try {
            DB::beginTransaction();
            $data=$request->only(
                'name',
                'printer_type',
                'ip_address',
                'product_category_id'
            );
            printers::where('id',$id)->update($data);
            DB::commit();
            return back()->with(['success'=>'Successfully Created']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with(['warning'=>'Something Went Wrong']);
        }
    }
    public function printerDestory(Request $request){
        try {
            $idForDelete=$request->idForDelete;
            foreach($idForDelete as $id){
                printers::where('id',$id)->first()->delete();
            }
            return response()->json([
                'status'=>'200',
                'success'=>'successfully deleted'
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error'=>'500',
                'message'=>'Something Went wrong'
            ], 200);
        }
    }
}
