<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\AnoLectivo;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Sala;
use App\Models\Turno;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;

class AlunoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function matriculas()
    {
        //
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
 
        $matriculas = Matricula::with(['aluno', 'curso', 'sala', 'turno', "ano_lectivo"])
        ->where('entidade_id', '=', $entidade->empresa->id)
        ->orderBy('created_at', 'desc')
        ->get();
        
        $head = [
            "titulo" => "matriculas",
            "descricao" => env('APP_NAME'),
            "matriculas" => $matriculas,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.alunos.matriculas', $head);
    }
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
 
        $alunos = Aluno::where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->orderBy('created_at', 'desc')->get();
        
        $head = [
            "titulo" => "alunos",
            "descricao" => env('APP_NAME'),
            "alunos" => $alunos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.alunos.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $aluno = Aluno::find($request->aluno_id);
        
        $cursos = Curso::where('entidade_id', '=', $entidade->empresa->id)->get();
        $turnos = Turno::where('entidade_id', '=', $entidade->empresa->id)->get();
        $salas = Sala::where('entidade_id', '=', $entidade->empresa->id)->get();
        $anos_lectivos = AnoLectivo::where('entidade_id', '=', $entidade->empresa->id)->get();
        $roles = Role::get();

        $head = [
            "titulo" => "Cadastrar alunos",
            "descricao" => env('APP_NAME'),
            "user" => Auth::user(),
            "cursos" => $cursos,
            "turnos" => $turnos,
            "roles" => $roles,
            "salas" => $salas,
            "anos_lectivos" => $anos_lectivos,
            "aluno" => $aluno,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.alunos.create', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->aluno_id){
            $request->validate([
                'nome' => 'required|string',
                'nif' => 'required|string',
                'email' => 'email|string',
                'genero' => 'required|string',
                'estado_civil' => 'required|string',
            ],[
                'nome.required' => 'O nome é um campo obrigatório',
                'nif.string' => 'O nif é um campo obrigatório',
                'email.string' => 'O e-mail é um campo obrigatório',
                'genero.string' => 'O genero é um campo obrigatório',
                'estado_civil.string' => 'O estado cívil é um campo obrigatório',
            ]); 
        }
   
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
        
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            if(!$request->aluno_id){
            
                $verificar_aluno = Aluno::where('nif', $request->nif)
                    ->where('entidade_id', '=', $entidade->empresa->id)
                    ->first();
                
                    
                if($verificar_aluno){
                    return redirect()->route('alunos.index')->with("warning", "Este aluno, já esta cadastro no sistema!");
                }
                
                $role = Role::findOrFail($request->id_user);
            
                $user = User::create([
                    "name" => $request->nome,
                    "email" => $request->email,
                    "is_admin" => false,
                    "type_user" => 'Aluno',
                    "status" => true,
                    "level" => 10,
                    "login_access" => false,
                    "password" => Hash::make($request->nif),
                    "entidade_id" => $entidade->empresa->id,
                ]);
                
                $user->assignRole($role);
                
                $alunos = Aluno::create([
                    "nif" => $request->nif,
                    "nome" => $request->nome,
                    "pai" => $request->pai,
                    "mae" => $request->mae,
                    "genero" => $request->genero,
                    "estado_civil" => $request->estado_civil,
                    "nascimento" => $request->nascimento,
                    "pais" => $request->pais,
                    "status" => true,
                    "id_user" => $user->id,
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
                
                $codigo = time();
                
                $matricula = Matricula::create([
                    'status' => 'DESACTIVO',
                    'codigo' => $codigo,
                    'valor_pagamento' => $request->valor_pagamento,
                    'user_id' => Auth::user()->id, 
                    'aluno_id' => $alunos->id,
                    'curso_id' => $request->curso_id,
                    'turno_id' => $request->turno_id,
                    'sala_id' => $request->sala_id,
                    'ano_lectivo_id' => $request->ano_lectivo_id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
                
        
                $matricula->numero = "PROC " .  $matricula->id;
                $alunos->conta = $alunos->id;
                
                $matricula->update();
                $alunos->update();
                
            }else{
            
                $aluno = Aluno::findOrFail($request->aluno_id);
            
                $verificar_matricula = Matricula::where('turno_id', $request->turno_id)
                    ->where('sala_id', $request->sala_id)
                    ->where('curso_id', $request->curso_id)
                    ->where('aluno_id', $aluno->id)
                    ->where('ano_lectivo_id', $request->ano_lectivo_id)
                    ->where('entidade_id', '=', $entidade->empresa->id)
                    ->first();
                
                if($verificar_matricula){
                    return redirect()->back()->with("warning", "Este aluno, já esta matriculado neste curso!");
                }
            
                $codigo = time();
                
                $matricula = Matricula::create([
                    'status' => 'DESACTIVO',
                    'codigo' => $codigo,
                    'valor_pagamento' => $request->valor_pagamento,
                    'user_id' => Auth::user()->id, 
                    'aluno_id' => $aluno->id,
                    'curso_id' => $request->curso_id,
                    'turno_id' => $request->turno_id,
                    'sala_id' => $request->sala_id,
                    'ano_lectivo_id' => $request->ano_lectivo_id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
                
                $matricula->numero = "PROC " .  $matricula->id;
                $matricula->update();
            
            }
            
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        
        return redirect()->route('alunos-matriculas')->with("success", "Dados Cadastrar com Sucesso!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $aluno = Aluno::findOrFail($id);
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $matriculas = Matricula::with(['aluno', 'ano_lectivo' ,'curso' ,'sala' ,'turno', 'user'])->where('aluno_id', $aluno->id)
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->get();
        
        $head = [
            "titulo" => "aluno",
            "descricao" => env('APP_NAME'),
            "aluno" => $aluno,
            "matriculas" => $matriculas,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.alunos.show', $head);    
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function matriculas_status($id)
    {
        $matricula = Matricula::findOrFail($id);
        
        
        if($matricula->status == "DESACTIVO"){
            $matricula->status = "ACTIVO";
        }elseif($matricula->status == "ACTIVO"){
            $matricula->status = "DESACTIVO";
        }
        
        $matricula->update();
        
        return redirect()->back()->with("success", "Matricula activada com Sucesso!");
        
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $aluno = Aluno::findOrFail($id);
        
        $matricula = Matricula::where('aluno_id', '=', $aluno->id)->first();
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $cursos = Curso::where('entidade_id', '=', $entidade->empresa->id)->get();
        $turnos = Turno::where('entidade_id', '=', $entidade->empresa->id)->get();
        $salas = Sala::where('entidade_id', '=', $entidade->empresa->id)->get();
        $anos_lectivos = AnoLectivo::where('entidade_id', '=', $entidade->empresa->id)->get();
        
        $user = User::with(['roles'])->findOrFail($aluno->id_user);
        
        $roles_user = $user->roles->pluck('id')->toArray();
        
        $roles = Role::get();
        
        
        $head = [
            "titulo" => "aluno",
            "descricao" => env('APP_NAME'),
            "aluno" => $aluno,
            "cursos" => $cursos,
            "turnos" => $turnos,
            "salas" => $salas,
            "roles" => $roles,
            "roles_user" => $roles_user,
            "matricula" => $matricula,
            "anos_lectivos" => $anos_lectivos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.alunos.edit', $head);    
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $matriculas_editar
     * @return \Illuminate\Http\Response
     */
    public function matriculas_editar($id)
    {
        $matricula = Matricula::with(['aluno'])->findOrFail($id);
                
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $cursos = Curso::where('entidade_id', '=', $entidade->empresa->id)->get();
        $turnos = Turno::where('entidade_id', '=', $entidade->empresa->id)->get();
        $salas = Sala::where('entidade_id', '=', $entidade->empresa->id)->get();
        $anos_lectivos = AnoLectivo::where('entidade_id', '=', $entidade->empresa->id)->get();

        $head = [
            "titulo" => "aluno",
            "descricao" => env('APP_NAME'),
            "cursos" => $cursos,
            "turnos" => $turnos,
            "salas" => $salas,
            "matricula" => $matricula,
            "anos_lectivos" => $anos_lectivos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.alunos.edit-matricula', $head);    
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
            'nif.string' => 'O nif é um campo obrigatório',
            'email.string' => 'O e-mail é um campo obrigatório',
            'genero.string' => 'O genero é um campo obrigatório',
            'estado_civil.string' => 'O estado cívil é um campo obrigatório',
        ]);

                
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
    
            $aluno = Aluno::findOrFail($id);
                
            $user = User::with(['roles'])->findOrFail($aluno->id_user);
            $user->name = $request->nome;
            $user->email = $request->email;
            
            foreach ($user->roles as $role) {
                $user->removeRole($role);
            }
            
            $new_role = Role::findOrFail($request->id_user);
            $user->assignRole($new_role);
            
            $user->update();
    
            $aluno->update($request->all());
            
            if($request->matricula_id){
            
                $matricula = Matricula::find($request->matricula_id);
                
                $matricula->status = 'DESACTIVO';
                $matricula->valor_pagamento = $request->valor_pagamento;
                $matricula->user_id = Auth::user()->id; 
                $matricula->curso_id = $request->curso_id;
                $matricula->turno_id = $request->turno_id;
                $matricula->sala_id = $request->sala_id;
                $matricula->ano_lectivo_id = $request->ano_lectivo_id;
                $matricula->update();
            }
              
            $aluno->id_user = $user->id;
       
            $aluno->save();
            
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }


        return redirect()->route('alunos.index')->with("success", "Dados Actualizados com Sucesso!");
  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $aluno = Aluno::findOrFail($id);
        if($aluno->delete()){
            return redirect()->route('alunos.index')->with("success", "Dados Excluído com Sucesso!");
        }else{
            return redirect()->route('alunos.index')->with("warning", "Erro ao tentar Excluir aluno");
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function matriculas_excluir($id)
    {
        $matricula = Matricula::findOrFail($id);
        
        if($matricula->delete()){
            return redirect()->route('alunos-matriculas')->with("success", "Dados Excluído com Sucesso!");
        }else{
            return redirect()->route('alunos-matriculas')->with("warning", "Erro ao tentar Excluir Matrícula");
        }
    }
}
