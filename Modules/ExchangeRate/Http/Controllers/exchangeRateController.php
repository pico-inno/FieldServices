<?php

namespace Modules\ExchangeRate\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Modules\ExchangeRate\Entities\exchangeRates;

class exchangeRateController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }
    public function index(){
        $rates=exchangeRates::get();
        return view('exchangerate::App.index',compact('rates'));
    }

    public function list(){
        $rates=exchangeRates::OrderBy('id','desc')
                    ->get();
        return DataTables::of($rates)
            ->addColumn('checkbox',function($rate){
                return
                '
                    <div class="form-check form-check-sm form-check-custom ">
                        <input class="form-check-input" type="checkbox" data-checked="delete" value='.$rate->id.' />
                    </div>
                ';
            })
            ->addColumn('action', function ($rate) {
                // $editBtn= '<a href=" ' . route('exchangeRate_edit', $rate->id) . ' " class="dropdown-item cursor-pointer" >Edit</a>';
                $html = '
                    <div class="dropdown ">
                        <button class="btn m-2 btn-sm btn-light btn-primary fw-semibold fs-7  dropdown-toggle " type="button" id="exchangeRateDropDown" data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                        </button>
                        <div class="z-3">
                        <ul class="dropdown-menu z-10 p-5 " aria-labelledby="exchangeRateDropDown" role="menu">';
                        $html.='<a class="dropdown-item cursor-pointer" id="edit"   data-href="'.route('exchangeRate.edit',$rate->id).'">Edit</a>';
                        $html.='<a class="dropdown-item cursor-pointer" id="delete" data-id="'.$rate->id.'"  data-kt-exchangeRate-table="delete_row" data-href="'.route('exchangeRate.destory',$rate->id).'">Delete</a>';
                        // $html .= $editBtn;
                    $html .= '</ul></div></div>';
                    return $html;
            })
            ->editColumn('currency',function($rate){
                $badge=$rate->default==1 ?'<sapn class="badge badge-sm badge-success ms-3">default</sapn>':'';
                return '<sapn>'.$rate->currency->name.$badge .'</sapn>';
            })
            ->rawColumns(['action','currency','checkbox'])
            ->make(true);
    }

    public function create(){
        return view('exchangerate::App.create');
    }

    public function store(Request $request){
        try {
            // dd($request->toArray());
            $request=$request->only('currency_id','rate','default');
            $isDefault=isset($request['default']) ?? '';
            $request['rate']=$isDefault ? 1 : $request['rate'];

            DB::beginTransaction();
            $this->resetDefaultValue($isDefault);

            // dd($request);
            $exchangeRates=exchangeRates::where('currency_id',$request['currency_id'])->exists();
            if($exchangeRates){
                exchangeRates::where('currency_id',$request['currency_id'])->first()->update($request);
            }else{
                exchangeRates::create($request);
            }

            DB::commit();
            return back()->with([
                'success'=>'successfully create exchange rate'
            ]);
        } catch (\Throwable $th) {
            dd($th);
            DB::rollBack();
            return back()->with([
                'error'=>'Something Went Wrong!'
            ]);
        }
    }

    public function edit($id){
        $currentRate=exchangeRates::where('id',$id)->first();
        return view('exchangerate::App.edit',compact('currentRate'));
    }

    public function update($id,Request $request){
        try {
            $request=$request->only('currency_id','rate','default');
            DB::beginTransaction();
            $this->resetDefaultValue($request['default'] ?? '');

            // dd($request);
            exchangeRates::where('id',$id)->update($request);

            DB::commit();
            return back()->with([
                'success'=>'successfully updated exchange rate'
            ]);
        } catch (\Throwable $th) {
            dd($th);
            DB::rollBack();
            return back()->with([
                'error'=>'Something Went Wrong!'
            ]);
        }
    }

    public function destory(Request $request){
        try {
            $idForDelete=$request->idForDelete;
            foreach($idForDelete as $id){
                exchangeRates::where('id',$id)->first()->delete();
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
    private function resetDefaultValue($default){
        if($default){
            if($default==1){
                exchangeRates::where('default','1')->update(['default'=>'0']);
            }
        }
    }
}
