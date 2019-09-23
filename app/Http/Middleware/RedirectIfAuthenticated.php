<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {


//            if(Auth::user()->hasRole('admin')){
//                return redirect(route('admin.dashboard'));
//            }

                if(!in_array('guest_candidate',$request->route()->middleware())){
                    $user = auth()->user();
                    if($user->hasRole('admin')){
                           return redirect(route('admin.dashboard'));
                    }
                    elseif($user->hasRole('employee')){
                         return redirect(route('member.dashboard'));
                    }
                }

        }

        return $next($request);
    }
}
