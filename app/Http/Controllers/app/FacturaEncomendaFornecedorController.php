<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\ContaBancaria;
use App\Models\Caixa;
use App\Models\ContaFornecedore;
use App\Models\EncomendaFornecedore;
use App\Models\Entidade;
use App\Models\FacturaEncomendaFornecedor;
use App\Models\Fornecedore;
use App\Models\ItensEncomenda;
use App\Models\Loja;
use App\Models\Movimento;
use App\Models\OperacaoFinanceiro;
use App\Models\Subconta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class FacturaEncomendaFornecedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $facturas = FacturaEncomendaFornecedor::where('entidade_id', '=', $entidade->empresa->id)
            ->with(['fornecedor', 'user', 'encomenda'])
            ->orderBy('created_at', 'asc')
            ->get(); 
   
        $head = [
            "titulo" => "Encomendas Listagem",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "facturas" => $facturas,
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.fornecedores.facturas.index', $head);


        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'factura' => 'required',
            'valor_a_pagar' => 'required',
            'valor_total_factura_original' => 'required',
            'data_factura' => 'required',
            'data_vencimento' => 'required',
            'marcar_como' => 'required',
            'encomenda_id' => 'required',
        ],[
            'factura.required' => 'A factura é um campo obrigatório',
            'valor_a_pagar.required' => 'O valor a pagar é um campo obrigatório',
            'data_factura.required' => 'Data da factura é um campo obrigatório',
            'data_vencimento.required' => 'Data vencimento é um campo obrigatório',
            'marcar_como.required' => 'Marcar como pagao é um campo obrigatório',
            'encomenda_id.required' => 'A encomenda é um campo obrigatório',
            'valor_total_factura_original.required' => 'O valor total da factura original é um campo obrigatório',
        ]);
        
        
        $encomenda = EncomendaFornecedore::findOrFail($request->encomenda_id);
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $fornecedor = Fornecedore::findOrFail($encomenda->fornecedor_id);
        
        $subconta_fornecedor = Subconta::where('code', $fornecedor->code)->first();
        
        $code = uniqid(time());
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            if($subconta_fornecedor ){
        
                $status2 = "nao concluido";
                $status = false;
        
                if($request->marcar_como == "sim"){
                    
                    if($request->forma_pagamento_id == ""){
                        return redirect()->back()->with('danger', 'Deves selecionar uma forma de pagamento da factura!');
                    }
                    
                    if($request->forma_pagamento_id == "NU"){
                        if($request->caixa_id == ""){
                            return redirect()->back()->with('danger', 'Deves selecionar o caixa onde será retirado o valor para o pagamento da factura!');
                        }
                        
                        $subconta_caixa = Subconta::where('code', $request->caixa_id)->first();
                        
                        #VAMOS CREDITAR NO CAIXA OU SEJA VAMOS TIRAR O DINHEIRO DO CAIXA SELECIONADO
                        $movimeto = Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $subconta_caixa->id,
                            'status' => true,
                            'movimento' => 'S',
                            'credito' => $request->valor_a_pagar,
                            'debito' => 0,
                            'observacao' => $request->observacao,
                            'code' => $code,
                            'data_at' => date("Y-m-d"),
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => 1,
                            'periodo_id' => 12,
                        ]);
                        
                        OperacaoFinanceiro::create([
                            'nome' => "PAGAMENTO DE MERCADORIAS",
                            'status' => "pago",
                            'motante' => $request->valor_a_pagar,
                            'subconta_id' => $subconta_caixa->id,
                            'fornecedor_id' => $fornecedor->id,
                            'model_id' => 14,
                            'type' => "D",
                            'parcelado' => "N",
                            'status_pagamento' => "pago",
                            'data_recebimento' => date("Y-m-d"),
                            'code' => $code,
                            'descricao' => "PAGAMENTO DE MERCADORIAS",
                            'movimento' => "S",
                            'date_at' => date("Y-m-d"),
                            'user_id' => Auth::user()->id,
                            'entidade_id' => $entidade->empresa->id,
                        ]);
                                        
                    }
                    
                    if($request->forma_pagamento_id == "MB"){
                        if($request->banco_id == ""){
                            return redirect()->back()->with('danger', 'Deves selecionar o banco onde será retirado o valor para o pagamento da factura!');
                        }
                        
                        $subconta_banco = Subconta::where('code', $request->banco_id)->first();
                        
                        #VAMOS CREDITAR NO BANCO OU SEJA VAMOS TIRAR O DINHEIRO DO BANCO SELECIONADO
                        
                        $movimeto = Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $subconta_banco->id,
                            'status' => true,
                            'movimento' => 'S',
                            'credito' => $request->valor_a_pagar,
                            'debito' => 0,
                            'observacao' => $request->observacao,
                            'code' => $code,
                            'data_at' => date("Y-m-d"),
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => 1,
                            'periodo_id' => 12,
                        ]);
                        
                        OperacaoFinanceiro::create([
                            'nome' => "PAGAMENTO DE MERCADORIAS",
                            'status' => "pago",
                            'motante' => $request->valor_a_pagar,
                            'subconta_id' => $subconta_banco->id,
                            'fornecedor_id' => $fornecedor->id,
                            'model_id' => 14,
                            'type' => "D",
                            'parcelado' => "N",
                            'status_pagamento' => "pago",
                            'data_recebimento' => date("Y-m-d"),
                            'code' => $code,
                            'descricao' => "PAGAMENTO DE MERCADORIAS",
                            'movimento' => "S",
                            'date_at' => date("Y-m-d"),
                            'user_id' => Auth::user()->id,
                            'entidade_id' => $entidade->empresa->id,
                        ]);
                    }
                    
                    ## CREDITAR FORNECEDOR
                    $movimeto = Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $subconta_fornecedor->id,
                        'status' => true,
                        'movimento' => 'S',
                        'credito' => 0,
                        'debito' => $request->valor_a_pagar,
                        'observacao' => $request->observacao,
                        'code' => $code,
                        'data_at' => date("Y-m-d"),
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => 1,
                        'periodo_id' => 12,
                    ]);
                    
                    $encomenda->status_pagamento = true;
                    $encomenda->update();
                    
                    
                    $status2 = "concluido";
                    $status = true;
                          
                }
            
                $create = FacturaEncomendaFornecedor::create([
                    'factura' => $request->factura,
                    'fornecedor_id' => $encomenda->fornecedor_id,
                    'encomenda_id' => $encomenda->id,
                    'user_id' => Auth::user()->id,
                    'desconto' => $request->desconto,
                    'valor_factura' => $encomenda->total,
                    'valor_pago' => $request->valor_a_pagar,
                    'valor_divida' => $request->valor_total_factura_original - $request->valor_a_pagar,
                    'data_factura' => $request->data_factura,
                    'data_vencimento' => $request->data_vencimento,
                    'observacao' => $request->observacao,
                    'referenciante' => $encomenda->factura,
                    'status' => $status,
                    'status2' => $status2,
                    'status3' => "original",
                    'entidade_id' => $entidade->empresa->id,
                ]);
    
                $conta = ContaFornecedore::where([
                    ['fornecedor_id', '=', $encomenda->fornecedor_id]
                ])->first();
    
                $upatedConta = ContaFornecedore::findOrFail($conta->id);
                
                if($request->marcar_como == "nao"){
                    $upatedConta->saldo = $upatedConta->saldo + $request->valor_a_pagar;
                    $upatedConta->divida_corrente = $upatedConta->divida_corrente + ($encomenda->tota_pago - $request->valor_a_pagar);
                    $upatedConta->update();
                }
                
                if($request->marcar_como == "sim"){
                    $upatedConta->saldo = $upatedConta->saldo - $request->valor_a_pagar;
                    $upatedConta->divida_corrente = $upatedConta->divida_corrente - ($encomenda->tota_pago - $request->valor_a_pagar);
                    $upatedConta->update();
                }
                
                $encomenda->tota_pago = $encomenda->tota_pago + $request->valor_a_pagar;
                $encomenda->total_a_pagar = $encomenda->total_a_pagar - $request->valor_a_pagar;
                $encomenda->update();
                
                if($encomenda->tota_pago > $encomenda->total){
                    return redirect()->back()->with('danger', 'O Valor a pagar não pode ser superior ao valor total da factura, verifica as facturas já 
                        criadas para estas encomendas, caso ainda não efectuou o pagamento das mesma factura faça-o!');
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

        Alert::success('Sucesso', 'Factura de Compra criada com sucesso');
        return redirect()->route('fornecedores-encomendas.show', $encomenda->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $factura = FacturaEncomendaFornecedor::with('fornecedor', 'user', 'encomenda')->findOrFail($id);

        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);

        $encomenda = EncomendaFornecedore::with('fornecedor', 'user')->findOrFail($factura->encomenda_id);

        $items = ItensEncomenda::where([
            ['code', '=', $encomenda->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->with('produto')
        ->get();
        
        $facturas = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->get();
        
        /********************************************** */
        $totalFacturaNaoPaga = FacturaEncomendaFornecedor::where([
            ['status2', '=', 'nao concluido'],
            ['status3', '=', 'orginal'],
            ['status', false],
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->count();

        $totalValorFacturaNaoPaga = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
            ['status2', '=', 'nao concluido'],
            ['status3', '=', 'orginal'],
            ['status', false],
        ])
        ->sum('valor_factura');

        $totalValorFacturaSaldoNaoPaga = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
            ['status2', '=', 'nao concluido'],
            ['status3', '=', 'orginal'],
            ['status', false],
        ])
        ->sum('valor_divida');

        $totalValorPagoNaoPaga = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
            ['status2', '=', 'nao concluido'],
            ['status3', '=', 'orginal'],
            ['status', false],
        ])
        ->sum('valor_pago');

        /******************************************************************** */
        /******************************************************************** */
        /******************************************************************** */
        $totalFacturaJPagas = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
            ['status2', '=', 'concluido'],
            ['status', true],
        ])
        ->count();

        $totalValorFacturaJPagas = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
            ['status2', '=', 'concluido'],
            ['status', true],
        ])
        ->sum('valor_pago');

        $facturasPagas = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
            ['status2', '=', 'concluido'],
            ['status', true],
        ])
        ->get();

        /******************************************************************** */
        /******************************************************************** */
        /******************************************************************** */


        /********************************************** */
        $totalFactura = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->count();

        $totalValorFactura = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->sum('valor_factura');

        $totalValorFacturaSaldo = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->sum('valor_divida');

        $totalValorPago = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->sum('valor_pago');

         /********************************************** */



        $head = [
            "titulo" => "Liquidar Encomenda",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "factura" => $factura,
            "loja" => $entidade,
            "items" => $items,
            "encomenda" => $encomenda,
            "facturas" => $facturas,
            "facturasPagas" => $facturasPagas,
            "totalFacturaJPagas" => $totalFacturaJPagas,
            "totalValorFacturaJPagas" => $totalValorFacturaJPagas,

            
            "totalValorFactura" => $totalValorFactura,
            "totalValorPago" => $totalValorPago,
            "totalFactura" => $totalFactura,
            "totalValorFacturaSaldo" => $totalValorFacturaSaldo,

            "totalValorFacturaNaoPaga" => $totalValorFacturaNaoPaga,
            "totalValorPagoNaoPaga" => $totalValorPagoNaoPaga,
            "totalFacturaNaoPaga" => $totalFacturaNaoPaga,
            "totalValorFacturaSaldoNaoPaga" => $totalValorFacturaSaldoNaoPaga,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.fornecedores.facturas.show', $head); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $factura = FacturaEncomendaFornecedor::findOrFail($id);
        $factura->delete();

        Alert::success('Sucesso', 'Factura ou Pagamento Excluído com sucesso!');
        return redirect()->route('fornecedores-encomendas.show', $factura->encomenda->id);
    }

    public function criarFacturaCompra($id)
    {
        $encomenda = EncomendaFornecedore::findOrFail($id);
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);
        
        $caixas = Caixa::where('entidade_id', $entidade->empresa->id)->get();
        $bancos = ContaBancaria::where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => "Adicionar Encomenda",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "encomenda" => $encomenda,
            "caixas" => $caixas,
            "bancos" => $bancos,
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.fornecedores.facturas.create', $head); 
    }

    public function liquidarFacturaCompra($id)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $factura = FacturaEncomendaFornecedor::with('fornecedor', 'user', 'encomenda')->findOrFail($id);

        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);

        $caixas = Caixa::where('entidade_id', $entidade->empresa->id)->get();
        $bancos = ContaBancaria::where('entidade_id', $entidade->empresa->id)->get();

        
        $head = [
            "titulo" => "Liquidar Encomenda",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "factura" => $factura,
            "loja" => $entidade,
            "caixas" => $caixas,
            "bancos" => $bancos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.fornecedores.facturas.liquidar', $head); 
    }


    public function liquidarFacturaCompraStore(Request $request)
    {        
        $request->validate([
            'valor_liquidar' => 'required',
            'factura_id' => 'required',
            'data_pagamento' => 'required',
            'observacao' => 'required',
        ],[
            'valor_liquidar.required' => 'O valor da liquidar é um campo obrigatório',
            'factura_id.required' => 'A factura é um campo obrigatório',
            'data_pagamento.required' => 'Data de pagamento é um campo obrigatório',
            'observacao.required' => 'Observação é um campo obrigatório',
        ]);
        
        
        $factura = FacturaEncomendaFornecedor::findOrFail($request->factura_id);
        $fornecedor = Fornecedore::findOrFail($factura->fornecedor_id);
        $encomenda = EncomendaFornecedore::findOrFail($factura->encomenda_id);
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $subconta_fornecedor = Subconta::where('code', $fornecedor->code)->first();
        
        $code = uniqid(time());
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            if($request->forma_pagamento_id == ""){
                return redirect()->back()->with('danger', 'Deves selecionar uma forma de pagamento da factura!');
            }
        
            if($request->forma_pagamento_id == "NU"){
                if($request->caixa_id == ""){
                    return redirect()->back()->with('danger', 'Deves selecionar o caixa onde será retirado o valor para o pagamento da factura!');
                }
                
                $subconta_caixa = Subconta::where('code', $request->caixa_id)->first();
                
                #VAMOS CREDITAR NO CAIXA OU SEJA VAMOS TIRAR O DINHEIRO DO CAIXA SELECIONADO
                $movimeto = Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $subconta_caixa->id,
                    'status' => true,
                    'movimento' => 'S',
                    'credito' => $request->valor_liquidar,
                    'debito' => 0,
                    'observacao' => $request->observacao,
                    'code' => $code,
                    'data_at' => date("Y-m-d"),
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => 1,
                    'periodo_id' => 12,
                ]);
                
                OperacaoFinanceiro::create([
                    'nome' => "PAGAMENTO DE MERCADORIAS",
                    'status' => "pago",
                    'motante' => $request->valor_liquidar,
                    'subconta_id' => $subconta_caixa->id,
                    'fornecedor_id' => $fornecedor->id,
                    'model_id' => 14,
                    'type' => "D",
                    'parcelado' => "N",
                    'status_pagamento' => "pago",
                    'data_recebimento' => date("Y-m-d"),
                    'code' => $code,
                    'descricao' => "PAGAMENTO DE MERCADORIAS",
                    'movimento' => "S",
                    'date_at' => date("Y-m-d"),
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
                
            }
        
            if($request->forma_pagamento_id == "MB"){
                if($request->banco_id == ""){
                    return redirect()->back()->with('danger', 'Deves selecionar o banco onde será retirado o valor para o pagamento da factura!');
                }
                
                $subconta_banco = Subconta::where('code', $request->banco_id)->first();
                
                #VAMOS CREDITAR NO BANCO OU SEJA VAMOS TIRAR O DINHEIRO DO BANCO SELECIONADO
                            
                $movimeto = Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $subconta_banco->id,
                    'status' => true,
                    'movimento' => 'S',
                    'credito' => $request->valor_liquidar,
                    'debito' => 0,
                    'observacao' => $request->observacao,
                    'code' => $code,
                    'data_at' => date("Y-m-d"),
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => 1,
                    'periodo_id' => 12,
                ]);
                
                OperacaoFinanceiro::create([
                    'nome' => "PAGAMENTO DE MERCADORIAS",
                    'status' => "pago",
                    'motante' => $request->valor_liquidar,
                    'subconta_id' => $subconta_banco->id,
                    'fornecedor_id' => $fornecedor->id,
                    'model_id' => 14,
                    'type' => "D",
                    'parcelado' => "N",
                    'status_pagamento' => "pago",
                    'data_recebimento' => date("Y-m-d"),
                    'code' => $code,
                    'descricao' => "PAGAMENTO DE MERCADORIAS",
                    'movimento' => "S",
                    'date_at' => date("Y-m-d"),
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
                
            }
              
            ## CREDITAR FORNECEDOR
            $movimeto = Movimento::create([
                'user_id' => Auth::user()->id,
                'subconta_id' => $subconta_fornecedor->id,
                'status' => true,
                'movimento' => 'S',
                'credito' => 0,
                'debito' => $request->valor_liquidar,
                'observacao' => $request->observacao,
                'code' => $code,
                'data_at' => date("Y-m-d"),
                'entidade_id' => $entidade->empresa->id,
                'exercicio_id' => 1,
                'periodo_id' => 12,
            ]);
    
            
            if(($request->valor_liquidar + $factura->valor_pago) > $factura->valor_factura){
                $status2 = "concluido";
                $status = true;
                $factura->valor_divida = 0;
                $encomenda->status_pagamento = true;
            }else {
                $status2 = "nao concluido";
                $status = false;
                $factura->valor_divida = $factura->valor_pago - $request->valor_liquidar;
                $encomenda->status_pagamento = false;
            }
            // $factura->valor_factura = $request->valor_liquidar;
            $factura->total_pago = $request->valor_liquidar;
            $factura->observacao = $request->observacao;
            $factura->data_pagamento = $request->data_pagamento;
            $factura->status = $status;
            $factura->status2 = $status2;
    
            $factura->update();
    
            $encomenda->update();
            
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        
        Alert::success('Sucesso', 'Factura Paga com sucesso');
        return redirect()->route('fornecedores-facturas-encomendas.show', $factura->id);

    }

    public function duplicarFacturaCompra($id)
    {
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $factura = FacturaEncomendaFornecedor::with('fornecedor', 'user', 'encomenda')->findOrFail($id);

        $encomenda = EncomendaFornecedore::with('fornecedor', 'user')->findOrFail($factura->encomenda_id);

        $head = [
            "titulo" => "Duplicar Factura",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "factura" => $factura,
            "encomenda" => $encomenda,
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),

            "fornecedores" => Fornecedore::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.fornecedores.facturas.duplicar-factura', $head); 
        
    }

    public function duplicarFacturaCompraStore(Request $request)
    {
        $request->validate([
            'factura' => 'required',
            'valor_factura' => 'required',
            'data_factura' => 'required',
            'data_vencimento' => 'required',
            'marcar_como' => 'required',
            'encomenda_id' => 'required',
        ],[
            'factura.required' => 'A factura é um campo obrigatório',
            'valor_factura.required' => 'O valor da factura é um campo obrigatório',
            'data_factura.required' => 'A data da factura é um campo obrigatório',
            'data_vencimento.required' => 'A data do vencimento é um campo obrigatório',
            'marcar_como.required' => 'Marcar como pago é um campo obrigatório',
            'encomenda_id.required' => 'A encomenda é um campo obrigatório',
        ]);
        
        $factura = FacturaEncomendaFornecedor::findOrFail($request->factura_id);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $encomenda = EncomendaFornecedore::findOrFail($request->encomenda_id);

        if($request->marcar_como == "nao"){
            $valorPago = 0;
            $status_pagamento = false;
            $valor_divida = $request->valor_pagar;
        }else if($request->marcar_como == "sim" && $request->valor_pagar > 0){
            $valorPago = $request->valor_pagar;
            $valor_divida = $request->valor_pagar - $request->valor_factura;
            $status_pagamento = true;
        }

        if($valorPago == $request->valor_factura){
            $status = "concluido";
        }else if($valorPago != $request->valor_factura){
            $status = "nao concluido";
        }

        $create = FacturaEncomendaFornecedor::create([
            'factura' => $factura->factura. " - duplicado",
            'fornecedor_id' => $request->fornecedor_id,
            'encomenda_id' => $factura->encomenda_id,
            'user_id' => Auth::user()->id,
            'desconto' => $request->desconto,
            'valor_factura' => $encomenda->total,
            'valor_pago' => $request->valor_factura,
            'valor_divida' => $encomenda->total - $request->valor_factura,
            'data_factura' => $request->data_factura,
            'data_vencimento' => $request->data_vencimento,
            'observacao' => $request->observacao,
            'referenciante' => $encomenda->factura,
            'status' => $status_pagamento,
            'status2' => $status,
            'status3' => "duplicado",
            'entidade_id' => $entidade->empresa->id,
        ]);

        if( $create->save()){
            
            $conta = ContaFornecedore::where([
                ['fornecedor_id', '=', $encomenda->fornecedor_id]
            ])->first();

            $upatedConta = ContaFornecedore::findOrFail($conta->id);
            $upatedConta->saldo = $upatedConta->saldo + $request->valor_factura;
            $upatedConta->divida_corrente = $upatedConta->divida_corrente + $request->valor_factura;
            $upatedConta->update();

            if($request->marcar_como == "sim" && $request->valor_pagar > 0){
                $upatedConta->saldo = $upatedConta->saldo - $request->valor_pagar;
                $upatedConta->divida_corrente = $upatedConta->divida_corrente - $request->valor_pagar;
                $upatedConta->update();
            }

            Alert::success('Sucesso', 'Factura Duplicada com sucesso');
            return redirect()->route('fornecedores-encomendas.show', $encomenda->id);
        }else{
            Alert::warning('Atenção', 'Não foi possível duplicar a factura!');
        }
        
    }
}
