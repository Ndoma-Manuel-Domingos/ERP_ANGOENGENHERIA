<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
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

        $roles = Role::with('permissions')->orderBy('created_at', 'desc')->get();
        
        $head = [
            "titulo" => "Perfis",
            "descricao" => env('APP_NAME'),
            "roles" => $roles,
            "permissions" => Permission::pluck('id')->toArray(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.roles.index', $head);
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
            "titulo" => "Cadastrar Perfis",
            "descricao" => env('APP_NAME'),
            "permissions" => Permission::get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.roles.create', $head);
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
            'role' => 'required|string|unique:roles,name', // Ajuste conforme o nome da sua tabela e campo
        ],[
            'role.unique' => 'Este nome já está em uso.',
            'role.required' => 'O perfil é um campo obrigatório',
        ]);
      
        $roles = Role::create(['name' => $request->role]);
            
        if($request->permissions){
            foreach ($request->permissions as $item) {
                $permission = Permission::findById($item);
                $roles->givePermissionTo($permission);
            }
        }

        Alert::success('Sucesso', "Dados Cadastrar com Sucesso!");
        return redirect()->route('roles.index');
   
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
        $role = Role::with('permissions')->findOrFail($id);
        
        $head = [
            "titulo" => "Detalhe Perfil",
            "descricao" => env('APP_NAME'),
            "role" => $role,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.roles.show', $head);

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
        $role = Role::with(['permissions'])->findOrFail($id);
        
        $role_permissions = $role->permissions->pluck('id')->toArray();

        $head = [
            "titulo" => "Perfil",
            "descricao" => env('APP_NAME'),
            "role" => $role,
            "role_permissions" => $role_permissions,
            "permissions" => Permission::get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.roles.edit', $head);
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
            'role' => 'required|string',
        ],[
            'role.required' => 'O perfil é um campo obrigatório',
        ]);
        
        $role = Role::findOrFail($id);
        $role->name = $request->role;
        
        $role->permissions()->sync($request->input('permissions', []));
        
        $role->update();
       
        Alert::success('Sucesso', "Dados Actualizados com Sucesso!");
        return redirect()->route('roles.index');
   
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
        $role = Role::findOrFail($id);
        if($role->delete()){
            Alert::success('Sucesso', "Dados Excluído com Sucesso!");
            return redirect()->route('roles.index');
        }else{
            Alert::success('Atenção', "Erro ao tentar Excluir perfil");
            return redirect()->route('roles.edit');
        }
    }
}
