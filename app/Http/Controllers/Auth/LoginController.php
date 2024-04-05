<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use http\Client\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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

    public function login(\Illuminate\Http\Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptAdminLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        activity('account')
            ->event('login')
            ->status('fail')
            ->log('Someone failed to login')->save();




        return $this->sendFailedLoginResponse($request);
    }

    protected function attemptAdminLogin(\Illuminate\Http\Request $request)
    {
        return Auth::guard('web')->attempt(
            $this->credentials($request),
            $request->boolean('remember')
        );
    }


    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {

        $log = activity('account')
            ->event('login')
            ->log('This account has been login')->save();

        if (!$user->is_active) {

            activity('account')
                ->log('This account access has been fail')
                ->status('fail')->update($log->id);

            auth()->logout();
            return back()->withErrors([
                'account_inactive' => 'Your account has been deactivated.',
            ]);
        }

        activity('account')->status('success')->update($log->id);

        return redirect()->intended($this->redirectTo);
    }

    public function logout(\Illuminate\Http\Request $request)
    {
        $log = activity('account')
            ->event('logout')
            ->log('This account has been logout')->save();

//        $this->guard()->logout();
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        activity('account')->status('success')->update($log->id);

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/admin');
    }
    public function __construct()
    {
        $this->middleware('activateBusinessCheckMiddleware');
        $this->middleware('guest')->except('logout');
    }
}
