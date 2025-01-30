<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Formador;
use App\Models\Prova;
use App\Models\Turma;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class VideoController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        //
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $head = [
            "titulo" => "Vídeos e Conteudos",
            "descricao" => env('APP_NAME'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.videos.home', $head);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function conteudo()
    {
        //
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
 
        $uploads = Video::with(['formador', 'turma', 'entidade', 'user'])->where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->where('type', 'pdf')
        ->get();
        
        $head = [
            "titulo" => "Conteúdos",
            "descricao" => env('APP_NAME'),
            "uploads" => $uploads,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.videos.conteudo', $head);
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
 
        $uploads = Video::with(['formador', 'turma', 'entidade', 'user'])->where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->where('type', 'video')
        ->get();
  
        $head = [
            "titulo" => "Vídeos",
            "descricao" => env('APP_NAME'),
            "uploads" => $uploads,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.videos.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function conteudo_create(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $turmas = Turma::where('entidade_id', $entidade->empresa->id)->get();
        $formadores = Formador::where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Cadastrar Conteudo",
            "descricao" => env('APP_NAME'),
            "turmas" => $turmas,
            "formadores" => $formadores,
            "user" => Auth::user(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.videos.create-conteudo', $head);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function conteudo_store(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
                    
                    
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $extension = NULL;
            
            if($request->hasFile('arquivo') && $request->file('arquivo')->isValid()){
                $requestImage = $request->arquivo;
                $extension = $requestImage->extension();
                
                $imageName = $requestImage->getClientOriginalName() . strtotime("now") . "." . $extension;
    
                $request->arquivo->move(public_path('videos'), $imageName);
            }else{
                $imageName = NULL;
            }
            
            if($extension == 'pdf'){
                $xet = 'pdf';
            }else {
                $xet = 'video';
            }
            
            $video = Video::create([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'data_at' => $request->data_at,
                'arquivo' => $imageName,
                'turma_id' => $request->turma_id,
                'formador_id' => $request->formador_id,
                'type' => $xet,
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
        return redirect()->back()->with("success", "Dados Cadastrar com Sucesso!");
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $turmas = Turma::where('entidade_id', $entidade->empresa->id)->get();
        $formadores = Formador::where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Cadastrar Vídeos",
            "descricao" => env('APP_NAME'),
            "turmas" => $turmas,
            "formadores" => $formadores,
            "user" => Auth::user(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.videos.create', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    
        dd($request->all());
    
        // $request->validate([
        //     'nome' => 'required|string',
        //     'arquivo' => 'required|mimes:mp4|max:51200', // Máx. 50 MB
        //     'turma_id' => 'required|string',
        //     'formador_id' => 'required|string',
        //     'data_at' => 'required|string',
        // ],[
        //     'nome.required' => 'O nome é um campo obrigatório',
        //     'arquivo.required' => 'Campo Obrigatório',
        //     'turma_id.required' => 'Campo Obrigatório',
        //     'formador_id.required' => 'Campo Obrigatório',
        //     'data_at.required' => 'Campo Obrigatório',
        // ]); 

        // $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
          
        // try {
        //     DB::beginTransaction();
        //     // Realizar operações de banco de dados aqui
            
        //     if($request->hasFile('arquivo') && $request->file('arquivo')->isValid()){
        //         $requestImage = $request->arquivo;
        //         $extension = $requestImage->extension();
    
        //         $imageName = md5($requestImage->getClientOriginalName() . strtotime("now") . "." . $extension);
    
        //         $request->imagem->move(public_path('videos'), $imageName);
        //     }else{
        //         $imageName = NULL;
        //     }
            
        //     dd($imageName);
            
        //     $file = $request->file('arquivo');
        //     $extension = $file->getClientOriginalExtension();
            
        //     // Define o diretório correto
        //     $folder = $extension === 'pdf' ? 'pdfs' : 'videos';
        //     $path = $file->storeAs('public/' . $folder, $file->getClientOriginalName());

        //     $video = Video::create([
        //         'nome' => $request->nome,
        //         'descricao' => $request->descricao,
        //         'data_at' => $request->data_at,
        //         'arquivo' => $request->arquivo,
        //         'turma_id' => $request->turma_id,
        //         'formador_id' => $request->formador_id,
        //         'name' => $file->getClientOriginalName(),
        //         'path' => $folder . '/' . $file->getClientOriginalName(),
        //         'type' => $extension === 'pdf' ? 'pdf' : 'video',
        //         'user_id' => Auth::user()->id,
        //         'entidade_id' => $entidade->empresa->id,
        //     ]);
            
        //     // Se todas as operações foram bem-sucedidas, você pode fazer o commit
        //     DB::commit();
        // } catch (\Exception $e) {
        //     // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
        //     DB::rollback();

        //     Alert::warning('Informação', $e->getMessage());
        //     return redirect()->back();
        //     // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        // }
        // return redirect()->back()->with("success", "Dados Cadastrar com Sucesso!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $prova = Prova::with(['formador', 'turma', 'entidade', 'user', 'questoes'])->findOrFail($id);
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $head = [
            "titulo" => "Detalhe Prova",
            "descricao" => env('APP_NAME'),
            "prova" => $prova,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.provas.show', $head);    
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $prova = Prova::with(['formador', 'turma', 'entidade', 'user', 'questoes'])->findOrFail($id);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $turmas = Turma::where('entidade_id', $entidade->empresa->id)->get();
        $formadores = Formador::where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Editar Prova",
            "descricao" => env('APP_NAME'),
            "prova" => $prova,
            "turmas" => $turmas,
            "formadores" => $formadores,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.provas.edit', $head);    
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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function conteudo_eliminar($id)
    {
        $video = Video::findOrFail($id);
        
        $video->delete();
        return redirect()->back()->with("success", "Dados Excluído com Sucesso!");

    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $prova = Prova::findOrFail($id);
        
        $prova->delete();
        return redirect()->back()->with("success", "Dados Excluído com Sucesso!");

    }
    

}
