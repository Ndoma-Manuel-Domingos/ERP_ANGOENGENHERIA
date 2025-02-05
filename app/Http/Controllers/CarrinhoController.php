<?php

namespace App\Http\Controllers;

use App\Models\ContaBancaria;
use App\Models\Caixa;
use App\Models\Cliente;
use App\Models\Entidade;
use App\Models\Estoque;
use App\Models\Itens_venda;
use App\Models\Loja;
use App\Models\Mesa;
use App\Models\Movimento;
use App\Models\Quarto;
use App\Models\OperacaoFinanceiro;
use App\Models\Produto;
use App\Models\Registro;
use App\Models\Subconta;
use App\Models\User;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;

use phpseclib\Crypt\RSA;

class CarrinhoController extends Controller
{

    //
    use TraitChavesSaft;
    use TraitHelpers;

    // Exibe a página do carrinho
    public function index()
    {
        $carrinho = Session::get('carrinho', []);
        $total = $this->calcularTotal($carrinho);
        $loja = ['moeda' => 'AOA'];  // Exemplo de dados da loja
        return view('carrinho.index', compact('carrinho', 'total', 'loja'));
    }

    // Adiciona um produto ao carrinho
    public function adicionar(Request $request)
    {
        $produtoId = $request->input('produto_id');
        $quantidade = $request->input('quantidade', 1);
        $preco = $request->input('preco');
        $nome = $request->input('nome');

        $carrinho = Session::get('carrinho', []);

        if (isset($carrinho[$produtoId])) {
            // Atualiza a quantidade
            $carrinho[$produtoId]['quantidade'] += $quantidade;
            // Recalcula o valor a pagar
            $carrinho[$produtoId]['valor_pagar'] = $carrinho[$produtoId]['quantidade'] * $carrinho[$produtoId]['preco'];
        } else {
            // Adiciona novo produto ao carrinho com preço unitário
            $carrinho[$produtoId] = [
                'produto_id' => $produtoId,
                'nome' => $nome,
                'quantidade' => $quantidade,
                'preco' => $preco,  // Armazena o preço unitário
                'valor_pagar' => $preco * $quantidade
            ];
        }

        // Salva o carrinho na sessão
        Session::put('carrinho', $carrinho);

        // Calcula o total do carrinho
        $total = $this->calcularTotal($carrinho);

        // Retorna o carrinho atualizado como resposta JSON
        return response()->json(['carrinho' => $carrinho, 'total' => $total, 'message' => 'Produto adicionado ao carrinho com sucesso!']);
    }
    
    // Adiciona um produto ao carrinho
    public function codigo_barra(Request $request)
    {
        
        $request->validate([
            'produto_id' => 'required|exists:produtos,codigo_barra', // Validação do código de barras
        ]);
        
        $codigoBarra = $request->input('produto_id');
        $produto = Produto::where('codigo_barra', $codigoBarra)->first();
        
        if (!$produto) {
            return response()->json([
                'success' => false,
                'message' => 'Produto não encontrado.',
            ], 404);
        }
        
        $carrinho = Session::get('carrinho', []);
        if (isset($carrinho[$produto->id])) {
            // Atualiza a quantidade
            $carrinho[$produto->id]['quantidade'] += $request->input('quantidade');
            // Recalcula o valor a pagar
            $carrinho[$produto->id]['valor_pagar'] = $carrinho[$produto->id]['quantidade'] * $carrinho[$produto->id]['preco'];
            
        } else {
            // Adiciona novo produto ao carrinho com preço unitário
            $carrinho[$produto->id] = [
                'produto_id' => $produto->id,
                'nome' => $produto->nome,
                'quantidade' => $request->input('quantidade'),
                'preco' => $produto->preco_venda,  // Armazena o preço unitário
                'valor_pagar' => $produto->preco_venda * $request->input('quantidade')
            ];
        }
        // Salva o carrinho na sessão
        Session::put('carrinho', $carrinho);
        
        // Calcula o total do carrinho
        $total = $this->calcularTotal($carrinho);

        // Retorna o carrinho atualizado como resposta JSON
        return response()->json([ 'success' => true, 'carrinho' => $carrinho, 'total' => $total, 'message' => 'Produto adicionado ao carrinho com sucesso!']);
        
    }

    // Remove um produto do carrinho
    public function remover(Request $request)
    {
        $produtoId = $request->input('produto_id');

        $carrinho = Session::get('carrinho', []);

        if (isset($carrinho[$produtoId])) {
            unset($carrinho[$produtoId]);
            Session::put('carrinho', $carrinho);
        }

        // Calcula o total do carrinho
        $total = $this->calcularTotal($carrinho);

        // Retorna o carrinho atualizado como resposta JSON
        return response()->json(['carrinho' => $carrinho, 'total' => $total, 'message' => 'Produto removido do carrinho com sucesso!']);
    }

    // Processa o pagamento e limpa o carrinho
    public function pagamento(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $caixaActivo = Caixa::where([
            ['active', true],
            ['status', '=', 'aberto'],
            ['user_id', '=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->first();
        
        if(!$caixaActivo){
            return response()->json(['error' => 'Por favor, não podes realizar nenhuma venda sem antes abrir o caixa!'], 400);
        }    
        
        $cliente = Cliente::findOrFail($request['clienteId']);
        $subconta_cliente = Subconta::where('code', $cliente->code)->first();
                
        $code = uniqid(time());
                    
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
      
            $carrinho = Session::get('carrinho', []);
            
            foreach ($carrinho as $key => $car) {
                
                $produto = Produto::with('marca','variacao','categoria', 'estoque')->findOrFail($car['produto_id']);   
                
                $loja = Loja::where([
                    ['status', '=', 'activo'],
                    ['entidade_id', '=', $entidade->empresa->id], 
                ])->first();
                
                if(!$loja){
                    Alert::warning('Atenção', 'Não têm nenhuma loja/armazém activa no momento para registrar saída deste produto. Por favor activa uma loja/armazém que tem este produto!');
                    // Retorna a resposta de sucesso
                    return response()->json(['error' => 'Não têm nenhuma loja/armazém activa no momento para registrar saída deste produto!'], 400);
                    // return redirect()->back()->with('warning', 'Não têm nenhuma loja/armazém activa no momento para registrar saída deste produto.');
                }
                
                // verificar quantidade de produto no estoque da loja
                $verificar_quantidade = Estoque::where('loja_id', $loja->id)
                    ->where('produto_id', $produto->id)
                    ->where('stock', '>', 1)
                    ->where('entidade_id', $entidade->empresa->id)
                    ->sum('stock');
                    
                $gestao_quantidade = Estoque::where('loja_id', $loja->id)
                    ->where('produto_id', $produto->id)
                    ->where('stock', '>', 1)
                    ->where('entidade_id', $entidade->empresa->id)
                    ->first();
                
                $verificar_quantidade = (int) $verificar_quantidade;
                
                if($verificar_quantidade <= 0){
                    return response()->json(['error' => 'A Loja activa não têm este produto em stock para poder comercializar!'], 400);
                }
             
                Registro::create([
                    "registro" => "Saída de Stock",
                    "data_registro" => date('Y-m-d'),
                    "quantidade" => $car['quantidade'],
                    "produto_id" => $produto->id,
                    "observacao" => "Saída do produto {$produto->nome} para venda",
                    "loja_id" => $loja->id,
                    "lote_id" => NULL,
                    "user_id" => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
                     
                if($request['venda_realizado'] == "CAIXA"){
                    $status_uso = "CAIXA";
                    $mesa_id = NULL;
                    $caixa_id = $caixaActivo->id;
                }else{
                    $mesa_id = $request['venda_realizado'];
                    $status_uso = "MESA";
                    $caixa_id = NULL;
                }
                    
                if( $status_uso == "CAIXA"){
                    $verificarProdutoAdicionado = Itens_venda::where([
                        ['status', '=', 'processo'],
                        ['produto_id', '=', $produto->id],
                        ['caixa_id', '=', $caixa_id],
                        ['movimento_id', '=', 1],
                        ['entidade_id', '=', $entidade->empresa->id], 
                        ['entidade_id', '=', $entidade->empresa->id], 
                        ['user_id', '=', Auth::user()->id], 
                    ])->first();
                }
                
                if($status_uso == "MESA"){
                    $verificarProdutoAdicionado = Itens_venda::where([
                        ['status', '=', 'processo'],
                        ['produto_id', '=', $produto->id],
                        ['mesa_id', '=', $mesa_id],
                        ['movimento_id', '=', 1],
                        ['entidade_id', '=', $entidade->empresa->id], 
                        ['user_id', '=', Auth::user()->id], 
                    ])->first();
                }
                // calcudo do total de incidencia
                //________________ valor total _____________
                $valorBase = $car['preco'] ?? $produto->preco_venda * $car['quantidade']; 
                // calculo do iva
                $valorIva = ($produto->taxa / 100) * $valorBase;

                if($verificarProdutoAdicionado){
                    $update = Itens_venda::findOrFail($verificarProdutoAdicionado->id);
                   
                    $desconto = ($car['preco'] ?? $produto->preco_venda * ($update->quantidade + $car['quantidade'])) * ($update->desconto_aplicado / 100);

                    $valorBase = $car['preco'] ?? $produto->preco_venda * ($update->quantidade + $car['quantidade']); 
                    // calculo do iva
                    $valorIva = ($produto->taxa / 100) * $valorBase;

                    $update->quantidade = $update->quantidade + $car['quantidade'];
                    $update->valor_pagar = ($valorBase + $valorIva) - $desconto;
                    
                    $update->custo_ganho = ($car['preco'] ?? $produto->preco_venda - $produto->preco_custo) * $update->quantidade;

                    $update->desconto_aplicado = $update->desconto_aplicado;
                    $update->desconto_aplicado_valor = $desconto;

                    $update->valor_base = $valorBase;
                    $update->valor_iva = $valorIva;

                    $update->update();

                    $update_gestao_quantidade = Estoque::find($gestao_quantidade->id);
                    
                    if($update_gestao_quantidade){
                        $update_gestao_quantidade->stock = $update_gestao_quantidade->stock - $car['quantidade']; 
                        $update_gestao_quantidade->update(); 
                    }

                    // return redirect()->back();
                    // return redirect()->route('pronto-venda');
                }else{
                    $create = Itens_venda::create( [
                        'produto_id' => $produto->id,
                        'movimento_id' => 1,
                        'quantidade' => $car['quantidade'],
                        'user_id' => Auth::user()->id,
                        'valor_pagar' => $valorBase + $valorIva,
                        'preco_unitario' => $car['preco'] ?? $produto->preco_venda,
                        'custo_ganho' => ($car['preco'] ?? $produto->preco_venda - $produto->preco_custo) * $car['quantidade'],
                        'desconto_aplicado' => 0,
                        'status' => 'processo',
                        'valor_base' => $valorBase,
                        'valor_iva' => $valorIva,
                        'desconto_aplicado_valor' => 0,
                        'iva' => $produto->imposto,
                        'iva_taxa' => $produto->taxa,
                        'texto_opcional' => "",
                        'status_uso' => $status_uso,
                        'caixa_id' => $caixa_id,
                        'mesa_id' => $mesa_id,
                        'code' => NULL,
                        'numero_serie' => "",
                        'entidade_id' => $entidade->empresa->id,
                    ]);  
                        
                    $update_gestao_quantidade = Estoque::find($gestao_quantidade->id);
                    
                    if($update_gestao_quantidade){
                        $update_gestao_quantidade->stock = $update_gestao_quantidade->stock - $car['quantidade']; 
                        $update_gestao_quantidade->update(); 
                    }

                }
            }
            
            $request['total_pagar'] = (int) $request['total_pagar'];
    
            $valor_multicaixa = 0;
            $valor_cash = 0;
            
            // verificar se selecionou um produto ou não para realizar a venda
            $movimento = Itens_venda::where([
                ['user_id','=', Auth::user()->id],
                ['code', NULL],
                ['status_uso', '=', $request->venda_realizado],
                ['status', '=', 'processo'],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->with('produto')
            ->get(); 
        
            if(count($movimento) == 0) {
                return response()->json(['message' => 'O correu um erro, não existe nenhum produto selecionado!'], 404);
            } 
                            
            $contarFactura = Venda::where([
                ['factura', '=', $request->documento],
                ['ano_factura', '=', $entidade->empresa->ano_factura],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->count();
            
            $numeroFactura = $contarFactura + 1;
            
            $codigo_designacao_factura = "{$request->factura} {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$numeroFactura}";
           
            
            if($request['formaPagamento'] == "NU"){
                                
                $this->registra_operacoes(  
                    $request['total_pagar'],
                    $caixaActivo->id,
                    $cliente->id,
                    'R',
                    "pago",
                    $code,
                    "E",
                    date("Y-m-d"),
                    $entidade->empresa->id,
                    "Pagamento referente: {$codigo_designacao_factura}",
                    Auth::user()->id,
                    'pendente', 
                    'C', 
                    $caixaActivo->code_caixa
                );
                
                $valor_cash = $request->total_pagar;
                $valor_multicaixa = 0;
                $request['valorEntregue'] = $request['valorEntregue'];
                
                $banco_id = NULL;
                
            }else if($request['formaPagamento'] == "MB" || $request['formaPagamento'] == "TB" || $request['formaPagamento'] == "DE"){
                $bancoActivo = ContaBancaria::where([
                    ['active', true],
                    ['status', '=', 'aberto'],
                    ['user_id', '=', Auth::user()->id],
                    ['entidade_id', '=', $entidade->empresa->id], 
                ])->first();
                
                if( !$bancoActivo ) {
                    return response()->json(['message' => "TPA não activo ou seja não existe nenhum Conta Bancaria activo, verifica e activa uma conta bancária para poder realizar uma venda via TPA.!"], 404);
                }
                
                $this->registra_operacoes($request['total_pagar'], $bancoActivo->id, $cliente->id, 'R', "pago", $code, "E", date("Y-m-d"), $entidade->empresa->id, "Pagamento referente: {$codigo_designacao_factura}", Auth::user()->id, 'pendente', 'B', $caixaActivo->code_caixa);
                
                $valor_cash = 0;
                $valor_multicaixa = $request->total_pagar;
                $request['valorEntregue'] = $request['valorEntregueMulticaixa'];
                $banco_id = $caixaActivo->id;
                
            }else if($request['formaPagamento'] == "OU"){
            
                $valor_cash =  $request['valorEntregueInput'];
                $valor_multicaixa = $request['valorEntregueMulticaixaInput'];
                $request['valorEntregue'] = $request['valorEntregueMulticaixaInput'] + $request['valorEntregueInput'];
                $banco_id = $caixaActivo->id;

                $bancoActivo = ContaBancaria::where([
                    ['active', true],
                    ['status', '=', 'aberto'],
                    ['user_id', '=', Auth::user()->id],
                    ['entidade_id', '=', $entidade->empresa->id], 
                ])->first();
                
                if( !$bancoActivo ) {
                    return response()->json(['message' => "TPA não activo ou seja não existe nenhum Conta Bancaria activo, verifica e activa uma conta bancária para poder realizar uma venda via TPA.!"], 404);
                }
                
                $this->registra_operacoes($valor_multicaixa, $bancoActivo->id, $cliente->id, 'R', "pago", $code, "E", date("Y-m-d"), $entidade->empresa->id, "Pagamento referente: {$codigo_designacao_factura}", Auth::user()->id, 'pendente', 'B', $caixaActivo->code_caixa);
                
                $this->registra_operacoes($valor_cash, $caixaActivo->id, $cliente->id, 'R', "pago", $code, "E", date("Y-m-d"), $entidade->empresa->id, "Pagamento referente: {$codigo_designacao_factura}", Auth::user()->id, 'pendente', 'C', $caixaActivo->code_caixa);
                
            }
            
            if($entidade->empresa->tem_permissao("Gestão Contabilidade")){
                $subconta_venda_mercadoria= Subconta::where('numero', ENV('VENDA_DE_MERCADORIA'))->first();
                $subconta_prestacao_servico = Subconta::where('numero', ENV('PRESTACAO_SERVICO'))->first();
                $subconta_custo_mercadoria = Subconta::where('numero', ENV('CUSTO_MERCADORIA_VENDIDA'))->first();
                
                foreach($carrinho as $car){
                
                    $subconta_iva = Subconta::where('numero', ENV('IVA_LIQUIDADO'))->first();
                    $produt = Produto::findOrFail($car['produto_id']); 
                    $subconta_servico_produto = Subconta::where('code', $produt->code)->first();
                    
                    if($subconta_servico_produto){
                        // caso o serviço/produto cobrar IVA
                        if($produt->taxa != 0){
                            if($subconta_iva){
                                if($produt->tipo == "P"){
                                    $movimeto = Movimento::create([
                                        'user_id' => Auth::user()->id,
                                        'subconta_id' => $subconta_venda_mercadoria->id,
                                        'status' => true,
                                        'movimento' => 'S',
                                        'credito' => $car['valor_pagar'] ?? 0,
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
                                    $movimeto = Movimento::create([
                                        'user_id' => Auth::user()->id,
                                        'subconta_id' => $subconta_prestacao_servico->id,
                                        'status' => true,
                                        'movimento' => 'S',
                                        'credito' => $car['valor_pagar'] ?? 0,
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
                                        'credito' => ($produt->preco_custo ?? 0) * $car['quantidade'],
                                        'debito' => 0,
                                        'observacao' => $request->observacao,
                                        'code' => $code,
                                        'data_at' => date("Y-m-d"),
                                        'entidade_id' => $entidade->empresa->id,
                                        'exercicio_id' => 1,
                                        'periodo_id' => 12,
                                    ]);
                                    
                                    ## custo da mercadoria
                                    $movimeto = Movimento::create([
                                        'user_id' => Auth::user()->id,
                                        'subconta_id' => $subconta_custo_mercadoria->id,
                                        'status' => true,
                                        'movimento' => 'S',
                                        'credito' => 0,
                                        'debito' => ($produt->preco_custo ?? 0) * $car['quantidade'],
                                        'observacao' => $request->observacao,
                                        'code' => $code,
                                        'data_at' => date("Y-m-d"),
                                        'entidade_id' => $entidade->empresa->id,
                                        'exercicio_id' => 1,
                                        'periodo_id' => 12,
                                    ]);
                                }
                                
                                ## creditar na conta do IVA LIQUIDADO - 34.5.3.1
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
                                    'debito' => $car['valor_pagar'] ?? 0,
                                    'observacao' => $request->observacao,
                                    'code' => $code,
                                    'data_at' => date("Y-m-d"),
                                    'entidade_id' => $entidade->empresa->id,
                                    'exercicio_id' => 1,
                                    'periodo_id' => 12,
                                ]);
                                $movimeto = Movimento::create([
                                    'user_id' => Auth::user()->id,
                                    'subconta_id' => $subconta_cliente->id,
                                    'status' => true,
                                    'movimento' => 'E',
                                    'credito' => $car['valor_pagar'] ?? 0,
                                    'debito' => 0,
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
                                ## creditar na conta proveito - 26 - ou seja diminuir o valor sem o iva
                                $movimeto = Movimento::create([
                                    'user_id' => Auth::user()->id,
                                    'subconta_id' => $subconta_venda_mercadoria->id,
                                    'status' => true,
                                    'movimento' => 'S',
                                    'credito' => $car['valor_pagar'] ?? 0,
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
                                ## creditar na conta proveito - 26 - ou seja diminuir o valor sem o iva
                                $movimeto = Movimento::create([
                                    'user_id' => Auth::user()->id,
                                    'subconta_id' => $subconta_prestacao_servico->id,
                                    'status' => true,
                                    'movimento' => 'S',
                                    'credito' => $car['valor_pagar'] ?? 0,
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
                                    'credito' => ($produt->preco_custo ?? 0) * $car['quantidade'],
                                    'debito' => 0,
                                    'observacao' => $request->observacao,
                                    'code' => $code,
                                    'data_at' => date("Y-m-d"),
                                    'entidade_id' => $entidade->empresa->id,
                                    'exercicio_id' => 1,
                                    'periodo_id' => 12,
                                ]);
                                
                                ## custo da mercadoria
                                $movimeto = Movimento::create([
                                    'user_id' => Auth::user()->id,
                                    'subconta_id' => $subconta_custo_mercadoria->id,
                                    'status' => true,
                                    'movimento' => 'S',
                                    'credito' => 0,
                                    'debito' => ($produt->preco_custo ?? 0) * $car['quantidade'],
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
                                'debito' => $car['valor_pagar'],
                                'observacao' => $request->observacao,
                                'code' => $code,
                                'data_at' => date("Y-m-d"),
                                'entidade_id' => $entidade->empresa->id,
                                'exercicio_id' => 1,
                                'periodo_id' => 12,
                            ]);
                            
                            $movimeto = Movimento::create([
                                'user_id' => Auth::user()->id,
                                'subconta_id' => $subconta_cliente->id,
                                'status' => true,
                                'movimento' => 'E',
                                'credito' => $car['valor_pagar'],
                                'debito' => 0,
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
                
                if($request['formaPagamento'] == "NU"){
                    $subconta_caixa = Subconta::where('code', $caixaActivo->code)->first();
                    ## vamor aumentar o valor do caixa - 45/43
                    $movimeto = Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $subconta_caixa->id,
                        'status' => true,
                        'movimento' => 'E',
                        'credito' => 0,
                        'debito' => $request->total_pagar??0,
                        'observacao' => $request->observacao,
                        'code' => $code,
                        'data_at' => date("Y-m-d"),
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => 1,
                        'periodo_id' => 12,
                    ]);
                    
                }else if($request['formaPagamento'] == "MB"){
                    $bancoActivo = ContaBancaria::where([
                        ['active', true],
                        ['status', '=', 'aberto'],
                        ['user_id', '=', Auth::user()->id],
                        ['entidade_id', '=', $entidade->empresa->id], 
                    ])->first();
                    
                    $subconta_banco = Subconta::where('code', $bancoActivo->code)->first();
                    
                    $movimeto = Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $subconta_banco->id,
                        'status' => true,
                        'movimento' => 'E',
                        'credito' => 0,
                        'debito' => $request->total_pagar??0,
                        'observacao' => $request->observacao,
                        'code' => $code,
                        'data_at' => date("Y-m-d"),
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => 1,
                        'periodo_id' => 12,
                    ]);
                                    
                }else if($request['formaPagamento'] == "OU"){
                    $bancoActivo = ContaBancaria::where([
                        ['active', true],
                        ['status', '=', 'aberto'],
                        ['user_id', '=', Auth::user()->id],
                        ['entidade_id', '=', $entidade->empresa->id], 
                    ])->first();
                    if( $bancoActivo ) {
                        $subconta_caixa = Subconta::where('code', $caixaActivo->code)->first();
                        $subconta_banco = Subconta::where('code', $bancoActivo->code)->first();
                        
                        $movimeto = Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $subconta_caixa->id,
                            'status' => true,
                            'movimento' => 'E',
                            'credito' => 0,
                            'debito' => $request['valorEntregueInput'] ??0,
                            'observacao' => $request->observacao,
                            'code' => $code,
                            'data_at' => date("Y-m-d"),
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => 1,
                            'periodo_id' => 12,
                        ]);
                            
                        $movimeto = Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $subconta_banco->id,
                            'status' => true,
                            'movimento' => 'E',
                            'credito' => 0,
                            'debito' => $request['valorEntregueMulticaixaInput']??0,
                            'observacao' => $request->observacao,
                            'code' => $code,
                            'data_at' => date("Y-m-d"),
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => 1,
                            'periodo_id' => 12,
                        ]);
                        
                    }
                }
            }
            
            if($request['valorEntregue'] < $request['total_pagar']){
                return response()->json(['error' => 'O Valor Entregue para esta Compra é insuficiente!'], 400);
                Alert::warning('Erro', 'O Valor Entregue para esta Compra é insuficiente!');
            }          

            $ultimoRecibo = Venda::where([
                ['factura', '=', $request->documento],
                ['ano_factura', '=', $entidade->empresa->ano_factura],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->first();
    
            if(!$ultimoRecibo){
                $hashAnterior = "";
            }else{
                $hashAnterior = $ultimoRecibo->hash;
            }
                    
            //Manipulação de datas: data actual
            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
    
            $rsa = new RSA(); //Algoritimo RSA
    
            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();
    
            // Lendo a private key
            $rsa->loadKey($privatekey);
                    
            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "{$request->documento} {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$numeroFactura}" . ';' . number_format($request['total_pagar'], 2, ".", "") . ';' . $hashAnterior;
            
            /**
            * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
            * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */
                    
            // HASH
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
    
            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)
    
            // Lendo a public key
            $rsa->loadKey($publickey);
    
            $valor_extenso = $this->valor_por_extenso($request['total_pagar']);
    
            if($request->venda_realizado == "CAIXA"){
                $mesa = Caixa::find($caixaActivo->id);
            }
            
            if($request->venda_realizado == "MESA"){
                $mesa = Mesa::find($request['mesa_id']);
            }
                 
            $create = Venda::create([
                'codigo_factura' =>  $numeroFactura,
                'status' => true,
                'cliente_id' => $cliente->id,
                'banco_id' => $banco_id,
                'mesa_id' => $mesa ? $mesa->id : NULL,
                'mesa_caixa' => $request['venda_realizado'],
                'status_factura' => 'pago',
                'loja_id' => $caixaActivo->loja_id,
                'status_venda' => "realizado",
                'user_id' => Auth::user()->id,
                'caixa_id' => $caixaActivo->id,
                'valor_entregue' => $request['valorEntregue'],
                'valor_total' => $request['total_pagar'],
                'valor_troco' => $request['valorEntregue'] - $request['total_pagar'],
                'code' => $code,
                'ano_factura' => $entidade->empresa->ano_factura,
                'nome_cliente' => $request['nomeCliente'] ?? "CONSUMIDOR FINAL",
                'documento_nif' => $request['nomeNIF'] ?? "999999999",
                'desconto' => 0,
                'desconto_percentagem' => 0,
                'entidade_id' => $entidade->empresa->id, 
                'prazo' => 0,
                'data_emissao' => date("y-m-d"),
                'data_vencimento' => date("y-m-d"),
                'data_disponivel' => date("y-m-d"),
                'pagamento' => $request['formaPagamento'],
                'factura' => $request['documento'],
                'factura_next' => "{$request['documento']} {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$numeroFactura}",
                'observacao' => "venda realizada com sucesso!",
                'referencia' => "venda realizada com sucesso!",
    
                'retificado' => 'N',
                'convertido_factura' => 'N',
                'factura_divida' => 'N',
                'anulado' => 'N',
    
                'moeda' => $entidade->empresa->moeda ?? 'AOA',
                'valor_extenso' => $valor_extenso,
                'valor_cash' => $valor_cash,
                'valor_multicaixa' => $valor_multicaixa,
                'texto_hash' => $plaintext,
                'hash' => base64_encode($signaturePlaintext),
                'nif_cliente' => $cliente->nif,
            ]);
            
            if($create->save()){
                
                if($request->venda_realizado == "CAIXA"){
                    $movimentos = Itens_venda::where('user_id','=', Auth::user()->id)
                        ->where('status', '=', 'processo')
                        ->where('caixa_id','=', $mesa->id)
                        ->where('status_uso','=', "CAIXA")
                        ->where('entidade_id', '=', $entidade->empresa->id)
                        ->where('code', NULL)
                    ->get(); 
                }
                if($request->venda_realizado == "MESA"){
                    $movimentos = Itens_venda::where('user_id','=', Auth::user()->id)
                        ->where('mesa_id','=', $mesa->id)
                        ->where('status_uso','=', "MESA")
                        ->where('status', '=', 'processo')
                        ->where('entidade_id', '=', $entidade->empresa->id)
                        ->where('code', NULL)
                    ->get(); 
                }
    
                $totalValorBase = 0;
                $totalValorIva = 0;
                $totalItems = 0;
    
                if($movimentos){
                    foreach ($movimentos as $value) {
                        $update = Itens_venda::findOrFail($value->id);
                        $update->code = $code;
                        $update->status = "realizado";
                        $update->factura_id = $create->id;
                        $update->banco_id = $banco_id;
                        $update->update();
                        
                        $totalValorBase+= $value->valor_base;
                        $totalValorIva+= $value->valor_iva;
                        $totalItems+= $value->quantidade;
                    }
                }
    
                $create->total_iva = $totalValorIva;
                $create->total_incidencia = $totalValorBase;
                $create->quantidade = $totalItems;
                $create->save();
    
            }
    
            if($request['venda_realizado'] == "MESA"){ 
                $mesa->solicitar_ocupacao = "LIVRE";
                $mesa->update();
            }
       
            $vendas = Venda::with('cliente')->where('code', $create->code)->first();
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            $items = Itens_venda::with('produto')->where('code', $vendas->code)->get();
    
            $factura = Venda::with('cliente')->with('caixa')->with('user')->where('code', $vendas->code)->first();
            
            $movimentos = Itens_venda::with('produto.motivo')->where('code', $factura->code)->where('entidade_id', $entidade->empresa->id)->get();
            
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
                    if ($item->iva == 'OUT'){
                        $total_incidencia_out = $total_incidencia_out + $item->valor_base;
                        $total_iva_out = $total_iva_out + $item->valor_iva;
                    }
                }
            }
          
            // Aqui você deve adicionar a lógica para processar o pagamento...
            // Por exemplo, integração com gateway de pagamento, verificação de estoque, etc.
    
            // Após o pagamento, limpar o carrinho
            Session::forget('carrinho');
            
          // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    
        // Após o pagamento, limpar o carrinho
        Session::forget('carrinho');

        $head = [
            'titulo' => "Movimentos do Stock",
            'descricao' => env('APP_NAME'),
            "loja" => $entidade,
            "factura" => $vendas,
            "items_facturas" => $items,
            
            // "items_facturas_movimentos" => $movimentos,
            "total_incidencia_nor" => $total_incidencia_nor,
            "total_iva_nor" => $total_iva_nor,

            "total_incidencia_ise" => $total_incidencia_ise,
            "total_iva_ise" => $total_iva_ise,

            "total_incidencia_out" => $total_incidencia_out,
            "total_iva_out" => $total_iva_out,
            "motivo" => $motivo,
            "venda_realizado" => $request->venda_realizado,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        // Retorna a resposta de sucesso
        return response()->json(['message' => 'Pagamento realizado com sucesso!', 'data' => $head], 200);
    }

    
    public function inserir_operacao($descricao, $motante, $subconta, $cliente, $model, $type, $code, $movimento, $formas = "C")
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
    
        OperacaoFinanceiro::create([
            'nome' => $descricao,
            'status' => "pago",
            'formas' => $formas,
            'motante' => $motante,
            'subconta_id' => $subconta,
            'cliente_id' => $cliente,
            'model_id' => $model,
            'type' => $type,
            'parcelado' => "N",
            'status_pagamento' => "pago",
            'code' => $code,
            'descricao' => $descricao,
            'movimento' => $movimento,
            'date_at' => date("Y-m-d"),
            'user_id' => Auth::user()->id,
            'entidade_id' => $entidade->empresa->id,
            'exercicio_id' => 1,
            'periodo_id' => 12,
        ]);
    }


    // Método auxiliar para calcular o total do carrinho
    private function calcularTotal($carrinho)
    {
        return array_reduce($carrinho, function($carry, $item) {
            return $carry + $item['valor_pagar'];
        }, 0);
    }
    
    // Adiciona um produto ao carrinho
    public function adicionar_mesa(Request $request)
    {
            
        try {
            // Inicia a transação
            DB::beginTransaction();
            // Comita a transação se tudo estiver correto
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            $produto = Produto::with('marca','variacao','categoria', 'estoque')->findOrFail($request->produto_id);  
            $mesa = Mesa::findOrFail($request->mesa);
            
            // $verifica se tem uma loja activa onde esta sendo retidados os produtos
            $loja = Loja::where([
                ['status', 'activo'],
                ['entidade_id', $entidade->empresa->id], 
            ])->first();
            
            if(!$loja){
                return response()->json([
                    'messagem' => "Não têm nenhuma loja/armazém activa no momento para registrar saída deste produto. Por favor activa uma loja/armazém que tem este produto!"
                ], 404);
            }
                        
            // verificar quantidade de produto no estoque da loja
            $verificar_quantidade = Estoque::where('loja_id', $loja->id)
                ->where('produto_id', $produto->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->sum('stock');
            
            $verificar_quantidade = (int) $verificar_quantidade;
            
            if($verificar_quantidade <= 0){
                return response()->json([
                    'messagem' => "A Loja activa não têm este produto em stock para poder comercializar!"
                ], 404);
            }
            
            if($produto->estoque){
                if($produto->estoque->stock <= $produto->estoque->stock_minimo){
                    return response()->json([
                        'messagem' => "A quantidade deste produto em estoque está abaixo do limite crítico, impedindo a venda no momento."
                    ], 404);
                }       
            }else{
                return response()->json([
                    'messagem' => "A quantidade deste produto em estoque está abaixo do limite crítico, impedindo a venda no momento."
                ], 404);
            }            
    
    
            $caixaActivo = Caixa::where([
                ['active', true],
                ['status', '=', 'aberto'],
                ['user_id', '=', Auth::user()->id],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->first();
            
            if($caixaActivo){
                
                $status_uso = "MESA";
                $caixa_id = NULL;
                
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
                
                $verificarProdutoAdicionado = Itens_venda::where([
                    ['status', '=', 'processo'],
                    ['produto_id', '=', $produto->id],
                    ['mesa_id', '=', $request->mesa],
                    ['status_uso', '=', $status_uso],
                    ['entidade_id', '=', $entidade->empresa->id], 
                    ['user_id', '=', Auth::user()->id], 
                ])->first();
                
                // calcudo do total de incidencia
                //________________ valor total _____________
                $valorBase = $produto->preco_venda * $request->quantidade ?? 1; 
                // calculo do iva
                $valorIva = ($produto->taxa / 100) * $valorBase;
                
                if($verificarProdutoAdicionado){
                    $update = Itens_venda::findOrFail($verificarProdutoAdicionado->id);
                   
                    $desconto = ($produto->preco_venda * ($update->quantidade + $request->quantidade??1)) * ($update->desconto_aplicado / 100);

                    $valorBase = $produto->preco_venda * ($update->quantidade + $request->quantidade??1); 
                    // calculo do iva
                    $valorIva = ($produto->taxa / 100) * $valorBase;

                    $update->quantidade = $update->quantidade + $request->quantidade??1;
                    $update->valor_pagar = ($valorBase + $valorIva) - $desconto;
                    
                    $update->custo_ganho = ($produto->preco_venda - $produto->preco_custo) * $update->quantidade;

                    $update->desconto_aplicado = $update->desconto_aplicado;
                    $update->desconto_aplicado_valor = $desconto;

                    $update->valor_base = $valorBase;
                    $update->valor_iva = $valorIva;

                    $update->update();

                    $produto->estoque->stock = $produto->estoque->stock - $request->quantidade ?? 1; 
                    $produto->estoque->update(); 

                }else{
                    $create = Itens_venda::create(
                        [
                            'produto_id' => $produto->id,
                            // 'movimento_id' => $movimentoActivo->id,
                            'quantidade' => $request->quantidade ?? 1,
                            'user_id' => Auth::user()->id,
                            'valor_pagar' => $valorBase + $valorIva,
                            'preco_unitario' => $produto->preco_venda,
                            'custo_ganho' => ($produto->preco_venda - $produto->preco_custo) * $request->quantidade ?? 1,
                            'desconto_aplicado' => 0,
                            'status' => 'processo',
                            'valor_base' => $valorBase,
                            'valor_iva' => $valorIva,
                            'desconto_aplicado_valor' => 0,
                            'iva' => $produto->imposto,
                            'iva_taxa' => $produto->taxa,
                            'texto_opcional' => "",
                            'status_uso' => $status_uso,
                            'caixa_id' => $caixa_id,
                            'mesa_id' => $mesa->id,
                            'code' => NULL,
                            'numero_serie' => "",
                            'entidade_id' => $entidade->empresa->id,
                        ]
                    );  
                    
                    if($create->save()){
                        $produto->estoque->stock = $produto->estoque->stock - $request->quantidade; 
                        $produto->estoque->update(); 
                    }else{
                        return response()->json([
                            'messagem' => "O correu um erro ão tentar adicionar este produto!"
                        ], 404);
                    }
                }
            }else{
                return response()->json([
                    'messagem' => "Verifica se tens um caixa aberto, por favor!"
                ], 404);
            }
        
            $movimentos = Itens_venda::where([
                ['code', '=', NULL],
                ['status', '=', 'processo'],
                ['status_uso', '=', 'MESA'],
                ['mesa_id', '=', $mesa->id],
                ['user_id','=', Auth::user()->id],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->with('produto')->get();
    
            $total_pagar = Itens_venda::where([
                ['code', '=', NULL],
                ['status', '=', 'processo'],
                ['status_uso', '=', 'MESA'],
                ['mesa_id', '=', $mesa->id],
                ['user_id','=', Auth::user()->id],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->sum('valor_pagar');
    
            $total_produtos = Itens_venda::where([
                ['code', '=', NULL],
                ['status', '=', 'processo'],
                ['status_uso', '=', 'MESA'],
                ['mesa_id', '=', $mesa->id],
                ['entidade_id', '=', $entidade->empresa->id], 
                ['user_id','=', Auth::user()->id],
            ])->count();
    
            $total_unidades = Itens_venda::where([
                ['code', '=', NULL],
                ['status', '=', 'processo'],
                ['status_uso', '=', 'MESA'],
                ['mesa_id', '=', $mesa->id],
                ['entidade_id', '=', $entidade->empresa->id], 
                ['user_id','=', Auth::user()->id],
            ])->sum('quantidade');
   
            
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger('Error', $e->getMessage());
            return redirect()->back()->with('danger', $e->getMessage());
        }
        
        return response()->json([
            "movimentos" => $movimentos,
            "total_pagar" => $total_pagar,
            "total_produtos" => $total_produtos,
            "total_unidades" => $total_unidades,
        ], 200);
    }
    
    // Adiciona um produto ao carrinho
    public function adicionar_quarto(Request $request)
    {
        try {
            // Inicia a transação
            DB::beginTransaction();
            // Comita a transação se tudo estiver correto
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            $produto = Produto::with('marca','variacao','categoria', 'estoque')->findOrFail($request->produto_id);  
            $quarto = Quarto::findOrFail($request->quarto);
            
            // $verifica se tem uma loja activa onde esta sendo retidados os produtos
            $loja = Loja::where([
                ['status', 'activo'],
                ['entidade_id', $entidade->empresa->id], 
            ])->first();
            
            if(!$loja){
                return response()->json([
                    'messagem' => "Não têm nenhuma loja/armazém activa no momento para registrar saída deste produto. Por favor activa uma loja/armazém que tem este produto!"
                ], 404);
            }
                        
            // verificar quantidade de produto no estoque da loja
            $verificar_quantidade = Estoque::where('loja_id', $loja->id)
                ->where('produto_id', $produto->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->sum('stock');
            
            $verificar_quantidade = (int) $verificar_quantidade;
            
            if($verificar_quantidade <= 0){
                return response()->json([
                    'messagem' => "A Loja activa não têm este produto em stock para poder comercializar!"
                ], 404);
            }
            
            if($produto->estoque){
                if($produto->estoque->stock <= $produto->estoque->stock_minimo){
                    return response()->json([
                        'messagem' => "A quantidade deste produto em estoque está abaixo do limite crítico, impedindo a venda no momento."
                    ], 404);
                }       
            }else{
                return response()->json([
                    'messagem' => "A quantidade deste produto em estoque está abaixo do limite crítico, impedindo a venda no momento."
                ], 404);
            }            
    
    
            $caixaActivo = Caixa::where([
                ['active', true],
                ['status', '=', 'aberto'],
                ['user_id', '=', Auth::user()->id],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->first();
         
            if($caixaActivo){
                
                $status_uso = "QUARTO";
                $caixa_id = NULL;
                
                Registro::create([
                    "registro" => "Saída de Stock",
                    "data_registro" => date('Y-m-d'),
                    "quantidade" => 1,
                    "produto_id" => $produto->id,
                    "observacao" => "Saída do produto {$produto->nome} para venda quarto",
                    "loja_id" => $loja->id,
                    "lote_id" => NULL,
                    "user_id" => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
                
                $verificarProdutoAdicionado = Itens_venda::where([
                    ['status', '=', 'processo'],
                    ['produto_id', '=', $produto->id],
                    ['quarto_id', '=', $request->quarto],
                    ['status_uso', '=', $status_uso],
                    ['entidade_id', '=', $entidade->empresa->id], 
                    ['user_id', '=', Auth::user()->id], 
                ])->first();
                
                // calcudo do total de incidencia
                //________________ valor total _____________
                $valorBase = $produto->preco_venda * $request->quantidade ?? 1; 
                // calculo do iva
                $valorIva = ($produto->taxa / 100) * $valorBase;
                
                if($verificarProdutoAdicionado){
                    $update = Itens_venda::findOrFail($verificarProdutoAdicionado->id);
                   
                    $desconto = ($produto->preco_venda * ($update->quantidade + $request->quantidade??1)) * ($update->desconto_aplicado / 100);

                    $valorBase = $produto->preco_venda * ($update->quantidade + $request->quantidade??1); 
                    // calculo do iva
                    $valorIva = ($produto->taxa / 100) * $valorBase;

                    $update->quantidade = $update->quantidade + $request->quantidade??1;
                    $update->valor_pagar = ($valorBase + $valorIva) - $desconto;
                    
                    $update->custo_ganho = ($produto->preco_venda - $produto->preco_custo) * $update->quantidade;

                    $update->desconto_aplicado = $update->desconto_aplicado;
                    $update->desconto_aplicado_valor = $desconto;

                    $update->valor_base = $valorBase;
                    $update->valor_iva = $valorIva;

                    $update->update();

                    $produto->estoque->stock = $produto->estoque->stock - $request->quantidade ?? 1; 
                    $produto->estoque->update(); 

                }else{
                
                    $create = Itens_venda::create([
                        'produto_id' => $produto->id,
                        // 'movimento_id' => $movimentoActivo->id,
                        'quantidade' => $request->quantidade ?? 1,
                        'user_id' => Auth::user()->id,
                        'valor_pagar' => $valorBase + $valorIva,
                        'preco_unitario' => $produto->preco_venda,
                        'custo_ganho' => ($produto->preco_venda - $produto->preco_custo) * $request->quantidade ?? 1,
                        'desconto_aplicado' => 0,
                        'status' => 'processo',
                        'valor_base' => $valorBase,
                        'valor_iva' => $valorIva,
                        'desconto_aplicado_valor' => 0,
                        'iva' => $produto->imposto,
                        'iva_taxa' => $produto->taxa,
                        'texto_opcional' => "",
                        'status_uso' => $status_uso,
                        'caixa_id' => $caixa_id,
                        'quarto_id' => $quarto->id,
                        'code' => NULL,
                        'numero_serie' => "",
                        'entidade_id' => $entidade->empresa->id,
                    ]);  
                    
                    if($create->save()){
                        $produto->estoque->stock = $produto->estoque->stock - $request->quantidade; 
                        $produto->estoque->update(); 
                    }else{
                        return response()->json([
                            'messagem' => "O correu um erro ão tentar adicionar este produto!"
                        ], 404);
                    }
                }
            }else{
                return response()->json([
                    'messagem' => "Verifica se tens um caixa aberto, por favor!"
                ], 404);
            }
        
            $movimentos = Itens_venda::where([
                ['code', '=', NULL],
                ['status', '=', 'processo'],
                ['status_uso', '=', $status_uso],
                ['quarto_id', '=', $quarto->id],
                ['user_id','=', Auth::user()->id],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->with('produto')->get();
    
            $total_pagar = Itens_venda::where([
                ['code', '=', NULL],
                ['status', '=', 'processo'],
                ['status_uso', '=', $status_uso],
                ['quarto_id', '=', $quarto->id],
                ['user_id','=', Auth::user()->id],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->sum('valor_pagar');
    
            $total_produtos = Itens_venda::where([
                ['code', '=', NULL],
                ['status', '=', 'processo'],
                ['status_uso', '=', $status_uso],
                ['quarto_id', '=', $quarto->id],
                ['entidade_id', '=', $entidade->empresa->id], 
                ['user_id','=', Auth::user()->id],
            ])->count();
    
            $total_unidades = Itens_venda::where([
                ['code', '=', NULL],
                ['status', '=', 'processo'],
                ['status_uso', '=', $status_uso],
                ['quarto_id', '=', $quarto->id],
                ['entidade_id', '=', $entidade->empresa->id], 
                ['user_id','=', Auth::user()->id],
            ])->sum('quantidade');
   
            
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger('Error', $e->getMessage());
            return redirect()->back()->with('danger', $e->getMessage());
        }
        
        return response()->json([
            "movimentos" => $movimentos,
            "total_pagar" => $total_pagar,
            "total_produtos" => $total_produtos,
            "total_unidades" => $total_unidades,
        ], 200);
    }


    // Remove um produto do carrinho
    public function remover_mesa(Request $request)
    {
        try {
            // Inicia a transação
            DB::beginTransaction();
            
            $movimento = Itens_venda::findOrFail($request->itemId);
        
            $produto = Produto::with('marca','variacao','categoria', 'estoque')->findOrFail($movimento->produto_id);  
            
            $produto->estoque->stock = $produto->estoque->stock + $movimento->quantidade ?? 1; 
            $produto->estoque->update(); 
            
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            $loja = Loja::where([
               ['status', 'activo'],
               ['entidade_id', $entidade->empresa->id], 
           ])->first();
            
            Registro::create([
                "registro" => "Entrada de Stock",
                "data_registro" => date('Y-m-d'),
                "quantidade" => $movimento->quantidade ?? 1,
                "produto_id" => $produto->id,
                "observacao" => "Entrada do produto {$produto->nome} para venda mesa",
                "loja_id" => $loja->id,
                "lote_id" => NULL,
                "user_id" => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);
            
            $status_uso = "MESA";
            $movimento->delete();
            
            $movimentos = Itens_venda::where([
                ['code', '=', NULL],
                ['status', '=', 'processo'],
                ['status_uso', '=', $status_uso],
                ['mesa_id', '=', $movimento->mesa_id],
                ['user_id','=', Auth::user()->id],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->with('produto')->get();
    
            $total_pagar = Itens_venda::where([
                ['code', '=', NULL],
                ['status', '=', 'processo'],
                ['status_uso', '=', $status_uso],
                ['mesa_id', '=', $movimento->mesa_id],
                ['user_id','=', Auth::user()->id],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->sum('valor_pagar');
    
            $total_produtos = Itens_venda::where([
                ['code', '=', NULL],
                ['status', '=', 'processo'],
                ['status_uso', '=', $status_uso],
                ['mesa_id', '=', $movimento->mesa_id],
                ['entidade_id', '=', $entidade->empresa->id], 
                ['user_id','=', Auth::user()->id],
            ])->count();
    
            $total_unidades = Itens_venda::where([
                ['code', '=', NULL],
                ['status', '=', 'processo'],
                ['status_uso', '=', $status_uso],
                ['mesa_id', '=', $movimento->mesa_id],
                ['entidade_id', '=', $entidade->empresa->id], 
                ['user_id','=', Auth::user()->id],
            ])->sum('quantidade');
           
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger('Error', $e->getMessage());
            return redirect()->back()->with('danger', $e->getMessage());
        }
        
        return response()->json([
            "movimentos" => $movimentos,
            "total_pagar" => $total_pagar,
            "total_produtos" => $total_produtos,
            "total_unidades" => $total_unidades,
        ], 200);
    
    }

    // Remove um produto do carrinho
    public function remover_quarto(Request $request)
    {
        try {
            // Inicia a transação
            DB::beginTransaction();
            
            $movimento = Itens_venda::findOrFail($request->itemId);
            $produto = Produto::with('marca','variacao','categoria', 'estoque')->findOrFail($movimento->produto_id);  
            
            $produto->estoque->stock = $produto->estoque->stock + $movimento->quantidade ?? 1; 
            $produto->estoque->update(); 
            
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            $loja = Loja::where([
               ['status', 'activo'],
               ['entidade_id', $entidade->empresa->id], 
           ])->first();
            
            Registro::create([
                "registro" => "Entrada de Stock",
                "data_registro" => date('Y-m-d'),
                "quantidade" => $movimento->quantidade ?? 1,
                "produto_id" => $produto->id,
                "observacao" => "Entrada do produto {$produto->nome} para venda quarto",
                "loja_id" => $loja->id,
                "lote_id" => NULL,
                "user_id" => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);
            
            $status_uso = "QUARTO";
            $movimento->delete();
            
            $movimentos = Itens_venda::where([
                ['code', '=', NULL],
                ['status', '=', 'processo'],
                ['status_uso', '=', $status_uso],
                ['quarto_id', '=', $movimento->quarto_id],
                ['user_id','=', Auth::user()->id],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->with('produto')->get();
    
            $total_pagar = Itens_venda::where([
                ['code', '=', NULL],
                ['status', '=', 'processo'],
                ['status_uso', '=', $status_uso],
                ['quarto_id', '=', $movimento->quarto_id],
                ['user_id','=', Auth::user()->id],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->sum('valor_pagar');
    
            $total_produtos = Itens_venda::where([
                ['code', '=', NULL],
                ['status', '=', 'processo'],
                ['status_uso', '=', $status_uso],
                ['quarto_id', '=', $movimento->quarto_id],
                ['entidade_id', '=', $entidade->empresa->id], 
                ['user_id','=', Auth::user()->id],
            ])->count();
    
            $total_unidades = Itens_venda::where([
                ['code', '=', NULL],
                ['status', '=', 'processo'],
                ['status_uso', '=', $status_uso],
                ['quarto_id', '=', $movimento->quarto_id],
                ['entidade_id', '=', $entidade->empresa->id], 
                ['user_id','=', Auth::user()->id],
            ])->sum('quantidade');
           
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger('Error', $e->getMessage());
            return redirect()->back()->with('danger', $e->getMessage());
        }
        
        return response()->json([
            "movimentos" => $movimentos,
            "total_pagar" => $total_pagar,
            "total_produtos" => $total_produtos,
            "total_unidades" => $total_unidades,
        ], 200);
    }


    public function carregar_vendas_mesas(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["caixas", "users", "variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);
        
        $mesa = Mesa::findOrFail($request->mesa);
        
        $movimentos = Itens_venda::where([
            ['code', '=', NULL],
            ['status', '=', 'processo'],
            ['status_uso', '=', 'MESA'],
            ['mesa_id', '=', $mesa->id],
            ['user_id','=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id], 
        ])
        ->with('produto')->get();

        $total_pagar = Itens_venda::where([
            ['code', '=', NULL],
            ['status', '=', 'processo'],
            ['status_uso', '=', 'MESA'],
            ['mesa_id', '=', $mesa->id],
            ['user_id','=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->sum('valor_pagar');

        $total_produtos = Itens_venda::where([
            ['code', '=', NULL],
            ['status', '=', 'processo'],
            ['status_uso', '=', 'MESA'],
            ['mesa_id', '=', $mesa->id],
            ['entidade_id', '=', $entidade->empresa->id], 
            ['user_id','=', Auth::user()->id],
        ])->count();

        $total_unidades = Itens_venda::where([
            ['code', '=', NULL],
            ['status', '=', 'processo'],
            ['status_uso', '=', 'MESA'],
            ['mesa_id', '=', $mesa->id],
            ['entidade_id', '=', $entidade->empresa->id], 
            ['user_id','=', Auth::user()->id],
        ])->sum('quantidade');
        
    
        return response()->json([
            "movimentos" => $movimentos,
            "total_pagar" => $total_pagar,
            "total_produtos" => $total_produtos,
            "total_unidades" => $total_unidades,
        ], 200);
    
    }

    public function carregar_vendas_quartos(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["caixas", "users", "variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);
        
        $quarto = Quarto::findOrFail($request->quarto);
        
        $movimentos = Itens_venda::where([
            ['code', '=', NULL],
            ['status', '=', 'processo'],
            ['status_uso', '=', 'QUARTO'],
            ['quarto_id', '=', $quarto->id],
            ['user_id','=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id], 
        ])
        ->with('produto')->get();

        $total_pagar = Itens_venda::where([
            ['code', '=', NULL],
            ['status', '=', 'processo'],
            ['status_uso', '=', 'QUARTO'],
            ['quarto_id', '=', $quarto->id],
            ['user_id','=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->sum('valor_pagar');

        $total_produtos = Itens_venda::where([
            ['code', '=', NULL],
            ['status', '=', 'processo'],
            ['status_uso', '=', 'QUARTO'],
            ['quarto_id', '=', $quarto->id],
            ['entidade_id', '=', $entidade->empresa->id], 
            ['user_id','=', Auth::user()->id],
        ])->count();

        $total_unidades = Itens_venda::where([
            ['code', '=', NULL],
            ['status', '=', 'processo'],
            ['status_uso', '=', 'QUARTO'],
            ['quarto_id', '=', $quarto->id],
            ['entidade_id', '=', $entidade->empresa->id], 
            ['user_id','=', Auth::user()->id],
        ])->sum('quantidade');
        
    
        return response()->json([
            "movimentos" => $movimentos,
            "total_pagar" => $total_pagar,
            "total_produtos" => $total_produtos,
            "total_unidades" => $total_unidades,
        ], 200);
    
    }

}
