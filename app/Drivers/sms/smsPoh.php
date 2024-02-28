<?php

namespace App\Drivers\sms;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;


class smsPoh extends Driver
{
    private $AuthToken;
    private $sender;
    public $endPoint='https://smspoh.com/api/v2/send';
    public function configuration()
    {
        $this->AuthToken = env('SMSPOH_AUTH_TOKEN');
        $this->sender = env('SMSPOH_SENDER');
    }

    /**
     * send
     *
     * @param  mixed $data = [
     *  'to' => receiver's phone number,
     *  'message' => text to send as message,
     * ]
     * @return Response
     */
    public function send(Array $data):Response
    {
        $data['sender']=$this->sender;
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->AuthToken,
            'Content-Type' => 'application/json',
        ])->post($this->endPoint, $data);

    }
}
