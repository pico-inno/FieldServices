<?php

namespace App\Services\sms;

use App\Drivers\sms\smsPoh;

class smsServicesV2 extends sms
{
    public function smsDriver(){
        return new smsPoh();
    }
}
