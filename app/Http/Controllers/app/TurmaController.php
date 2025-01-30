<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Curso;
use App\Models\Sala;
use App\Models\Turma;
use App\Models\Matricula;
use App\Models\Turno;
use App\Models\AnoLectivo;
use App\Models\Categoria;
use App\Models\Estoque;
use App\Models\Formador;
use App\Models\Imposto;
use App\Models\Loja;
use App\Models\lojaProduto;
use App\Models\Marca;
use App\Models\Motivo;
use App\Models\Pauta;
use App\Models\Produto;
use App\Models\Registro;
use App\Models\TurmaAluno;
use App\Models\TurmaFormador;
use App\Models\User;
use App\Models\Variacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class TurmaController extends Controller
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
        
        if(!$user->can('listar turma')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $turmas = Turma::with(['curso', 'turno', 'sala', 'ano_lectivo'])->where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => "Turma",
            "descricao" => env('APP_NAME'),
            "turmas" => $turmas,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.turmas.index', $head);
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
        
        if(!$user->can('criar turma')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $salas = Sala::where('entidade_id', $entidade->empresa->id)->get();
        $cursos = Curso::where('entidade_id', $entidade->empresa->id)->get();
        $turnos = Turno::where('entidade_id', $entidade->empresa->id)->get();
        $anos_lectivos = AnoLectivo::where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Cadastrar Turma",
            "descricao" => env('APP_NAME'),
            "salas" => $salas,
            "cursos" => $cursos,
            "turnos" => $turnos,
            "anos_lectivos" => $anos_lectivos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.turmas.create', $head);
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
        
        if(!$user->can('criar turma')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'nome' => 'required|string',
            'curso_id' => 'required',
            'preco' => 'required',
            'sala_id' => 'required',
            'turno_id' => 'required',
            'ano_lectivo_id' => 'required',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
            'preco.required' => 'O preço é um campo obrigatório',
            'curso_id.required' => 'O curso é um campo obrigatório',
            'sala_id.required' => 'A sala é um campo obrigatório',
            'turno_id.required' => 'O turno é um campo obrigatório',
            'ano_lectivo_id.required' => 'O ano lectivo é um campo obrigatório',
        ]);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
            $conta = "62.1.";
            
            $verifica_conta_contabilidade = Produto::where('entidade_id', $entidade->empresa->id)->where('conta', 'like', "{$conta}%")->count();
            
            $nova_conta = $conta . $verifica_conta_contabilidade + 1;
            
            $motivo = Motivo::findOrFail($entidade->empresa->motivo_id);
            $imposto = Imposto::findOrFail($entidade->empresa->imposto_id);
            
            $categoria = Categoria::where('entidade_id', $entidade->empresa->id)->where('categoria', "-- Sem Categoria --")->first();
            $marca = Marca::where('entidade_id', $entidade->empresa->id)->where('nome', "-- Sem Marca --")->first();
            $variacao = Variacao::where('entidade_id', $entidade->empresa->id)->where('nome', "-- Sem Variação --")->first();
            
            $imageName = NULL;
            
            $code = time();
  
            $turma = Turma::create([
                'nome' => $request->nome,
                'preco' => $request->preco,
                'status' => $request->status,
                'user_id' => Auth::user()->id,
                'curso_id' => $request->curso_id,
                'sala_id' => $request->sala_id,
                'turno_id' => $request->turno_id,
                'ano_lectivo_id' => $request->ano_lectivo_id,
                'entidade_id' => $entidade->empresa->id,
            ]);
                                        
            $produto = Produto::create([
                "type_model_id" => $turma->id,
                "nome" => $request->nome,
                "codigo_barra" => $code,
                "referencia" => $code,
                'conta' => $nova_conta,
                'conta_ordem' => $verifica_conta_contabilidade + 1,
                "descricao" => $request->nome,
                "incluir_factura" => "Não",
                "imagem" => $imageName,
                "variacao_id" => $variacao->id ?? NULL,
                "categoria_id" => $categoria->id ?? NULL,
                "marca_id" => $marca->id ?? NULL,
                "imposto_id" => $imposto->id ?? NULL,
                "tipo" => 'S',
                "unidade" => 'uni',
                "imposto" => $imposto->codigo,
                "taxa" => $imposto->valor,
                "motivo_isencao" => $motivo->codigo,
                "motivo_id" => $motivo->id ?? NULL,
                "preco_custo" => $request->preco,
                "preco" => $request->preco,
                "margem" => 0,
                "preco_venda" => $request->preco,
                "controlo_stock" => "Sim",
                "tipo_stock" => "M",
                "disponibilidade" => $request->disponibilidade,
                "status" => $request->status,          
                "user_id" => Auth::user()->id,   
                'entidade_id' =>  $entidade->empresa->id,      
                // 'data_criacao' => $request->data_criacao,
                // 'data_expiracao'  => $request->data_expiracao,
            ]);
            
            if($produto->save()){
                $lojas = Loja::where([
                    ['entidade_id', '=', $entidade->empresa->id],
                ])->get();
                
                foreach ($lojas as $loja) {
                    $estoque = Estoque::create([
                        "loja_id" => $loja->id,
                        "produto_id" => $produto->id,
                        "user_id" => Auth::user()->id,
                        "data_operacao" => date('Y-m-d'),
                        "stock" => 999999999,
                        "observacao" => 'Entrada inicial de produtos de Stock',
                        "stock_minimo" => 0,
                        "operacao" => "Actualizar de Stock",
                        'entidade_id' => $entidade->empresa->id,
                    ]);   
                    
                    Registro::create([
                        "registro" => "Entrada de Stock",
                        "data_registro" => date('Y-m-d'),
                        "quantidade" => 999999999,
                        "produto_id" => $produto->id,
                        "observacao" => 'Entrada inicial de produtos de Stock',
                        "loja_id" => $estoque->loja_id,
                        "lote_id" => $estoque->lote_id,
                        "user_id" => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                }
            }
        
            foreach ($lojas as $loja) {
                $saveProdutoLoja = lojaProduto::create([
                    'produto_id' => $produto->id,
                    'loja_id' => $loja->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
                $saveProdutoLoja->save();                    
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function turma_adicionar_aluno_store(Request $request)
    {
        //
        $request->validate([
            'aluno_id' => 'required',
            'turma_id' => 'required',
            'matricula_id' => 'required',
        ],[
            'aluno_id.required' => 'O aluno é um campo obrigatório',
            'turma_id.required' => 'A turma é um campo obrigatório',
            'matricula_id.required' => 'A matrícula é um campo obrigatório',
        ]);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $turma = Turma::findOrFail($request->turma_id);
        $aluno = Aluno::findOrFail($request->aluno_id);
        $matricula = Matricula::findOrFail($request->matricula_id);
        
        if($turma->curso_id == $matricula->curso_id && $turma->turno_id == $matricula->turno_id){
        
            $verificar = TurmaAluno::where([
                'turma_id' => $turma->id,
                'matricula_id' => $matricula->id,
                'aluno_id' => $aluno->id,
                'ano_lectivo_id' => $matricula->ano_lectivo_id,
            ])->first();
            
            if($verificar){
                return redirect()->route('turma-adicionar-aluno', $aluno->id)->with("warning", "Este aluno já está nesta turma!");
            }
            
            $adicionar = TurmaAluno::create([
                'status' => 'ACTIVO',
                'user_id' => Auth::user()->id,
                'turma_id' => $turma->id,
                'matricula_id' => $matricula->id,
                'aluno_id' => $aluno->id,
                'ano_lectivo_id' => $matricula->ano_lectivo_id,
                'entidade_id' => $entidade->empresa->id,
            ]);
            
            if($adicionar->save()){
                return redirect()->route('turma-adicionar-aluno', $aluno->id)->with("success", "Aluno adicionado com sucesso!");
            }
            
        }else{
            return redirect()->route('turma-adicionar-aluno', $aluno->id)->with("danger", "Este turma não foi preparada para receber este aluno, o curso de turno da matricula não corresponde com o curso e turno da turma!");
        }

    }
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function turma_adicionar_formador_store(Request $request)
    {
        //
        $request->validate([
            'formador_id' => 'required',
            'turma_id' => 'required',
        ],[
            'formador_id.required' => 'O formador é um campo obrigatório',
            'turma_id.required' => 'A turma é um campo obrigatório',
        ]);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $turma = Turma::findOrFail($request->turma_id);
        $formador = Formador::findOrFail($request->formador_id);
     
        $verificar = TurmaFormador::where([
            'turma_id' => $turma->id,
            'formador_id' => $formador->id,
            'ano_lectivo_id' => $turma->ano_lectivo_id,
        ])->first();
             
        
        if($verificar){
            return redirect()->route('turma-adicionar-formador', $formador->id)->with("warning", "Este Formador já está nesta turma!");
        }
   
        $adicionar = TurmaFormador::create([
            'status' => 'ACTIVO',
            'user_id' => Auth::user()->id,
            'turma_id' => $turma->id,
            'formador_id' => $formador->id,
            'ano_lectivo_id' => $turma->ano_lectivo_id,
            'entidade_id' => $entidade->empresa->id,
        ]);
        
        if($adicionar->save()){
            return redirect()->route('turma-adicionar-formador', $formador->id)->with("success", "Formador adicionado com sucesso!");
        }
      
        return redirect()->route('turma-adicionar-formador', $formador->id)->with("danger", "Este turma não foi preparada para receber este formador, o curso de turno da matricula não corresponde com o curso e turno da turma!");

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
        
        if(!$user->can('listar turma')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        
        $turma = Turma::with(['curso', 'turno', 'sala', 'ano_lectivo'])->findOrFail($id);
        
        $formadores = TurmaFormador::with(['formador'])->where('turma_id', $turma->id)->get();
        
        $alunos = TurmaAluno::with(['aluno'])->where('turma_id', $turma->id)->get();

        $pautas = Pauta::where('turma_id', $turma->id)->get();

        $head = [
            "titulo" => "Detalhe turma",
            "descricao" => env('APP_NAME'),
            "turma" => $turma,
            "formadores" => $formadores,
            "alunos" => $alunos,
            "pautas" => $pautas,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.turmas.show', $head);

    }
        
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function turma_distribuir_pautas($id)
    {       
        //
        $turma = Turma::with(['curso', 'turno', 'sala', 'ano_lectivo'])->findOrFail($id);
        
        $formadores = TurmaFormador::with(['formador'])->where('turma_id', $turma->id)->get();
        
        $alunos = TurmaAluno::with(['aluno'])->where('turma_id', $turma->id)->get();
        
        foreach($alunos AS $item){
            $verificar = Pauta::where('aluno_id', $item->aluno->id)->where('turma_id', $turma->id)->where('entidade_id', $turma->entidade_id)->first();
            
            if(!$verificar){
                
                $pauta = Pauta::create([
                    'aluno_id' => $item->aluno->id,
                    'turma_id' => $turma->id,
                    'user_id' => Auth::user()->id,
                    'prova_1' => 0,
                    'prova_2' => 0,
                    'prova_3' => 0,
                    'status' => 'DESACTIVO',
                    'media' => 0,
                    'exame' => 0,
                    'resultado' => 'Nao Definido',
                    'ano_lectivo_id' => $turma->ano_lectivo_id,
                    'entidade_id' => $turma->entidade_id,
                ]);
            
            }
            
        }
        
        return redirect()->back()->with("success", "Dados Actualizados com Sucesso!");

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

        return view('dashboard.turmas.pautas', $head);

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

        return view('dashboard.turmas.lancamento-nota', $head);

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function turma_adicionar_aluno($aluno_id)
    {
        //
        $aluno = Aluno::findOrFail($aluno_id);
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $turmas = Turma::with(['curso', 'sala', 'turno'])->where('entidade_id', $entidade->empresa->id)->get();
        $alunos = Aluno::where('entidade_id', $entidade->empresa->id)->get();
        $matriculas = Matricula::with(['curso', 'sala', 'turno'])->where('aluno_id', $aluno->id)->where('entidade_id', $entidade->empresa->id)->get();
        
     
        $head = [
            "titulo" => "Adicionar Aluno á Turma",
            "descricao" => env('APP_NAME'),
            "aluno" => $aluno,
            "turmas" => $turmas,
            "alunos" => $alunos,
            "matriculas" => $matriculas,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.turmas.adicionar-aluno', $head);

    }
     
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function turma_adicionar_formador($formador_id)
    {
        //
        $formador = Formador::findOrFail($formador_id);
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $turmas = Turma::with(['curso', 'sala', 'turno'])->where('entidade_id', $entidade->empresa->id)->get();
        $formadores = Formador::where('entidade_id', $entidade->empresa->id)->get();

     
        $head = [
            "titulo" => "Adicionar Formador á Turma",
            "descricao" => env('APP_NAME'),
            "formador" => $formador,
            "turmas" => $turmas,
            "formadores" => $formadores,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.turmas.adicionar-formador', $head);

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
        $user = auth()->user();
        
        if(!$user->can('editar turma')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $turma = Turma::with(['curso', 'turno', 'sala', 'ano_lectivo'])->findOrFail($id);
           
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $salas = Sala::where('entidade_id', $entidade->empresa->id)->get();
        $cursos = Curso::where('entidade_id', $entidade->empresa->id)->get();
        $turnos = Turno::where('entidade_id', $entidade->empresa->id)->get();
        $anos_lectivos = AnoLectivo::where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "turma",
            "descricao" => env('APP_NAME'),
            "turma" => $turma,
            "salas" => $salas,
            "turnos" => $turnos,
            "cursos" => $cursos,
            "anos_lectivos" => $anos_lectivos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.turmas.edit', $head);
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
        $user = auth()->user();
        
        if(!$user->can('editar turma')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'nome' => 'required|string',
            'curso_id' => 'required',
            'preco' => 'required',
            'sala_id' => 'required',
            'turno_id' => 'required',
            'ano_lectivo_id' => 'required',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
            'curso_id.required' => 'O curso é um campo obrigatório',
            'preco.required' => 'O preço é um campo obrigatório',
            'sala_id.required' => 'O sala é um campo obrigatório',
            'turno_id.required' => 'O turno é um campo obrigatório',
            'ano_lectivo_id.required' => 'O ano lectivo é um campo obrigatório',
        ]);
        
                
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
                     
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
    
            $turma = Turma::findOrFail($id);
            
            $turma->nome = $request->nome;
            $turma->preco = $request->preco;
            $turma->status = $request->status;
            $turma->curso_id = $request->curso_id;
            $turma->sala_id = $request->sala_id;
            $turma->turno_id = $request->turno_id;
            $turma->ano_lectivo_id = $request->ano_lectivo_id;
            
            $turma->update();
            
            $produto = Produto::where('entidade_id', $entidade->empresa->id)->where('type_model_id', $turma->id)->first();
            
            $produto->nome = $request->nome;
            $produto->preco_custo = $request->preco;
            $produto->preco = $request->preco;
            $produto->margem = 0;
            $produto->preco_venda = $request->preco;
            $produto->update();
            
        
            
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
        //
                $user = auth()->user();
        
        if(!$user->can('eliminar sala')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $turma = Turma::findOrFail($id);
        if($turma->delete()){
            return redirect()->back()->with("success", "Dados Excluído com Sucesso!");
        }else{
            return redirect()->back()->with("warning", "Erro ao tentar Excluir turno");
        }
    }
}
