<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Agendamento;
use App\Models\Cliente;
use App\Models\Entidade;
use App\Models\Marca;
use App\Models\Produto;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;


use PDF;

class AgendamentoController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('listar agendamento')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        //
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $agendas = Agendamento::with(['produto', 'user', 'cliente'])->where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('data_at', '>=', Carbon::createFromDate($value));
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('data_at', '<=', Carbon::createFromDate($value));
        })
        ->when($request->cliente_id, function($query, $value){
            $query->where('cliente_id', '=', $value);
        })
        ->when($request->status, function($query, $value){
            $query->where('status', '=', $value);
        })
        ->when($request->user_id, function($query, $value){
            $query->where('user_id', '=', $value);
        })
        ->orderBy('created_at', 'desc')
        ->get();

        $head = [
            "titulo" => "Agendamentos",
            "descricao" => env('APP_NAME'),
            "agendas" => $agendas,
            "clientes" => Cliente::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
            "requests" => $request->all('data_inicio', 'data_final', 'cliente_id', 'status', 'user_id'),
        ];

        return view('dashboard.agendamentos.index', $head);
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
        
        if(!$user->can('criar agendamento')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $produtos = Produto::with(['categoria', 'marca', 'taxa_imposto'])
            ->where('tipo', '=', 'S')
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $head = [
            "titulo" => "Novo Agendamento",
            "descricao" => env('APP_NAME'),
            "produtos" => $produtos,
            "clientes" => Cliente::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.agendamentos.create', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        
        $user = auth()->user();
        
        if(!$user->can('criar agendamento')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        
        $request->validate([
            'cliente_id' => 'required|string',
            'hora' => 'required|string',
            'data_at' => 'required|string',
            'observacao' => 'required|string',
            'servico_id' => 'required|string',
            'status' => 'required|string',
        ], [
            'cliente_id.required' => 'O cliente é um campo obrigatório',
            'hora.required' => 'A hora é um campo obrigatório',
            'data_at.required' => 'A data é um campo obrigatório',
            'observacao.required' => 'A observação é um campo obrigatório',
            'servico_id.required' => 'O serviço é um campo obrigatório',
            'status.required' => 'O estado é um campo obrigatório',
        ]);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $total_agendamento = Agendamento::where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])->count();
        
        $numero = "AGD Nº " . $total_agendamento + 1 ;

        $categoria = Agendamento::create([
            'hora' => $request->hora,
            'data_at' => $request->data_at,
            'observacao' => $request->observacao,
            'numero' => $numero,
            'servico_id' => $request->servico_id,
            'entidade_id' => $entidade->empresa->id,
            'cliente_id' => $request->cliente_id,
            'status' => $request->status,
            'user_id' => Auth::user()->id,
        ]);

        if ($categoria->save()) {
            return redirect()->route('agendamentos.index')->with("success", "Dados Cadastrar com Sucesso!");
        } else {
            return redirect()->route('agendamentos.create')->with("warning", "Erro ao tentar cadastrar Marca");
        }
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
        
        if(!$user->can('listar agendamento')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        //
        $agenda = Agendamento::with(['produto', 'user', 'cliente'])->findOrFail($id);
    
        $head = [
            "titulo" => "Detalhe Agendamento",
            "descricao" => env('APP_NAME'),
            "agenda" => $agenda,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.agendamentos.show', $head);
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
        
        if(!$user->can('editar agendamento')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        //
        
        $agendamento = Agendamento::with(['produto', 'user', 'cliente'])->findOrFail($id);
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $produtos = Produto::with(['categoria', 'marca', 'taxa_imposto'])
            ->where('tipo', '=', 'S')
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

            
        $head = [
            "titulo" => "Editar Agendamento",
            "clientes" => Cliente::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "descricao" => env('APP_NAME'),
            "agenda" => $agendamento,
            "produtos" => $produtos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.agendamentos.edit', $head);
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
        
        if(!$user->can('editar agendamento')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        
        $request->validate([
            'cliente_id' => 'required|string',
            'hora' => 'required|string',
            'data_at' => 'required|string',
            'observacao' => 'required|string',
            'servico_id' => 'required|string',
            'status' => 'required|string',
        ], [
            'cliente_id.required' => 'O cliente é um campo obrigatório',
            'hora.required' => 'A hora é um campo obrigatório',
            'data_at.required' => 'A data é um campo obrigatório',
            'observacao.required' => 'A observação é um campo obrigatório',
            'servico_id.required' => 'O serviço é um campo obrigatório',
            'status.required' => 'O estado é um campo obrigatório',
        ]);

        $agendamento = Agendamento::findOrFail($id);
        $agendamento->update($request->all());

        if ($agendamento->update()) {
            return redirect()->route('agendamentos.index')->with("success", "Dados Actualizados com Sucesso!");
        } else {
            return redirect()->route('agendamentos.edit')->with("warning", "Erro ao tentar Actualizar Marca");
        }
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
        
        if(!$user->can('eliminar agendamento')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        $agendamento = Agendamento::findOrFail($id);
        if ($agendamento->delete()) {
            return redirect()->route('agendamentos.index')->with("success", "Dados Excluído com Sucesso!");
        } else {
            return redirect()->route('agendamentos.index')->with("warning", "Erro ao tentar Excluir Marca");
        }
    }
    
    
    public function imprimir($id)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $agendamento = Agendamento::with(['produto', 'user', 'cliente'])->findOrFail($id);
        
        $head = [
            "titulo" => "Factura Recibo",
            "descricao" => env('APP_NAME'),
            "agendamento" => $agendamento,
            "loja" => $entidade,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.agendamentos.imprimir', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();

    }
    
        
    public function pdf_agendamentos(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);
       
        $agendas = Agendamento::with(['produto', 'user', 'cliente'])->where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('data_at', '>=', Carbon::createFromDate($value));
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('data_at', '<=', Carbon::createFromDate($value));
        })
        ->when($request->cliente_id, function($query, $value){
            $query->where('cliente_id', '=', $value);
        })
        ->when($request->status, function($query, $value){
            $query->where('status', '=', $value);
        })
        ->when($request->user_id, function($query, $value){
            $query->where('user_id', '=', $value);
        })
        ->orderBy('created_at', 'desc')
        ->get();
        
        $cliente = Cliente::find($request->cliente_id);
        $operador = User::find($request->user_id);
    
        $head = [
            'titulo' => "AGENDAMENTOS",
            'descricao' => env('APP_NAME'),
            'agendas' => $agendas,
            "cliente" => $cliente,
            "operador" => $operador,
            "empresa" => $empresa,
            "requests" => $request->all('data_inicio', 'data_final','status', 'cliente_id', 'user_id'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.agendamentos.pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
        // return $pdf->download('test.pdf');
    }
    
    
}
