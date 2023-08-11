<?php

namespace App\Http\Middleware;

use Closure;

class SuspendUser
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
        if (auth()->check() && auth()->user()->user_status == 'suspended') {

            auth()->logout();

            return redirect()->route('login', ['tenant' => tenant('id')])->with('suspended', 'Your account has been suspended. Please contact administrator.');
        }
        return $next($request);
    }
}
