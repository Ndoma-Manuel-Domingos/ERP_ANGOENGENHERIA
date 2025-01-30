<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitChavesSaft;
use App\Http\Controllers\TraitHelpers;
use App\Models\Subconta;
use App\Models\Caixa;
use App\Models\Cliente;
use App\Models\ContaCliente;
use App\Models\Entidade;
use App\Models\Estoque;
use App\Models\FacturaOriginal;
use App\Models\ItemFacturaOriginal;
use App\Models\ItemNotaCredito;
use App\Models\ItemRecibo;
use App\Models\Itens_venda;
use App\Models\Loja;
use App\Models\Movimento;
use App\Models\ContaBancaria;
use App\Models\OperacaoFinanceiro;
use App\Models\MovimentoContaCliente;
use App\Models\NotaCredito;
use App\Models\Produto;
use App\Models\Recibo;
use App\Models\Registro;
use App\Models\TipoPagamento;
use App\Models\User;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

use phpseclib\Crypt\RSA;

use PDF;

class FacturasController extends Controller
{
    use TraitChavesSaft;
    use TraitHelpers;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
            
        $user = auth()->user();
        
        if(!$user->can('listar facturas')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $facturas = Venda::with('cliente')
        ->when($request->tipo_documento, function($query, $value){
            $query->where('factura', '=', $value);
        })
        ->when($request->loja_id, function($query, $value){
            $query->where('loja_id', '=', $value);
        })
        ->when($request->data_inicio, function($query, $value){
            $query->whereDate('created_at', '>=', Carbon::parse($value));
        })
        ->when($request->data_final, function($query, $value){
            $query->whereDate('created_at', '<=',Carbon::parse($value));
        })
        ->where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->orderby('created_at', 'desc')
        ->get();
        
        //
        $head = [
            "titulo" => "Facturas",
            "descricao" => env('APP_NAME'),
            "caixa" => Caixa::where([
                ['active', true],
                ['entidade_id', '=', $entidade->empresa->id],
            ])->first(),
            "facturas" => $facturas,
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            'requests' => $request->all('data_inicio', 'data_final', 'loja_id', 'tipo_documento'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.facturas.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        
        if(!$user->can('criar facturas')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $movimentos = NULL;
        $total_pagar = NULL;
        $total_unidades = NULL;
        $total_produtos = NULL;

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
        $movimentos = Itens_venda::where([
            ['code', '=', NULL],
            ['status', '=', 'processo'],
            ['user_id','=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])->with('produto')->get();

        $total_pagar = Itens_venda::where([
            ['code', '=', NULL],
            ['status', '=', 'processo'],
            ['user_id','=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])->sum('valor_pagar');

        $total_retencao = Itens_venda::where([
            ['code', '=', NULL],
            ['status', '=', 'processo'],
            ['user_id','=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])->sum('retencao_fonte');

        $total_produtos = Itens_venda::where([
            ['code', '=', NULL],
            ['status', '=', 'processo'],
            ['entidade_id', '=', $entidade->empresa->id],
            ['user_id','=', Auth::user()->id],
        ])->count();

        $total_unidades = Itens_venda::where([
            ['code', '=', NULL],
            ['status', '=', 'processo'],
            ['entidade_id', '=', $entidade->empresa->id],
            ['user_id','=', Auth::user()->id],
        ])->sum('quantidade');
        
        $caixas = Caixa::where('entidade_id', $entidade->empresa->id)->get();
        $bancos = ContaBancaria::where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Criar Facturas",
            "descricao" => env('APP_NAME'), 
            "caixas" => $caixas,
            "bancos" => $bancos,
            "forma_pagmento" => TipoPagamento::get(),
            "caixa" => Caixa::where([
                ['active', true],
                ['entidade_id', '=', $entidade->empresa->id],
            ])->first(),
            "clientes" => Cliente::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "produtos" => Produto::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),

            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "movimentos" => $movimentos,
            "total_pagar"=> $total_pagar,
            "total_unidades"=> $total_unidades,
            "total_produtos"=> $total_produtos,
            "total_retencao"=> $total_retencao,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.facturas.create', $head);
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
        
        if(!$user->can('criar facturas')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
    
                
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
                    
            $movimentos = Itens_venda::where('code', NULL)
                ->where('entidade_id', '=', $entidade->empresa->id)
                ->where('status', '=', 'processo')
                ->where('user_id','=', Auth::user()->id)
                ->get();
                
            if(count($movimentos) == 0){
                return response()->json(['error' => true, 'message' => "Por favor, selecione itens para esta documentos!"], 404);
            }
                       
            $code = uniqid(time());
    
            $cliente = Cliente::findOrFail($request->cliente_id);
        
            $subconta_cliente = Subconta::where('code', $cliente->code)->first();
           
            // registro contabilisticos
            if($request->factura == "FR"){
             
                if($request->forma_de_pagamento == "NU"){
                
                    if($request->caixa_id == ""){
                        return response()->json(['message' => 'Deves selecionar o caixa onde será retirado o valor para o pagamento da factura!'], 404);
                        // return redirect()->back()->with('danger', 'Deves selecionar o caixa onde será retirado o valor para o pagamento da factura!');
                    } 
                    $valor_cash = $request->valor_entregue;
                    $valor_multicaixa = 0;
                    $request->total_pagar = $request->valor_entregue;
                    
                    
                    $subconta_caixa = Subconta::where('code', $request->caixa_id)->first();
                    
                    #VAMOS DEBITAR NO CAIXA OU SEJA VAMOS AUMENTAR O DINHEIRO DO CAIXA
                    
                    $this->registra_movimentos($subconta_caixa->id, $code, $request->observacao, $request->data_emissao, $entidade->empresa->id, "E", 0, $request->total_pagar);
                    
                    $this->registra_operacoes($request->total_pagar, $subconta_caixa->id, $cliente->id, "R", 'pago', $code, "E", $request->data_emissao, $entidade->empresa->id, $request->observacao);
                              
                    ## CREDITAR CLEINTE
                    $this->registra_movimentos($subconta_cliente->id, $code, $request->observacao, $request->data_emissao, $entidade->empresa->id, "S", $request->total_pagar, 0);
                }
                
                if($request->forma_de_pagamento == "MB" || $request->forma_de_pagamento == "TE" || $request->forma_de_pagamento == "DE"){
                      
                    
                    if($request->banco_id == ""){
                        return response()->json(['message' => 'Deves selecionar o banco onde será retirado o valor para o pagamento da factura!'], 404);
                        // return redirect()->back()->with('danger', 'Deves selecionar o banco onde será retirado o valor para o pagamento da factura!');
                    }
                    
                    $valor_cash = 0;
                    $valor_multicaixa = $request->valor_entregue_multicaixa;
                    $request->total_pagar = $request->valor_entregue_multicaixa;
                    
                    $subconta_banco = Subconta::where('code', $request->banco_id)->first();
                    
                    #VAMOS DEBITAR NO BANCO OU SEJA VAMOS TIRAR O DINHEIRO DO BANCO SELECIONADO
                    $this->registra_movimentos($subconta_banco->id, $code, $request->observacao, $request->data_emissao, $entidade->empresa->id, "E", 0, $request->total_pagar);
                    
                    $this->registra_operacoes($request->total_pagar, $subconta_banco->id, $cliente->id, "R", 'pago', $code, "E", $request->data_emissao, $entidade->empresa->id, $request->observacao);
                    
                    ## CREDITAR CLEINTE
                    $this->registra_movimentos($subconta_cliente->id, $code, $request->observacao, $request->data_emissao, $entidade->empresa->id, "S", $request->total_pagar, 0);
                    
                }
                
                if($request->forma_de_pagamento == "OU"){
                    if($request->caixa_id == ""){
                        return response()->json(['message' => 'Deves selecionar o caixa onde será retirado o valor para o pagamento da factura!'], 404);
                        // return redirect()->back()->with('danger', 'Deves selecionar o caixa onde será retirado o valor para o pagamento da factura!');
                    }
                    
              
                    $valor_cash =  $request->valor_entregue;
                    $valor_multicaixa = $request->valor_entregue_multicaixa_input;
                    
                    $subconta_caixa = Subconta::where('code', $request->caixa_id)->first();
                    
                    #VAMOS DEBITAR NO CAIXA OU SEJA VAMOS TIRAR O DINHEIRO DO CAIXA SELECIONADO
                    
                    $this->registra_movimentos($subconta_caixa->id, $code, $request->observacao, $request->data_emissao, $entidade->empresa->id, "E", 0, $request->valor_entregue);
                    
                    $this->registra_operacoes($request->valor_entregue, $subconta_caixa->id, $cliente->id, "R", 'pago', $code, "E", $request->data_emissao, $entidade->empresa->id, $request->observacao ?? "Lancamento");
                                         
                    ## CREDITAR CLEINTE
                    $this->registra_movimentos($subconta_cliente->id, $code, $request->observacao, $request->data_emissao, $entidade->empresa->id, "S", $request->valor_entregue, 0);
                
                    if($request->banco_id == ""){
                        // return redirect()->back()->with('danger', 'Deves selecionar o banco onde será retirado o valor para o pagamento da factura!');
                        return response()->json(['message' => 'Deves selecionar o banco onde será retirado o valor para o pagamento da factura!'], 404);
                    }
                    
                    $subconta_banco = Subconta::where('code', $request->banco_id)->first();
                    
                    #VAMOS DEBITAR NO BANCO OU SEJA VAMOS TIRAR O DINHEIRO DO BANCO SELECIONADO
                    
                    $this->registra_movimentos($subconta_banco->id, $code, $request->observacao, $request->data_emissao, $entidade->empresa->id, "E", 0, $request->valor_entregue_multicaixa);
                    
                    $this->registra_operacoes($request->valor_entregue_multicaixa, $subconta_caixa->id, $cliente->id, "R", 'pago', $code, "E", $request->data_emissao, $entidade->empresa->id, $request->observacao);
                    
                    ## CREDITAR CLEINTE
                    $this->registra_movimentos($subconta_cliente->id, $code, $request->observacao, $request->data_emissao, $entidade->empresa->id, "S", $request->valor_entregue_multicaixa, 0);
                }
                
            }
            
        
            $contarFactura = Venda::where([
                ['factura', '=', $request->factura],
                ['ano_factura', '=', $entidade->empresa->ano_factura],
                ['entidade_id', '=', $entidade->empresa->id],
            ])->count();
            
            $ultimoRecibo = Venda::where([
                ['factura', '=', $request->factura],
                ['ano_factura', '=', $entidade->empresa->ano_factura],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
            ->orderBy('id', 'DESC')
            ->first();
            
            if(!$ultimoRecibo){
                $hashAnterior = "";
            }else{
                $hashAnterior = $ultimoRecibo->hash;
            }
    
            $dias = 0;
            if($request->data_vencimento == 0){
                $dias = 0;
            }else if($request->data_vencimento == 15){
                $dias = 15;
            }else if($request->data_vencimento == 30){
                $dias = 30;
            }else if($request->data_vencimento == 45){
                $dias = 45;
            }else if($request->data_vencimento == 60){
                $dias = 60;
            }else if($request->data_vencimento == 90){
                $dias = 90;
            } 
    
            $cliente = Cliente::findOrFail($request->cliente_id);
                
            $request->data_emissao = $request->data_emissao . " " .date('H:i:s');
            
            //Manipulação de datas: data actual
            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', $request->data_emissao);
        
            $numeroFactura = $contarFactura + 1;
    
            $rsa = new RSA(); //Algoritimo RSA
    
            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();
    
            // Lendo a private key
            $rsa->loadKey($privatekey);
                
            $codigo_designacao_factura = "{$request->factura} {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$numeroFactura}";
    
            /**
            * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
            * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */
    
            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ";{$codigo_designacao_factura};" . number_format($request->total_pagar, 2, ".", "") . ';' . $hashAnterior;
            
            // HASH
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
    
            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)
    
            // Lendo a public key
            $rsa->loadKey($publickey);
            
    
            $request->data_vencimento = date('Y-m-d', strtotime($request->data_emissao. ' + ' . $dias .' days'));
            
            $statusFactura = "";
            if($request->factura == "FR"){
                $statusFactura = "pago";
                $retificado = "N";
                $convertido_factura = "N";
                $factura_divida = "N";
                $anulado = "N";
            }else{
                $statusFactura = "por pagar";
                $retificado = "N";
                $convertido_factura = "N";
                $factura_divida = "Y";
                $anulado = "N";
            }
            
            if($request->forma_de_pagamento == "NU"){
                $valor_cash = $request->total_pagar;
                $valor_multicaixa = 0;
            }else if($request->forma_de_pagamento == "MB"){
                $valor_cash = 0;
                $valor_multicaixa = $request->total_pagar;
            }else {
                $valor_cash = $request->valor_entregue;
                $valor_multicaixa = $request->valor_entregue_multicaixa;
            }
            
            $movimentos = Itens_venda::where([
                ['user_id','=', Auth::user()->id],
                ['status', '=', 'processo'],
                ['entidade_id', '=', $entidade->empresa->id],
                ['code', NULL],
            ])->get(); 
            
            $totalValorBase = 0;
            $totalValorIva = 0;
            $totalItems = 0;
            $totalDesconto = 0;
            $totalRetencao = 0;
    
            if($movimentos){
                foreach ($movimentos as $value) {
                    $update = Itens_venda::findOrFail($value->id);
                    $update->code = $code;
                    $update->status = "realizado";
                    $update->update();
    
                    $totalValorBase+= $value->valor_base;
                    $totalValorIva+= $value->valor_iva;
                    $totalItems+= $value->quantidade;
                    $totalDesconto+= $value->desconto_aplicado_valor;
                    $totalRetencao+= $value->retencao_fonte;
                }
            }
            $verificar_factura = Venda::where('factura_next', $codigo_designacao_factura)->where('ano_factura', $entidade->empresa->ano_factura)->where('entidade_id', $entidade->empresa->id)->get();
            
            if(count($verificar_factura) != 0){
                Alert::success('Sucesso', "Não pode concluir essa factura, parece que a mesma tentou ser duplicada, por favor verifica-se ja tens uma factura com esta referência: {$codigo_designacao_factura} !");
                return redirect()->route('facturas.create')->with('danger', "Não pode concluir essa factura, parece que a mesma tentou ser duplicada, por favor verifica-se ja tens uma factura com esta referência: {$codigo_designacao_factura} !");
            }
            
            $total__ = $request->total_pagar - $totalRetencao;
            
            $valor_extenso = $this->valor_por_extenso($total__);
        
            $create_factura = Venda::create([   
                'codigo_factura' => $numeroFactura,
                'status' => true,
                'status_venda' => "realizado",
                'status_factura' => $statusFactura,
                'user_id' => Auth::user()->id,
                'cliente_id' => $request->cliente_id,
                'valor_entregue' => 0,
                'valor_total' => $total__,
                'valor_divida' => $total__,
                'total_retencao_fonte' => $totalRetencao,
                'valor_pago' => 0,
                'ano_factura' => $entidade->empresa->ano_factura,
                'prazo' => $dias,
                'valor_troco' => $total__ - $total__,
                'data_emissao' => $request->data_emissao,
                'data_vencimento' => $request->data_vencimento,
                'data_disponivel' => $request->data_disponivel,
                'code' => $code,
                'desconto_percentagem' => $request->desconto_percentagem,
                'desconto' => $totalDesconto,
                'pagamento' => $request->forma_de_pagamento ?? "NU",
                'factura' => $request->factura,
                'factura_next' => $codigo_designacao_factura,
                'observacao' => $request->observacao,
                'referencia' => $request->referencia,
                'entidade_id' => $entidade->empresa->id,
                
                'nome_cliente' => $cliente->nome,
                'documento_nif' => $cliente->nif,
                
                'retificado' => $retificado,
                'convertido_factura' => $convertido_factura,
                'factura_divida' => $factura_divida,
                'anulado' => $anulado,
                
                'moeda' => $entidade->empresa->moeda ?? 'AOA',
                'valor_extenso' => $valor_extenso,
                'valor_cash' => $valor_cash,
                'valor_multicaixa' => $valor_multicaixa,
                'texto_hash' => $plaintext,
                'hash' => base64_encode($signaturePlaintext),
                'nif_cliente' => $cliente->nif,
                
                'total_iva' => $totalValorIva,
                'total_incidencia' => $totalValorBase,
                'quantidade' => $totalItems,
            ]);
            
            $movimentos = Itens_venda::where('code', $code)->get(); 

            if($movimentos){
                foreach($movimentos as $item){
                        
                    $subconta_iva = Subconta::where('numero', ENV('IVA_LIQUIDADO'))->first();
                    $subconta_venda_mercadoria= Subconta::where('numero', ENV('VENDA_DE_MERCADORIA'))->first();
                    $subconta_prestacao_servico = Subconta::where('numero', ENV('PRESTACAO_SERVICO'))->first();
                    $subconta_custo_mercadoria = Subconta::where('numero', ENV('CUSTO_MERCADORIA_VENDIDA'))->first();

                    $produt = Produto::findOrFail($item->produto_id); 
                    $subconta_servico_produto = Subconta::where('code', $produt->code)->first();
                           
                    if($request->factura == "FT" || $request->factura == "FR"){
                        if($subconta_servico_produto){
                            // caso o serviço/produto cobrar IVA
                            if($produt->taxa != 0){
                                if($subconta_iva){
                                    
                                    if($produt->tipo == "P"){
                                        ## creditar na conta proveito - 61/62/63/65 - ou seja diminuir o valor sem o iva
                                        $movimeto = Movimento::create([
                                            'user_id' => Auth::user()->id,
                                            'subconta_id' => $subconta_venda_mercadoria->id,
                                            'status' => true,
                                            'movimento' => 'S',
                                            'credito' => $item->valor_pagar,
                                            'debito' => 0,
                                            'observacao' => $request->observacao,
                                            'code' => $code,
                                            'data_at' => date("Y-m-d"),
                                            'entidade_id' => $entidade->empresa->id,
                                            'exercicio_id' => 1,
                                            'periodo_id' => 12,
                                        ]);
                                    }
                                    if($produt->tipo == "S"){
                                        ## creditar na conta proveito - 61/62/63/65 - ou seja diminuir o valor sem o iva
                                        $movimeto = Movimento::create([
                                            'user_id' => Auth::user()->id,
                                            'subconta_id' => $subconta_prestacao_servico->id,
                                            'status' => true,
                                            'movimento' => 'S',
                                            'credito' => $item->valor_pagar,
                                            'debito' => 0,
                                            'observacao' => $request->observacao,
                                            'code' => $code,
                                            'data_at' => date("Y-m-d"),
                                            'entidade_id' => $entidade->empresa->id,
                                            'exercicio_id' => 1,
                                            'periodo_id' => 12,
                                        ]);
                                    }
                                
                                    
                                    if($entidade->empresa->tipo_inventario == "PERMANENTE"){
                                        ## creditar na conta proveito - 26 - ou seja diminuir o valor sem o iva
                                        $movimeto = Movimento::create([
                                            'user_id' => Auth::user()->id,
                                            'subconta_id' => $subconta_servico_produto->id,
                                            'status' => true,
                                            'movimento' => 'S',
                                            'credito' => ($produt->preco_custo ?? 0) * $item->quantidade,
                                            'debito' => 0,
                                            'observacao' => $request->observacao,
                                            'code' => $code,
                                            'data_at' => date("Y-m-d"),
                                            'entidade_id' => $entidade->empresa->id,
                                            'exercicio_id' => 1,
                                            'periodo_id' => 12,
                                        ]);
                                        
                                        ## custo de mercadoria
                                        $movimeto = Movimento::create([
                                            'user_id' => Auth::user()->id,
                                            'subconta_id' => $subconta_custo_mercadoria->id,
                                            'status' => true,
                                            'movimento' => 'S',
                                            'credito' => 0,
                                            'debito' => ($produt->preco_custo ?? 0) * $item->quantidade,
                                            'observacao' => $request->observacao,
                                            'code' => $code,
                                            'data_at' => date("Y-m-d"),
                                            'entidade_id' => $entidade->empresa->id,
                                            'exercicio_id' => 1,
                                            'periodo_id' => 12,
                                        ]);
                                    }
                                    
                                    // ## creditar na conta do IVA LIQUIDADO - 34.5.3.1
                                    // $movimeto = Movimento::create([
                                    //     'user_id' => Auth::user()->id,
                                    //     'subconta_id' => $subconta_iva->id,
                                    //     'status' => true,
                                    //     'movimento' => 'S',
                                    //     'credito' => ($produt->preco_venda ?? 0) - ($produt->preco??0),
                                    //     'debito' => 0,
                                    //     'observacao' => $request->observacao,
                                    //     'code' => $code,
                                    //     'data_at' => date("Y-m-d"),
                                    //     'entidade_id' => $entidade->empresa->id,
                                    //     'exercicio_id' => 1,
                                    //     'periodo_id' => 12,
                                    // ]);
                                    
                                    ## creditar e debitar na conta 31 ou seja preciso aumentar a divida do clientes e depois liquidar da mesma divida
                                    ## START
                                    $movimeto = Movimento::create([
                                        'user_id' => Auth::user()->id,
                                        'subconta_id' => $subconta_cliente->id,
                                        'status' => true,
                                        'movimento' => 'E',
                                        'credito' => 0,
                                        'debito' => $item->valor_pagar ?? 0,
                                        'observacao' => $request->observacao,
                                        'code' => $code,
                                        'data_at' => date("Y-m-d"),
                                        'entidade_id' => $entidade->empresa->id,
                                        'exercicio_id' => 1,
                                        'periodo_id' => 12,
                                    ]);
                                    
                                    ## - END
                                    ## vamor aumentar o valor do caixa - 45/43
                                                        
                                }else{
                                    ## a conta do iva não esta cadastrada
                                }
                            }else {
                                ## caso o serviço/produto não cobra o iva ou 
                                
                                if($produt->tipo == "P"){
                                    ## creditar na conta proveito - 61/62/63/65 - ou seja diminuir o valor sem o iva
                                    $movimeto = Movimento::create([
                                        'user_id' => Auth::user()->id,
                                        'subconta_id' => $subconta_venda_mercadoria->id,
                                        'status' => true,
                                        'movimento' => 'S',
                                        'credito' => $item->valor_pagar ?? 0,
                                        'debito' => 0,
                                        'observacao' => $request->observacao,
                                        'code' => $code,
                                        'data_at' => date("Y-m-d"),
                                        'entidade_id' => $entidade->empresa->id,
                                        'exercicio_id' => 1,
                                        'periodo_id' => 12,
                                    ]);
                                }
                                
                                if($produt->tipo == "S"){
                                    ## creditar na conta proveito - 61/62/63/65 - ou seja diminuir o valor sem o iva
                                    $movimeto = Movimento::create([
                                        'user_id' => Auth::user()->id,
                                        'subconta_id' => $subconta_prestacao_servico->id,
                                        'status' => true,
                                        'movimento' => 'S',
                                        'credito' => $item->valor_pagar ?? 0,
                                        'debito' => 0,
                                        'observacao' => $request->observacao,
                                        'code' => $code,
                                        'data_at' => date("Y-m-d"),
                                        'entidade_id' => $entidade->empresa->id,
                                        'exercicio_id' => 1,
                                        'periodo_id' => 12,
                                    ]);
                                }
                                
                                if($entidade->empresa->tipo_inventario == "PERMANENTE"){
                                    ## creditar na conta proveito - 26 - ou seja diminuir o valor sem o iva
                                    $movimeto = Movimento::create([
                                        'user_id' => Auth::user()->id,
                                        'subconta_id' => $subconta_servico_produto->id,
                                        'status' => true,
                                        'movimento' => 'S',
                                        'credito' => ($produt->preco_custo ?? 0) * $item->quantidade,
                                        'debito' => 0,
                                        'observacao' => $request->observacao,
                                        'code' => $code,
                                        'data_at' => date("Y-m-d"),
                                        'entidade_id' => $entidade->empresa->id,
                                        'exercicio_id' => 1,
                                        'periodo_id' => 12,
                                    ]);
                                     
                                    ## custo de mercadoria
                                    $movimeto = Movimento::create([
                                        'user_id' => Auth::user()->id,
                                        'subconta_id' => $subconta_custo_mercadoria->id,
                                        'status' => true,
                                        'movimento' => 'S',
                                        'credito' => 0,
                                        'debito' => ($produt->preco_custo ?? 0) * $item->quantidade,
                                        'observacao' => $request->observacao,
                                        'code' => $code,
                                        'data_at' => date("Y-m-d"),
                                        'entidade_id' => $entidade->empresa->id,
                                        'exercicio_id' => 1,
                                        'periodo_id' => 12,
                                    ]);
                                }
                                
                                ## creditar e debitar na conta 31 ou seja preciso aumentar a divida do clientes e depois liquidar da mesma divida
                                ## START
                                $movimeto = Movimento::create([
                                    'user_id' => Auth::user()->id,
                                    'subconta_id' => $subconta_cliente->id,
                                    'status' => true,
                                    'movimento' => 'E',
                                    'credito' => 0,
                                    'debito' => $item->valor_pagar ?? 0,
                                    'observacao' => $request->observacao,
                                    'code' => $code,
                                    'data_at' => date("Y-m-d"),
                                    'entidade_id' => $entidade->empresa->id,
                                    'exercicio_id' => 1,
                                    'periodo_id' => 12,
                                ]);
                                ## - END
                            }
                        }else {
                            ## subconta do produto não encontrado
                        }
                    }
                    
                    $update = Itens_venda::findOrFail($item->id);
                    $update->factura_id = $create_factura->id;
                    $update->update();
                }
            }
        
            if($statusFactura == "por pagar"){
             
                $create = MovimentoContaCliente::create([
                    'user_id' => Auth::user()->id,
                    'documento' => $codigo_designacao_factura,
                    'observacao' => $request->observacao,
                    'montante' => $total__,
                    'cliente_id' => $request->cliente_id,
                    'data_emissao' => $request->data_emissao,
                    'tipo_movimento' => -1,
                    'entidade_id' => $entidade->empresa->id,
                ]);  
    
                if($create->save()){
                    $actualizarConta = ContaCliente::findOrFail($request->cliente_id);
                    $actualizarConta->divida_corrente = $actualizarConta->divida_corrente + $request->montante;
                    $actualizarConta->update();
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

        // Alert::success('Sucesso', 'Venda realizada com sucesso!');
        // return redirect()->route('facturas.index');
        return response()->json(['success' => true, 'factura' => $create_factura]);
    }

    public function factura_adicionar_produto($id)
    {
        $user = auth()->user();
        
        if(!$user->can('criar facturas')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
     
        try {
            // Inicia a transação
            DB::beginTransaction();
        
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
    
            $produto = Produto::with('marca','variacao','categoria', 'estoque')->findOrFail($id);
                
            if($produto->tipo == 'P'){
                $loja = Loja::where([
                    ['status', '=', 'activo'],
                    ['entidade_id', '=', $entidade->empresa->id], 
                ])->first();
                
                if(!$loja){
                    Alert::warning('Atenção', 'Não têm nenhuma loja/armazém activa no momento para registrar saída deste produto. Por favor activa uma loja/armazém que tem este produto!');
                    return redirect()->back()->with('warning', 'Não têm nenhuma loja/armazém activa no momento para registrar saída deste produto.');
                }
        
                // verificar quantidade de produto no estoque da loja
                $verificar_quantidade = Estoque::where('loja_id', $loja->id)
                ->where('produto_id', $produto->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->sum('stock');
                
                $verificar_quantidade = (int) $verificar_quantidade;
                            
                if($verificar_quantidade <= 0){
                    Alert::warning('Atenção', 'A Loja activa não têm este produto em stock para poder comercializar!');
                    return redirect()->back()->with('warning', 'A Loja activa não têm este produto em stock para poder comercializar!');
                }
                
                if($produto->estoque){
                    if($produto->estoque->stock <= $produto->estoque->stock_minimo){
                        Alert::warning('Atenção', 'A quantidade deste produto em estoque está abaixo do limite crítico, impedindo a venda no momento.');
                        return redirect()->back();
                    }       
                }else{
                    Alert::warning('Atenção', 'A quantidade deste produto em estoque está abaixo do limite crítico, impedindo a venda no momento.');
                    return redirect()->back();
                } 
                
                Registro::create([
                    "registro" => "Saída de Stock",
                    "data_registro" => date('Y-m-d'),
                    "quantidade" => 1,
                    "produto_id" => $produto->id,
                    "observacao" => "Saída do produto {$produto->nome} para venda",
                    "loja_id" => $loja->id,
                    "lote_id" => NULL,
                    "user_id" => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
                
            }
    
            // calcudo do total de incidencia
            //________________ valor total _____________
            $valorBase = $produto->preco * 1; 
            // calculo do iva
            $valorIva = ($produto->taxa / 100) * $valorBase;
    
            $verificarProdutoAdicionado = Itens_venda::where([
                ['status', '=', 'processo'],
                ['produto_id', '=', $produto->id],
                ['user_id', '=', Auth::user()->id],
                ['entidade_id', '=', $entidade->empresa->id]
            ])->first();

            if($verificarProdutoAdicionado){
                
                $update_item = Itens_venda::findOrFail($verificarProdutoAdicionado->id);
                
                // Verifica se os dados estão corretos
                if (!$produto || !$produto->estoque) {
                    throw new \Exception('Dados do produto ou estoque não encontrados.');
                }
                if ($produto->tipo == 'P' && $produto->estoque->stock <= 0) {
                    throw new \Exception('Estoque insuficiente.');
                }
                
                $update_item->update([
                
                ]);
                
                
                $newQuantid = $update_item->quantidade + 1;
                
                $desconto = ($produto->preco * $newQuantid) * (($update_item->desconto_aplicado ?? 0) / 100);
                
                $valorBase = $produto->preco * $newQuantid; 
                // calculo do iva
                $valorIva = ($produto->taxa ??0) / 100 * $valorBase;
                
                $retencao_fonte = 0;
                
                $valor_ = $valorBase + $valorIva;
                
                if($produto->tipo == "S"){
                    $retencao_fonte = $valor_ * ($entidade->empresa->taxa_retencao_fonte ?? 0) / 100;
                }else {
                    $retencao_fonte = 0;
                }
            
                $update_item->quantidade = $newQuantid;
                $update_item->valor_pagar = $valor_ - $desconto;

                $update_item->retencao_fonte = $retencao_fonte;
                $update_item->desconto_aplicado = $update_item->desconto_aplicado;
                $update_item->desconto_aplicado_valor = $desconto;
                $update_item->custo_ganho = ($produto->preco - $produto->preco_custo) * $newQuantid;

                $update_item->valor_base = $valorBase;
                $update_item->valor_iva = $valorIva;
                $update_item->save();
                
                  // Atualiza o estoque
                if ($produto->tipo == 'P') {
                    $produto->estoque->stock -= 1;
                    $produto->estoque->save();
                }
                
                
                return redirect()->route('facturas.create');
            }else{
                
                $retencao_fonte = 0;
                
                if($produto->tipo == "S"){
                    $valor_ = $valorBase + $valorIva;
                    $retencao_fonte = $valor_ * $entidade->empresa->taxa_retencao_fonte / 100;
                }else {
                    $retencao_fonte = 0;
                }
            
                Itens_venda::create(
                    [
                        'produto_id' => $produto->id,
                        //'caixa_id' => $caixaActivo->id,
                        'quantidade' => 1,
                        'valor_pagar' => $valorBase + $valorIva,
                        'preco_unitario' => $produto->preco,
                        'custo_ganho' => ($produto->preco - $produto->preco_custo) * 1,
                        'desconto_aplicado' => 0,
                        'status' => 'processo',
                        'valor_base' => $valorBase,
                        'valor_iva' => $valorIva,
                        'retencao_fonte' => $retencao_fonte,
                        'desconto_aplicado_valor' => 0,
                        'iva' => $produto->imposto,
                        'iva_taxa' => $produto->taxa,
                        'texto_opcional' => "",
                        'code' => NULL,
                        'numero_serie' => "",
                        'user_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]
                );  
                
                if($produto->tipo == 'P'){
                    $produto->estoque->stock = $produto->estoque->stock - 1; 
                    $produto->estoque->update(); 
                }
             
            }
            

            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger('Error', $e->getMessage());
            return redirect()->back()->with('danger', $e->getMessage());
            // return Response()->json($e->getMessage());
            // Trate o erro ou exiba uma mensagem de falha
            // por exemplo: return response()->json(['message' => 'Erro ao salvar'], 500);
        }
        
        
        return redirect()->route('facturas.create');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //                    
        $user = auth()->user();
        
        if(!$user->can('listar facturas')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
    
        $factura = Venda::with('cliente')->findOrFail($id);
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $movimentos = Itens_venda::where([
            ['code','=', $factura->code],
            ['user_id','=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])->with('produto')->get();

        $total_pagar = Itens_venda::where([
            ['code','=', $factura->code],
            ['user_id','=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])->sum('valor_pagar');

        $total_pagar = $factura->valor_divida;
        
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);
        
        $caixas = Caixa::where('entidade_id', $entidade->empresa->id)->get();
        $bancos = ContaBancaria::where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => "Detalhe Factura",
            "descricao" => env('APP_NAME'),
            "factura" => $factura,
            "movimentos" => $movimentos,
            "total_pagar" => $total_pagar,
            "caixas" => $caixas,
            "bancos" => $bancos,
            "forma_pagmento" => TipoPagamento::get(),
            "caixa" => Caixa::where([
                ['active', true],
                ['entidade_id', '=', $entidade->empresa->id],
            ])->first(),
            "clientes" => Cliente::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "produtos" => Produto::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
            
            "empresa" => $empresa,
        ];

        return view('dashboard.facturas.show', $head);
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
        $user = auth()->user();
        
        if(!$user->can('editar facturas')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
    
    
        $factura = Venda::with('cliente')->findOrFail($id);
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $movimentos = Itens_venda::where([
            ['entidade_id', '=', $entidade->empresa->id], 
            ['code','=', $factura->code],
            ['user_id','=', Auth::user()->id],
        ])->with('produto')->get();

        $total_pagar = Itens_venda::where([
            ['code','=', $factura->code],
            ['user_id','=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->sum('valor_pagar');

        $head = [
            "titulo" => "Detalhe Factura",
            "descricao" => env('APP_NAME'),
            "factura" => $factura,
            "movimentos" => $movimentos,
            "total_pagar" => $total_pagar,
            "caixa" => Caixa::where([
                ['active', true],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->first(),
            "clientes" => Cliente::where([
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->get(),
            "produtos" => Produto::where([
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->get(),
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.facturas.edit', $head);
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
                
        $user = auth()->user();
        
        if(!$user->can('editar facturas')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }    
        
        
             
        try {
            // Inicia a transação
            DB::beginTransaction();

    
            $venda = Venda::findOrFail($id);
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            
            $caixaActivo = Caixa::where([
                ['active', true],
                ['user_id', '=', Auth::user()->id],
                ['entidade_id', '=', $entidade->empresa->id],
            ])->first();
    
    
            /** dados da nova factura */
            $contarFacturaNovo = 0;
            
            if($venda->factura != $request->factura){
                $contarFacturaNovo = Venda::where([
                    ['entidade_id', '=', $entidade->empresa->id], 
                    ['factura', '=', $request->factura],
                    ['user_id', '=', Auth::user()->id],
                    ['ano_factura', '=', date("Y")],
                ])->count();
            }else{
                $contarFacturaNovo = 0;
            }
    
            $anoNovo = date("Y");
            $numeroFacturaNovo = $contarFacturaNovo + 1;
    
            /***
             * registrar o pagamento original
             */
            $factura_original = FacturaOriginal::create([
                'status' => $venda->status, 
                'status_venda' => $venda->status_venda, 
                'status_factura' => $venda->status_factura, 
                'user_id' => $venda->user_id, 
                'caixa_id' => $venda->caixa_id, 
                'factura_id' => $venda->id, 
                'data_disponivel' => $venda->data_disponivel, 
                'cliente_id' => $venda->cliente_id, 
                'loja_id' => $venda->loja_id, 
                'valor_entregue' => $venda->valor_entregue, 
                'valor_total' => $venda->valor_total, 
                'data_emissao' => $venda->data_emissao, 
                'data_vencimento' => $venda->data_vencimento, 
                'valor_troco' => $venda->valor_troco, 
                'code' => $venda->code, 
                'pagamento' => $venda->pagamento, 
                'factura' => $venda->factura, 
                'factura_next' => $venda->factura_next, 
                'codigo_factura' => $venda->codigo_factura, 
                'ano_factura' => $venda->ano_factura, 
                'prazo' => $venda->prazo, 
                'desconto' => $venda->desconto, 
                'retificado' => $venda->retificado, 
                'convertido_factura' => $venda->convertido_factura, 
                'factura_divida' => $venda->factura_divida, 
                'anulado' => $venda->anulado, 
                'quantidade' => $venda->quantidade, 
        
                'total_iva' => $venda->total_iva, 
                'valor_cash' => $venda->valor_cash, 
                'valor_multicaixa' => $venda->valor_multicaixa, 
        
                'numeracao_proforma' => $venda->numeracao_proforma, 
                'moeda' => $venda->moeda, 
                'total_incidencia' => $venda->total_incidencia, 
                'valor_extenso' => $venda->valor_extenso, 
                'texto_hash' => $venda->texto_hash, 
                'hash' => $venda->hash, 
                'conta_corrente_cliente' => $venda->conta_corrente_cliente, 
                'nif_cliente' => $venda->nif_cliente, 
                'desconto_percentagem' => $venda->desconto_percentagem, 
                'observacao' => $venda->observacao, 
                'referencia' => $venda->referencia, 
                'entidade_id' => $venda->entidade_id, 
            ]);
    
            if($factura_original->save()){
                $movimentos = Itens_venda::where([
                    ['code', "=", $venda->code],
                ])->get(); 
    
                if($movimentos){
                    foreach($movimentos as $movimento){
                        ItemFacturaOriginal::create([
                            'produto_id' => $movimento->produto_id,
                            'factura_id' => $factura_original->id,
                            'movimento_id' => $movimento->movimento_id,
                            'user_id' => $movimento->user_id,
                            'quantidade' => $movimento->quantidade,
                            'status' => $movimento->status,
                            'valor_iva' => $movimento->valor_iva,
                            'valor_base' => $movimento->valor_base,
                            'valor_pagar' => $movimento->valor_pagar,
                            'preco_unitario' => $movimento->preco_unitario,
                            'desconto_aplicado' => $movimento->desconto_aplicado,
                            'desconto_aplicado_valor' => $movimento->desconto_aplicado_valor,
                            'iva' => $movimento->iva,
                            'iva_taxa' => $movimento->iva_taxa,
                            'texto_opcional' => $movimento->texto_opcional,
                            'code' => $movimento->code,
                            'numero_serie' => $movimento->numero_serie,
                            'entidade_id' => $movimento->entidade_id,
                            'user_id' => $movimento->user_id,
                        ]);
                    }
                }
            }
    
            /**
             * end registro factura items
            */
    
            // criar nota de credito
            $contarFactura = NotaCredito::where([
                ['factura', '=', 'NC'],
                ['ano_factura', '=', date("Y")],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->count();
    
            $ultimoRecibo = NotaCredito::where([
                ['factura', '=',  'NC'],
                ['ano_factura', '=', date("Y")],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
            ->orderBy('id', 'DESC')
            ->first();
    
            if(!$ultimoRecibo){
                $hashAnterior = "";
            }else{
                $hashAnterior = $ultimoRecibo->hash;
            }
    
            //Manipulação de datas: data actual
            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
    
            $ano = date("Y");
            $numeroFactura = $contarFactura + 1;
    
            $rsa = new RSA(); //Algoritimo RSA
    
            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();
    
            // Lendo a private key
            $rsa->loadKey($privatekey);
            
            $codigo_designacao_factura = ENV('DESIGNACAO_FACTURA');
    
            /**
            * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
            * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */
    
            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "NC {$codigo_designacao_factura}{$ano}/{$numeroFactura}" . ';' . number_format($venda->valor_total, 2, ".", "") . ';' . $hashAnterior;
            // HASH
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
    
            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)
    
            // Lendo a public key
            $rsa->loadKey($publickey);
    
            $nota = NotaCredito::create([
                'status' => true,
                'status_factura' => 'anulada',
                'status_venda' => "anulada",
                'user_id' => $venda->user_id,
                'caixa_id' => $venda->caixa_id,
                'cliente_id' => $venda->cliente_id,
                'loja_id' => $venda->loja_id,
                'factura_id' => $venda->id,
                'valor_entregue' => $venda->valor_entregue,
                'valor_total' => $venda->valor_total,
                
                'prazo' => 0,
                'data_emissao' => date("y-m-d"),
                'data_vencimento' => date("y-m-d"),
                'data_disponivel' => date("y-m-d"),
    
                
                'valor_troco' => $venda->valor_troco,
                'code' => $venda->code,
                'pagamento' => $venda->pagamento,
                'factura' => 'NC',
                'codigo_factura' =>  $numeroFactura,
                'factura_next' => "NC {$codigo_designacao_factura}{$ano}/{$numeroFactura}",
                'ano_factura' => date('Y'),
                'desconto' => $venda->desconto,
    
                'retificado' => $venda->retificado,
                'convertido_factura' => $venda->convertido_factura,
                'factura_divida' => $venda->factura_divida,
                'anulado' => 'Y',
    
                'quantidade' => $venda->quantidade,
        
                'total_iva' => $venda->total_iva,
                'valor_cash' => $venda->valor_cash,
                'valor_multicaixa' => $venda->valor_multicaixa,
        
                'numeracao_proforma' => $venda->factura_next,
                'moeda' => $venda->moeda,
                'total_incidencia' => $venda->total_incidencia,
                'valor_extenso' => $venda->valor_extenso,
                'texto_hash' => $plaintext,
                'hash' => base64_encode($signaturePlaintext),
                'conta_corrente_cliente' => $venda->conta_corrente_cliente,
                'nif_cliente' => $venda->nif_cliente,
                'desconto_percentagem' => $venda->desconto_percentagem,
                'observacao' => $venda->observacao,
                'referencia' => $venda->referencia,
                'entidade_id' => $venda->entidade_id,
            ]);
    
            $movimentos = Itens_venda::where([
                ['code','=' ,$venda->code],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->get();
    
            if($movimentos){
                foreach ($movimentos as $items) {
                    ItemNotaCredito::create([
                        'produto_id' => $items->produto_id,
                        'factura_id' => $nota->id,
                        'movimento_id' => $items->movimento_id,
                        'user_id' => $items->user_id,
                        'quantidade' => $items->quantidade,
                        'status' => $items->status,
                        'valor_iva' => $items->valor_iva,
                        'valor_base' => $items->valor_base,
                        'valor_pagar' => $items->valor_pagar,
                        'preco_unitario' => $items->preco_unitario,
                        'desconto_aplicado' => $items->desconto_aplicado,
                        'desconto_aplicado_valor' => $items->desconto_aplicado_valor,
                        'iva' => $items->iva,
                        'iva_taxa' => $items->iva_taxa,
                        'texto_opcional' => $items->texto_opcional,
                        'code' => $items->code,
                        'numero_serie' => $items->numero_serie,
                        'entidade_id' => $items->entidade_id,
                        'user_id' => $items->user_id,
                    ]);
                }
            }
            /** end nota credito */
    
            $ultimoRecibo = Venda::where([
                ['factura', '=',  $request->factura],
                ['ano_factura', '=', date("Y")],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
            ->orderBy('id', 'DESC')
            ->first();
    
            if(!$ultimoRecibo){
                $hashAnterior = "";
            }else{
                $hashAnterior = $ultimoRecibo->hash;
            }
    
            //Manipulação de datas: data actual
            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
    
            $ano = date("Y");
            $numeroFactura = $contarFacturaNovo + 1;
    
           
            $rsa = new RSA(); //Algoritimo RSA
    
            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();
    
            // Lendo a private key
            $rsa->loadKey($privatekey);
            $codigo_designacao_factura = ENV('DESIGNACAO_FACTURA');
            /**
            * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
            * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */
    
            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "{$request->factura} {$codigo_designacao_factura}{$ano}/{$numeroFactura}" . ';' . number_format($request->total_pagar, 2, ".", "") . ';' . $hashAnterior;
            // HASH
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
    
            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)
    
            // Lendo a public key
            $rsa->loadKey($publickey);
    
    
            $dias = 0;
            if($request->data_vencimento == 0){
                $dias = 0;
            }else if($request->data_vencimento == 15){
                $dias = 15;
            }else if($request->data_vencimento == 30){
                $dias = 30;
            }else if($request->data_vencimento == 45){
                $dias = 45;
            }else if($request->data_vencimento == 60){
                $dias = 60;
            }else if($request->data_vencimento == 90){
                $dias = 90;
            } 
    
            $request->data_vencimento = date('Y-m-d', strtotime($request->data_emissao. ' + ' . $dias .' days'));
    
            $statusFactura = "";
            if($request->data_emissao == $request->data_vencimento){
                $statusFactura = "pago";
            }else{
                $statusFactura = "por pagar";
            }
            
            $venda->codigo_factura = $numeroFacturaNovo;
            $venda->status_factura = $statusFactura;
            $venda->status = true;
            $venda->status_venda = "realizado";
            $venda->status_venda = "reficada";
            $venda->cliente_id = $request->cliente_id;
            $venda->loja_id = $caixaActivo->loja_id;
            $venda->valor_entregue = $request->total_pagar;
            $venda->valor_total = $request->total_pagar;
            $venda->valor_troco = 0;
            $venda->ano_factura = $anoNovo;
            $venda->prazo = $dias;
            $venda->data_emissao = $request->data_emissao;
            $venda->data_vencimento = $request->data_vencimento;
            $venda->data_disponivel = $request->data_disponivel;
            $venda->desconto_percentagem = $request->desconto_percentagem;
            $venda->desconto = $request->desconto;
            $venda->pagamento = $request->forma_pagamento;
            $venda->factura = $request->factura;
            $venda->factura_next = "{$request->factura} {$codigo_designacao_factura}{$anoNovo}/{$numeroFacturaNovo}";
            $venda->observacao = $request->observacao;
            $venda->referencia = $request->referencia;
            $venda->retificado  = 'Y';
            $venda->texto_hash = $plaintext;
            $venda->hash = base64_encode($signaturePlaintext);
    
            if($venda->update()){
                $movimentos = Itens_venda::where([
                    ['code', "=", $venda->code],
                    ['entidade_id', '=', $entidade->empresa->id],
                ])->get();        
                
                $totalValorBase = 0;
                $totalValorIva = 0;
                $totalItems = 0; 
    
                if($movimentos){
                    foreach ($movimentos as $value) {
                        $update = Itens_venda::findOrFail($value->id);
                        $update->status = "realizado";
                        $update->update();
    
                        $totalValorBase+= $value->valor_base;
                        $totalValorIva+= $value->valor_iva;
                        $totalItems+= $value->quantidade;
                    }
                }
            }
            
            $venda->total_iva = $totalValorIva;
            $venda->total_incidencia = $totalValorBase;
            $venda->quantidade = $totalItems;
    
            $venda->save();
            
            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger('Error', $e->getMessage());
            return redirect()->back()->with('danger', $e->getMessage());
            // return Response()->json($e->getMessage());
            // Trate o erro ou exiba uma mensagem de falha
            // por exemplo: return response()->json(['message' => 'Erro ao salvar'], 500);
        }
        

        Alert::success('Success', 'Factura Actualizada com sucesso!');
        return redirect()->route('facturas.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = auth()->user();
        
        if(!$user->can('eliminar facturas')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function converter_factura($id)
    {
        //
        $user = auth()->user();
        
        if(!$user->can('editar facturas') || !$user->can('criar facturas')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $factura = Venda::with('cliente')->findOrFail($id);
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $movimentos = Itens_venda::where([
            ['entidade_id', '=', $entidade->empresa->id], 
            ['code','=', $factura->code],
            ['user_id','=', Auth::user()->id],
        ])->with('produto')->get();

        $total_pagar = Itens_venda::where([
            ['code','=', $factura->code],
            ['user_id','=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->sum('valor_pagar');

        $head = [
            "titulo" => "Detalhe Factura",
            "descricao" => env('APP_NAME'),
            "factura" => $factura,
            "movimentos" => $movimentos,
            "total_pagar" => $total_pagar,
            "caixa" => Caixa::where([
                ['active', true],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->first(),
            "clientes" => Cliente::where([
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->get(),
            "produtos" => Produto::where([
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->get(),
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.facturas.converter', $head);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function converter_factura_put(Request $request, $id)
    {
                       
        $user = auth()->user();
        
        if(!$user->can('editar facturas') || !$user->can('criar facturas')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
    
        $venda = Venda::findOrFail($id);
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        

        if($venda->factura == $request->factura){
            Alert::warning('Success', 'Factura não pode ser convertida, porque é do mesmo tipo com a antiga!');
            return redirect()->route('converter_factura', $id);
        }

        /** dados da nova factura */
        $contarFacturaNovo = 0;
        
        if($venda->factura != $request->factura){
            $contarFacturaNovo = Venda::where([
                ['entidade_id', '=', $entidade->empresa->id], 
                ['factura', '=', $request->factura],
                ['user_id', '=', Auth::user()->id],
                ['ano_factura', '=', date("Y")],
            ])->count();
        }else{
            $contarFacturaNovo = 0;
        }
        
        $numeroFacturaNovo = $contarFacturaNovo + 1;

        if($request->factura == "RG"){
            // criar nota de credito
            $contarFactura = Recibo::where([
                ['factura', '=', 'RG'],
                ['ano_factura', '=', date("Y")],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->count();

            $ultimoRecibo = Recibo::where([
                ['factura', '=',  'RG'],
                ['ano_factura', '=', date("Y")],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
            ->orderBy('id', 'DESC')
            ->first();

            if(!$ultimoRecibo){
                $hashAnterior = "";
            }else{
                $hashAnterior = $ultimoRecibo->hash;
            }

            //Manipulação de datas: data actual
            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

            $ano = date("Y");
            $numeroFactura = $contarFactura + 1;

            $rsa = new RSA(); //Algoritimo RSA

            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();

            // Lendo a private key
            $rsa->loadKey($privatekey);
            
            $codigo_designacao_factura = ENV('DESIGNACAO_FACTURA');
            /**
            * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
            * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */

            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "RG {$codigo_designacao_factura}{$ano}/{$numeroFactura}" . ';' . number_format($venda->valor_total, 2, ".", "") . ';' . $hashAnterior;
            // HASH
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

            // Lendo a public key
            $rsa->loadKey($publickey);

                
            $recibo = Recibo::create([
                'status' => true,
                'status_factura' => 'convertida',
                'status_venda' => "convertida",
                'user_id' => $venda->user_id,
                'caixa_id' => $venda->caixa_id,
                'cliente_id' => $venda->cliente_id,
                'loja_id' => $venda->loja_id,
                'factura_id' => $venda->id,
                'valor_entregue' => $venda->valor_entregue,
                'valor_total' => $venda->valor_total,
                
                'prazo' => 0,
                'data_emissao' => date("y-m-d"),
                'data_vencimento' => date("y-m-d"),
                'data_disponivel' => date("y-m-d"),

                
                'valor_troco' => $venda->valor_troco,
                'code' => $venda->code,
                'pagamento' => $venda->pagamento,
                'factura' => 'RG',
                'codigo_factura' =>  $numeroFactura,
                'factura_next' => "RG {$codigo_designacao_factura}{$ano}/{$numeroFactura}",
                'ano_factura' => date('Y'),
                'desconto' => $venda->desconto,

                'retificado' => $venda->retificado,
                'convertido_factura' => "Y",
                'factura_divida' => $venda->factura_divida,
                'anulado' => $venda->anulado,

                'quantidade' => $venda->quantidade,
        
                'total_iva' => $venda->total_iva,
                'valor_cash' => $venda->valor_cash,
                'valor_multicaixa' => $venda->valor_multicaixa,
        
                'numeracao_proforma' => $venda->factura_next,
                'moeda' => $venda->moeda,
                'total_incidencia' => $venda->total_incidencia,
                'valor_extenso' => $venda->valor_extenso,
                'texto_hash' => $plaintext,
                'hash' => base64_encode($signaturePlaintext),
                'conta_corrente_cliente' => $venda->conta_corrente_cliente,
                'nif_cliente' => $venda->nif_cliente,
                'desconto_percentagem' => $venda->desconto_percentagem,
                'observacao' => $venda->observacao,
                'referencia' => $venda->referencia,
                'entidade_id' => $venda->entidade_id,
            ]);

            $movimentos = Itens_venda::where([
                ['code','=' ,$venda->code],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->get();
    
            if($movimentos){
                foreach ($movimentos as $items) {
                    ItemRecibo::create([
                        'produto_id' => $items->produto_id,
                        'factura_id' => $recibo->id,
                        'movimento_id' => $items->movimento_id,
                        'user_id' => $items->user_id,
                        'quantidade' => $items->quantidade,
                        'status' => $items->status,
                        'valor_iva' => $items->valor_iva,
                        'valor_base' => $items->valor_base,
                        'valor_pagar' => $items->valor_pagar,
                        'preco_unitario' => $items->preco_unitario,
                        'desconto_aplicado' => $items->desconto_aplicado,
                        'desconto_aplicado_valor' => $items->desconto_aplicado_valor,
                        'iva' => $items->iva,
                        'iva_taxa' => $items->iva_taxa,
                        'texto_opcional' => $items->texto_opcional,
                        'code' => $items->code,
                        'numero_serie' => $items->numero_serie,
                        'entidade_id' => $items->entidade_id,
                        'user_id' => $items->user_id,
                    ]);
                }
            }
          
            $venda->codigo_factura = $numeroFactura;
            $venda->status_factura = "pago";
            $venda->status = true;
            $venda->status_venda = "realizado";
            $venda->status_venda = "convertida";
            $venda->factura = $request->factura;
            $venda->convertido_factura  = 'Y';

            $venda->update();

            Alert::success('Success', 'Factura Actualizada com sucesso!');
            return redirect()->route('factura-recibo-recibo', $venda->code);

        }else{
            // criar nota de credito
            $contarFactura = NotaCredito::where([
                ['factura', '=', 'NC'],
                ['ano_factura', '=', date("Y")],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->count();
    
            $ultimoRecibo = NotaCredito::where([
                ['factura', '=',  'NC'],
                ['ano_factura', '=', date("Y")],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
            ->orderBy('id', 'DESC')
            ->first();
    
            if(!$ultimoRecibo){
                $hashAnterior = "";
            }else{
                $hashAnterior = $ultimoRecibo->hash;
            }
    
            //Manipulação de datas: data actual
            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
    
            $ano = date("Y");
            $numeroFactura = $contarFactura + 1;
    
            $rsa = new RSA(); //Algoritimo RSA
    
            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();
    
            // Lendo a private key
            $rsa->loadKey($privatekey);
            $codigo_designacao_factura = ENV('DESIGNACAO_FACTURA');
            /**
            * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
            * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */
    
            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "NC {$codigo_designacao_factura}{$ano}/{$numeroFactura}" . ';' . number_format($venda->valor_total, 2, ".", "") . ';' . $hashAnterior;
            // HASH
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
    
            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)
    
            // Lendo a public key
            $rsa->loadKey($publickey);
    
            $nota = NotaCredito::create([
                'status' => true,
                'status_factura' => 'convertida',
                'status_venda' => "convertida",
                'user_id' => $venda->user_id,
                'caixa_id' => $venda->caixa_id,
                'cliente_id' => $venda->cliente_id,
                'loja_id' => $venda->loja_id,
                'factura_id' => $venda->id,
                'valor_entregue' => $venda->valor_entregue,
                'valor_total' => $venda->valor_total,
                
                'prazo' => 0,
                'data_emissao' => date("y-m-d"),
                'data_vencimento' => date("y-m-d"),
                'data_disponivel' => date("y-m-d"),
    
                
                'valor_troco' => $venda->valor_troco,
                'code' => $venda->code,
                'pagamento' => $venda->pagamento,
                'factura' => 'NC',
                'codigo_factura' =>  $numeroFactura,
                'factura_next' => "NC {$codigo_designacao_factura}{$ano}/{$numeroFactura}",
                'ano_factura' => date('Y'),
                'desconto' => $venda->desconto,
    
                'retificado' => $venda->retificado,
                'convertido_factura' => "Y",
                'factura_divida' => $venda->factura_divida,
                'anulado' => $venda->anulado,
    
                'quantidade' => $venda->quantidade,
        
                'total_iva' => $venda->total_iva,
                'valor_cash' => $venda->valor_cash,
                'valor_multicaixa' => $venda->valor_multicaixa,
        
                'numeracao_proforma' => $venda->factura_next,
                'moeda' => $venda->moeda,
                'total_incidencia' => $venda->total_incidencia,
                'valor_extenso' => $venda->valor_extenso,
                'texto_hash' => $plaintext,
                'hash' => base64_encode($signaturePlaintext),
                'conta_corrente_cliente' => $venda->conta_corrente_cliente,
                'nif_cliente' => $venda->nif_cliente,
                'desconto_percentagem' => $venda->desconto_percentagem,
                'observacao' => $venda->observacao,
                'referencia' => $venda->referencia,
                'entidade_id' => $venda->entidade_id,
            ]);
    
            $movimentos = Itens_venda::where([
                ['code','=' ,$venda->code],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->get();
    
            if($movimentos){
                foreach ($movimentos as $items) {
                    ItemNotaCredito::create([
                        'produto_id' => $items->produto_id,
                        'factura_id' => $nota->id,
                        'movimento_id' => $items->movimento_id,
                        'user_id' => $items->user_id,
                        'quantidade' => $items->quantidade,
                        'status' => $items->status,
                        'valor_iva' => $items->valor_iva,
                        'valor_base' => $items->valor_base,
                        'valor_pagar' => $items->valor_pagar,
                        'preco_unitario' => $items->preco_unitario,
                        'desconto_aplicado' => $items->desconto_aplicado,
                        'desconto_aplicado_valor' => $items->desconto_aplicado_valor,
                        'iva' => $items->iva,
                        'iva_taxa' => $items->iva_taxa,
                        'texto_opcional' => $items->texto_opcional,
                        'code' => $items->code,
                        'numero_serie' => $items->numero_serie,
                        'entidade_id' => $items->entidade_id,
                        'user_id' => $items->user_id,
                    ]);
                }
            }
            /** end nota credito */
    
            $statusFactura = "";
            if($request->factura == "FT"){
                $statusFactura = "pago";
            }else{
                $statusFactura = "por pagar";
            }
    
            $venda->codigo_factura = $numeroFacturaNovo;
            $venda->status_factura = $statusFactura;
            $venda->status = true;
            $venda->status_venda = "realizado";
            $venda->status_venda = "convertida";
            $venda->factura = $request->factura;
            $venda->convertido_factura  = 'Y';
    
            $venda->update();
    
            Alert::success('Success', 'Factura Actualizada com sucesso!');

            if($request->factura == "FT"){
                return redirect()->route('factura-factura', $venda->code);
            }

            if($request->factura == "FR"){
                return redirect()->route('factura-recibo', $venda->code);
            }

            if($request->factura == "RG"){
                return redirect()->route('factura-recibo-recibo', $venda->code);
            }

            if($request->factura == "FP"){
                return redirect()->route('factura-proforma', $venda->code);
            }

            if($request->factura == "NC"){
                return redirect()->route('factura-nota-credito', $venda->code);
            }
            

        }

    }

    public function emitir_recibo(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('editar facturas') || !$user->can('criar facturas')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        $request->validate([
            'total_pagar' => 'required',
            'factura_id' => 'required',
            'forma_de_pagamento' => 'required',
            'data_pagamento' => 'required',
        ],[
            'total_pagar.required' => 'O total a pagar é um campo obrigatório',
            'factura_id.required' => 'A factura é um campo obrigatório',
            'forma_de_pagamento.required' => 'Forma de Pagamento é Obrigatório!',
            'data_pagamento.required' => 'Data do Pagamento é Obrigatório!',
        ]);
        
        
        if($request->forma_de_pagamento == "NU") 
        {
            $request->validate([
                'caixa_id' => 'required',
                'valor_entregue' => 'required',
            ],[
                'caixa_id.required' => 'Deves selecionar um caixa é Obrigatório!',
                'valor_entregue.required' => 'Informe o valor a sem retirado no caixa é obrigatório!',
            ]);
        }
        
        if($request->forma_de_pagamento == "MB" || $request->forma_de_pagamento == "TE" || $request->forma_de_pagamento == "DE") 
        {
            $request->validate([
                'banco_id' => 'required',
                'valor_entregue_multicaixa' => 'required',
            ],[
                'banco_id.required' => 'Deves selecionar um Banco ou uma conta bancaria é Obrigatório!',
                'valor_entregue_multicaixa.required' => 'Informe o valor a sem retirado no banco é obrigatório!',
            ]);
        }
        
        if($request->forma_de_pagamento == "OU") 
        {
            $request->validate([
                'caixa_id' => 'required',
                'banco_id' => 'required',
                'valor_entregue' => 'required',
                'valor_entregue_multicaixa' => 'required',
            ],[
                'caixa_id.required' => 'Deves selecionar um caixa é Obrigatório',
                'banco_id.required' => 'Deves selecionar um Banco ou conta bancaria é Obrigatório!',
                'valor_entregue.required' => 'Informe o valor a sem retirado no caixa é obrigatório!',
                'valor_entregue_multicaixa.required' => 'Informe o valor a sem retirado no banco é obrigatório!',
            ]);
        }
        
        
        try {
            // Inicia a transação
            DB::beginTransaction();
                         
            $request->valor_entregue_multicaixa = (int) $request->valor_entregue_multicaixa;
            $request->valor_entregue = (int) $request->valor_entregue;
            $request->total_pagar =  $request->valor_entregue_multicaixa + $request->valor_entregue;
    
            $venda = Venda::findOrFail($request->factura_id);
            
            $cliente = Cliente::findOrFail($venda->cliente_id);
            $subconta_cliente = Subconta::where('code', $cliente->code)->first();
            
            $code = uniqid(time());
                        
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            if($venda->status_factura == "pago"){
                Alert::warning('Erro', 'Esta Factura já esta paga, então não podes emitir nenhum recibo!');
                return redirect()->route('finalizar-venda');
            }    
            
            if($request->forma_de_pagamento == "NU"){
                if($request->caixa_id == ""){
                    return redirect()->back()->with('danger', 'Deves selecionar o caixa onde será retirado o valor para o pagamento da factura!');
                } 
                $valor_cash = $request->total_pagar;
                $valor_multicaixa = 0;
                $venda->valor_cash = $venda->valor_cash + $valor_cash;
                $subconta_caixa = Subconta::where('code', $request->caixa_id)->first();
                
                #VAMOS CREDITAR NO CAIXA OU SEJA VAMOS TIRAR O DINHEIRO DO CAIXA SELECIONADO
                $movimeto = Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $subconta_caixa->id,
                    'status' => true,
                    'movimento' => 'E',
                    'credito' => 0,
                    'debito' => $request->total_pagar,
                    'observacao' => "Pagamento da factura referente {$venda->factura_next}",
                    'code' => $code,
                    'data_at' => date("Y-m-d"),
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => 1,
                    'periodo_id' => 12,
                ]);
                
                OperacaoFinanceiro::create([
                    'nome' => "Pagamento da factura referente {$venda->factura_next}",
                    'status' => "pago",
                    'motante' => $request->total_pagar,
                    'subconta_id' => $subconta_caixa->id,
                    'cliente_id' => $cliente->id,
                    'model_id' => 3,
                    'type' => "R",
                    'status_pagamento' => "pago",
                    'code' => $code,
                    'descricao' => "Pagamento da factura referente {$venda->factura_next}",
                    'movimento' => "E",
                    'date_at' => date("Y-m-d"),
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => 1,
                    'periodo_id' => 12,
                ]);
                                     
                ## CREDITAR CLEINTE
                $movimeto = Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $subconta_cliente->id,
                    'status' => true,
                    'movimento' => 'E',
                    'credito' => $request->total_pagar,
                    'debito' => 0,
                    'observacao' => "Pagamento da factura referente {$venda->factura_next}",
                    'code' => $code,
                    'data_at' => date("Y-m-d"),
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => 1,
                    'periodo_id' => 12,
                ]);
               
            }
            
            if($request->forma_de_pagamento == "MB" || $request->forma_de_pagamento == "TE" || $request->forma_de_pagamento == "DE"){
                                
                if($request->banco_id == ""){
                    return redirect()->back()->with('danger', 'Deves selecionar o banco onde será retirado o valor para o pagamento da factura!');
                }
                $valor_cash = 0;
                $valor_multicaixa = $request->total_pagar;
                $venda->valor_multicaixa = $venda->valor_multicaixa + $valor_multicaixa;
                
                $subconta_banco = Subconta::where('code', $request->banco_id)->first();
                
                #VAMOS CREDITAR NO BANCO OU SEJA VAMOS TIRAR O DINHEIRO DO BANCO SELECIONADO
                
                $movimeto = Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $subconta_banco->id,
                    'status' => true,
                    'movimento' => 'E',
                    'credito' => 0,
                    'debito' => $request->total_pagar,
                    'observacao' => $request->observacao,
                    'code' => $code,
                    'data_at' => date("Y-m-d"),
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => 1,
                    'periodo_id' => 12,
                ]);
                
                OperacaoFinanceiro::create([
                    'nome' => "Pagamento da factura referente {$venda->factura_next}",
                    'status' => "pago",
                    'motante' => $request->total_pagar,
                    'subconta_id' => $subconta_banco->id,
                    'cliente_id' => $cliente->id,
                    'model_id' => 3,
                    'type' => "R",
                    'parcelado' => "N",
                    'status_pagamento' => "pago",
                    'code' => $code,
                    'descricao' => "Pagamento da factura referente {$venda->factura_next}",
                    'movimento' => "S",
                    'date_at' => date("Y-m-d"),
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id, 
                    'exercicio_id' => 1,
                    'periodo_id' => 12,
                ]);
                                     
                ## CREDITAR CLEINTE
                $movimeto = Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $subconta_cliente->id,
                    'status' => true,
                    'movimento' => 'E',
                    'credito' => $request->total_pagar,
                    'debito' => 0,
                    'observacao' => "Pagamento da factura referente {$venda->factura_next}",
                    'code' => $code,
                    'data_at' => date("Y-m-d"),
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => 1,
                    'periodo_id' => 12,
                ]);
                
            }
            
            if($request->forma_de_pagamento == "OU"){
                if($request->caixa_id == ""){
                    return redirect()->back()->with('danger', 'Deves selecionar o caixa onde será retirado o valor para o pagamento da factura!');
                }
                
                $valor_cash =  $request->valor_entregue_input;
                $valor_multicaixa = $request->valor_entregue_multicaixa_input;
                $venda->valor_cash = $venda->valor_cash + $valor_cash;
                $venda->valor_multicaixa = $venda->valor_multicaixa + $valor_multicaixa;
                
                $subconta_caixa = Subconta::where('code', $request->caixa_id)->first();
                
                #VAMOS CREDITAR NO CAIXA OU SEJA VAMOS TIRAR O DINHEIRO DO CAIXA SELECIONADO
                $movimeto = Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $subconta_caixa->id,
                    'status' => true,
                    'movimento' => 'E',
                    'credito' => 0,
                    'debito' => $request->valor_entregue_input,
                    'observacao' => "Pagamento da factura referente {$venda->factura_next}",
                    'code' => $code,
                    'data_at' => date("Y-m-d"),
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => 1,
                    'periodo_id' => 12,
                ]);
                
                OperacaoFinanceiro::create([
                    'nome' => "Pagamento da factura referente {$venda->factura_next}",
                    'status' => "pago",
                    'motante' => $request->valor_entregue_input,
                    'subconta_id' => $subconta_caixa->id,
                    'cliente_id' => $cliente->id,
                    'model_id' => 3,
                    'type' => "R",
                    'status_pagamento' => "pago",
                    'code' => $code,
                    'descricao' => "Pagamento da factura referente {$venda->factura_next}",
                    'movimento' => "E",
                    'date_at' => date("Y-m-d"),
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => 1,
                    'periodo_id' => 12,
                ]);
                                     
                ## CREDITAR CLEINTE
                $movimeto = Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $subconta_cliente->id,
                    'status' => true,
                    'movimento' => 'E',
                    'credito' => $request->valor_entregue_input,
                    'debito' => 0,
                    'observacao' => "Pagamento da factura referente {$venda->factura_next}",
                    'code' => $code,
                    'data_at' => date("Y-m-d"),
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => 1,
                    'periodo_id' => 12,
                ]);
                
                if($request->banco_id == ""){
                    return redirect()->back()->with('danger', 'Deves selecionar o banco onde será retirado o valor para o pagamento da factura!');
                }
                
                $subconta_banco = Subconta::where('code', $request->banco_id)->first();
                
                #VAMOS CREDITAR NO BANCO OU SEJA VAMOS TIRAR O DINHEIRO DO BANCO SELECIONADO
                
                $movimeto = Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $subconta_banco->id,
                    'status' => true,
                    'movimento' => 'E',
                    'credito' => 0,
                    'debito' => $request->valor_entregue_multicaixa_input,
                    'observacao' => $request->observacao,
                    'code' => $code,
                    'data_at' => date("Y-m-d"),
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => 1,
                    'periodo_id' => 12,
                ]);
                
                OperacaoFinanceiro::create([
                    'nome' => "Pagamento da factura referente {$venda->factura_next}",
                    'status' => "pago",
                    'motante' => $request->valor_entregue_multicaixa_input,
                    'subconta_id' => $subconta_banco->id,
                    'cliente_id' => $cliente->id,
                    'model_id' => 3,
                    'type' => "R",
                    'parcelado' => "N",
                    'status_pagamento' => "pago",
                    'data_recebimento' => date("Y-m-d"),
                    'code' => $code,
                    'descricao' => "Pagamento da factura referente {$venda->factura_next}",
                    'movimento' => "S",
                    'date_at' => date("Y-m-d"),
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id, 
                    'exercicio_id' => 1,
                    'periodo_id' => 12,
                ]);
                                     
                ## CREDITAR CLEINTE
                $movimeto = Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $subconta_cliente->id,
                    'status' => true,
                    'movimento' => 'E',
                    'credito' => $request->valor_entregue_multicaixa_input,
                    'debito' => 0,
                    'observacao' => "Pagamento da factura referente {$venda->factura_next}",
                    'code' => $code,
                    'data_at' => date("Y-m-d"),
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => 1,
                    'periodo_id' => 12,
                ]);
            }
     
            // criar nota de credito
            $contarFactura = Recibo::where('factura', '=', 'RG')
                ->where('ano_factura', '=', $entidade->empresa->ano_factura)
                ->where('entidade_id', '=', $entidade->empresa->id)
                ->count();
        
            $ultimoRecibo = Recibo::where('factura', '=', 'RG')
            ->where('ano_factura', '=', $entidade->empresa->ano_factura)
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->first();
    
            if(!$ultimoRecibo){
                $hashAnterior = "";
            }else{
                $hashAnterior = $ultimoRecibo->hash;
            }
    
            $request->data_pagamento = $request->data_pagamento . " " .date('H:i:s');
            
            //Manipulação de datas: data actual
            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', $request->data_pagamento);
    
            $numeroFactura = $contarFactura + 1;
    
            $rsa = new RSA(); //Algoritimo RSA
    
            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();
    
            // Lendo a private key
            $rsa->loadKey($privatekey);
            
            $codigo_designacao_factura = "RG {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$numeroFactura}";
            /**
            * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
            * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */
    
            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ";{$codigo_designacao_factura};" . number_format($request->total_pagar, 2, ".", "") . ';' . $hashAnterior;
            // HASH
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
    
            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)
    
            // Lendo a public key
            $rsa->loadKey($publickey);
            
            $movimentos = Itens_venda::where([
                ['code', "=", $venda->code],
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get();        
            
            $totalValorBase = 0;
            $totalValorIva = 0;
            $totalItems = 0; 
            $totalIDesconto = 0; 
    
            if($movimentos){
                foreach ($movimentos as $value) {
                    $totalValorBase+= $value->valor_base;
                    $totalValorIva+= $value->valor_iva;
                    $totalItems+= $value->quantidade;
                    $totalIDesconto+= $value->desconto_aplicado_valor;
                }
            }
            
            $code_recibo = uniqid(time());
            
            $valor_extenso = $this->valor_por_extenso($request->total_pagar);
                    
            $recibo = Recibo::create([
                'status' => true,
                'status_factura' => 'pago',
                'status_venda' => "convertida",
                'user_id' => $venda->user_id,
                'cliente_id' => $venda->cliente_id,
                'factura_id' => $venda->id,
                'valor_entregue' => $request->total_pagar,
                'valor_total' => $request->total_pagar,
                
                'prazo' => $venda->prazo,
                'data_emissao' => $request->data_pagamento,
                'data_vencimento' => $venda->data_vencimento,
                'data_disponivel' => $venda->data_disponivel,
                
                'valor_troco' => 0,
                'code' => $code_recibo,
                'pagamento' => $request->forma_de_pagamento,
                'factura' => 'RG',
                'codigo_factura' => $numeroFactura,
                'factura_next' => $codigo_designacao_factura,
                'ano_factura' => $entidade->empresa->ano_factura,
                'desconto' => $totalIDesconto,
    
                'retificado' => $venda->retificado,
                'convertido_factura' => "Y",
                'factura_divida' => $venda->factura_divida,
                'anulado' => $venda->anulado,
    
                'quantidade' => $totalItems,
        
                'total_iva' => $totalValorIva,
                'valor_cash' => $valor_cash,
                'valor_multicaixa' => $valor_multicaixa,
        
                'numeracao_proforma' => $venda->factura_next,
                'moeda' => $venda->moeda,
                'total_incidencia' => $request->total_pagar, //  $totalValorBase,
                'valor_extenso' => $valor_extenso,
                'texto_hash' => $plaintext,
                'hash' => base64_encode($signaturePlaintext),
                'conta_corrente_cliente' => $venda->conta_corrente_cliente,
                'nif_cliente' => $venda->nif_cliente,
                'desconto_percentagem' => $venda->desconto_percentagem,
                'observacao' => $venda->observacao,
                'referencia' => $venda->referencia,
                'entidade_id' => $venda->entidade_id,
            ]);
    
            $movimentos = Itens_venda::where([
                ['code','=' ,$venda->code],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->get();
    
            if($movimentos){
                foreach ($movimentos as $items) {
                    ItemRecibo::create([
                        'produto_id' => $items->produto_id,
                        'factura_id' => $recibo->id,
                        'movimento_id' => $items->movimento_id ?? NULL,
                        'user_id' => $items->user_id,
                        'quantidade' => $items->quantidade,
                        'status' => 'realizado',
                        'valor_iva' => $items->valor_iva,
                        'valor_base' => $items->valor_base,
                        'valor_pagar' => $items->valor_pagar,
                        'preco_unitario' => $items->preco_unitario,
                        'desconto_aplicado' => $items->desconto_aplicado,
                        'desconto_aplicado_valor' => $items->desconto_aplicado_valor,
                        'iva' => $items->iva,
                        'iva_taxa' => $items->iva_taxa,
                        'texto_opcional' => $items->texto_opcional,
                        'code' => $code_recibo,
                        'numero_serie' => $items->numero_serie,
                        'entidade_id' => $items->entidade_id,
                        'user_id' => $items->user_id,
                    ]);
                }
            }
            
            //dd($request->total_pagar, $venda->valor_total);
            
            if( $request->total_pagar == ($venda->valor_divida + $venda->valor_pago)){
                $status = 'pago';
                $factura_divida = "N";
                $convertido_factura  = 'Y';
                $status_venda = "convertida";
                //factura já paga
            }else if($venda->valor_pago == $venda->valor_total){
                $status = 'pago';
                $factura_divida = "N";
                $convertido_factura  = 'Y';
                $status_venda = "convertida";
            }else {
                $status = 'por pagar';
                $factura_divida = "Y";
                $convertido_factura  = 'N';
                $status_venda = "realizado";
            }
            
            $venda->valor_pago += $request->total_pagar;
            $venda->valor_entregue += $request->total_pagar;
            $venda->valor_divida = $venda->valor_total - $venda->valor_pago;
            
            if($venda->valor_divida < 1){
                $status = 'pago';
                $factura_divida = "N";
                $convertido_factura  = 'Y';
                $status_venda = "convertida";
                $venda->valor_divida = 0;
            }
                
            $venda->status_factura = $status;
            $venda->status = true;
            $venda->pagamento = $request->forma_de_pagamento;
            $venda->factura_divida = $factura_divida;
            $venda->status_venda = $status_venda;
            $venda->convertido_factura  = $convertido_factura;
    
            $venda->update();
          
            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger('Error', $e->getMessage());
            return redirect()->back()->with('danger', $e->getMessage());
        }
    
        // Alert::success('Success', 'Factura Actualizada com sucesso!');
        // return redirect()->route('factura-recibo-recibo', $recibo->code);       
        return response()->json(['success' => true, 'factura' => $recibo]);

    }

    public function anularFactura($id)
    {
        $user = auth()->user();
        
        if(!$user->can('editar facturas') || !$user->can('criar facturas')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
    
        try {
            // Inicia a transação
            DB::beginTransaction();
            
            $factura = venda::findOrFail($id);
     
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            // $verifica se tem uma loja activa onde esta sendo retidados os produtos
            $loja = Loja::where([
                ['status', '=', 'activo'],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->first();
            
            if(!$loja){
                Alert::warning('Atenção', 'Não têm nenhuma loja/armazém activa no momento para registrar saída deste produto. Por favor activa uma loja/armazém que tem este produto!');
                return redirect()->back()->with('warning', 'Não têm nenhuma loja/armazém activa no momento para registrar saída deste produto.');
            }

            // criar nota de credito
            $contarFactura = NotaCredito::where([
                ['factura', '=', 'NC'],
                ['ano_factura', '=', date("Y")],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->count();
    
            $ultimoRecibo = NotaCredito::where([
                ['factura', '=',  'NC'],
                ['ano_factura', '=', date("Y")],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
            ->orderBy('id', 'DESC')
            ->first();
    
            if(!$ultimoRecibo){
                $hashAnterior = "";
            }else{
                $hashAnterior = $ultimoRecibo->hash;
            }
    
            //Manipulação de datas: data actual
            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
    
            // $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
    
            $ano = date("Y");
            $numeroFactura = $contarFactura + 1;
    
            $rsa = new RSA(); //Algoritimo RSA
    
            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();
    
            // Lendo a private key
            $rsa->loadKey($privatekey);
            $codigo_designacao_factura = ENV('DESIGNACAO_FACTURA');
            /**
            * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
            * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */
    
            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "NC {$codigo_designacao_factura}{$ano}/{$numeroFactura}" . ';' . number_format($factura->valor_total, 2, ".", "") . ';' . $hashAnterior;
            // HASH
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
    
            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)
    
            // Lendo a public key
            $rsa->loadKey($publickey);
    
            $nota = NotaCredito::create([
                'status' => true,
                'status_factura' => 'anulada',
                'status_venda' => "anulada",
                'user_id' => $factura->user_id,
                'caixa_id' => $factura->caixa_id,
                'factura_id' => $factura->id,
                'cliente_id' => $factura->cliente_id,
                'loja_id' => $factura->loja_id,
                'valor_entregue' => $factura->valor_entregue,
                'valor_total' => $factura->valor_total,
                
                'prazo' => 0,
                'data_emissao' => date("y-m-d"),
                'data_vencimento' => date("y-m-d"),
                'data_disponivel' => date("y-m-d"),
                
                'valor_troco' => $factura->valor_troco,
                'code' => $factura->code,
                'pagamento' => $factura->pagamento,
                'factura' => 'NC',
                'codigo_factura' =>  $numeroFactura,
                'factura_next' => "NC {$codigo_designacao_factura}{$ano}/{$numeroFactura}",
                'ano_factura' => date('Y'),
                'desconto' => $factura->desconto,
    
                'retificado' => $factura->retificado,
                'convertido_factura' => $factura->convertido_factura,
                'factura_divida' => $factura->factura_divida,
                'anulado' => 'Y',
    
                'quantidade' => $factura->quantidade,
        
                'total_iva' => $factura->total_iva,
                'valor_cash' => $factura->valor_cash,
                'valor_multicaixa' => $factura->valor_multicaixa,
        
                'numeracao_proforma' => $factura->factura_next,
                'moeda' => $factura->moeda,
                'total_incidencia' => $factura->total_incidencia,
                'valor_extenso' => $factura->valor_extenso,
                'texto_hash' => $plaintext,
                'hash' => base64_encode($signaturePlaintext),
                'conta_corrente_cliente' => $factura->conta_corrente_cliente,
                'nif_cliente' => $factura->nif_cliente,
                'desconto_percentagem' => $factura->desconto_percentagem,
                'observacao' => $factura->observacao,
                'referencia' => $factura->referencia,
                'entidade_id' => $factura->entidade_id,
            ]);
    
            $movimentos = Itens_venda::with(['produto'])->where('code','=' ,$factura->code)
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->get();
            
            if($movimentos){
                foreach ($movimentos as $items) {
                    $item = ItemNotaCredito::create([
                        'produto_id' => $items->produto_id,
                        'factura_id' => $nota->id,
                        'movimento_id' => $items->movimento_id,
                        'user_id' => $items->user_id,
                        'quantidade' => $items->quantidade,
                        'status' => $items->status,
                        'valor_iva' => $items->valor_iva,
                        'valor_base' => $items->valor_base,
                        'valor_pagar' => $items->valor_pagar,
                        'preco_unitario' => $items->preco_unitario,
                        'desconto_aplicado' => $items->desconto_aplicado,
                        'desconto_aplicado_valor' => $items->desconto_aplicado_valor,
                        'iva' => $items->iva,
                        'iva_taxa' => $items->iva_taxa,
                        'texto_opcional' => $items->texto_opcional,
                        'code' => $items->code,
                        'numero_serie' => $items->numero_serie,
                        'entidade_id' => $items->entidade_id,
                        'user_id' => $items->user_id,
                    ]);
                }
            }
            
            // retornar os produtos no stock
            if($movimentos){
                foreach ($movimentos as $value) {
                    // ************************************************
                    $movimento = Itens_venda::findOrFail($value->id);
                    $produto = Produto::with('estoque')->findOrFail($movimento->produto_id);
                    $produto->estoque->stock = $produto->estoque->stock + $movimento->quantidade;
                    $produto->estoque->update();
                    
                    Registro::create([
                        "registro" => "Entrada de Stock",
                        "data_registro" => date('Y-m-d'),
                        "quantidade" => $value->quantidade,
                        "produto_id" => $value->produto_id,
                        "observacao" => "Retorno do produto {$value->produto->nome} no Stock",
                        "loja_id" => $factura->loja_id,
                        "lote_id" => NULL,
                        "user_id" => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                
                    // ************************************************
                    $item = Itens_venda::findOrFail($value->id);
                    $item->status = "anulada";
                    $item->update();
                }
            }
    
            $factura->numeracao_proforma = "NC {$codigo_designacao_factura}{$ano}/{$numeroFactura}";
            $factura->anulado = "Y";
            $factura->status_venda = "anulada";
            $factura->status_factura = "anulada";
            $factura->update();
            
            
            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger('Error', $e->getMessage());
            return redirect()->back()->with('danger', $e->getMessage());
            // return Response()->json($e->getMessage());
            // Trate o erro ou exiba uma mensagem de falha
            // por exemplo: return response()->json(['message' => 'Erro ao salvar'], 500);
        }
    
        

        Alert::success("Sucesso", "Factura Anulada com Sucesso");
        return redirect()->route("facturas.index");
    }

    public function imprimirFactura($id)
    {
        $factura = venda::with('cliente')->findOrFail($id);
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $movimentos = Itens_venda::where([
            ['entidade_id', '=', $entidade->empresa->id], 
            ['code','=', $factura->code],
            ['user_id','=', Auth::user()->id],
        ])->get();
       
        $head = [
            'titulo' => "Imprimir Factura",
            'descricao' => env('APP_NAME'),
            'factura' => $factura,
            'movimentos' => $movimentos,
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.facturas.documentos.factura', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }
    
    public function pdf(Request $request)
    {
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);
       
        $vendas = Venda::with(['user', 'cliente'])->where('entidade_id', $entidade->empresa->id)
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('created_at', '>=', Carbon::createFromDate($value));
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('created_at', '<=', Carbon::createFromDate($value));
        })
        ->when($request->caixa_id, function($query, $value){
            $query->where('caixa_id', '=', $value);
        })
        ->when($request->user_id, function($query, $value){
            $query->where('user_id', '=', $value);
        })
        ->orderBy('created_at', 'desc')
        ->get();
        
        
        $vendas = Venda::with('cliente')
        ->when($request->tipo_documento, function($query, $value){
            $query->where('factura', '=', $value);
        })
        ->when($request->loja_id, function($query, $value){
            $query->where('loja_id', '=', $value);
        })
        ->when($request->data_inicio, function($query, $value){
            $query->whereDate('created_at', '>=', Carbon::parse($value));
        })
        ->when($request->data_final, function($query, $value){
            $query->whereDate('created_at', '<=',Carbon::parse($value));
        })
        ->where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->orderby('created_at', 'desc')
        ->get();
        
        // $caixa = Caixa::find($request->caixa_id);
        $loja = Loja::find($request->loja_id);
    
        $head = [
            'titulo' => "FACTURAS",
            'descricao' => "",
            'vendas' => $vendas,
            "loja" => $loja,
            "empresa" => $empresa,
            "requests" => $request->all('data_inicio', 'data_final','caixa_id', 'user_id'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.facturas.pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
        // return $pdf->download('test.pdf');
    }


    public function retificarFactura($id)
    {
        return "retificar Factura {$id}";
    }  

    public function facturaSemPagamentos(Request $request)
    {
                   
        $user = auth()->user();
        
        if(!$user->can('listar facturas')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
    

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        if($request->tipo_documento == "todas" && $request->factura == ""){
            $facturas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();

             // dividas vencidas
             $facturasVencidas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_vencimento', '<', date("Y-m-d")],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->sum('valor_total');

            //dividas corrente
            $facturasVencidasCorrente = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_emissao', '<', date("Y-m-d")],
                ['data_vencimento', '>', date("Y-m-d")],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->sum('valor_total');


        }else if($request->tipo_documento == "todas" && $request->factura != ""){
            $facturas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['factura_next', 'like' ,"%{$request->factura}%"],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();

             // dividas vencidas
             $facturasVencidas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_vencimento', '<', date("Y-m-d")],
                ['factura_next', 'like' ,"%{$request->factura}%"],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->sum('valor_total');

            //dividas corrente
            $facturasVencidasCorrente = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_emissao', '<', date("Y-m-d")],
                ['data_vencimento', '>', date("Y-m-d")],
                ['factura_next', 'like' ,"%{$request->factura}%"],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->sum('valor_total');



        }else if($request->tipo_documento == "dividas_corrente" && $request->factura != ""){
            $facturas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_emissao', '<', date("Y-m-d")],
                ['data_vencimento', '>', date("Y-m-d")],
                ['factura_next', 'like' ,"%{$request->factura}%"],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();

            // dividas vencidas
            $facturasVencidas = 0;
            //dividas corrente
            $facturasVencidasCorrente = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_emissao', '<', date("Y-m-d")],
                ['data_vencimento', '>', date("Y-m-d")],
                ['factura_next', 'like' ,"%{$request->factura}%"],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->sum('valor_total');


        }else if($request->tipo_documento == "dividas_corrente" && $request->factura == ""){
            $facturas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_emissao', '<', date("Y-m-d")],
                ['data_vencimento', '>', date("Y-m-d")],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();

            // dividas vencidas
            $facturasVencidas = 0;

            //dividas corrente
            $facturasVencidasCorrente = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_emissao', '<', date("Y-m-d")],
                ['data_vencimento', '>', date("Y-m-d")],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->sum('valor_total');


        }else if($request->tipo_documento == "dividas_vencidas" && $request->factura != ""){
            $facturas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_vencimento', '<', date("Y-m-d")],
                ['factura_next', 'like' ,"%{$request->factura}%"],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();

            // dividas vencidas
            $facturasVencidas = Venda::where([
                ['status_factura', '=','por pagar'],['data_vencimento', '<', date("Y-m-d")],
                ['factura_next', 'like' ,"%{$request->factura}%"],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->sum('valor_total');

            //dividas corrente
            $facturasVencidasCorrente = 0;


        }else if($request->tipo_documento == "dividas_vencidas" && $request->factura == ""){
            $facturas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_vencimento', '<', date("Y-m-d")],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();

            // dividas vencidas
            $facturasVencidas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_vencimento', '<', date("Y-m-d")],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->sum('valor_total');

            //dividas corrente
            $facturasVencidasCorrente = 0;

        }else{
            ####################### PADRÃO

            $facturas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();        
            
            // dividas vencidas
            $facturasVencidas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_vencimento', '<', date("Y-m-d")],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->sum('valor_total');

            //dividas corrente
            $facturasVencidasCorrente = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_emissao', '<', date("Y-m-d")],
                ['data_vencimento', '>', date("Y-m-d")],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->sum('valor_total');
        }

        $head = [
            "titulo" => "Facturas sem Pagamentos",
            "descricao" => env('APP_NAME'),
            "caixa" => Caixa::where([
                ['active', true]
            ])->first(),
            "facturas" => $facturas,
            "facturasVencidas" => $facturasVencidas,
            "facturasVencidasCorrente" => $facturasVencidasCorrente,
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.facturas.sem_pagamentos', $head);
    }

    public function facturaFacturacao(Request $request)
    {
                       
        $user = auth()->user();
        
        if(!$user->can('listar facturas')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
    
    
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        if($request->factura != ""){
            
            $facturas = Venda::where([
                ['factura', '=','FR'],
                ['factura_next', 'like' ,"%{$request->factura}%"],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->orWhere('factura', 'FT')
            ->orWhere('factura', 'FG')
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();

        }else{
            $facturas = Venda::where([
                ['factura', '=','FR'],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->orWhere('factura', 'FT')
            ->orWhere('factura', 'FG')
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();            
        }

        $head = [
            "titulo" => "Facturação",
            "descricao" => env('APP_NAME'),
            "caixa" => Caixa::where([
                ['active', true],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->first(),
            "facturas" => $facturas,
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.facturas.facturacao', $head);
    }

    public function facturaInformativo(Request $request)
    {
                           
        $user = auth()->user();
        
        if(!$user->can('listar facturas')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
    
    
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        if($request->factura != ""){
            
            $facturas = Venda::where([
                ['factura', '=','OT'],
                ['entidade_id', '=', $entidade->empresa->id], 
                ['factura_next', 'like' ,"%{$request->factura}%"],
            ])
            ->orWhere('factura', 'EC')
            ->orWhere('factura', 'PF')
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();

        }else{
            ####################### PADRÃO
            $facturas = Venda::where([
                ['factura', '=','OT'],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->orWhere('factura', 'EC')
            ->orWhere('factura', 'PF')
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();
        }

        $head = [
            "titulo" => "Facturas Informativo",
            "descricao" => env('APP_NAME'),
            "caixa" => Caixa::where([
                ['active', true],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->first(),
            "facturas" => $facturas,
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.facturas.informativos', $head);
    }

    public function NotaCreditos(Request $request)
    {
                       
        $user = auth()->user();
        
        if(!$user->can('listar facturas')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
    
    
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $facturas = NotaCredito::when($request->factura, function($query, $value){
            $query->where('factura_next', 'like' ,"%{$value}%");
        })->where('entidade_id', '=', $entidade->empresa->id)
        ->with('cliente')
        ->with('facturas')
        ->orderby('created_at', 'desc')
        ->get();


        $head = [
            "titulo" => "Notas Creditos",
            "descricao" => env('APP_NAME'),
            "facturas" => $facturas,
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "requests" => $request->all('factura'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.facturas.notas-creditos', $head);
    }


    public function recibos(Request $request)
    {
        #RECIBO            
        $user = auth()->user();
        
        if(!$user->can('listar facturas')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
    

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $facturas = Recibo::when($request->factura, function($query, $value){
            $query->where('factura_next', 'like' ,"%{$value}%");
        })->where('entidade_id', '=', $entidade->empresa->id)
        ->with('cliente')
        ->with('facturas')
        ->orderby('created_at', 'desc')
        ->get();

        $head = [
            "titulo" => "Recibos",
            "descricao" => env('APP_NAME'),
            "facturas" => $facturas,
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "requests" => $request->all('factura'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.facturas.recibos', $head);
    }

    public function FacturaProforma($code)
    {
                           
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $factura = Venda::with('cliente')->with('caixa')->with('user')->where('code', $code)->first();
        if(!$factura){
            return redirect()->back();
        }

        $movimentos = Itens_venda::with('produto')->where('code', $factura->code)->where('user_id', Auth::user()->id)->where('entidade_id', $entidade->empresa->id)->get();

        if($movimentos){
                
            $total_incidencia_ise = 0;
            $total_retencao_ise = 0;
            $total_iva_ise = 0;

            $total_incidencia_nor = 0;
            $total_retencao_nor = 0;
            $total_iva_nor = 0;

            $total_incidencia_out = 0;
            $total_retencao_out = 0;
            $total_iva_out = 0;

            $motivo = "";

            foreach ($movimentos as $item){
                if ($item->iva == 'NOR'){
                    $total_incidencia_nor = $total_incidencia_nor + $item->valor_base;
                    $total_iva_nor = $total_iva_nor + $item->valor_iva;
                }
                if ($item->iva == 'ISE'){
                    $total_incidencia_ise = $total_incidencia_ise + $item->valor_base;
                    $total_iva_ise = $total_iva_ise + $item->valor_iva;
    
                    // $motivo = $item->produto->motivo->descricao;
                }
                if ($item->iva == 'RED'){
                    $total_incidencia_out = $total_incidencia_out + $item->valor_base;
                    $total_iva_out = $total_iva_out + $item->valor_iva;
                }
            }
        }
        

        $head = [
            "titulo" => "Factura Pro-forma",
            "descricao" => env('APP_NAME'),
            "factura" => $factura,
            "items_facturas" => $movimentos,
            "loja" => $entidade,
            
            "total_incidencia_nor" => $total_incidencia_nor,
            "total_iva_nor" => $total_iva_nor,
            "total_retencao_nor" => $total_retencao_nor,

            "total_incidencia_ise" => $total_incidencia_ise,
            "total_iva_ise" => $total_iva_ise,
            "total_retencao_ise" => $total_retencao_ise,

            "total_incidencia_out" => $total_incidencia_out,
            "total_iva_out" => $total_iva_out,
            "total_retencao_out" => $total_retencao_out,

            "motivo" => $motivo,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.facturas.documentos.impressao.factura-proforma', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();

    }

    public function FacturaFactura($code)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $factura = Venda::with('cliente')->with('caixa')->with('user')->where('code', $code)->first();
        if(!$factura){
            return redirect()->back();
        }

        $movimentos = Itens_venda::with('produto.motivo')->where('code', $factura->code)->where('entidade_id', $entidade->empresa->id)->get();

        if($movimentos){
                
            $total_incidencia_ise = 0;
            $total_retencao_ise = 0;
            $total_iva_ise = 0;


            $total_incidencia_nor = 0;
            $total_retencao_nor = 0;
            $total_iva_nor = 0;

            $total_incidencia_out = 0;
            $total_retencao_out = 0;
            $total_iva_out = 0;

            $motivo = "";

            foreach ($movimentos as $item){
                if ($item->iva == 'NOR'){
                    $total_incidencia_nor = $total_incidencia_nor + $item->valor_base;
                    $total_iva_nor = $total_iva_nor + $item->valor_iva;
                }
                if ($item->iva == 'ISE'){
                    $total_incidencia_ise = $total_incidencia_ise + $item->valor_base;
                    $total_iva_ise = $total_iva_ise + $item->valor_iva;
                }
                if ($item->iva == 'RED'){
                    $total_incidencia_out = $total_incidencia_out + $item->valor_base;
                    $total_iva_out = $total_iva_out + $item->valor_iva;
                }
            }
        }
        
        $head = [
            "titulo" => "Factura",
            "descricao" => env('APP_NAME'),
            "factura" => $factura,
            "items_facturas" => $movimentos,
            "loja" => $entidade,

            "total_incidencia_nor" => $total_incidencia_nor,
            "total_iva_nor" => $total_iva_nor,
            "total_retencao_nor" => $total_retencao_nor,

            "total_incidencia_ise" => $total_incidencia_ise,
            "total_iva_ise" => $total_iva_ise,
            "total_retencao_ise" => $total_retencao_ise,

            "total_incidencia_out" => $total_incidencia_out,
            "total_iva_out" => $total_iva_out,
            "total_retencao_out" => $total_retencao_out,

            "motivo" => $motivo,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.facturas.documentos.impressao.factura-factura', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();

    }

    public function FacturaRecibo($code)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $factura = Venda::with('cliente')->with('caixa')->with('user')->where('code', $code)->first();
        if(!$factura){
            return redirect()->back();
        }

        $movimentos = Itens_venda::with('produto')->where('code', $factura->code)->where('user_id', Auth::user()->id)->where('entidade_id', $entidade->empresa->id)->get();

        if($movimentos){
                
            $total_incidencia_ise = 0;
            $total_retencao_ise = 0;
            $total_iva_ise = 0;


            $total_incidencia_nor = 0;
            $total_retencao_nor = 0;
            $total_iva_nor = 0;

            $total_incidencia_out = 0;
            $total_retencao_out = 0;
            $total_iva_out = 0;

            $motivo = "";

            foreach ($movimentos as $item){
                if ($item->iva == 'NOR'){
                    $total_incidencia_nor = $total_incidencia_nor + $item->valor_base;
                    $total_iva_nor = $total_iva_nor + $item->valor_iva;
                }
                if ($item->iva == 'ISE'){
                    $total_incidencia_ise = $total_incidencia_ise + $item->valor_base;
                    $total_iva_ise = $total_iva_ise + $item->valor_iva;
    
                    // $motivo = $item->produto->motivo->descricao;
                }
                if ($item->iva == 'RED'){
                    $total_incidencia_out = $total_incidencia_out + $item->valor_base;
                    $total_iva_out = $total_iva_out + $item->valor_iva;
                }
            }
        }

        $head = [
            "titulo" => "Factura Recibo",
            "descricao" => env('APP_NAME'),
            "factura" => $factura,
            "items_facturas" => $movimentos,
            "loja" => $entidade,
 
            "total_incidencia_nor" => $total_incidencia_nor,
            "total_iva_nor" => $total_iva_nor,
            "total_retencao_nor" => $total_retencao_nor,

            "total_incidencia_ise" => $total_incidencia_ise,
            "total_iva_ise" => $total_iva_ise,
            "total_retencao_ise" => $total_retencao_ise,

            "total_incidencia_out" => $total_incidencia_out,
            "total_iva_out" => $total_iva_out,
            "total_retencao_out" => $total_retencao_out,
            
            "motivo" => $motivo,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.facturas.documentos.impressao.factura-recibo', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();

    }

    public function FacturaReciboRecibo($code)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $factura = Recibo::with('cliente')->with('caixa')->with('user')->with('facturas')->where('code', $code)->first();
        if(!$factura){
            return redirect()->back();
        }

        $movimentos = ItemRecibo::with('produto')->where('code', $factura->code)->where('user_id', Auth::user()->id)->where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => "Factura Recibo Recibo",
            "descricao" => env('APP_NAME'),
            "factura" => $factura,
            "items_facturas" => $movimentos,
            "loja" => $entidade,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.facturas.documentos.impressao.factura-recibo-recibo', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function FacturaNotaCredito($code)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        // $factura = Venda::with('cliente')->with('caixa')->with('user')->where('code', $code)->first();
        $factura = NotaCredito::with('origem')->with('cliente')->with('caixa')->with('user')->where('code', $code)->first();
        
        
        if(!$factura){
            return redirect()->back();
        }
        
        $movimentos = ItemNotaCredito::where('code', $factura->code)
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        if($movimentos){
                
            $total_incidencia_ise = 0;
            $total_iva_ise = 0;


            $total_incidencia_nor = 0;
            $total_iva_nor = 0;

            $total_incidencia_out = 0;
            $total_iva_out = 0;

            $motivo = "";

            foreach ($movimentos as $item){
                if ($item->iva == 'NOR'){
                    $total_incidencia_nor = $total_incidencia_nor + $item->valor_base;
                    $total_iva_nor = $total_iva_nor + $item->valor_iva;
                }
                if ($item->iva == 'ISE'){
                    $total_incidencia_ise = $total_incidencia_ise + $item->valor_base;
                    $total_iva_ise = $total_iva_ise + $item->valor_iva;
    
                    $motivo = $item->produto->motivo->descricao;
                }
                if ($item->iva == 'RED'){
                    $total_incidencia_out = $total_incidencia_out + $item->valor_base;
                    $total_iva_out = $total_iva_out + $item->valor_iva;
                }
            }
        }
        
        $head = [
            "titulo" => "Factura Nota Credito",
            "descricao" => env('APP_NAME'),
            "factura" => $factura,
            "items_facturas" => $movimentos,
            
            "total_incidencia_nor" => $total_incidencia_nor,
            "total_iva_nor" => $total_iva_nor,
    
            "total_incidencia_ise" => $total_incidencia_ise,
            "total_iva_ise" => $total_iva_ise,
    
            "total_incidencia_out" => $total_incidencia_out,
            "total_iva_out" => $total_iva_out,
            
            "loja" => $entidade,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.facturas.documentos.impressao.factura-nota-credito', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();

    }

    public function MovimentoPDF($code)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $movimento = Movimento::with('caixa')->with('user')->where('code', $code)->first();
        if(!$movimento){
            return redirect()->back();
        }
        
        $resultado = ($movimento->movimento == "E") ? "ENTRADA DE VALORES" : "SAÍDA DE VALORES";

        $head = [
            "titulo" => "NOTA DE {$resultado}",
            "descricao" => env('APP_NAME'),
            "movimento" => $movimento,
            "loja" => $entidade,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.facturas.documentos.impressao.nota-movimento', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

}
