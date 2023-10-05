<?php

namespace App\Http\Controllers;

use App\Actions\mail\mailsActions;
use Illuminate\Http\Request;
use App\Models\Contact\Contact;
use App\Services\mailServices;

class mailController extends Controller
{
    public function commpose(){
        $contact = Contact::where('type', 'Customer')->whereNotNull('email')->orWhere('type', 'Both')->get();
        return view('App.mail.compose',compact('contact'));
    }
    public function send(Request $request,mailsActions $mails,mailServices $mailServices){
        try {
            
            //send mail using mail services
            $mails->send($request,$mailServices);

            return back()->with(['success' => 'Successfully Send Mail']);
        } catch (\Throwable $th) {
            return back()->with(['error' => $th->getMessage()]);
        }

    }
}
