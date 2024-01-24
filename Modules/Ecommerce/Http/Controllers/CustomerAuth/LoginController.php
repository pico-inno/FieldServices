<?php

namespace Modules\Ecommerce\Http\Controllers\CustomerAuth;
use App\Http\Controllers\Controller;
use App\Models\Contact\Contact;
use App\Providers\RouteServiceProvider;
use App\Services\smsServices;
use http\Client\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Modules\Ecommerce\Entities\CustomerUser;
use Sabberworm\CSS\Value\URL;

class LoginController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::ECOMMERCE_HOME;


    public function __construct()
    {
        $this->middleware('activateBusinessCheckMiddleware');
        $this->middleware('guest')->except('logout');

    }

    public function verification()
    {
            return view('ecommerce::auth.verification');
    }
    public function requestCode(\Illuminate\Http\Request $request, $type, smsServices $sms)
    {

        if ($type === 'login'){
            $request->validate([
                'phone' => 'required',
            ]);

            $customerUser = CustomerUser::where('phone', $request->phone)->get()->first();

            if ($customerUser){

                $verificationCode = rand(1000, 9999);

                $customerUser->update([
                    'phone_verification_code' => $verificationCode,
                ]);

//                $message_template = "[PICO] Your SMS Code is $verificationCode. DO NOT! share it to anyone, in order to prevent fraud. This SMS Code is for your PICO Ecommerce Login only.";
//
//                $data = [
//                    'to' => $request->phone,
//                    'message' => $message_template,
//                ];
//
//                $response = $sms->sendWithSMSPoh($data);

                return view('ecommerce::auth.verification')->with([
                    'phone_number' => $request->phone,
                ]);

            }else{
                return redirect(route('ecommerce.login'))->withErrors(['warning' => "This number not registered!"]);
            }
        }elseif ($type === 'register'){
            $request->validate([
                'phone' => 'required',
                'first_name' => 'required',
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

                $message_template = "[PICO] Your SMS Code is $verificationCode. DO NOT! share it to anyone, in order to prevent fraud. This SMS Code is for your PICO Ecommerce Login only.";

                $data = [
                    'to' => $request->phone,
                    'message' => $message_template,
                ];

                $response = $sms->sendWithSMSPoh($data);


                return view('ecommerce::auth.verification')->with([
                    'phone_number' => $request->phone,
                    'success' => 'Registration successful! Please check your phone for the verification code.'
                ]);

            }else{
                return view('ecommerce::auth.verification')->with([
                    'phone_number' => $request->phone,
                ]);
            }
        }



    }



    public function showLoginForm()
    {
        return view('ecommerce::auth.login');
    }


    protected function validateLogin(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'phone_verification_code' => 'required',
        ]);
    }

    public function login(\Illuminate\Http\Request $request)
    {

        $verificationCode = $request->code_1 . $request->code_2 . $request->code_3 . $request->code_4;

        $customerUser = CustomerUser::where('phone', $request->phone)->first();

        if (!$customerUser) {
            return redirect(route('ecommerce.login'))->with(['warning' => "This number is not registered!"]);
        }

        if ($customerUser->phone_verification_code === $verificationCode) {

            Auth::guard('customer')->login($customerUser);

            request()->session()->regenerate();
            $customerUser->update([
                'phone_verification_code' => null,
                'phone_verified_at' => now(),
            ]);

            return redirect(route('ecommerce.home'))->with(['success' => "Success"]);
        }

        return back()->withErrors(['warning' => "Invalid verification code!"]);

    }



    public function logout(\Illuminate\Http\Request $request)
    {
        $user = Auth::guard('customer')->user();

        if ($user) {
            $user->update(['phone_verified_at' => null]);
            Auth::guard('customer')->logout();


            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return $request->wantsJson()
                ? new JsonResponse([], 204)
                : redirect('/');
        }
        return redirect(route('ecommerce.login'))->with(['warning' => "You are not logged in!"]);
    }

}
