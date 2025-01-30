<?php

namespace App\Http\Middleware;

use App\Models\Entidade;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Licenca
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
        // Verifica se o usuário está autenticado
        if (Auth::check()) {
            // Obtém o usuário autenticado com a relação 'empresa'
            $controlo = Entidade::findOrFail(Auth::user()->entidade_id);

            // Verifica se há um Pin ativo para a entidade
            // $pins = Pin::where('status', 'activo')->where('entidade_id', $entidade->empresa->id)->first();

            // Se houver um Pin ativo, redireciona para a rota 'congelamento-pin'

            if($controlo->dias_licencas($controlo->id) <= 0){
                return redirect()->route('licenca-activa');
            }

        }

        // Continua para a próxima request se não houver Pins ativos
        return $next($request);
    }
}