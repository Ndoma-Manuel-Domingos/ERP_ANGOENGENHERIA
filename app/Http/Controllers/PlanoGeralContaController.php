<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanoGeralContaController extends Controller
{
    //
    public function index(Request $request)
    {
        $user = auth()->user();
    
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $classes = Classe::with(['contas.subcontas'])->get();
 
        $head = [
            "titulo" => "Plano Geral de Contas",
            "descricao" => "Contabilidade",
            "plano" => $classes,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
    
        return view('dashboard.plano-geral-contas.index', $head);
    }
}
