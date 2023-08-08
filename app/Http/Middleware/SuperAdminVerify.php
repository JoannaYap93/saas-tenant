<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SuperAdminVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->user_type_id != 1 || Auth::user()->company_id != 0) {
            Session::flash('fail_msg', 'Forbidden! Permission is not allowed.');
            return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}
