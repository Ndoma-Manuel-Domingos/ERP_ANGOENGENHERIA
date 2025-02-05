<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Models\ContaBancaria;
use App\Models\Caixa;
use App\Models\Cliente;
use App\Models\Subconta;
use App\Models\Dispesa;
use App\Models\Fornecedore;
use App\Models\Receita;
use App\Models\OperacaoFinanceiro;
use App\Models\TipoPagamento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

use PDF;

class OperacaoFinanceiraController extends Controller
{
    use TraitHelpers;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $user = auth()->user();

        if(!$user->can('operacao financeira')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
      
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $operacoes = OperacaoFinanceiro::when($request->data_inicio, function($query, $value){
            $query->whereDate('date_at', '>=', Carbon::parse($value));
        })
        ->when($request->data_final, function($query, $value){
             $query->whereDate('date_at', '<=',Carbon::parse($value));
        })
        ->when($request->tipo_movimento, function($query, $value){
            $query->where('type', $value);
        })
        ->when($request->status, function($query, $value){
            $query->where('status_pagamento', $value);
        })
        ->where('entidade_id', '=', $entidade->empresa->id)
        ->whereIn('type', ['R', 'D'])
        ->with(['fornecedor', 'cliente', 'dispesa', 'caixa', 'contabancaria', 'receita', 'subconta'])
        ->orderBy('created_at', 'desc')
        ->get();
        

        $head = [
            "titulo" => "Operações Financeiras",
            "descricao" => env('APP_NAME'),
            "operacoes" => $operacoes,
            "requests" => $request->all('data_inicio', 'data_final', 'tipo_movimento', 'status'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.operacao-financeira.index', $head);
    }
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function lixeira(Request $request)
    {
        //
        $user = auth()->user();

        if(!$user->can('operacao financeira')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $operacoes = OperacaoFinanceiro::when($request->data_inicio, function($query, $value){
            $query->whereDate('date_at', '>=', Carbon::parse($value));
        })
        ->when($request->data_final, function($query, $value){
             $query->whereDate('date_at', '<=',Carbon::parse($value));
        })
        ->when($request->tipo_movimento, function($query, $value){
            $query->where('type', $value);
        })
        ->when($request->status, function($query, $value){
            $query->where('status_pagamento', $value);
        })
        ->where('entidade_id', '=', $entidade->empresa->id)
        ->whereIn('type', ['R', 'D'])
        ->with(['subconta', 'fornecedor','cliente','dispesa', 'caixa','contabancaria','receita','user' ,'entidade'])
        ->orderBy('created_at', 'desc')
        ->onlyTrashed()
        ->get();
            

        $head = [
            "titulo" => "Operações Financeiras",
            "descricao" => env('APP_NAME'),
            "operacoes" => $operacoes,
            "requests" => $request->all('data_inicio', 'data_final', 'tipo_movimento', 'status'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.operacao-financeira.lixeira', $head);
    }
    
    public function exportar(Request $request)
    {
        $user = auth()->user();

        if(!$user->can('operacao financeira')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $operacoes = OperacaoFinanceiro::when($request->data_inicio, function($query, $value){
            $query->whereDate('date_at', '>=', Carbon::parse($value));
        })
        ->when($request->data_final, function($query, $value){
             $query->whereDate('date_at', '<=',Carbon::parse($value));
        })
        ->when($request->tipo_movimento, function($query, $value){
            $query->where('type', $value);
        })
        ->when($request->status, function($query, $value){
            $query->where('status_pagamento', $value);
        })
        ->where('entidade_id', '=', $entidade->empresa->id)
        ->whereIn('type', ['R', 'D'])
        ->with(['subconta', 'fornecedor','cliente','dispesa', 'caixa','contabancaria','receita','user' ,'entidade'])
        ->orderBy('created_at', 'desc')
        ->get();
 
        $head = [
            'titulo' => "Operações Financeiras",
            'descricao' => env('APP_NAME'),
            "operacoes" => $operacoes,
            "requests" => $request->all('data_inicio', 'data_final', 'tipo_movimento', 'status'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.operacao-financeira.exportar', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
        // return $pdf->download('test.pdf');
    }    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transacoes(Request $request)
    {
        //
        $user = auth()->user();

        if(!$user->can('operacao financeira')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $hoje = Carbon::now();
        
        if($request->relatorio == "contas_receber_atraso"){
            // Contas a receber - contas_receber_atraso
            $operacoes = OperacaoFinanceiro::where('type', 'R')
                ->with(['fornecedor', 'forma_recebimento', 'forma_pagamento', 'cliente', 'dispesa', 'receita'])
                ->where('status_pagamento', 'pendente')
                ->where('entidade_id', $entidade->empresa->id)
                ->where('date_at', '<=', $hoje)
                ->get();
        
        }else if($request->relatorio == "contas_receber_mes"){
            // contas_receber_mes
            $operacoes = OperacaoFinanceiro::where('type', 'R')
                ->with(['fornecedor', 'forma_recebimento', 'forma_pagamento', 'cliente', 'dispesa', 'receita'])
                ->where('status_pagamento', 'pendente')
                ->where('entidade_id', $entidade->empresa->id)
                ->whereMonth('date_at', $hoje->month)
                ->whereYear('date_at', $hoje->year)
                ->get();
        
        }else if($request->relatorio == "contas_pagar_atraso"){
            // Contas a pagar - contas_pagar_atraso
            $operacoes = OperacaoFinanceiro::where('type', 'D')
                ->with(['fornecedor', 'forma_recebimento', 'forma_pagamento', 'cliente', 'dispesa', 'receita'])
                ->where('status_pagamento', 'pendente')
                ->where('entidade_id', $entidade->empresa->id)
                ->where('date_at', '<=', $hoje) 
                ->get();
        
        }else if($request->relatorio == "contas_pagar_mes"){
            // contas_pagar_mes
            $operacoes = OperacaoFinanceiro::where('type', 'D')
                ->with(['fornecedor', 'forma_recebimento', 'forma_pagamento', 'cliente', 'dispesa', 'receita'])
                ->where('status_pagamento', 'pendente')
                ->where('entidade_id', $entidade->empresa->id)
                ->whereMonth('date_at', $hoje->month)
                ->whereYear('date_at', $hoje->year)
                ->get();
        }else if($request->relatorio == "") {
            // contas_pagar_mes
            $operacoes = OperacaoFinanceiro::with(['fornecedor', 'forma_recebimento', 'forma_pagamento', 'cliente', 'dispesa', 'receita'])
                ->where('entidade_id', $entidade->empresa->id)
                ->get();
        }

        // Saldo atual
        $receitasPagas = OperacaoFinanceiro::where('type', 'R')->where('entidade_id', $entidade->empresa->id)->where('status_pagamento', 'pago')->sum('motante');
        $despesasPagas = OperacaoFinanceiro::where('type', 'D')->where('entidade_id', $entidade->empresa->id)->where('status_pagamento', 'pago')->sum('motante');
        $saldoAtual = $receitasPagas - $despesasPagas;
        
        $head = [
            "titulo" => "Transações Financeiras",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "operacoes" => $operacoes,
            "receitasPagas" => $receitasPagas,
            "despesasPagas" => $despesasPagas,
            "saldoAtual" => $saldoAtual,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

    
        return view('dashboard.operacao-financeira.transacoes', $head);
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $user = auth()->user();
        
        if(!$user->can('operacao financeira')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
               
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        if($request->tipo == "receita"){
            $tipos = Receita::where('type', 'R')->get();
        }
        
        if($request->tipo == "dispesa"){
            $tipos = Dispesa::where('type', 'D')->get();
        }
        
        $caixas = Caixa::where('entidade_id', '=', $entidade->empresa->id)->get();
        $bancos = ContaBancaria::where('entidade_id', '=', $entidade->empresa->id)->get();
        
        $formas_pagamentos = TipoPagamento::get();
        $clientes = Cliente::where('entidade_id', '=', $entidade->empresa->id)->get();
        $fornecedores = Fornecedore::where('entidade_id', '=', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Registro de {$request->tipo}",
            "descricao" => env('APP_NAME'),
            "tipos" => $tipos,
            "formas_pagamentos" => $formas_pagamentos,
            "clientes" => $clientes,
            "fornecedores" => $fornecedores,
            "caixas" => $caixas,
            "bancos" => $bancos,
            "requests" => $request->all('tipo'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.operacao-financeira.create', $head);
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
       
        if(!$user->can('operacao financeira')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'tipo_id' => 'required|string',
            'referencia' => 'required|string',
            'tipo_servico_id' => 'required|string',
        ],[
            'tipo_id.required' => 'O tipo é um campo obrigatório',
            'referencia.required' => 'A designação é um campo obrigatório',
            'tipo_servico_id.required' => 'O tipo de serviço é um campo obrigatório',
        ]);
        
         
        $entidade = User::with('empresa.tipo_entidade.modulos')->findOrFail(Auth::user()->id);
        
        $motante1 = 0;
        $motante2 = 0;
        
        $caixaActivo = Caixa::where([
            ['active', true],
            ['status', '=', 'aberto'],
            ['user_id', '=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->first();
        
        
        if($request->tipo_servico_id === "receita"){
            $request->validate([
                'forma_recebimento_id' => 'required|string',
                'data_recebimento' => 'required|string',
            ],[
                'forma_recebimento_id.required' => 'A forma de recebimento é um campo obrigatório',
                'data_recebimento.required' => 'A data do recebimento é um campo obrigatório',
            ]);
            
            $forma = TipoPagamento::findOrFail($request->forma_recebimento_id);
            
            if($forma->tipo === "NU"){
                $request->validate([
                    'caixa_id' => 'required|string',
                    'motante' => 'required|string',
                ],[
                    'caixa_id.required' => 'O caixa é um campo obrigatório',
                    'motante.required' => 'O motante do caixa é um campo obrigatório',
                ]);
                
                $motante1 = $request->motante ?? 0;
            }
            
            if($forma->tipo === "MB" || $forma->tipo === "DE" || $forma->tipo === "TE"){
                $request->validate([
                    'banco_id' => 'required|string',
                    'motante_banco' => 'required|string',
                ],[
                    'banco_id.required' => 'A conta bancária é um campo obrigatório',
                    'motante_banco.required' => 'O motante da conta bancária é um campo obrigatório',
                ]);
                
                $motante2 = $request->motante_banco ?? 0;
            }
            
            if($forma->tipo === "OU"){
                $request->validate([
                    'caixa_id' => 'required|string',
                    'banco_id' => 'required|string',
                    'motante' => 'required|string',
                    'motante_banco' => 'required|string',
                ],[
                    'caixa_id.required' => 'O caixa é um campo obrigatório',
                    'banco_id.required' => 'A conta bancária é um campo obrigatório',
                    'motante.required' => 'O motante do caixa é um campo obrigatório',
                    'motante_banco.required' => 'O motante da conta bancária é um campo obrigatório',
                ]);
                
                $motante1 = $request->motante ?? 0;
                $motante2 = $request->motante_banco ?? 0;
            }
            
            $data_at = $request->data_recebimento;
            $motante = $motante1 + $motante2;
            
        }else 
        {
            $request->validate([
                'forma_pagamento_id' => 'required|string',
                'data_pagamento' => 'required|string',
            ],[
                'forma_pagamento_id.required' => 'A forma de pagamento é um campo obrigatório',
                'data_pagamento.required' => 'A data do pagamento é um campo obrigatório',
            ]);
            
            $forma = TipoPagamento::findOrFail($request->forma_pagamento_id);
            
            if($forma->tipo === "NU"){
                $request->validate([
                    'caixa_id' => 'required|string',
                    'motante' => 'required|string',
                ],[
                    'caixa_id.required' => 'O caixa é um campo obrigatório',
                    'motante.required' => 'O motante do caixa é um campo obrigatório',
                ]);
                
                $motante1 = $request->motante ?? 0;
            }
            
            if($forma->tipo === "MB" || $forma->tipo === "DE" || $forma->tipo === "TE"){
                $request->validate([
                    'banco_id' => 'required|string',
                    'motante_banco' => 'required|string',
                ],[
                    'banco_id.required' => 'A conta bancária é um campo obrigatório',
                    'motante_banco.required' => 'O motante da conta bancária é um campo obrigatório',
                ]);
                
                $motante2 = $request->motante_banco ?? 0;
            }
            
            if($forma->tipo === "OU"){
                $request->validate([
                    'caixa_id' => 'required|string',
                    'banco_id' => 'required|string',
                    'motante' => 'required|string',
                    'motante_banco' => 'required|string',
                ],[
                    'caixa_id.required' => 'O caixa é um campo obrigatório',
                    'banco_id.required' => 'A conta bancária é um campo obrigatório',
                    'motante.required' => 'O motante do caixa é um campo obrigatório',
                    'motante_banco.required' => 'O motante da conta bancária é um campo obrigatório',
                ]);
                
                $motante1 = $request->motante ?? 0;
                $motante2 = $request->motante_banco ?? 0;
            }
            
            $data_at = $request->data_pagamento;
            $motante = $motante1 + $motante2;
        }
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
           
            $code = uniqid(time());
            $formaPagamento = TipoPagamento::findOrFail($request->forma_pagamento_id ?? $request->forma_recebimento_id);
           
            if($formaPagamento->tipo == "NU"){
                if($entidade->empresa->tem_permissao("Gestão Contabilidade")){
                    $conta = Caixa::find($request->caixa_id);
                    
                    if($conta){
                        $subconta = Subconta::where('code', $conta->code)->first();
                        if($subconta){
                        
                            $this->registra_operacoes(  
                                $request->motante,
                                $subconta->id,
                                $request->cliente_id,
                                $request->tipo_servico_id == "receita" ? "R" : "D",
                                $request->status_pagamento,
                                $code,
                                $request->tipo_servico_id == "receita" ? "E" : "S",
                                $data_at,
                                $entidade->empresa->id,
                                $request->referencia,
                                Auth::user()->id,
                                $caixaActivo ? 'pendente' : 'concluido', 
                                'C', 
                                $caixaActivo ? $caixaActivo->code_caixa : NULL,
                                $request->tipo_id,
                                $request->parcelado,
                                $request->parcelas,
                                $request->fornecedor_id
                            );
                            
                        }else{
                            return response()->json(['message' => "Por favor, verificar se esta caixa foi criado ou cadastrado correctamente, para qualquer duvido actualiza o caixa!"], 404);
                        }
                    }else{
                        return response()->json(['message' => "Por favor, verificar se informou o caixa correcto!"], 404);
                    }
                    
                }else {
                    //  NAO USA A CONTABILIDADE
                    $conta = Caixa::find($request->caixa_id);
                              
                    $this->registra_operacoes(  
                        $motante,
                        $conta->id,
                        $request->cliente_id,
                        $request->tipo_servico_id == "receita" ? "R" : "D",
                        $request->status_pagamento,
                        $code,
                        $request->tipo_servico_id == "receita" ? "E" : "S",
                        $data_at,
                        $entidade->empresa->id,
                        $request->referencia,
                        Auth::user()->id,
                        $caixaActivo ? 'pendente' : 'concluido',
                        'C', 
                        $caixaActivo ? $caixaActivo->code_caixa : NULL,
                        $request->tipo_id,
                        $request->parcelado,
                        $request->parcelas,
                        $request->fornecedor_id
                    );
                }
            }
            
            if($formaPagamento->tipo == "MB" || $formaPagamento->tipo == "DE" || $formaPagamento->tipo == "TE"){
           
                if($entidade->empresa->tem_permissao("Gestão Contabilidade")){
                                
                    $conta = ContaBancaria::find($request->banco_id);
                    if($conta){
                        $subconta = Subconta::where('code', $conta->code)->first();
                        
                        if($subconta){
                            
                            $this->registra_operacoes(  
                                $request->motante,
                                $subconta->id,
                                $request->cliente_id,
                                $request->tipo_servico_id == "receita" ? "R" : "D",
                                $request->status_pagamento,
                                $code,
                                $request->tipo_servico_id == "receita" ? "E" : "S",
                                $data_at,
                                $entidade->empresa->id,
                                $request->referencia,
                                Auth::user()->id,
                                $caixaActivo ? 'pendente' : 'concluido', 
                                'B', 
                                $caixaActivo ? $caixaActivo->code_caixa : NULL,
                                $request->tipo_id,
                                $request->parcelado,
                                $request->parcelas,
                                $request->fornecedor_id
                            );
                            
                        }else{
                            return response()->json(['message' => "Por favor, verificar se esta conta bancária foi criado ou cadastrado correctamente, para qualquer duvido actualiza a conta bancária!"], 404);
                        }
                    }else{
                        return response()->json(['message' => "Por favor, verificar se informou a conta bancária correcto!"], 404);
                    }
                }else {
                    
                    $conta = ContaBancaria::find($request->banco_id);
                    
                    $this->registra_operacoes(  
                        $motante,
                        $conta->id,
                        $request->cliente_id,
                        $request->tipo_servico_id == "receita" ? "R" : "D",
                        $request->status_pagamento,
                        $code,
                        $request->tipo_servico_id == "receita" ? "E" : "S",
                        $data_at,
                        $entidade->empresa->id,
                        $request->referencia,
                        Auth::user()->id,
                        $caixaActivo ? 'pendente' : 'concluido', 
                        'B', 
                        $caixaActivo ? $caixaActivo->code_caixa : NULL,
                        $request->tipo_id,
                        $request->parcelado,
                        $request->parcelas,
                        $request->fornecedor_id
                    );

                }

            }
            
            if($formaPagamento->tipo == "OU"){
                
                if($entidade->empresa->tem_permissao("Gestão Contabilidade")){
                    $conta = Caixa::find($request->caixa_id);
                    $conta1 = ContaBancaria::find($request->banco_id);
                    
                    if($conta){
                        $subconta = Subconta::where('code', $conta->code)->first();
                        if($subconta){
                            
                            $this->registra_operacoes(  
                                $request->motante,
                                $subconta->id,
                                $request->cliente_id,
                                $request->tipo_servico_id == "receita" ? "R" : "D",
                                $request->status_pagamento,
                                $code,
                                $request->tipo_servico_id == "receita" ? "E" : "S",
                                $data_at,
                                $entidade->empresa->id,
                                $request->referencia,
                                Auth::user()->id,
                                $caixaActivo ? 'pendente' : 'concluido', 
                                'C', 
                                $caixaActivo ? $caixaActivo->code_caixa : NULL,
                                $request->tipo_id,
                                $request->parcelado,
                                $request->parcelas,
                                $request->fornecedor_id
                            );
                        
                        }else {
                            return response()->json(['message' => "Por favor, verificar se este caixa foi criado ou cadastrado correctamente, para qualquer duvido actualiza a conta bancária!"], 404);
                        }
                    }else{
                        return response()->json(['message' => "Por favor, verificar se informou o caixa correcto!"], 404);
                    }
                    if($conta1){
                        $subconta = Subconta::where('code', $conta1->code)->first();
                        if($subconta){
                            
                            $this->registra_operacoes(  
                                $request->motante_banco,
                                $subconta->id,
                                $request->cliente_id,
                                $request->tipo_servico_id == "receita" ? "R" : "D",
                                $request->status_pagamento,
                                $code,
                                $request->tipo_servico_id == "receita" ? "E" : "S",
                                $data_at,
                                $entidade->empresa->id,
                                $request->referencia,
                                Auth::user()->id,
                                $caixaActivo ? 'pendente' : 'concluido', 
                                'B', 
                                $caixaActivo ? $caixaActivo->code_caixa : NULL,
                                $request->tipo_id,
                                $request->parcelado,
                                $request->parcelas,
                                $request->fornecedor_id
                            );
                        
                        }else {
                            return response()->json(['message' => "Por favor, verificar se esta conta bancária foi criado ou cadastrado correctamente, para qualquer duvido actualiza a conta bancária!"], 404);
                        }
                    }else{
                        return response()->json(['message' => "Por favor, verificar se informou a conta bancária correcto!"], 404);
                    }
                
                }else{
                    $conta = Caixa::find($request->caixa_id);
                    $conta1 = ContaBancaria::find($request->banco_id);
                    
                    $this->registra_operacoes(  
                        $request->motante,
                        $conta->id,
                        $request->cliente_id,
                        $request->tipo_servico_id == "receita" ? "R" : "D",
                        $request->status_pagamento,
                        $code,
                        $request->tipo_servico_id == "receita" ? "E" : "S",
                        $data_at,
                        $entidade->empresa->id,
                        $request->referencia,
                        Auth::user()->id,
                        $caixaActivo ? 'pendente' : 'concluido', 
                        'C', 
                        $caixaActivo ? $caixaActivo->code_caixa : NULL,
                        $request->tipo_id,
                        $request->parcelado,
                        $request->parcelas,
                        $request->fornecedor_id
                    );
                    
                    $this->registra_operacoes(  
                        $request->motante_banco,
                        $conta1->id,
                        $request->cliente_id,
                        $request->tipo_servico_id == "receita" ? "R" : "D",
                        $request->status_pagamento,
                        $code,
                        $request->tipo_servico_id == "receita" ? "E" : "S",
                        $data_at,
                        $entidade->empresa->id,
                        $request->referencia,
                        Auth::user()->id,
                        $caixaActivo ? 'pendente' : 'concluido', 
                        'B', 
                        $caixaActivo ? $caixaActivo->code_caixa : NULL,
                        $request->tipo_id,
                        $request->parcelado,
                        $request->parcelas,
                        $request->fornecedor_id
                    );
                    
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

    
    public function graficoAnual(Request $request)
    {
        $anoAtual = now()->year;
        
        // Inicializa os dados para cada mês
        $dadosMensais = array_fill(1, 12, [
            'receita' => 0,
            'despesa' => 0,
            'saldo' => 0,
        ]);
        
        // Totais anuais
        $totalReceita = 0;
        $totalDespesa = 0;
        $totalSaldo = 0;

        // Recupera os dados do banco
        $operacoes = OperacaoFinanceiro::where('status_pagamento', ['pago'])->whereYear('date_at', $anoAtual)->get();
        
        foreach ($operacoes as $operacao) {
            $mes = Carbon::parse($operacao->date_at)->month;
            if ($operacao->type === 'R') {
                $dadosMensais[$mes]['receita'] += $operacao->motante;
                $totalReceita += $operacao->motante;
            } else if ($operacao->type === 'D') {
                $dadosMensais[$mes]['despesa'] += $operacao->motante;
                $totalDespesa += $operacao->motante;
            }

            // Calcula o saldo
            $dadosMensais[$mes]['saldo'] = $dadosMensais[$mes]['receita'] - $dadosMensais[$mes]['despesa'];
        }
        
        
        // Calcula o saldo anual
        $totalSaldo = $totalReceita - $totalDespesa;

        
        // Retorna os dados formatados para o frontend
        return response()->json([
            'mensal' => $dadosMensais,
            'totais' => [
                'receita' => $totalReceita,
                'despesa' => $totalDespesa,
                'saldo' => $totalSaldo,
            ],
        ]);

    }
    
    public function graficoReceitas(Request $requst)
    {
        // Consulta as receitas e soma os valores de cada categoria
        $dados = Receita::with(['operacoes' => function($query){
            $query->where('status_pagamento', 'pago');
        }])->where('type', 'R')
            ->get()
            ->map(function ($receita) {
                return [
                    'nome' => $receita->nome,
                    'total' => $receita->operacoes->sum('motante'),
                ];
            });

        return response()->json($dados);
    }

    
    public function graficoDespesas(Request $requst)
    {
        // Consulta as receitas e soma os valores de cada categoria
        $dados = Dispesa::with(['operacoes' => function($query){
            $query->where('status_pagamento', 'pago');
        }])->where('type', 'D')
            ->get()
            ->map(function ($dispesa) {
                return [
                    'nome' => $dispesa->nome,
                    'total' => $dispesa->operacoes->sum('motante'),
                ];
            });

        return response()->json($dados);
    }
     
    
    public function graficoSaldos(Request $requst)
    {
        $anoAtual = now()->year;
        
        // Cria uma estrutura padrão para os meses
        $meses = collect(range(1, 12))->mapWithKeys(function ($mes) {
            return [$mes => [
                'mes' => $mes,
                'total_receita' => 0,
                'total_despesa' => 0,
                'saldo' => 0
            ]];
        });

        // Busca os dados reais agrupados por mês
        $dados = OperacaoFinanceiro::selectRaw('
            MONTH(date_at) as mes,
            SUM(CASE WHEN type = "R" THEN motante ELSE 0 END) as total_receita,
            SUM(CASE WHEN type = "D" THEN motante ELSE 0 END) as total_despesa
        ')
        ->where('status_pagamento', ['pago'])
        ->whereYear('date_at', $anoAtual)
        ->groupBy('mes')
        ->get();

        // Atualiza os meses padrão com os dados reais
        $meses = $meses->map(function ($item) use ($dados) {
            $mesReal = $dados->firstWhere('mes', $item['mes']);
            if ($mesReal) {
                $item['total_receita'] = $mesReal->total_receita;
                $item['total_despesa'] = $mesReal->total_despesa;
                $item['saldo'] = $mesReal->total_receita - $mesReal->total_despesa;
            }
            return $item;
        });
        return response()->json($meses->values());
    }   
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //
        $user = auth()->user();
        
        if(!$user->can('operacao financeira')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $operacao = OperacaoFinanceiro::with(['subconta', 'fornecedor','cliente','dispesa', 'caixa','contabancaria','receita','user' ,'entidade'])->findOrFail($id);
               
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        if($operacao->type == "R"){
            $tipos = Receita::where('type', 'R')->get();
        }
        
        if($operacao->type == "D"){
            $tipos = Dispesa::where('type', 'D')->get();
        }
        
        $caixas = Caixa::where('entidade_id', '=', $entidade->empresa->id)->get();
        $bancos = ContaBancaria::where('entidade_id', '=', $entidade->empresa->id)->get();
        
        $formas_pagamentos = TipoPagamento::get();
        $clientes = Cliente::where('entidade_id', '=', $entidade->empresa->id)->get();
        $fornecedores = Fornecedore::where('entidade_id', '=', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Registro de {$request->tipo}",
            "descricao" => env('APP_NAME'),
            "tipos" => $tipos,
            "formas_pagamentos" => $formas_pagamentos,
            "clientes" => $clientes,
            "fornecedores" => $fornecedores,
            "caixas" => $caixas,
            "bancos" => $bancos,
            "operacao" => $operacao,
            // "requests" => $request->all('tipo'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.operacao-financeira.show', $head);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function imprimir(Request $request, $id)
    {
        //
        $user = auth()->user();
        
        if(!$user->can('operacao financeira')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $operacao = OperacaoFinanceiro::with(['subconta', 'fornecedor','cliente','dispesa', 'caixa','contabancaria','receita','user' ,'entidade'])->findOrFail($id);
        $itemOperacoes = OperacaoFinanceiro::with(['subconta', 'fornecedor','cliente','dispesa', 'caixa','contabancaria','receita','user' ,'entidade'])->where('code', $operacao->code)->get();
               
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        if($operacao->type == "R"){
            $titulo = "REGISTRO DE RECEITA";
        }
        
        if($operacao->type == "D"){
            $titulo = "REGISTRO DE DESPESA";
        }
    
        $head = [
            'titulo' => $titulo,
            'descricao' => env('APP_NAME'),
            "operacao" => $operacao,
            "entidade" => $entidade,
            "itemOperacoes" => $itemOperacoes,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.operacao-financeira.imprimir', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();

    }

    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //
        $user = auth()->user();
        
        if(!$user->can('operacao financeira')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $operacao = OperacaoFinanceiro::findOrFail($id);
               
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        if($operacao->type == "R"){
            $tipos = Receita::where('type', 'R')->get();
        }
        
        if($operacao->type == "D"){
            $tipos = Dispesa::where('type', 'D')->get();
        }
        
        $caixas = Caixa::where('entidade_id', '=', $entidade->empresa->id)->get();
        $bancos = ContaBancaria::where('entidade_id', '=', $entidade->empresa->id)->get();
        
        $formas_pagamentos = TipoPagamento::get();
        $clientes = Cliente::where('entidade_id', '=', $entidade->empresa->id)->get();
        $fornecedores = Fornecedore::where('entidade_id', '=', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Registro de {$request->tipo}",
            "descricao" => env('APP_NAME'),
            "tipos" => $tipos,
            "formas_pagamentos" => $formas_pagamentos,
            "clientes" => $clientes,
            "fornecedores" => $fornecedores,
            "caixas" => $caixas,
            "bancos" => $bancos,
            "operacao" => $operacao,
            // "requests" => $request->all('tipo'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.operacao-financeira.edit', $head);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        if(!$user->can('operacao financeira')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'tipo_id' => 'required|string',
            'motante' => 'required|string',
        ],[
            'tipo_id.required' => 'O tipo é um campo obrigatório',
            'motante.required' => 'O motante é um campo obrigatório',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $operacao = OperacaoFinanceiro::findOrFail($id);
            
            $operacao->nome = $request->referencia;
            $operacao->status = $request->status_pagamento;
            $operacao->motante = $request->motante;
            $operacao->caixa_id = $request->caixa_id;
            $operacao->banco_id = $request->banco_id;
            $operacao->cliente_id = $request->cliente_id;
            $operacao->fornecedor_id = $request->fornecedor_id;
            $operacao->model_id = $request->tipo_id;
            $operacao->status_pagamento = $request->status_pagamento;
            $operacao->parcelado = $request->parcelado;
            $operacao->parcelas = $request->parcelas;
            $operacao->data_recebimento = $request->data_recebimento;
            $operacao->data_pagamento = $request->data_pagamento;
            $operacao->forma_recebimento_id = $request->forma_recebimento_id;
            $operacao->forma_pagamento_id = $request->forma_pagamento_id;
            $operacao->descricao = $request->descricao;
            $operacao->date_at = $request->data_pagamento ?? $request->data_recebimento;
            
            $operacao->update();
            
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function recuperar($id)
    {
        //
        $user = auth()->user();
        
        if(!$user->can('operacao financeira')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        //
        try {
            DB::beginTransaction();
            $registro = OperacaoFinanceiro::onlyTrashed()->find($id);
            if ($registro) {
                $registro->restore();
            }
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        
        return response()->json(['success' => true, 'message' => "Dados recuperados com sucesso!"], 200);
      
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
        
        if(!$user->can('eliminar dispesa')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $operacao = OperacaoFinanceiro::findOrFail($id);
            $operacao->delete();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        return response()->json(['success' => true, 'message' => "Dados Excluídos com sucesso!"], 200);
    }

}
