<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Consulta;
use App\Models\Entidade;
use App\Models\Medico;
use App\Models\Produto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultaController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
 
        $consultas = Consulta::with(['paciente', 'produto', 'medico', 'entidade', 'user'])->where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])->orderBy('created_at', 'desc')->get();
            
        $empresa = Entidade::with(['variacoes', 'clientes', 'marcas','categorias'])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Consultas",
            "descricao" => env('APP_NAME'),
            "consultas" => $consultas,
            "empresa" => $empresa,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.consultas.index', $head);
    }

        
    public function create(Request $request, $id = null)
    {
 
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $produtos = Produto::where('entidade_id', '=', $entidade->empresa->id)->orderBy('created_at', 'desc')->get();
        $medicos = Medico::where('entidade_id', '=', $entidade->empresa->id)->orderBy('created_at', 'desc')->get();
        $pacientes = Cliente::when($id, function($query, $value){
            $query->where('id', $value);
        })->where('entidade_id', '=', $entidade->empresa->id)->orderBy('created_at', 'desc')->get();
            
        $head = [
            "titulo" => "Marcar Consulta",
            "descricao" => env('APP_NAME'),
            "entidade" => $entidade,
            "produtos" => $produtos,
            "medicos" => $medicos,
            "pacientes" => $pacientes,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.consultas.create', $head);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'data_consulta' => 'required',
            'hora_consulta' => 'required',
            'paciente_id' => 'required',
            'consulta_id' => 'required',
            'medico_id' => 'required',
        ], [
            'data_consulta.required' => 'A data da consulta é um campo obrigatório',
            'hora_consulta.required' => 'A hora da consulta é um campo obrigatório',
            'paciente_id.required' => 'O paciente é um campo obrigatório',
            'consulta_id.required' => 'O consulta é um campo obrigatório',
            'medico_id.required' => 'O médico é um campo obrigatório',
        ]);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $consultas = Consulta::create([
            
            'data_consulta' => $request->data_consulta,
            'hora_consulta' => $request->hora_consulta,
            'paciente_id' => $request->paciente_id,
            'consulta_id' => $request->consulta_id,
            'medico_id' => $request->medico_id,
            'status' => 'AGENDADA',
            'pago' => 'NAO PAGO',
          
            "observacao" => $request->observacao,         
            "user_id" => Auth::user()->id,    
            'entidade_id' => $entidade->empresa->id,      
        ]);
            
        if($consultas->save()){
            return redirect()->route('consultas.index')->with("success", "Dados Cadastrar com Sucesso!");
        }else{
            return redirect()->route('consultas.create')->with("warning", "Erro ao tentar cadastrar médico");
        }
    }
    
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $consulta = Consulta::with(['paciente.estado_civil', 'produto', 'medico', 'entidade', 'user'])->findOrFail($id);
    
        $head = [
            "titulo" => "Médico",
            "descricao" => env('APP_NAME'),
            "consulta" => $consulta,
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.consultas.show', $head);    
    }

    


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $consulta = Consulta::findOrFail($id);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(['variacoes', 'clientes', 'marcas', 'categorias'])->findOrFail($entidade->empresa->id);

        $produtos = Produto::where('entidade_id', '=', $entidade->empresa->id)->orderBy('created_at', 'desc')->get();
        $medicos = Medico::where('entidade_id', '=', $entidade->empresa->id)->orderBy('created_at', 'desc')->get();
        $pacientes = Cliente::where('entidade_id', '=', $entidade->empresa->id)->orderBy('created_at', 'desc')->get();
        
        $head = [
            "titulo" => "Consultas",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "produtos" => $produtos,
            "medicos" => $medicos,
            "pacientes" => $pacientes,
            "consulta" => $consulta,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.consultas.edit', $head);    
    }
    
        /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'data_consulta' => 'required',
            'hora_consulta' => 'required',
            'paciente_id' => 'required',
            'consulta_id' => 'required',
            'medico_id' => 'required',
        ], [
            'data_consulta.required' => 'A data da consulta é um campo obrigatório',
            'hora_consulta.required' => 'A hora da consulta é um campo obrigatório',
            'paciente_id.required' => 'O paciente é um campo obrigatório',
            'consulta_id.required' => 'A consulta é um campo obrigatório',
            'medico_id.required' => 'O médico é um campo obrigatório',
        ]);
        
        $consulta = Consulta::findOrFail($id);
        $consulta->data_consulta = $request->data_consulta;
        $consulta->hora_consulta = $request->hora_consulta;
        $consulta->paciente_id = $request->paciente_id;
        $consulta->consulta_id = $request->consulta_id;
        $consulta->medico_id = $request->medico_id;
        
        $consulta->update();

        if($consulta->save()){
            return redirect()->route('consultas.index')->with("success", "Dados Actualizados com Sucesso!");
        }else{
            return redirect()->route('consultas.create')->with("warning", "Erro ao tentar Actualizar Cnosulta");
        }
    }
    
        /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancelar_consulta($id)
    {
        $consulta = Consulta::findOrFail($id);

        if($consulta->status == "AGENDADA"){
            $estado = "CANCELADA";
        }
        
        if($consulta->status == "CANCELADA"){
            $estado = "AGENDADA";
        }
        
        $consulta->status = $estado;
        $consulta->update();

        return redirect()->route('consultas.index')->with("success", "Estado da Consulta Modificada com sucesso!");  
    }
    
    
        /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $consulta = Consulta::findOrFail($id);
        if($consulta->delete()){
            return redirect()->route('consultas.index')->with("success", "Dados Excluído com Sucesso!");
        }else{
            return redirect()->route('consultas.index')->with("warning", "Erro ao tentar Excluir consulta");
        }
    }
    
}
