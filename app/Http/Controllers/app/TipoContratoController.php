<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\TipoContrato;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class TipoContratoController extends Controller
{
    //
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = auth()->user();

        if(!$user->can('listar tipo contrato')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $tipos_contratos = TipoContrato::where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => "Tipos Contratos",
            "descricao" => env('APP_NAME'),
            "tipos_contratos" => $tipos_contratos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.tipos-contratos.index', $head);
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
        
        if(!$user->can('criar tipo contrato')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $head = [
            "titulo" => "Cadastrar Tipos Contratos",
            "descricao" => env('APP_NAME'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.tipos-contratos.create', $head);
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
        
        if(!$user->can('criar tipo contrato')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'nome' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
        ]);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $tipo_contrato = TipoContrato::create([
            'entidade_id' => $entidade->empresa->id, 
            'nome' => $request->nome,
            'status' => $request->status,
            'user_id' => Auth::user()->id,
        ]);

        if($tipo_contrato->save()){
            return redirect()->route('tipos-contratos.index')->with("success", "Dados Cadastrar com Sucesso!");
        }else{
            return redirect()->route('tipos-contratos.create')->with("warning", "Erro ao tentar cadastrar tipo contrato");
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
        
        if(!$user->can('listar tipo contrato')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $tipo_contrato = TipoContrato::findOrFail($id);
        
        $head = [
            "titulo" => "Detalhe Tipo Contrato",
            "descricao" => env('APP_NAME'),
            "tipo_contrato" => $tipo_contrato,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.tipos-contratos.show', $head);

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
        
        if(!$user->can('editar tipo contrato')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $tipo_contrato = TipoContrato::findOrFail($id);

        $head = [
            "titulo" => "Editar Tipos Contratos",
            "descricao" => env('APP_NAME'),
            "tipo_contrato" => $tipo_contrato,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.tipos-contratos.edit', $head);
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
        
        if(!$user->can('editar tipo contrato')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'nome' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
        ]);

        $tipo_contrato = TipoContrato::findOrFail($id);
        $tipo_contrato->update($request->all());

        if($tipo_contrato->update()){
            return redirect()->route('tipos-contratos.index')->with("success", "Dados Actualizados com Sucesso!");
        }else{
            return redirect()->route('tipos-contratos.edit')->with("warning", "Erro ao tentar Actualizar Tipo Contrato");
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
        
        if(!$user->can('eliminar tipo contrato')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $tipo_contrato = TipoContrato::findOrFail($id);
        if($tipo_contrato->delete()){
            return redirect()->route('tipos-contratos.index')->with("success", "Dados Excluído com Sucesso!");
        }else{
            return redirect()->route('tipos-contratos.index')->with("warning", "Erro ao tentar Excluir Tipo Contrato");
        }
    }

}
