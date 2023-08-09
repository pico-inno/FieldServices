<?php

namespace App\Http\Controllers\posSession;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\paymentsTransactionsController;
use App\Models\paymentAccounts;
use App\Models\posRegisters;
use App\Models\posSession\posRegisterSessions;

class posSessionController extends Controller
{


    protected $currencyId='';
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
        $this->currencyId=getSettingValue('currency_id');
    }
    public function sessionCheck($posRegisteredId){
        $statusCheck=posRegisterSessions::where('pos_register_id',$posRegisteredId)->where('status','open')->exists();
        if($statusCheck == false){
            $paymentAccounts=paymentAccounts::where('currency_id',$this->currencyId)->get();
            $paymentAccountForRegister=$this->getPaymnetAccountForPosRegister($posRegisteredId);
            return view('App.posSession.posSessionCreate',compact('posRegisteredId','paymentAccounts','paymentAccountForRegister'));
        }else{
            $pos=posRegisters::where('id',$posRegisteredId)->first();
            // if($pos->use_for_res==1 && hasModule('Restaurant') &&  isEnableModule('Restaurant')){
            //     return redirect()->route('table.dashboard',['pos_register_id'=>encrypt($posRegisteredId)]);
            // }
            return redirect()->route('pos.create',['pos_register_id'=>encrypt($posRegisteredId)]);
        }
    }

    public function sessionCreate($posRegisteredId){
        $statusCheck=posRegisterSessions::where('pos_register_id',$posRegisteredId)->where('status','open')->exists();
        if($statusCheck == false){
            $paymentAccounts=paymentAccounts::where('currency_id',$this->currencyId)->get();
            $paymentAccountForRegister=$this->getPaymnetAccountForPosRegister($posRegisteredId);
            return view('App.posSession.posSessionCreate',compact('posRegisteredId','paymentAccounts','paymentAccountForRegister'));
        }else{
            $pos=posRegisters::where('id',$posRegisteredId)->first();
            // if($pos->use_for_res==1 && hasModule('Restaurant') &&  isEnableModule('Restaurant')){
            //     return redirect()->route('table.dashboard',['pos_register_id'=>encrypt($posRegisteredId)]);
            // }
            return redirect()->route('pos.create',['pos_register_id'=>encrypt($posRegisteredId)]);
        }
    }

    public function sessionStore($posRegisteredId,Request $request){
        $statusCheck=posRegisterSessions::where('pos_register_id',$posRegisteredId)->where('status','open')->exists();
        if($statusCheck==false){
         // transfer_account
        $tx=paymentAccounts::where('id',$request->tx_account);
        $tx_acc=$tx->first();
        $tx_acc_currency_id=$tx_acc->currency_id;

        // receive_account
        $rx=paymentAccounts::where('id',$request->rx_account);
        $rx_acc=$rx->first();
        $rx_acc_currency_id=$rx_acc->currency_id;
        $rx_amount=$request->opening_amount;

        // make transfer
        $paymentsTransactionController=new paymentsTransactionsController();
        $paymentsTransactionController->transfer($request->tx_account,$request->rx_account,$request->opening_amount,$rx_amount,'opening_amount');
            posRegisterSessions::create([
                'pos_register_id'=>$posRegisteredId,
                'opening_amount'=>$request->opening_amount,
                'opening_at'=>now(),
                'status'=>'open',
            ]);
            posRegisters::where('id',$posRegisteredId)->update([
                'status'=>'open'
            ]);
        }
        return redirect()->route('pos.create',['pos_register_id'=>encrypt($posRegisteredId)]);

    }


    private function getPaymnetAccountForPosRegister($posRegisteredId){
        try {
            $posRegister=posRegisters::where('id',$posRegisteredId)->first();
            $paymentAccountJson=$posRegister->payment_account_id;
            $paymentAccountJsonDecode=json_decode($paymentAccountJson);
            $paymentAccountForRegister=paymentAccounts::whereIn('id',$paymentAccountJsonDecode)->where('currency_id',$this->currencyId)->get();
            return $paymentAccountForRegister;
        } catch (\Throwable $th) {
            return false;
        }
    }











    public function selectPos(){
        $posRegisters=posRegisters::get();
        return view('App.posSession.selectPosMechain',compact('posRegisters'));
    }
}
