<?php

use App\Helpers\ActivityLogger;

function activity($logName = 'default')
{

    return new ActivityLogger($logName);
}
