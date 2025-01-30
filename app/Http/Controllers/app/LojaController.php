<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Estoque;
use App\Models\ItensTransferencia;
use App\Models\Loja;
use App\Models\Produto;
use App\Models\Registro;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class LojaController extends Controller
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
        
        if(!$user->can('listar loja/armazem')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        } 
        
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $lojas = Loja::where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])
        ->with(['caixas', 'bancos'])
        ->orderBy('created_at', 'desc')
        ->get();

        $head = [
            "titulo" => "Lojas",
            "descricao" => env('APP_NAME'),
            "lojas" => $lojas,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.lojas.index', $head);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function gestao_lojas_armazem()
    {
                
        $user = auth()->user();
        
        if(!$user->can('gestao loja/armazem')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        } 
        
        //
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
      
        $lojas = Loja::where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])
        ->with(['caixas', 'produtos_estoques'])
        ->orderBy('created_at', 'desc')
        ->get();
      
        $head = [
            "titulo" => "Lojas",
            "descricao" => env('APP_NAME'),
            "lojas" => $lojas,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.lojas.gestao-lojas', $head);
    }
        
  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function gestao_lojas_armazem_detalhe($id)
    {
        //
        $user = auth()->user();
        
        if(!$user->can('gestao loja/armazem')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        } 
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $loja = Loja::findOrFail($id);
      
        $estoques = Estoque::where([
            ['entidade_id', '=', $entidade->empresa->id], 
            ['loja_id', '=', $loja->id], 
        ])
        ->with(['produto'])
        ->orderBy('created_at', 'desc')
        ->get();

        $head = [
            "titulo" => "Mais detalhe do Stock da Loja",
            "descricao" => env('APP_NAME'),
            "loja" => $loja,
            "estoques" => $estoques,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.lojas.gestao-lojas-detalhe', $head);
    }
 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transferencia_lojas_armazem()
    {
        $user = auth()->user();
        
        if(!$user->can('gestao loja/armazem')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        } 
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $items = ItensTransferencia::where('user_id', '=', Auth::user()->id)
        ->where('status', '=', 'em processo')
        ->where('code', '=', NULL)
        ->where('entidade_id', '=', $entidade->empresa->id)
        ->with('produto')
        ->get();
      
        $lojas = Loja::where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])
        ->with(['caixas', 'produtos_estoques'])
        ->orderBy('id', 'asc')
        ->get();
        
        $produtos = Produto::where('entidade_id', '=', $entidade->empresa->id)
        ->orderBy('nome', 'asc')
        ->get();
      
        $head = [
            "titulo" => "Transferências de Produtos Lojas/Armazém",
            "descricao" => env('APP_NAME'),
            "lojas" => $lojas,
            "produtos" => $produtos,
            "items" => $items,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.lojas.transferencia-lojas', $head);
    }
 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transferencia_lojas_armazem_remover_item($id)
    {
        $user = auth()->user();
        
        if(!$user->can('gestao loja/armazem')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        } 
        
        $transferencia = ItensTransferencia::findOrFail($id);

        if($transferencia->delete()){
            return redirect()->back();
        }            
    }
 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transferencia_lojas_armazem_item($id)
    {
        //
        
        $user = auth()->user();
        
        if(!$user->can('gestao loja/armazem')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        } 
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
                
        $produto = Produto::findOrFail($id);
                        
        $verificar = ItensTransferencia::where([
            'produto_id' => $produto->id,
            'user_id' => Auth::user()->id,
            'data_emissao' => date('Y-m-d'),
            'status' => 'em processo',
            'code' =>  NULL,
            'entidade_id' => $entidade->empresa->id,
        ])->first();

        if($verificar){
            Alert::error("Erro", "Este produto Já foi Adicionar... Pode alterar a quantidade");
            return redirect()->back();
        }
        
        $items = ItensTransferencia::create([
            'code' => NULL,
            'produto_id' => $produto->id,
            'armazem_origem_id' => NULL,
            'armazem_destino_id' => NULL,
            'quantidade' => 0,
            'quantidade_anterior' => 0,
            'status' => 'em processo',
            'user_id' => Auth::user()->id,
            'data_emissao' => date('Y-m-d'),
            'entidade_id' => $entidade->empresa->id,
        ]);

        if($items->save()){
            return redirect()->back();
        }else{
            Alert::error("Erro", "Ocorreu um erro ao tentar adicionar este produto");
            return redirect()->back();
        }
 
    }
      
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transferencia_lojas_armazem_store(Request $request)
    {
        //
        
        $user = auth()->user();
        
        if(!$user->can('gestao loja/armazem')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        } 
        
        $request->validate([
            "loja_origem_id" => 'required',
            "loja_destino_id" => 'required',
        ], [
        
        ]);
        
        try {
            // Inicia a transação
            DB::beginTransaction();
    
            if($request->loja_origem_id == $request->loja_destino_id){
                return redirect()->back()->with("warning", "Não podes fazer a transferência de Stocks na mesma Loja!");
            }
             
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            foreach($request->ids as $item){
            
                $actualizar_a_quantidade = ItensTransferencia::findOrFail($item);
            
                $estoque_origem = Estoque::where('loja_id', $request->loja_origem_id)
                    ->where('produto_id', $actualizar_a_quantidade->produto_id)
                    ->where('entidade_id', $entidade->empresa->id)
                    ->first();
                    
                $estoque_destino = Estoque::where('loja_id', $request->loja_destino_id)
                    ->where('produto_id', $actualizar_a_quantidade->produto_id)
                    ->where('entidade_id', $entidade->empresa->id)
                    ->first();
                    
                if($estoque_origem) {
                    $estoque_origem_update = Estoque::findOrFail($estoque_origem->id);
                    $estoque_origem_update->stock = $estoque_origem_update->stock - $request->input("quantidade{$item}");
                      
                    $estoque_origem_update->update();  
                    
                    Registro::create([
                        "registro" => "Saída de Stock",
                        "data_registro" => date('Y-m-d'),
                        "quantidade" => $request->input("quantidade{$item}"),
                        "produto_id" => $estoque_origem_update->produto_id,
                        "observacao" => "Transferência de Stocks do Armazém - Saída",
                        "loja_id" => $estoque_origem_update->loja_id,
                        "lote_id" => $estoque_origem_update->lote_id,
                        "user_id" => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
        
                }else{
                
                    $estoque_origem_dados = Estoque::findOrFail($request->loja_origem_id);
                    $estoque_destino_dados = Estoque::findOrFail($request->loja_destino_id);
                    
                    Estoque::create([
                        "loja_id" => $request->loja_origem_id,
                        "lote_id" => NULL,
                        "produto_id" => $actualizar_a_quantidade->produto_id,
                        "user_id" => Auth::user()->id,
                        "data_operacao" => date('Y-m-d'),
                        "stock" => $request->input("quantidade{$item}"),
                        "operacao" => 'Transferenca de Stock',
                        "observacao" => "Transferência de Stocks do Armazém: {$estoque_origem_dados->nome} para armazém: {$estoque_destino_dados->nome}",
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                    
                    Registro::create([
                        "registro" => "Saída de Stock",
                        "data_registro" => date('Y-m-d'),
                        "quantidade" => $request->input("quantidade{$item}"),
                        "produto_id" => $actualizar_a_quantidade->produto_id,
                        "observacao" => "Transferência de Stocks do Armazém - Saída",
                        "loja_id" => $estoque_origem_dados->loja_id,
                        "lote_id" => $estoque_origem_dados->lote_id,
                        "user_id" => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                    
                }
                
                if($estoque_destino) {
                    $estoque_destino_update = Estoque::findOrFail($estoque_destino->id);
                    $estoque_destino_update->stock = $estoque_destino_update->stock + $request->input("quantidade{$item}");
                    
                    $estoque_destino_update->update();
                    
                    Registro::create([
                        "registro" => "Entrada de Stock",
                        "data_registro" => date('Y-m-d'),
                        "quantidade" => $request->input("quantidade{$item}"),
                        "produto_id" => $estoque_destino_update->produto_id,
                        "observacao" => "Transferência de Stocks do Armazém - Entrada",
                        "loja_id" => $estoque_destino_update->loja_id,
                        "lote_id" => $estoque_destino_update->lote_id,
                        "user_id" => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                    
                }else{
                                
                    $estoque_origem_dados = Estoque::findOrFail($request->loja_origem_id);
                    $estoque_destino_dados = Estoque::findOrFail($request->loja_destino_id);
                    
                    
                    Estoque::create([
                        "loja_id" => $request->loja_destino_id,
                        "lote_id" => NULL,
                        "produto_id" => $actualizar_a_quantidade->produto_id,
                        "user_id" => Auth::user()->id,
                        "data_operacao" => date('Y-m-d'),
                        "stock" => $request->input("quantidade{$item}"),
                        "operacao" => 'Transferenca de Stock',
                        "observacao" => "Transferência de Stocks do Armazém: {$estoque_origem_dados->nome} para armazém: {$estoque_destino_dados->nome}",
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                    
                    Registro::create([
                        "registro" => "Saída de Stock",
                        "data_registro" => date('Y-m-d'),
                        "quantidade" => $request->input("quantidade{$item}"),
                        "produto_id" => $actualizar_a_quantidade->produto_id,
                        "observacao" => "Transferência de Stocks do Armazém - Saída",
                        "loja_id" => $estoque_origem_dados->loja_id,
                        "lote_id" => $estoque_origem_dados->lote_id,
                        "user_id" => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                    
                }
           
                $actualizar_a_quantidade->armazem_destino_id = $request->loja_destino_id;
                $actualizar_a_quantidade->armazem_origem_id = $request->loja_origem_id;
                $actualizar_a_quantidade->quantidade = $request->input("quantidade{$item}");
                $actualizar_a_quantidade->status = 'realizada';
              
                $actualizar_a_quantidade->update();
            
            }
            
            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger('Error', $e->getMessage());
            return redirect()->back()->with('danger', $e->getMessage());
            // return Response()->json($e->getMessage());
            // Trate o erro ou exiba uma mensagem de falha
            // por exemplo: return response()->json(['message' => 'Erro ao salvar'], 500);
        }
    
        return redirect()->back()->with("success", "Produto transferido com sucesso!");
   
    }
      
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
                
        $user = auth()->user();
        
        if(!$user->can('criar loja/armazem')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        } 

        $head = [
            "titulo" => "Cadastrar lojas",
            "descricao" => env('APP_NAME'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.lojas.create', $head);
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
        
        if(!$user->can('criar loja/armazem')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        } 
        
        $request->validate([
            'nome' => 'required|string',
        ]);
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $loja = Loja::create([
            'nome' => $request->nome,
            'status' => 'desactivo',
            'codigo_postal' => $request->codigo_postal,
            'morada' => $request->morada,
            'localidade' => $request->localidade,
            'telefone' => $request->telefone,
            'email' => $request->email,
            'cae' => $request->cae,
            'descricao' => $request->descricao,
            'user_id' => Auth::user()->id,
            'entidade_id' => $entidade->empresa->id, 
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
            'status.required' => 'O estado é um campo obrigatório',
            'email.required' => 'O e-mail é um campo obrigatório',
            'morada.required' => 'A morada é um campo obrigatório',
            'localidade.required' => 'A localidade é um campo obrigatório',
            'telefone.required' => 'O telefone é um campo obrigatório',
            'cae.required' => 'A cae é um campo obrigatório',
            'descricao.required' => 'A descrição é um campo obrigatório',
            'user_id.required' => 'O usuário é um campo obrigatório',
            'entidade_id.required' => 'A empresa é um campo obrigatório',
        ]);

        if($loja->save()){
            return redirect()->route('lojas.index')->with("success", "Dados Cadastrar com Sucesso!");
        }else{
            return redirect()->route('lojas.create')->with("warning", "Erro ao tentar cadastrar Lojas");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
                   
        $user = auth()->user();
        
        if(!$user->can('listar loja/armazem')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        } 
        
        $loja = Loja::findOrFail($id);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        // se estiver activo e´por esta sendo desactivo então verificamos a quantidade de lojas acticas caso so tem uma barramos
        if($loja->status == "desactivo"){
            
            $lojas = Loja::where([
                ['id', '!=', $loja->id],
                ['status', '=', 'activo'],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->count();

            if($lojas != 0){
                Alert::warning("Alerta!", "Tem que ter sempre uma loja activa");
                return redirect()->route('lojas.index')->with("warning", "Não pode ter duas lojas activa ao mesmo tempo, desactiva uma e volta activar a outra!");
            }
        }

        if($loja->status == "desactivo"){
            $loja->status = 'activo';
        }else{
            $loja->status = 'desactivo';
        }
        
        if($loja->update()){
            Alert::success("Sucesso!", "Loja Suspendida do successo");
            return redirect()->route('lojas.index');
        }else {
            Alert::error("Erro!", "Não foi possível Suspender a Loja");
            return redirect()->route('lojas.index');
        }
        
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
        
        if(!$user->can('editar loja/armazem')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        } 
        
        $loja = Loja::findOrFail($id);

        $head = [
            "titulo" => "Loja",
            "descricao" => env('APP_NAME'),
            "loja" => $loja,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.lojas.edit', $head);
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
                 
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $user = auth()->user();
        
        if(!$user->can('editar loja/armazem')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        } 
        
        $request->validate([
            'nome' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
        ]);

        $lojas = Loja::findOrFail($id);
        
        if($request->status == "activo"){
            
            $lojas = Loja::where([
                ['status', '=', 'activo'],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->count();
            
            if($lojas != 0){
                Alert::warning("Alerta!", "Tem que ter sempre uma loja activa");
                return redirect()->route('lojas.index')->with("warning", "Não pode ter duas lojas activa ao mesmo tempo, desactiva uma e volta activar a outra!");
            }
        }
        
        $lojas->update($request->all());

        if($lojas->update()){
            return redirect()->route('lojas.index')->with("success", "Dados Actualizados com Sucesso!");
        }else{
            return redirect()->route('lojas.edit')->with("warning", "Erro ao tentar Actualizar Loja");
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
        
        if(!$user->can('eliminar loja/armazem')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        } 
        
        $lojas = Loja::findOrFail($id);
        if($lojas->delete()){
            return redirect()->route('lojas.index')->with("success", "Dados Excluído com Sucesso!");
        }else{
            return redirect()->route('lojas.index')->with("warning", "Erro ao tentar Excluir Loja");
        }
    }
}
