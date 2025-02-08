<?php

namespace App\Http\Middleware;

use App\Models\Entidade;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class FirstLoginSystem
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
        $user = User::with('empresa')->find(auth()->id());
        
        session()->forget('FirstLoginSystem');
    
        if($user){
            $entidade = Entidade::findOrFail($user->empresa->id);
            if($entidade->first_login_system == false){
                session(['FirstLoginSystem' => $entidade]);
            }else {
                session()->forget('FirstLoginSystem');
            }
        }
        
        return $next($request);
    }
}
