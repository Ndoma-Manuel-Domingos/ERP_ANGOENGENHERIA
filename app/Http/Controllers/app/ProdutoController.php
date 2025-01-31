<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Imports\ProdutoImport;
use App\Models\Categoria;
use App\Models\Conta;
use App\Models\Subconta;
use App\Models\Entidade;
use App\Models\Estoque;
use App\Models\Imposto;
use App\Models\Itens_venda;
use App\Models\Loja;
use App\Models\lojaProduto;
use App\Models\Lote;
use App\Models\Marca;
use App\Models\Motivo;
use App\Models\Movimento;
use App\Models\Produto;
use App\Models\ProdutoGrupoPreco;
use App\Models\Registro;
use App\Models\User;
use App\Models\Variacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('listar produtos')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $produtos = Produto::with(['categoria', 'marca', 'taxa_imposto'])->when($request->nome_referencia, function($query, $value){
            $query->where('nome', 'LIKE', "%{$value}%");
            $query->orWhere('referencia', 'LIKE', "%{$value}%");
            $query->orWhere('codigo_barra', 'LIKE', "%{$value}%");
        })
        ->when($request->categoria_id, function($query, $value){
            $query->where('categoria_id', '=', $value);
        })
        ->when($request->tipo, function($query, $value){
            $query->where('tipo', '=', $value);
        })
        ->when($request->marca_id, function($query, $value){
            $query->where('marca_id', '=', $value);
        })
        ->where('entidade_id', '=', $entidade->empresa->id)
        ->orderBy('nome', 'asc')
        ->get();

        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Produtos",
            "descricao" => env('APP_NAME'),
            "produtos" => $produtos,
            "empresa" => $empresa,
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),

            "categorias" => Categoria::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),

            "marcas" => Marca::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "requests" => $request->all('categoria_id', 'tipo', 'marca_id'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.produtos.index', $head);
    }
    
    
       /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_import()
    {
        $user = auth()->user();
        
        if(!$user->can('criar produtos')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes", "categorias", "marcas")->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Cadastrar Produto",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "categorias" => Categoria::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "motivos" => Motivo::get(),
            "impostos" => Imposto::get(),
            "marcas" => Marca::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "variacoes" => Variacao::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.produtos.create-import', $head);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store_import(Request $request)
    {        
        $user = auth()->user();
        
        if(!$user->can('criar produtos')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);
        
        try {
            Excel::import(new ProdutoImport, $request->file('file'));
            return redirect()->back()->with('success', 'Dados importados com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao importar dados: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao importar dados: ' . $e->getMessage());
        }
    } 
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        
        if(!$user->can('criar produtos')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes", "categorias", "marcas")->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Cadastrar Produto",
            "descricao" => env('APP_NAME'),

            "referencia" => time(),
            "codigo_barra" => time(),

            "empresa" => $empresa,
            "categorias" => Categoria::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "motivos" => Motivo::get(),
            "impostos" => Imposto::get(),
            "marcas" => Marca::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "variacoes" => Variacao::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.produtos.create', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('criar produtos')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
      
        
        $request->validate([
            'nome' => 'required|string',
            'variacao_id' => 'required|string',
            'categoria_id' => 'required|string',
            'marca_id' => 'required|string',
            'tipo' => 'required|string',
            'controlo_stock' => 'required',
            'tipo_stock' => 'required',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
            'variacao_id.required' => 'A variação é um campo obrigatório',
            'categoria_id.required' => 'A categoria é um campo obrigatório',
            'marca_id.required' => 'A marca é um campo obrigatório',
            'tipo.required' => 'O tipo é um campo obrigatório',
            'controlo_stock.required' => 'O controlo de stock é um campo obrigatório',
            'tipo_stock.required' => 'O tipo de stock é um campo obrigatório',
        ]);
        
        $entidade = User::with('empresa.tipo_entidade.modulos')->findOrFail(Auth::user()->id);
       
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            if($request->preco_venda == null){
                $request->preco_venda = $request->preco;
            }
          
    
            if($request->hasFile('imagem') && $request->file('imagem')->isValid()){
                $requestImage = $request->imagem;
                $extension = $requestImage->extension();
    
                $imageName = md5($requestImage->getClientOriginalName() . strtotime("now") . "." . $extension);
    
                $request->imagem->move(public_path('images/produtos'), $imageName);
            }else{
                $imageName = NULL;
            }
            
            $code = uniqid(time());
            
            $nova_conta = "";
            
            if($entidade->empresa->tem_permissao("Gestão Contabilidade")){
               
                if($request->tipo == "S"){
                    // 26.1
                    $conta = Conta::where('conta', '62')->first();
                    $serie = "62.1.1";
                    
                    $qtds = 0;
                    $observacao = "Registro de serviço";
                }else {
                    if($request->tipo_stock == "M"){
                        // 26.1
                        $conta = Conta::where('conta', '26')->first();
                        $serie = "26.1";
                    }
                    
                    if($request->tipo_stock == "P"){
                        // 22.1
                        $conta = Conta::where('conta', '22')->first();
                        $serie = "22.1";
                    }
                    
                    if($request->tipo_stock == "P1"){
                        // 22.2
                        $conta = Conta::where('conta', '22')->first();
                        $serie = "22.2";
                    }
                    
                    if($request->tipo_stock == "P2"){
                        // 22.4
                        $conta = Conta::where('conta', '22')->first();
                        $serie = "22.4";
                    }
                    
                    if($request->tipo_stock == "A"){
                        $conta = Conta::where('conta', '24')->first();
                        $serie = "24.1";
                    }
                    
                    
                    if($request->tipo_stock == "A1"){
                        $conta = Conta::where('conta', '24')->first();
                        $serie = "24.2";
                    }
                    
                    if($request->tipo_stock == "S"){
                        $conta = Conta::where('conta', '25')->first();
                        $serie = "25.1";
                    }
                    
                    if($request->tipo_stock == "S1"){
                        $conta = Conta::where('conta', '25')->first();
                        $serie = "25.2";
                    }
                    
                    if($request->tipo_stock == "T"){
                        $conta = Conta::where('conta', '23')->first();
                        $serie = "23";
                    }
                    
                    $qtds = $request->quantidade_inicial_stock ?? 0;
                    $observacao = "Entrada de Existência";
                }
                
                if($conta){
                    
                    $subc_ = Subconta::where('numero', 'like', $serie . "%")->where('entidade_id', $entidade->empresa->id)->count();
                    $numero =  $subc_ + 1;
                    
                    $nova_conta = $serie. "." . $numero;
                    
                    $subconta = Subconta::create([
                        'entidade_id' => $entidade->empresa->id, 
                        'numero' => $nova_conta,
                        'nome' => $request->nome,
                        'tipo_conta' => 'M',
                        'code' => $code,
                        'status' => $conta->status,
                        'conta_id' => $conta->id,
                        'user_id' => Auth::user()->id,
                    ]);
                    
                    $movimeto = Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $subconta->id,
                        'status' => true,
                        'movimento' => 'E',
                        'credito' => 0,
                        'debito' => $request->preco_custo * $qtds,
                        'observacao' => $observacao,
                        'code' => $code,
                        'data_at' => date("Y-m-d"),
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => 1,
                        'periodo_id' => 12,
                    ]);
                    
                }else{
                    ## outros tratamento depois
                }
          
            }else {
          
                if($request->tipo == "S"){
                    $qtds = 0;
                    $observacao = "Registro de serviço";                
                    $serie = "62.1";
                }else {
                    $qtds = $request->quantidade_inicial_stock ?? 0;
                    $observacao = "Entrada de Existência";
                    $serie = "26.1";
                }
                
                $subc_ = Produto::where('conta', 'like', $serie . "%")->where('entidade_id', $entidade->empresa->id)->count();
                $numero =  $subc_ + 1;
                
                $nova_conta = $serie. "." . $numero;
            
            }
    
            $motivo = Motivo::findOrFail($request->motivo_isencao ?? $entidade->empresa->motivo_id);
            
            $imposto = Imposto::findOrFail($request->imposto ?? $entidade->empresa->imposto_id);
            
            $produto = Produto::create([
                "nome" => $request->nome,
                "codigo_barra" => $request->codigo_barra,
                "referencia" => $request->referencia,
                'conta' => $nova_conta,
                'code' => $code,
                "descricao" => $request->descricao != "" ? $request->descricao : $request->nome,
                "incluir_factura" => $request->incluir_factura,
                "imagem" => $imageName,
                "variacao_id" => $request->variacao_id,
                "categoria_id" => $request->categoria_id,
                "imposto_id" => $imposto->id,
                "marca_id" => $request->marca_id,
                "tipo" => $request->tipo,
                "unidade" => $request->unidade,
                "imposto" => $imposto->codigo,
                "taxa" => $imposto->valor,
                "motivo_isencao" => $motivo->codigo,
                "motivo_id" => $motivo->id,
                "preco_custo" => $request->preco_custo,
                "preco" => $request->preco,
                "margem" => $request->margem,
                "preco_venda" => $request->preco_venda,
                "controlo_stock" => $request->controlo_stock,
                "tipo_stock" => $request->tipo_stock,
                "disponibilidade" => $request->disponibilidade,
                "status" => $request->status,          
                "subconta_id" => $subconta->id ?? 1,          
                "user_id" => Auth::user()->id,   
                'entidade_id' =>  $entidade->empresa->id,      
            ]);       
            
            $lojas = Loja::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get();
            
            foreach ($lojas as $loja) {
                if($request->tipo == "S"){
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
                }else{
                    $estoque = Estoque::create([
                        "loja_id" => $loja->id,
                        "produto_id" => $produto->id,
                        "user_id" => Auth::user()->id,
                        "data_operacao" => date('Y-m-d'),
                        "stock" => 0,
                        "stock_minimo" => 0,
                        "operacao" => "Actualizar de Stock",
                        'entidade_id' => $entidade->empresa->id,
                    ]);   
                    $estoque->save();
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
    
            $update_estoque = Estoque::where("loja_id", $request->disponibilidade)
                ->where("produto_id", $produto->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->first(); 
            
            if($update_estoque){
                $update_estoque_up = Estoque::findOrFail($update_estoque->id);
                $update_estoque_up->lote_id =  NULL;
    
                $registro = Registro::create([
                    "registro" => "Entrada de Stock",
                    "data_registro" => date('Y-m-d'),
                    "quantidade" => $request->quantidade_inicial_stock,
                    "produto_id" => $produto->id,
                    "observacao" => 'Entrada inicial de produtos de Stock',
                    "loja_id" => $update_estoque->loja_id,
                    "lote_id" => $update_estoque->lote_id,
                    "user_id" => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
        
                $update_estoque_up->stock = $update_estoque_up->stock + $request->quantidade_inicial_stock;
                $update_estoque_up->update();
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
        
        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"], 200);
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
        
        if(!$user->can('listar produtos')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $produto = Produto::with(["variacao", 'taxa_imposto', "categoria", "marca"])->findOrFail($id);
        
        $grupo_precos = ProdutoGrupoPreco::with(['produto'])->where('produto_id', $produto->id)->get();
        
        $totalStock = Estoque::where([
            ['produto_id', $produto->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])->sum('stock');

        $lojas = Estoque::with('loja')->where('produto_id', $produto->id)->get();
        
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Detalhe Marca",
            "descricao" => env('APP_NAME'),
            "produto" => $produto,
            "empresa" => $empresa,
            "totalStock" => $totalStock,
            "lojas" => $lojas,
            "grupo_precos" => $grupo_precos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.produtos.show', $head);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function definir_preco_venda($grupo, $movimento)
    {
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes", "categorias", "marcas")->findOrFail($entidade->empresa->id);
        
        $movimento = Itens_venda::with('produto')->findOrFail($movimento);
        $grupo = ProdutoGrupoPreco::findOrFail($grupo);       
        
        $produto = Produto::with(["estoque", "variacao", "taxa_imposto", "categoria", "marca"])->findOrFail($grupo->produto_id);
        
        $grupos = ProdutoGrupoPreco::where('produto_id', $produto->id)->get();
        
        foreach ($grupos as $item) {
            $update = ProdutoGrupoPreco::findOrFail($item->id);
            $update->status = 'desactivo';
            $update->update();
        }
        
        $produto->imposto_id = $grupo->id;
        $produto->preco = $grupo->preco_venda;
        $produto->imposto = $grupo->imposto; 
        $produto->taxa = $grupo->taxa; 
        $produto->motivo_isencao = $grupo->codigo;
        $produto->motivo_id = $grupo->id;
        $produto->preco_custo = $grupo->preco_custo;
        $produto->margem = $grupo->margem;
        $produto->preco_venda = $grupo->preco_venda;
        $produto->update();
    
        $grupo->status = 'activo';
        $grupo->update();
        
        
        // actualização de vendas ou seja actualizar produtos dos seus preços 
        $desconto = ($produto->preco * $movimento->quantidade) * ($movimento->desconto_aplicado / 100);

        $produto->estoque->stock = ($produto->estoque->stock + $movimento->quantidade) - $movimento->quantidade;

        $valorBase = $produto->preco * $movimento->quantidade; 
        // calculo do iva
        $valorIva = ($produto->taxa / 100) * $valorBase;

        $movimento->quantidade = $movimento->quantidade;
        $movimento->valor_pagar = ($valorBase + $valorIva) - $desconto;
        $movimento->preco_unitario = $produto->preco;

        $movimento->valor_base = $valorBase;
        $movimento->valor_iva = $valorIva;

        $movimento->desconto_aplicado = $movimento->desconto_aplicado;
        $movimento->desconto_aplicado_valor = $desconto;

        $movimento->iva = $movimento->iva;
        $movimento->texto_opcional = $movimento->texto_opcional;
        $movimento->numero_serie = $movimento->numero_serie;
        $movimento->update();
        
        return redirect()->route('actualizar-venda', [$movimento->id, "null"])->with("success", "Preços actualizados com successo!");

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function definir_preco_factura($grupo, $movimento)
    {
        //
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes", "categorias", "marcas")->findOrFail($entidade->empresa->id);
        
        $movimento = Itens_venda::with('produto')->findOrFail($movimento);
        $grupo = ProdutoGrupoPreco::findOrFail($grupo);       
        
        $produto = Produto::with(["estoque", "variacao", "taxa_imposto", "categoria", "marca"])->findOrFail($grupo->produto_id);
        
        $grupos = ProdutoGrupoPreco::where('produto_id', $produto->id)->get();
        
        foreach ($grupos as $item) {
            $update = ProdutoGrupoPreco::findOrFail($item->id);
            $update->status = 'desactivo';
            $update->update();
        }
        
        $produto->imposto_id = $grupo->id;
        $produto->preco = $grupo->preco;
        $produto->imposto = $grupo->imposto; 
        $produto->taxa = $grupo->taxa; 
        $produto->motivo_isencao = $grupo->codigo;
        $produto->motivo_id = $grupo->id;
        $produto->preco_custo = $grupo->preco_custo;
        $produto->margem = $grupo->margem;
        $produto->preco_venda = $grupo->preco_venda;
        $produto->update();
    
        $grupo->status = 'activo';
        $grupo->update();
        
        // actualização de vendas ou seja actualizar produtos dos seus preços 
        $desconto = ($produto->preco * $movimento->quantidade) * ($movimento->desconto_aplicado / 100);

        $produto->estoque->stock = ($produto->estoque->stock + $movimento->quantidade) - $movimento->quantidade;

        $valorBase = $produto->preco * $movimento->quantidade; 
        // calculo do iva
        $valorIva = ($produto->taxa / 100) * $valorBase;

        $movimento->quantidade = $movimento->quantidade;
        $movimento->valor_pagar = ($valorBase + $valorIva) - $desconto;
        $movimento->preco_unitario = $produto->preco;

        $movimento->valor_base = $valorBase;
        $movimento->valor_iva = $valorIva;

        $movimento->desconto_aplicado = $movimento->desconto_aplicado;
        $movimento->desconto_aplicado_valor = $desconto;

        $movimento->iva = $movimento->iva;
        $movimento->texto_opcional = $movimento->texto_opcional;
        $movimento->numero_serie = $movimento->numero_serie;
        $movimento->update();
        
        return redirect()->route('actualizar-venda-factura', $movimento->id)->with("success", "Preços actualizados com successo!");

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function definir_preco($id)
    {
        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui    
            
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            $empresa = Entidade::with("variacoes", "categorias", "marcas")->findOrFail($entidade->empresa->id);
            
            $grupo = ProdutoGrupoPreco::findOrFail($id);       
            
            $produto = Produto::with(["variacao", "taxa_imposto", "categoria", "marca"])->findOrFail($grupo->produto_id);
            
            $grupos = ProdutoGrupoPreco::where('produto_id', $produto->id)->get();
            
            foreach ($grupos as $item) {
                $update = ProdutoGrupoPreco::findOrFail($item->id);
                $update->status = 'desactivo';
                $update->update();
            }
            
            $produto->imposto_id = $grupo->id;
            $produto->preco = $grupo->preco;
            $produto->imposto = $grupo->imposto; 
            $produto->taxa = $grupo->taxa; 
            $produto->motivo_isencao = $grupo->codigo;
            $produto->motivo_id = $grupo->id;
            $produto->preco_custo = $grupo->preco_custo;
            $produto->margem = $grupo->margem;
            $produto->preco_venda = $grupo->preco_venda;
            $produto->update();
        
            $grupo->status = 'activo';
            $grupo->update();

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
    
    public function grupos_preco_delete($id)
    {
        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui        
            
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            $empresa = Entidade::with("variacoes", "categorias", "marcas")->findOrFail($entidade->empresa->id);
            
            $produto = ProdutoGrupoPreco::findOrFail($id);
            $produto->delete();

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
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function grupos_preco($id)
    {
        //
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes", "categorias", "marcas")->findOrFail($entidade->empresa->id);
        
        $produto = Produto::with(["variacao", "taxa_imposto", "categoria", "marca"])->findOrFail($id);

        $head = [
            "titulo" => "Produto",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "produto" => $produto,
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "motivos" => Motivo::get(),
            "impostos" => Imposto::get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.produtos.grupo-precos', $head);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function grupos_preco_put(Request $request, $id)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $produto = Produto::findOrFail($id);
     
        // verificar se já tem produto
        $grupos = ProdutoGrupoPreco::where('produto_id', $id)->get();
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            if(count($grupos) == 0){
                $create = ProdutoGrupoPreco::create([
                    "produto_id" => $produto->id,
                    "imposto_id" => $produto->imposto_id,
                    "preco" => $produto->preco,
                    "imposto" => $produto->imposto, 
                    "taxa" => $produto->taxa, 
                    "motivo_isencao" => $produto->movito_isencao,
                    "motivo_id" => $produto->motivo_id,
                    "preco_custo" => $produto->preco_custo,
                    "margem" => $produto->margem,
                    "preco_venda" => $produto->preco_venda,
                    "status" => 'activo',
                    "user_id" => Auth::user()->id,  
                    'entidade_id' => $entidade->empresa->id,  
                ]);
            }
    
            $motivo = Motivo::findOrFail($request->motivo_isencao);
    
            if($request->imposto == "5"){
                $request->taxa = 14;
            }else
    
            if($request->imposto == "1"){
                $request->taxa = 0;
            }else
    
            if($request->imposto == "2"){
                $request->taxa = 2;
            }else
    
            if($request->imposto == "3"){
                $request->taxa = 5;
            }else
    
            if($request->imposto == "4"){
                $request->taxa = 7;
            }else{
                $request->taxa = 0;
            }
            
            if($request->preco == null){
                $preco = $request->preco_venda;
                $venda = $request->preco_venda;
            }else{
                $preco = $request->preco;
                $venda = $request->preco_venda;
            }
            
            if($request->preco_venda == null){
                $preco = $request->preco;
                $venda = $request->preco;
            }
            
            $imposto = Imposto::where('id', $request->imposto)->first();
            
            $create = ProdutoGrupoPreco::create([
                "produto_id" => $produto->id,
                "imposto_id" => $imposto->id,
                "preco" => $preco,
                "imposto" => $request->imposto, 
                "taxa" => $request->taxa, 
                "motivo_isencao" => $motivo->codigo,
                "motivo_id" => $motivo->id,
                "preco_custo" => $request->preco_custo,
                "margem" => $request->margem,
                "preco_venda" => $venda,
                "status" => $request->status,
                "user_id" => Auth::user()->id,  
                'entidade_id' => $entidade->empresa->id,  
            ]);
    
            $create->save();
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = auth()->user();
        
        if(!$user->can('editar produtos')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes", "categorias", "marcas")->findOrFail($entidade->empresa->id);
        
        $produto = Produto::with(["variacao", "taxa_imposto", "categoria", "marca"])->findOrFail($id);

        $head = [
            "titulo" => "Produto",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "produto" => $produto,
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "motivos" => Motivo::get(),
            "impostos" => Imposto::get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.produtos.edit', $head);
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
        
        if(!$user->can('editar produtos')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'nome' => 'required|string',
            'tipo' => 'required|string',
            'controlo_stock' => 'required',
            'tipo_stock' => 'required',
            'variacao_id' => 'required',
            'marca_id' => 'required',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
            'variacao_id.required' => 'A variação é um campo obrigatório',
            'categoria_id.required' => 'A categoria é um campo obrigatório',
            'marca_id.required' => 'A marca é um campo obrigatório',
            'tipo.required' => 'O tipo é um campo obrigatório',
            'controlo_stock.required' => 'O controlo de stock é um campo obrigatório',
            'tipo_stock.required' => 'O tipo de stock é um campo obrigatório',
        ]);
                
                
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
    
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
    
            if($request->hasFile('imagem') && $request->file('imagem')->isValid()){
                $requestImage = $request->imagem;
                $extension = $requestImage->extension();
    
                $imageName = md5($requestImage->getClientOriginalName() . strtotime("now") . "." . $extension);
    
                $request->imagem->move(public_path('images/produtos'), $imageName);
            }else{
                $imageName = $request->imagem_guardada;
            }
            
            $nova_conta = "";
            $code = uniqid(time());
    
            $produto = Produto::findOrFail($id);
   
            if($produto->subconta_id == NULL) {
            
                if($request->tipo_stock == "M"){
                    // 26.1
                    $conta = Conta::where('conta', '26')->first();
                    $serie = "26.1";
                }
                
                if($request->tipo_stock == "P"){
                    // 22.1
                    $conta = Conta::where('conta', '22')->first();
                    $serie = "22.1";
                }
                
                if($request->tipo_stock == "P1"){
                    // 22.2
                    $conta = Conta::where('conta', '22')->first();
                    $serie = "22.2";
                }
                
                if($request->tipo_stock == "P2"){
                    // 22.4
                    $conta = Conta::where('conta', '22')->first();
                    $serie = "22.4";
                }
                
                if($request->tipo_stock == "A"){
                    $conta = Conta::where('conta', '24')->first();
                    $serie = "24.1";
                }
                
                if($request->tipo_stock == "A1"){
                    $conta = Conta::where('conta', '24')->first();
                    $serie = "24.2";
                }
                
                if($request->tipo_stock == "S"){
                    $conta = Conta::where('conta', '25')->first();
                    $serie = "25.1";
                }
                
                if($request->tipo_stock == "S1"){
                    $conta = Conta::where('conta', '25')->first();
                    $serie = "25.2";
                }
                
                if($request->tipo_stock == "T"){
                    $conta = Conta::where('conta', '23')->first();
                    $serie = "23";
                }
                
                if($conta){
                
                    $subc_ = Subconta::where('numero', 'like', $serie . "%")->where('entidade_id', $entidade->empresa->id)->count();
                    
                    $numero =  $subc_ + 1;
                    
                    $nova_conta = $serie. "." . $numero;
                    
                    $subconta = Subconta::create([
                        'entidade_id' => $entidade->empresa->id, 
                        'numero' => $nova_conta,
                        'nome' => $request->nome,
                        'tipo_conta' => 'M',
                        'code' => $code,
                        'status' => $conta->status,
                        'conta_id' => $conta->id,
                        'user_id' => Auth::user()->id,
                    ]);
                    
                }else{
                    ## outros tratamento depois
                }
                
            }else {
              
                if($request->tipo_stock == $produto->tipo_stock){
                    $nova_conta = $produto->conta;
                    $code = $produto->code;
                    $subc_ = Subconta::where('code', $produto->code)->where('entidade_id', $entidade->empresa->id)->first();
                    
                    if($subc_){
                        $subconta = Subconta::findOrFail($subc_->id);
                        $subconta->nome = $request->nome;
                        $subconta->update();
                    }else{
                        ## depois damos outro tratamento
                    }
                }else {
                    if($request->tipo_stock == "M"){
                        // 26
                        $conta = Conta::where('conta', '26')->first();
                        $serie = "26.1";
                    }
                    
                    if($request->tipo_stock == "P"){
                        // 22.1
                        $conta = Conta::where('conta', '22')->first();
                        $serie = "22.1";
                    }
                    
                    if($request->tipo_stock == "P1"){
                        // 22.2
                        $conta = Conta::where('conta', '22')->first();
                        $serie = "22.2";
                    }
                    
                    if($request->tipo_stock == "P2"){
                        // 22.4
                        $conta = Conta::where('conta', '22')->first();
                        $serie = "22.4";
                    }
                    
                    if($request->tipo_stock == "A"){
                        $conta = Conta::where('conta', '24')->first();
                        $serie = "24.1";
                    }
                    
                    if($request->tipo_stock == "A1"){
                        $conta = Conta::where('conta', '24')->first();
                        $serie = "24.2";
                    }
                    
                    if($request->tipo_stock == "S"){
                        $conta = Conta::where('conta', '25')->first();
                        $serie = "25.1";
                    }
                    
                    if($request->tipo_stock == "S1"){
                        $conta = Conta::where('conta', '25')->first();
                        $serie = "25.2";
                    }
                    
                    if($request->tipo_stock == "T"){
                        $conta = Conta::where('conta', '23')->first();
                        $serie = "23";
                    }
                    
                    if($conta){
                
                        $subc_ = Subconta::where('numero', 'like', $serie . "%")->where('entidade_id', $entidade->empresa->id)->count();
                        $numero =  $subc_ + 1;
                        
                        $nova_conta = $serie . "." . $numero;
                        
                        $subconta = Subconta::create([
                            'entidade_id' => $entidade->empresa->id, 
                            'numero' => $nova_conta,
                            'nome' => $request->nome,
                            'tipo_conta' => 'M',
                            'code' => $code,
                            'status' => $conta->status,
                            'conta_id' => $conta->id,
                            'user_id' => Auth::user()->id,
                        ]);
                        
                    }else{
                        ## outros tratamento depois
                    }
                }
            
            }
    
    
            $motivo = Motivo::findOrFail($request->motivo_isencao);
            $imposto = Imposto::where('id', $request->imposto)->first();
            
            $produto->update([
                "nome" => $request->nome,
                "referencia" => $request->referencia,
                "codigo_barra" => $request->codigo_barra,
                "code" => $code,
                "conta" => $nova_conta,
                "descricao" => $request->descricao,
                "incluir_factura" => $request->incluir_factura,
                "imagem" => $imageName ,
                "imposto_id" => $imposto->id,
                "variacao_id" => $request->variacao_id,
                "categoria_id" => $request->categoria_id,
                "marca_id" => $request->marca_id,
                "tipo" => $request->tipo,
                "unidade" => $request->unidade,
                "imposto" => $request->imposto, 
                "taxa" => $imposto->valor,
                "motivo_isencao" => $motivo->codigo,
                "motivo_id" => $motivo->id,
                "preco" => $request->preco_venda,
                "preco_custo" => $request->preco_custo,
                "margem" => $request->margem,
                "preco_venda" => $request->preco_venda,
                "controlo_stock" => $request->controlo_stock,
                "tipo_stock" => $request->tipo_stock,
                "disponibilidade" => $request->disponibilidade,
                "status" => $request->status,
                'subconta_id' => $subconta->id,  
                // 'entidade_id' => $entidade->empresa->id,  
                // 'data_criacao' => $request->data_criacao,
                // 'data_expiracao'  => $request->data_expiracao,        
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = auth()->user();
        
        if(!$user->can('eliminar produtos')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        try {
            DB::beginTransaction();
            
            $produto = Produto::findOrFail($id);
            $produto->delete();
            
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
    
    public function getLotes($id)
    {
        $states = Produto::findOrFail($id);
        
        $lotes = Lote::where('produto_id', $states->id)->where('status', 'activo')->get();
        
        $option = "<option value=''>Selecione o Lote</option>";
        foreach($lotes as $state){
            $option .= '<option value="'.$state->id.'">'. $state->lote.'-'.$state->codigo_barra .'<option>';
        }
        return $option;
    }
}
