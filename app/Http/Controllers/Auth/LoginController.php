<?php

namespace App\Http\Controllers\Auth;

use App\Model\User;
use App\Model\Landlord\CentralUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cookie;
use App\Providers\RouteServiceProvider;
use Spatie\Activitylog\Models\Activity;
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
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'user_email';
    }

    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $user = User::where('user_email', $request->user_email)->first();
            $ip = $request->ip();
            
            if ($user->can('access_backend') == false){
              $this->guard()->logout();

              $request->session()->invalidate();

              $request->session()->regenerateToken();

              return $this->loggedOut($request) ?: redirect()->route('main.index', ['tenant' => tenant('id')]);
            }
            // activity('login')
            //     ->withProperties(['user_ip' => $ip])
            //     ->log('login');
            // if (Auth::viaRemember()) {
            //     $value = Cookie::get('user_email');
            // }
            // return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function logout(Request $request)
    {
        $user = User::where('user_email', $request->user_email)->first();
        $ip = $request->ip();
        // activity('logout')
        //     ->withProperties(['user_ip' => $ip])
        //     ->log('logout');

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect()->route('main.index', ['tenant' => tenant('id')]);
    }

    protected function attemptLogin(Request $request)
    {
        $remember_me = false;
        if (isset($request->remember_me)) {
            $remember_me = true;
        }

        return $this->guard()->attempt(
            $this->credentials($request),
            $remember_me
        );
    }
    
    public function resetPassword(Request $request) 
    {
        return view('auth.passwords.email');
    }

    public function landlordByPassLogin(Request $request, $landlord_id)
    {
        $landlord_id = $landlord_id;
        $valid = tenancy()->central(function ($tenant) use ($landlord_id) {
            $user = CentralUser::find($landlord_id);

            if ($user) {
                if ($user->user_status == 'active') {
                    return true;
                }

                return false;
            } else {
                return false;
            }
        });

        if ($valid) {
            $user = User::find(1);
            Auth::login($user);
            return redirect()->route('main.index', ['tenant' => tenant('id')]);
        }

        return view('pages-404');
    }
}
