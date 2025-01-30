<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $permissions = Permission::get();
      
        $head = [
            "titulo" => "Permissões",
            "descricao" => env('APP_NAME'),
            "permissions" => $permissions,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.permissoes.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $head = [
            "titulo" => "Cadastrar Permissão",
            "descricao" => env('APP_NAME'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.permissoes.create', $head);
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
            'permission' => 'required|string|unique:permissions,name', // Ajuste conforme o nome da sua tabela e campo
        ],[
            'permission.unique' => 'Este nome já está em uso.',
            'permission.required' => 'A permissão é uma campo obrigatório',
        ]);
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $permission = Permission::create([
            'name' => $request->permission,
        ]);
        
        Alert::success('Sucesso', "Dados Cadastrar com Sucesso!");
        return redirect()->back();

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
        $permission = Permission::findOrFail($id);
        
        $head = [
            "titulo" => "Detalhe Perfil",
            "descricao" => env('APP_NAME'),
            "permission" => $permission,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.permissoes.show', $head);
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
        $permission = Permission::findOrFail($id);

        $head = [
            "titulo" => "Perfil",
            "descricao" => env('APP_NAME'),
            "permission" => $permission,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.permissoes.edit', $head);
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
            'permission' => 'required|string',
        ],[
            'permission.required' => 'A permissão é um campo obrigatório',
        ]);

        $permission = Permission::findOrFail($id);
        $permission->name = $request->permission;

        if($permission->update()){
            Alert::success('Sucesso', "Dados Actualizados com Sucesso!");
            return redirect()->back();
        }else{
            Alert::success('Atenção', "Erro ao tentar Actualizar Permissão!");
            return redirect()->back();
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
        $permission = Permission::findOrFail($id);
        if($permission->delete()){
            Alert::success('Sucesso', "Dados Excluído com Sucesso!");
            return redirect()->back();
        }else{
            Alert::success('Atenção', "Erro ao tentar Excluir permissão");
            return redirect()->back();
        }
    }

}
