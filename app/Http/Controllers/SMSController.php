<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SMSController extends Controller
{
    private $AuthToken;
    private $sender;
    public function __construct()
    {
        $this->AuthToken= env('SMSPOH_AUTH_TOKEN');
        $this->sender=env('SMSPOH_SENDER');
    }
    public function create(){
        return view('App.SMS.create');
    }
    public function send(Request $request)
    {
        $this->sendMessage($request);
        return;
    }
    public function sendMessage($request){
        $data = [
            'to' => $request->sent_to,
            'message' => $request->message,
            'sender' => $this->sender,
        ];
        Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->AuthToken,
            'Content-Type' => 'application/json',
        ])->post('https://smspoh.com/api/v2/send', $data);

        // if ($response->successful()) {
        //     dd('Successfully sended');
        //     $responseData = $response->json();
        // } else {
        //     dd($response);
        //     $errorCode = $response->status();
        //     $errorMessage = $response->body();
        // }
    }

}
