<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Aluno;
use App\Models\AnoLectivo;
use App\Models\AnuncioAdmin;
use App\Models\ContaBancaria;
use App\Models\Caixa;
use App\Models\Cargo;
use App\Models\Cliente;
use App\Models\Configuracao;
use App\Models\Contrato;
use App\Models\Curso;
use App\Models\Departamento;
use App\Models\Documento;
use App\Models\Entidade;
use App\Models\Formador;
use App\Models\Funcionario;
use App\Models\Itens_venda;
use App\Models\Lote;
use App\Models\MotivoAusencia;
use App\Models\MotivoSaida;
use App\Models\OperacaoFinanceiro;
use App\Models\PacoteSalarial;
use App\Models\Produto;
use App\Models\Quarto;
use App\Models\Reserva;
use App\Models\Sala;
use App\Models\Tarefario;
use App\Models\TaxaIRT;
use App\Models\Turma;
use App\Models\Turno;
use App\Models\User;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     
    public function admin()
    {
        $user = auth()->user();
    
        if($user->level  == 2){
            $entidade = Entidade::whereIn('level', [2])->count();
        }else if($user->level == 3){
            $entidade = Entidade::whereIn('level', [1, 2, 3])->count();
        }
        
        $anuncios_total = AnuncioAdmin::count();
        
        $head = [
            "titulo" => "Dashboard",
            "descricao" => env('APP_NAME'),
            "entidade_total" => $entidade,
            "anuncios_total" => $anuncios_total,
        ];
                 
        return view('admin.dashboard', $head);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     
    public function painel_escolha()
    {
        $user = auth()->user();
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $head = [
            "titulo" => "Dashboard Painel",
            "descricao" => env('APP_NAME'),
            "entidade" => $entidade,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
        
                 
        return view('dashboard.dashboard-painel', $head);
    }
     
    public function gerar_licenca_configuracao()
    {
        $head = [
            "titulo" => "Gerar Licenças",
            "descricao" => env('APP_NAME'),
            "codigo" => null,
        ];
                 
        return view('admin.configuracao.licenca', $head);
    }
     
    public function gerar_licenca_configuracao_post(Request $request)
    {
        $request->validate([
            'data_inicio' => 'required',
            'dia_tempo' => 'required',
            'data_final' => 'required',
        ],[
            'data_inicio.required' => 'A data de inicio é obrigatório',
            // 'dia_tempo.required' => 'A data de inicio é obrigatório',
            'data_final.required' => 'A data final é obrigatório',
        ]);
    
        $head = [
            "titulo" => "Gerar Licenças",
            "descricao" => env('APP_NAME'),
            "codigo" => Crypt::encrypt($request->all()),
        ];
                 
        return view('admin.configuracao.licenca', $head);
        
    }     
     
    public function configuracao()
    {
        $head = [
            "titulo" => "Configuração",
            "descricao" => env('APP_NAME'),
        ];
                 
        return view('admin.configuracao.create', $head);
    }
  
  
     
    public function configuracao_post(Request $request)
    {
        
        $configuracao = Configuracao::first();
        
        if($configuracao){
            $update = Configuracao::findOrFail($configuracao->id);
            $update->limite_dias = $request->dias;
            $update->update();
        }else{
            $create = Configuracao::create([
                "limite_dias" => $request->dias
            ]);
            
            $create->save();
        }
                
        return redirect()->route('dashboard-admin')->with("success", "Dados Actualizados com Sucesso!");
        
    }     

    public function dashboard()
    {
        $user = auth()->user();
		
		if(!$user->can('painel bem-vindo')){
            return redirect()->route('pronto-venda');
        }
        
        $head = [
            "titulo" => "Dashboard",
            "descricao" => env('APP_NAME'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
                
        return view('dashboard.home', $head);
    }

    public function configuracao_operacoes()
    {
            
        $user = auth()->user();
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $caixas = Caixa::where('active', false)->where('status', 'fechado')->where('entidade_id', '=', $entidade->empresa->id)->get();
        $bancos = ContaBancaria::where('active', false)->where('status', 'fechado')->where('entidade_id', '=', $entidade->empresa->id)->get();
           
           
        $caixaActivo = Caixa::where([
            ['active', true],
            ['user_id', '=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->first();
        
        $bancoActivo = ContaBancaria::where([
            ['active', true],
            ['user_id', '=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->first();
        
        $head = [
            "titulo" => "Configuração",
            "descricao" => env('APP_NAME'),
            "caixas" => $caixas,
            "bancos" => $bancos,
            "caixaActivo" => $caixaActivo,
            "bancoActivo" => $bancoActivo,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
                
        return view('dashboard.configuracao', $head);
    }

    public function configuracao_inicializacao()
    {
           
        $user = auth()->user();
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $entid = Entidade::findOrFail($entidade->empresa->id);

        $status = "";

        if($entid->inicializacao == "N"){
            $status = "Y";
        }else if($entid->inicializacao == "Y"){
            $status = "N";
        }

        $entid->inicializacao = $status;
        $entid->update();

        return redirect()->back()->with("success", "Dados Actualizados com Sucesso!");
        
    }

    public function configuracao_finalizacao()
    {
           
        $user = auth()->user();
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $entid = Entidade::findOrFail($entidade->empresa->id);

        $status = "";

        if($entid->finalizacao == "N"){
            $status = "Y";
        }else if($entid->finalizacao == "Y"){
            $status = "N";
        }

        $entid->finalizacao = $status;
        $entid->update();

        return redirect()->back()->with("success", "Dados Actualizados com Sucesso!");
        
    }
    
    // DASHBOARD PRINCIPAL
    public function dashboardPrincipal(Request $request)
    {
        $user = auth()->user();

        if(!$user->can('painel principal')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $data = Carbon::now()->subDays(30)->toDateString(); // Data dos últimos 5 dias

        $produtosMaisVendidos = Itens_venda::whereDate('created_at', '>', $data)
            ->where('status', 'realizado')
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->with(['produto', 'factura'])
            ->whereHas('produto', function($query) {
                $query->where('tipo', 'P');
            })
            ->whereHas('factura', function($query) {
                $query->whereIn('status_factura', ['pago']);
            })
            ->select('produto_id', 
                DB::raw('SUM(quantidade) as total_quantidade'), 
                DB::raw('SUM(valor_pagar) as total_valor_pagar'), 
                DB::raw('AVG(preco_unitario) as media_preco_unitario')
            ) // Selecionar produto_id e outras colunas com funções de agregação
            ->having('total_quantidade', '>=', 1)
            ->groupBy('produto_id') // Agrupar por produto_id
            ->get();
            
        $SevicosMaisPrestados = Itens_venda::whereDate('created_at', '>', $data)
        ->where('status', 'realizado')
        ->where('entidade_id', '=', $entidade->empresa->id)
        ->with(['produto', 'factura'])
        ->whereHas('factura', function($query) {
            $query->whereIn('status_factura', ['pago']);
        })
        ->whereHas('produto', function($query) {
            $query->where('tipo', 'S');
        })
        ->select('produto_id', 
            DB::raw('SUM(quantidade) as total_quantidade'), 
            DB::raw('SUM(valor_pagar) as total_valor_pagar'), 
            DB::raw('AVG(preco_unitario) as media_preco_unitario')
        ) // Selecionar produto_id e outras colunas com funções de agregação
        ->having('total_quantidade', '>=', 1)
        ->groupBy('produto_id') // Agrupar por produto_id
        ->get();
      
      
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
        ->where('tipo', 'P')
        ->having('quantidade_sum_quantidade', '>=', 20) // Adicionando a condição de quantidade máxima permitida (<= 50)
        // ->groupBy('id', 'nome', 'referencia')
        ->orderBy('nome', 'asc')
        ->limit(5)
        ->get();
        
        
        $produtos_abaixo = Produto::when($request->nome_referencia, function($query, $value){
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
        ->having('quantidade_sum_quantidade', '<=', 20) // Adicionando a condição de quantidade máxima permitida (<= 50)
        // ->groupBy('id')
        ->orderBy('nome', 'asc')
        ->limit(5)
        ->get();
        
        $total_vendas = Venda::where('entidade_id', $entidade->empresa->id)->whereIn('status_factura', ['pago'])->sum('valor_total');
        
        $vendas = Venda::select(
            DB::raw('SUM(valor_total) as total_vendas'),
            DB::raw('SUM(quantidade) as total_quantidade')
        )
        ->where('entidade_id', $entidade->empresa->id)
        ->whereIn('status_factura', ['pago'])
        ->first();
        
        $total_estoque_activo = Lote::where("status", "activo")
            ->join('estoques', 'lotes_validade_produtos.id', '=', 'estoques.lote_id')
            ->where('estoques.entidade_id', $entidade->empresa->id)
            ->sum('estoques.stock');
        
        $total_estoque_expirado = Lote::where("status", "expirado")
            ->join('estoques', 'lotes_validade_produtos.id', '=', 'estoques.lote_id')
            ->where('estoques.entidade_id', $entidade->empresa->id)
            ->sum('estoques.stock');
            
        $agendas = Agendamento::with(['produto', 'user', 'cliente'])->where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->get();
        
        // Obter o total de agendamentos por status
        $totalCancelados = Agendamento::where('entidade_id', $entidade->empresa->id)
            ->where('status', 'cancelado')
            ->count();
        
        $totalExpirados = Agendamento::where('entidade_id', $entidade->empresa->id)
            ->where('status', 'expirado')
            ->count();
        
        $totalAtendidos = Agendamento::where('entidade_id', $entidade->empresa->id)
            ->where('status', 'atendido')
            ->count();
        
        $totalPendentes = Agendamento::where('entidade_id', $entidade->empresa->id)
            ->where('status', 'pendente')
            ->count();
            
        $totalCursos = Curso::where('entidade_id', $entidade->empresa->id)->count();
        $totalTurmas = Turma::where('entidade_id', $entidade->empresa->id)->count();
        $totalTurnos = Turno::where('entidade_id', $entidade->empresa->id)->count();
        $totalAlunos = Aluno::where('entidade_id', $entidade->empresa->id)->count();
        $totalFormador = Formador::where('entidade_id', $entidade->empresa->id)->count();
        $totalSolicitacao = Documento::where('entidade_id', $entidade->empresa->id)->count();
        $totalSalas = Sala::where('entidade_id', $entidade->empresa->id)->count();
        $totalAnoLectivo = AnoLectivo::where('entidade_id', $entidade->empresa->id)->count();
        $totalQuarto = Quarto::where('entidade_id', $entidade->empresa->id)->count();
        $totalCliente = Cliente::where('entidade_id', $entidade->empresa->id)->count();
        $totalReservas = Reserva::where('entidade_id', $entidade->empresa->id)->count();
        
        $totalReservasCheckOut = Reserva::where('data_final', "=", date("Y-m-d"))
            ->whereIn('status', ['SUCESSO', 'EM USO'])
            ->where('entidade_id', $entidade->empresa->id)
            ->count();
        
        $totalReservasCheckIn = Reserva::whereIn('status', ['PENDENTE', 'EM USO'])
            ->where('data_inicio', "=", date("Y-m-d"))
            ->where('entidade_id', $entidade->empresa->id)
            ->count();
        
        $totalTarifarios = Tarefario::where('entidade_id', $entidade->empresa->id)->count();
 

        $head = [
            "titulo" => "Dashboard Principal",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "produtos" => $produtos,
            "produtos_abaixo" => $produtos_abaixo,
            "produtosMaisVendidos" => $produtosMaisVendidos,
            "SevicosMaisPrestados" => $SevicosMaisPrestados,
            "total_produtos" => Produto::where('entidade_id', $entidade->empresa->id)->where('tipo', 'P')->count(),
            "total_servicos" => Produto::where('entidade_id', $entidade->empresa->id)->where('tipo', 'S')->count(),
            "total_estoque_activo" => $total_estoque_activo,
            "total_estoque_expirado" => $total_estoque_expirado,
            "total_vendas" => $total_vendas,
            "vendas" => $vendas,
            
            'total_cancelados' => $totalCancelados,
            'total_expirados' => $totalExpirados,
            'total_atendidos' => $totalAtendidos,
            'total_pendentes' => $totalPendentes,
            
            'total_cursos' => $totalCursos,
            'total_solicitacao' => $totalSolicitacao,
            'total_turmas' => $totalTurmas,
            'total_turnos' => $totalTurnos,
            'total_alunos' => $totalAlunos,
            'total_salas' => $totalSalas,
            'totalQuarto' => $totalQuarto,
            'total_anos_lectivos' => $totalAnoLectivo,
            'total_formadores' => $totalFormador,
            'totalCliente' => $totalCliente,
            'totalReservas' => $totalReservas,
            'totalReservasCheckOut' => $totalReservasCheckOut,
            'totalReservasCheckIn' => $totalReservasCheckIn,
            'totalTarifarios' => $totalTarifarios,
            
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
        

        return view('dashboard.dashboard', $head);
    }

    // DASHBOARD RECURSOS HUMANOS
    public function dashboardRecursoHumano(Request $request)
    {
        $user = auth()->user();

        if(!$user->can('painel financeiro')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        } 
    
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
      
        $total_receita = Venda::where('conta_movimento', 'receita')->where('entidade_id', $entidade->empresa->id)->where('status_factura', 'pago')->sum('valor_total');
        $total_dispesa = Venda::where('conta_movimento', 'dispesa')->where('entidade_id', $entidade->empresa->id)->where('status_factura', 'pago')->sum('valor_total');
        
        $total_funcionarios = Funcionario::where('entidade_id', $entidade->empresa->id)->count();
        $total_departamentos = Departamento::where('entidade_id', $entidade->empresa->id)->count();
        $total_cargos = Cargo::where('entidade_id', $entidade->empresa->id)->count();
        $total_contratos = Contrato::where('entidade_id', $entidade->empresa->id)->count();
        $total_contratos_renovados = Contrato::where('renovacoes_efectuadas', '!=', 0)->where('entidade_id', $entidade->empresa->id)->count();
        $total_pacotes = PacoteSalarial::where('entidade_id', $entidade->empresa->id)->count();
        $total_motivos_saidas = MotivoSaida::where('entidade_id', $entidade->empresa->id)->count();
        $total_motivos_ausencias = MotivoAusencia::where('entidade_id', $entidade->empresa->id)->count();
        
        $head = [
            "titulo" => "Dashboard Recursos Humanos",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "total_receita" => $total_receita,
            "total_dispesa" => $total_dispesa,
            
            "total_funcionarios" => $total_funcionarios,
            "total_departamentos" => $total_departamentos,
            "total_motivos_saidas" => $total_motivos_saidas,
            "total_motivos_ausencias" => $total_motivos_ausencias,
            "total_cargos" => $total_cargos,
            "total_contratos" => $total_contratos,
            "total_contratos_renovados" => $total_contratos_renovados,
            "total_pacotes" => $total_pacotes,
            
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
        

        return view('dashboard.recursos-humanos', $head);
    }

    // CONFIGURCAO RECURSOS HUMANOS
    public function configuracaoRecursoHumano(Request $request)
    {
        $user = auth()->user();

        if(!$user->can('painel financeiro')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        } 
    
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
      
        $total_receita = Venda::where('conta_movimento', 'receita')->where('entidade_id', $entidade->empresa->id)->where('status_factura', 'pago')->sum('valor_total');
        $total_dispesa = Venda::where('conta_movimento', 'dispesa')->where('entidade_id', $entidade->empresa->id)->where('status_factura', 'pago')->sum('valor_total');
        
        $total_funcionarios = Funcionario::where('entidade_id', $entidade->empresa->id)->count();
        $total_departamentos = Departamento::where('entidade_id', $entidade->empresa->id)->count();
        $total_cargos = Cargo::where('entidade_id', $entidade->empresa->id)->count();
        $total_contratos = Contrato::where('entidade_id', $entidade->empresa->id)->count();
        $total_contratos_renovados = Contrato::where('renovacoes_efectuadas', '!=', 0)->where('entidade_id', $entidade->empresa->id)->count();
        $total_pacotes = PacoteSalarial::where('entidade_id', $entidade->empresa->id)->count();
        $total_motivos_saidas = MotivoSaida::where('entidade_id', $entidade->empresa->id)->count();
        $total_motivos_ausencias = MotivoAusencia::where('entidade_id', $entidade->empresa->id)->count();
        
        $head = [
            "titulo" => "Dashboard Recursos Humanos",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "total_receita" => $total_receita,
            "total_dispesa" => $total_dispesa,
            
            "total_funcionarios" => $total_funcionarios,
            "total_departamentos" => $total_departamentos,
            "total_motivos_saidas" => $total_motivos_saidas,
            "total_motivos_ausencias" => $total_motivos_ausencias,
            "total_cargos" => $total_cargos,
            "total_contratos" => $total_contratos,
            "total_contratos_renovados" => $total_contratos_renovados,
            "total_pacotes" => $total_pacotes,
            
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
        

        return view('dashboard.configuracao-recursos-humanos', $head);
    }

    // DASHBOARD FINANCEIRO
    public function dashboardFinanceiro(Request $request)
    {
        $user = auth()->user();

        if(!$user->can('painel financeiro')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        } 
    
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $hoje = Carbon::now();

        // Contas a receber
        $contasReceberAtraso = OperacaoFinanceiro::where('type', 'R')
            ->where('status_pagamento', 'pendente')
            ->where('entidade_id', $entidade->empresa->id)
            ->where('date_at', '<=', $hoje)
            ->sum('motante');
            
        $contasReceberMes = OperacaoFinanceiro::where('type', 'R')
            ->where('status_pagamento', 'pendente')
            ->where('entidade_id', $entidade->empresa->id)
            ->whereMonth('date_at', $hoje->month)
            ->whereYear('date_at', $hoje->year)
            ->sum('motante');
            
        // Contas a pagar
        $contasPagarAtraso = OperacaoFinanceiro::where('type', 'D')
            ->where('status_pagamento', 'pendente')
            ->where('entidade_id', $entidade->empresa->id)
            ->where('date_at', '<=', $hoje) 
            ->sum('motante');
    
        $contasPagarMes = OperacaoFinanceiro::where('type', 'D')
            ->where('status_pagamento', 'pendente')
            ->where('entidade_id', $entidade->empresa->id)
            ->whereMonth('date_at', $hoje->month)
            ->whereYear('date_at', $hoje->year)
            ->sum('motante');
               
        // Saldo atual
        $receitasPagas = OperacaoFinanceiro::where('type', 'R')->where('entidade_id', $entidade->empresa->id)->where('status_pagamento', 'pago')->sum('motante');
        $despesasPagas = OperacaoFinanceiro::where('type', 'D')->where('entidade_id', $entidade->empresa->id)->where('status_pagamento', 'pago')->sum('motante');
        $saldoAtual = $receitasPagas - $despesasPagas;
    
      
        // $total_receita = Venda::where('conta_movimento', 'receita')->where('entidade_id', $entidade->empresa->id)->where('status_factura', 'pago')->sum('valor_total');
        // $total_dispesa = Venda::where('conta_movimento', 'dispesa')->where('entidade_id', $entidade->empresa->id)->where('status_factura', 'pago')->sum('valor_total');
        
        $head = [
            "titulo" => "Dashboard Financeiro",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "contasReceberAtraso" => $contasReceberAtraso,
            "contasReceberMes" => $contasReceberMes,
            "contasPagarAtraso" => $contasPagarAtraso,
            "contasPagarMes" => $contasPagarMes,
            "receitasPagas" => $receitasPagas,
            "despesasPagas" => $despesasPagas,
            "saldoAtual" => $saldoAtual,
            
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
        

        return view('dashboard.financeiro', $head);
    }

    // DASHBOARD FINANCEIRO
    public function taxa_irt(Request $request)
    {
        $user = auth()->user();
    
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
      
        $taxas = TaxaIRT::paginate(14);
        
        $head = [
            "titulo" => "Dashboard Financeiro",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "taxas" => $taxas,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
        

        return view('dashboard.taxas_irt', $head);
    }

}
