<?php

namespace App\Actions\mail;

use Exception;
use App\Services\mailServices;

class mailsActions
{

    public function send($data,$mailServices){
        try {
            $mails = requestJsonId($data->compose_to, 'email', 'email');
            foreach ($mails as $mail) {
                $data = [
                    'to' => $mail['email'],
                    'subject' => $data->compose_subject,
                    'body' => $data->body
                ];
                $mailServices->sendEmail($data);
            }
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage());
        }
    }
}
