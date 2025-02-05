<?php

namespace App\Http\Controllers\app\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Models\Caixa;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Conta;
use App\Models\ContaBancaria;
use App\Models\Entidade;
use App\Models\Exercicio;
use App\Models\Contrapartida;
use App\Models\Fornecedore;
use App\Models\Loja;
use App\Models\Movimento;
use App\Models\MovimentoCaixa;
use App\Models\OperacaoFinanceiro;
use App\Models\Periodo;
use App\Models\Produto;
use App\Models\Receita;
use App\Models\Subconta;
use App\Models\TipoCredito;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;


use PDF;

class CaixaController extends Controller
{
    //
    use TraitHelpers;
    
    public function caixas()
    {
        $user = auth()->user();
    
        if(!$user->can('listar caixa')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
       
        $head = [
            "titulo" => "Caixas",
            "descricao" => env('APP_NAME'),
            "categorias" => Categoria::with('produtos.marca', 'produtos.variacao')->where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "loja" => Entidade::findOrFail($entidade->empresa->id),
            "caixas" => Caixa::where([
                ['entidade_id', '=', $entidade->empresa->id],
                ['user_id', '=', Auth::user()->id],
            ])
            ->with('loja')
            ->get(),
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.pronto-venda.caixas.caixas', $head);
    }

    public function caixasCreateUpdate(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('listar caixa')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        // entidade ou empresa logada
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $veririficar_caixa = Caixa::findOrFail($request->caixa);
        
        if($veririficar_caixa && $veririficar_caixa->active == true && $veririficar_caixa->status == 'aberto') {
            Alert::error('Erro', 'Não é possível abrir este caixa, pois ele já está em uso.');
            return redirect()->back()->with('danger', "Não é possível abrir este caixa, pois ele já está em uso.");
        }
        
        // verificar caixa aberto
        $caixaActivo = Caixa::where([
            ['active', true],
            ['entidade_id', '=', $entidade->empresa->id],
            ['status', 'aberto'],
            ['user_id', '=', Auth::user()->id],
        ])->first();

        // fechar o caixa aberto
        if($caixaActivo){
           // encontrar os movimento ja feiro enqunto o caixa estava aberto e fechar este movimento para abrir do outro
            $movimentos = Movimento::where('entidade_id', $entidade->empresa->id)
                ->where('code_caixa', $caixaActivo->code)
                ->where('caixa_id', $caixaActivo->id)
                ->where('user_id', Auth::user()->id)
                ->where('status_caixa', 1)
                ->with(['user','caixa'])
                ->where('entidade_id', '=', $entidade->empresa->id)
                ->get();
                
            foreach($movimentos as $item){
                $up = Movimento::findOrFail($item->id);
                $up->status_caixa = 0;
                $up->update();
            }
        }

        $caixas = Caixa::where([
            ['active', true],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->get();

        foreach ($caixas as $item) {
            $caixa = Caixa::findOrFail($item->id);
            $caixa->active = false;
            $caixa->continuar_apos_login = false;
            $caixa->code_caixa = NULL;
            $caixa->status = "fachado";
            $caixa->update();
        }

        // encontrar o caixa selecionado
        $caixa = Caixa::findOrFail($request->caixa);
        $caixa->active = true;
        $caixa->status = "aberto";
        $caixa->continuar_apos_login = true;
        $caixa->code_caixa = uniqid(time());
        $caixa->user_id = Auth::user()->id;
        $caixa->update();

        return redirect()->route('pronto-venda');
    }
    
    public function abertura_caixa()
    {
        $user = auth()->user();
     
        if(!$user->can('abertura do caixa')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);    
        
        $caixas = Caixa::where('active', false)->where('status', 'fechado')->where('entidade_id', '=', $entidade->empresa->id)->get();
                
        if(count($caixas) === 0){
            Alert::warning('Alerta!', 'Já Existe Caixa Aberto no Momento!');
            return redirect()->route('pronto-venda');
        }
        
        $caixaActivo = Caixa::where([
            ['active', true],
            ['user_id', '=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->first();
        
       if($caixaActivo){
            Alert::error('Erro', 'Não podes ter duas contas aberta no mesmo instante!');
            return redirect()->back();
        }

        $head = [
            "titulo" => "Abertura de Caixa",
            "descricao" => env('APP_NAME'),
            "caixas" => $caixas,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.vendas.caixas.abertura', $head);
    }

    public function abertura_caixa_create(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('abertura do caixa')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $request->validate([
            'valor' => 'required|string',
            'caixa_id' => 'required|string',
        ],[
            'valor.required' => 'o valor é um campo obrigatório',
            'caixa_id.required' => 'o caixa é um campo obrigatório',
        ]);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $code = uniqid(time());
        
            $caixaActivo = Caixa::findOrFail($request->caixa_id);
            
            if($entidade->empresa->tem_permissao("Gestão Contabilidade")){
            
                $exercicio = Exercicio::findOrFail($this->exercicio());
                $periodo = Periodo::where('exercicio_id', $exercicio->id)->orderBy('id', 'desc')->first();
                
                $subconta = Subconta::where('entidade_id' , $entidade->empresa->id)->where('code', $caixaActivo->code)->first();
                  
                if($subconta){
                    Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $subconta->id,
                        'exercicio_id' => $exercicio->id,
                        'periodo_id' => $periodo->id,
                        'status' => true,
                        'movimento' => 'E',
                        'observacao' => 'Saldo Inicial - Diario',
                        'credito' => 0,
                        'debito' => $request->valor,
                        'code' => $code,
                        'data_at' => date("Y-m-d"),
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                }else {
                    return response()->json(['message' => "Por favor, verifica se esta caixa foi criado correctamente, actualiza este caixa. !"], 404);
                }
                
            }else{
            
                $subconta = Caixa::findOrFail($request->caixa_id);
            }
            
            $caixaActivo->status = "aberto";
            $caixaActivo->active = true;
            $caixaActivo->user_open_id = Auth::user()->id;
            $caixaActivo->continuar_apos_login = true;
            $caixaActivo->update();
            
            $cliente = NULL;
            
            $this->registra_operacoes(
                $request->valor, // valor da operação
                $subconta->id, // conta a ser movimentada EX: caixa / banco ou qualquer outra conta da contabilidade
                $cliente, // cliente que 
                "R", // tipo de operação se é receita ou dispesa
                "pago",  // Status da operação se esta paga ou não
                $code, // code => rash para esta operação
                "E", // tipo de movimento se em Entrada ou saída
                date("Y-m-d"), // data da operação
                $entidade->empresa->id, // empresa que esta a fazer a operação
                "Saldo Inicial - Abertura caixa (Entrada de Valores)",
                Auth::user()->id, // user_open
                'pendente', // status do caixa / pendente - concluido
                "C", // forma de entrada ou saída de valores Ex: Multicaixa ou Cash
                $caixaActivo->code_caixa, // code que identifica o caixa que esta operar,
            );
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        
        return response()->json(['message' => "Caixa Aberto com sucesso", 'success' => true, 'redirect' => route('pronto-venda')]);
        
    }

    public function entrada_dinheiro_caixa()
    {
        $user = auth()->user();
        
        if(!$user->can('entrada valor no caixa')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $contas = Conta::where('conta', '45')->where('entidade_id', '=', $entidade->empresa->id)->pluck('id');
        $subcontas = Subconta::with(['conta'])->whereIn('conta_id', $contas)->get();

        $clientes = Cliente::where('entidade_id', '=', $entidade->empresa->id)->get();
        $fornecedores = Fornecedore::where('entidade_id', '=', $entidade->empresa->id)->get();
        $caixas = Caixa::where('entidade_id', $entidade->empresa->id)->get();
        $bancos = ContaBancaria::where('entidade_id', $entidade->empresa->id)->get();
        
        
        $contas_proveitos = Conta::whereIn('conta', ['61', '62', '63'])->where('entidade_id', '=', $entidade->empresa->id)->pluck('id');
        $proveitos = Subconta::with(['conta'])->whereIn('conta_id', $contas_proveitos)->orderBy('numero', 'asc')->get();
        
        $contrapartia = Contrapartida::with(['subconta'])->where('entidade_id', '=', $entidade->empresa->id)->get();
        
        $tipos_creditos = TipoCredito::where('entidade_id', '=', $entidade->empresa->id)->get();
        // $proveitos = Receita::where('type', 'R')->where('entidade_id', '=', $entidade->empresa->id)->get();
 
        $exercicios = Exercicio::where('id', $this->exercicio())->get();
        $periodos = Periodo::where('exercicio_id', '=', $this->exercicio())->get();    
        
        $head = [
            "titulo" => "Entrada de dinheiro no Caixa",
            "descricao" => env('APP_NAME'),
            "categorias" => Categoria::with('produtos.marca', 'produtos.variacao')->where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "loja" => Entidade::findOrFail($entidade->empresa->id),
            "subcontas" => $subcontas,
            "contrapartias" => $contrapartia,
            "tipos_creditos" => $tipos_creditos,
            "exercicios" => $exercicios,
            "periodos" => $periodos,
            "proveitos" => $proveitos,
            "clientes" => $clientes,
            "fornecedores" => $fornecedores,
            "caixas" => $caixas,
            "bancos" => $bancos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.vendas.caixas.entrada-dinheiro', $head);
    }  

    public function entrada_dinheiro_caixa_create(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('entrada valor no caixa')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'montante' => 'required',
            'tipo_movimento_id' => 'required',
        ],[
            'montante.required' => 'Informe o motante da operação é obrigatório',
            'tipo_movimento_id.required' => 'Selecione o tipo de movimento é obrigatório',
        ]);
          
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
         
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
      
            $code = uniqid(time());
            // $tipo_proveito = Receita::findOrFail($request->tipo_proveito_id);
              
            
            if($request->tipo_movimento_id == "C"){
                
                $subconta = Subconta::findOrFail($request->contrapartida_id);
                $fornecedor = Fornecedore::findOrFail($request->fornecedor_id);
                $subconta_fornecedor = Subconta::where('code', $fornecedor->code)->firstOrFail();
                
                if($request->marcar_como == "sim"){
                    $status = "pago";
                }else {
                    $status = "pendente";
                }
                              
                if($request->forma_pagamento_id == ""){
                    return redirect()->back()->with('danger', 'Deves selecionar uma forma de pagamento da factura!');
                }
                
                if($request->forma_pagamento_id == "NU"){
                    if($request->caixa_id == ""){
                        return redirect()->back()->with('danger', 'Deves selecionar o caixa onde será retirado o valor para o pagamento da factura!');
                    }
                    $subconta_saida = Subconta::where('code', $request->caixa_id)->first();
                    $formas = "C";
                }
                if($request->forma_pagamento_id == "MB"){
                    if($request->banco_id == ""){
                        return redirect()->back()->with('danger', 'Deves selecionar o banco onde será retirado o valor para o pagamento da factura!');
                    }
                    $subconta_saida = Subconta::where('code', $request->banco_id)->first();
                    $formas = "B";
                }
              
                OperacaoFinanceiro::create([
                    'nome' => $request->observacao ?? "PAGAMENTO DE {$subconta->nome}",
                    'status' => $status,
                    'formas' => $formas,
                    'motante' => $request->montante,
                    'subconta_id' => $subconta->id,
                    'fornecedor_id' => $fornecedor->id,
                    'model_id' => 12,
                    'type' => 'D',
                    'parcelado' => "N",
                    'status_pagamento' => $status,
                    'code' => $code,
                    'descricao' => $request->observacao ?? "PAGAMENTO DE {$subconta->nome}",
                    'movimento' => "S",
                    'date_at' => $request->date_at,
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $request->exercicio_id,
                    'periodo_id' => 13,
                ]);
              
                if($request->operacao_id == "A"){
                    
                    $conta_encargo_pagar = Subconta::where('numero', ENV('ENCARGOS_A_PAGAR'))->first();
                     
                    $total_parcela = count($request->periodo_id);
                    
                    foreach($request->periodo_id as $item) {
                        
                        $valor_parcela = $request->montante / $total_parcela;
  
                        ## - creditamos encargos a pagar
                        $movimeto = Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $conta_encargo_pagar->id,
                            'status' => true,
                            'movimento' => 'S',
                            'credito' => $valor_parcela,
                            'debito' => 0,
                            'observacao' => $request->observacao,
                            'code' => $code,
                            'data_at' => $request->date_at,
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $request->exercicio_id,
                            'periodo_id' =>  $item,
                        ]);
                          
                        ## debitamos na conta dos serviço a ser pago ou seja a contrapartida
                        $movimeto = Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $subconta->id,
                            'status' => true,
                            'movimento' => 'E',
                            'credito' => 0,
                            'debito' => $valor_parcela,
                            'observacao' => $request->observacao,
                            'code' => $code,
                            'data_at' => $request->date_at,
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $request->exercicio_id,
                            'periodo_id' =>  $item,
                        ]);
                    }
                    
                    ## MOMENTO DO PAGAMENTO
                    
                    ## vamos creditar no caixa onde esta sair o dinheiro
                    if($request->marcar_como == "sim"){
                        $movimeto = Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $subconta_saida->id,
                            'status' => true,
                            'movimento' => 'S',
                            'credito' => $request->montante ?? 0,
                            'debito' => 0,
                            'observacao' => $request->observacao,
                            'code' => $code,
                            'data_at' => $request->date_at,
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $request->exercicio_id,
                            'periodo_id' => 13,
                        ]);
                    }
                    
                    ## vamos anula a conta de custo ou seja encargos a pagar
                    $movimeto = Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $conta_encargo_pagar->id,
                        'status' => true,
                        'movimento' => 'E',
                        'credito' => 0,
                        'debito' => $request->montante ?? 0,
                        'observacao' => $request->observacao,
                        'code' => $code,
                        'data_at' => $request->date_at,
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $request->exercicio_id,
                        'periodo_id' => 13,
                    ]);
                    
                    // Regitra dados com o fornecedor - SAIDA
                    $movimeto = Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $subconta_fornecedor->id,
                        'status' => true,
                        'movimento' => 'S',
                        'credito' => $request->montante ?? 0,
                        'debito' => 0,
                        'observacao' => $request->observacao,
                        'code' => $code,
                        'data_at' => $request->date_at,
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $request->exercicio_id,
                        'periodo_id' => 13,
                    ]);
                    
                    if($request->marcar_como == "sim"){
                        // Regitra dados com o fornecedor ENTRADA
                        $movimeto = Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $subconta_fornecedor->id,
                            'status' => true,
                            'movimento' => 'E',
                            'credito' => 0,
                            'debito' => $request->montante ?? 0,
                            'observacao' => $request->observacao,
                            'code' => $code,
                            'data_at' => $request->date_at,
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $request->exercicio_id,
                            'periodo_id' => 13,
                        ]);
                    }
                
                }
                
                if($request->operacao_id == "D"){
                    
                    $subconta_ = Subconta::where('numero', ENV('ENCARGOS_A_REPARTIR_POR_PERIODO_FUTURO'))->first();
                  
                    $total_parcela = count($request->periodo_id);
                    
                    foreach($request->periodo_id as $item) {
                        
                        $valor_parcela = $request->montante / $total_parcela;
                        
                        ## - creditamos encargos a pagar
                        $movimeto = Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $subconta_->id,
                            'status' => true,
                            'movimento' => 'S',
                            'credito' => $valor_parcela ?? 0,
                            'debito' => 0,
                            'observacao' => $request->observacao,
                            'code' => $code,
                            'data_at' => $request->date_at,
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $request->exercicio_id,
                            'periodo_id' => $item,
                        ]);
                        
                        ## debitamos na conta dos serviço a ser pago ou seja a contrapartida
                        $movimeto = Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $subconta->id,
                            'status' => true,
                            'movimento' => 'E',
                            'credito' => 0,
                            'debito' => $valor_parcela ?? 0,
                            'observacao' => $request->observacao,
                            'code' => $code,
                            'data_at' => $request->date_at,
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $request->exercicio_id,
                            'periodo_id' => $item,
                        ]);
                        
                    }
                    
                    ## vamos creditar no caixa onde esta sair o dinheiro
                    $movimeto = Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $request->subconta_id,
                        'status' => true,
                        'movimento' => 'S',
                        'credito' => $request->montante ?? 0,
                        'debito' => 0,
                        'observacao' => $request->observacao,
                        'code' => $code,
                        'data_at' => $request->date_at,
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $request->exercicio_id,
                        'periodo_id' => 13,
                    ]);
                    
                    ## vamos anula a conta de custo ou seja Encargos a repartir por períodos futuros
                    $movimeto = Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $subconta_->id,
                        'status' => true,
                        'movimento' => 'E',
                        'credito' => 0,
                        'debito' => $request->montante ?? 0,
                        'observacao' => $request->observacao,
                        'code' => $code,
                        'data_at' => $request->date_at,
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $request->exercicio_id,
                        'periodo_id' => 13,
                    ]);
                    
                }
            
            }else if($request->tipo_movimento_id == "D"){
                 
                $valor_total_factura = 0;
                
                foreach($request->tipo_proveito_id as $item){
                    $subconta_iva = Subconta::where('numero', ENV('IVA_LIQUIDADO'))->first();
                    $proveito = Subconta::findOrFail($item);
                    $produto_servico = Produto::where('code', $proveito->code)->first(); 
                    
                    if($request->operacao_id == "A"){
                    
                    }
                    
                    if($request->operacao_id == "D"){
                              
                        $subconta_ = Subconta::where('numero', ENV('PROVEITOS_A_REPARTIR_POR_PERIDOS_FUTUROS'))->first();
                      
                        $total_parcela = count($request->periodo_id);
                    }
                    
                    if($produto_servico){
                        // caso o serviço/produto cobrar IVA
                        if($produto_servico->taxa != 0){
                            if($subconta_iva){
                                
                                ## creditar na conta proveito - 61/62/63/65 - ou seja diminuir o valor sem o iva
                                $movimeto = Movimento::create([
                                    'user_id' => Auth::user()->id,
                                    'subconta_id' => $proveito->id,
                                    'status' => true,
                                    'movimento' => 'S',
                                    'credito' => $produto_servico->preco ?? 0,
                                    'debito' => 0,
                                    'observacao' => $request->observacao,
                                    'code' => $code,
                                    'data_at' => $request->date_at,
                                    'entidade_id' => $entidade->empresa->id,
                                    'exercicio_id' => $request->exercicio_id,
                                    'periodo_id' => $request->periodo_id,
                                ]);
                                
                                ## creditar na conta do IVA LIQUIDADO - 34.5.3.1
                                $movimeto = Movimento::create([
                                    'user_id' => Auth::user()->id,
                                    'subconta_id' => $subconta_iva->id,
                                    'status' => true,
                                    'movimento' => 'S',
                                    'credito' => ($produto_servico->preco_venda ?? 0) - ($produto_servico->preco??0),
                                    'debito' => 0,
                                    'observacao' => $request->observacao,
                                    'code' => $code,
                                    'data_at' => $request->date_at,
                                    'entidade_id' => $entidade->empresa->id,
                                    'exercicio_id' => $request->exercicio_id,
                                    'periodo_id' => $request->periodo_id,
                                ]);
                                
                                ## creditar e debitar na conta 31 ou seja preciso aumentar a divida do clientes e depois liquidar da mesma divida
                                ## START
                                $movimeto = Movimento::create([
                                    'user_id' => Auth::user()->id,
                                    'subconta_id' => $request->cliente_id,
                                    'status' => true,
                                    'movimento' => 'E',
                                    'credito' => 0,
                                    'debito' => $produto_servico->preco_venda??0,
                                    'observacao' => $request->observacao,
                                    'code' => $code,
                                    'data_at' => $request->date_at,
                                    'entidade_id' => $entidade->empresa->id,
                                    'exercicio_id' => $request->exercicio_id,
                                    'periodo_id' => $request->periodo_id,
                                ]);
                                
                                $movimeto = Movimento::create([
                                    'user_id' => Auth::user()->id,
                                    'subconta_id' => $request->cliente_id,
                                    'status' => true,
                                    'movimento' => 'E',
                                    'credito' => $produto_servico->preco_venda??0,
                                    'debito' => 0,
                                    'observacao' => $request->observacao,
                                    'code' => $code,
                                    'data_at' => $request->date_at,
                                    'entidade_id' => $entidade->empresa->id,
                                    'exercicio_id' => $request->exercicio_id,
                                    'periodo_id' => $request->periodo_id,
                                ]);
                                ## - END
                                ## vamor aumentar o valor do caixa - 45/43
                                $movimeto = Movimento::create([
                                    'user_id' => Auth::user()->id,
                                    'subconta_id' => $request->subconta_id,
                                    'status' => true,
                                    'movimento' => 'E',
                                    'credito' => 0,
                                    'debito' => $produto_servico->preco_venda??0,
                                    'observacao' => $request->observacao,
                                    'code' => $code,
                                    'data_at' => $request->date_at,
                                    'entidade_id' => $entidade->empresa->id,
                                    'exercicio_id' => $request->exercicio_id,
                                    'periodo_id' => $request->periodo_id,
                                ]);
                                
                                
                            }else{
                                ## a conta do iva não esta cadastrada
                            }
                        }else {
                            ## caso o serviço/produto não cobra o iva ou 
                            
                            ## creditar na conta proveito - 61/62/63/65 - ou seja diminuir o valor sem o iva
                            $movimeto = Movimento::create([
                                'user_id' => Auth::user()->id,
                                'subconta_id' => $proveito->id,
                                'status' => true,
                                'movimento' => 'S',
                                'credito' => $produto_servico->preco ?? 0,
                                'debito' => 0,
                                'observacao' => $request->observacao,
                                'code' => $code,
                                'data_at' => $request->date_at,
                                'entidade_id' => $entidade->empresa->id,
                                'exercicio_id' => $request->exercicio_id,
                                'periodo_id' => $request->periodo_id,
                            ]);
                            
                            ## creditar e debitar na conta 31 ou seja preciso aumentar a divida do clientes e depois liquidar da mesma divida
                            ## START
                            $movimeto = Movimento::create([
                                'user_id' => Auth::user()->id,
                                'subconta_id' => $request->cliente_id,
                                'status' => true,
                                'movimento' => 'E',
                                'credito' => 0,
                                'debito' => $produto_servico->preco_venda??0,
                                'observacao' => $request->observacao,
                                'code' => $code,
                                'data_at' => $request->date_at,
                                'entidade_id' => $entidade->empresa->id,
                                'exercicio_id' => $request->exercicio_id,
                                'periodo_id' => $request->periodo_id,
                            ]);
                            
                            $movimeto = Movimento::create([
                                'user_id' => Auth::user()->id,
                                'subconta_id' => $request->cliente_id,
                                'status' => true,
                                'movimento' => 'E',
                                'credito' => $produto_servico->preco_venda??0,
                                'debito' => 0,
                                'observacao' => $request->observacao,
                                'code' => $code,
                                'data_at' => $request->date_at,
                                'entidade_id' => $entidade->empresa->id,
                                'exercicio_id' => $request->exercicio_id,
                                'periodo_id' => $request->periodo_id,
                            ]);
                            ## - END
                            ## vamor aumentar o valor do caixa - 45/43
                            $movimeto = Movimento::create([
                                'user_id' => Auth::user()->id,
                                'subconta_id' => $request->subconta_id,
                                'status' => true,
                                'movimento' => 'E',
                                'credito' => 0,
                                'debito' => $produto_servico->preco_venda??0,
                                'observacao' => $request->observacao,
                                'code' => $code,
                                'data_at' => $request->date_at,
                                'entidade_id' => $entidade->empresa->id,
                                'exercicio_id' => $request->exercicio_id,
                                'periodo_id' => $request->periodo_id,
                            ]);
                            
                        }
                        $valor_total_factura += $produto_servico->preco_venda;
                    }else {
                        ## Servico o produto não cadastrado correntamente
                    }
                    
                    if($produto_servico){
                        if($produto_servico->tipo == "S"){
                            OperacaoFinanceiro::create([
                                'nome' => "PRESTAÇÃO DE SERVIÇO",
                                'status' => "pago",
                                'motante' => $produto_servico->preco_venda,
                                'subconta_id' => $request->subconta_id,
                                'cliente_id' => $request->cliente_id,
                                'model_id' => 4,
                                'type' => 'R',
                                'parcelado' => "N",
                                'status_pagamento' => "pago",
                                'code' => $code,
                                'descricao' => "PRESTAÇÃO DE SERVIÇO",
                                'movimento' => "E",
                                'date_at' => $request->date_at,
                                'user_id' => Auth::user()->id,
                                'entidade_id' => $entidade->empresa->id,
                                'exercicio_id' => $request->exercicio_id,
                                'periodo_id' => $request->periodo_id,
                            ]);
                        }else if($produto_servico->tipo == "P"){
                            OperacaoFinanceiro::create([
                                'nome' => "VENDA DE PRODUTOS",
                                'status' => "pago",
                                'motante' => $produto_servico->preco_venda,
                                'subconta_id' => $request->subconta_id,
                                'cliente_id' => $request->cliente_id,
                                'model_id' => 3,
                                'type' => 'R',
                                'parcelado' => "N",
                                'status_pagamento' => "pago",
                                'code' => $code,
                                'descricao' => "VENDA DE PRODUTOS",
                                'movimento' => "E",
                                'date_at' => $request->date_at,
                                'user_id' => Auth::user()->id,
                                'entidade_id' => $entidade->empresa->id,
                                'exercicio_id' => $request->exercicio_id,
                                'periodo_id' => $request->periodo_id,
                            ]);
                        }else {
                            OperacaoFinanceiro::create([
                                'nome' => "OUTRAS RECEITAS",
                                'status' => "pago",
                                'motante' => $produto_servico->preco_venda,
                                'subconta_id' => $request->subconta_id,
                                'cliente_id' => $request->cliente_id,
                                'model_id' => 7,
                                'type' => 'R',
                                'parcelado' => "N",
                                'status_pagamento' => "pago",
                                'code' => $code,
                                'descricao' => "OUTRAS RECEITAS",
                                'movimento' => "E",
                                'date_at' => $request->date_at,
                                'user_id' => Auth::user()->id,
                                'entidade_id' => $entidade->empresa->id,
                                'exercicio_id' => $request->exercicio_id,
                                'periodo_id' => $request->periodo_id,
                            ]);
                        }
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

        return redirect()->back()->with('success', "Operação realizada com sucesso!");
        // return redirect()->route('nota-de-movimento', $movimeto->code);

    }

    public function saida_dinheiro_caixa()
    {
        $user = auth()->user();
        
        if(!$user->can('saida valor no caixa')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $caixaActivo = Caixa::where([
            ['active', true],
            ['user_id', '=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->first();

        if(!$caixaActivo){
            Alert::error('Erro', 'Verifica se tens um caixa aberto, por favor!');
            return redirect()->back();
        }
        
        $caixas = Caixa::where('active', true)->where('status', 'aberto')->where('entidade_id', '=', $entidade->empresa->id)->get();

        $head = [
            "titulo" => "Saída de dinheiro no Caixa",
            "descricao" => env('APP_NAME'),
            "categorias" => Categoria::with('produtos.marca', 'produtos.variacao')->where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "loja" => Entidade::findOrFail($entidade->empresa->id),
            "caixaActivo" => $caixaActivo,
            "caixas" => $caixas,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.vendas.caixas.saida-dinheiro', $head);
    }  

    public function saida_dinheiro_caixa_create(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('saida valor no caixa')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'montante' => 'required',
            'caixa_id' => 'required',
        ],[
            'montante.required' => 'O motante é um campo obrigatório',
            'caixa_id.required' => 'O caixa é um campo obrigatório',
        ]);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $numero = Movimento::where('entidade_id' , $entidade->empresa->id)->where('movimento', 'S')->count() + 1;
              
        try {
            DB::beginTransaction();
            
            $caixaActivo = Caixa::findOrFail($request->caixa_id);
            
            // Realizar operações de banco de dados aqui
            $code = uniqid(time());
            $movimeto = Movimento::create([
                'user_id' => Auth::user()->id,
                'caixa_id' => $caixaActivo->id,
                'status' => true,
                'movimento' => 'S',
                'numero' => "NOTA Nº {$numero}/{$entidade->empresa->ano_factura}",
                'credito' => $request->montante,
                'debito' => 0,
                'observacao' => $request->observacao,
                'code' => $code,
                'code_caixa' => $caixaActivo->code,
                'status_caixa' => 1,
                'forma_movimento' => "NU",
                'data_at' => date("Y-m-d"),
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

        return redirect()->route('nota-de-movimento', $movimeto->code);


    }

    public function movimentos_caixa(Request $request)
    {
        $user = auth()->user();
        
        // if(!$user->can('movimento no caixa')){
        //     Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        //     return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $users = User::where('entidade_id', '=', $entidade->empresa->id)->get();
        $caixas = Caixa::where('entidade_id', '=', $entidade->empresa->id)->get();

        $movimentos = Movimento::where('entidade_id', $entidade->empresa->id)
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('data_at', '>=', Carbon::createFromDate($value));
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('data_at', '<=', Carbon::createFromDate($value));
        })
        ->when($request->caixa_id , function($query, $value){
            $query->where('caixa_id', $value);
        })
        ->when($request->operador_id , function($query, $value){
            $query->where('user_id', $value);
        })
        ->with(['user','caixa'])
        ->where('entidade_id', '=', $entidade->empresa->id)
        // ->where('entidade_id', '=', $entidade->empresa->id)
        ->get();
        
        
        
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);
        
        $head = [
            "titulo" => "Movimentos do caixa",
            "descricao" => env('APP_NAME'),
            "loja" => Entidade::findOrFail($entidade->empresa->id),
            "movimentos" => $movimentos,
            "users" => $users,
            "caixas" => $caixas,
            "empresa" => $empresa,
            "requests" => $request->all('data_inicio', 'data_final', 'operador_id', 'caixa_id'), 
            "user" => User::find($request->operador_id),
            "caixa" => Caixa::find($request->caixa_id),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];    
        
        if($request->documento_pdf === "exportar_pdf"){
        
            $pdf = PDF::loadView('dashboard.vendas.caixas.movimentos-pdf', $head);
            $pdf->setPaper('A4', 'portrait');
    
            return $pdf->stream();
        }else{
            return view('dashboard.vendas.caixas.movimentos', $head);
        }

    } 

    public function movimentos_imprimir(Request $request)
    {
        $movimento = MovimentoCaixa::with(['user', 'caixa'])->findOrFail($request->id_imprimir);
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);
        
        $head = [
            "titulo" => "Movimento do caixa Detalhado",
            "descricao" => env('APP_NAME'),
            "loja" => Entidade::findOrFail($entidade->empresa->id),
            "movimento" => $movimento,
            "empresa" => $empresa,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];    
    
        $pdf = PDF::loadView('dashboard.vendas.caixas.movimentos-detalhe-pdf', $head);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->stream();

    } 

    public function fechamento_caixa($id = null)
    {
        $user = auth()->user();
        
        if(!$user->can('fecho do caixa')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa.tipo_entidade.modulos')->findOrFail(Auth::user()->id);
        
        $caixaActivo = Caixa::find($id);
        if(!$caixaActivo){
            return response()->json(['message' => 'Verificar o caixa que pretendes fechar, por favor'], 404);
        }
        if($caixaActivo->status == "fechado" && $caixaActivo->active == false){
            return redirect()->route('dashboard');
        }
        
        $credito = 0;
        $debito = 0;
                     
        $multicaixa = 0;
        $multicaixa_credito = 0;
        $multicaixa_debito = 0;
        
        $numerorio = 0;
        $numerorio_credito = 0;
        $numerorio_debito = 0;
        
        $duplo = 0;
        $duplo_credito = 0;
        $duplo_debito = 0;
        
        $movimentos = OperacaoFinanceiro::where('entidade_id', $entidade->empresa->id)
        // ->when($data, function ($query, $value) {
        //     $query->whereDate('date_at', '>=', Carbon::createFromDate($value));
        // })
        // ->when($data, function ($query, $value) {
        //     $query->whereDate('date_at', '<=', Carbon::createFromDate($value));
        // })
        ->where('user_open_id', Auth::user()->id)
        ->where('code_caixa', $caixaActivo->code_caixa)
        ->where('status_caixa', 'pendente')
        ->get();
        
        foreach($movimentos as $item){
        
            if($item->formas == "C"){
                if($item->type == "R"){
                    $numerorio_debito += $item->motante;
                }
                if($item->type == "D"){
                    $numerorio_credito += $item->motante;
                }
            }
            
            if($item->formas == "B"){
            
                if($item->type == "R"){
                    $multicaixa_debito += $item->motante;
                }
                if($item->type == "D"){
                    $multicaixa_credito += $item->motante;
                }
            }
            
            if($item->formas == "O"){
                if($item->type == "R"){
                    $duplo_debito += $item->motante;
                }
                if($item->type == "D"){
                    $duplo_credito += $item->motante;
                }
            }
        
            
            if($item->type == "R"){
                $debito += $item->motante;
            }
                
            if($item->type == "D"){
                $credito += $item->motante;
            }
        }
        
        $multicaixa = $multicaixa_debito - $multicaixa_credito;
        $numerorio = $numerorio_debito - $numerorio_credito;
        $duplo = $duplo_debito - $duplo_credito;
    
        $head = [
            "titulo" => "Fecho caixa",
            "descricao" => env('APP_NAME'),
            "categorias" => Categoria::with('produtos.marca', 'produtos.variacao')
            ->where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])
            ->get(),
            "empresa" => Entidade::findOrFail($entidade->empresa->id),
            "caixaActivo" => $caixaActivo,
            "movimentos" => $movimentos,
            
            "credito" => $credito,
            "debito" => $debito,
            
            "multicaixa" => $multicaixa,
            "numerorio" => $numerorio,
            "duplo" => $duplo,
            
            "multicaixa_credito" => $multicaixa_credito,
            "multicaixa_debito" => $multicaixa_debito,
            "numerorio_credito" => $numerorio_credito,
            "numerorio_debito" => $numerorio_debito,
            "duplo_credito" => $duplo_credito,
            "duplo_debito" => $duplo_debito,
            
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.vendas.caixas.fecho', $head);
    } 

    public function fechamento_caixa_create(Request $request)
    { 
        $request->validate([
            'caixa_id' => 'required',    
        ], [
            'caixa_id.required' => 'O caixa é obrigatório.!',
        ]);
    
        $user = auth()->user();
        if(!$user->can('fecho do caixa')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
   
        $entidade = User::with('empresa.tipo_entidade.modulos')->findOrFail(Auth::user()->id);
        
        $caixaActivo = Caixa::findOrFail($request->caixa_id);

        if(!$caixaActivo){
            return response()->json(['message' => "Verifica se tens um caixa aberto, por favor!"], 404);
        }
        
        $data = date("Y-m-d");
        
        $movimentos = OperacaoFinanceiro::where('entidade_id', $entidade->empresa->id)
            // ->when($data, function ($query, $value) {
            //     $query->whereDate('date_at', '>=', Carbon::createFromDate($value));
            // })
            // ->when($data, function ($query, $value) {
            //     $query->whereDate('date_at', '<=', Carbon::createFromDate($value));
            // })
            ->where('user_open_id', Auth::user()->id)
            ->where('code_caixa', $caixaActivo->code_caixa)
            ->where('status_caixa', 'pendente')
            ->get();
        
        foreach($movimentos as $item){
            $update = OperacaoFinanceiro::findOrFail($item->id);
            $update->status_caixa = "concluido";
            $update->update();
        }
                
        $statusCaixa = Caixa::findOrFail($caixaActivo->id);
        $statusCaixa->status = "fechado";
        $statusCaixa->active = false;
        $statusCaixa->user_open_id = NULL;
        $statusCaixa->user_close_id = Auth::user()->id;
        $caixaActivo->continuar_apos_login = false;
        $statusCaixa->update();
        
        #PAREI AQUI ESTA BEN
        
        return response()->json(['success' => true, 'redirect' => route('relatorio-fechamento-caixa', ['data_inicio' => $data, 'data_final' => $data, 'caixa_id' => $caixaActivo->id])]);
   
    }  

    public function continuar_caixa_create()
    {   
        $user = auth()->user();
        if(!$user->can('fecho do caixa')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
   
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $caixaActivo = Caixa::where([
            ['active', true],
            ['continuar_apos_login', false],
            ['entidade_id', '=', $entidade->empresa->id],
            ['status', 'aberto'],
            ['user_id', '=', Auth::user()->id],
        ])->first();
        
        if($caixaActivo){
            $update = Caixa::findOrFail($caixaActivo->id);
            $update->continuar_apos_login = true;
            $update->update();
        }

        return response()->json(['caixaActivo' => $caixaActivo], 200);
   
    }  

    // relatorio fechamento caixa
    public function relatorio_fechamento_caixa($data_inicio, $data_final, $subconta_id)
    {
        $user = auth()->user();
        
        if(!$user->can('fecho do caixa')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $caixaActivo = Caixa::find($subconta_id);
        if(!$caixaActivo){
            return redirect()->back();
        }
        
        $entidade = User::with('empresa.tipo_entidade.modulos')->findOrFail(Auth::user()->id);
        
        $credito = 0;
        $debito = 0;
                     
        $multicaixa = 0;
        $multicaixa_credito = 0;
        $multicaixa_debito = 0;
        
        $numerorio = 0;
        $numerorio_credito = 0;
        $numerorio_debito = 0;
        
        $duplo = 0;
        $duplo_credito = 0;
        $duplo_debito = 0;
      
        $subconta_caixa = Caixa::findOrFail($caixaActivo->id);
        
        $movimentos = OperacaoFinanceiro::where('entidade_id', $entidade->empresa->id)
        // ->when($data_inicio, function ($query, $value) {
        //     $query->whereDate('date_at', '>=', Carbon::createFromDate($value));
        // })
        // ->when($data_final, function ($query, $value) {
        //     $query->whereDate('date_at', '<=', Carbon::createFromDate($value));
        // })
        // ->where('user_id', Auth::user()->id)
        ->where('code_caixa', $caixaActivo->code_caixa)
        ->where('status_caixa', 'concluido')
        ->get();
        
        foreach($movimentos as $item){
            if($item->formas == "C"){
                if($item->type == "R"){
                    $numerorio_debito += $item->motante;
                }
                if($item->type == "D"){
                    $numerorio_credito += $item->motante;
                }
            }
            if($item->formas == "B"){
            
                if($item->type == "R"){
                    $multicaixa_debito += $item->motante;
                }
                if($item->type == "D"){
                    $multicaixa_credito += $item->motante;
                }
            }
            if($item->formas == "O"){
                if($item->type == "R"){
                    $duplo_debito += $item->motante;
                }
                if($item->type == "D"){
                    $duplo_credito += $item->motante;
                }
            }
            if($item->type == "R"){
                $debito += $item->motante;
            }
            if($item->type == "D"){
                $credito += $item->motante;
            }
        }
        
        $multicaixa = $multicaixa_debito - $multicaixa_credito;
        $numerorio = $numerorio_debito - $numerorio_credito;
        $duplo = $duplo_debito - $duplo_credito;
  

        $head = [
            "titulo" => "Fecho caixa",
            "descricao" => env('APP_NAME'),
            "categorias" => Categoria::with('produtos.marca', 'produtos.variacao')
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->get(),
            "empresa" => Entidade::findOrFail($entidade->empresa->id),
            
            "subconta" => $subconta_caixa,
            "data_inicio" => $data_inicio,
            "data_final" => $data_final,
            
            "credito" => $credito,
            "debito" => $debito,
            
            "multicaixa" => $multicaixa,
            "numerorio" => $numerorio,
            "duplo" => $duplo,
            
            "multicaixa_credito" => $multicaixa_credito,
            "multicaixa_debito" => $multicaixa_debito,
            "numerorio_credito" => $numerorio_credito,
            "numerorio_debito" => $numerorio_debito,
            "duplo_credito" => $duplo_credito,
            "duplo_debito" => $duplo_debito,
            
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.vendas.caixas.relatorio-fecho', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
        
    } 

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function caixaDesactivar($id)
    {
        //
        $user = auth()->user();
        
        if(!$user->can('listar caixa')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $caixa = Caixa::findOrFail($id);
        
        if($caixa->active == false){
            $caixa->status = true;
        }else{
            $caixa->status = false;
        }
        
        if($caixa->update()){
            Alert::success("Sucesso!", "Caixa Suspendida do successo");
            return redirect()->route('lojas.index');
        }else {
            Alert::error("Erro!", "Não foi possível Suspender a Caixa");
            return redirect()->route('lojas.index');
        }
        
    }
    
    public function caixasDetalhe($id)
    {
        $user = auth()->user();
        
        if(!$user->can('listar caixa')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $movimentos = MovimentoCaixa::with('user')->where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->with('caixa')->findOrFail($id);
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);
        
        $head = [
            "titulo" => "Detalhe Caixa",
            "descricao" => env('APP_NAME'),
            "movimento" => $movimentos,
            "empresa" => $empresa,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
        

        return view('dashboard.pronto-venda.caixas.detalhe', $head);
    }
}
