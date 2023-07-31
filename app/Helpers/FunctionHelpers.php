<?php

use Nwidart\Modules\Facades\Module;





function hasModule($moduleName){
    $moduleName=ucfirst($moduleName);
    return  Module::has($moduleName);
}



function isEnableModule($moduleName){
    $moduleName=ucfirst($moduleName);
    $status = Module::find($moduleName)->isEnabled();
    return  $status;
}


function getModuleVer($moduleName,$ver='version'){
    $moduleName=strtolower($moduleName);
    return config($moduleName.'.'.$ver);
}
