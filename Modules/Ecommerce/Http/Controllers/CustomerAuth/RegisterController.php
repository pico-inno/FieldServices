<?php

namespace Modules\Ecommerce\Http\Controllers\CustomerAuth;
use App\Http\Controllers\Controller;
use App\Models\Contact\Contact;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\Ecommerce\Entities\CustomerUser;

class RegisterController extends Controller
{

//
//    use RegistersUsers;


    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegisterForm()
    {
        return view('ecommerce::auth.register');
    }

    protected function create(Request $request)
    {

        $request->validate([
            'first_name' => 'required',
            'phone' => 'required|unique:customer_users',
        ]);

        $user = CustomerUser::where('phone', $request->phone)->first();
        $verificationCode = rand(1000, 9999);

        if (!$user){
            $contact = Contact::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'type' => 'Customer',
                'business_id' => 1
            ]);

            $user = CustomerUser::create([
                'contact_id' => $contact->id,
                'phone' => $request->phone,
                'phone_verification_code' => $verificationCode,
            ]);

        }

        $user->update([
            'phone_verification_code' => $verificationCode
        ]);

        return redirect(route('ecommerce.request.code'))->with([
            'phone_number' => $user->phone,
            'success' => 'Registration successful! Please check your phone for the verification code.'
        ]);


//        return view('ecommerce::auth.verification')

    }
}
