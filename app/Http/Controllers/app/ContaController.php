<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Conta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ContaController extends Controller
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

        if(!$user->can('listar conta')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $contas = Conta::with(['user','entidade', 'classe'])->where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => "Contas",
            "descricao" => env('APP_NAME'),
            "contas" => $contas,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contas.index', $head);
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
        
        if(!$user->can('criar conta')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $classes = Classe::where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Cadastrar Contas",
            "descricao" => env('APP_NAME'),
            "classes" => $classes,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contas.create', $head);
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
        
        if(!$user->can('criar conta')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'nome' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
        ]);
        
        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
    
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            $conta = Conta::create([
                'entidade_id' => $entidade->empresa->id, 
                'nome' => $request->nome,
                'serie' => $request->serie,
                'status' => $request->status,
                'conta' => $request->conta,
                'classe_id' => $request->classe_id,
                'user_id' => Auth::user()->id,
            ]);
    
            $conta->save();
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
        
        if(!$user->can('listar conta')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $conta = Conta::with(['exercicio'. 'classe'])->findOrFail($id);
        
        $head = [
            "titulo" => "Detalhe Conta",
            "descricao" => env('APP_NAME'),
            "conta" => $conta,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contas.show', $head);

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
        
        if(!$user->can('editar conta')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $conta = Conta::with(['entidade', 'user'])->findOrFail($id);
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $classes = Classe::where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Editar Conta",
            "descricao" => env('APP_NAME'),
            "conta" => $conta,
            "classes" => $classes,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contas.edit', $head);
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
        
        if(!$user->can('editar conta')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'nome' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
        ]);
        
        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $conta = Conta::findOrFail($id);
            $conta->update($request->all());
    
            $conta->update();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }


        return redirect()->back()->with("success", "Dados Actualizados com Sucesso!");
       
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
        
        if(!$user->can('eliminar conta')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $conta = Conta::findOrFail($id);
            $conta->delete();
            
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        
        return redirect()->back()->with("success", "Dados Excluído com Sucesso!");
    
    }

}
