<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Mesa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class MesaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $mesas = Mesa::where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->orderBy('created_at', 'desc')
        ->get();

        $head = [
            "titulo" => "Mesas",
            "descricao" => env('APP_NAME'),
            "mesas" => $mesas,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.mesas.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        if(!isset($request->createLoja)){
            return redirect()->route('salas.index');
        }

        $head = [
            "titulo" => "Cadastrar Mesa",
            "descricao" => env('APP_NAME'),
            "sala_id" => $request->createLoja,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.mesas.create', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string',
            'status' => 'required',
            "sala_id" =>  'required',
        ]);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $mesas = Mesa::create([
            'nome' => $request->nome,
            'status' => $request->status,
            'ocupacao' => $request->ocupacao,
            'solicitar_ocupacao' => $request->solicitar_ocupacao,
            "sala_id" => $request->sala_id,
            'entidade_id' => $entidade->empresa->id,
        ]);

        if($mesas->save()){
            Alert::success("Sucesso!", "Dados Cadastrar com Sucesso!");
            return redirect()->route('salas.index');
        }else{
            Alert::warning("Alerta!", "Erro ao tentar cadastrar Mesa");
            return redirect()->route('salas.index');
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
        $mesas = Mesa::findOrFail($id);
        $head = [
            "titulo" => "Detalhe Mesa",
            "descricao" => env('APP_NAME'),
            "mesa" => $mesas,
            "dados" => User::with("empresa")->findOrFail(Auth::user()->id),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.mesas.show', $head);
    }
    
    public function mudar_status_mesa($id)
    {
        $mesa = Mesa::findOrFail($id);
        $mesa->solicitar_ocupacao = "LIVRE";
        $mesa->update();
        
        Alert::success("Sucesso!", "Mesa reiniciada com sucesso!");
        return redirect()->route('pronto-venda-mesas');
        
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
        $mesas = Mesa::findOrFail($id);

        $head = [
            "titulo" => "Mesas",
            "descricao" => env('APP_NAME'),
            "mesa" => $mesas,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.mesas.edit', $head);
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
        $request->validate([
            'nome' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
        ]);

        $mesas = Mesa::findOrFail($id);
        $mesas->update($request->all());

        if($mesas->update()){
            Alert::success("Sucesso!", "Dados Actualizados com Sucesso!");
            return redirect()->route('salas.index');
        }else{
            Alert::warning("Alerta!", "Erro ao tentar cadastrar Sala");
            return redirect()->route('salas.index');
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
        $mesas = Mesa::findOrFail($id);
        if($mesas->delete()){
            return redirect()->route('mesas.index')->with("success", "Dados Excluído com Sucesso!");
        }else{
            return redirect()->route('mesas.index')->with("warning", "Erro ao tentar Excluir Mesa");
        }
    }
}
