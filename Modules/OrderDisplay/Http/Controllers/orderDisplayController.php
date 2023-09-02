<?php

namespace Modules\OrderDisplay\Http\Controllers;


use App\Models\posRegisters;
use Illuminate\Http\Request;
use App\Models\Product\Category;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\networkPrinterController;
use App\Models\resOrders;
use Yajra\DataTables\Facades\DataTables;
use App\Models\settings\businessLocation;
use Modules\OrderDisplay\Entities\orderDisplay;

class orderDisplayController extends Controller
{
    //order
    public function odList() {

        return view('orderdisplay::App.list');
        // return view('main.list');
    }
    public function odDataForList(){
        $orderDisplays=orderDisplay::get();
        return DataTables::of($orderDisplays)
        ->addColumn('checkbox',function($orderDisplay){
            return
            '
                <div class="form-check form-check-sm form-check-custom ">
                    <input class="form-check-input" type="checkbox" data-checked="delete" value='.$orderDisplay->id.' />
                </div>
            ';
        })
        ->addColumn('action', function ($orderDisplay) {
            $html = '
                <div class="dropdown ">
                    <button class="btn m-2 btn-sm btn-light btn-primary fw-semibold fs-7  dropdown-toggle " type="button" id="exchangeRateDropDown" data-bs-toggle="dropdown" aria-expanded="false">
                        Actions
                    </button>
                    <div class="z-3">
                    <ul class="dropdown-menu z-10 p-5 " aria-labelledby="exchangeRateDropDown" role="menu">';
                    $html.='<a class="dropdown-item cursor-pointer " href="'.route('orderDisplay',$orderDisplay->id).'">Open Display</a>';
                    $html.='<a class="dropdown-item cursor-pointer openModal" data-href="'.route('odEdit',$orderDisplay->id).'">Edit</a>';
                    $html.='<a class="dropdown-item cursor-pointer" id="delete" data-id="'.$orderDisplay->id.'"  data-kt-exchangeRate-table="delete_row" data-href="'.route('odDestory',$orderDisplay->id).'">Delete</a>';
                    // $html .= $editBtn;
                $html .= '</ul></div></div>';
                return $html;
        })
        ->editColumn('location',function($orderDisplay){
            return $orderDisplay->location->name ?? '';
        })
        ->editColumn('posRegister',function($orderDisplay){
            if($orderDisplay->pos_register_id==0){
                return 'All POS';
            }
            $pos_register_ids=json_decode($orderDisplay->pos_register_id);
            $pos_registers=posRegisters::whereIn('id',$pos_register_ids)->get();
            $posText='';
            foreach ($pos_registers as $key=>$pos_register) {
                $seperator=$key!= 0 ?',':'';
                $posText.=$seperator.$pos_register->name;
            }
            return $posText;
        })
        ->editColumn('productCategory',function($orderDisplay){
            if($orderDisplay->product_category_id==0){
                return 'All Category';
            }
            $product_category_ids=json_decode($orderDisplay->product_category_id);
            $product_categorys=Category::whereIn('id',$product_category_ids)->get();
            $categoryText='';
            foreach ($product_categorys as $key=>$product_category) {
                $seperator=$key!= 0 ?',':'';
                $categoryText.=$seperator.$product_category->name ?? '';
            }
            return $categoryText;
        })

        ->rawColumns(['checkbox','action'])
        ->make('true');
    }
    public function odCreate() {
        $locations=businessLocation::where('is_active',1)->get();
        $categories=Category::get();
        $posRegisters=posRegisters::get();
        return view('orderdisplay::App.create',compact('locations','categories','posRegisters'));
    }
    public function odStore(Request $request){
        try {
            DB::beginTransaction();
            $jsonCategory=$this->requestJsonId($request->category_id);
            $jsonPosRegister=$this->requestJsonId($request->pos_register_id);

            orderDisplay::create([
                'name'=>$request->name,
                'location_id'=>$request->location_id,
                'pos_register_id'=>$jsonPosRegister,
                'product_category_id'=>$jsonCategory,
            ]);
            DB::commit();
            return back()->with(['success'=>'successfully added']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with(['error'=>'Something Wrong!']);
        }
    }
    public function odUpdate($id,Request $request){
        try {
            DB::beginTransaction();
            $jsonCategory=$this->requestJsonId($request->category_id);
            $jsonPosRegister=$this->requestJsonId($request->pos_register_id);
            orderDisplay::where('id',$id)->update([
                'name'=>$request->name,
                'location_id'=>$request->location_id,
                'pos_register_id'=>$jsonPosRegister,
                'product_category_id'=>$jsonCategory,
            ]);
            DB::commit();
            return back()->with(['success'=>'successfully added']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with(['error'=>'Something Wrong!']);
        }
    }
    public function odDisplay($id) {
        if(!$id){
            return back();
        }
        $odDisplaysQuery=orderDisplay::where('id',$id);
        if($odDisplaysQuery->exists()){
            $odDisplayData=$odDisplaysQuery->first();
            return view('orderdisplay::App.orderDisplay',compact('odDisplayData'));
        }else{
            return back();
        }
    }

    public function odEdit($id) {
        $locations=businessLocation::where('is_active',1)->get();
        $categories=Category::get();
        $posRegisters=posRegisters::get();
        $orderDisplay=orderDisplay::where('id',$id)->first();

        // for pos register
        $pos_register_ids=json_decode($orderDisplay->pos_register_id);
        $posRegisterText = '';
       if($pos_register_ids){
            $pos_registers = posRegisters::whereIn('id', $pos_register_ids)->get();
            foreach ($pos_registers as $key => $e) {
                $seperator = $key == 0 ? '' : ',';
                $posRegisterText .= $seperator . $e->name;
            }
       } else {
            $posRegisterText = 'All';
        }

        // for pos register
        $product_category_ids=json_decode($orderDisplay->product_category_id);
        $productCategoryText = '';
        if($product_category_ids){
            $product_categories=Category::whereIn('id',$product_category_ids)->get();
            foreach ($product_categories as $key => $p) {
                $seperator = $key == 0 ? '' : ',';
                $productCategoryText .= $seperator . $p->name;
            }
        }else{
            $productCategoryText='All';
        }


        return view('orderdisplay::App.edit',compact('orderDisplay','locations','categories','posRegisters','posRegisterText','productCategoryText'));
    }
    public function odDestory(Request $request){
        try {
            $idForDelete=$request->idForDelete;
            foreach($idForDelete as $id){
                orderDisplay::where('id',$id)->first()->delete();
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



    // this function is change request json data that with nested data to only id json data
    protected function requestJsonId($requestJson){
        $categories=json_decode($requestJson);

        if($categories){
            $id=array_map(function($c){
                return $c->id;
            },$categories);
            $idJson=json_encode($id);
            return $idJson;
        }
        return false;
    }

    public function odPrint(Request $request){
        $orderInfo=$request->orderInfo;
        $networkPrinter=new networkPrinterController();
        $status=$networkPrinter->printForOD($orderInfo);
        if(isset($status['success'])){
            return response()->json([
                'status' => '200',
                'success' => $status['success']
            ], 200);
        }
        if (isset($status['error'])) {
            return response()->json([
                'status' => '200',
                'error' => $status['error']
            ], 200);
        }
    }
}
