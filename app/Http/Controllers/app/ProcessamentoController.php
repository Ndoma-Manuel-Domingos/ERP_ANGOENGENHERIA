<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Models\Contrato;
use App\Models\Exercicio;
use App\Models\Funcionario;
use App\Models\MarcacaoFalta;
use App\Models\MarcacaoFeria;
use App\Models\Periodo;
use App\Models\Processamento;
use App\Models\TaxaIRT;
use App\Models\TipoProcessamento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use PDF;

class ProcessamentoController extends Controller
{

    use TraitHelpers;
    //
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
 
        $faltas = MarcacaoFalta::where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->orderBy('created_at', 'desc')->get();

        $processamentos = Processamento::with(['exercicio', 'periodo', 'funcionario', 'processamento', 'user'])
        ->when($request->processamento_id, function($query, $value){
            $query->where('processamento_id', $value);
        })
        ->when($request->exercicio_id, function($query, $value){
            $query->where('exercicio_id', $value);
        })
        ->when($request->periodo_id, function($query, $value){
            $query->where('periodo_id', $value);
        })
        ->when($request->status, function($query, $value){
            $query->where('status', $value);
        })
        // ->when($request->data_inicio, function($query, $value){
        //     $query->whereDate('data_registro', '=>', $value);
        // })
        // ->when($request->data_final, function($query, $value){
        //     $query->whereDate('data_registro', '<=', $value);
        // })
        ->where('entidade_id', $entidade->empresa->id)
        ->orderBy('created_at', 'desc')
        ->get();
        
        
        $tipo_processamentos = TipoProcessamento::where('entidade_id', $entidade->empresa->id)
            ->get();

        $exercicios = Exercicio::where('entidade_id', $entidade->empresa->id)
            ->get();

        $periodos = Periodo::where('entidade_id', $entidade->empresa->id)
            ->where('exercicio_id', $this->exercicio())
            ->get();

        $head = [
            "titulo" => "Processamentos",
            "descricao" => env('APP_NAME'),
            "faltas" => $faltas,
            "processamentos" => $processamentos,
            "tipo_processamentos" => $tipo_processamentos,
            "periodos" => $periodos,
            "exercicios" => $exercicios,
            "requests" => $request->all('data_inicio', 'data_final', 'funcionario_id', 'processamento_id', 'exercicio_id', 'periodo_id', 'status'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.processamentos.index', $head);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     
    public function emissao_recibo(Request $request)
    {
        //
        $user = auth()->user();

        // if(!$user->can('listar subsidio')){
        //     Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        //     return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
 
        $faltas = MarcacaoFalta::where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->orderBy('created_at', 'desc')->get();

        $processamentos = Processamento::with(['exercicio', 'periodo', 'funcionario', 'processamento', 'user'])
        ->when($request->processamento_id, function($query, $value){
            $query->where('processamento_id', $value);
        })
        ->when($request->exercicio_id, function($query, $value){
            $query->where('exercicio_id', $value);
        })
        ->when($request->periodo_id, function($query, $value){
            $query->where('periodo_id', $value);
        })
        ->when($request->status, function($query, $value){
            $query->where('status', $value);
        })
        // ->when($request->data_inicio, function($query, $value){
        //     $query->whereDate('data_registro', '=>', $value);
        // })
        // ->when($request->data_final, function($query, $value){
        //     $query->whereDate('data_registro', '<=', $value);
        // })
        ->where('entidade_id', $entidade->empresa->id)
        ->orderBy('created_at', 'desc')
        ->get();

        $tipo_processamentos = TipoProcessamento::where('entidade_id', $entidade->empresa->id)
        // ->orderBy('created_at', 'desc')
        ->get();

        $exercicios = Exercicio::where('entidade_id', $entidade->empresa->id)
        // ->orderBy('created_at', 'desc')
        ->get();

        $periodos = Periodo::where('entidade_id', $entidade->empresa->id)
            ->where('exercicio_id', $this->exercicio())
            ->get();

        $head = [
            "titulo" => "Emissão de Recibos",
            "descricao" => env('APP_NAME'),
            "faltas" => $faltas,
            "processamentos" => $processamentos,
            "tipo_processamentos" => $tipo_processamentos,
            "periodos" => $periodos,
            "exercicios" => $exercicios,
            "requests" => $request->all('data_inicio', 'data_final', 'funcionario_id', 'processamento_id', 'exercicio_id', 'periodo_id', 'status'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.processamentos.emissao-recibos', $head);
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
 
        $funcionarios = Funcionario::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito', 'faltas'])->where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])->orderBy('created_at', 'asc')->get();
            
            
        $tipo_processamentos = TipoProcessamento::where('entidade_id', $entidade->empresa->id)
        ->orderBy('created_at', 'asc')
        ->get();

        $exercicios = Exercicio::where('entidade_id', $entidade->empresa->id)
        ->orderBy('created_at', 'asc')
        ->get();

        $periodos = Periodo::where('entidade_id', $entidade->empresa->id)
            ->where('exercicio_id', $this->exercicio())
            ->get();

        $head = [
            "titulo" => "Processamentos",
            "descricao" => env('APP_NAME'),
            "funcionarios" => $funcionarios,
            
            "tipo_processamentos" => $tipo_processamentos,
            "periodos" => $periodos,
            "exercicios" => $exercicios,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.processamentos.create', $head);
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
            'processamento_id' => 'required|string',
            'exercicio_id' => 'required|string',
            'periodo_id' => 'required|string',
            'dias_processados' => 'required|string',
        ],[
            'processamento_id.required' => 'O tipo de processamento é um campo obrigatório',
            'exercicio_id.required' => 'O exercício é um campo obrigatório',
            'periodo_id.required' => 'O período é um campo obrigatório',
            'dias_processados.required' => 'Dias de processamento é um campo obrigatório',
        ]);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $tipo_processamento = TipoProcessamento::findOrFail($request->processamento_id); 
              
            $periodo = Periodo::findOrFail($request->periodo_id);
            
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            // Data atual
            $dataAtual = Carbon::now()->format('Y-m-d');
            
            if($tipo_processamento->sigla == "V"){
                // Consultar contratos que estão ativos e cujo data_final é menor ou igual à data atual
                $contratos = Contrato::where('entidade_id', $entidade->empresa->id)
                    ->where('status', 'activo')
                    ->whereDate('data_final', '>=', $periodo->final) // antes era da data actual, mais vo colocar da final do mesmo que esta sendo processado, porque pode se dar o caso que o funcionario ate no mesmo a ser pago ainda na tinha completado 22 dias
                    ->whereRaw('DATE_ADD(data_inicio, INTERVAL 22 DAY) <= ?', [$periodo->final])
                    // ->whereDate('data_final', '>=', $dataAtual)
                    // ->whereRaw('DATE_ADD(data_inicio, INTERVAL 22 DAY) <= ?', [$dataAtual])
                    ->pluck('funcionario_id');
                    
                if ($contratos->isEmpty()) {
                    return redirect()->back()->with("warning", "Nenhum contrato valido encontrado, verifica a data do fim de contrato dos funcionários");
                }                  
           
                $funcionarios = Funcionario::with(['contrato.subsidios_contrato.subsidio'])
                ->with(['contrato.subsidios_contrato' => function($query) use ($request){
                    $query->where('processamento_id', $request->processamento_id);
                }])
                ->with(['contrato.descontos_contrato' => function($query) use ($request){
                    $query->where('processamento_id', $request->processamento_id);
                }])
                ->with(['faltas' => function ($query) use ($periodo) {
                    if ($periodo->inicio && $periodo->final) {
                        $query->whereBetween('data_registro', [$periodo->inicio, $periodo->final]);
                    }
                }])
                ->where('entidade_id', $entidade->empresa->id)
                ->whereIn('id', $contratos)
                ->orderBy('created_at', 'desc')
                ->get();
          
                foreach ($funcionarios as $funcionario) {
                
                    $salario_iliquido = 0;
                    $salario_base = 0;
                    $total_subsidios = 0;
                    $total_outros_descontos = 0;
                    $total_faltas = 0;
                    
                    $total_valor_desconto_faltas = 0;
                    
                    // Subsídios sujeitos a IRT e Não Sujeitos
                    $subsidio_soma_s = 0;
                    $subsidio_soma_n = 0;
                    
                    
                    $soma_subsidios_irt = 0;
                    
                    // Subsídios sujeitos a INSS e Não Sujeitos
                    $subsidio_soma_inss = 0;
                    
                    $numero_faltas = count($funcionario->faltas);
                    
                    $ramanescente = 0;
                    
                    // Salario base do funcionario
                    $salario_base = $funcionario->contrato->salario_base ?? 0;
                    
                   
                    foreach ($funcionario->contrato->subsidios_contrato as $subsidio) {
                      
                        if($subsidio->irt == 'Y'){
                        
                            // $total_valor_desconto_faltas = ($subsidio->salario * $numero_faltas) / $request->dias_processados;
                            // - $total_valor_desconto_faltas
                            
                            if($subsidio->salario > $subsidio->limite_isencao){
                                $soma_subsidios_irt += ($subsidio->salario - $subsidio->limite_isencao);
                            }
                        }
                        // if($subsidio->inss == 'Y'){
                        //     $total_subsidios = +$subsidio->salario;       
                        // }
                        
                        $total_subsidios += $subsidio->salario;
                        
                    }
                    
                    foreach ($funcionario->contrato->descontos_contrato as $desconto) {
                        if($desconto->tipo == "O"){
                            
                            if($desconto->tipo_valor == 'P'){
                                $total_outros_descontos += $salario_base * ($desconto->salario / 100);
                            }
                            
                            if($desconto->tipo_valor == 'E'){
                                $total_outros_descontos += $desconto->salario;
                            }
                            
                        }
                    }
                    
                    $salario_iliquido = $salario_base + $total_subsidios;
                    
                    $inss = $salario_iliquido * (3 / 100);
                    $inss_empresa = $salario_iliquido * (8 / 100);
                    
                    $materia_coletavel = ($salario_base + $soma_subsidios_irt) - $inss;
                    
                    $tabela = TaxaIRT::where('remuneracao', '>=', $materia_coletavel)->where('abatimento', '<=', $materia_coletavel)->where('exercicio_id', $this->exercicio())->first();
                    
                    if($tabela){
                        $ramanescente =  $materia_coletavel - $tabela->excesso;
                        
                        $irt = $tabela->valor_fixo + ($ramanescente * ($tabela->taxa / 100));
                        
                    }else{
                        $ramanescente  = 0;
                        
                        $irt  = 0;
                    }
                    
                    $total_faltas = ($salario_base * $numero_faltas) / $request->dias_processados;
                    
                    $desconto = $irt + $inss + $total_faltas + $total_outros_descontos;
                    
                    $salario_liquido = $salario_iliquido - $desconto;
                    
                    $verificar_processamento = Processamento::where('funcionario_id', $funcionario->id)
                        ->where('exercicio_id', $request->exercicio_id)
                        ->where('periodo_id', $request->periodo_id)
                        ->where('processamento_id', $request->processamento_id)
                        ->where('entidade_id', $entidade->empresa->id)
                        ->whereIn('status', ['Pendente'])
                        ->first();
                    
                    if(!$verificar_processamento){
                        Processamento::create([
                            'data_registro' => date("Y-m-d"),
                            'funcionario_id' => $funcionario->id,
                            'exercicio_id' => $request->exercicio_id,
                            'periodo_id' => $request->periodo_id,
                            'outros_descontos' => $total_outros_descontos,
                            'irt' => $irt,
                            'inss' => $inss,
                            'inss_empresa' => $inss_empresa,
                            
                            'taxa_irt' => $tabela->taxa ?? 0,
                            'escalao' => $tabela->escalao ?? "",
                            
                            'forma_pagamento' => $funcionario->contrato->forma_pagamento_id,
                            'categoria' => $funcionario->categoria,
                            'processamento_id' => $request->processamento_id,
                            'dias_processados' => $request->dias_processados,
                            'valor_base' => $salario_base,
                            'valor_iliquido' => $salario_iliquido,
                            'valor_liquido' => $salario_liquido,
                            'faltas' => $total_faltas,
                            'total_desconto' => $desconto,
                            'total_subsidios' => $total_subsidios,
                            'data_inicio' => $request->data_inicio,
                            'data_final' => $request->data_final,
                            'status' => 'Pendente',
                            'user_id' => Auth::user()->id,
                            'entidade_id' => $entidade->empresa->id,
                        ]);
                    }
                }
            }
            
            if($tipo_processamento->sigla == "F"){
            
                $total_outros_descontos = 0;
            
                $marcacao_ferias = MarcacaoFeria::where('exercicio_id', $request->exercicio_id)
                    ->where('periodo_id', $request->periodo_id)
                    // ->where('funcionario_id', $request->funcionario_id)
                    // ->whereDate('data_inicio', '>=', $dataAtual)
                    // ->whereDate('data_final', '<=', $dataAtual)
                    ->where('status', 'Nao Processados')
                    ->pluck('funcionario_id');
                    
                if($marcacao_ferias){
                    
                    $funcionarios = Funcionario::with(['contrato.subsidios_contrato.subsidio'])
                        ->where('entidade_id', $entidade->empresa->id)
                        ->whereIn('id', $marcacao_ferias)
                        ->orderBy('created_at', 'desc')
                        ->get();
                
                    foreach ($funcionarios as $funcionario) {
                        
                        if($funcionario->contrato->mes_pagamento_ferias == $periodo->mes_processamento){
                            if($funcionario->contrato->forma_pagamento_ferias == "completa"){
                                
                                $salario_base = $funcionario->contrato->salario_base ?? 0;
                                $subsidio_ferias = ($funcionario->contrato->salario_base ?? 0) * ($funcionario->contrato->subsidio_ferias / 100);
                                
                                $inss = $subsidio_ferias * (3 / 100);
                                $inss_empresa = $subsidio_ferias * (8 / 100);
                                
                                // subsidio de ferias não e aplicado o inss
                                $inss = 0;
                                $inss_empresa = 0;
                                
                                $materia_coletavel = $subsidio_ferias - $inss;
                                
                                $tabela = TaxaIRT::where('remuneracao', '>=', $materia_coletavel)->where('abatimento', '<=', $materia_coletavel)->where('exercicio_id', $this->exercicio())->first();
                            
                                if($tabela){
                                    $ramanescente =  $materia_coletavel - $tabela->excesso;
                                    
                                    $irt = $tabela->valor_fixo + ($ramanescente * ($tabela->taxa / 100));
                                }else{
                                    $ramanescente  = 0;
                                    
                                    $irt  = 0;
                                }
                                
                                $total_faltas = 0;
                            
                                $desconto = $irt + $inss + $total_faltas + $total_outros_descontos;
                                
                                $salario_liquido = $subsidio_ferias - $desconto;
                                
                                $verificar_processamento = Processamento::where('funcionario_id', $funcionario->id)
                                    ->where('exercicio_id', $request->exercicio_id)
                                    ->where('periodo_id', $request->periodo_id)
                                    ->where('processamento_id', $request->processamento_id)
                                    ->where('entidade_id', $entidade->empresa->id)
                                    ->whereIn('status', ['Pendente'])
                                    ->first();
                                    
                                if(!$verificar_processamento){
                                    Processamento::create([
                                        'data_registro' => date("Y-m-d"),
                                        'funcionario_id' => $funcionario->id,
                                        'exercicio_id' => $request->exercicio_id,
                                        'periodo_id' => $request->periodo_id,
                                        'outros_descontos' => $total_outros_descontos,
                                        'irt' => $irt,
                                        'inss' => $inss,
                                        'inss_empresa' => $inss_empresa,
                                        'categoria' => $funcionario->categoria,
                                        'forma_pagamento' => $funcionario->contrato->forma_pagamento_id,
                                        'dias_processados' => $request->dias_processados,
                                        'processamento_id' => $request->processamento_id,
                                        'valor_base' => $salario_base,
                                        'valor_iliquido' => $subsidio_ferias,
                                        'valor_liquido' => $salario_liquido,
                                        'faltas' => $total_faltas,
                                        'total_desconto' => $desconto,
                                        'total_subsidios' => 0,
                                        'data_inicio' => $request->data_inicio,
                                        'data_final' => $request->data_final,
                                        'status' => 'Pendente',
                                        'user_id' => Auth::user()->id,
                                        'entidade_id' => $entidade->empresa->id,
                                    ]);
                                }
                            }
                        }
                    }
                    
                    $ferias_processadas = MarcacaoFeria::where('exercicio_id', $request->exercicio_id)
                    ->where('periodo_id', $request->periodo_id)
                    ->where('status', 'Nao Processados')
                    ->get();
                    
                    foreach( $ferias_processadas as $item ){
                        $update = MarcacaoFeria::findOrFail($item->id);
                        $update->status = "Processados";
                        $update->update();
                    }
                    
                }else {
                    return redirect()->back()->with("warning", "Nenhum funcionário encontrado com escala de ferias neste Período e Exercício, Exito aqueles que já tem processamentos!");
                }
                
            }
            
            if($tipo_processamento->sigla == "N"){
                                
                $total_outros_descontos = 0;
                
                $contratos = Contrato::where('entidade_id', $entidade->empresa->id)
                    ->where('status', 'activo')
                    ->whereDate('data_final', '>=', $dataAtual)
                    ->pluck('funcionario_id');
                 
                if ($contratos->isEmpty()) {
                    return redirect()->back()->with("warning", "Nenhum contrato valido encontrado, verifica a data do fim de contrato dos funcionários");
                }  
              
                foreach ($funcionarios as $funcionario) {
                    if($funcionario->contrato->mes_pagamento_natal == $periodo->mes_processamento){
                        if($funcionario->contrato->forma_pagamento_natal == "completa"){
                            
                            $salario_base = $funcionario->contrato->salario_base ?? 0;
                            $subsidio_natal = ($funcionario->contrato->salario_base ?? 0) * ($funcionario->contrato->subsidio_natal / 100);
                            
                            $inss = $subsidio_natal * (3 / 100);
                            $inss_empresa = $subsidio_natal * (8 / 100);
                            
                            
                            $materia_coletavel = $subsidio_natal - $inss;
                            
                            $tabela = TaxaIRT::where('remuneracao', '>=', $materia_coletavel)->where('abatimento', '<=', $materia_coletavel)->where('exercicio_id', $this->exercicio())->first();
                        
                            if($tabela){
                                $ramanescente =  $materia_coletavel - $tabela->excesso;
                                
                                $irt = $tabela->valor_fixo + ($ramanescente * ($tabela->taxa / 100));
                            }else{
                                $ramanescente  = 0;
                                
                                $irt  = 0;
                            }
                            
                            $total_faltas = 0;
                        
                            $desconto = $irt + $inss + $total_faltas + $total_outros_descontos;
                            
                            $salario_liquido = $subsidio_natal - $desconto;
                            
                            $verificar_processamento = Processamento::where('funcionario_id', $funcionario->id)
                                ->where('exercicio_id', $request->exercicio_id)
                                ->where('periodo_id', $request->periodo_id)
                                ->where('processamento_id', $request->processamento_id)
                                ->where('entidade_id', $entidade->empresa->id)
                                ->whereIn('status', ['Pendente'])
                                ->first();
                                
                            if(!$verificar_processamento){
                                Processamento::create([
                                    'data_registro' => date("Y-m-d"),
                                    'funcionario_id' => $funcionario->id,
                                    'exercicio_id' => $request->exercicio_id,
                                    'periodo_id' => $request->periodo_id,
                                    'total_subsidios' => $total_outros_descontos,
                                    'irt' => $irt,
                                    'inss' => $inss,
                                    'inss_empresa' => $inss_empresa,
                                    'forma_pagamento' => $funcionario->contrato->forma_pagamento_id,
                                    'categoria' => $funcionario->categoria,
                                    'dias_processados' => $request->dias_processados,
                                    'valor_base' => $salario_base,
                                    'valor_iliquido' => $subsidio_natal,
                                    'processamento_id' => $request->processamento_id,
                                    'valor_liquido' => $salario_liquido,
                                    'faltas' => $total_faltas,
                                    'total_desconto' => $desconto,
                                    'total_subsidios' => 0,
                                    'data_inicio' => $request->data_inicio,
                                    'data_final' => $request->data_final,
                                    'status' => 'Pendente',
                                    'user_id' => Auth::user()->id,
                                    'entidade_id' => $entidade->empresa->id,
                                ]);
                            }
                        }
                    }else{
                        return redirect()->back()->with("warning", "Ocorreu um erro ao processar o subsídio de natal, mês invalido!");
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

        return redirect()->back()->with("success", "Processamento concluido com sucesso!");
      
    }
   
    //
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pagamentos(Request $request)
    {
        //
        $user = auth()->user();

        // if(!$user->can('listar subsidio')){
        //     Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        //     return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $processamentos = Processamento::with(['exercicio', 'periodo', 'funcionario', 'processamento', 'user'])
            ->where('entidade_id', $entidade->empresa->id)
            ->whereIn('status', ['Pago'])
            ->orderBy('created_at', 'desc')
            ->get();
             
        $tipo_processamentos = TipoProcessamento::where('entidade_id', $entidade->empresa->id)
        ->orderBy('created_at', 'desc')
        ->get();

        $exercicios = Exercicio::where('entidade_id', $entidade->empresa->id)
        ->orderBy('created_at', 'desc')
        ->get();

        $periodos = Periodo::where('entidade_id', $entidade->empresa->id)
            ->where('exercicio_id', $this->exercicio())
            ->get();

        $head = [
            "titulo" => "Pagamento de Processamentos",
            "descricao" => env('APP_NAME'),
            "tipo_processamentos" => $tipo_processamentos,
            "processamentos" => $processamentos,
            "periodos" => $periodos,
            "exercicios" => $exercicios,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];


        return view('dashboard.processamentos.pagamentos', $head);
    }

        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pagamentos_store(Request $request)
    {
        $user = auth()->user();
        
        // if(!$user->can('criar subsidio')){
        //     Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        //     return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }
        
        $request->validate([
            'processamento_id' => 'required|string',
            'exercicio_id' => 'required|string',
            'periodo_id' => 'required|string',
            'data_inicio' => 'required|string',
            'data_final' => 'required|string',
            'dias_processados' => 'required|string',
        ],[
            'processamento_id.required' => 'P tipo de processamento é um campo obrigatório',
            'exercicio_id.required' => 'O exercício é um campo obrigatório',
            'periodo_id.required' => 'O período é um campo obrigatório',
            'data_inicio.required' => 'Data de início é um campo obrigatório',
            'data_final.required' => 'Data final é um campo obrigatório',
            'dias_processados.required' => 'Dias de processamento é um campo obrigatório',
        ]);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            $processamentos = Processamento::where('exercicio_id', $request->exercicio_id)
                ->where('periodo_id', $request->periodo_id)
                ->where('processamento_id', $request->processamento_id)
                ->where('entidade_id', $entidade->empresa->id)
                ->whereIn('status', ['Pendente'])
                ->get();
            
            if($processamentos){
                foreach ($processamentos as $processamento) {
                    $update = Processamento::findOrFail($processamento->id);
                    $update->status = 'Pago';
                    $update->update();
                }
            }
                   
            if(count($processamentos) == 0){
                return redirect()->back()->with("danger", "Sem processamento encontrato!");
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

        return redirect()->back()->with("success", "Pagamentos concluido com sucesso!");
      
    }
   
        
    //
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function anulacao(Request $request)
    {
        //
        $user = auth()->user();

        // if(!$user->can('listar subsidio')){
        //     Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        //     return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $processamentos = Processamento::with(['exercicio', 'periodo', 'funcionario', 'processamento', 'user'])
            ->where('entidade_id', $entidade->empresa->id)
            ->whereIn('status', ['Anulado'])
            ->orderBy('created_at', 'desc')
            ->get();

        $tipo_processamentos = TipoProcessamento::where('entidade_id', $entidade->empresa->id)
        ->orderBy('created_at', 'desc')
        ->get();

        $exercicios = Exercicio::where('entidade_id', $entidade->empresa->id)
        ->orderBy('created_at', 'desc')
        ->get();

        $periodos = Periodo::where('entidade_id', $entidade->empresa->id)
            ->where('exercicio_id', $this->exercicio())
            ->get();

        $head = [
            "titulo" => "Anulação de Processamentos",
            "descricao" => env('APP_NAME'),
            "tipo_processamentos" => $tipo_processamentos,
            "processamentos" => $processamentos,
            "periodos" => $periodos,
            "exercicios" => $exercicios,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.processamentos.anulacao', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function anulacao_store(Request $request)
    {
        $user = auth()->user();
        
        // if(!$user->can('criar subsidio')){
        //     Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        //     return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }
        
        $request->validate([
            'processamento_id' => 'required|string',
            'exercicio_id' => 'required|string',
            'periodo_id' => 'required|string',
        ],[
            'processamento_id.required' => 'O tipo de processamento é um campo obrigatório',
            'exercicio_id.required' => 'O exercício é um campo obrigatório',
            'periodo_id.required' => 'O período é um campo obrigatório',
        ]);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            $processamentos = Processamento::where('exercicio_id', $request->exercicio_id)
                ->where('periodo_id', $request->periodo_id)
                ->where('processamento_id', $request->processamento_id)
                ->where('entidade_id', $entidade->empresa->id)
                ->whereIn('status', ['Pendente'])
                ->get();
            
            if($processamentos){
                foreach ($processamentos as $processamento) {
                    $update = Processamento::findOrFail($processamento->id);
                    $update->status = 'Anulado';
                    $update->update();
                }
            }
            
            if(count($processamentos) == 0){
                return redirect()->back()->with("danger", "Sem processamento encontrato!");
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

        return redirect()->back()->with("success", "Processamento Anulado com sucesso!");
      
    }
   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function recibo(Request $request, $id)
    {
        $user = auth()->user();
        
        $processamento = Processamento::with(['exercicio', 'periodo', 
            'funcionario.contrato.forma_pagamento', 
            'funcionario.contrato.categoria', 
            'funcionario.contrato.subsidios_contrato.subsidio', 
            'funcionario.contrato.descontos_contrato.desconto', 
            'funcionario.contrato.cargo.departamento', 
            'funcionario.contrato.tipo_contrato',  'processamento', 'user'
        ])
            ->orderBy('created_at', 'desc')
            ->findOrFail($id);
        
        
        $head = [
            "titulo" => "Recibo",
            "descricao" => env('APP_NAME'),
            "processamento" => $processamento,
       
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.processamentos.recibo', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    
    }
   
   
}
