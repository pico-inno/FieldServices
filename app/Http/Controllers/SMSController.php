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
        try {
            $data = [
                'to' => $request->sent_to,
                'message' => $request->message,
                'sender' => $this->sender,
            ];
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->AuthToken,
                'Content-Type' => 'application/json',
            ])->post('https://smspoh.com/api/v2/send', $data);

            // return;
            if ($response->successful()) {
                return back()->with(['success' => 'Successfully Send Message']);
            } else {
                return back()->with(['error' => $response->status()]);
            }
        } catch (\Throwable $th) {
            return back()->with(['error' => $th->getMessage()]);
        }
    }
}
