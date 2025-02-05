<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use App\Models\Departamento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class CargoController extends Controller
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

        if(!$user->can('listar cargo')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $cargos = Cargo::with(['departamento'])->where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => "cargos",
            "descricao" => env('APP_NAME'),
            "cargos" => $cargos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.cargos.index', $head);
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
        
        if(!$user->can('criar cargo')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $departamentos = Departamento::where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Cadastrar Cargos",
            "descricao" => env('APP_NAME'),
            "departamentos" => $departamentos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.cargos.create', $head);
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
        
        if(!$user->can('criar cargo')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'nome' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
        ]);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            Cargo::create([
                'entidade_id' => $entidade->empresa->id, 
                'departamento_id' => $request->departamento_id, 
                'salario_base' => $request->salario_base, 
                'nome' => $request->nome,
                'status' => $request->status,
                'user_id' => Auth::user()->id,
            ]);
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        
        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"], 200);

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
        
        if(!$user->can('listar cargo')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $cargo = Cargo::with(['departamento'])->findOrFail($id);
        
        $head = [
            "titulo" => "Detalhe Cargo",
            "descricao" => env('APP_NAME'),
            "cargo" => $cargo,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.cargos.show', $head);

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
        
        if(!$user->can('editar cargo')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $cargo = Cargo::findOrFail($id);
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $departamentos = Departamento::where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Editar Cargos",
            "departamentos" => $departamentos,
            "descricao" => env('APP_NAME'),
            "cargo" => $cargo,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.cargos.edit', $head);
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
        
        if(!$user->can('editar cargo')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'nome' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $cargo = Cargo::findOrFail($id);
            $cargo->update($request->all());

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        
        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"], 200);

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
        
        if(!$user->can('eliminar cargo')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $cargo = Cargo::findOrFail($id);
            $cargo->delete();
            
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
