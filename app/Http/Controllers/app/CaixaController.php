<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Caixa;
use App\Models\Conta;
use App\Models\Entidade;
use App\Models\Movimento;
use App\Models\Subconta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class CaixaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::findOrFail($entidade->empresa->id);

        $caixas = Caixa::where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => "Caixas",
            "descricao" => env('APP_NAME'),
            "caixas" => $caixas,
            "entidade" => $entidade,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.caixas.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        if(!isset($request->createLoja)){
            return redirect()->route('lojas.index');
        }
        
        $head = [
            "titulo" => "Cadastrar Caixas",
            "descricao" => env('APP_NAME'),
            "loja_id" => $request->createLoja,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.caixas.create', $head);
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
        ],[
            'nome.required' => 'O nome é um campo obrigatório'
        ]);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
                
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
                        
            $code = uniqid(time());
            $nova_conta = "";
            
            
            if($entidade->empresa->tem_permissao("Gestão Contabilidade")){
                $conta = Conta::where('conta', '45')->first();
                
                if($conta){
                    $c_ = Conta::findOrFail($conta->id);
                    $subc_ = Subconta::where('conta_id', $c_->id)->where('entidade_id', $entidade->empresa->id)->count();
                    
                    $numero =  $subc_ + 1;
                    
                    $nova_conta = $c_->conta. "." . $c_->serie . "." . $numero;
                    
                    $subconta = Subconta::create([
                        'entidade_id' => $entidade->empresa->id, 
                        'numero' => $nova_conta,
                        'nome' => $request->nome,
                        'tipo_conta' => 'M',
                        'code' => $code,
                        'status' => $c_->status,
                        'conta_id' => $c_->id,
                        'user_id' => Auth::user()->id,
                    ]);
                }else{
                    ######################
                    ## depois vamos dar o tratamento
                }
            }else {
                
                $cot = "45.1.";
                $subc_ = Caixa::where('conta', 'like', $cot . "%")->where('entidade_id', $entidade->empresa->id)->count();
                $numero =  $subc_ + 1;
                
                $nova_conta = $cot. "". $numero;
            }
            
            
            $caixas = Caixa::create([
                'nome' => $request->nome,
                'conta' => $nova_conta,
                'code' => $code,
                'status' => $request->status,
                'user_id' => Auth::user()->id,
                "tipo_caixa" => $request->tipo_caixa,
                "vencimento" => date("Y-m-d"), // $request->vencimento,
                "documento_predefinido" => $request->documento_predefinido,
                "aspecto" => $request->aspecto,
                "metodo_impressao" => $request->metodo_impressao,
                "modelo" => $request->modelo,
                "impressao_papel" => $request->impressao_papel,
                "modelo_email" => $request->modelo_email,
                "finalizar_avancado" => $request->finalizar_avancado,
                "referencia_produtos" => $request->referencia_produtos,
                "precos_produtos" => $request->precos_produtos,
                "modo_funcionamento" => $request->modo_funcionamento,
                "listar_produtos" => $request->listar_produtos,
                "grupo_precos" => $request->grupo_precos,
                "numeracao_pedidos_mesa" => $request->numeracao_pedidos_mesa,
                "loja_id" => $request->loja_id,
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
       
        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        //
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $caixa = Caixa::findOrFail($id);

        $movimentos = Movimento::where('caixa_id', '=', $caixa->id)
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('data_at', '>=', Carbon::createFromDate($value));
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('data_at', '<=', Carbon::createFromDate($value));
        })
        ->where('entidade_id', $entidade->empresa->id)
        ->with('user')
        ->orderBy('id', 'desc')
        ->get();
        
        $utilizadores = User::where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->get();

        $head = [
            "titulo" => "Detalhe Caixa",
            "descricao" => env('APP_NAME'),
            "caixa" => $caixa,
            "movimentos" => $movimentos,
            "dados" => $entidade,
            "utilizadores" => $utilizadores,
            "requests" => $request->all('data_inicio', 'data_final'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.caixas.show', $head);
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
        $caixa = Caixa::findOrFail($id);

        $head = [
            "titulo" => "Caixas",
            "descricao" => env('APP_NAME'),
            "caixa" => $caixa,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.caixas.edit', $head);
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
        $request->validate([
            'nome' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório'
        ]);
                       
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $code = uniqid(time());
            $nova_conta = "";
            
            $caixa = Caixa::findOrFail($id);
            
            if($entidade->empresa->tem_permissao("Gestão Contabilidade")){
                $conta = Conta::where('conta', '45')->first();
                
                
                if($caixa->code == NULL){
                    if($conta){
                    
                        $c_ = Conta::findOrFail($conta->id);
                        $subc_ = Subconta::where('conta_id', $c_->id)->where('entidade_id', $entidade->empresa->id)->count();
                        $numero =  $subc_ + 1;
                        $nova_conta = $c_->conta. "." . $c_->serie . "." . $numero;
                        
                        $subconta = Subconta::create([
                            'entidade_id' => $entidade->empresa->id, 
                            'numero' => $nova_conta,
                            'nome' => $request->nome,
                            'tipo_conta' => 'M',
                            'code' => $code,
                            'status' => $c_->status,
                            'conta_id' => $c_->id,
                            'user_id' => Auth::user()->id,
                        ]);
                    }else{
                        ######################
                        ## depois vamos dar o tratamento
                    }
                }else {
                    $subc_ = Subconta::where('code', $caixa->code)->where('entidade_id', $entidade->empresa->id)->first();
                    $nova_conta = $caixa->conta;
                    if($subc_){
                        $subc_up = Subconta::findOrFail($subc_->id);
                        $subc_up->numero = $nova_conta;
                        $subc_up->code = $code;
                        $subc_up->nome = $request->nome;
                        $subc_up->update();
                    }
                }
            
            }else {
                $nova_conta = $caixa->conta;   
            }
                       
            
            
            $caixa->nome = $request->nome;
            $caixa->conta = $nova_conta;
            $caixa->code = $code;
            $caixa->status = $request->status;
            $caixa->tipo_caixa = $request->tipo_caixa;
            $caixa->documento_predefinido = $request->documento_predefinido;
            $caixa->aspecto = $request->aspecto;
            $caixa->metodo_impressao = $request->metodo_impressao;
            $caixa->modelo = $request->modelo;
            $caixa->impressao_papel = $request->impressao_papel;
            $caixa->modelo_email = $request->modelo_email;
            $caixa->finalizar_avancado = $request->finalizar_avancado;
            $caixa->referencia_produtos = $request->referencia_produtos;
            $caixa->precos_produtos = $request->precos_produtos;
            $caixa->modo_funcionamento = $request->modo_funcionamento;
            $caixa->listar_produtos = $request->listar_produtos;
            $caixa->grupo_precos = $request->grupo_precos;
            $caixa->numeracao_pedidos_mesa = $request->numeracao_pedidos_mesa;
            $caixa->update();
            
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"], 200);
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
            
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $caixa = Caixa::findOrFail($id);
            $caixa->delete();
             
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Excluídos com sucesso!"], 200);
    }
}
