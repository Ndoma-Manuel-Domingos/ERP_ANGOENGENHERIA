<?php

namespace App\Http\Middleware;

use App\Models\Caixa;
use Closure;
use Illuminate\Http\Request;

class VerificarCaixaAberto
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
        // Verificar se o usuário tem um caixa aberto
        $caixaAberto = Caixa::where('user_id', auth()->id())
            ->where('active', true)
            ->where('continuar_apos_login', false)
            ->where('status', 'aberto')
            ->first();
            
        // Adicionar a informação à sessão
        if ($caixaAberto) {
            session(['caixaAberto' => $caixaAberto]);
        } else {
            session()->forget('caixaAberto');
        }
        
        return $next($request);
    }
}
