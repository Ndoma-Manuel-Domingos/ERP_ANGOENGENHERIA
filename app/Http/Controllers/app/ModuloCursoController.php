<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\CursoModulo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ModuloCursoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = auth()->user();
        
        if(!$user->can('listar curso')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $cursos = Curso::where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => "Cursos",
            "descricao" => env('APP_NAME'),
            "cursos" => $cursos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.cursos.index', $head);
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
        
        if(!$user->can('criar curso')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $head = [
            "titulo" => "Cadastrar Curso",
            "descricao" => env('APP_NAME'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.cursos.create', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $user = auth()->user();
        
        if(!$user->can('criar curso')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'nome' => 'required|string',
            'curso_id' => 'required|string',
        ],[
            'nome.required' => 'Modulo é um campo obrigatório',
            'curso_id.required' => 'Curso é um campo obrigatório',
        ]);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            $curso = CursoModulo::create([
                'nome' => $request->nome,
                'curso_id' => $request->curso_id,
                'descricao' => $request->descricao,
                'status' => 'activo',
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
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

        return response()->json(['message' => 'Dados criado com sucesso!'], 201);
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
        $user = auth()->user();
        
        if(!$user->can('listar curso')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
                
        $curso = Curso::findOrFail($id);
        
        $head = [
            "titulo" => "Detalhe Curso",
            "descricao" => env('APP_NAME'),
            "curso" => $curso,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.cursos.show', $head);

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
        
        if(!$user->can('editar curso')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $curso = Curso::findOrFail($id);

        $head = [
            "titulo" => "Curso",
            "descricao" => env('APP_NAME'),
            "curso" => $curso,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.cursos.edit', $head);
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
        
        if(!$user->can('editar curso')){
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
    
            $modulo = CursoModulo::findOrFail($id);
            
            $modulo->nome = $request->nome;
            $modulo->descricao = $request->descricao;
            $modulo->update();
            
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
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
        $user = auth()->user();
        
        if(!$user->can('eliminar curso')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
             $modulo = CursoModulo::findOrFail($id);
             $modulo->delete();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['mensagem' => 'Modulo excluído com sucesso!'], 200);
      
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function modulos($id)
    {        
        $user = auth()->user();
        
        if(!$user->can('criar curso')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $curso = Curso::findOrFail($id);
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $modulos = CursoModulo::where('curso_id', $curso->id)->where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Modulos de {$curso->nome}",
            "descricao" => env('APP_NAME'),
            "curso" => $curso,
            "modulos" => $modulos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.cursos.modulos', $head);
    }
}
