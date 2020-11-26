<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class SetLanguage
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
        $systemLanguage  = Auth::user()->system_language != null ? Auth::user()->system_language : 1;

        if($systemLanguage == 1){
            $language = 'en';
        }
        else{
            $language = 'si';
        }

        App::setlocale($language);
        return $next($request);
    }
}
