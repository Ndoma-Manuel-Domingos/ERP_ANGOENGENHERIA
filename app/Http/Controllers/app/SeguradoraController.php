<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Municipio;
use App\Models\Seguradora;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class SeguradoraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = auth()->user();
        
        if(!$user->can('listar seguradora')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $seguradoras = Seguradora::where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => "seguradoras",
            "descricao" => env('APP_NAME'),
            "seguradoras" => $seguradoras,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.seguradoras.index', $head);
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
        
        if(!$user->can('criar seguradora')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        //
        $head = [
            "titulo" => "Cadastrar Seguradora",
            "descricao" => env('APP_NAME'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.seguradoras.create', $head);
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
        
        if(!$user->can('criar seguradora')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        
        $request->validate([
            'nome' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
        ]);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $seguradora = Seguradora::create([
            'nome' => $request->nome,
            'status' => $request->status,
            'numero' => $request->numero,
            'user_id' => Auth::user()->id,
            'entidade_id' => $entidade->empresa->id,
        ]);

        if($seguradora->save()){
            return redirect()->route('seguradoras.index')->with("success", "Dados Cadastrar com Sucesso!");
        }else{
            return redirect()->route('seguradoras.create')->with("warning", "Erro ao tentar cadastrar seguradora");
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
        //
        $user = auth()->user();
        
        if(!$user->can('listar seguradora')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        
        $seguradora = Seguradora::findOrFail($id);
        
        $head = [
            "titulo" => "Detalhe Seguradora",
            "descricao" => env('APP_NAME'),
            "seguradora" => $seguradora,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.seguradoras.show', $head);

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
        
        if(!$user->can('editar seguradora')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $seguradora = Seguradora::findOrFail($id);
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $head = [
            "titulo" => "Seguradora",
            "descricao" => env('APP_NAME'),
            "seguradora" => $seguradora,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.seguradoras.edit', $head);
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
        
        if(!$user->can('editar seguradora')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'nome' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
        ]);

        $seguradora = Seguradora::findOrFail($id);
        
        $seguradora->nome = $request->nome;
        $seguradora->numero = $request->numero;
        $seguradora->status = $request->status;
        
        if($seguradora->update()){
            return redirect()->route('seguradoras.index')->with("success", "Dados Actualizados com Sucesso!");
        }else{
            return redirect()->route('seguradoras.edit')->with("warning", "Erro ao tentar Actualizar seguradora");
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
        //
        $user = auth()->user();
        
        if(!$user->can('eliminar seguradora')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $seguradora = Seguradora::findOrFail($id);
        if($seguradora->delete()){
            return redirect()->route('seguradoras.index')->with("success", "Dados Excluído com Sucesso!");
        }else{
            return redirect()->route('seguradoras.index')->with("warning", "Erro ao tentar Excluir seguradora");
        }
    }
}
