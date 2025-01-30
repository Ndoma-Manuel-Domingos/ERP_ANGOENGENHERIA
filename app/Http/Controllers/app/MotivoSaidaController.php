<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\MotivoSaida;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class MotivoSaidaController extends Controller
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
        //
        $user = auth()->user();

        if(!$user->can('listar motivo')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $motivos = MotivoSaida::where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => "Motivos",
            "descricao" => env('APP_NAME'),
            "motivos" => $motivos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.motivos-saidas.index', $head);
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
        
        if(!$user->can('criar motivo')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $head = [
            "titulo" => "Cadastrar Motivo",
            "descricao" => env('APP_NAME'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.motivos-saidas.create', $head);
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
        
        if(!$user->can('criar departamento')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'nome' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
        ]);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $motivo = MotivoSaida::create([
            'entidade_id' => $entidade->empresa->id, 
            'nome' => $request->nome,
            'user_id' => Auth::user()->id,
        ]);

        if($motivo->save()){
            return redirect()->back()->with("success", "Dados Cadastrar com Sucesso!");
        }else{
            return redirect()->back()->with("warning", "Erro ao tentar cadastrar Departamento");
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
        
        if(!$user->can('listar motivo')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $motivo = MotivoSaida::findOrFail($id);
        
        $head = [
            "titulo" => "Detalhe Motivo",
            "descricao" => env('APP_NAME'),
            "motivo" => $motivo,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.motivos-saidas.show', $head);

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
        
        if(!$user->can('editar motivo')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $motivo = MotivoSaida::findOrFail($id);

        $head = [
            "titulo" => "Editar Motivo",
            "descricao" => env('APP_NAME'),
            "motivo" => $motivo,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.motivos-saidas.edit', $head);
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
        
        if(!$user->can('editar motivo')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'nome' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
        ]);

        $motivo = MotivoSaida::findOrFail($id);
        $motivo->update($request->all());

        if($motivo->update()){
            return redirect()->back()->with("success", "Dados Actualizados com Sucesso!");
        }else{
            return redirect()->back()->with("warning", "Erro ao tentar Actualizar Motivo");
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
        
        if(!$user->can('eliminar motivo')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $motivo = MotivoSaida::findOrFail($id);
        if($motivo->delete()){
            return redirect()->back()->with("success", "Dados Excluído com Sucesso!");
        }else{
            return redirect()->back()->with("warning", "Erro ao tentar Excluir motivo");
        }
    }

}
