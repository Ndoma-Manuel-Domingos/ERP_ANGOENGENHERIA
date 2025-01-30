<?php

namespace App\Http\Controllers;

use App\Models\EncomendaFornecedore;
use App\Models\Entidade;
use App\Models\Estoque;
use App\Models\FacturaEncomendaFornecedor;
use App\Models\Fornecedore;
use App\Models\Imposto;
use App\Models\ItensEncomenda;
use App\Models\Loja;
use App\Models\Motivo;
use App\Models\Movimento;
use App\Models\Produto;
use App\Models\Registro;
use App\Models\Subconta;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

use PDF;

class EncomendaForncedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);    
        
        $encomendas = EncomendaFornecedore::when($request->status, function($query, $value){
            $query->where('status', '=', $value);
        })
        ->when($request->data_inicio, function($query, $value){
            $query->whereDate('created_at', '>=', Carbon::parse($value));
        })
        ->when($request->data_final, function($query, $value){
            $query->whereDate('created_at', '<=',Carbon::parse($value));
        })
        ->where('entidade_id', '=', $entidade->empresa->id)
        ->with('fornecedor')
        ->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => "Encomendas Listagem",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "encomendas" => $encomendas,
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
            "requests" => $request->all("status", "data_inicio", "data_final"),
        ];

        return view('dashboard.fornecedores.encomendas.index', $head); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);
        
        $items = ItensEncomenda::where([
            ['user_id', '=', Auth::user()->id],
            ['status', '=', 'em processo'],
            ['code', '=', NULL],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->with('produto.taxa_imposto')
        ->get();
        
        $produtos = Produto::where([
            ['status', '=', 'activo'],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->get();

        $fornecedores = Fornecedore::where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->get();

        $totalEncomendas = EncomendaFornecedore::where([
            ['user_id', '=', Auth::user()->id],
            ['status', '!=', 'em processo'],
            ['code', '!=', NULL],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->count();

        $resultado = $totalEncomendas + 1;
        
        $head = [
            "titulo" => "Adicionar Encomenda",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "produtos" => $produtos,
            "fornecedores" => $fornecedores,
            "items" => $items,
            "motivos" => Motivo::get(),
            "impostos" => Imposto::get(),
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "totalEncomendas" =>  $resultado."-".date('y') ."". date('m') ."". date('d'). "/F",
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.fornecedores.encomendas.create', $head); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   

        $request->validate(
            [
                'data_previsao' => 'required',
                'fornecedor_selecionado' => 'required',
                // 'observacao' => 'required',
            ], [
                'data_previsao.required' => 'A data da previsão é um campo obrigatório',
                'fornecedor_selecionado.required' => 'O fornecedor é um campo obrigatório',
            ]
        );
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $code = uniqid(time());
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            $fornecedor = Fornecedore::findOrFail($request->input("fornecedor_selecionado"));
            
            $subconta_fornecedor = Subconta::where('numero', $fornecedor->conta)
            ->where('code', $fornecedor->code)
            ->first();
            
            $subconta_compra_mercadoria = Subconta::where('numero', ENV('COMPRA_MERCADORIA'))->first();
            
            $subconta_iva = Subconta::where('numero', ENV('IVA_DEDUTIVO'))->first();
            
                        
            foreach($request->ids as $id){
            
                $update = ItensEncomenda::findOrFail($id);
                
                $produto = Produto::findOrFail($update->produto_id);
                
                $update->quantidade = $request->input("quantidade{$id}");
                $update->iva = $request->input("iva{$id}");
                $update->custo = $request->input("custo{$id}");
                $update->preco_venda = $request->input("custo{$id}") + ($request->input("custo{$id}") * ($request->input("iva{$id}") / 100)) + ($request->input("custo{$id}") * ($produto->margem / 100));
                        
                $update->imposto_valor = ($request->input("custo{$id}") * $request->input("quantidade{$id}")) * ($request->input("iva{$id}") / 100);
                
                if($request->input("desonto{$id}") >= 1 && $request->input("desonto{$id}") <= 100){
                    
                    $valorIVa = (($request->input("custo{$id}") * $request->input("quantidade{$id}")) * ($request->input("iva{$id}") / 100));
                
                    $totalComIva = ($request->input("custo{$id}") * $request->input("quantidade{$id}")) + ($valorIVa);
                    $totalSemIva = ($request->input("custo{$id}") * $request->input("quantidade{$id}"));
                    
                    $desconto = (($request->input("custo{$id}")* $request->input("quantidade{$id}")) * ($request->input("desonto{$id}") / 100));
                    $update->desconto_valor = $desconto;
                    
                    $c_iva_do_desconto = $desconto * ($request->input("iva{$id}") / 100);
                    
                }else{
                
                    $valorIVa = (($request->input("custo{$id}") * $request->input("quantidade{$id}")) * ($request->input("iva{$id}") / 100));
                    
                    $totalComIva = ($request->input("custo{$id}") * $request->input("quantidade{$id}")) + $valorIVa;
                    $totalSemIva = ($request->input("custo{$id}") * $request->input("quantidade{$id}"));
                    $update->desconto_valor = 0;
                    $c_iva_do_desconto = 0;
                    
                }
                
                $update->totalCiva = $totalComIva;
                $update->totalSiva = $totalSemIva;
                $update->valorIva = $valorIVa;
                $update->fornecedor_id = $request->input("fornecedor_selecionado");
                
                $update->desconto = $request->input("desonto{$id}");
                
                $update->total = $totalComIva - $update->desconto_valor;
                
                $update->loja_id = $request->loja_id;
                
                ##COMPRA A PRAZO

                $subconta_mercadoria = Subconta::where('code', $produto->code)->first();
                
                if($subconta_compra_mercadoria && $subconta_fornecedor && $subconta_iva)
                {
                    #DEBITAMOS O 21
                    $movimeto = Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $subconta_compra_mercadoria->id,
                        'status' => true,
                        'movimento' => 'E',
                        'credito' => 0,
                        'debito' => $totalSemIva,
                        'observacao' => $request->observacao,
                        'code' => $code,
                        'data_at' => date("Y-m-d"),
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => 1,
                        'periodo_id' => 12,
                    ]);
                    
                    if($entidade->empresa->tipo_inventario == "PERMANENTE"){
                        $movimeto = Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $subconta_compra_mercadoria->id,
                            'status' => true,
                            'movimento' => 'E',
                            'credito' => $totalSemIva,
                            'debito' => 0,
                            'observacao' => $request->observacao,
                            'code' => $code,
                            'data_at' => date("Y-m-d"),
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => 1,
                            'periodo_id' => 12,
                        ]);
                        
                        $movimeto = Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $subconta_mercadoria->id,
                            'status' => true,
                            'movimento' => 'E',
                            'credito' => 0,
                            'debito' => $totalSemIva,
                            'observacao' => $request->observacao,
                            'code' => $code,
                            'data_at' => date("Y-m-d"),
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => 1,
                            'periodo_id' => 12,
                        ]);
                    }
                
                    ##DEBITAMOS 34.5.2.....
                    $movimeto = Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $subconta_iva->id,
                        'status' => true,
                        'movimento' => 'E',
                        'credito' => 0,
                        'debito' => $valorIVa,
                        'observacao' => $request->observacao,
                        'code' => $code,
                        'data_at' => date("Y-m-d"),
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => 1,
                        'periodo_id' => 12,
                    ]);
                
                    ## CREDITAMOS FORNECEDOR
                    $movimeto = Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $subconta_fornecedor->id,
                        'status' => true,
                        'movimento' => 'E',
                        'credito' => $totalComIva,
                        'debito' => 0,
                        'observacao' => $request->observacao,
                        'code' => $code,
                        'data_at' => date("Y-m-d"),
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => 1,
                        'periodo_id' => 12,
                    ]);
                }
           
                $update->update();
            }        
            
            ## OUTRA PARTE DOS CUSTOS - START
            $outros_custos = $request->custo_transporte + $request->custo_manuseamento + $request->outros_custos;
    
            ## CREDITAMOS FORNECEDOR
            $movimeto = Movimento::create([
                'user_id' => Auth::user()->id,
                'subconta_id' => $subconta_fornecedor->id,
                'status' => true,
                'movimento' => 'E',
                'credito' => $outros_custos,
                'debito' => 0,
                'observacao' => $request->observacao,
                'code' => $code,
                'data_at' => date("Y-m-d"),
                'entidade_id' => $entidade->empresa->id,
                'exercicio_id' => 1,
                'periodo_id' => 12,
            ]);
            
            #DEBITAMOS O 21
            $movimeto = Movimento::create([
                'user_id' => Auth::user()->id,
                'subconta_id' => $subconta_compra_mercadoria->id,
                'status' => true,
                'movimento' => 'E',
                'credito' => 0,
                'debito' => $outros_custos,
                'observacao' => $request->observacao,
                'code' => $code,
                'data_at' => date("Y-m-d"),
                'entidade_id' => $entidade->empresa->id,
                'exercicio_id' => 1,
                'periodo_id' => 12,
            ]);
            
            if($entidade->empresa->tipo_inventario == "PERMANENTE"){
                $movimeto = Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $subconta_compra_mercadoria->id,
                    'status' => true,
                    'movimento' => 'E',
                    'credito' => $outros_custos,
                    'debito' => 0,
                    'observacao' => $request->observacao,
                    'code' => $code,
                    'data_at' => date("Y-m-d"),
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => 1,
                    'periodo_id' => 12,
                ]);
            }
                        
            ##END CUSTO
        
            $totalValorSiva = ItensEncomenda::where([
                ['fornecedor_id', '=', $request->input("fornecedor_selecionado")],
                ['user_id', '=', Auth::user()->id],
                ['status', '=', 'em processo'],
                ['code', '=', NULL],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
            ->with('produto')
            ->sum('totalSiva');
    
            $totalValorCiva = ItensEncomenda::where([
                ['fornecedor_id', '=', $request->input("fornecedor_selecionado")],
                ['user_id', '=', Auth::user()->id],
                ['status', '=', 'em processo'],
                ['code', '=', NULL],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
            ->with('produto')
            ->sum('totalCiva');
    
            $totalDesconto = ItensEncomenda::where([
                ['fornecedor_id', '=', $request->input("fornecedor_selecionado")],
                ['user_id', '=', Auth::user()->id],
                ['status', '=', 'em processo'],
                ['code', '=', NULL],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
            ->with('produto')
            ->sum('desconto_valor');
    
            $totalQuantidade = ItensEncomenda::where([
                ['fornecedor_id', '=', $request->input("fornecedor_selecionado")],
                ['user_id', '=', Auth::user()->id],
                ['status', '=', 'em processo'],
                ['code', '=', NULL],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
            ->with('produto')
            ->sum('quantidade');
    
            $totalProduto = ItensEncomenda::where([
                ['fornecedor_id', '=', $request->input("fornecedor_selecionado")],
                ['user_id', '=', Auth::user()->id],
                ['status', '=', 'em processo'],
                ['code', '=', NULL],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
            ->with('produto')
            ->count();
        
            $imposto = ItensEncomenda::where([
                ['fornecedor_id', '=', $request->input("fornecedor_selecionado")],
                ['user_id', '=', Auth::user()->id],
                ['status', '=', 'em processo'],
                ['code', '=', NULL],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
            ->with('produto')
            ->sum('imposto_valor');
    
            $code = uniqid(time());
    
            $totalEncomendas = EncomendaFornecedore::where([
                ['user_id', '=', Auth::user()->id],
                ['status', '!=', 'em processo'],
                ['code', '!=', NULL],
                ['entidade_id', '=', $entidade->empresa->id],
            ])->count();
    
            $resultado = $totalEncomendas + 1;
            
            $create = EncomendaFornecedore::create([
                'status' => 'pendente',
                'numero' => $resultado,
                'factura' => $request->numero,
                'fornecedor_id' => $request->fornecedor_selecionado,
                'loja_id' => $request->loja_id,
                'data_emissao' => date('Y-m-d'),
                'previsao_entrega' => $request->data_previsao,
                'observacao' => $request->observacao,
                'custo_transporte' => $request->custo_transporte,
                'custo_manuseamento' => $request->custo_manuseamento,
                'outros_custos' => $request->outros_custos,
                'code' => $code,
                'imposto' => $imposto,
                'quantidade' => $totalQuantidade,
                'total_produto' => $totalProduto,
                'total_sIva' => $totalValorSiva,
                'total_cIVa' =>  $totalValorCiva,
                'total' =>  ($outros_custos + $totalValorCiva) - $totalDesconto,
                'desconto' => $totalDesconto,
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);
    
            $items = ItensEncomenda::where([
                ['fornecedor_id', '=', $request->input("fornecedor_selecionado")],
                ['user_id', '=', Auth::user()->id],
                ['status', '=', 'em processo'],
                ['code', '=', NULL],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
            ->with('produto')
            ->get();

            foreach ($items as $value) {
                $update = ItensEncomenda::findOrFail($value->id);
                $update->code = $code;
                $update->status = 'pendente';
                $update->update();
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
        

        Alert::success('Sucesso', 'Encomenda realizada com sucesso!');
        return redirect()->route('fornecedores-encomendas.show', $create->id);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $encomenda = EncomendaFornecedore::with('fornecedor', 'user')->findOrFail($id);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);

        $facturas = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->get();

        $facturasPagas = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
            ['status2', '=', 'concluido'],
            ['status', true],
        ])
        ->get();

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
        ->sum('valor_pago');

        $totalPagoFactura = FacturaEncomendaFornecedor::where([
            ['status2', '=', 'nao concluido'],
            ['status', false],
            ['status3', '=', 'original'],
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->sum('total_pago');

        $totalValorFacturaSaldo = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
            ['status2', '=', 'nao concluido'],
            ['status3', '=', 'original'],
            ['status', false],
        ])
        ->sum('valor_divida');

        $totalValorPago = FacturaEncomendaFornecedor::where([
            ['status2', '=', 'concluido'],
            ['status', true],
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->sum('valor_pago');

         /********************************************** */


        /********************************************** */
        $totalFacturaPaga = FacturaEncomendaFornecedor::where([
            ['status2', '=', 'concluido'],
            ['status', true],
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->count();

        $totalValorFacturaNaoPaga = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
            ['status2', '=', 'nao concluido'],
            ['status3', '=', 'original'],
            ['status', false],
        ])
        ->sum('valor_factura');

        $totalValorFacturaSaldoNaoPaga = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
            ['status2', '=', 'nao concluido'],
            ['status3', '=', 'original'],
            ['status', false],
        ])
        ->sum('valor_divida');

        $totalValorPagoNaoPaga = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
            ['status2', '=', 'nao concluido'],
            ['status3', '=', 'original'],
            ['status', false],
        ])
        ->sum('valor_pago');

         /********************************************** */

        $items = ItensEncomenda::where([
            ['code', '=', $encomenda->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->with('produto')
        ->get();

        $head = [
            "titulo" => "Visualizar Encomenda {$encomenda->factura}",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "encomenda" => $encomenda,
            "facturas" => $facturas,
            "facturasPagas" => $facturasPagas,
            "items" => $items,
            "loja" => $entidade,

            "totalPagoFactura" => $totalPagoFactura,

            "totalValorFactura" => $totalValorFactura,
            "totalValorPago" => $totalValorPago,
            "totalFactura" => $totalFactura,
            "totalValorFacturaSaldo" => $totalValorFacturaSaldo,

            "totalValorFacturaNaoPaga" => $totalValorFacturaNaoPaga,
            "totalValorPagoNaoPaga" => $totalValorPagoNaoPaga,
            "totalFacturaPaga" => $totalFacturaPaga,
            "totalValorFacturaSaldoNaoPaga" => $totalValorFacturaSaldoNaoPaga,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.fornecedores.encomendas.show', $head); 
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
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);
        
        $encomenda = EncomendaFornecedore::with('fornecedor', 'loja', 'user')->findOrFail($id);

        $items = ItensEncomenda::where([
            ['fornecedor_id', '=', $encomenda->fornecedor_id],
            ['code', '=', $encomenda->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->with('produto')
        ->with('loja')
        ->get();

        $produtos = Produto::where([
            ['status', '=', 'activo'],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->get();

        $fornecedores = Fornecedore::where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])->get();

        $head = [
            "titulo" => "Adicionar Encomenda",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "produtos" => $produtos,
            "encomenda" => $encomenda,
            "fornecedores" => $fornecedores,
            "items" => $items,
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.fornecedores.encomendas.edit', $head); 
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
        $encomenda = EncomendaFornecedore::findOrFail($id);
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);

        foreach($request->ids as $id){
            $update = ItensEncomenda::findOrFail($id);
            $update->quantidade = $request->input("quantidade{$id}");
            $update->iva = $request->input("iva{$id}");
            $update->custo = $request->input("custo{$id}");
            $update->preco_venda = $request->input("custo{$id}") + ($request->input("custo{$id}") * ($request->input("iva{$id}") / 100));
            
            $update->imposto_valor = (($request->input("custo{$id}") * $request->input("quantidade{$id}")) * ($request->input("iva{$id}") / 100));

            if($request->input("desonto{$id}") >= 1 && $request->input("desonto{$id}") <= 100){
                $totalComIva = ($request->input("custo{$id}") * $request->input("quantidade{$id}")) + (($request->input("custo{$id}") * $request->input("quantidade{$id}")) * ($request->input("desonto{$id}") / 100));
                $totalSemIva = ($request->input("custo{$id}") * $request->input("quantidade{$id}")) - (($request->input("custo{$id}") * $request->input("quantidade{$id}")) * ($request->input("desonto{$id}") / 100));
                $valorIVa = ($request->input("custo{$id}") * $request->input("quantidade{$id}")) * ($request->input("desonto{$id}") / 100);
                
                $update->desconto_valor = ($request->input("custo{$id}")) - (($request->input("custo{$id}")) * ($request->input("desonto{$id}") / 100));
            }else{
                $totalComIva = $request->input("custo{$id}") * $request->input("quantidade{$id}");
                $totalSemIva = $request->input("custo{$id}") * $request->input("quantidade{$id}");
                $valorIVa = 
                 ($request->input("custo{$id}") * $request->input("quantidade{$id}")) - 
                 (($request->input("custo{$id}") * $request->input("quantidade{$id}")) * 
                 ($request->input("iva{$id}") / 100));

                $update->desconto_valor = 0;
            }

            $update->totalCiva = $totalComIva;
            $update->totalSiva = $totalSemIva;
            $update->valorIva = $valorIVa;
            $update->fornecedor_id = $request->input("fornecedor_selecionado");

            $update->desconto = $request->input("desonto{$id}");

            $update->total = $request->input("custo{$id}") * $request->input("quantidade{$id}");

            $update->loja_id = $request->loja_id;

            $update->update();
        }
        
        $totalValorSiva = ItensEncomenda::where([
            ['fornecedor_id', '=', $encomenda->fornecedor_id],
            ['code', '=', $encomenda->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->with('produto')
        ->sum('totalSiva');

        $totalValorCiva = ItensEncomenda::where([
            ['fornecedor_id', '=', $encomenda->fornecedor_id],
            ['code', '=', $encomenda->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->with('produto')
        ->sum('totalCiva');

        $totalDesconto = ItensEncomenda::where([
            ['fornecedor_id', '=', $encomenda->fornecedor_id],
            ['code', '=', $encomenda->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->with('produto')
        ->sum('desconto_valor');

        $totalQuantidade = ItensEncomenda::where([
            ['fornecedor_id', '=', $encomenda->fornecedor_id],
            ['code', '=', $encomenda->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->with('produto')
        ->sum('quantidade');
        

        $totalProduto = ItensEncomenda::where([
            ['fornecedor_id', '=', $encomenda->fornecedor_id],
            ['code', '=', $encomenda->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->with('produto')
        ->count();

        $total = ItensEncomenda::where([
            ['fornecedor_id', '=', $encomenda->fornecedor_id],
            ['code', '=', $encomenda->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->with('produto')
        ->sum('total');

        $imposto = ItensEncomenda::where([
            ['fornecedor_id', '=', $encomenda->fornecedor_id],
            ['code', '=', $encomenda->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->with('produto')
        ->sum('imposto_valor');

        $updated = EncomendaFornecedore::findOrFail($encomenda->id);
        $updated->status = 'pendente';
        $updated->fornecedor_id = $request->fornecedor_selecionado;
        $updated->loja_id = $request->loja_id;
        $updated->data_emissao = date('Y-m-d');
        $updated->previsao_entrega = $request->data_previsao;
        $updated->observacao = $request->observacao;
        $updated->imposto = $imposto;
        $updated->quantidade = $totalQuantidade;
        $updated->total_produto = $totalProduto;
        $updated->total_sIva = $totalValorSiva;
        $updated->total_cIVa =  $totalValorCiva;
        $updated->total = ($totalValorSiva + $imposto);
        $updated->desconto =  $totalDesconto;

        if($updated->update()){
            $items = ItensEncomenda::where([
                ['fornecedor_id', '=', $encomenda->fornecedor_id],
                ['code', '=', $encomenda->code],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
            ->with('produto')
            ->get();

            foreach ($items as $value) {
                $update = ItensEncomenda::findOrFail($value->id);
                $update->status = 'pendente';
                $update->update();
            }
        }

        Alert::success('Sucesso', 'Encomenda Actualizada com sucesso!');
        return redirect()->route('fornecedores-encomendas.show', $updated->id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $encomenda = EncomendaFornecedore::findOrFail($id);

        $items = ItensEncomenda::where([
            ['code', '=', $encomenda->code],
        ])
        ->get();

        if($items){
            foreach ($items as $value) {
                ItensEncomenda::findOrFail($value->id)->delete();
            }
        }

        $encomenda->delete();

        Alert::success('Sucesso', 'Encomenda Excluída com sucesso!');
        return redirect()->route('fornecedores-encomendas.index');
    }

    public function itemsNovaEncomandaSFornecedor($id)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
    
        $produto = Produto::findOrFail($id);
        
        $verificar = ItensEncomenda::where([
            ['produto_id', '=', $produto->id],
            ['user_id', '=', Auth::user()->id],
            ['data_emissao', '=', date('Y-m-d')],
            ['status', '=',  'em processo'],
            ['code',  NULL],
            ['entidade_id' , '=', $entidade->empresa->id],
        ])->first();

        if($verificar){
            Alert::error("Erro", "Este produto Já foi Adicionar... Pode alterar a quantidade");
            return redirect()->route('fornecedores-encomendas.create');
        }

        $items = ItensEncomenda::create([
            'produto_id' => $produto->id,
            'user_id' => Auth::user()->id,
            'quantidade' => 1,
            'desconto' => 0,
            'data_emissao' => date('Y-m-d'),
            'status' => 'em processo',
            'custo' => $produto->preco_custo,
            'margem' => $produto->margem,
            'iva' => $produto->imposto_id,
            'total' => $produto->preco_custo * 1,
            'code' =>  NULL,
            'entidade_id' => $entidade->empresa->id,
        ]);

        if($items->save()){
            return redirect()->route('fornecedores-encomendas.create');
        }else{
            Alert::error("Erro", "Ocorreu um erro ao tentar adicionar este produto");
            return redirect()->route('fornecedores-encomendas.create');
        }

    }
        
    public function itemsNovaEncomandaSFornecedorEdit($id, $encomenda)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
    
        $produto = Produto::findOrFail($id);
        $enco = EncomendaFornecedore::findOrFail($encomenda);

        $verificar = ItensEncomenda::where([
            ['produto_id', '=', $produto->id],
            ['code', '=', $enco->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])->first();

        if($verificar){
            Alert::error("Erro", "Este produto Já foi Adicionar... Pode alterar a quantidade");
            return redirect()->route('fornecedores-encomendas.edit', $enco->id);
        }

        $iva = "";

        if($produto->imposto == "ISE"){
            $iva = 0;
        }else if($produto->imposto == "RED"){
            $iva = 2;
        }else if($produto->imposto == "INT"){
            $iva = 5;
        }else if($produto->imposto == "OUT"){
            $iva = 7;
        }else if($produto->imposto == "NOR"){
            $iva = 14;
        }else{
            $iva = 0;
        }

        $items = ItensEncomenda::create([
            'produto_id' => $produto->id,
            'loja_id' => $enco->loja_id,
            'fornecedor_id' => $enco->fornecedor_id,
            'user_id' => Auth::user()->id,
            'quantidade' => 1,
            'desconto' => 0,
            'data_emissao' => date('Y-m-d'),
            'status' => 'em processo',
            'custo' => $produto->preco_custo,
            'iva' => $iva,
            'total' => $produto->preco_custo * 1,
            'code' =>  $enco->code,
            'entidade_id' => $entidade->empresa->id,
        ]);

        if($items->save()){
            return redirect()->route('fornecedores-encomendas.edit', $enco->id);
        }else{
            Alert::error("Erro", "Ocorreu um erro ao tentar adicionar este produto");
            return redirect()->route('fornecedores-encomendas.edit', $enco->id);
        }

    }

    public function itemsNovaEncomandaRemoverSFornecedor($id)
    {
        $encomenda = ItensEncomenda::findOrFail($id);

        if($encomenda->delete()){
            return redirect()->back();
        }            
       
    }

    public function marcarComoEntregue($id)
    {
        $encomenda = EncomendaFornecedore::findOrFail($id);
        $encomenda->status = "entregue";
        $encomenda->update();

        $items = ItensEncomenda::where([
            ['code', '=', $encomenda->code]
        ])->get();

        if($items){
            foreach ($items as $item) {
                $updated = ItensEncomenda::findOrFail($item->id);
                $updated->status = 'entregue';
                $updated->loja_id = $encomenda->loja_id;
                $updated->update();
            }
        }

        Alert::success('Sucesso', 'Encomenda Entregue com sucesso!');
        return redirect()->route('fornecedores-encomendas.show', $encomenda->id);
    }

    public function marcarComoCancelada($id)
    {
        $encomenda = EncomendaFornecedore::findOrFail($id);
        $encomenda->status = "cancelada";
        $encomenda->update();

        $items = ItensEncomenda::where([
            ['code', '=', $encomenda->code]
        ])->get();

        if($items){
            foreach ($items as $item) {
                $updated = ItensEncomenda::findOrFail($item->id);
                $updated->status = 'cancelada';
                $updated->loja_id = $encomenda->loja_id;
                $updated->update();
            }
        }

        Alert::success('Sucesso', 'Encomenda Cancelada com sucesso!');
        return redirect()->route('fornecedores-encomendas.show', $encomenda->id);
    }

    public function receberProduto($id)
    {
        $encomenda = EncomendaFornecedore::findOrFail($id);

        $items = ItensEncomenda::where([
            ['code', '=', $encomenda->code]
        ])->get();

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);
        
        $encomenda = EncomendaFornecedore::with('fornecedor', 'loja', 'user')->findOrFail($id);

        $items = ItensEncomenda::where([
            ['fornecedor_id', '=', $encomenda->fornecedor_id],
            ['code', '=', $encomenda->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->with('produto.estoque')
        ->with('loja')
        ->get();

        $produtos = Produto::where([
            ['status', '=', 'activo'],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->get();

        $fornecedores = Fornecedore::where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])->get();

        $head = [
            "titulo" => "Receber Ecomenda ou Produto",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "produtos" => $produtos,
            "encomenda" => $encomenda,
            "fornecedores" => $fornecedores,
            "items" => $items,
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.fornecedores.encomendas.receber', $head); 
    }

    public function receberProdutoStore(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $encomenda = EncomendaFornecedore::findOrFail($request->encomenda_id);
        
        $total_recebida = 0;

        foreach($request->ids as $id){
            if($request->input("condicao{$id}") == 'sim'){
                $update = ItensEncomenda::findOrFail($id);
                if(($update->quantidade + $update->quantidade_recebida) >= $request->input("quantidade{$id}") && $update->quantidade != 0){
                
                    $total_recebida += $request->input("quantidade{$id}");
                
                    $update->quantidade_recebida = $update->quantidade_recebida + $request->input("quantidade{$id}");
                    $update->quantidade = $update->quantidade - $request->input("quantidade{$id}");
                    $update->update();

                    $produto = Produto::findOrFail($update->produto_id);
                    $produto->preco_venda = $request->input("preco_venda{$id}");
                    $produto->update();

                    $loja = Loja::findOrFail($encomenda->loja_id);

                    $actualizarEstoque = Estoque::where([
                        ['produto_id', '=', $produto->id],
                        ['loja_id', '=', $loja->id],
                    ])->first();

                    $actualizar = Estoque::findOrFail($actualizarEstoque->id);
                    $actualizar->stock = $actualizar->stock + $request->input("quantidade{$id}");
                    $actualizar->update();

                    Registro::create([
                        "registro" => "Receção de Encomenda",
                        "data_registro" => date('Y-m-d'),
                        "quantidade" => $request->input("quantidade{$id}"),
                        "observacao" => $encomenda->factura,
                        "encomenda_id" => $encomenda->id,
                        "produto_id" => $produto->id,
                        "loja_id" => $encomenda->loja_id,
                        "user_id" => Auth::user()->id,
                        "entidade_id" => $entidade->empresa->id,
                    ]);
                }
            }   
        }  
        
        $total = ItensEncomenda::where([
            ['code', '=', $encomenda->code]
        ])->sum('quantidade');

        if($total == 0){
            $encomenda->status = "entregue";
            $encomenda->update();

            $updateItensEncomenda = ItensEncomenda::where([
                ['code', '=', $encomenda->code]
            ])->get();

            foreach ($updateItensEncomenda as $item) {
                $up = ItensEncomenda::findOrFail($item->id);
                $up->status = "entregue";
                $up->update();
            }
        }
        
        $encomenda->quantidade_recebida = $encomenda->quantidade_recebida + $total_recebida;
        $encomenda->quantidade = $encomenda->quantidade - $total_recebida;
        $encomenda->update();
        
        return redirect()->route('fornecedores-encomendas.show', $encomenda->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function imprimir($id)
    {

        $encomenda = EncomendaFornecedore::with('fornecedor', 'user')->findOrFail($id);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);

        $facturas = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->get();

        $facturasPagas = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
            ['status2', '=', 'concluido'],
            ['status', true],
        ])
        ->get();

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
        ->sum('valor_pago');

        $totalPagoFactura = FacturaEncomendaFornecedor::where([
            ['status2', '=', 'nao concluido'],
            ['status', false],
            ['status3', '=', 'original'],
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->sum('total_pago');

        $totalValorFacturaSaldo = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
            ['status2', '=', 'nao concluido'],
            ['status3', '=', 'original'],
            ['status', false],
        ])
        ->sum('valor_divida');

        $totalValorPago = FacturaEncomendaFornecedor::where([
            ['status2', '=', 'concluido'],
            ['status', true],
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->sum('valor_pago');

         /********************************************** */


        /********************************************** */
        $totalFacturaPaga = FacturaEncomendaFornecedor::where([
            ['status2', '=', 'concluido'],
            ['status', true],
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->count();

        $totalValorFacturaNaoPaga = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
            ['status2', '=', 'nao concluido'],
            ['status3', '=', 'original'],
            ['status', false],
        ])
        ->sum('valor_factura');

        $totalValorFacturaSaldoNaoPaga = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
            ['status2', '=', 'nao concluido'],
            ['status3', '=', 'original'],
            ['status', false],
        ])
        ->sum('valor_divida');

        $totalValorPagoNaoPaga = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
            ['status2', '=', 'nao concluido'],
            ['status3', '=', 'original'],
            ['status', false],
        ])
        ->sum('valor_pago');

         /********************************************** */

        $items = ItensEncomenda::where([
            ['code', '=', $encomenda->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->with('produto')
        ->get();

        $head = [
            "titulo" => "Encomenda: {$encomenda->factura}",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "encomenda" => $encomenda,
            "facturas" => $facturas,
            "facturasPagas" => $facturasPagas,
            "items" => $items,
            "loja" => $entidade,

            "totalPagoFactura" => $totalPagoFactura,

            "totalValorFactura" => $totalValorFactura,
            "totalValorPago" => $totalValorPago,
            "totalFactura" => $totalFactura,
            "totalValorFacturaSaldo" => $totalValorFacturaSaldo,

            "totalValorFacturaNaoPaga" => $totalValorFacturaNaoPaga,
            "totalValorPagoNaoPaga" => $totalValorPagoNaoPaga,
            "totalFacturaPaga" => $totalFacturaPaga,
            "totalValorFacturaSaldoNaoPaga" => $totalValorFacturaSaldoNaoPaga,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
        
        
        $pdf = PDF::loadView('dashboard.fornecedores.encomendas.imprimir', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function imprimir_todas(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);    
        
        $encomendas = EncomendaFornecedore::when($request->status, function($query, $value){
            $query->where('status', '=', $value);
        })
        ->when($request->data_inicio, function($query, $value){
            $query->whereDate('created_at', '>=', Carbon::parse($value));
        })
        ->when($request->data_final, function($query, $value){
            $query->whereDate('created_at', '<=',Carbon::parse($value));
        })
        ->where('entidade_id', '=', $entidade->empresa->id)
        ->with('fornecedor')
        ->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => "Listagem das Encomendas",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "encomendas" => $encomendas,
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
            "requests" => $request->all("status", "data_inicio", "data_final"),
        ];
        
        $pdf = PDF::loadView('dashboard.fornecedores.encomendas.imprimir-todas', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();

    }

}
