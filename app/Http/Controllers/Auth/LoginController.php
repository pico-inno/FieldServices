<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use http\Client\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;

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

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        activity('account')->status('success')->update($log->id);

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }
    public function __construct()
    {
        $this->middleware('activateBusinessCheckMiddleware');
        $this->middleware('guest')->except('logout');
    }
}
