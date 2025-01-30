<?php

namespace App\Http\Controllers\app\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitChavesSaft;
use App\Http\Controllers\TraitHelpers;
use App\Models\Quarto;
use App\Models\Caixa;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Entidade;
use App\Models\Estoque;
use App\Models\Subconta;
use App\Models\ContaBancaria;
use App\Models\OperacaoFinanceiro;
use App\Models\Itens_venda;
use App\Models\Loja;
use App\Models\Mesa;
use App\Models\Movimento;
use App\Models\Pin;
use App\Models\Produto;
use App\Models\ProdutoGrupoPreco;
use App\Models\Registro;
use App\Models\Reserva;
use App\Models\TipoPagamento;
use App\Models\User;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\Session;

use phpseclib\Crypt\RSA;

class VendaController extends Controller
{
    //
    use TraitChavesSaft;
    use TraitHelpers;

    public function vendas(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
       
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
        
        $empresa = Entidade::with(["caixas", "users"])->findOrFail($entidade->empresa->id);
        
        $head = [
            "titulo" => "Vendas",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "vendas" => $vendas,
            "entidade" => $entidade,
            "requests" => $request->all('data_inicio', 'data_final','caixa_id', 'user_id'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        // return view('dashboard.pronto-venda.home', $head);
        return view('dashboard.vendas.dashboard', $head);
    }    

    public function vendas_produtos(Request $request)
    {
    
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
       
        $query = Itens_venda::with(['produto', 'user', 'factura.cliente'])
        ->where('entidade_id', $entidade->empresa->id)
        ->whereIn('status', ['realizado'])
        ->whereHas('factura', function($query) {
            $query->whereIn('status_factura', ['pago']);
        })
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
        ->orderBy('created_at', 'desc');
        
        $total_venda = $query->sum('valor_pagar');
        
        $vendas = $query->get();
        
        $empresa = Entidade::with(["caixas", "users"])->findOrFail($entidade->empresa->id);
        
        $head = [
            "titulo" => "Vendas",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "total_venda" => $total_venda,
            "vendas" => $vendas,
            "entidade" => $entidade,
            "requests" => $request->all('data_inicio', 'data_final','caixa_id', 'user_id'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.vendas.dashboard-produtos', $head);
    }    

    public function vendas_por_produtos(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $request->data_inicio = $request->data_inicio ?? date("Y-m-d");
        $request->data_final = $request->data_final ?? date("Y-m-d");
        
        $vendas = Itens_venda::with(['produto', 'user', 'factura.cliente'])
        ->select(
            'produto_id',
            DB::raw('SUM(quantidade) as total_quantidade'),
            DB::raw('SUM(valor_pagar) as total_valor'),
            DB::raw('SUM(iva_taxa) as total_iva')
        )
        ->where('entidade_id', $entidade->empresa->id)
        ->whereIn('status', ['realizado'])
        ->whereHas('factura', function($query) {
            $query->whereIn('status_factura', ['pago']);
        })
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
        ->groupBy('produto_id')
        ->get();
        
        $empresa = Entidade::with(["caixas", "users"])->findOrFail($entidade->empresa->id);
        
        
        
        $head = [
            "titulo" => env('APP_NAME') ." Pronto de Vendas",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "total_venda" => 0,
            // "total_venda" => $total_venda,
            "vendas" => $vendas,
            "entidade" => $entidade,
            "requests" => $request->all('data_inicio', 'data_final','caixa_id', 'user_id'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.vendas.dashboard-por-produtos', $head);
    }    

    public function vendas_por_artigo(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $produtos = Produto::with(['vendas' => function ($query) use ($request) {
            // Filtrar as vendas com base nas datas fornecidas pelo usuário
            $query->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('created_at', '>=', Carbon::parse($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('created_at', '<=', Carbon::parse($value));
            });
            $query->where('status', "!=", "anulada");
        }, 'stocks' => function ($query) use ($request) {
            // Filtrar os estoques com base na data final fornecida pelo usuário
            $query->when($request->data_final, function ($query, $value) {
                $query->whereDate('created_at', '<=', Carbon::parse($value));
            });
        }])
        ->where('entidade_id', $entidade->empresa->id)
        ->get();

        // Preparar os dados para a resposta
        $dados = $produtos->map(function ($produto) use ($request) {
            $dataInicio = $request->data_inicio ? Carbon::parse($request->data_inicio) : Carbon::now()->startOfDay();
            $dataFinal = $request->data_final ? Carbon::parse($request->data_final) : Carbon::now()->endOfDay();
      
            // totdal vendido
            $quantidadeVendida = $produto->vendas
                ->whereBetween('created_at', [$dataInicio, $dataFinal])
                // ->where('registro', 'Saída de Stock')
                ->sum('quantidade');
                
            $totalVendida = $produto->vendas
                ->whereBetween('created_at', [$dataInicio, $dataFinal])
                // ->where('registro', 'Saída de Stock')
                ->sum('valor_pagar');
                
            $totalCustoGanho = $produto->vendas
                ->whereBetween('created_at', [$dataInicio, $dataFinal])
                // ->where('registro', 'Saída de Stock')
                ->sum('custo_ganho');
        
            // Calcular a quantidade em estoque até a data final especificada
            $quantidadeEmEstoque = $produto->stocks
                ->where('created_at', '<=', $dataFinal)
                ->sum('stock');
            
            // Calcular a quantidade restante
            $quantidadeRestante = $quantidadeEmEstoque - $quantidadeVendida;
        
            // Calcular a quantidade inicial
            $quantidadeInicial = $quantidadeEmEstoque + $quantidadeVendida;
            
            return  (object) [
                'id' => $produto->id,
                'produto' => $produto->nome,
                'preco' => $produto->preco_venda,
                'custo' => $produto->preco_custo,
                'imposto' => $produto->taxa,
                'desconto' => 0,
                'total_liquido_vendido' => $totalVendida,
                'total_liquido_custo' => $totalCustoGanho,
                // 'total_liquido_vendido' => $produto->preco_venda * $quantidadeVendida,
                'total_liquido_restante' => $produto->preco_venda * $quantidadeInicial,
                'total_liquido_geral' => $produto->preco_venda * $quantidadeEmEstoque,
                'quantidade_inicial' => $quantidadeInicial,
                'quantidade_vendida' => $quantidadeVendida,
                'quantidade_estoque' => $quantidadeEmEstoque,
                'quantidade_restante' => $quantidadeRestante,
            ];
            
        });
        
                
        $empresa = Entidade::with(["caixas", "users", "lojas"])->findOrFail($entidade->empresa->id);
        
        $head = [
            "titulo" => "Vendas por Artigos",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "vendas" => $dados,
            "entidade" => $entidade,
            "requests" => $request->all('data_inicio', 'data_final','loja_id', 'user_id'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.vendas.dashboard-por-artigos', $head);
    }    

    public function vendas_por_artigo_anterior(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $produtos = Produto::with(['vendas' => function ($query) use ($request) {
            // Filtrar as vendas com base nas datas fornecidas pelo usuário
            $query->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('created_at', '>=', Carbon::parse($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('created_at', '<=', Carbon::parse($value));
            });
            $query->where('status', "!=", "anulada");
        }, 'stocks' => function ($query) use ($request) {
            // Filtrar os estoques com base na data final fornecida pelo usuário
            $query->when($request->data_final, function ($query, $value) {
                $query->whereDate('created_at', '<=', Carbon::parse($value));
            });
        }])
        ->where('entidade_id', $entidade->empresa->id)
        ->get();

        // Preparar os dados para a resposta
        $dados = $produtos->map(function ($produto) use ($request) {
            $dataInicio = $request->data_inicio ? Carbon::parse($request->data_inicio) : Carbon::now()->startOfDay();
            $dataFinal = $request->data_final ? Carbon::parse($request->data_final) : Carbon::now()->endOfDay();
        
            // Calcular a quantidade vendida no período especificado
            // $quantidadeVendida = $produto->registros
            //     ->whereBetween('created_at', [$dataInicio, $dataFinal])  
            //     ->where('registro', 'Saída de Stock')
            //     ->sum('quantidade');
                
            // totdal vendido
            $quantidadeVendida = $produto->vendas
                ->whereBetween('created_at', [$dataInicio, $dataFinal])
                // ->where('registro', 'Saída de Stock')
                ->sum('quantidade');
                
            $totalVendida = $produto->vendas
                ->whereBetween('created_at', [$dataInicio, $dataFinal])
                // ->where('registro', 'Saída de Stock')
                ->sum('valor_pagar');
                
           $totalCustoGanho = $produto->vendas
            ->whereBetween('created_at', [$dataInicio, $dataFinal])
            // ->where('registro', 'Saída de Stock')
            ->sum('custo_ganho');
            
            // Calcular a quantidade em estoque até a data final especificada
            $quantidadeEmEstoque = $produto->stocks
                ->where('created_at', '<=', $dataFinal)
                ->sum('stock');
        
            // Calcular a quantidade restante
            $quantidadeRestante = $quantidadeEmEstoque - $quantidadeVendida;
        
            // Calcular a quantidade inicial
            $quantidadeInicial = $quantidadeEmEstoque + $quantidadeVendida;
        
            return (object) [
                'id' => $produto->id,
                'produto' => $produto->nome,
                'preco' => $produto->preco_venda,
                'imposto' => $produto->taxa,
                'desconto' => 0,
                'total_liquido_vendido' => $totalVendida,
                'total_liquido_custo' => $totalCustoGanho,
                'total_liquido_restante' => $produto->preco_venda * $quantidadeInicial,
                'total_liquido_geral' => $produto->preco_venda * $quantidadeEmEstoque,
                'quantidade_inicial' => $quantidadeInicial,
                'quantidade_vendida' => $quantidadeVendida,
                'quantidade_estoque' => $quantidadeEmEstoque,
                'quantidade_restante' => $quantidadeRestante,
            ];
        });
        
        $empresa = Entidade::with(["caixas", "users", "lojas"])->findOrFail($entidade->empresa->id);
        
        $head = [
            "titulo" => "Vendas por Artigos",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "vendas" => $dados,
            "entidade" => $entidade,
            "requests" => $request->all('data_inicio', 'data_final','loja_id', 'user_id'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.vendas.dashboard-por-artigos-anterior', $head);
    }    
    
    // Método auxiliar para calcular o total do carrinho
    private function calcularTotal($carrinho)
    {
        return array_reduce($carrinho, function($carry, $item) {
            return $carry + $item['valor_pagar'];
        }, 0);
    }

    public function pronto_vendas(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
	 
        $pins = Pin::where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->get();

        if(empty($pins)){
            return redirect()->route('pins.create');
        }
        // recuperar todos os caixas aberto
        $caixas = Caixa::where('active', false)->where('status', 'fechado')->where('entidade_id', '=', $entidade->empresa->id)->get();

        if(empty($caixas)){
            return redirect()->route('caixa.caixas');
        }
        
        // Exibe a página do carrinho
        $carrinho = Session::get('carrinho', []);
        $total = $this->calcularTotal($carrinho);

        $total_pagar = NULL;

        $caixaActivo = Caixa::where([
            ['active', true],
            ['user_id', '=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->first();
       
        if($caixaActivo){
            $total_pagar = Itens_venda::where([
                ['code', '=', NULL],
                ['status', '=', 'processo'],
                ['status_uso', '=', 'CAIXA'],
                ['caixa_id', '=', $caixaActivo->id],
                ['user_id','=', Auth::user()->id],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->sum('valor_pagar');   
        }
        
        $checkCaixa = Caixa::where([
            ['active', true],
            ['status', '=', 'aberto'],
            ['user_id', '=', Auth::user()->id],
        ])
        ->where('entidade_id', $entidade->empresa->id)
        ->first();
        
        $lockStartTime = Session::get('lock_start_time');
        $unlockTime = Carbon::parse($lockStartTime)->addHours(24);
        $remainingTime = $unlockTime->diffInSeconds(Carbon::now());

        $head = [
            "titulo" => "Pronto Vendas",
            "descricao" => env('APP_NAME'),
            "categorias" => Categoria::with('produtos.marca', 'produtos.variacao')->where([
                ['entidade_id', '=', $entidade->empresa->id]
            ])
            ->whereHas('produtos', function($query){
                $query->whereIn('tipo', ['P', 'S']);                
            })
            ->get(),
            "produtos" => Produto::with(['marca', 'variacao', 'estoque'])
                ->whereIn('tipo', ['P', 'S'])
                ->where('entidade_id', $entidade->empresa->id)
                ->get(),
            "loja" => Entidade::findOrFail($entidade->empresa->id),
            "clientes" => Cliente::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "forma_pagmento" => TipoPagamento::get(),
            "total_pagar"=> $total_pagar,
            "caixas"=> $caixas,
            "checkCaixa"=> $checkCaixa,
            
            "carrinho" => $carrinho,
            "total" => $total,
            
            "remainingTime" => $remainingTime,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
        // return view('dashboard.pronto-venda.home', $head);
        return view('dashboard.vendas.index', $head);
    }

    public function pronto_vendas_mesas(Request $request)
    {
     
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["caixas", "users", "variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);
     
        $pins = Pin::where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->get();
        
        $mesas = Mesa::where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->get();

        $head = [
            "titulo" => "Pronto Vendas Mesas",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "mesas" => $mesas,
            "clientes" => Cliente::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "forma_pagmento" => TipoPagamento::get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
        // return view('dashboard.pronto-venda.home', $head);
        return view('dashboard.vendas.venda-pedido-mesas', $head);
    }
    
    public function pronto_vendas_quatros(Request $request)
    {
     
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["caixas", "users", "variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);
     
        $pins = Pin::where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->get();
        
        $quartos = Quarto::where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->get();

        $head = [
            "titulo" => "Pronto Vendas Quartos",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "quartos" => $quartos,
            "clientes" => Cliente::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "forma_pagmento" => TipoPagamento::get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
        // return view('dashboard.pronto-venda.home', $head);
        return view('dashboard.vendas.venda-pedido-quartos', $head);
    }
    
    public function pronto_vendas_mesas_pedidos(Request $request, $id)
    {

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["caixas", "users", "variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);
        
        $mesa = Mesa::findOrFail(Crypt::decrypt($id));
        
        if($mesa->solicitar_ocupacao == "LIVRE"){
            $mesa->solicitar_ocupacao = "OCUPADA";
            $mesa->update();
        }
        
        $pins = Pin::where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->get();

        if(empty($pins)){
            return redirect()->route('pins.create');
        }
        
        // recuperar todos os caixas aberto
        $caixas = Caixa::where('active', false)->where('status', 'fechado')->where('entidade_id', '=', $entidade->empresa->id)->get();

        if(empty($caixas)){
            return redirect()->route('caixa.caixas');
        }
        
        $data = date("Y-m-d");
        
        $movimento_caixa = Venda::where('user_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->whereDate('created_at', '=', Carbon::parse($data))
            ->select(DB::raw('SUM(valor_total) as total_vendido'))
            ->first();

        $movimentos = NULL;
        $total_pagar = NULL;
        $total_unidades = NULL;
        $total_produtos = NULL;

        $checkCaixa = Caixa::where([
            ['active', true],
            ['status', '=', 'aberto'],
            ['user_id', '=', Auth::user()->id],
        ])
        ->where('entidade_id', $entidade->empresa->id)
        ->first();        
        
        $caixaActivo = Caixa::where([
            ['active', true],
            ['user_id', '=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->first();
        
        if($caixaActivo){
            $movimentos = Itens_venda::where([
                ['code', '=', NULL],
                ['status', '=', 'processo'],
                ['status_uso', '=', 'MESA'],
                ['mesa_id', '=', $mesa->id],
                // ['movimento_id', '=', $movimentoActivo->id],
                ['user_id','=', Auth::user()->id],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->with('produto')->get();

            $total_pagar = Itens_venda::where([
                ['code', '=', NULL],
                ['status', '=', 'processo'],
                ['status_uso', '=', 'MESA'],
                // ['movimento_id', '=', $movimentoActivo->id],
                ['mesa_id', '=', $mesa->id],
                ['user_id','=', Auth::user()->id],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->sum('valor_pagar');

            $total_produtos = Itens_venda::where([
                ['code', '=', NULL],
                ['status', '=', 'processo'],
                ['status_uso', '=', 'MESA'],
                // ['movimento_id', '=', $movimentoActivo->id],
                ['mesa_id', '=', $mesa->id],
                ['entidade_id', '=', $entidade->empresa->id], 
                ['user_id','=', Auth::user()->id],
            ])->count();

            $total_unidades = Itens_venda::where([
                ['code', '=', NULL],
                ['status', '=', 'processo'],
                ['status_uso', '=', 'MESA'],
                // ['movimento_id', '=', $movimentoActivo->id],
                ['mesa_id', '=', $mesa->id],
                ['entidade_id', '=', $entidade->empresa->id], 
                ['user_id','=', Auth::user()->id],
            ])->sum('quantidade');
            
        }else{
            return redirect()->route('pronto-venda-mesas')->with("danger", "Nenhum caixa Aberto no momento!");
        }

        $head = [
            "titulo" => "Pronto Vendas Mesas",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "mesa" => $mesa,
            "categorias" => Categoria::with('produtos.marca', 'produtos.variacao')->where([
                ['entidade_id', '=', $entidade->empresa->id]
            ])->get(),
            "produtos" => Produto::with(['marca', 'variacao', 'estoque'])
                ->where('entidade_id', $entidade->empresa->id)
                ->get(),
            "loja" => Entidade::findOrFail($entidade->empresa->id),
            "clientes" => Cliente::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "forma_pagmento" => TipoPagamento::get(),
            "movimentos" => $movimentos,
            "total_pagar"=> $total_pagar,
            "total_unidades"=> $total_unidades,
            "total_produtos"=> $total_produtos,
            "checkCaixa"=> $checkCaixa,
            "caixas"=> $caixas,
            "movimento_caixa"=> $movimento_caixa,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
     
     
        return view('dashboard.vendas.index-mesas', $head);
    }    
    
    public function pronto_vendas_mesas_quartos(Request $request, $id)
    {

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["caixas", "users", "variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);
        
        $quarto = Quarto::findOrFail(Crypt::decrypt($id));
        
        $reserva = Reserva::where('code', $quarto->code)
            ->with([
                'quarto',
                'exercicio',
                'periodo',
                'cliente.estado_civil',
                'cliente.seguradora',
                'cliente.provincia',
                'cliente.municipio',
                'cliente.distrito'
            ])->first();
            
        if(!$reserva){
            return redirect()->route('pronto-venda-quartos')->with("danger", "Reserva não encontrada!");
        }
        
        if($quarto->solicitar_ocupacao == "LIVRE"){
            $quarto->solicitar_ocupacao = "OCUPADA";
            $quarto->update();
        }
        
        $pins = Pin::where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->get();

        if(empty($pins)){
            return redirect()->route('pins.create');
        }
        
        // recuperar todos os caixas fechados
        $caixas = Caixa::where('active', false)->where('status', 'fechado')->where('entidade_id', '=', $entidade->empresa->id)->get();

        if(empty($caixas)){
            return redirect()->route('caixa.caixas');
        }
        
        $data = date("Y-m-d");
        
        $movimento_caixa = Venda::where('user_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->whereDate('created_at', '=', Carbon::parse($data))
            ->select(DB::raw('SUM(valor_total) as total_vendido'))
            ->first();

        $movimentos = NULL;
        $total_pagar = NULL;
        $total_unidades = NULL;
        $total_produtos = NULL;

        $checkCaixa = Caixa::where([
            ['active', true],
            ['status', '=', 'aberto'],
            ['user_id', '=', Auth::user()->id],
        ])
        ->where('entidade_id', $entidade->empresa->id)
        ->first();        
        
        $caixaActivo = Caixa::where([
            ['active', true],
            ['user_id', '=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->first();
        
        if($caixaActivo){
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
                // ['movimento_id', '=', $movimentoActivo->id],
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
            
        }else{
            return redirect()->route('pronto-venda-quartos')->with("danger", "Nenhum caixa Aberto no momento!");
        }

        $head = [
            "titulo" => "Pronto Vendas Quartos",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "quarto" => $quarto,
            "categorias" => Categoria::with('produtos.marca', 'produtos.variacao')->where([
                ['entidade_id', '=', $entidade->empresa->id]
            ])->get(),
            "produtos" => Produto::with(['marca', 'variacao', 'estoque'])
                ->where('entidade_id', $entidade->empresa->id)
                ->get(),
            "loja" => Entidade::findOrFail($entidade->empresa->id),
            "clientes" => Cliente::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "forma_pagmento" => TipoPagamento::get(),
            "movimentos" => $movimentos,
            "total_pagar"=> $total_pagar,
            "total_unidades"=> $total_unidades,
            "total_produtos"=> $total_produtos,
            "checkCaixa"=> $checkCaixa,
            "caixas"=> $caixas,
            "reserva"=> $reserva,
            "movimento_caixa"=> $movimento_caixa,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
     
     
        return view('dashboard.vendas.index-quartos', $head);
    }    

    public function buscar_produto(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        return response()->json([
            "loja" => Entidade::findOrFail($entidade->empresa->id),
            "produtos" => Produto::with(['marca', 'variacao', 'estoque'])
            ->where('entidade_id', $entidade->empresa->id)
            // ->where('tipo', 'P')
            ->when($request->produto, function($query, $value){
                $query->where('nome', 'like' ,"%{$value}%");
            })
            ->with('marca', 'variacao')
            ->get(),
        ], 200);
    }

    public function buscar_produto_codigo_barra(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
				
        $produt = Produto::where("codigo_barra", $request->produto_codigo_barra)->first();
		
		if($produt){
			return redirect()->route('adicionar-produto', $produt->id);
		}

		return redirect()->back();
    }

    public function actualizar_vendas($id, $back)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $movimento = Itens_venda::with('produto')->findOrFail($id);

        $produto = Produto::findOrFail($movimento->produto_id);
        $grupo_precos = ProdutoGrupoPreco::with(['produto'])->where('produto_id', $produto->id)->get();
        
       
        if($back){
            $mesa = Mesa::find($back);
        }
        
        $head = [
            "titulo" => env('APP_NAME') ." Pronto Vendas",
            "descricao" => env('APP_NAME'),
            "categorias" => Categoria::with('produtos.marca', 'produtos.variacao')->where([
                ['entidade_id', '=', $entidade->empresa->id]
            ])->get(),
            "dados" => Entidade::findOrFail($entidade->empresa->id),
            "movimento" => $movimento,
            
            "produto" => $produto,
            "grupo_precos" => $grupo_precos,
            "mesa" => $mesa,
            
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
        

        return view('dashboard.vendas.actualizar-quantidade', $head);
    }

    public function actualizar_vendas_update(Request $request, $id, $back = null)
    {
        
        try {
            // Inicia a transação
            DB::beginTransaction();
        
            $movimento = Itens_venda::with('produto')->findOrFail($id);
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
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
                ->where('produto_id', $movimento->produto_id)
                ->where('entidade_id', $entidade->empresa->id)
                ->sum('stock');
            
            $verificar_quantidade = (int) $verificar_quantidade;
            
            if($request->quantidade > $verificar_quantidade){
                Alert::warning('Atenção', 'A Loja activa não têm esta quantidade de produto em stock para poder comercializar!');
                return redirect()->back()->with('warning', 'A Loja activa não têm esta quantidade de produto em stock para poder comercializar!');
            }
    
            if($movimento){
    
                $produto = Produto::with('estoque')->findOrFail($movimento->produto_id);
    
                if($request->quantidade > $produto->estoque->stock){
                    Alert::warning('Atenção', 'A quantidade Adiciona nesta compra e maior do que a existente no Stock!');
                    return redirect()->back();
                }
                
                $desconto = ($produto->preco_venda * $request->quantidade) * ($request->desconto_aplicado / 100);
    
                $produto->estoque->stock = ($produto->estoque->stock + $movimento->quantidade) - $request->quantidade;
    
                $valorBase = $produto->preco_venda * $request->quantidade; 
                // calculo do iva
                $valorIva = ($produto->taxa / 100) * $valorBase;
    
                $movimento->quantidade = $request->quantidade;
                $movimento->valor_pagar = ($valorBase + $valorIva) - $desconto;
                $movimento->preco_unitario = $produto->preco_venda;
                
                
                $movimento->custo_ganho = ($produto->preco_venda - $produto->preco_custo) * $update->quantidade;

    
                $movimento->valor_base = $valorBase;
                $movimento->valor_iva = $valorIva;
    
                $movimento->desconto_aplicado = $request->desconto_aplicado;
                $movimento->desconto_aplicado_valor = $desconto;
    
                $movimento->iva = $request->iva;
                $movimento->texto_opcional = $request->texto_opcional;
                $movimento->numero_serie = $request->numero_serie;
                if($movimento->update()){
    
                    $produto->estoque->update();
    
                }else{
                    Alert::error('Erro', 'Ao tentar actualizar os dodos deste produto nesta venda');
                    if($back == "factura"){
                        return redirect()->route('facturas.create');
                    }else if(Mesa::find($back)){
                        $mesa = Mesa::find($back);
                        return redirect()->route('pronto-venda-mesas-pedidos', Crypt::encrypt($mesa->id));
                    }else{
                        return redirect()->route('pronto-venda');
                    }
                }
                
              
                if($request->quantidade > $request->quantidade_anterior){
                
                    Registro::create([
                       "registro" => "Saída de Stock",
                       "data_registro" => date('Y-m-d'),
                       "quantidade" => (int) $request->quantidade - (int) $request->quantidade_anterior,
                       "produto_id" => $produto->id,
                       "observacao" => "Saída de produto {$produto->nome} para venda",
                       "loja_id" => $loja->id,
                       "lote_id" => NULL,
                       "user_id" => Auth::user()->id,
                       'entidade_id' => $entidade->empresa->id,
                   ]);
                   
                }else if($request->quantidade < $request->quantidade_anterior){
                
                    $quantidade = (int) $request->quantidade_anterior - (int) $request->quantidade;
                
                    Registro::create([
                       "registro" => "Saída de Stock",
                       "data_registro" => date('Y-m-d'),
                       "quantidade" => (int) $request->quantidade_anterior - (int) $request->quantidade,
                       "produto_id" => $produto->id,
                       "observacao" => "Saída de produto {$produto->nome} para venda",
                       "loja_id" => $loja->id,
                       "lote_id" => NULL,
                       "user_id" => Auth::user()->id,
                       'entidade_id' => $entidade->empresa->id,
                   ]);
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

        
        if($back == "factura"){
            return redirect()->route('facturas.create');
        }else if(Mesa::find($back)){
            $mesa = Mesa::find($back);
            return redirect()->route('pronto-venda-mesas-pedidos', Crypt::encrypt($mesa->id));
        }
        else{
            return redirect()->route('pronto-venda');
        }
        
    }

    public function printAnteVenda()
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $movimento = Itens_venda::where([
            ['user_id','=', Auth::user()->id],
            ['code', NULL],
            ['status', '=', 'processo'],
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->with('produto')->get();

        $head = [
            "titulo" => env('APP_NAME') ." Pronto Vendas",
            "descricao" => env('APP_NAME'),
            "categorias" => Categoria::with('produtos.marca', 'produtos.variacao')->get(),
            "dados" => Entidade::with('empresa', 'configuracao_empressao')->findOrFail($entidade->empresa->id),
            "movimento" => $movimento,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.pronto-venda.prints.print-venda-antes', $head);
        
    }

    // adicionar produto ao carrinho
    public function adicionar_produto($id, $mesa_caixa = "")
    {
    
        try {
            // Inicia a transação
            DB::beginTransaction();
            // Comita a transação se tudo estiver correto
            
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            $produto = Produto::with('marca','variacao','categoria', 'estoque')->findOrFail($id);        
            
            // $verifica se tem uma loja activa onde esta sendo retidados os produtos
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
    
            $caixaActivo = Caixa::where([
                ['active', true],
                ['status', '=', 'aberto'],
                ['user_id', '=', Auth::user()->id],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->first();

            if(!empty($caixaActivo)){
               
                        
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
        
                     
                if($mesa_caixa == ""){
                    $status_uso = "CAIXA";
                    $mesa_id = NULL;
                    $caixa_id = $caixaActivo->id;
                }else{
                    $mesa_id = $mesa_caixa;
                    $status_uso = "MESA";
                    $caixa_id = NULL;
                }
    
                if( $status_uso == "CAIXA"){
                
                    $verificarProdutoAdicionado = Itens_venda::where([
                        ['status', '=', 'processo'],
                        ['produto_id', '=', $produto->id],
                        ['caixa_id', '=', $caixa_id],
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
                        ['entidade_id', '=', $entidade->empresa->id], 
                        ['user_id', '=', Auth::user()->id], 
                    ])->first();
                    
                }

                // calcudo do total de incidencia
                //________________ valor total _____________
                $valorBase = $produto->preco_venda * 1; 
                // calculo do iva
                $valorIva = ($produto->taxa / 100) * $valorBase;

                if($verificarProdutoAdicionado){
                    $update = Itens_venda::findOrFail($verificarProdutoAdicionado->id);
                   
                    $desconto = ($produto->preco_venda * ($update->quantidade + 1)) * ($update->desconto_aplicado / 100);

                    $valorBase = $produto->preco_venda * ($update->quantidade + 1); 
                    // calculo do iva
                    $valorIva = ($produto->taxa / 100) * $valorBase;

                    $update->quantidade = $update->quantidade + 1;
                    $update->valor_pagar = ($valorBase + $valorIva) - $desconto;
                    
                    $update->custo_ganho = ($produto->preco_venda - $produto->preco_custo) * $update->quantidade;

                    $update->desconto_aplicado = $update->desconto_aplicado;
                    $update->desconto_aplicado_valor = $desconto;

                    $update->valor_base = $valorBase;
                    $update->valor_iva = $valorIva;

                    $update->update();

                    $produto->estoque->stock = $produto->estoque->stock - 1; 
                    $produto->estoque->update(); 

                    // return redirect()->back();
                    // return redirect()->route('pronto-venda');
                }else{
                    $create = Itens_venda::create([
                        'produto_id' => $produto->id,
                        'quantidade' => 1,
                        'user_id' => Auth::user()->id,
                        'valor_pagar' => $valorBase + $valorIva,
                        'preco_unitario' => $produto->preco_venda,
                        'custo_ganho' => ($produto->preco_venda - $produto->preco_custo) * 1,
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
                
                    if($create->save()){

                        $produto->estoque->stock = $produto->estoque->stock - 1; 
                        $produto->estoque->update(); 

                        // return redirect()->route('pronto-venda');
                        // return redirect()->back();
                    }else{
                        Alert::error('Erro', 'O correu um erro ão tentar adicionar este produto');
                        // return redirect()->route('pronto-venda');
                        return redirect()->back();
                    }
                }
    
            }else{
                Alert::error('Erro', 'Verifica se tens um caixa aberto, por favor!');
                return redirect()->back();
                // return redirect()->route('pronto-venda');
            }
            
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
        
        return redirect()->back();
    }

    // adicionar produto ao carrinho
    public function remover_produto($id, $back = null)
    {
        try {
            // Inicia a transação
            DB::beginTransaction();
                    
            $movimento = Itens_venda::findOrFail($id);
    
            $produto = Produto::with('estoque')->findOrFail($movimento->produto_id);
            $produto->estoque->stock = $produto->estoque->stock + $movimento->quantidade;
            $produto->estoque->update();
            
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            $loja = Loja::where([
               ['status', '=', 'activo'],
               ['entidade_id', '=', $entidade->empresa->id], 
           ])->first();
           
            Registro::create([
               "registro" => "Entrada de Stock",
               "data_registro" => date('Y-m-d'),
               "quantidade" => $movimento->quantidade,
               "produto_id" => $produto->id,
               "observacao" => "Retorno do produto {$produto->nome} no Stock",
               "loja_id" => $loja->id,
               "lote_id" => NULL,
               "user_id" => Auth::user()->id,
               'entidade_id' => $entidade->empresa->id,
           ]);
            
            $movimento->delete();
            
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


        if($back == "factura"){
            return redirect()->route('facturas.create');
        }else if(Mesa::find($back)){
            $mesa = Mesa::find($back);
            return redirect()->route('pronto-venda-mesas-pedidos', Crypt::encrypt($mesa->id));
        }else{
            return redirect()->route('pronto-venda');
        }
           
    
    } 

    public function finalizar_vendas()
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $movimento = Itens_venda::where([
            ['user_id','=', Auth::user()->id],
            ['code', NULL],
            ['status_uso', '=', 'CAIXA'],
            ['status', '=', 'processo'],
            ['entidade_id', '=', $entidade->empresa->id], 
        ])
        ->with('produto')
        ->get(); 

        if(count($movimento) == 0) {
            Alert::error('Erro', 'O correu um erro, não existe nenhum produto selecionado!');
            return redirect()->route('pronto-venda');
        }
        
        $total_pagar = Itens_venda::where([
            ['code', '=', NULL],
            ['status', '=', 'processo'],
            ['status_uso', '=', 'CAIXA'],
            ['user_id','=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->sum('valor_pagar');
        
        $head = [
            "titulo" => env('APP_NAME') ." Pronto Vendas",
            "descricao" => env('APP_NAME'),
            "categorias" => Categoria::with('produtos.marca', 'produtos.variacao')->get(),
            "loja" => Entidade::findOrFail($entidade->empresa->id),
            "clientes" => Cliente::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "forma_pagmento" => TipoPagamento::get(),
            "movimentos" => $movimento,
            "total_pagar" => $total_pagar,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.vendas.finalizar', $head);
    }
    
    public function finalizar_vendas_pedido($id)
    {
        $mesa = Mesa::findOrFail(Crypt::decrypt($id));
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $movimento = Itens_venda::where([
            ['user_id','=', Auth::user()->id],
            ['code', NULL],
            ['status', '=', 'processo'],
            ['status_uso', '=', 'MESA'],
            ['mesa_id', '=', $mesa->id],
            ['entidade_id', '=', $entidade->empresa->id], 
        ])
        ->with('produto')
        ->get(); 

        if(count($movimento) == 0) {
            Alert::error('Erro', 'O correu um erro, não existe nenhum produto selecionado!');
            return redirect()->route('pronto-venda');
        }
        
        $total_pagar = Itens_venda::where([
            ['code', '=', NULL],                
            ['status', '=', 'processo'],
            ['status_uso', '=', 'MESA'],
            ['mesa_id', '=', $mesa->id],
            ['user_id','=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->sum('valor_pagar');
        
        $head = [
            "titulo" => env('APP_NAME') ." Finalizar venda a pedido",
            "descricao" => env('APP_NAME'),
            "categorias" => Categoria::with('produtos.marca', 'produtos.variacao')->get(),
            "loja" => Entidade::findOrFail($entidade->empresa->id),
            "clientes" => Cliente::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "forma_pagmento" => TipoPagamento::get(),
            "movimentos" => $movimento,
            "total_pagar" => $total_pagar,
            "mesa" => $mesa,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.vendas.finalizar-pedido', $head);
    }
    
    public function finalizar_vendas_create(Request $request)
    {
 
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            $cliente = Cliente::findOrFail($request->clienteId);
            $subconta_cliente = Subconta::where('code', $cliente->code)->first();
            
            $formaPagamento = TipoPagamento::where('tipo', $request->pagamento)->first();
                    
            $code = uniqid(time());

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
                Alert::error('Erro', 'O correu um erro, não existe nenhum produto selecionado!');
                return redirect()->route('pronto-venda');
            }
                
            $caixaActivo = Caixa::where([
                ['active', true],
                ['user_id', '=', Auth::user()->id],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->first();
            
            if(!$caixaActivo){
                Alert::error('Erro', 'Por favor, não podes realizar nenhuma venda sem antes abrir o caixa!');
                return redirect()->back();    
            }
            
            $contador_facturas = Venda::where('factura', $request->documento)->where('ano_factura', date("Y"))->where('entidade_id', $entidade->empresa->id)->count();
            $ano_ = date("Y");
            $numeroFacturaDoc = $contador_facturas + 1;
            $codigo_designacao_factura = ENV('DESIGNACAO_FACTURA');
            $designacao_factura = "{$request->documento} {$codigo_designacao_factura}{$ano_}/{$numeroFacturaDoc}";
             
            $request->total_pagar = (int) $request->total_pagar;
            
            if($formaPagamento->tipo == "NU"){
            
                $subconta_caixa = Subconta::where('code', $caixaActivo->code)->first();
                
                if($subconta_caixa){
                    OperacaoFinanceiro::create([
                        'nome' => $designacao_factura,
                        'status' => "pago",
                        'motante' => $request->total_pagar,
                        'subconta_id' => $subconta_caixa->id,
                        'cliente_id' => $cliente->id,
                        'model_id' => 3,
                        'type' => "R",
                        'parcelado' => "N",
                        'status_pagamento' => "pago",
                        'data_recebimento' => date("Y-m-d"),
                        'forma_recebimento_id' => $formaPagamento->id,
                        'code' => $code,
                        'descricao' => "VENDA REALIZADA COM SUCESSO",
                        'movimento' => "E",
                        'date_at' => date("Y-m-d"),
                        'user_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                }else{
                    ##
                }

                $valor_cash = (int) $request->total_pagar;
                $valor_multicaixa = 0;
                $request->valor_entregue = $request->valor_entregue;
                $banco_id = NULL;
                
            }else if($formaPagamento->tipo == "MB"){
            
                $bancoActivo = ContaBancaria::where([
                    ['active', true],
                    ['status', '=', 'aberto'],
                    ['user_id', '=', Auth::user()->id],
                    ['entidade_id', '=', $entidade->empresa->id], 
                ])->first();
                
                
                if( $bancoActivo ) {
                
                    $subconta_banco = Subconta::where('code', $bancoActivo->code)->first();
                    
                    if($subconta_banco){
                        OperacaoFinanceiro::create([
                            'nome' => $designacao_factura,
                            'status' => "pago",
                            'motante' => $request->total_pagar,
                            'subconta_id' => $subconta_banco->id,
                            'cliente_id' => $cliente->id,
                            'model_id' => 3,
                            'type' => "R",
                            'parcelado' => "N",
                            'status_pagamento' => "pago",
                            'data_recebimento' => date("Y-m-d"),
                            'forma_recebimento_id' => $formaPagamento->id,
                            'code' => $code,
                            'descricao' => "VENDA REALIZADA COM SUCESSO",
                            'movimento' => "E",
                            'date_at' => date("Y-m-d"),
                            'user_id' => Auth::user()->id,
                            'entidade_id' => $entidade->empresa->id,
                        ]);
                    }else{
                        ##
                    }
                
                }
                
                $valor_cash = 0;
                $valor_multicaixa =  (int) $request->total_pagar;
                $request->valor_entregue = $request->valor_entregue_multicaixa;
                $banco_id = $caixaActivo->id;
                
            }else if($formaPagamento->tipo == "OU"){
            
                $bancoActivo = ContaBancaria::where([
                    ['active', true],
                    ['status', '=', 'aberto'],
                    ['user_id', '=', Auth::user()->id],
                    ['entidade_id', '=', $entidade->empresa->id], 
                ])->first();
                
                if( $bancoActivo ) {
                    $subconta_caixa = Subconta::where('code', $caixaActivo->code)->first();
                    
                    if($subconta_caixa){
                        OperacaoFinanceiro::create([
                            'nome' => $designacao_factura,
                            'status' => "pago",
                            'motante' => $request->valor_entregue_input,
                            'subconta_id' => $subconta_caixa->id,
                            'cliente_id' => $cliente->id,
                            'model_id' => 3,
                            'type' => "R",
                            'parcelado' => "N",
                            'status_pagamento' => "pago",
                            'data_recebimento' => date("Y-m-d"),
                            'forma_recebimento_id' => $formaPagamento->id,
                            'code' => $code,
                            'descricao' => "VENDA REALIZADA COM SUCESSO",
                            'movimento' => "E",
                            'date_at' => date("Y-m-d"),
                            'user_id' => Auth::user()->id,
                            'entidade_id' => $entidade->empresa->id,
                        ]);
                    }else{
                        ##
                    }
                
                    $subconta_banco = Subconta::where('code', $bancoActivo->code)->first();
                    
                    if($subconta_banco){
                        OperacaoFinanceiro::create([
                            'nome' => $designacao_factura,
                            'status' => "pago",
                            'motante' => $request->valor_entregue_multicaixa_input,
                            'subconta_id' => $subconta_banco->id,
                            'cliente_id' => $cliente->id,
                            'model_id' => 3,
                            'type' => "R",
                            'parcelado' => "N",
                            'status_pagamento' => "pago",
                            'data_recebimento' => date("Y-m-d"),
                            'forma_recebimento_id' => $formaPagamento->id,
                            'code' => $code,
                            'descricao' => "VENDA REALIZADA COM SUCESSO",
                            'movimento' => "E",
                            'date_at' => date("Y-m-d"),
                            'user_id' => Auth::user()->id,
                            'entidade_id' => $entidade->empresa->id,
                        ]);
                    }else{
                        ##
                    }
                }
                
                $valor_cash =  (int)  $request->valor_entregue_input;
                $valor_multicaixa = (int)  $request->valor_entregue_multicaixa_input;
                $request->valor_entregue = $request->valor_entregue_multicaixa_input + $request->valor_entregue_input;
                $banco_id = $caixaActivo->id;
            }
            
            ## DEBITO FINAL VERIFICA EM - CARRINHOCONTROLLER ou CAIXACONTROLLER
            if($formaPagamento->tipo == "NU"){
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
                
            }else if($formaPagamento->tipo == "MB"){
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
                                
            }else if($formaPagamento->tipo == "OU"){
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
                        'debito' => $request->valor_entregue_input ??0,
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
                        'debito' => $request->valor_entregue_multicaixa_input??0,
                        'observacao' => $request->observacao,
                        'code' => $code,
                        'data_at' => date("Y-m-d"),
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => 1,
                        'periodo_id' => 12,
                    ]);
                    
                }
            }
            
            
            if($request->valor_entregue < $request->total_pagar){
                Alert::warning('Erro', 'O Valor Entregue para esta Conta é insuficiente!');
                return redirect()->back();
            }          

   
            $contarFactura = Venda::where([
                ['factura', '=', $request->documento],
                ['ano_factura', '=', date("Y")],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->count();
    
            $ultimoRecibo = Venda::where([
                ['factura', '=', $request->documento],
                ['ano_factura', '=', date("Y")],
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
    
            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "{$request->documento} {$codigo_designacao_factura}{$ano}/{$numeroFactura}" . ';' . number_format($request->total_pagar, 2, ".", "") . ';' . $hashAnterior;
            
            // HASH
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
    
            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)
    
            // Lendo a public key
            $rsa->loadKey($publickey);
    
            $valor_extenso = $this->valor_por_extenso($request->total_pagar);
    
            if($request->venda_realizado == "CAIXA"){
                $mesa = Caixa::find($caixaActivo->id);
                $caixa_id = $mesa->id;
                $mesa_id = NULL;
                $quarto_id = NULL;
            }
            
            if($request->venda_realizado == "MESA"){
                $mesa = Mesa::find($request->mesa_id);
                $caixa_id = NULL;
                $mesa_id = $mesa->id;
                $quarto_id = NULL;
            }
            
            if($request->venda_realizado == "QUARTO"){
                $mesa = Quarto::find($request->quarto_id);
                $caixa_id = NULL;
                $mesa_id = NULL;
                $quarto_id = $mesa->id;
            }
        
            $create = Venda::create([
                'codigo_factura' =>  $numeroFactura,
                'status' => true,
                'cliente_id' => $cliente->id,
                'banco_id' => $banco_id,
                'mesa_id' => $mesa_id,
                'quarto_id' => $quarto_id,
                'mesa_caixa' => $request->venda_realizado,
                'status_factura' => 'pago',
                'loja_id' => $caixaActivo->loja_id,
                'status_venda' => "realizado",
                'user_id' => Auth::user()->id,
                'caixa_id' => $caixaActivo->id,
                'valor_entregue' => $request->valor_entregue,
                'valor_total' => $request->total_pagar,
                'valor_troco' => $request->valor_entregue - $request->total_pagar,
                'code' => $code,
                'ano_factura' => $ano,
				'nome_cliente' => $request->nomeCliente ?? $cliente->nome,
				'documento_nif' => $request->nomeNIF ?? $cliente->nif,
                'desconto' => 0,
                'desconto_percentagem' => 0,
                'entidade_id' => $entidade->empresa->id, 
                'prazo' => 0,
                'data_emissao' => date("y-m-d"),
                'data_vencimento' => date("y-m-d"),
                'data_disponivel' => date("y-m-d"),
                'pagamento' => $formaPagamento->tipo,
                'factura' => $request->documento,
                'factura_next' => "{$request->documento} {$codigo_designacao_factura}{$ano}/{$numeroFactura}",
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
                'nif_cliente' => $request->nomeNIF ?? $cliente->nif,
            ]);
            
            if($create->save()){
                
                if($request->venda_realizado == "CAIXA"){
                    $movimentos = Itens_venda::where([
                        ['user_id','=', Auth::user()->id],
                        ['status', '=', 'processo'],
                        ['caixa_id','=', $mesa->id],
                        ['status_uso','=', "CAIXA"],
                        ['entidade_id', '=', $entidade->empresa->id], 
                        ['code', NULL],
                    ])->get(); 
                }
                if($request->venda_realizado == "MESA"){
                    $movimentos = Itens_venda::where([
                        ['user_id','=', Auth::user()->id],
                        ['mesa_id','=', $mesa->id],
                        ['status_uso','=', "MESA"],
                        ['status', '=', 'processo'],
                        ['entidade_id', '=', $entidade->empresa->id], 
                        ['code', NULL],
                    ])->get(); 
                }
                if($request->venda_realizado == "QUARTO"){
                    $movimentos = Itens_venda::where([
                        ['user_id','=', Auth::user()->id],
                        ['quarto_id','=', $mesa->id],
                        ['status_uso','=', "QUARTO"],
                        ['status', '=', 'processo'],
                        ['entidade_id', '=', $entidade->empresa->id], 
                        ['code', NULL],
                    ])->get(); 
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
            
            if($request->venda_realizado == "MESA"){ 
                $mesa->solicitar_ocupacao = "LIVRE";
                $mesa->update();
            }
            
            if($request->venda_realizado == "QUARTO"){ 
                $mesa->code = NULL;
                $mesa->solicitar_ocupacao = "LIVRE";
                $mesa->update();
            }
    
            $vendas = Venda::with('cliente')->where('code', $create->code)->first();
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            $items = Itens_venda::with('produto')->where('code', $vendas->code)->get();
    
            $factura = Venda::with('cliente')
                ->with(['caixa', 'user'])
                ->where('code', $vendas->code)
                ->first();
            
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
            
            $subconta_venda_mercadoria= Subconta::where('numero', ENV('VENDA_DE_MERCADORIA'))->first();
            $subconta_prestacao_servico = Subconta::where('numero', ENV('PRESTACAO_SERVICO'))->first();
            $subconta_custo_mercadoria = Subconta::where('numero', ENV('CUSTO_MERCADORIA_VENDIDA'))->first();
            
            foreach($movimentos as $car){
                
                dd($car);
            
                $subconta_iva = Subconta::where('numero', ENV('IVA_LIQUIDADO'))->first();
                $produt = Produto::findOrFail($car->produto_id); 
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
                                    'credito' => $car->valor_pagar ??0,
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
                                    'credito' => $car->valor_pagar ??0,
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
                                    'credito' => ($produt->preco_custo ?? 0) * $car->quantidade,
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
                                    'debito' => ($produt->preco_custo ?? 0) * $car->quantidade,
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
                                'debito' => $car->valor_pagar ??0,
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
                                'credito' => $car->valor_pagar ??0,
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
                        
                        ## creditar na conta proveito - 61/62/63/65 - ou seja diminuir o valor sem o iva
                        if($produt->tipo == "P"){
                            $movimeto = Movimento::create([
                                'user_id' => Auth::user()->id,
                                'subconta_id' => $subconta_venda_mercadoria->id,
                                'status' => true,
                                'movimento' => 'S',
                                'credito' => $car->valor_pagar ?? 0,
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
                                'credito' => $car->valor_pagar ?? 0,
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
                                'credito' => ($produt->preco_custo ?? 0) * $car->quantidade,
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
                                'debito' => ($produt->preco_custo ?? 0) * $car->quantidade,
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
                            'debito' => $car->valor_pagar ??0,
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
                            'credito' => $car->valor_pagar ??0,
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

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        $head = [
            'titulo' => "Movimentos do Stock",
            'descricao' => env('APP_NAME'),
            "loja" => $entidade,
            "factura" => $vendas,
            "items_facturas" => $items,
            
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

    public function factura_recibo_pos_venda(Request $request)
    {

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $factura = Venda::with(['cliente', 'caixa', 'user'])->findOrFail($request->factura);
               
        $items = Itens_venda::with('produto')->where('code', $factura->code)->get();
        
        $vendas = Venda::with('cliente')->where('code', $factura->code)->first();
            
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
        
        $head = [
            'titulo' => "FACTURA RECIBO",
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
            "venda_realizado" => $factura->mesa_caixa,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
        
        return view('dashboard.facturas.documentos.factura-recibo', $head);
        
    }


    public function cancelar_vendas()
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $movimento = Itens_venda::where([
            ['user_id','=', Auth::user()->id],
            ['code', NULL],
            ['status', '=', 'processo'],
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->with('produto')->get(); 

        if(count($movimento) == 0) {
            Alert::error('Erro', 'O correu um erro, não existe nenhum produto selecionado!');
            return redirect()->route('pronto-venda');
        }
    
        foreach ($movimento as $item) {
            #TODO        
            // dd($item);
            $item_venda = Itens_venda::findOrFail($item->id);
            $produto = Produto::with('estoque')->findOrFail($item_venda->produto_id);
            $produto->estoque->stock = $produto->estoque->stock + $item_venda->quantidade;
            $produto->estoque->update();
            
            $item_venda->delete();
        }
        
        Alert::success('Sucesso', 'Venda interropida com sucesso!');
        return redirect()->route('pronto-venda');
        
    }

    ################ PROCESSO RETIFICAR FACTURA
    // Retificando facturas
    public function retificar_vendas($id)
    {
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $movimento = Itens_venda::with('produto')->findOrFail($id);

        $head = [
            "titulo" => env('APP_NAME') ." Pronto Vendas",
            "descricao" => env('APP_NAME'),
            "categorias" => Categoria::with('produtos.marca', 'produtos.variacao')->where([
                ['entidade_id', '=', $entidade->empresa->id]
            ])->get(),
            "dados" => Entidade::findOrFail($entidade->empresa->id),
            "movimento" => $movimento,
            "id_back" => Venda::where([
                ['code', "=" ,$movimento->code], 
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->first()->id,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.pronto-venda.retificar-venda', $head);
    }
    //rficia
    public function retificar_vendas_update(Request $request, $id)
    {
        //TODO
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $movimento = Itens_venda::with('produto')->findOrFail($id);
        $id_back = Venda::where([
            ['code', "=" ,$movimento->code],
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->first()->id;

        if($movimento){

            $produto = Produto::with('estoque')->findOrFail($movimento->produto_id);

            if($request->quantidade > $produto->estoque->stock){
                Alert::warning('Atenção', 'A quantidade Adiciona nesta compra e maior do que a existente no Stock!');
                return redirect()->route('facturas.edit', $id_back);
            }
            /**
            */
            
            $produto->estoque->stock = ($produto->estoque->stock + $movimento->quantidade) - $request->quantidade;
            
            $desconto = ($produto->preco * $request->quantidade) * ($request->desconto_aplicado / 100);
            
            $valorBase = $produto->preco * $request->quantidade; 
            // calculo do iva
            $valorIva = ($produto->taxa / 100) * $valorBase;
            
            $movimento->quantidade = $request->quantidade;
            $movimento->valor_pagar = ($valorBase + $valorIva) - $desconto;
            $movimento->preco_unitario = $produto->preco;
            
            $movimento->valor_base = $valorBase;
            $movimento->valor_iva = $valorIva;
            
            
            $movimento->desconto_aplicado = $request->desconto_aplicado;
            $movimento->desconto_aplicado_valor = $desconto;
            $movimento->iva = $request->iva;

            $movimento->texto_opcional = $request->texto_opcional;
            $movimento->numero_serie = $request->numero_serie;
            if($movimento->update()){

                $produto->estoque->update();
                return redirect()->route('facturas.edit', $id_back);

            }else{
                Alert::error('Erro', 'Ao tentar actualizar os dodos deste produto nesta venda');
                return redirect()->route('facturas.edit', $id_back);
            }
        }

        Alert::error('Erro', 'Ao tentar actualizar os dodos deste produto nesta venda');
        return redirect()->route('facturas.edit', $id_back);
        
    }

    // adicionar produto ao carrinho
    public function retificar_venda_remover_produto($id)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $movimento = Itens_venda::findOrFail($id);
        $id_back = Venda::where([
            ['code', '=' ,$movimento->code], 
            ['entidade_id', '=', $entidade->empresa->id],
        ])->first()->id;

        $produto = Produto::with('estoque')->findOrFail($movimento->produto_id);
        $produto->estoque->stock = $produto->estoque->stock + $movimento->quantidade;
        $produto->estoque->update();

        if($movimento->delete()){
            return redirect()->route('facturas.edit', $id_back);
        }else{
            Alert::error('Erro', 'O correu um erro ão tentar remover este produto');
            return redirect()->route('facturas.edit', $id_back);
        }
    } 

    public function retificar_venda_adicionar_produto($id, $codigo)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $produto = Produto::with('marca','variacao','categoria', 'estoque')->findOrFail($id);
        $id_back = Venda::where([
            ['code', '=', $codigo],
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->first()->id;       

        $caixaActivo = Caixa::where([
            ['active', true],
            ['user_id', '=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->first();

        if(!empty($caixaActivo)){

            $verificarProdutoAdicionado = Itens_venda::where([
                ['code', '=', $codigo],
                ['produto_id', '=', $produto->id],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->first();

                       
            $var_iva = "";
            if($produto->imposto == "ISE"){
                $var_iva = 0;
            }else if ($produto->imposto == "RED"){
                $var_iva = 2;
            }else if ($produto->imposto == "INT"){
                $var_iva = 5;
            }else if ($produto->imposto == "OUT"){
                $var_iva = 7;
            }else if ($produto->imposto == "NOR"){
                $var_iva = 14;
            }else{
                $var_iva = 0;
            }
                                                    //________________ valor total _____________
            $valorBase = ($produto->preco_venda) - (($produto->preco_venda) * ($var_iva / 100));
            $valorIva = ($produto->preco_venda) * ($var_iva / 100);

            if($verificarProdutoAdicionado){
                $update = Itens_venda::findOrFail($verificarProdutoAdicionado->id);
                $update->quantidade = $update->quantidade + 1;
                $update->valor_pagar = $update->valor_pagar  + ($update->preco_unitario *  1);
                
                $update->custo_ganho = ($produto->preco - $produto->preco_custo) * $update->quantidade;

                /////

                $valorBase = ($update->valor_pagar) - (($update->valor_pagar) * ($var_iva / 100));
                $valorIva = ($update->valor_pagar) * ($var_iva / 100);

                $update->valor_iva = $valorIva;
                $update->valor_base = $valorBase;

                //////
                $update->update();

                $produto->estoque->stock = $produto->estoque->stock - 1; 
                $produto->estoque->update(); 

                return redirect()->route('facturas.edit', $id_back);
            }else{
                $create = Itens_venda::create(
                    [
                        'produto_id' => $produto->id,
                        'movimento_id' => $movimentoActivo->id,
                        'quantidade' => 1,
                        'user_id' => Auth::user()->id,
                        'valor_pagar' => $produto->preco_venda * 1,
                        'preco_unitario' => $produto->preco_venda,
                        'custo_ganho' => ($produto->preco_venda - $produto->preco_custo) * 1,
                        'desconto_aplicado' => 0,
                        'valor_iva' => $valorIva,
                        'valor_base' => $valorBase,
                        'status' => 'processo',
                        'desconto_aplicado_valor' => 0,
                        'iva' => $produto->imposto,
                        'iva_taxa' => $produto->taxa,
                        'texto_opcional' => "",
                        'code' => $codigo,
                        'numero_serie' => "",
                        'entidade_id' => $entidade->empresa->id, 
                    ]
                );  
                
                if($create->save()){

                    $produto->estoque->stock = $produto->estoque->stock - 1; 
                    $produto->estoque->update(); 

                    return redirect()->route('facturas.edit', $id_back);
                }else{
                    Alert::error('Erro', 'O correu um erro ão tentar adicionar este produto');
                    return redirect()->route('facturas.edit', $id_back);
                }
            }

        }else{
            Alert::error('Erro', 'Verifica se tens um caixa aberto, por favor!');
            return redirect()->route('facturas.edit', $id_back);
        }
    }
    ################

    ############## PROCESSO FACTURA

    public function actualizar_vendas_factura($id)
    {
        $movimento = Itens_venda::with('produto')->findOrFail($id);
        
        $produto = Produto::findOrFail($movimento->produto_id);
        $grupo_precos = ProdutoGrupoPreco::with(['produto'])->where('produto_id', $produto->id)->get();
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $head = [
            "titulo" => env('APP_NAME') ." Pronto Vendas",
            "descricao" => env('APP_NAME'),
            "categorias" => Categoria::with('produtos.marca', 'produtos.variacao')->where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "dados" => Entidade::findOrFail($entidade->empresa->id),
            "movimento" => $movimento,
            "produto" => $produto,
            "grupo_precos" => $grupo_precos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.pronto-venda.actualizar-venda-factura', $head);
    }

    public function actualizar_vendas_factura_update(Request $request, $id)
    {
        try {
            // Inicia a transação
            DB::beginTransaction();
        
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            $movimento = Itens_venda::with('produto')->findOrFail($id);
            
            $quantidade_final = $request->input1 * $request->input2 * $request->quantidade;
            
            $produto = Produto::with('estoque')->findOrFail($movimento->produto_id);
            
            if($movimento){
                $loja = Loja::where([
                   ['status', '=', 'activo'],
                   ['entidade_id', '=', $entidade->empresa->id], 
                ])->first();
                
                if ($produto->tipo == 'P') {
                        
                    if(!$loja){
                        Alert::warning('Atenção', 'Não têm nenhuma loja/armazém activa no momento para registrar saída deste produto. Por favor activa uma loja/armazém que tem este produto!');
                        return redirect()->back()->with('warning', 'Não têm nenhuma loja/armazém activa no momento para registrar saída deste produto.');
                    }
                    
                    // verificar quantidade de produto no estoque da loja
                    $verificar_quantidade = Estoque::where('loja_id', $loja->id)
                        ->where('produto_id', $movimento->produto_id)
                        ->where('entidade_id', $entidade->empresa->id)
                        ->sum('stock');
                    
                    $verificar_quantidade = (int) $verificar_quantidade;
                    
                    if($quantidade_final > $verificar_quantidade){
                        Alert::warning('Atenção', 'A Loja activa não têm esta quantidade de produto em stock para poder comercializar!');
                        return redirect()->back()->with('warning', 'A Loja activa não têm esta quantidade de produto em stock para poder comercializar!');
                    }
        
                    if($quantidade_final > $produto->estoque->stock){
                        Alert::warning('Atenção', 'A quantidade Adiciona nesta compra e maior do que a existente no Stock!');
                        return redirect()->back()->with('danger', 'A quantidade Adiciona nesta compra e maior do que a existente no Stock!');
                    }
                }
    
                $desconto = ($request->preco_unitario * $quantidade_final) * ($request->desconto_aplicado ?? 0) / 100;
    
                $produto->estoque->stock = ($produto->estoque->stock + $movimento->quantidade) - $quantidade_final;
    
                $valorBase = $request->preco_unitario * $quantidade_final; 
                // calculo do iva
                $valorIva = ($produto->taxa ?? 0) / 100 * $valorBase;
                
                $retencao_fonte = 0;
                
                if($produto->tipo == "S"){
                    $valor_ = $valorBase + $valorIva;
                    $retencao_fonte = $valor_ * $entidade->empresa->taxa_retencao_fonte / 100;
                }else {
                    $retencao_fonte = 0;
                }
    
                $movimento->quantidade = $quantidade_final;
                $movimento->valor_pagar = ($valorBase + $valorIva) - $desconto;
                $movimento->preco_unitario = $request->preco_unitario;
    
                $movimento->valor_base = $valorBase;
                $movimento->valor_iva = $valorIva;
                $movimento->retencao_fonte = $retencao_fonte;
    
                $movimento->desconto_aplicado = $request->desconto_aplicado;
                $movimento->desconto_aplicado_valor = $desconto;
    
                $movimento->iva = $request->iva;
                $movimento->texto_opcional = $request->texto_opcional;
                $movimento->numero_serie = $request->numero_serie;
                
                if ($produto->tipo == 'P') {
                    if($movimento->update()){
                        $produto->estoque->update();
                    }else{
                        Alert::error('Erro', 'Ao tentar actualizar os dodos deste produto nesta venda');
                        return redirect()->route('facturas.create')->with('Aconteceu um erro por isso não concluimos facturação deste produto!');
                    }
                    
                    if($quantidade_final > $request->quantidade_anterior){
                    
                        Registro::create([
                           "registro" => "Saída de Stock",
                           "data_registro" => date('Y-m-d'),
                           "quantidade" => (int) $quantidade_final - (int) $request->quantidade_anterior,
                           "produto_id" => $produto->id,
                           "observacao" => "Saída de produto {$produto->nome} para venda",
                           "loja_id" => $loja->id,
                           "lote_id" => NULL,
                           "user_id" => Auth::user()->id,
                           'entidade_id' => $entidade->empresa->id,
                       ]);
                       
                    }else if($quantidade_final < $request->quantidade_anterior){
                    
                        Registro::create([
                           "registro" => "Saída de Stock",
                           "data_registro" => date('Y-m-d'),
                           "quantidade" => (int) $request->quantidade_anterior - (int) $quantidade_final,
                           "produto_id" => $produto->id,
                           "observacao" => "Saída de produto {$produto->nome} para venda",
                           "loja_id" => $loja->id,
                           "lote_id" => NULL,
                           "user_id" => Auth::user()->id,
                           'entidade_id' => $entidade->empresa->id,
                       ]);
                    }
                }else {
                    $movimento->update();
                }
                
            }else{
                Alert::error('Erro', 'Ao tentar actualizar os dodos deste produto nesta venda');
                return redirect()->route('facturas.create');
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



}
