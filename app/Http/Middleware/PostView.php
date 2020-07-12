<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PostView
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
        if(Auth::user()->iduser_role == 8 || Auth::user()->iduser_role == 3|| Auth::user()->iduser_role == 4 || Auth::user()->iduser_role == 10){
            return $next($request);
        }
        else{
            return redirect('/');
        }
    }
}
