<?php

namespace App\Http\Controllers\pdf;

use App\Http\Controllers\Controller;
use App\Models\Caixa;
use App\Models\Cliente;
use App\Models\Entidade;
use App\Models\Exercicio;
use App\Models\Itens_venda;
use App\Models\Loja;
use App\Models\Periodo;
use App\Models\Processamento;
use App\Models\Produto;
use App\Models\Quarto;
use App\Models\Registro;
use App\Models\Reserva;
use App\Models\TipoProcessamento;
use App\Models\User;
use App\Models\Venda;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use PDF;
use Excel;
use Illuminate\Support\Facades\DB;

class PDFController extends Controller
{
    //
    public function imprimirProcessamentos(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $processamentos_orgacao_social = Processamento::with(['exercicio', 'periodo', 'funcionario.contrato.categoria', 'funcionario.contrato.cargo', 'processamento', 'user'])
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
        ->where('categoria', "Orgão Sociais")
        ->where('entidade_id', $entidade->empresa->id)
        ->orderBy('created_at', 'desc')
        ->get();
        
        $processamentos_pessoal = Processamento::with(['exercicio', 'periodo', 'funcionario.contrato.categoria', 'funcionario.contrato.cargo', 'processamento', 'user'])
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
        ->where('categoria', "Pessoal")
        ->where('entidade_id', $entidade->empresa->id)
        ->orderBy('created_at', 'desc')
        ->get();
        
        $processamentos = Processamento::with(['exercicio', 'periodo', 'funcionario.contrato.categoria', 'funcionario.contrato.cargo', 'processamento', 'user'])
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

        $tipo_processamento = TipoProcessamento::find($request->processamento_id);

        $exercicio = Exercicio::find($request->exercicio_id);

        $periodo = Periodo::find($request->periodo_id);

        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);
        
        $head = [
            'titulo' => "Listagem de Produtos & Serviços",
            'descricao' => env('APP_NAME'),
            
            "empresa" => $empresa,
            "processamentos" => $processamentos,
            "processamentos_orgacao_social" => $processamentos_orgacao_social,
            "processamentos_pessoal" => $processamentos_pessoal,
            "tipo_processamento" => $tipo_processamento,
            "periodo" => $periodo,
            "exercicio" => $exercicio,
            
            "lojas" => Loja::where('entidade_id', $entidade->empresa->id)->get(),
            "requests" => $request->all('data_inicio', 'data_final', 'funcionario_id', 'processamento_id', 'exercicio_id', 'periodo_id', 'status'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.processamentos.imprimir', $head);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream();
    }
    //
    public function imprimirRecibosProcessamentos(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
    
        $processamentos = Processamento::with(['exercicio', 'periodo', 
            'funcionario.contrato.forma_pagamento', 
            'funcionario.contrato.categoria', 
            'funcionario.contrato.pacote_salarial.subsidios_pacotes.subsidio', 
            'funcionario.contrato.cargo.departamento', 
            'funcionario.contrato.tipo_contrato',  'processamento', 'user'
        ])
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
        ->where('entidade_id', $entidade->empresa->id)
        ->orderBy('created_at', 'desc')
        ->get();

        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);
        
        $head = [
            'titulo' => "Recibos",
            'descricao' => env('APP_NAME'),
            
            "empresa" => $empresa,
            "processamentos" => $processamentos,
            
            "lojas" => Loja::where('entidade_id', $entidade->empresa->id)->get(),
            "requests" => $request->all('data_inicio', 'data_final', 'funcionario_id', 'processamento_id', 'exercicio_id', 'periodo_id', 'status'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.processamentos.recibos', $head);
      //  $pdf->setPaper('A4', 'landscape');

        return $pdf->stream();
    }
    
    
    public function pdfClientes(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
 
        $clientes = Cliente::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito'])->where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->orderBy('conta', 'asc')->get();
            

        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);
        
        $head = [
            'titulo' => "Listagem dos Clientes",
            'descricao' => "",
            'clientes' => $clientes,
            "empresa" => $empresa,
            "requests" => $request->all('hora_entrada', 'hora_saida', 'data_inicio', 'data_final', 'cliente_id', 'status_reserva', 'status_pagamento', 'quarto_id'),
            "lojas" => Loja::where('entidade_id', $entidade->empresa->id)->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.clientes.pdf-clientes', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
        // return $pdf->download('test.pdf');
    }
    
    
    public function pdfReserva(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $reservas = Reserva::when($request->cliente_id, function ($query, $value) {
            $query->where('cliente_id', $value);
        })
        ->when($request->status_reserva, function ($query, $value) {
            $query->where('status', $value);
        })
        ->when($request->quarto_id, function ($query, $value) {
            $query->where('quarto_id', $value);
        })
        ->when($request->status_pagamento, function ($query, $value) {
            $query->where('pagamento', $value);
        })
        ->when($request->hora_entrada, function ($query, $value) {
            $query->where('hora_entrada', $value);
        })
        ->when($request->hora_saida, function ($query, $value) {
            $query->where('hora_saida', $value);
        })
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('data_inicio', '>=', $value);
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('data_final', '<=', $value);
        })
        ->with([
            'quarto',
            'exercicio',
            'periodo',
            'cliente.estado_civil',
            'cliente.seguradora',
            'cliente.provincia',
            'cliente.municipio',
            'cliente.distrito'
        ])
        ->where('entidade_id', $entidade->empresa->id)
        ->orderBy('created_at', 'desc')
        ->get();

        $cliente = Cliente::find($request->cliente_id);
        $quarto = Quarto::find($request->quarto_id);

        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);
        
        $head = [
            'titulo' => "Listagem das Reservas",
            'descricao' => "",
            'cliente' => $cliente,
            'quarto' => $quarto,
            'reservas' => $reservas,
            "empresa" => $empresa,
            "requests" => $request->all('hora_entrada', 'hora_saida', 'data_inicio', 'data_final', 'cliente_id', 'status_reserva', 'status_pagamento', 'quarto_id'),
            "lojas" => Loja::where('entidade_id', $entidade->empresa->id)->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.reservas.pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
        // return $pdf->download('test.pdf');
    }
    
    
    public function pdfProduto(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $produtos = Produto::where([
            ['user_id', '=', Auth::user()->id]
        ])
        ->when($request->categoria_id, function($query, $value){
            $query->where('categoria_id', '=', $value);
        })
        ->when($request->tipo, function($query, $value){
            $query->where('tipo', '=', $value);
        })
        ->when($request->marca_id, function($query, $value){
            $query->where('marca_id', '=', $value);
        })
        ->where('entidade_id', '=', $entidade->empresa->id)
        ->orderBy('created_at', 'desc')
        ->get();

        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);
        
        $head = [
            'titulo' => "Listagem de Produtos & Serviços",
            'descricao' => env('APP_NAME'),
            'produtos' => $produtos,
            "empresa" => $empresa,
            "lojas" => Loja::where('entidade_id', $entidade->empresa->id)->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.produtos.pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
        // return $pdf->download('test.pdf');
    }
    
    public function pdfVendas(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);
       
        $query = Venda::with(['user', 'cliente'])->where('entidade_id', $entidade->empresa->id)
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('created_at', '>=', Carbon::createFromDate($value));
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('created_at', '<=', Carbon::createFromDate($value));
        })
        ->where('status_factura', ['pago'])
        ->when($request->caixa_id, function($query, $value){
            $query->where('caixa_id', '=', $value);
        })
        ->when($request->user_id, function($query, $value){
            $query->where('user_id', '=', $value);
        })
        ->orderBy('created_at', 'desc');
             
        $total_venda = $query->sum('valor_total');
        
        $vendas = $query->get();
        
        $caixa = Caixa::find($request->caixa_id);
        $user = User::find($request->user_id);
    
        $head = [
            'titulo' => "Lista de Vendas",
            'descricao' => env('APP_NAME'),
            'total_venda' => $total_venda,
            'vendas' => $vendas,
            "caixa" => $caixa,
            "user" => $user,
            "empresa" => $empresa,
            "requests" => $request->all('data_inicio', 'data_final','caixa_id', 'user_id'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.vendas.pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
        // return $pdf->download('test.pdf');
    }
        
    public function imprimirPdfVendas(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);
       
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
        
        $caixa = Caixa::find($request->caixa_id);
        $user = User::find($request->user_id);
    
        $head = [
            'titulo' => "Lista de Vendas",
            'descricao' => env('APP_NAME'),
            'total_venda' => 0,
            // 'total_venda' => $total_venda,
            'vendas' => $vendas,
            "caixa" => $caixa,
            "user" => $user,
            "empresa" => $empresa,
            "requests" => $request->all('data_inicio', 'data_final','caixa_id', 'user_id'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.vendas.pdf-por-produto', $head);

        // $pdf = PDF::loadView('dashboard.vendas.pdf', $head);
        // $pdf->setPaper('A4', 'portrait');

        // return $pdf->stream();
        // return $pdf->download('test.pdf');
    }
        
    
    public function pdfStockArtigo(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
       
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
       
        // Obter todos os produtos com suas vendas e Stock
        $produtos = Produto::with(['vendas' => function ($query) use ($request) {
            $query->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('created_at', '>=', Carbon::createFromDate($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('created_at', '<=', Carbon::createFromDate($value));
            })
            ->when($request->caixa_id, function ($query, $value) {
                $query->where('caixa_id', '=', $value);
            })
            ->when($request->loja_id, function ($query, $value) {
                $query->whereHas('factura', function ($query) use ($value) {
                    $query->where('loja_id', '=', $value);
                });
            });
        }, 'stocks'])
        ->where('entidade_id', $entidade->empresa->id)
        ->get();
        
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
                'total_liquido_restante' => $produto->preco_venda * $quantidadeInicial,
                'total_liquido_geral' => $produto->preco_venda * $quantidadeEmEstoque,
                'quantidade_inicial' => $quantidadeInicial,
                'quantidade_vendida' => $quantidadeVendida,
                'quantidade_estoque' => $quantidadeEmEstoque,
                'quantidade_restante' => $quantidadeRestante,
            ];
        });
        
        $loja = Loja::find($request->loja_id);
        $user = User::find($request->user_id);
    
        $head = [
            'titulo' => "Stock Por Artigo",
            'descricao' => env('APP_NAME'),
            'dados' => $dados,
            "loja" => $loja,
            "user" => $user,
            "requests" => $request->all('data_inicio', 'data_final','loja_id', 'user_id'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.vendas.pdf-stock-artigo', $head);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream();
        // return $pdf->download('test.pdf');
    }
    
    public function pdfMovimentoEstoque(Request $request)
    {
    
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $movimentos = Registro::when($request->loja_id, function($query, $value){
            $query->where('loja_id', $value);
        })
        ->when($request->produto_id, function($query, $value){
            $query->where('produto_id', $value);
        })
        ->when($request->data_inicio, function($query, $value){
            $query->whereDate('created_at', '>=', Carbon::parse($value));
        })
        ->when($request->data_final, function($query, $value){
            $query->whereDate('created_at', '<=',Carbon::parse($value));
        })
        ->with('produto', 'user', 'loja')
        ->where('entidade_id', $entidade->empresa->id)
        ->orderBy('created_at', 'desc')
        ->get();
        
        $produto = Produto::find($request->produto_id);
        $loja = Loja::find($request->loja_id);

        $empresa = User::with("variacoes")->with('empresa')->with("categorias")->with("marcas")->findOrFail(Auth::user()->id);
        $head = [
            'titulo' => "Movimentos do Stock",
            'descricao' => "",
            'movimentos' => $movimentos,
            "produto" => $produto,
            "loja" => $loja,
            "empresa" => $empresa,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
            "requests" => $request->all('loja_id', 'produto_id', 'data_inicio', 'data_final')
        ];
        

        $pdf = PDF::loadView('dashboard.estoques-movimentos.pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
        // return $pdf->download('estoques-movimentos.pdf');
    }

    public function pdfMovimentoEstoqueLoja($id)
    {
        $loja = Loja::findOrFail($id);

        $movimentos = Registro::with('produto', 'user', 'loja')->where([
            ['registros.user_id', '=', Auth::user()->id],
            ['registros.produto_id', '=', $loja->id],
        ])
        ->orderBy('registros.created_at', 'desc')
        ->get();

        $empresa = User::with("variacoes")->with('empresa')->with("categorias")->with("marcas")->findOrFail(Auth::user()->id);
        $head = [
            'titulo' => "Movimentos do Stock",
            'descricao' => env('APP_NAME'),
            'tituloPagina' => "Movimentos do Stock",
            'movimentos' => $movimentos,
            "empresa" => $empresa,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.estoques-movimentos.pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
        // return $pdf->download('estoques-movimentos.pdf');
    }

    public function pdfMovimentoEstoqueProduto($id)
    {
        $produto = Produto::findOrFail($id);

        $movimentos = Registro::with('produto', 'user', 'loja')->where([
            ['registros.user_id', '=', Auth::user()->id],
            ['registros.produto_id', '=', $produto->id],
        ])
        ->orderBy('registros.created_at', 'desc')
        ->get();

        $empresa = User::with("variacoes")->with('empresa')->with("categorias")->with("marcas")->findOrFail(Auth::user()->id);
        $head = [
            'titulo' => "Movimentos do Stock",
            'descricao' => env('APP_NAME'),
            'tituloPagina' => "Movimentos do Stock",
            'movimentos' => $movimentos,
            "empresa" => $empresa,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.estoques-movimentos.pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
        // return $pdf->download('estoques-movimentos.pdf');
    }

    public function imprimirFactura()
    {
        $head = [
            'titulo' => "Movimentos do Stock",
            'descricao' => env('APP_NAME'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.facturas.documentos.factura', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function imprimirFacturaRecibo($id)
    {
        $vendas = Venda::with('cliente')->where('code', $id)->first();
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $items = Itens_venda::with('produto')->where('code', $vendas->code)->get();

        $head = [
            'titulo' => "Movimentos do Stock",
            'descricao' => env('APP_NAME'),
            "loja" => Entidade::findOrFail($entidade->empresa->id),
            "factura" => $vendas,
            "items" => $items,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.facturas.documentos.factura-recibo', $head);
    }

    public function cliente_pdf(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $clientes = Cliente::with(['vendas.items.produto'])
        ->with(['vendas.items' => function ($query) use ($request) {
            // Filtrar as vendas por intervalo de datas se as datas forem fornecidas
            $query->when($request->data_inicio && $request->data_final, function ($query) use ($request) {
                $query->whereBetween('created_at', [Carbon::parse($request->data_inicio), Carbon::parse($request->data_final)]);
            });
            $query->where('status', '!=' , 'anulada');
        }])
        ->when($request->cliente_id, function ($query, $clienteId) {
            // Filtrar pelo cliente se um ID de cliente for fornecido
            $query->where('id', $clienteId);
        })
        ->where('entidade_id', $entidade->empresa->id)
        ->orderBy('created_at', 'desc')
        ->get();
        
        
        // Preparar os dados para que os produtos não sejam duplicados
        $dadosClientes = $clientes->map(function ($cliente) {
            // Usar um array para consolidar os produtos
            $produtosAgrupados = [];
        
            foreach ($cliente->vendas as $venda) {
                foreach ($venda->items as $item) {
                
                    $produtoId = $item->produto->id;
        
                    // Se o produto já estiver no array, somar a quantidade e o valor
                    if (isset($produtosAgrupados[$produtoId])) {
                        $produtosAgrupados[$produtoId]['quantidade'] += $item->quantidade;
                        $produtosAgrupados[$produtoId]['valor_pagar'] += $item->valor_pagar;
                        $produtosAgrupados[$produtoId]['desconto_aplicado_valor'] += $item->desconto_aplicado_valor;
                        $produtosAgrupados[$produtoId]['custo_ganho'] += $item->custo_ganho;
                    } else {
                    
                        // Se não estiver, adicionar ao array
                        $produtosAgrupados[$produtoId] = [
                            'produto' => $item->produto->nome,
                            'preco' => $item->produto->preco_venda,
                            'custo' => $item->produto->preco_custo,
                            
                            'quantidade' => $item->quantidade,
                            'preco_unitario' => $item->preco_unitario,
                            'valor_pagar' => $item->valor_pagar,
                            'desconto_aplicado_valor' => $item->desconto_aplicado_valor,
                            'custo_ganho' => $item->custo_ganho,
                        ];
                    }
                }
            }
        
            // Retornar os dados do cliente com os produtos agrupados
            return (object) [
                'cliente' => $cliente->nome,
                'codigo' => $cliente->id,
                'produtos' => array_values($produtosAgrupados), // Usar array_values para retornar apenas os valores
            ];
        });
        
        $head = [
            "titulo" => "Relatórios de Clientes",
            "descricao" => env('APP_NAME'),
            "clientes" => $clientes,
            "dadosClientes" => $dadosClientes,
            "requests" => $request->all('data_inicio', 'data_final', 'cliente_id'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.relatorio', $head);
    }


    public function cliente_pdf_imprimir(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $cliente = Cliente::find($request->cliente_id);
        
        $clientes = Cliente::with(['vendas.items.produto'])
        ->with(['vendas.items' => function ($query) use ($request) {
            // Filtrar as vendas por intervalo de datas se as datas forem fornecidas
            $query->when($request->data_inicio && $request->data_final, function ($query) use ($request) {
                $query->whereBetween('created_at', [Carbon::parse($request->data_inicio), Carbon::parse($request->data_final)]);
            });
            $query->where('status', '!=' , 'anulada');
        }])
        ->when($request->cliente_id, function ($query, $clienteId) {
            // Filtrar pelo cliente se um ID de cliente for fornecido
            $query->where('id', $clienteId);
        })
        ->where('entidade_id', $entidade->empresa->id)
        ->orderBy('created_at', 'desc')
        ->get();
        
        // Preparar os dados para que os produtos não sejam duplicados
        $dadosClientes = $clientes->map(function ($cliente) {
            // Usar um array para consolidar os produtos
            $produtosAgrupados = [];
        
            foreach ($cliente->vendas as $venda) {
                foreach ($venda->items as $item) {
                
                    $produtoId = $item->produto->id;
        
                    // Se o produto já estiver no array, somar a quantidade e o valor
                    if (isset($produtosAgrupados[$produtoId])) {
                        $produtosAgrupados[$produtoId]['quantidade'] += $item->quantidade;
                        $produtosAgrupados[$produtoId]['valor_pagar'] += $item->valor_pagar;
                        $produtosAgrupados[$produtoId]['desconto_aplicado_valor'] += $item->desconto_aplicado_valor;
                        $produtosAgrupados[$produtoId]['custo_ganho'] += $item->custo_ganho;
                    } else {
                    
                        // Se não estiver, adicionar ao array
                        $produtosAgrupados[$produtoId] = [
                            'produto' => $item->produto->nome,
                            'preco' => $item->produto->preco_venda,
                            'custo' => $item->produto->preco_custo,
                            
                            'quantidade' => $item->quantidade,
                            'preco_unitario' => $item->preco_unitario,
                            'valor_pagar' => $item->valor_pagar,
                            'desconto_aplicado_valor' => $item->desconto_aplicado_valor,
                            'custo_ganho' => $item->custo_ganho,
                        ];
                    }
                }
            }
        
            // Retornar os dados do cliente com os produtos agrupados
            return (object) [
                'cliente' => $cliente->nome,
                'codigo' => $cliente->id,
                'produtos' => array_values($produtosAgrupados), // Usar array_values para retornar apenas os valores
            ];
        });
        
        $head = [
            "titulo" => "Relatórios de Compras dos Clientes",
            "descricao" => env('APP_NAME'),
            "cliente" => $cliente,
            "dadosClientes" => $dadosClientes,
            "requests" => $request->all('data_inicio', 'data_final', 'cliente_id'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.clientes.pdf-compras', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }


    // public function exportProdutoExcel()
    // {
    //     return Excel::download(new ProdutoExport, 'produto.xlsx');
    // }

    // public function exportProdutoCsv()
    // {
    //     return Excel::download(new ProdutoExport, 'produto.csv');
    // }
}
