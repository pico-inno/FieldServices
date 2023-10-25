<?php

namespace App\Http\Controllers;

use App\Models\PersonalInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class languageController extends Controller
{
    public function change($lang_code){
        try {
            PersonalInfo::where('id', Auth::user()->id)
            ->update([
                'language' => $lang_code
            ]);
            return back();
        } catch (\Throwable $th) {
           return back()->with(['warning'=>'Something Wrong']);
        }
    }
}
