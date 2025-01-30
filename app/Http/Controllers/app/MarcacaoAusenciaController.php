<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Funcionario;
use App\Models\MarcacaoAusencia;
use App\Models\MotivoAusencia;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class MarcacaoAusenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $user = auth()->user();

        // if(!$user->can('listar subsidio')){
        //     Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        //     return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $ausencias = MarcacaoAusencia::with(['ausencia', 'funcionario'])
        ->when($request->funcionario_id, function($query, $value){
            $query->where('funcionario_id', $value);
        })
        ->when($request->ausencia_id, function($query, $value){
            $query->where('ausencia_id', $value);
        })
        ->where('entidade_id', $entidade->empresa->id)
        ->orderBy('created_at', 'desc')
        ->get();
        
        $funcionarios = Funcionario::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito', 'faltas', 'ausencias'])->where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])->orderBy('nome', 'asc')->get();

        $head = [
            "titulo" => "Marcações de Ausências",
            "descricao" => env('APP_NAME'),
            "ausencias" => $ausencias,
            "funcionarios" => $funcionarios,
            "requests" => $request->all('data_inicio', 'data_final', 'funcionario_id'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.marcacoes-ausencias.index', $head);
    }

        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $user = auth()->user();
        
        // if(!$user->can('criar subsidio')){
        //     Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        //     return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
 
        $funcionarios = Funcionario::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito', 'faltas', 'ausencias'])->where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])->orderBy('created_at', 'desc')->get();
            
        $motivos = MotivoAusencia::where('entidade_id', $entidade->empresa->id)->get();
            
        $head = [
            "titulo" => "Criar Marcações de Ausências",
            "descricao" => env('APP_NAME'),
            "funcionarios" => $funcionarios,
            "motivos" => $motivos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.marcacoes-ausencias.create', $head);
    }

        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        // if(!$user->can('criar subsidio')){
        //     Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        //     return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }

        $request->validate([
            'funcionario_id' => 'required|string',
            'data_inicio' => 'required|string',
            'data_final' => 'required|string',
            'ausencia_id' => 'required|string',
        ],[
            'funcionario_id.required' => 'O funcionário é um campo obrigatório',
            'data_inicio.required' => 'A data de início é um campo obrigatório',
            'data_final.required' => 'A data final é um campo obrigatório',
            'ausencia_id.required' => 'A ausência é um campo obrigatório',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            $funcionario = Funcionario::findOrFail($request->funcionario_id);
   
            $marcacao = MarcacaoAusencia::create([
                'data_inicio' => $request->data_inicio,
                'data_final' => $request->data_final,
                'data_referenciada' => $request->data_referenciada,
                'funcionario_id' => $funcionario->id,
                'ausencia_id' => $request->ausencia_id,
                'dias' => $this->calcularDiferencaDias($request->data_inicio, $request->data_final),
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);
            
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return redirect()->back()->with("success", "Dados Cadastrar com Sucesso!");
      
    }
    
        
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = auth()->user();
        
        if(!$user->can('listar motivo')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $ausencia = MarcacaoAusencia::findOrFail($id);
        
        $head = [
            "titulo" => "Detalhe de Marcação de Ausência",
            "descricao" => env('APP_NAME'),
            "ause$ausencia" => $ausencia,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.marcacoes-ausencias.show', $head);

    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = auth()->user();
        
        // if(!$user->can('editar motivo')){
        //     Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        //     return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
         
        $funcionarios = Funcionario::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito', 'faltas', 'ausencias'])->where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])->orderBy('created_at', 'desc')->get();
            
        $motivos = MotivoAusencia::where('entidade_id', $entidade->empresa->id)->get();
        
        $ausencia = MarcacaoAusencia::findOrFail($id);
        
        $head = [
            "titulo" => "Editar Marcação de Ausência",
            "descricao" => env('APP_NAME'),
            "ausencia" => $ausencia,
            "funcionarios" => $funcionarios,
            "motivos" => $motivos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.marcacoes-ausencias.edit', $head);
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
        //
        
        $user = auth()->user();
        
        // if(!$user->can('editar motivo')){
        //     Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        //     return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }
        
        $request->validate([
            'funcionario_id' => 'required|string',
            'data_inicio' => 'required|string',
            'data_final' => 'required|string',
            'ausencia_id' => 'required|string',
        ],[
            'funcionario_id.required' => 'O funcionário é um campo obrigatório',
            'data_inicio.required' => 'A data de início é um campo obrigatório',
            'data_final.required' => 'A data final é um campo obrigatório',
            'ausencia_id.required' => 'A ausência é um campo obrigatório',
        ]);

        $ausencia = MarcacaoAusencia::findOrFail($id);
        
        $ausencia->data_inicio = $request->data_inicio;
        $ausencia->data_final = $request->data_final;
        $ausencia->data_referenciada = $request->data_referenciada;
        $ausencia->funcionario_id = $request->funcionario_id;
        $ausencia->ausencia_id = $request->ausencia_id;
        $ausencia->dias = $this->calcularDiferencaDias($request->data_inicio, $request->data_final);
        
        $ausencia->update();

        return redirect()->back()->with("success", "Dados Actualizados com Sucesso!");
  
    }

        /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = auth()->user();
        
        if(!$user->can('eliminar motivo')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $ausencia = MarcacaoAusencia::findOrFail($id);
     
        if($ausencia->delete()){
            return redirect()->back()->with("success", "Dados Excluído com Sucesso!");
        }else{
            return redirect()->back()->with("warning", "Erro ao tentar Excluir motivo");
        }
    }
    
    function calcularDiferencaDias($dataInicio, $dataFim) {
        // Converte as datas em objetos DateTime
        $dataInicio = new DateTime($dataInicio);
        $dataFim = new DateTime($dataFim);
    
        // Calcula a diferença entre as duas datas
        $diferenca = $dataInicio->diff($dataFim);
    
        // Retorna o número de dias de diferença
        return $diferenca->days;
    }
   
}
