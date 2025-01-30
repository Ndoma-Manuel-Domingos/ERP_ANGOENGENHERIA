<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CheckUserInactivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if (Session::has('last_activity')) {
                $lastActivity = Carbon::parse(Session::get('last_activity'));
                $inactivityLimit = Carbon::now()->subMinutes(100);
    
                if ($lastActivity->lt($inactivityLimit)) {
                    Session::put('screen_locked', true);
                    Session::put('lock_start_time', Carbon::now());
                    return redirect()->route('screen.locked');
                }
            }
    
            Session::put('last_activity', Carbon::now());
        }

        return $next($request);
    }
}
