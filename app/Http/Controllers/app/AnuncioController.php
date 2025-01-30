<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Anuncio;
use App\Models\AnuncioUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

use function Ramsey\Uuid\v1;

class AnuncioController extends Controller
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
        
        // if(!$user->can('listar curso')){
        //     Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        //     return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $anuncios = Anuncio::where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => "anuncios",
            "descricao" => env('APP_NAME'),
            "anuncios" => $anuncios,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.anuncios.index', $head);
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
        
        // if(!$user->can('criar curso')){
        //     Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        //     return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }
        
        $head = [
            "titulo" => "Cadastrar Anuncios",
            "descricao" => env('APP_NAME'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.anuncios.create', $head);
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
        
        // if(!$user->can('criar curso')){
        //     Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        //     return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }
        
        $request->validate([
            'titulo' => 'required|string',
            'descricao' => 'required|string',
        ],[
            'titulo.required' => 'O titulo é um campo obrigatório',
            'descricao.required' => 'A descricao é um campo obrigatório',
        ]);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $users = User::where('entidade_id', $entidade->empresa->id)->get();
        
        try {
            DB::beginTransaction();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            
            $anuncio = Anuncio::create([
                'titulo' => $request->titulo,
                'descricao' => $request->descricao,
                'origem' => 'Entidade',
                'status' => $request->status,
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);
            
            if ($users) {
                foreach ($users as $u) {
                    
                    if($u->type_user == "Formador"){
                        $type = "formador";
                    }else if($u->type_user == "Aluno"){
                        $type = "aluno";
                    }else {
                        $type = "outro";
                    }
                    
                    AnuncioUser::create([
                        'model_id' => $u->id,
                        'anuncio_id' => $anuncio->id,
                        'status' => false,
                        'type' => $type,
                        'user_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                }
            }
    
            $anuncio->save();    
            
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"]);

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
        
        // if(!$user->can('listar curso')){
        //     Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        //     return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }
                
        $anuncio = Anuncio::findOrFail($id);
        
        $head = [
            "titulo" => "Detalhe do Anuncio",
            "descricao" => env('APP_NAME'),
            "anuncio" => $anuncio,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.anuncios.show', $head);

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
        
        // if(!$user->can('editar curso')){
        //     Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        //     return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }
        
        $anuncio = Anuncio::findOrFail($id);

        $head = [
            "titulo" => "anuncio",
            "descricao" => env('APP_NAME'),
            "anuncio" => $anuncio,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.anuncios.edit', $head);
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
        
        // if(!$user->can('editar curso')){
        //     Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        //     return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }
        
        $request->validate([
            'titulo' => 'required|string',
        ],[
            'titulo.required' => 'O titulo é um campo obrigatório',
        ]);
        
                
        try {
            DB::beginTransaction();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
    
            $anuncio = Anuncio::findOrFail($id);
            $anuncio->titulo = $request->titulo;
            $anuncio->descricao = $request->descricao;
            $anuncio->status = $request->status;
            $anuncio->update();
            
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"]);
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
        
        // if(!$user->can('eliminar curso')){
        //     Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        //     return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }
                        
        try {
            DB::beginTransaction();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            
            $anuncio = Anuncio::findOrFail($id);
            $anuncio->delete();
            
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Excluídos com sucesso!"]);
        
    }

}
