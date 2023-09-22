<?php

namespace App\Components;

class Input
{
    /**
     * ZeroBorder
     *
     * @param  mixed $name
     * @param  mixed $id
     * @param  mixed $class
     * @param  mixed $value
     * @return void
     */
    public static function ZeroBorder($name='',$id='',$class='',$value){
        return'
         <input type="text"
         class="form-control form-control-sm border-left-0 border-top-0 border-right-0 rounded-0 '.$class.' "
         id="'.$id.'"
         name="'.$name.'"
         value="'.old($name,$value).'" />
        ';
    }
}
