<?php

namespace App\Services;

use Twilio\Rest\Client;
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

    public function sendWithTwillio($data){
        // Your Account SID and Auth Token from console.twilio.com
        $sid =env('TWILIO_SID');
        $token =env('TWILIO_AUTH_TOKEN');
        $client = new Client($sid, $token);

        // Use the Client to make requests to the Twilio REST API
        return $client->messages->create(
            // The number you'd like to send the message to
            '+'.$data['to'],
            [
                // A Twilio phone number you purchased at https://console.twilio.com
                'from' => '+18143284752',
                // The body of the text message you'd like to send
                'body' =>$data['message'],
            ]
        );
    }
}
