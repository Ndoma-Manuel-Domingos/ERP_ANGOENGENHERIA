<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\CursoModulo;
use App\Models\Formador;
use App\Models\Prova;
use App\Models\ProvaQuestao;
use App\Models\Turma;
use App\Models\TurmaFormador;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ProvaFormadorController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $entidade = User::with(['empresa', 'formador'])->findOrFail(Auth::user()->id);
        
        $provas = Prova::with(['formador', 'turma', 'entidade', 'user', 'questoes', 'modulo'])->where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->when($request->modulo_id, function($query, $value){
            $query->where('modulo_id', $value);
        })
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('data_at', '>=', Carbon::parse($value));
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('data_at', '<=', Carbon::parse($value));
        })
        ->where('formador_id', $entidade->formador->id)
        ->orderBy('created_at', 'desc')
        ->get();
        
        $modulos = CursoModulo::where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Provas",
            "descricao" => env('APP_NAME'),
            "provas" => $provas,
            "modulos" => $modulos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('formadores.provas.index', $head);
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
        $modulos = CursoModulo::where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Cadastrar Provas",
            "descricao" => env('APP_NAME'),
            "user" => Auth::user(),
            "turmas" => $turmas,
            "modulos" => $modulos,
            "user" => Auth::user(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('formadores.provas.create', $head);
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
            'nota_maxima' => 'required|string',
            'turma_id' => 'required|string',
            'data_at' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
            'nota_maxima.required' => 'A nota maxíma é um campo obrigatório',
            'turma_id.required' => 'A turma é um campo obrigatório',
            'data_at.required' => 'A data é um campo obrigatório',
        ]); 

        $entidade = User::with(['empresa', 'formador'])->findOrFail(Auth::user()->id);
          
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $prova = Prova::create([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'data_at' => $request->data_at,
                'modulo_id' => $request->modulo_id,
                'nota_maxima' => $request->nota_maxima,
                'turma_id' => $request->turma_id,
                'formador_id' => $entidade->formador->id,
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);
                                        
                                        
            foreach ($request->questao as $index => $quest) {
                ProvaQuestao::create([
                    
                    'prova_id' => $prova->id,
                    'questao' => $quest,
                    
                    'opcao_a' => $request->opcao_a[$index],
                    'opcao_b' => $request->opcao_b[$index],
                    'opcao_c' => $request->opcao_c[$index],
                    'opcao_d' => $request->opcao_d[$index],
                    'opcao_e' => $request->opcao_e[$index],
                    'nota' => $request->nota[$index],
                    'opcao_certa' => $request->opcao_certa[$index],
                    
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                  
                ]);
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
        $prova = Prova::with(['formador', 'turma', 'entidade', 'user', 'questoes'])->findOrFail($id);
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $head = [
            "titulo" => "Detalhe Prova",
            "descricao" => env('APP_NAME'),
            "prova" => $prova,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('formadores.provas.show', $head);    
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

        $entidade = User::with(['empresa', 'formador'])->findOrFail(Auth::user()->id);
        $turmas = Turma::where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Editar Prova",
            "descricao" => env('APP_NAME'),
            "prova" => $prova,
            "turmas" => $turmas,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('formadores.provas.edit', $head);    
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
            'nota_maxima' => 'required|string',
            'turma_id' => 'required|string',
            'data_at' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
            'nota_maxima.required' => 'A nota maxíma é um campo obrigatório',
            'turma_id.required' => 'A turma é um campo obrigatório',
            'data_at.required' => 'A data é um campo obrigatório',
        ]); 
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
    
            $prova = Prova::findOrFail($id);
            
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
                        
            // Deletar os registros atuais do funcionário para recriar os novos
            ProvaQuestao::where('prova_id', $prova->id)->delete();
            
            foreach ($request->questao as $index => $quest) {
                ProvaQuestao::create([
                    
                    'prova_id' => $prova->id,
                    'questao' => $quest,
                    
                    'opcao_a' => $request->opcao_a[$index],
                    'opcao_b' => $request->opcao_b[$index],
                    'opcao_c' => $request->opcao_c[$index],
                    'opcao_d' => $request->opcao_d[$index],
                    'opcao_e' => $request->opcao_e[$index],
                    'nota' => $request->nota[$index],
                    'opcao_certa' => $request->opcao_certa[$index],
                    
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                  
                ]);
            }
        
            $prova->update($request->all());
            
            $prova->save();
            
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
        $prova = Prova::findOrFail($id);
        
        $prova->delete();
        return redirect()->back()->with("success", "Dados Excluído com Sucesso!");

    }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_conteudo(Request $request)
    {
        //
        $entidade = User::with(['empresa', 'formador'])->findOrFail(Auth::user()->id);
 
        $uploads = Video::with(['formador', 'turma', 'entidade', 'user'])
        ->when($request->modulo_id, function($query, $value){
            $query->where('modulo_id', $value);
        })
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('data_at', '>=', Carbon::parse($value));
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('data_at', '<=', Carbon::parse($value));
        })
        ->where('entidade_id', $entidade->empresa->id)
        ->where('formador_id', $entidade->formador->id)
        ->where('type', 'pdf')
        ->get();
        
        $modulos = CursoModulo::where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Conteúdos",
            "descricao" => env('APP_NAME'),
            "modulos" => $modulos,
            "uploads" => $uploads,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('formadores.videos.index-conteudo', $head);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_conteudo(Request $request)
    {
        $entidade = User::with(['empresa', 'formador'])->findOrFail(Auth::user()->id);
        
        $turmas_ids = TurmaFormador::where('formador_id', $entidade->formador->id)->pluck('turma_id');
        $turmas = Turma::whereIn('id', [$turmas_ids])->get();  
        $modulos = CursoModulo::where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Cadastrar Conteudo",
            "descricao" => env('APP_NAME'),
            "turmas" => $turmas,
            "modulos" => $modulos,
            "user" => Auth::user(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('formadores.videos.create-conteudo', $head);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store_conteudo(Request $request)
    {
        $entidade = User::with(['empresa', 'formador'])->findOrFail(Auth::user()->id);
                    
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
                'modulo_id' => $request->modulo_id,
                'formador_id' => $entidade->formador->id,
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
    public function index_video(Request $request)
    {
        $entidade = User::with(['empresa', 'formador'])->findOrFail(Auth::user()->id);
        
        $uploads = Video::with(['formador', 'turma', 'entidade', 'user'])
        ->when($request->modulo_id, function($query, $value){
            $query->where('modulo_id', $value);
        })
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('data_at', '>=', Carbon::parse($value));
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('data_at', '<=', Carbon::parse($value));
        })
        ->where('entidade_id', '=', $entidade->empresa->id)
        ->where('formador_id', '=', $entidade->formador->id)
        ->where('type', 'video')
        ->get();
        
        $modulos = CursoModulo::where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Vídeos",
            "descricao" => env('APP_NAME'),
            "modulos" => $modulos,
            "uploads" => $uploads,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('formadores.videos.index-video', $head);
    }

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_video(Request $request)
    {
        $entidade = User::with(['empresa', 'formador'])->findOrFail(Auth::user()->id);
        
        $turmas_ids = TurmaFormador::where('formador_id', $entidade->formador->id)->pluck('turma_id');
        $turmas = Turma::whereIn('id', [$turmas_ids])->get();
        $modulos = CursoModulo::where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Cadastrar Vídeos",
            "descricao" => env('APP_NAME'),
            "turmas" => $turmas,
            "modulos" => $modulos,
            "user" => Auth::user(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('formadores.videos.create-video', $head);
    }
}
