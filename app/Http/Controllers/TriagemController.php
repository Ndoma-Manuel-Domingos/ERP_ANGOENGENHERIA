<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\Entidade;
use App\Models\FichaConsulta;
use App\Models\FichaTriagem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TriagemController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
 
        $triagens = FichaTriagem::with(['consulta.paciente', 'consulta.produto', 'consulta.medico'])->where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])->orderBy('created_at', 'desc')->get();
            
        $empresa = Entidade::with(['variacoes', 'clientes', 'marcas','categorias'])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Triagens",
            "descricao" => env('APP_NAME'),
            "triagens" => $triagens,
            "empresa" => $empresa,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.triagens.index', $head);
    }
    
    //
    public function create(Request $request)
    {
        $consulta = Consulta::findOrFail($request->id);
        $consulta->status = 'EM ATENDIMENTO';
        $consulta->update();
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $triagem = FichaTriagem::create([
            'consulta_id' => $consulta->id,
            'status' => 'EM ATENDIMENTO',
            'user_id' => Auth::user()->id,
            'entidade_id' => $entidade->empresa->id,
        ]);
        
        FichaConsulta::create([
            'consulta_id' => $triagem->consulta_id,
            'ficha_triagem_id' => $triagem->id,
            'status' => 'EM ATENDIMENTO',
            'user_id' => auth()->user()->id,
            'entidade_id' => $entidade->empresa->id,
        ]);
        
        return redirect()->back()->with("success", "Paciente Enviado para triagem com sucesso!");

    }
    
        
        /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $triagem = FichaTriagem::findOrFail($id);
        if($triagem->delete()){
            return redirect()->route('triagens.index')->with("success", "Dados Excluído com Sucesso!");
        }else{
            return redirect()->route('triagens.index')->with("warning", "Erro ao tentar Excluir triagem");
        }
    }
}
