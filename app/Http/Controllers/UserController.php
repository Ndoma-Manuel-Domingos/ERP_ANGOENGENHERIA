<?php

namespace App\Http\Controllers;

use App\Models\Entidade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        
        if(!$user->can('listar utilizadores')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
    
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::findOrFail($entidade->empresa->id);

        $utilizadores = User::where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->with('roles')
        ->orderBy('created_at', 'desc')
        ->get();
        
        $roles = Role::get();

        $head = [
            "titulo" => "Utilizadores",
            "descricao" => env('APP_NAME'),
            "utilizadores" => $utilizadores,
            "empresa" => $entidade,
            "roles" => $roles,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.utilizadores.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
            
        if(!$user->can('criar utilizadores')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $roles = Role::get();

        $head = [
            "titulo" => "Cadastrar Caixas",
            "descricao" => env('APP_NAME'),
            "roles" => $roles,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.utilizadores.create', $head);
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
        
        if(!$user->can('criar utilizadores')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'nome' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:3|max:20|same:password',
            'password_r' => 'required|min:3|max:20',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
            'nome.string' => 'O nome é um campo obrigatório',
            'email.required' => 'O e-mail é um campo obrigatório',
            'email.email' => 'O e-mail é um campo invalido',
            'password.required' => 'a senha é um campo obrigatório',
            'password.min' => 'A senha deve ter no minimo 3 caracteres',
            'password.max' => 'A senha deve ter no maximo 20 caracteres',
            'password_r.required' => 'Confirmar senha é um campo obrigatório',
            'password_r.min' => 'Confirmar senha deve ter no minimo 3 caracteres',
            'password_r.max' => 'Confirmar senha deve ter no maximo 20 caracteres',
        ]);
          
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $roles = Role::findOrFail($request->roles);
        
        $user = User::create([
            "name" => $request->nome,
            "email" => $request->email,
            "is_admin" => false,
            "status" => true,
            "level" => 1,
            "login_access" => false,
            "password" => Hash::make($request->password),
            "entidade_id" => $entidade->empresa->id,
        ]);

        $user->assignRole($roles);

        if($user->save()){
            Alert::success("Sucesso!", "Dados Cadastrar com Sucesso!");
            return redirect()->back()->with('success', "Dados Cadastrar com Sucesso!");
        }else{
            Alert::warning("Alerta!", "Erro ao tentar cadastrar utilizador");
            return redirect()->back()->with('danger', "Erro ao tentar cadastrar utilizador!");
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
        
        if(!$user->can('editar utilizadores')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $utilizador = User::with(['roles'])->findOrFail($id);
        
        $users_roles = $utilizador->roles->pluck('id')->toArray();
        
        $roles = Role::get();

        $head = [
            "titulo" => "Utilizador",
            "descricao" => env('APP_NAME'),
            "utilizador" => $utilizador,
            "roles" => $roles,
            "users_roles" => $users_roles,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.utilizadores.edit', $head);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function privacidade()
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $roles = Role::get();

        $utilizador = User::findOrFail(Auth::user()->id);

        $head = [
            "titulo" => "Utilizador",
            "descricao" => env('APP_NAME'),
            "utilizador" => $utilizador,
            "roles" => $roles,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.utilizadores.privacidade', $head);
    }   
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function privacidade_store(Request $request)
    {
        
        $request->validate([
            'senha' => 'required|string',
            'nova_senha' => 'required|min:3|max:20', //s|same:password
            'confirmar_senha' => 'required|min:3|max:20',
        ],[
            'senha.required' => 'A senha é um campo obrigatório',
            'senha.string' => 'A senha é um campo obrigatório',
            'nova_senha.required' => 'Nova senha é um campo obrigatório',
            'nova_senha.min' => 'Nova senha deve ter no minimo 3 caracteres',
            'nova_senha.max' => 'Nova senha deve ter no maximo 20 caracteres',
            'confirmar_senha.required' => 'Confirmar senha é um campo obrigatório',
            'confirmar_senha.min' => 'Confirmar senha deve ter no minimo 3 caracteres',
            'confirmar_senha.max' => 'Confirmar senha deve ter no maximo 20 caracteres',
        ]);
                
        if (!Hash::check($request->senha, Auth::user()->password)) {
            Alert::warning("Alerta!", "Senha actual invalída!");
            return redirect()->route('privacidade');
        }   
                  
        if ($request->nova_senha != $request->confirmar_senha) {
            Alert::warning("Alerta!", "Nova Senha e confirmação da nova senha não conferem!");
            return redirect()->route('privacidade');
        } 
        
        
        $user = User::findOrFail(Auth::user()->id);
        $user->password = Hash::make($request->nova_senha);
        $user->login_access = 1;
        $user->update();
        
        Alert::success("Sucesso!", "Dados Actualizados com Sucesso!");
        return redirect()->back();

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
        $user = auth()->user();
        
        if(!$user->can('editar utilizadores')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'nome' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
            'nome.string' => 'O nome é um campo obrigatório',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->nome;
        $user->email = $request->email;
        
        foreach ($user->roles as $role) {
            $user->removeRole($role);
        }
        
        $new_role = Role::findOrFail($request->roles);
        $user->assignRole($new_role);
        
        $user->update();

        Alert::success("Sucesso!", "Dados Actualizados com Sucesso!");
        return redirect()->back();
  
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
        
        if(!$user->can('eliminar utilizadores')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        
        $utilizador = User::findOrFail($id);
        if($utilizador->delete()){
            return redirect()->route('utilizadores.index')->with("success", "Dados Excluído com Sucesso!");
        }else{
            return redirect()->route('utilizadores.index')->with("warning", "Erro ao tentar Excluir utilizador");
        }
    }
}
