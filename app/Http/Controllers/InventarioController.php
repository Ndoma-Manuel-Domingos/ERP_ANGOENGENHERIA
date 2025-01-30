<?php

namespace App\Http\Controllers;

use App\Models\Entidade;
use App\Models\EquipamentoActivo;
use App\Models\Loja;
use App\Models\Produto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;
use RealRashid\SweetAlert\Facades\Alert;

class InventarioController extends Controller
{
    //
    use TraitHelpers;
    
    public function index(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(['variacoes', 'categorias', 'marcas'])->findOrFail($entidade->empresa->id);
       
        
        $head = [
            "titulo" => "Inventário",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
        
        return view('dashboard.inventarios.index', $head);
    }
    
    
    public function inicial(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(['variacoes', 'categorias', 'marcas'])->findOrFail($entidade->empresa->id);
       
        
        $head = [
            "titulo" => "Inventário",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
        
        return view('dashboard.inventarios.inicial', $head);
    }
    
    public function equipamentos(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(['variacoes', 'categorias', 'marcas'])->findOrFail($entidade->empresa->id);
       
        $equipamentos_activos = EquipamentoActivo::with(['user', 'classificacao', 'fornecedor', 'conta', 'entidade'])
        ->where( 'entidade_id', '=', $entidade->empresa->id )
        ->get();
        
        $head = [
            "titulo" => "Equipamentos / Activos",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "equipamentos_activos" => $equipamentos_activos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
        
        return view('dashboard.inventarios.equipamentos', $head);
    }
    
    public function existencias(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(['variacoes', 'categorias', 'marcas'])->findOrFail($entidade->empresa->id);
        
        $produtos = Produto::with(['categoria', 'marca', 'taxa_imposto'])
        ->where('entidade_id', $entidade->empresa->id)
        ->where('tipo', 'P')
        ->orderBy('nome', 'asc')
        ->get();
        
        $lojas = Loja::where([
            ['entidade_id', $entidade->empresa->id],
        ])->get();
        
        $head = [
            "titulo" => "Existências",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "produtos" => $produtos,
            "lojas" => $lojas,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
        
        return view('dashboard.inventarios.existencias', $head);
    }
  
}
