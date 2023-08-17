<?php

namespace App\Http\Controllers\posSession;

use App\Models\posRegisters;
use Illuminate\Http\Request;
use App\Models\paymentAccounts;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\posRegisterTransactions;
use App\Models\posSession\posRegisterSessions;
use App\Http\Controllers\paymentsTransactionsController;

class posSessionController extends Controller
{


    protected $currencyId='';
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
        $this->currencyId=getSettingValue('currency_id');
    }
    public function sessionCheck($posRegisteredId){
       $posSessionQry=posRegisterSessions::where('pos_register_id',$posRegisteredId)->where('status','open');
        $statusCheck=$posSessionQry->exists();
        if($statusCheck == false){
            $paymentAccounts=paymentAccounts::where('currency_id',$this->currencyId)->get();
            $paymentAccountForRegister=$this->getPaymnetAccountForPosRegister($posRegisteredId);
            return view('App.posSession.posSessionCreate',compact('posRegisteredId','paymentAccounts','paymentAccountForRegister'));
        }else{
            $pos=posRegisters::where('id',$posRegisteredId)->first();
            // if($pos->use_for_res==1 && hasModule('Restaurant') &&  isEnableModule('Restaurant')){
            //     return redirect()->route('table.dashboard',['pos_register_id'=>encrypt($posRegisteredId)]);
            // }
            $posSession=$posSessionQry->first();
            return redirect()->route('pos.create',['pos_register_id'=>encrypt($posRegisteredId),'sessionId'=>$posSession->id]);
        }
    }

    public function sessionCreate($posRegisteredId){
        $posSessionQry=posRegisterSessions::where('pos_register_id',$posRegisteredId)->where('status','open');
        $statusCheck=$posSessionQry->exists();
        if($statusCheck == false){
            $paymentAccounts=paymentAccounts::where('currency_id',$this->currencyId)->get();
            $paymentAccountForRegister=$this->getPaymnetAccountForPosRegister($posRegisteredId);
            return view('App.posSession.posSessionCreate',compact('posRegisteredId','paymentAccounts','paymentAccountForRegister'));
        }else{
            $pos=posRegisters::where('id',$posRegisteredId)->first();
            $posSession=$posSessionQry->first();
            return redirect()->route('pos.create',['pos_register_id'=>encrypt($posRegisteredId),'sessionId'=>$posSession->id]);
        }
    }

    public function sessionStore($posRegisteredId,Request $request){
        $statusCheck=posRegisterSessions::where('pos_register_id',$posRegisteredId)->where('status','open')->exists();
        try {
            DB::beginTransaction();
            if($statusCheck==false){
                if(getSettingsValue('use_paymentAccount')){
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
                    $paymentTransaction=$paymentsTransactionController->transfer($request->tx_account,$request->rx_account,$request->opening_amount,$rx_amount,'opening_amount');
                }

               $posSession= posRegisterSessions::create([
                    'pos_register_id'=>$posRegisteredId,
                    'opening_amount'=>$request->opening_amount,
                    'opening_at'=>now(),
                    'status'=>'open',
                ]);
                posRegisterTransactions::create([
                    'register_session_id'=>$posSession->id,
                    'payment_account_id'=>$request->rx_account,
                    'transaction_type'=>'opening_amount',
                    'transaction_amount'=>$request->opening_amount,
                    'currency_id'=>$rx_acc->currency_id ?? null,
                    'payment_transaction_id'=>$paymentTransaction->id ?? null,
                    'transaction_id'=>$paymentTransaction->id ?? null,

                ]);
                posRegisters::where('id',$posRegisteredId)->update([
                    'status'=>'open'
                ]);
            }
            DB::commit();
            return redirect()->route('pos.create',['pos_register_id'=>encrypt($posRegisteredId),'sessionId'=>$posSession->id]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with(['warning'=>'Something Went Wrong!']);

        }

    }

    public function sessionDestory($id,Request $request){
        $posRegisterId=$request->posRegisterId;
        $closeAmount=$request->closeAmount;
        $posSessionQry=posRegisterSessions::where('id',$id)->where('pos_register_id',$posRegisterId)->where('status','open');
        $statusCheck=$posSessionQry->exists();
        try {
            DB::beginTransaction();
            if($statusCheck==true){
                $posSessionQry->first()->update([
                    'status'=>'close',
                    'closing_at'=>now(),
                    'closing_amount'=>$closeAmount,
                    'closing_note'=>$request->closing_note,
                ]);
                $posRegister=posRegisters::where('id',$posRegisterId)->firstOrFail()->update([
                    'status'=>'close'
                ]);
            }
            DB::commit();
            return redirect()->route('pos.selectPos');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with(['warning'=>'Something Went Wrong!']);

        }

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
