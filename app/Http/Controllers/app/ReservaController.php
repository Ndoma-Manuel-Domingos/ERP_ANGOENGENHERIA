<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Models\ContaBancaria;
use App\Models\Caixa;
use App\Models\Cliente;
use App\Models\Exercicio;
use App\Models\OperacaoFinanceiro;
use App\Models\Reserva;
use App\Models\Periodo;
use App\Models\Quarto;
use App\Models\Subconta;
use App\Models\Tarefario;
use App\Models\TipoPagamento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ReservaController extends Controller
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

        if (!$user->can('listar reserva')) {
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

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

        $clientes = Cliente::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito', 'reservas'])
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $quartos = Quarto::where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $head = [
            "titulo" => "Reservas",
            "descricao" => env('APP_NAME'),
            "quartos" => $quartos,
            "reservas" => $reservas,
            "clientes" => $clientes,
            "requests" => $request->all('hora_entrada', 'hora_saida', 'data_inicio', 'data_final', 'cliente_id', 'status_reserva', 'status_pagamento', 'quarto_id'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.reservas.index', $head);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function diario_check(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('listar reserva')) {
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

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
        ->where('data_final', "=", date("Y-m-d"))
        ->whereIn('status', ['SUCESSO', 'EM USO'])
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
        

        $clientes = Cliente::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito', 'reservas'])
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $quartos = Quarto::where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $head = [
            "titulo" => "Check Out Diários",
            "descricao" => env('APP_NAME'),
            "quartos" => $quartos,
            "reservas" => $reservas,
            "clientes" => $clientes,
            "requests" => $request->all('hora_entrada', 'hora_saida', 'data_inicio', 'data_final', 'cliente_id', 'status_reserva', 'status_pagamento', 'quarto_id'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.reservas.chek-out', $head);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function diario_check_in(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('listar reserva')) {
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

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
        ->where('data_inicio', "=", date("Y-m-d"))
        ->whereIn('status', ['PENDENTE', 'EM USO'])
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


        $clientes = Cliente::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito', 'reservas'])
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $quartos = Quarto::where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $head = [
            "titulo" => "Check In Diários",
            "descricao" => env('APP_NAME'),
            "quartos" => $quartos,
            "reservas" => $reservas,
            "clientes" => $clientes,
            "requests" => $request->all('hora_entrada', 'hora_saida', 'data_inicio', 'data_final', 'cliente_id', 'status_reserva', 'status_pagamento', 'quarto_id'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.reservas.chek-in', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pagamento(Request $request, $id)
    {
        //
        $user = auth()->user();

        if (!$user->can('criar reserva')) {
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $reserva = Reserva::findOrFail($id);

        $clientes = Cliente::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito', 'reservas'])
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $exercicios = Exercicio::where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $tarefarios = Tarefario::where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('id', 'asc')
            ->get();

        $bancos = ContaBancaria::where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('id', 'asc')
            ->get();

        $caixas = Caixa::where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('id', 'asc')
            ->get();

        $quartos = Quarto::where('entidade_id', '=', $entidade->empresa->id)->get();

        $forma_pagamentos = TipoPagamento::get();

        $head = [
            "titulo" => "Fazer Pagamento da Reservação",
            "descricao" => env('APP_NAME'),
            "exercicios" => $exercicios,
            "bancos" => $bancos,
            "caixas" => $caixas,
            "quartos" => $quartos,
            "tarefarios" => $tarefarios,
            "forma_pagamentos" => $forma_pagamentos,
            "clientes" => $clientes,
            "reserva" => $reserva,
            "requests" => $request->all('quarto_id'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.reservas.fazer-pagamento', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pagamento_store(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('criar reserva')) {
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'reserva_id' => 'required|string',
            'actualizar_check_in' => 'required|string',
        ], [
            'reserva_id.required' => 'A reserva é um campo obrigatório',
            'actualizar_check_in.required' => 'O campo actualizar check in é obrigatório',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            $reserva = Reserva::findOrFail($request->reserva_id);
            
            if($request->actualizar_check_in == "sim"){
    
                $reserva->user_check_in = $user->id;
                $reserva->data_check_in = date("Y-m-d");
                $reserva->hora_check_in = date("h:i:s");
                $reserva->check = 'IN';
                $reserva->status = 'EM USO';
        
                $quarto = Quarto::findOrFail($reserva->quarto_id);
                $quarto->solicitar_ocupacao = "OCUPADA";
                $quarto->update();
        
                $reserva->update();
            }

            $code = uniqid(time());
                                
            if ($request->forma_pagamento_id == "NU") {
                if ($request->caixa_id == "") {
                    return redirect()->back()->with('danger', 'Deves selecionar o caixa onde será retirado o valor para o pagamento da factura!');
                }

                $subconta_caixa = Subconta::where('code', $request->caixa_id)->first();
                $subconta_id = $subconta_caixa->id;
                
                OperacaoFinanceiro::create([
                    'nome' => "PAGAMENTO DA RESERVA DO QUARTO",
                    'status' => "pago",
                    'formas' => "C",
                    'motante' => $request->valor_entregue,
                    'subconta_id' => $subconta_caixa->id,
                    'fornecedor_id' => $request->cliente_id,
                    'model_id' => 3,
                    'type' => "R",
                    'parcelado' => "N",
                    'status_pagamento' => "pago",
                    'data_recebimento' => date("Y-m-d"),
                    'code' => $code,
                    'descricao' => "PAGAMENTO DA RESERVA DO QUARTO",
                    'movimento' => "E",
                    'date_at' => date("Y-m-d"),
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
                
            }

            if ($request->forma_pagamento_id == "MB") {
                if ($request->banco_id == "") {
                    return redirect()->back()->with('danger', 'Deves selecionar o banco onde será retirado o valor para o pagamento da factura!');
                }

                $subconta_banco = Subconta::where('code', $request->banco_id)->first();
                $subconta_id = $subconta_banco->id;
                
                OperacaoFinanceiro::create([
                    'nome' => "PAGAMENTO DA RESERVA DO QUARTO",
                    'status' => "pago",
                    'formas' => "B",
                    'motante' => $request->valor_entregue,
                    'subconta_id' => $subconta_banco->id,
                    'fornecedor_id' => $request->cliente_id,
                    'model_id' => 3,
                    'type' => "R",
                    'parcelado' => "N",
                    'status_pagamento' => "pago",
                    'data_recebimento' => date("Y-m-d"),
                    'code' => $code,
                    'descricao' => "PAGAMENTO DA RESERVA DO QUARTO",
                    'movimento' => "E",
                    'date_at' => date("Y-m-d"),
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
            }
                      
            if(($request->valor_entregue + $reserva->valor_pago) >= $reserva->valor_total){
                $status = "EFECTUADO";
                $reserva->valor_divida = 0;
            }else {
                $status = "NAO EFECTUADO";
                $reserva->valor_divida = $reserva->valor_pago - $request->valor_entregue;
            }
       
            $reserva->valor_total = $request->valor_entregue;
            $reserva->observacao = $request->observacao;
            $reserva->pagamento = $status;
            $reserva->subconta_id = $subconta_id;
            
            $reserva->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return redirect()->route('reserva.index')->with("success", "Dados Cadastrar com Sucesso!");
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

        if (!$user->can('criar reserva')) {
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);


        $clientes = Cliente::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito', 'reservas'])
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $exercicios = Exercicio::where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $periodos = Periodo::where('exercicio_id', $this->exercicio())->where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $tarefarios = Tarefario::where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('id', 'asc')
            ->get();

        $bancos = ContaBancaria::where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('id', 'asc')
            ->get();

        $caixas = Caixa::where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('id', 'asc')
            ->get();

        $quartos = Quarto::where('entidade_id', '=', $entidade->empresa->id)
            ->where('solicitar_ocupacao', 'LIVRE')
            ->get();

        $forma_pagamentos = TipoPagamento::get();

        $head = [
            "titulo" => "Fazer nova reserva",
            "descricao" => env('APP_NAME'),
            "exercicios" => $exercicios,
            "bancos" => $bancos,
            "caixas" => $caixas,
            "quartos" => $quartos,
            "tarefarios" => $tarefarios,
            "periodos" => $periodos,
            "forma_pagamentos" => $forma_pagamentos,
            "clientes" => $clientes,
            "requests" => $request->all('quarto_id'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.reservas.create', $head);
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

        if (!$user->can('criar reserva')) {
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'cliente_id' => 'required|string',
            'quarto_id' => 'required|string',
        ], [
            'cliente_id.required' => 'O cliente é um campo obrigatório',
            'quarto_id.required' => 'O quarto é um campo obrigatório',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

            $dataAtual = Carbon::now()->format('Y-m-d');
            $divida = 0;
            $valor_pago = 0;
            $valor_troco = 0;
            $subconta_id = NULL;
            $valor_a_pagar = 0;
            
            $code = uniqid(time());

            if ($request->marcar_como == "sim") {
                if ($request->forma_pagamento_id == "") {
                    return redirect()->back()->with('danger', 'Deves selecionar uma forma de pagamento da factura!');
                }
                                
                if($request->valor_entregue >= $request->total_factura){
                    $divida = 0;
                    $valor_a_pagar = $request->total_factura;
                }else {
                    $divida = $request->total_factura - $request->valor_entregue;
                    $valor_a_pagar = $request->valor_entregue;
                }
                
                $valor_pago = $request->valor_entregue - $request->total_factura;
                $valor_troco = $request->valor_entregue - $request->total_factura;

                if ($request->forma_pagamento_id == "NU") {
                    if ($request->caixa_id == "") {
                        return redirect()->back()->with('danger', 'Deves selecionar o caixa onde será retirado o valor para o pagamento da factura!');
                    }

                    $subconta_caixa = Subconta::where('code', $request->caixa_id)->first();
                    $subconta_id = $subconta_caixa->id;
                    
                    OperacaoFinanceiro::create([
                        'nome' => "PAGAMENTO DA RESERVA DO QUARTO",
                        'status' => "pago",
                        'formas' => "C",
                        'motante' => $valor_a_pagar,
                        'subconta_id' => $subconta_caixa->id,
                        'fornecedor_id' => $request->cliente_id,
                        'model_id' => 3,
                        'type' => "R",
                        'parcelado' => "N",
                        'status_pagamento' => "pago",
                        'data_recebimento' => date("Y-m-d"),
                        'code' => $code,
                        'descricao' => "PAGAMENTO DA RESERVA DO QUARTO",
                        'movimento' => "E",
                        'date_at' => date("Y-m-d"),
                        'user_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                    
                }

                if ($request->forma_pagamento_id == "MB") {
                    if ($request->banco_id == "") {
                        return redirect()->back()->with('danger', 'Deves selecionar o banco onde será retirado o valor para o pagamento da factura!');
                    }

                    $subconta_banco = Subconta::where('code', $request->banco_id)->first();
                    $subconta_id = $subconta_banco->id;
                    
                    OperacaoFinanceiro::create([
                        'nome' => "PAGAMENTO DA RESERVA DO QUARTO",
                        'status' => "pago",
                        'formas' => "B",
                        'motante' => $valor_a_pagar,
                        'subconta_id' => $subconta_banco->id,
                        'fornecedor_id' => $request->cliente_id,
                        'model_id' => 3,
                        'type' => "R",
                        'parcelado' => "N",
                        'status_pagamento' => "pago",
                        'data_recebimento' => date("Y-m-d"),
                        'code' => $code,
                        'descricao' => "PAGAMENTO DA RESERVA DO QUARTO",
                        'movimento' => "E",
                        'date_at' => date("Y-m-d"),
                        'user_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                }
                
                $pagamento = 'EFECTUADO';

                
            }else {
                $divida = $request->total_factura;
                $pagamento = 'NAO EFECTUADO';
                $valor_pago = 0;
                $valor_troco = 0;
                
                $subconta_id = NULL;
            }

            $reserva = Reserva::create([
                'valor_unitario' => $request->preco_unitario,
                'valor_total' => $request->total_factura,
                'valor_pago' => $valor_pago,
                'valor_divida' =>  $divida,
                'valor_troco' => $valor_troco,
                'valor_retencao_fonte' => $request->total_factura * (6.5 / 100),
                'tarefario_id' => $request->tarefario_id,
                'total_pessoas' => $request->total_pessoas,
                'subconta_id' => $subconta_id,
            
                'hora_entrada' => $request->hora_entrada,
                'hora_saida' => $request->hora_saida,
            
                'data_inicio' => $request->data_entrada,
                'data_final' => $request->data_saida,
                'data_registro' => $dataAtual,
                'total_dias' => $request->total_dias,
                'code' => $code,
                'cliente_id' => $request->cliente_id,
                'status' => "PENDENTE",
                'quarto_id' => $request->quarto_id,
                'exercicio_id' => $request->exercicio_id,
                'periodo_id' => $request->periodo_id,
                'pagamento' => $pagamento,
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);

            $quarto = Quarto::findOrFail($request->quarto_id);
            $quarto->solicitar_ocupacao = "RESERVADA";
            $quarto->code = $code;
            $quarto->update();

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = auth()->user();

        if (!$user->can('listar reserva')) {
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $reserva = Reserva::with([
            'subconta', 
            'tarefario',
            'quarto',
            'exercicio',
            'periodo',
            'user_ckeck_in',
            'user_ckeck_out',
            'cliente.estado_civil',
            'cliente.seguradora',
            'cliente.provincia',
            'cliente.municipio',
            'cliente.distrito'
        ])->findOrFail($id);

        $head = [
            "titulo" => "Detalhes da Reserva",
            "descricao" => env('APP_NAME'),
            "reserva" => $reserva,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.reservas.show', $head);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function anulacao($id)
    {
        $user = auth()->user();

        if (!$user->can('listar quarto')) {
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $reserva = Reserva::findOrFail($id);
        
        $code = uniqid(time());
        
        if($reserva->pagamento == "EFECTUADO"){
            $subconta = Subconta::where('code', $reserva->subconta_id)->first();
            OperacaoFinanceiro::create([
                'nome' => "REEMBOLSO DOS VALORES DA RESERVADA",
                'status' => "pago",
                'formas' => "C",
                'motante' => $reserva->valor_total,
                'subconta_id' => $subconta->id,
                'fornecedor_id' => $reserva->cliente_id,
                'model_id' => 14,
                'type' => "D",
                'parcelado' => "N",
                'status_pagamento' => "pago",
                'data_recebimento' => date("Y-m-d"),
                'code' => $code,
                'descricao' => "REEMBOLSO DOS VALORES DA RESERVADA",
                'movimento' => "S",
                'date_at' => date("Y-m-d"),
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);
        }
        
        $reserva->status = 'CANCELADO';
        
        $quarto = Quarto::findOrFail($reserva->quarto_id);
        $quarto->solicitar_ocupacao = "LIVRE";
        $quarto->code = NULL;
        $quarto->update();
        $reserva->update();

        return redirect()->back()->with("success", "Reserva Anulada com sucesso!");
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function check_in($id)
    {
        $user = auth()->user();

        if (!$user->can('listar quarto')) {
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $reserva = Reserva::findOrFail($id);

        if($reserva->data_inicio != date("Y-m-d")){
            return redirect()->back()->with('danger', "Por favor, verifique a data de início da hospedagem do cliente registrada na reserva. A data de entrada informada na reserva não corresponde a hoje, mas sim a: {$reserva->data_inicio}!");
        }

        $reserva->user_check_in = $user->id;
        $reserva->data_check_in = date("Y-m-d");
        $reserva->hora_check_in = date("h:i:s");
        $reserva->check = 'IN';
        $reserva->status = 'EM USO';

        $quarto = Quarto::findOrFail($reserva->quarto_id);
        $quarto->solicitar_ocupacao = "OCUPADA";
        $quarto->update();

        $reserva->update();

        return redirect()->back()->with("success", "Check In realizado com sucesso!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function check_out($id)
    {
        $user = auth()->user();

        if (!$user->can('criar reserva')) {
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $reserva = Reserva::findOrFail($id);
        
        if($reserva->data_inicio != date("Y-m-d")){
            return redirect()->back()->with('danger', "Por favor, verifique a data da saída do cliente na hospedagem registrada na reserva. A data de saída informada na reserva não corresponde a hoje, mas sim a: {$reserva->data_final}!");
        }

        $reserva->user_check_out = $user->id;
        $reserva->data_check_out = date("Y-m-d");
        $reserva->hora_check_out = date("h:i:s");
        $reserva->check = 'OUT';
        $reserva->status = 'SUCESSO';

        $quarto = Quarto::findOrFail($reserva->quarto_id);
        $quarto->solicitar_ocupacao = "LIVRE";
        $quarto->code = NULL;
        $quarto->update();

        $reserva->update();

        return redirect()->back()->with("success", "Check Out realizado com sucesso!!");
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = auth()->user();

        if (!$user->can('editar reserva')) {
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $reserva = Reserva::findOrFail($id);

        $clientes = Cliente::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito', 'reservas'])
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $exercicios = Exercicio::where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $periodos = Periodo::where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $quartos = Quarto::where('entidade_id', '=', $entidade->empresa->id)
            ->whereIn('solicitar_ocupacao', ['LIVRE', 'RESERVADA'])
            ->get();
            
        $tarefarios = Tarefario::where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('id', 'asc')
            ->get();

        $quartos = Quarto::where('entidade_id', '=', $entidade->empresa->id)
            ->where('solicitar_ocupacao', 'LIVRE')
            ->get();

        $forma_pagamentos = TipoPagamento::get();

        $head = [
            "titulo" => "Editar reserva",
            "descricao" => env('APP_NAME'),
            "reserva" => $reserva,
            "exercicios" => $exercicios,
            "quartos" => $quartos,
            "tarefarios" => $tarefarios,
            "quartos" => $quartos,
            "forma_pagamentos" => $forma_pagamentos,
            "periodos" => $periodos,
            "clientes" => $clientes,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.reservas.edit', $head);
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

        if (!$user->can('editar reserva')) {
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'cliente_id' => 'required|string',
            'quarto_id' => 'required|string',
        ], [
            'cliente_id.required' => 'O cliente é um campo obrigatório',
            'quarto_id.required' => 'O quarto é um campo obrigatório',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

            $reserva = Reserva::findOrFail($id);
            $code = uniqid(time());

            $quarto = Quarto::findOrFail($reserva->quarto_id);
            $quarto->solicitar_ocupacao = "LIVRE";
            $quarto->code = "NULL";
            $quarto->update();
         
            $reserva->valor_divida = $request->total_factura;
            $reserva->valor_pago = 0;
            $reserva->valor_troco = 0;
            $reserva->valor_total = $request->total_factura;
            $reserva->valor_unitario = $request->preco_unitario;
            $reserva->valor_retencao_fonte = $request->total_factura * (6.5 / 100);
            $reserva->tarefario_id = $request->tarefario_id;
            $reserva->data_inicio = $request->data_entrada;
            $reserva->data_final = $request->data_saida;
            $reserva->hora_entrada = $request->hora_entrada;
            $reserva->hora_saida = $request->hora_saida;
            $reserva->total_dias = $request->total_dias;
            $reserva->code = $code;
            $reserva->cliente_id = $request->cliente_id;
            $reserva->quarto_id = $request->quarto_id;
            $reserva->exercicio_id = $request->exercicio_id;
            $reserva->periodo_id = $request->periodo_id;
            $reserva->pagamento = "NAO EFECTUADO";
            $reserva->user_id = Auth::user()->id;
            $reserva->entidade_id = $entidade->empresa->id;
            $reserva->update();

            $quarto = Quarto::findOrFail($request->quarto_id);
            $quarto->solicitar_ocupacao = "RESERVADA";
            $quarto->code = $code;
            $quarto->update();


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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = auth()->user();

        if (!$user->can('eliminar reserva')) {
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $reserva = Reserva::findOrFail($id);
            $reserva->delete();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        return redirect()->route('reservas.index')->with("success", "Dados Excluído com Sucesso!");
    }
}
