<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class smsServices
{
    private $AuthToken;
    private $sender;
    public function __construct()
    {
        $this->AuthToken = env('SMSPOH_AUTH_TOKEN');
        $this->sender = env('SMSPOH_SENDER');
    }
    /**
     * send sms using sms pos
     *
     * @param  mixed $data
     * @return void
     */
    public function sendWithSMSPoh(Array $data){
        $data['sender']=$this->sender;
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->AuthToken,
            'Content-Type' => 'application/json',
        ])->post('https://smspoh.com/api/v2/send', $data);
    }

    public function getSMSWithSmsPoh()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->AuthToken,
        ])->get('https://smspoh.com/api/v1/messages');

        return $response->json();
    }
}
