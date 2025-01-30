<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Sala;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class SalaController extends Controller
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
        
        if(!$user->can('listar sala')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $salas = Sala::where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])
        ->with('mesas')
        ->orderBy('created_at', 'desc')
        ->get();

        $head = [
            "titulo" => "Salas",
            "descricao" => env('APP_NAME'),
            "salas" => $salas,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.salas.index', $head);
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
        
        if(!$user->can('criar sala')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        
        $head = [
            "titulo" => "Cadastrar Sala",
            "descricao" => env('APP_NAME'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.salas.create', $head);
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
        
        if(!$user->can('criar sala')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        
        $request->validate([
            'nome' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
        ]);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $salas = Sala::create([
            'nome' => $request->nome,
            'status' => $request->status,
            'solicitar_ocupacao' => $request->solicitar_ocupacao,
            'entidade_id' => $entidade->empresa->id, 
        ]);

        if($salas->save()){
            return redirect()->back()->with("success", "Dados Cadastrar com Sucesso!");
        }else{
            return redirect()->back()->with("warning", "Erro ao tentar cadastrar Salas");
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
        
        if(!$user->can('listar sala')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $sala = Sala::findOrFail($id);
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        // se estiver activo e´por esta sendo desactivo então verificamos a quantidade de lojas acticas caso so tem uma barramos
        if($sala->status == "activo"){
            
            $salas = Sala::where([
                ['status', '=', 'activo'],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->count();

            if($salas == 1){
                Alert::warning("Alerta!", "Tem que ter sempre uma Sala activa");
                return redirect()->route('salas.index')->with("warning", "Tem que ter sempre uma Sala activa");
            }
        }

        if($sala->status == "desactivo"){
            $sala->status = 'activo';
        }else{
            $sala->status = 'desactivo';
        }
        
        if($sala->update()){
            Alert::success("Sucesso!", "Loja Suspendida do successo");
            return redirect()->back()->with("warning", "Sala Suspendida do successo");
        }else {
            Alert::error("Erro!", "Não foi possível Suspender a Sala");
            return redirect()->back()->with("warning", "Não foi possível Suspender a Sala");
        }
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
        
        if(!$user->can('editar sala')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $sala = Sala::findOrFail($id);

        $head = [
            "titulo" => "Sala",
            "descricao" => env('APP_NAME'),
            "sala" => $sala,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.salas.edit', $head);
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
        
        if(!$user->can('editar sala')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'nome' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
        ]);

        $salas = Sala::findOrFail($id);
        $salas->update($request->all());

        if($salas->update()){
            return redirect()->back()->with("success", "Dados Actualizados com Sucesso!");
        }else{
            return redirect()->back()->with("warning", "Erro ao tentar Actualizar sala");
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
        
        if(!$user->can('eliminar sala')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $salas = Sala::findOrFail($id);
        if($salas->delete()){
            return redirect()->back()->with("success", "Dados Excluído com Sucesso!");
        }else{
            return redirect()->back()->with("warning", "Erro ao tentar Excluir Sala");
        }
        
    }
}
