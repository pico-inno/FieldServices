<?php

namespace App\Services;

use Exception;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

class mailServices
{
    public function sendEmail($mailData)
    {
        try {
            Mail::to($mailData['to'])->send(new SendMail($mailData));
        } catch (\Throwable $th) {
           return throw new Exception($th->getMessage());
        }
    }
}
// write sms send logic
