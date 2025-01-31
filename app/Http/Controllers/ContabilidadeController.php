<?php

namespace App\Http\Controllers;

use App\Models\Caixa;
use App\Models\Categoria;
use App\Models\Entidade;
use App\Models\Itens_venda;
use App\Models\Marca;
use Illuminate\Support\Facades\DB;
use App\Models\Exercicio;
use App\Models\Periodo;
use App\Models\Movimento;
use App\Models\OperacaoFinanceiro;
use App\Models\Produto;
use App\Models\Recibo;
use App\Models\Classe;
use App\Models\Conta;
use App\Models\Subconta;
use App\Models\User;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;
use RealRashid\SweetAlert\Facades\Alert;

class ContabilidadeController extends Controller
{
    //
    use TraitHelpers;
    
    public function inventario(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(['variacoes', 'categorias', 'marcas'])->findOrFail($entidade->empresa->id);
        
        $produtos = Produto::when($request->nome_referencia, function($query, $value){
            $query->where('nome', 'LIKE', "%{$value}%");
            $query->orWhere('referencia', 'LIKE', "%{$value}%");
        })
        ->when($request->categoria_id, function($query, $value) {
            $query->where('categoria_id', $value);
        })
        ->when($request->marca_id, function($query, $value) {
            $query->where('marca_id', $value);
        })
        ->withSum('quantidade', 'quantidade')
        ->where('entidade_id', $entidade->empresa->id)
        ->groupBy('id')
        ->orderBy('nome', 'asc')
        ->get();
        
        $head = [
            "titulo" => "Inventário",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "produtos" => $produtos,
            
            "requests" => $request->all('categoria_id', 'marca_id'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
        
        return view('dashboard.contabilidade.inventario', $head);
    }
        
    public function inventarioExportarPdf(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(['variacoes', 'categorias', 'marcas'])->findOrFail($entidade->empresa->id);
        
        $produtos = Produto::where('tipo', 'P')->when($request->nome_referencia, function($query, $value){
            $query->where('nome', 'LIKE', "%{$value}%");
            $query->orWhere('referencia', 'LIKE', "%{$value}%");
        })
        ->when($request->categoria_id, function($query, $value) {
            $query->where('categoria_id', $value);
        })
        ->when($request->marca_id, function($query, $value) {
            $query->where('marca_id', $value);
        })
        ->withSum('quantidade', 'quantidade')
        ->where('entidade_id', $entidade->empresa->id)
        ->groupBy('id')
        ->orderBy('nome', 'asc')
        ->get();
        
        $marca = Marca::find($request->marca_id);
        $categoria = Categoria::find($request->categoria_id);

        $head = [
            "titulo" => "INVETÁRIO INICIAL",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "produtos" => $produtos,
            "marca" => $marca,
            "categoria" => $categoria,
            // "tituloPagina" => "RELATÓRIO DE INVETÁRIO INICIAL",            
            "requests" => $request->all('categoria_id', 'marca_id'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
        
        $pdf = PDF::loadView('dashboard.produtos.inventario-pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
        
        return view('dashboard.contabilidade.inventario', $head);
    }
    
    public function diarios(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('movimento no caixa')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
    
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);
        
        $data = date("Y-m-d");

        $movimentos = Movimento::where('entidade_id', $entidade->empresa->id)
        ->when($data, function ($query, $value) {
            $query->whereDate('data_at', '>=', Carbon::createFromDate($value));
        })
        ->when($data, function ($query, $value) {
            $query->whereDate('data_at', '<=', Carbon::createFromDate($value));
        })
        ->where('user_id', Auth::user()->id)
        ->with(['user','subconta'])
        ->where('entidade_id', '=', $entidade->empresa->id)
        ->get();
        
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
            
        foreach($movimentos as $item){
            
            if($item->forma_movimento == "NU"){
                $numerorio_credito += $item->credito;
                $numerorio_debito += $item->debito;
            }
            
            if($item->forma_movimento == "MB"){
                $multicaixa_credito += $item->credito;
                $multicaixa_debito += $item->debito;
            }
            
            if($item->forma_movimento == "OU"){
                $duplo_credito += $item->credito;
                $duplo_debito += $item->debito;
            }
        
            $credito += $item->credito;
            $debito += $item->debito;
        }
        
        $multicaixa = $multicaixa_debito - $multicaixa_credito;
        $numerorio = $numerorio_debito - $numerorio_credito;
        $duplo = $duplo_debito - $duplo_credito;

       
        $relatorios = Venda::with(['items', 'user', 'caixa','cliente'])
            ->where('entidade_id', $empresa->id)
            ->where('status_factura', ['pago'])
            ->where('factura', ['FR'])
            ->when($data, function($query, $value){
                $query->whereDate('created_at', '=',Carbon::parse($value));
            })
            ->get();
        
        
        $facturas = Recibo::where('entidade_id', '=', $entidade->empresa->id)
            ->with(['cliente', 'facturas'])
            ->when($data, function($query, $value){
                $query->whereDate('created_at', '=',Carbon::parse($value));
            })
            ->get();
            
        // Unifica as coleções e adiciona um campo de identificação do tipo
        $resultadoUnificado = $relatorios->map(function ($item) {
            $item->tipo = 'relatorio'; // Identificador de tipo
            return $item;
        })->merge(
            $facturas->map(function ($item) {
                $item->tipo = 'factura'; // Identificador de tipo
                return $item;
            })
        );

        $total_arrecadado = Venda::where('entidade_id', $empresa->id)
            ->where('user_id', Auth::user()->id)
            ->where('status_factura', ['pago'])
            ->when($data, function($query, $value){ $query->whereDate('created_at', '=',Carbon::parse($value)); })
            ->get();

        
        $head = [
            "titulo" => "Diários",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "relatorios" => $relatorios,
            "total_arrecadado" => $total_arrecadado,
            "resultadoUnificado" => $resultadoUnificado,
            
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

        return view('dashboard.contabilidade.diario', $head);
    }
    
    public function diariosPDF(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);
        
        $caixaActivo = Caixa::where([
            ['active', true],
            ['status', '=', 'aberto'],
            ['user_id', '=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->first();
        
        if($caixaActivo){
           
        }else{
            Alert::error('Erro', 'Verifica se tens um caixa aberto, por favor!');
            return redirect()->back();
        }
       
        $data = date("Y-m-d");
       
        $relatorios = Venda::with(['items', 'user', 'caixa','cliente'])
            ->where('entidade_id', $empresa->id)
            ->where('status_factura', ['pago'])
            ->where('factura', ['FR'])
            ->when($data, function($query, $value){
                $query->whereDate('created_at', '=',Carbon::parse($value));
            })
            ->get();
        
        $total_arrecadado = Venda::where('entidade_id', $empresa->id)
            ->where('status_factura', ['pago'])
            ->where('factura', ['FR'])
            ->when($data, function($query, $value){ $query->whereDate('created_at', '=',Carbon::parse($value)); })
            ->selectRaw('SUM(CASE WHEN pagamento = "NU" THEN valor_total ELSE 0 END) as total_arrecadado_cash')
            ->selectRaw('SUM(CASE WHEN pagamento = "MB" THEN valor_total ELSE 0 END) as total_arrecadado_multicaixa')
            ->selectRaw('SUM(CASE WHEN pagamento = "OU" THEN valor_total ELSE 0 END) as total_arrecadado_duplo')
            ->selectRaw('SUM(valor_total) as total_arrecadado')
            ->first();
            
        $facturas = Recibo::where('entidade_id', '=', $entidade->empresa->id)
            ->with(['cliente', 'facturas'])
            ->when($data, function($query, $value){
                $query->whereDate('created_at', '=',Carbon::parse($value));
            })
            ->get();
            
        $total_arrecadado_cash = 0;
        $total_arrecadado_multicaixa = 0;
        $total_arrecadado_duplo = 0;  
            
            
        // Unifica as coleções e adiciona um campo de identificação do tipo
        $resultadoUnificado = $relatorios->map(function ($item) {
            $item->tipo = 'relatorio'; // Identificador de tipo
            return $item;
        })->merge(
            $facturas->map(function ($item) {
                $item->tipo = 'factura'; // Identificador de tipo
                return $item;
            })
        );
        
        foreach($resultadoUnificado as $item)
        {
            if($item->pagamento == "NU"){
                $total_arrecadado_cash += $item->valor_total;
            }
            if($item->pagamento == "MB"){
                $total_arrecadado_multicaixa += $item->valor_total;
            }
            if($item->pagamento == "OU"){
                $total_arrecadado_duplo += $item->valor_total;
            }
        }
        
        $head = [
            "titulo" => "Movimentos Diário",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "relatorios" => $relatorios,
            "resultadoUnificado" => $resultadoUnificado,
            "total_arrecadado_cash" => $total_arrecadado_cash,
            "total_arrecadado_multicaixa" => $total_arrecadado_multicaixa,
            "total_arrecadado_duplo" => $total_arrecadado_duplo,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
        
        $pdf = PDF::loadView('dashboard.contabilidade.diarios-pdf', $head);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->stream();

    }    
    
    public function diariosDetalhe(Request $request, $id)
    {
    
        $user = auth()->user();
        
        if(!$user->can('movimento no caixa')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
    
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);
        
        $vendas = Venda::with(['items.produto', 'user', 'caixa', 'cliente'])->findOrFail($id);
        
        $head = [
            "titulo" => "Diários Detalhes",
            "descricao" => env('APP_NAME'),
            "relatorios" => $vendas,            
            "empresa" => $empresa,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
        
        return view('dashboard.contabilidade.diario-detalhes', $head);
    }
        
    public function facturacao(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('movimento no caixa geral')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
    
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $empresa = Entidade::with(["caixas", "users", "variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);
       
        $relatorios = Venda::with(['items', 'user', 'caixa','cliente'])
        ->with(['items' => function ($query) use ($request) {
            $query->where('status', '!=' , 'anulada');
        }])
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
        ->where('entidade_id', $empresa->id)
        ->whereIn('status_factura', ['pago'])
        ->get();
        
        $total_arrecadado = Itens_venda::with(['factura'])->when($request->data_inicio, function ($query, $value) {
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
        ->where('status', "!=", "anulada")
        ->where('entidade_id', $empresa->id)
        ->get();
        
        $total_arrecadado_cash = 0;
        $total_arrecadado_multicaixa = 0;
        $total_arrecadado_transferencias = 0;
        $total_arrecadado_depositos = 0;
        
        foreach ($total_arrecadado as $valores) {
            
            if($valores->factura){
                if($valores->factura->pagamento == "NU"){
                    $total_arrecadado_cash += $valores->valor_pagar;
                }
                if($valores->factura->pagamento == "MB"){
                    $total_arrecadado_multicaixa += $valores->valor_pagar;
                }
                if($valores->factura->pagamento == "OU"){
                    $total_arrecadado_cash += $valores->valor_cash;
                    $total_arrecadado_multicaixa += $valores->valor_multicaixa;
                }
                if($valores->factura->pagamento == "TE"){
                    $total_arrecadado_transferencias += $valores->valor_pagar;
                }
                if($valores->factura->pagamento == "DE"){
                    $total_arrecadado_depositos += $valores->valor_pagar;
                }
            }
        }
        
        $head = [
            "titulo" => "FACTURAÇÃO",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "relatorios" => $relatorios,
            "total_arrecadado" => $total_arrecadado,
            "total_arrecadado_cash" => $total_arrecadado_cash,
            "total_arrecadado_multicaixa" => $total_arrecadado_multicaixa,
            "total_arrecadado_transferencias" => $total_arrecadado_transferencias,
            "total_arrecadado_depositos" => $total_arrecadado_depositos,
            "requests" => $request->all("data_inicio", "data_final", "caixa_id", "user_id"),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contabilidade.facturacao', $head);
    }
    
    public function getActivoNaoCorrente(array $contas, array $classeIds, string $subcont = null) 
    {
        return Conta::whereIn('conta', $contas)
            ->with(['subcontas' => function ($query) use($subcont) {
                if (!empty($subcont)) {
                    $query->where('numero', 'like', "{$subcont}%"); // Passa o array diretamente
                }
                $query->whereHas('movimentos', function ($query) {
                    $query->selectRaw('subconta_id, SUM(credito) as credito, SUM(debito) as debito')
                        ->groupBy('subconta_id');
                });
            }])
            ->whereHas('subcontas.movimentos') // Apenas subcontas com movimentos
            ->whereIn('classe_id', $classeIds)
            ->get();
    }
    
    public function calcularSaldos($activos) {
        $totais = ['credito' => 0, 'debito' => 0];
        foreach ($activos as $item) {
            foreach ($item->subcontas as $sub_) {
                foreach ($sub_->movimentos as $sl) {
                    $totais['credito'] += $sl->credito;
                    $totais['debito'] += $sl->debito;
                }
            }
        }
        return $totais;
    }
    
    public function balanco_inicial(Request $request)
    {
        $user = auth()->user();
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        // activos não correntes
        $meios_fixos_investimento_classe1 = $this->getActivoNaoCorrente(['11'], [1]);
        $meios_fixos_investimento_classe2 = $this->getActivoNaoCorrente(['12'], [1]);
        $meios_fixos_investimento_classe3 = $this->getActivoNaoCorrente(['13'], [1]);
        $meios_fixos_investimento_outros_activos_nao_correntes = $this->getActivoNaoCorrente(['14', '18'], [1]);
        $meios_fixos_investimento_financeiros = $this->getActivoNaoCorrente(['19'], [1]);
        
        $subtotal_activos_nao_correntes = $this->getActivoNaoCorrente(['11', '12', '13', '14', '15', '16', '17', '18', '19'], [1]);
        $saldos_activos_nao_correntes = $this->calcularSaldos($subtotal_activos_nao_correntes);
        // end activos não correntes
    
        // activos correntes
        $activo_corrente_existencias = $this->getActivoNaoCorrente(['21', '22', '23', '24', '25', '26', '27', '28'], [2]);
        $saldos_corrente_existencias = $this->calcularSaldos($activo_corrente_existencias);
        
        $activo_corrente_terceiros = $this->getActivoNaoCorrente(['31'], [3]);
        $activo_corrente_contas_receber = $this->getActivoNaoCorrente(['35'], [3], '35.1');
        $activo_corrente_contas_receber_2 = $this->getActivoNaoCorrente(['37'], [3], '37.2');
        
        $activo_corrente_disponibilidade = $this->getActivoNaoCorrente(['41', '42', '43', '44', '45', '48'], [4]);

        $subtotal_activos_correntes = $this->getActivoNaoCorrente(['21', '22', '23', '24', '25', '26', '27', '28', '31', '35', '37', '41', '42', '43', '44', '45', '48'], [1, 2, 3, 4]);
        $saldos_activos_correntes = $this->calcularSaldos($subtotal_activos_correntes);
        // end activos correntes
        
        
        // passivo não correntes
        $contas_passivo_nao_corrente = $this->getActivoNaoCorrente(['33'], [3], '33.1');
        $saldos_passivo_nao_corrente = $this->calcularSaldos($contas_passivo_nao_corrente);
        
        // passivo corrente
        $contas_passivo_corrente = $this->getActivoNaoCorrente(['32', '34', '36'], [3]);
        $saldo_passivo_corrente = $this->calcularSaldos($contas_passivo_corrente);
        
        // parte 1
        $outras_contas_passivos_correntes = $this->getActivoNaoCorrente(['37'], [3], '37.9');
        $saldo_outras_contas_passivos_correntes = $this->calcularSaldos($outras_contas_passivos_correntes);
        
        // parte 2
        $outras_contas_passivos_correntes1 = $this->getActivoNaoCorrente(['35'], [3], '35.2');
        $saldo_outras_contas_passivos_correntes1 = $this->calcularSaldos($outras_contas_passivos_correntes1);
                
        // passivo não correntes
        
        // capital Próprio
        $contas_resultado_transitados = $this->getActivoNaoCorrente(['81'], [8]);
        $saldo_resultado_transitados = $this->calcularSaldos($contas_resultado_transitados);
        
        $contas_resultado_liquido_exercicios = $this->getActivoNaoCorrente(['88'], [8]);
        $saldo_resultado_liquido_exercicios = $this->calcularSaldos($contas_resultado_liquido_exercicios);
        
        $contas_capital_social = $this->getActivoNaoCorrente(['51'], [5]);
        $saldo_capital_social = $this->calcularSaldos($contas_capital_social);
        
        $contas_reserva_legais = $this->getActivoNaoCorrente(['55'], [5]);
        $saldo_reserva_legais = $this->calcularSaldos($contas_reserva_legais);
        

        $head = [
            "titulo" => "Balanço Inicial",
            "descricao" => env('APP_NAME'),
            
            // activos
            // activos não correntes
            "meios_fixos_investimento_classe1" => $meios_fixos_investimento_classe1,
            "meios_fixos_investimento_classe2" => $meios_fixos_investimento_classe2,
            "meios_fixos_investimento_classe3" => $meios_fixos_investimento_classe3,
            "meios_fixos_investimento_financeiros" => $meios_fixos_investimento_financeiros,
            "meios_fixos_investimento_outros_activos_nao_correntes" => $meios_fixos_investimento_outros_activos_nao_correntes,
            "saldos_activos_nao_correntes" => $saldos_activos_nao_correntes,
            
            // activos correntes
            "activo_corrente_existencias" => $activo_corrente_existencias,
            "activo_corrente_disponibilidade" => $activo_corrente_disponibilidade,
            "activo_corrente_terceiros" => $activo_corrente_terceiros,
            "activo_corrente_contas_receber" => $activo_corrente_contas_receber,
            "activo_corrente_contas_receber_2" => $activo_corrente_contas_receber_2,
            "saldos_activos_correntes" => $saldos_activos_correntes,
            
            //passivos
            // passivos não correntes
            "contas_passivo_nao_corrente" => $contas_passivo_nao_corrente,
            "saldos_passivo_nao_corrente" => $saldos_passivo_nao_corrente,
            // passivo corrente
            "contas_passivo_corrente" => $contas_passivo_corrente,
            "saldo_passivo_corrente" => $saldo_passivo_corrente,
            
            "outras_contas_passivos_correntes" => $outras_contas_passivos_correntes,
            "saldo_outras_contas_passivos_correntes" => $saldo_outras_contas_passivos_correntes,
            
            "outras_contas_passivos_correntes1" => $outras_contas_passivos_correntes1,
            "saldo_outras_contas_passivos_correntes1" => $saldo_outras_contas_passivos_correntes1,
            // end passívos
            
            // capital social
            "contas_resultado_liquido_exercicios" => $contas_resultado_liquido_exercicios,
            "saldo_resultado_liquido_exercicios" => $saldo_resultado_liquido_exercicios,
            
            "contas_resultado_transitados" => $contas_resultado_transitados,
            "saldo_resultado_transitados" => $saldo_resultado_transitados,
            
            "contas_capital_social" => $contas_capital_social,
            "saldo_capital_social" => $saldo_capital_social,
            
            "contas_reserva_legais" => $contas_reserva_legais,
            "saldo_reserva_legais" => $saldo_reserva_legais,
            // capital social
            
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contabilidade.balanco-inicial', $head);
    }
        
    public function balanco_inicial_create(Request $request)
    {
        $user = auth()->user();

        // if(!$user->can('painel financeiro')){
        //     Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        //     return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // } 
    
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $subcontas = Subconta::with(['conta'])->where('entidade_id', $entidade->empresa->id)->orderBy('numero', 'asc')->get();
        
        $exercicio = Exercicio::findOrFail($this->exercicio());
        $periodos = Periodo::where('exercicio_id', $exercicio->id)->where('entidade_id', $entidade->empresa->id)->get();
        
        
        $movimentos = Movimento::with(['subconta' => function($query){
            $query->orderBy('numero', 'asc');
        }])
        ->whereIn('origem', ['BI'])
        ->where('exercicio_id', $exercicio->id)
        ->where('entidade_id', $entidade->empresa->id)
        ->get();
    
        $head = [
            "titulo" => "Novo Balanço Inicial",
            "descricao" => env('APP_NAME'),
            "subcontas" => $subcontas,
            "exercicio" => $exercicio,
            "periodos" => $periodos,
            "movimentos" => $movimentos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contabilidade.novo-balanco-inicial', $head);
    }
        
        
    public function balanco_inicial_store(Request $request)
    {
        $user = auth()->user();
        
        // if(!$user->can('painel financeiro')){
        //     Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        //     return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // } 
        
        $request->validate([
            'exercicio_id' => 'required|string',
            'periodo_id' => 'required|string',
            'subconta_id' => 'required|string',
            'saldo' => 'required|string',
        ],[
            'exercicio_id.required' => 'O exercício é um campo obrigatório',
            'periodo_id.required' => 'O período é um campo obrigatório',
            'subconta_id.required' => 'A subconta é um campo obrigatório',
            'saldo.required' => 'O saldo é um campo obrigatório',
        ]);
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $code = uniqid(time());
            
            $subconta = Subconta::findOrFail($request->subconta_id);
            
            Movimento::create([
                'user_id' => Auth::user()->id,
                'subconta_id' => $subconta->id,
                'movimento' => 'E',
                'observacao' => 'Saldo Inicial',
                'origem' => 'BI',
                'numero' => $subconta->nome,
                'credito' => 0,
                'debito' => $request->saldo,
                'exercicio_id' => $request->exercicio_id,
                'periodo_id' => $request->periodo_id,
                'code' => $code,
                'data_at' => date("Y-m-d"),
                'entidade_id' => $entidade->empresa->id,
            ]);
            
            OperacaoFinanceiro::create([
                'nome' => "BALANÇO INICAL",
                'status' => "pago",
                'motante' => $request->saldo,
                'subconta_id' => $subconta->id,
                'cliente_id' => NULL,
                'model_id' => 7,
                'type' => 'R',
                'parcelado' => "N",
                'status_pagamento' => "pago",
                'code' => $code,
                'descricao' => "BALANÇO INICAL",
                'movimento' => "E",
                'date_at' => date("Y-m-d"),
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
                'exercicio_id' => $request->exercicio_id,
                'periodo_id' => $request->periodo_id,
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
        
        
        return redirect()->back()->with("success", "Dados Cadastrar com Sucesso!");
        
    }
        
    public function balancete(Request $request)
    {
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $movimentos = Movimento::query()
        ->when($request->exercicio_id, fn($query, $value) => $query->where('exercicio_id', $value))
        ->when($request->periodo_id, fn($query, $value) => $query->where('periodo_id', $value))
        ->when($request->subconta_id, fn($query, $value) => $query->where('subconta_id', $value))
        ->when($request->data_inicio, fn($query, $value) => $query->whereDate('data_at', '>=', $value))
        ->when($request->data_final, fn($query, $value) => $query->whereDate('data_at', '<=', $value))
        ->with(['subconta', 'exercicio', 'periodo'])
        ->where('entidade_id', $entidade->empresa->id)
        ->get();
        
        $subcontas = Subconta::with(['conta'])->where('entidade_id', $entidade->empresa->id)->get();
        $exercicios = Exercicio::where('id', $this->exercicio())->get();
        $periodos = Periodo::where('exercicio_id', '=', $this->exercicio())->where('entidade_id', $entidade->empresa->id)->get();   
        
        $head = [
            "titulo" => "Balancete",
            "descricao" => env('APP_NAME'),
            "movimentos" => $movimentos,
            "subcontas" => $subcontas,
            "exercicios" => $exercicios,
            "periodos" => $periodos,
            "requests" => $request->all('exercicio_id', 'periodo_id', 'subconta_id', 'data_inicio', 'data_final'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contabilidade.balancete', $head);
    }
}
