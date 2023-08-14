<?php

namespace Modules\Restaurant\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product\Category;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Restaurant\Entities\table;
use Yajra\DataTables\Facades\DataTables;
use App\Models\settings\businessLocation;

class tableController extends Controller
{
    public function index() {
        return view('App.restaurants.table.index');
    }
    public function dataForList() {
        $tables=table::get();
        return DataTables::of($tables)
        ->addColumn('checkbox',function($table){
            return
            '
                <div class="form-check form-check-sm form-check-custom ">
                    <input class="form-check-input" type="checkbox" data-checked="delete" value='.$table->id.' />
                </div>
            ';
        })
        ->addColumn('action', function ($table) {
            $html = '
                <div class="dropdown ">
                    <button class="btn m-2 btn-sm btn-light btn-primary fw-semibold fs-7  dropdown-toggle " type="button" id="exchangeRateDropDown" data-bs-toggle="dropdown" aria-expanded="false">
                        Actions
                    </button>
                    <div class="z-3">
                    <ul class="dropdown-menu z-10 p-5 " aria-labelledby="exchangeRateDropDown" role="menu">';
                    $html.='<a class="dropdown-item cursor-pointer openModal" data-href="'.route('restaurant.tableEdit',$table->id).'">Edit</a>';
                    $html.='<a class="dropdown-item cursor-pointer" id="delete" data-id="'.$table->id.'"  data-kt-exchangeRate-table="delete_row" data-href="'.route('restaurant.destory',$table->id).'">Delete</a>';
                    // $html .= $editBtn;
                $html .= '</ul></div></div>';
                return $html;
        })
        ->rawColumns(['checkbox','action'])
        ->make('true');
    }
    public function create() {
        return view('App.restaurants.table.create');
    }
    public function edit($id) {
        $table=table::where('id',$id)->first();
        return view('App.restaurants.table.edit',compact('table'));
    }

    public function store(Request $request) {
        try {
            DB::beginTransaction();
            $data=$request->only(
                'table_no',
                'seats',
                'description'
                );
                table::create($data);
                DB::commit();
                return redirect()->route('restaurant.tableList')->with(['success'=>'successfully Added Table']);
        } catch (\Throwable $th) {
            return back()->with(['error'=>'Something Wrong While creating']);
        }
    }

    public function update($id,Request $request) {
        try {
            DB::beginTransaction();
            $data=$request->only(
                'table_no',
                'seats',
                'description'
                );
                table::where('id',$id)->update($data);
                DB::commit();
                return redirect()->route('restaurant.tableList')->with(['success'=>'successfully Table Updated ']);
        } catch (\Throwable $th) {
            return back()->with(['error'=>'Something Wrong While creating']);
        }
    }
    public function destory(Request $request){
        try {
            $idForDelete=$request->idForDelete;
            foreach($idForDelete as $id){
                table::where('id',$id)->first()->delete();
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
