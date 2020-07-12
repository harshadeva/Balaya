<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class upToManagement
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
        $mediaStaff = [
            'addUser',

        ];

        if (Auth::user()->iduser_role <= 4) {
            return $next($request);
        }
        else{
            return redirect('/');
        }
    }
}
