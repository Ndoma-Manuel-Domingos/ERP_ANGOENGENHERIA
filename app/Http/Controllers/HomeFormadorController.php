<?php

namespace App\Http\Controllers;

use App\Models\Pauta;
use App\Models\Turma;
use App\Models\TurmaAluno;
use App\Models\TurmaFormador;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class HomeFormadorController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        
        $entidade = User::with(['empresa', 'formador'])->findOrFail(Auth::user()->id);
        
        $turmas = TurmaFormador::where('formador_id', $entidade->formador->id)->count();
        
        $head = [
            "titulo" => "Dashboard Formador",
            "descricao" => env('APP_NAME'),
            "total_turmas" => $turmas,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
                
        return view('formadores.dashboard', $head);
    }
    
    public function turmas()
    {
        $user = auth()->user();
        
        $entidade = User::with(['empresa', 'formador'])->findOrFail(Auth::user()->id);
        
        $total_turmas = TurmaFormador::where('formador_id', $entidade->formador->id)->count();
        $turmas_ids = TurmaFormador::where('formador_id', $entidade->formador->id)->pluck('turma_id');
        
        $turmas = Turma::whereIn('id', [$turmas_ids])->get();
        
        $head = [
            "titulo" => "Dashboard Furmas",
            "descricao" => env('APP_NAME'),
            "total_turmas" => $total_turmas,
            "turmas" => $turmas,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
                
        return view('formadores.turmas', $head);
    }
    
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function turmas_detalhes($id)
    {       
        //
        $user = auth()->user();
        
        if(!$user->can('listar turma')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $turma = Turma::with(['curso', 'turno', 'sala', 'ano_lectivo'])->findOrFail($id);
        
        $alunos = TurmaAluno::with(['aluno'])->where('turma_id', $turma->id)->get();

        $pautas = Pauta::where('turma_id', $turma->id)->get();

        $head = [
            "titulo" => "Detalhe turma",
            "descricao" => env('APP_NAME'),
            "turma" => $turma,
            "alunos" => $alunos,
            "pautas" => $pautas,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('formadores.turmas-show', $head);

    }
    
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function turma_visualizar_pautas($id)
    {       
        //
        $turma = Turma::with(['curso', 'turno', 'sala', 'ano_lectivo'])->findOrFail($id);
        
        $pautas = Pauta::with(['turma', 'aluno', 'ano_lectivo', 'entidade', 'user'])
        ->where('turma_id', $turma->id)
        ->get();

        $head = [
            "titulo" => "Detalhe turma",
            "descricao" => env('APP_NAME'),
            "turma" => $turma,
            "pautas" => $pautas,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('formadores.pautas', $head);

    }
    
        
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function turma_lancamento_pautas($id)
    {       
        //
        $pauta = Pauta::with(['turma', 'aluno'])->findOrFail($id);
        
        $head = [
            "titulo" => "Detalhe turma",
            "descricao" => env('APP_NAME'),
            "pauta" => $pauta,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('formadores.lancamento-nota', $head);

    }   
    
        
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function turma_lancamento_pautas_store(Request $request)
    {       
        //
        $pauta = Pauta::with(['turma', 'aluno'])->findOrFail($request->pauta_id);

        $pauta->prova_1 = $request->prova_1;
        $pauta->prova_2 = $request->prova_2;
        $pauta->prova_3 = $request->prova_3;
        
        $pauta->status = "ACTIVO";
        
        $media = ($request->prova_1 + $request->prova_2 + $request->prova_3) / 3;
        
        $pauta->media = $media;
        $pauta->exame = $request->exame;
        
        if($media >= 10){
            $resultado = "Aprovado";
        }else{
            $resultado = "Reprovado";
        }
        
        $pauta->resultado = $resultado;
        
        $pauta->update();
        
        return redirect()->back()->with("success", "Dados Actualizados com Sucesso!");
    
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

        $utilizador = User::findOrFail(Auth::user()->id);

        $head = [
            "titulo" => "Utilizador",
            "descricao" => env('APP_NAME'),
            "utilizador" => $utilizador,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('formadores.privacidade', $head);
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
            'senha.string' => 'A senha é um campo que deve obedecer letras e número',
            'nova_senha.required' => 'A nova senha é um campo obrigatório',
            'nova_senha.min' => 'A nova senha deve ter no minimo 3 caracteres',
            'nova_senha.max' => 'A nova senha deve ter no maximo 20 caracteres',
            'confirmar_senha.required' => 'Confirmar senha é um campo obrigatório',
            'confirmar_senha.min' => 'Confirmar senha deve ter no minimo 3 caracteres',
            'confirmar_senha.max' => 'Confirmar senha deve ter no maximo 20 caracteres',
        ]);
                
        if (!Hash::check($request->senha, Auth::user()->password)) {
            Alert::warning("Alerta!", "Senha actual invalída!");
            return redirect()->route('formadores-privacidade');
        }   
                  
        if ($request->nova_senha != $request->confirmar_senha) {
            Alert::warning("Alerta!", "Nova Senha e confirmação da nova senha não conferem!");
            return redirect()->route('formadores-privacidade');
        } 
        
        
        $user = User::findOrFail(Auth::user()->id);
        $user->password = Hash::make($request->nova_senha);
        $user->login_access = 1;
        $user->update();
        
        Alert::success("Sucesso!", "Dados Actualizados com Sucesso!");
        return redirect()->back();

    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function dados($id)
    {        
        $user = auth()->user();
        

        $utilizador = User::with(['roles'])->findOrFail($id);

        $head = [
            "titulo" => "Utilizador",
            "descricao" => env('APP_NAME'),
            "utilizador" => $utilizador,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('formadores.edit', $head);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function dados_update(Request $request, $id)
    {
        $user = auth()->user();
        
        $request->validate([
            'nome' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
            'nome.string' => 'O nome deve obedecer letras e número',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->nome;
        $user->email = $request->email;
        
        $user->update();

        Alert::success("Sucesso!", "Dados Actualizados com Sucesso!");
        return redirect()->back();
  
    }
    

}
