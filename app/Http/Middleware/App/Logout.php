<?php

namespace App\Http\Middleware\App;

use Closure;
use Illuminate\Http\Request;

class Logout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!request()->session()->has('users') || !request()->session()->get('users')['isLogged']) {
            return redirect()->route('login.start');
        }
        return $next($request);
    }
}
