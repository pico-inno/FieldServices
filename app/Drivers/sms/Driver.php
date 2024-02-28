<?php

namespace App\Drivers\sms;

abstract class Driver
{
    abstract function configuration();
    abstract function send(Array $data);
}
