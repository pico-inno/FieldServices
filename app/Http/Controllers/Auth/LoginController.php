<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use http\Client\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function username()
    {
        return 'username';
    }

    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {
        if (!$user->is_active) {
            auth()->logout();
            return back()->withErrors([
                'account_inactive' => 'Your account has been deactivated.',
            ]);
        }
        return redirect()->intended($this->redirectTo);
    }
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
