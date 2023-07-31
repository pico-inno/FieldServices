<?php

namespace App\Http\Controllers;

use App\Models\paymentAccounts;
use Illuminate\Http\Request;

class currencyController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }
    public function index(){
        return view('App.currency.index');
    }
    public function paymentAccountByCurrency($id){
        $accounts=paymentAccounts::where('currency_id',$id)->with('currency')->get();
        return response()->json($accounts, 200);
    }
}
