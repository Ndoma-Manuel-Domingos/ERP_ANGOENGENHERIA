<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Models\Exercicio;
use App\Models\Funcionario;
use App\Models\MarcacaoFeria;
use App\Models\Periodo;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class MarcacaoFeriaController extends Controller
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
        
        $ferias = MarcacaoFeria::when($request->funcionario_id, function ($query, $value) {
            $query->where('funcionario_id', $value);
        })
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('data_inicio', '>=', $value);
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('data_final', '<=', $value);
        })
        ->with([
            'exercicio',
            'periodo',
            'funcionario.estado_civil',
            'funcionario.seguradora',
            'funcionario.provincia',
            'funcionario.municipio',
            'funcionario.distrito'
        ])
        ->where('entidade_id', $entidade->empresa->id)
        ->orderBy('created_at', 'desc')
        ->get();
        
        $contrato_id = Contrato::where('entidade_id', $entidade->empresa->id)->where('status', 'activo')->pluck('funcionario_id');
        
        $funcionarios = Funcionario::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito', 'faltas'])
            ->whereIn('id', $contrato_id)
            ->where('entidade_id', '=', $entidade->empresa->id )
            ->get();

        $head = [
            "titulo" => "Marcações de Ferias",
            "descricao" => env('APP_NAME'),
            "ferias" => $ferias,
            "funcionarios" => $funcionarios,
            "requests" => $request->all('data_inicio', 'data_final', 'funcionario_id'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.marcacoes-ferias.index', $head);
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
                
        $contrato_id = Contrato::where('entidade_id', $entidade->empresa->id)->where('status', 'activo')->pluck('funcionario_id');
        
        $funcionarios = Funcionario::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito', 'faltas'])
            ->where('entidade_id', '=', $entidade->empresa->id )
            ->whereIn('id', $contrato_id)
            ->get();
        
        $exercicios = Exercicio::where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('created_at', 'asc')
            ->get();
        
        $periodos = Periodo::where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('created_at', 'asc')
            ->get();
            
        $head = [
            "titulo" => "Criar Marcações de Ferias",
            "descricao" => env('APP_NAME'),
            "exercicios" => $exercicios,
            "periodos" => $periodos,
            "funcionarios" => $funcionarios,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.marcacoes-ferias.create', $head);
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
        ],[
            'funcionario_id.required' => 'O funcionário é um campo obrigatório',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            $funcionario = Funcionario::findOrFail($request->funcionario_id);
            
            $dataAtual = Carbon::now()->format('Y-m-d');
            
            $contrato = Contrato::where('funcionario_id', $funcionario->id)
                ->where('status', 'activo')
                ->whereDate('data_final', '<=', $dataAtual)
                ->whereRaw('DATE_ADD(data_inicio, INTERVAL 6 MONTH) <= ?', [$dataAtual])
                ->first();
            
            if(!$contrato){
                return response()->json(['error' => 'Este funcionário, não tem permissão para gozar as ferias.'], 404);
            }
            
            $datas = $request->input('datas');
            
            if (!empty($datas)) {
                
                $total_dias = count($datas);
                
                // Converter as datas para objetos Carbon
                $datas = array_map(function ($data) {
                    return Carbon::parse($data);
                }, $datas);
                
                // Ordenar as datas
                usort($datas, function ($a, $b) {
                    return $a->gt($b) ? 1 : -1;
                });
                
                // Obter a primeira e a última data
                $primeiraDataInicio = $datas[0];
                $ultimaDataFinal = end($datas);
        
                // Formatar as datas de volta para string, se necessário
                $primeiraDataInicio = $primeiraDataInicio->format('Y-m-d');
                $ultimaDataFinal = $ultimaDataFinal->format('Y-m-d');
                
                $verificar = MarcacaoFeria::where('exercicio_id', $request->exercicio_id)->where('periodo_id', $request->periodo_id)->where('funcionario_id', $funcionario->id)->where('entidade_id', $entidade->empresa->id)->first();
                
                if(!$verificar){
                    $marcacao = MarcacaoFeria::create([
                        'data_inicio' => $primeiraDataInicio,
                        'data_final' => $ultimaDataFinal,
                        'data_registro' => date("Y-m-d"),
                        'total_dias' => $total_dias,
                        'funcionario_id' => $funcionario->id,
                        'exercicio_id' => $request->exercicio_id,
                        'periodo_id' => $request->periodo_id,
                        'user_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                    
                    $contrato = Contrato::where('funcionario_id', $funcionario->id)->where('status', 'activo')->first();
                    
                    $periodo = Periodo::findOrFail($request->periodo_id);
                    
                    if($contrato){
                        $update = Contrato::findOrFail($contrato->id);
                    
                        $update->mes_pagamento_ferias = $periodo->mes_processamento;
                        $update->update();
                    }
                    
                }
                
            }
            
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
    
    function verificarAptidaoFerias($dataInicioTrabalho) {
        // Data atual
        $dataAtual = new DateTime();
    
        // Converte a data de início de trabalho em um objeto DateTime
        $dataInicioTrabalho = new DateTime($dataInicioTrabalho);
    
        // Calcula a diferença entre a data de início e a data atual
        $diferenca = $dataInicioTrabalho->diff($dataAtual);
    
        // Verifica se a diferença é maior ou igual a 6 meses
        if ($diferenca->m >= 6 && $diferenca->y >= 0) {
            return true; // O funcionário está apto para tirar férias
        } else {
            return false; // O funcionário ainda não está apto para tirar férias
        }
    }
   
}
