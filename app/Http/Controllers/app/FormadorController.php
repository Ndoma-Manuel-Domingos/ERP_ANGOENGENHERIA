<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Formador;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;

class FormadorController extends Controller
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
 
        $formadores = Formador::where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->orderBy('created_at', 'desc')->get();
        
        $head = [
            "titulo" => "formadores",
            "descricao" => env('APP_NAME'),
            "formadores" => $formadores,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.formadores.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $roles = Role::get();

        $head = [
            "titulo" => "Cadastrar Formadores",
            "descricao" => env('APP_NAME'),
            "roles" => $roles,
            "user" => Auth::user(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.formadores.create', $head);
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
            'nif' => 'required|string',
            'email' => 'email|string',
            'genero' => 'required|string',
            'estado_civil' => 'required|string',
            'id_user' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
            'nif.required' => 'O nif é um campo obrigatório',
            'email.required' => 'O e-mail é um campo obrigatório',
            'genero.required' => 'O genero é um campo obrigatório',
            'estado_civil.required' => 'O estado cívil é um campo obrigatório',
            'id_user.required' => 'O usuário é um campo obrigatório',
        ]); 

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
          
        $verificar_aluno = Formador::where('nif', $request->nif)
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->first();
            
        if($verificar_aluno){
            return redirect()->route('formadores.index')->with("warning", "Este formador, já esta cadastro no sistema!");
        }
        
        $role = Role::findOrFail($request->id_user);
        
        $user = User::create([
            "name" => $request->nome,
            "email" => $request->email,
            "is_admin" => false,
            "type_user" => 'Formador',
            "status" => true,
            "level" => 5,
            "login_access" => false,
            "password" => Hash::make($request->nif),
            "entidade_id" => $entidade->empresa->id,
        ]);

        $user->assignRole($role);
        
        $formador = Formador::create([
            "nif" => $request->nif,
            "nome" => $request->nome,
            "pai" => $request->pai,
            "mae" => $request->mae,
            "genero" => $request->genero,
            "estado_civil" => $request->estado_civil,
            "data_nascimento" => $request->data_nascimento,
            "id_user" => $user->id,
            "pais" => $request->pais,
            "status" => true,
            "codigo_postal" => $request->codigo_postal,
            "localidade" => $request->localidade,
            "telefone" => $request->telefone,
            "telemovel" => $request->telemovel,
            "email" => $request->email,
            "website" => $request->website,
            "referencia_externa" => $request->referencia_externa,
            "observacao" => $request->observacao,         
            "user_id" => Auth::user()->id,    
            'entidade_id' => $entidade->empresa->id,      
        ]);
                
        $formador->update();
        
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
        $formador = Formador::findOrFail($id);
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $head = [
            "titulo" => "formador",
            "descricao" => env('APP_NAME'),
            "formador" => $formador,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.formadores.show', $head);    
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $formador = Formador::findOrFail($id);
        
        $user = User::with(['roles'])->findOrFail($formador->id_user);
        
        $roles_user = $user->roles->pluck('id')->toArray();
        
        $roles = Role::get();
        
        $head = [
            "titulo" => "formador",
            "descricao" => env('APP_NAME'),
            "roles" => $roles,
            "roles_user" => $roles_user,
            "formador" => $formador,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.formadores.edit', $head);    
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
        $request->validate([
            'nome' => 'required|string',
            'nif' => 'required|string',
            'email' => 'email|string',
            'genero' => 'required|string',
            'estado_civil' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
            'nif.required' => 'O nif é um campo obrigatório',
            'email.required' => 'O e-mail é um campo obrigatório',
            'genero.required' => 'O genero é um campo obrigatório',
            'estado_civil.required' => 'O estado cívil é um campo obrigatório',
        ]);
        
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
    
            $formador = Formador::findOrFail($id);
    
            $user = User::with(['roles'])->findOrFail($formador->id_user);
            $user->name = $request->nome;
            $user->email = $request->email;
            
            foreach ($user->roles as $role) {
                $user->removeRole($role);
            }
            
            $new_role = Role::findOrFail($request->id_user);
            $user->assignRole($new_role);
            
            $user->update();
    
            $formador->update($request->all());
            
            $formador->id_user = $user->id;
            
            $formador->save();
            
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
        $formador = Formador::findOrFail($id);
        if($formador->delete()){
            return redirect()->route('formadores.index')->with("success", "Dados Excluído com Sucesso!");
        }else{
            return redirect()->route('formadores.index')->with("warning", "Erro ao tentar Excluir formador");
        }
    }
    

}
