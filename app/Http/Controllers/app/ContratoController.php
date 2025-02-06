<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use App\Models\CategoriaCargo;
use App\Models\Contrato;
use App\Models\Departamento;
use App\Models\Desconto;
use App\Models\DescontoContrato;
use App\Models\Funcionario;
use App\Models\PacoteSalarial;
use App\Models\Subsidio;
use App\Models\SubsidioContrato;
use App\Models\TipoContrato;
use App\Models\TipoPagamento;
use App\Models\TipoProcessamento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ContratoController extends Controller
{
    //
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $user = auth()->user();

        if(!$user->can('listar contrato')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        // Data atual
        $dataAtual = Carbon::now()->format('Y-m-d');  
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        // vamos desactivar automaticamente dos os contratos expirados
        $contratos_expirados = Contrato::with(['categoria', 'subsidios_contrato', 'descontos_contrato', 'funcionario', 'cargo', 'tipo_contrato', 'user', 'forma_pagamento'])->where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])
        ->whereDate('data_final', '<=', $dataAtual)
        ->orderBy('created_at', 'desc')
        ->get();
        
        
        foreach($contratos_expirados as $expirado){
            $update = Contrato::findOrFail($expirado->id);
            $update->status = 'desactivo';
            $update->update();
        }
        
        $contratos = Contrato::with(['categoria', 'subsidios_contrato', 'descontos_contrato', 'funcionario', 'cargo', 'tipo_contrato', 'user', 'forma_pagamento'])->where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->orderBy('created_at', 'desc')->get();
        
        
        $head = [
            "titulo" => "Contratos",
            "descricao" => env('APP_NAME'),
            "contratos" => $contratos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contratos.index', $head);
    }

        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $user = auth()->user();
        
        if(!$user->can('criar contrato')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $tipos_contratos = TipoContrato::where('entidade_id', $entidade->empresa->id)->get();
        $cargos = Cargo::where('entidade_id', $entidade->empresa->id)->get();
        $funcionarios = Funcionario::where('entidade_id', $entidade->empresa->id)->get();
        $categorias = CategoriaCargo::where('entidade_id', $entidade->empresa->id)->get();
        $forma_pagamentos = TipoPagamento::get();
        
        $subsidios = Subsidio::where('entidade_id', $entidade->empresa->id)->get();
        $descontos = Desconto::where('entidade_id', $entidade->empresa->id)->get();
        $processamentos = TipoProcessamento::where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Cadastrar Contrato",
            "descricao" => env('APP_NAME'),
            "tipos_contratos" => $tipos_contratos,
            "cargos" => $cargos,
            "funcionarios" => $funcionarios,
            "categorias" => $categorias,
            "forma_pagamentos" => $forma_pagamentos,
            "subsidios" => $subsidios,
            "descontos" => $descontos,
            "processamentos" => $processamentos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contratos.create', $head);
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
        
        if(!$user->can('criar contrato')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'funcionario_id'  => 'required|string',
            'cargo_id'  => 'required|string',
            'categoria_id'  => 'required|string',
            'tipo_contrato_id'  => 'required|string',
            'data_inicio'  => 'required|string',
            'data_final'  => 'required|string',
            'hora_entrada'  => 'required|string',
            'hora_saida'  => 'required|string',
            'status'  => 'required|string',
        ],[
            'funcionario_id.required'  => 'O funcionário é um campo obrigatório',
            'cargo_id.required'  => 'O cargo é um campo obrigatório',
            'categoria_id.required'  => 'A categoria é um campo obrigatório',
            'tipo_contrato_id.required'  => 'O tipo de contrato é um campo obrigatório',
            'data_inicio.required'  => 'A data de início é um campo obrigatório',
            'data_final.required'  => 'A data final é um campo obrigatório',
            'hora_entrada.required'  => 'A hora de entrada é um campo obrigatório',
            'hora_saida.required'  => 'A hora da saída é um campo obrigatório',
            'status.required'  => 'O estado é um campo obrigatório',
        ]);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            // $verificar_pacote = PacoteSalarial::where('entidade_id', $entidade->empresa->id)->where('cargo_id', $request->cargo_id)->where('categoria_id', $request->categoria_id)->first();
            
            // if(!$verificar_pacote){
            //     return redirect()->back()->with("danger", "Por Favor, primeiramente deves cadastrar uma pacote salarial que seja compactível com o cargo e a categoria deste funcionário!");
            // }
            
            $total_contratos = Contrato::where('entidade_id', $entidade->empresa->id)->count();
            
            $verificar_contrato = Contrato::where('entidade_id', $entidade->empresa->id)->where('funcionario_id', $request->funcionario_id)->first();
            
            if(!$verificar_contrato){
                
                $dataInicio = Carbon::parse($request->input('data_inicio'));
                $dataFinal = Carbon::parse($request->input('data_final'));
                    
                // Calcular a diferença em meses
                $diferencaMeses = $dataInicio->diffInMonths($dataFinal);
            
                $create = Contrato::create([
                    'status' => $request->status,
                    'renovacoes_efectuadas' => 0,
                    
                    'antiguidade' => $diferencaMeses,
                    'duracao_renovacao' => 0,
                    
                    'user_id' => Auth::user()->id,
                    'funcionario_id' => $request->funcionario_id,
                    'categoria_id' => $request->categoria_id,
                    'cargo_id' => $request->cargo_id,
                    'tipo_contrato_id' => $request->tipo_contrato_id,
                    
                    'forma_pagamento_id' => $request->forma_pagamento_id,
                    'hora_entrada' => $request->hora_entrada,
                    'hora_saida' => $request->hora_saida,
                    'data_inicio' => $request->data_inicio,
                    'data_final' => $request->data_final,
                    'data_envio_previo' => $request->data_envio_previo,
                    'data_demissao' => $request->data_demissao,
                    'entidade_id' => $entidade->empresa->id,
                    
                    //'pacote_salarial_id' => $verificar_pacote->id,
                    
                    'salario_base' => $request->salario_base,
                    
                    'dias_processamento' => $request->dias_processamento,
                    'subsidio_natal' => $request->subsidio_natal,
                    'forma_pagamento_natal' => $request->forma_pagamento_natal,
                    'mes_pagamento_natal' => $request->mes_pagamento_natal,
                    'subsidio_ferias' => $request->subsidio_ferias,
                    'forma_pagamento_ferias' => $request->forma_pagamento_ferias,
                    'mes_pagamento_ferias' => $request->mes_pagamento_ferias,
                    
                ]);
                
                $create->numero = "CONTR Nº 00" . $total_contratos+1;
                $create->save();
                            
                foreach ($request->subsidio_id as $index => $subsidioId) {
                    SubsidioContrato::create([
                        'subsidio_id' => $subsidioId,
                        'contrato_id' => $create->id,
                        'salario' => $request->salario_subsidio[$index],
                        'processamento_id' => $request->processamento_id[$index],
                        'limite_isencao' => $request->limite_isencao[$index],
                        'irt' => $request->irt[$index],
                        'inss' => $request->inss[$index],
                        'user_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                }
        
                foreach ($request->desconto_id as $index => $descontoId) {
                    DescontoContrato::create([
                        'desconto_id' => $descontoId,
                        'contrato_id' => $create->id,
                        'salario' => $request->salario_desconto[$index],
                        'processamento_id' => $request->processamento_desconto_id[$index],
                        'tipo_valor' => $request->tipo_valor[$index],
                        'irt' => $request->irt_desconto[$index],
                        'inss' => $request->inss_desconto[$index],
                        'user_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                }
            }else {
                return redirect()->back()->with("warning", "Este Funcionário Já tem um contrato assinado!");
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


      
        return response()->json(['success' => true, 'message' => "Dados salvos com sucesso!"], 200);
    
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
        
        if(!$user->can('listar contrato')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $contrato = Contrato::with(['categoria','subsidios_contrato.processamento', 'subsidios_contrato.subsidio', 'descontos_contrato.processamento', 'descontos_contrato.desconto', 'funcionario', 'cargo', 'tipo_contrato', 'user', 'forma_pagamento'])->findOrFail($id);
        
        $head = [
            "titulo" => "Detalhe Contrato",
            "descricao" => env('APP_NAME'),
            "contrato" => $contrato,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contratos.show', $head);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = auth()->user();
        
        if(!$user->can('editar contrato')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $contrato = Contrato::with(['categoria', 'subsidios_contrato.subsidio', 'descontos_contrato.desconto', 'funcionario', 'cargo', 'tipo_contrato', 'user'])->findOrFail($id);
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $departamentos = Departamento::where('entidade_id', $entidade->empresa->id)->get();
        $tipos_contratos = TipoContrato::where('entidade_id', $entidade->empresa->id)->get();
        $cargos = Cargo::where('entidade_id', $entidade->empresa->id)->get();
        $funcionarios = Funcionario::where('entidade_id', $entidade->empresa->id)->get();
        
        $categorias = CategoriaCargo::where('entidade_id', $entidade->empresa->id)->get();
        
        $subsidios = Subsidio::where('entidade_id', $entidade->empresa->id)->get();
        $descontos = Desconto::where('entidade_id', $entidade->empresa->id)->get();
        $processamentos = TipoProcessamento::where('entidade_id', $entidade->empresa->id)->get();
        

        $forma_pagamentos = TipoPagamento::get();
        
        $head = [
            "titulo" => "Editar Contrato",
            "departamentos" => $departamentos,
            "tipos_contratos" => $tipos_contratos,
            "forma_pagamentos" => $forma_pagamentos,
            "cargos" => $cargos,
            "categorias" => $categorias,
            "funcionarios" => $funcionarios,
            "subsidios" => $subsidios,
            "descontos" => $descontos,
            "processamentos" => $processamentos,
            "descricao" => env('APP_NAME'),
            "contrato" => $contrato,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contratos.edit', $head);
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
        $user = auth()->user();
        
        if(!$user->can('editar contrato')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
              
        $request->validate([
            'funcionario_id'  => 'required|string',
            'cargo_id'  => 'required|string',
            'categoria_id'  => 'required|string',
            'tipo_contrato_id'  => 'required|string',
            'data_inicio'  => 'required|string',
            'data_final'  => 'required|string',
            'hora_entrada'  => 'required|string',
            'hora_saida'  => 'required|string',
            'status'  => 'required|string',
        ],[
            'funcionario_id.required'  => 'O funcionário é um campo obrigatório',
            'cargo_id.required'  => 'O cargo é um campo obrigatório',
            'categoria_id.required'  => 'A categoria é um campo obrigatório',
            'tipo_contrato_id.required'  => 'O tipo de contrato é um campo obrigatório',
            'data_inicio.required'  => 'A data de início é um campo obrigatório',
            'data_final.required'  => 'A data final é um campo obrigatório',
            'hora_entrada.required'  => 'A hora de entrada é um campo obrigatório',
            'hora_saida.required'  => 'A hora da saída é um campo obrigatório',
            'status.required'  => 'O estado é um campo obrigatório',
        ]);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
                       
            // $verificar_pacote = PacoteSalarial::where('entidade_id', $entidade->empresa->id)->where('cargo_id', $request->cargo_id)->where('categoria_id', $request->categoria_id)->first();
            
            // if(!$verificar_pacote){
            //     return redirect()->back()->with("danger", "Por Favor, primeiramente deves cadastrar uma pacote salarial que seja compactível com o cargo e a categoria deste funcionário!");
            // }
    
            $contrato = Contrato::findOrFail($id);
            
            $contrato->status = $request->status;
            
            $contrato->funcionario_id = $request->funcionario_id;
            $contrato->categoria_id = $request->categoria_id;
            $contrato->cargo_id = $request->cargo_id;
            $contrato->tipo_contrato_id = $request->tipo_contrato_id;
            $contrato->forma_pagamento_id = $request->forma_pagamento_id;
            $contrato->hora_entrada = $request->hora_entrada;
            $contrato->hora_saida = $request->hora_saida;
            $contrato->data_inicio = $request->data_inicio;
            $contrato->data_final = $request->data_final;
            
            $contrato->data_envio_previo = $request->data_envio_previo;
            $contrato->data_demissao = $request->data_demissao;
            
            //$contrato->pacote_salarial_id = $verificar_pacote->id;
            $contrato->salario_base = $request->salario_base;
            
            $contrato->dias_processamento = $request->dias_processamento;
            $contrato->subsidio_natal = $request->subsidio_natal;
            $contrato->forma_pagamento_natal = $request->forma_pagamento_natal;
            $contrato->mes_pagamento_natal = $request->mes_pagamento_natal;
            $contrato->subsidio_ferias = $request->subsidio_ferias;
            $contrato->forma_pagamento_ferias = $request->forma_pagamento_ferias;
            $contrato->mes_pagamento_ferias = $request->mes_pagamento_ferias;
            
            $contrato->update();

            // Deletar os registros atuais do funcionário para recriar os novos
            SubsidioContrato::where('contrato_id', $contrato->id)->delete();
            DescontoContrato::where('contrato_id', $contrato->id)->delete();
            
            // Recriar os registros com os novos dados
            foreach ($request->subsidio_id as $index => $subsidioId) {
                SubsidioContrato::create([
                    'subsidio_id' => $subsidioId,
                    'contrato_id' => $contrato->id,
                    'salario' => $request->salario_subsidio[$index],
                    'processamento_id' => $request->processamento_id[$index],
                    'inss' => $request->inss[$index],
                    'irt' => $request->irt[$index],
                    'limite_isencao' => $request->limite_isencao[$index],
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
            }
            
            foreach ($request->desconto_id as $index => $descontoId) {
                DescontoContrato::create([
                    'desconto_id' => $descontoId,
                    'contrato_id' => $contrato->id,
                    'salario' => $request->salario_desconto[$index],
                    'processamento_id' => $request->processamento_desconto_id[$index],
                    'tipo_valor' => $request->tipo_valor[$index],
                    'irt' => $request->irt_desconto[$index],
                    'inss' => $request->inss_desconto[$index],
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
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

        $contrato->update();
      
        return response()->json(['success' => true, 'message' => "Dados salvos com sucesso!"], 200);    
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
        
        if(!$user->can('eliminar contrato')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui     
            $contrato = Contrato::findOrFail($id);
            $contrato->delete();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        
      
        return response()->json(['success' => true, 'message' => "Dados Excluído com sucesso!"], 200); 
        
    }

}
