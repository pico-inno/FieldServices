<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\smsServices;
use App\Models\Contact\Contact;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;

class SMSController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }
    public function index(smsServices $sms){
        $smsDatas=$sms->getSMSWithSmsPoh()['data'];
        return view('App.SMS.smsPoh.index',compact('smsDatas'));
    }
    public function create(){
        $contact=Contact::where('type', 'Customer')->orWhere('type', 'Both')->get();
        return view('App.SMS.smsPoh.create',compact('contact'));
    }
    public function send(Request $request,smsServices $sms)
    {

        try {
            if (!trim($request->message)) {
                throw new Exception("Message is Required", 1);
            }
            $receivers=$this->requestJsonId($request->sent_to);
            foreach ($receivers as $receiver) {
                if($receiver['mobile']){
                    $data = [
                        'to'=>$receiver['mobile'],
                        'message' => $request->message,
                    ];
                    $response = $sms->sendWithSMSPoh($data);
                }
            }

            if ($response->successful()) {
                return back()->with(['success' => 'Successfully Send Message']);
            } else {
                return back()->with(['error' => $response->status()]);
            }
        } catch (\Throwable $th) {
            return back()->with(['error' => $th->getMessage()]);
        }
    }
    public function getSMS(smsServices $sms){
        // dd('ejer');
        // echo "<pre>";
        // return $sms->getSMSWithSmsPoh();
    }
    protected function requestJsonId($requestJson)
    {
        $datas = json_decode($requestJson);
        if ($datas) {
            $data = array_map(function ($c) {
                return ['mobile'=>$c->mobile];
            }, $datas);
            return $data;
        }
        return [];
    }
}
