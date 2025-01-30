<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Matricula;
use App\Models\Prova;
use App\Models\Role;
use App\Models\TurmaAluno;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class HomeAlunoController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        
        $entidade = User::with(['empresa', 'aluno'])->findOrFail(Auth::user()->id);
        $alunoTurmas = TurmaAluno::with(['turma.curso.modulos', 'turma.sala', 'turma.turno', 'turma.formadores.formador'])->where('aluno_id', $entidade->aluno->id)->get();
        
        $head = [
            "titulo" => "Dashboard Aluno",
            "descricao" => env('APP_NAME'),
            "entidade" => $entidade,
            "alunoTurmas" => $alunoTurmas,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
        
        return view('alunos.dashboard', $head);
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

        return view('alunos.privacidade', $head);
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
            'senha.string' => 'A senha deve ser um campo valido',
            'nova_senha.required' => 'A nova senha é um campo obrigatório',
            'nova_senha.min' => 'A nova senha deve ter no minimo 3 caracteres',
            'nova_senha.max' => 'A nova senha deve ter no maximo 20 caracteres',
            'confirmar_senha.required' => 'Confirmar senha é um campo obrigatório',
            'confirmar_senha.min' => 'Confirmar senha deve ter no minimo 3 caracteres',
            'confirmar_senha.max' => 'Confirmar senha deve ter no maximo 20 caracteres',
        ]);
                
        if (!Hash::check($request->senha, Auth::user()->password)) {
            Alert::warning("Alerta!", "Senha actual invalída!");
            return redirect()->route('alunos-privacidade');
        }   
                  
        if ($request->nova_senha != $request->confirmar_senha) {
            Alert::warning("Alerta!", "Nova Senha e confirmação da nova senha não conferem!");
            return redirect()->route('alunos-privacidade');
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

        return view('alunos.edit', $head);
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
            'nome.string' => 'O nome deve obedecer os letras e números',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->nome;
        $user->email = $request->email;
        
        $user->update();

        Alert::success("Sucesso!", "Dados Actualizados com Sucesso!");
        return redirect()->back();
  
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_conteudo()
    {
        //
        $entidade = User::with(['empresa', 'aluno'])->findOrFail(Auth::user()->id);
 
        $turmas_ids = TurmaAluno::where('aluno_id', $entidade->aluno->id)->pluck('turma_id');
        
        $uploads = Video::with(['formador', 'turma', 'entidade', 'user'])
        ->where('entidade_id', $entidade->empresa->id)
        ->whereIn('turma_id', $turmas_ids)
        ->where('type', 'pdf')
        ->get();
        
        $head = [
            "titulo" => "Conteúdos",
            "descricao" => env('APP_NAME'),
            "uploads" => $uploads,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('alunos.index-conteudo', $head);
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_video(Request $request)
    {
        $entidade = User::with(['empresa', 'aluno'])->findOrFail(Auth::user()->id);
        
        $turmas_ids = TurmaAluno::where('aluno_id', $entidade->aluno->id)->pluck('turma_id');
        
        $uploads = Video::with(['formador', 'turma', 'entidade', 'user'])
        ->where('entidade_id', '=', $entidade->empresa->id)
        ->whereIn('turma_id', $turmas_ids)
        ->where('type', 'video')
        ->get();
  
        $head = [
            "titulo" => "Vídeos",
            "descricao" => env('APP_NAME'),
            "uploads" => $uploads,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('alunos.index-video', $head);
    }
    
    public function provas()
    {
        //
        $entidade = User::with(['empresa', 'formador'])->findOrFail(Auth::user()->id);
        
        $turmas_ids = TurmaAluno::where('aluno_id', $entidade->aluno->id)->pluck('turma_id');
        
        $provas = Prova::with(['formador', 'turma', 'entidade', 'user', 'questoes'])->where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->whereIn('turma_id', $turmas_ids)
        ->orderBy('created_at', 'desc')
        ->get();
        
        $head = [
            "titulo" => "Provas",
            "descricao" => env('APP_NAME'),
            "provas" => $provas,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('alunos.provas', $head);
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function provas_detalhe($id)
    {
        $prova = Prova::with(['formador', 'turma', 'entidade', 'user', 'questoes'])->findOrFail($id);
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $head = [
            "titulo" => "Detalhe Prova",
            "descricao" => env('APP_NAME'),
            "prova" => $prova,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('alunos.prova-detalhes', $head);    
    }
    
    public function matriculas()
    {
        //
        $entidade = User::with(['empresa', 'aluno'])->findOrFail(Auth::user()->id);
 
        $matriculas = Matricula::with(['aluno', 'curso', 'sala', 'turno', "ano_lectivo"])
            ->where('aluno_id', '=', $entidade->aluno->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $head = [
            "titulo" => "Matrículas",
            "descricao" => env('APP_NAME'),
            "matriculas" => $matriculas,
        ];
    
        return view('alunos.matriculas', $head);
    }

}
