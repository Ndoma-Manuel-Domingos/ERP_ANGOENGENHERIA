<?php

namespace App\Http\Controllers;

use App\Models\EncomendaFornecedore;
use App\Models\Entidade;
use App\Models\Estoque;
use App\Models\FacturaEncomendaFornecedor;
use App\Models\Fornecedore;
use App\Models\Imposto;
use App\Models\ItensEncomenda;
use App\Models\ItensRequisicao;
use App\Models\Loja;
use App\Models\Motivo;
use App\Models\Produto;
use App\Models\Registro;
use App\Models\Requisicao;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

use PDF;

class RequisacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);    
        
        $requisicoes = Requisicao::with(['items'])->when($request->tipo_documento, function($query, $value){
            $query->where('status', '=', $value);
        })
        ->when($request->data_inicio, function($query, $value){
            $query->whereDate('created_at', '>=', Carbon::parse($value));
        })
        ->when($request->data_final, function($query, $value){
            $query->whereDate('created_at', '<=',Carbon::parse($value));
        })
        ->where('entidade_id', '=', $entidade->empresa->id)
        ->orderBy('created_at', 'desc')
        ->get();

        $head = [
            "titulo" => "Requisições",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "requisicoes" => $requisicoes,
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
            "requests" => $request->all("tipo_documento", "data_inicio", "data_final"),
        ];

        return view('dashboard.requisacoes.index', $head); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);
        
        $items = ItensRequisicao::where('user_id', '=', Auth::user()->id)
            ->where('status', '=', 'em processo')
            ->where('code', '=', NULL)
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->with('produto.taxa_imposto')
            ->get();
        
        $produtos = Produto::where('status', '=', 'activo')
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $fornecedores = Fornecedore::where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $totalEncomendas = ItensRequisicao::where([
            ['user_id', '=', Auth::user()->id],
            ['status', '!=', 'em processo'],
            ['code', '!=', NULL],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->count();

        $resultado = $totalEncomendas + 1;
        
        $head = [
            "titulo" => "Nova Requisição",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "produtos" => $produtos,
            "fornecedores" => $fornecedores,
            "items" => $items,
            "motivos" => Motivo::get(),
            "impostos" => Imposto::get(),
            "lojas" => Loja::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "totalRequisicao" =>  $resultado."-".date('y') ."". date('m') ."". date('d'). "/R",
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.requisacoes.create', $head); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $request->validate(
            ['numero' => 'required' ], [ 'numero.required' => 'O número é um campo obrigatório' ]
        );
        
        
        try {
            // Inicia a transação
            DB::beginTransaction();
                    
            foreach($request->ids as $id){
                $update = ItensRequisicao::findOrFail($id);
                $update->quantidade = $request->input("quantidade{$id}");
                $update->loja_id = $request->loja_id;
                $update->update();
            }        
            
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
    
            $totalQuantidade = ItensRequisicao::where([
                ['user_id', '=', Auth::user()->id],
                ['status', '=', 'em processo'],
                ['code', '=', NULL],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
            ->with(['produto'])
            ->sum('quantidade');
            
            // dd($request->numero, $request->loja_id);
    
            $code = uniqid(time());
            $create = Requisicao::create([
                'status' => 'pendente',
                'numero' => $request->numero,
                'loja_id' => $request->loja_id,
                'data_emissao' => date('Y-m-d'),
                'observacao' => $request->observacao,
                'code' => $code,
                'quantidade' => $totalQuantidade,
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);
            
            $items = ItensRequisicao::where([
                ['user_id', '=', Auth::user()->id],
                ['status', '=', 'em processo'],
                ['code', '=', NULL],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
            ->with(['produto'])
            ->get();

            foreach ($items as $value) {
                $update = ItensRequisicao::findOrFail($value->id);
                $update->code = $code;
                $update->requisicao_id = $create->id;
                $update->status = 'pendente';
                $update->update();
            }
            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            Alert::warning('Error', $e->getMessage());
        }

        Alert::success('Sucesso', 'Requisição realizada com sucesso!');
        return redirect()->route('requisacoes.show', $create->id);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $requisicao = Requisicao::with(['items.produto.taxa_imposto', 'items.produto.categoria', 'items.produto.variacao', 'items.produto.marca', 'aprovador', 'user', 'loja'])->findOrFail($id);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);

        $items = ItensRequisicao::where([
            ['code', '=', $requisicao->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->with(['produto'])
        ->get();

        $head = [
            "titulo" => "Visualizar Requisição {$requisicao->numero}",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "requisicao" => $requisicao,
            "items" => $items,
            "loja" => $entidade,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.requisacoes.show', $head); 
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
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);
        
        $requisicao = Requisicao::with(['items.produto.taxa_imposto', 'items.produto.categoria', 'items.produto.variacao', 'items.produto.marca', 'aprovador', 'user', 'loja'])->findOrFail($id);

        $items = ItensRequisicao::where([
            ['code', '=', $requisicao->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->with('produto')
        ->with('loja')
        ->get();

        $produtos = Produto::where([
            ['status', '=', 'activo'],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->get();

        $fornecedores = Fornecedore::where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])->get();

        $head = [
            "titulo" => "Adicionar Encomenda",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "produtos" => $produtos,
            "requisicao" => $requisicao,
            "fornecedores" => $fornecedores,
            "items" => $items,
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.requisacoes.edit', $head); 
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
        $requisicao = Requisicao::findOrFail($id);
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);

        try {
            // Inicia a transação
            DB::beginTransaction();
                
            foreach($request->ids as $id){
                $update = ItensRequisicao::findOrFail($id);
                $update->quantidade = $request->input("quantidade{$id}");
                $update->loja_id = $request->loja_id;
                $update->update();
            }
            
            $totalQuantidade = ItensRequisicao::where([
                ['code', '=', $requisicao->code],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
            ->with(['produto'])
            ->sum('quantidade');
    
            $updated = ItensRequisicao::findOrFail($requisicao->id);
            $updated->status = $updated->status;
            $updated->loja_id = $request->loja_id;
            $updated->data_emissao = date('Y-m-d');
            $updated->quantidade = $totalQuantidade;
            $updated->update();
            
            $items = ItensRequisicao::where([
                ['code', '=', $requisicao->code],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
            ->with(['produto'])
            ->get();
    
            foreach ($items as $value) {
                $update = ItensRequisicao::findOrFail($value->id);
                $update->status = $updated->status;
                $update->update();
            }

            
            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            Alert::warning('Error', $e->getMessage());
        }

        Alert::success('Sucesso', 'Requisição Actualizada com sucesso!');
        return redirect()->route('requisacoes.show', $updated->id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $requisicao = Requisicao::findOrFail($id);

        $items = ItensRequisicao::where('code', '=', $requisicao->code)
        ->get();

        if($items){
            foreach ($items as $value) {
                ItensRequisicao::findOrFail($value->id)->delete();
            }
        }

        $requisicao->delete();

        Alert::success('Sucesso', 'Requisição Excluída com sucesso!');
        return redirect()->route('requisacoes.index');
    }

    public function adicionarProduto($id)
    {
    
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
    
        $produto = Produto::findOrFail($id);
        
        $verificar = ItensRequisicao::where([
            ['produto_id', '=', $produto->id],
            ['user_id', '=', Auth::user()->id],
            ['data_emissao', '=', date('Y-m-d')],
            ['status', '=',  'em processo'],
            ['code',  NULL],
            ['entidade_id' , '=', $entidade->empresa->id],
        ])->first();

        if($verificar){
            Alert::error("Erro", "Este produto Já foi Adicionar... Pode alterar a quantidade");
            return redirect()->back();
            // return redirect()->route('fornecedores-encomendas.create');
        }

        $items = ItensRequisicao::create([
            'produto_id' => $produto->id,
            'user_id' => Auth::user()->id,
            'quantidade' => 1,
            'data_emissao' => date('Y-m-d'),
            'status' => 'em processo',
            'code' =>  NULL,
            'entidade_id' => $entidade->empresa->id,
        ]);

        if($items->save()){
            return redirect()->back();
        }else{
            Alert::error("Erro", "Ocorreu um erro ao tentar adicionar este produto");
            return redirect()->back();
        }

    }
        
    public function editarProduto($id, $requisicao)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
    
        $produto = Produto::findOrFail($id);
        $request = Requisicao::findOrFail($requisicao);

        $verificar = ItensRequisicao::where([
            ['produto_id', '=', $produto->id],
            ['code', '=', $request->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])->first();

        if($verificar){
            Alert::error("Erro", "Este produto Já foi Adicionar... Pode alterar a quantidade");
            return redirect()->back();
        }

        $items = ItensRequisicao::create([
            'produto_id' => $produto->id,
            'loja_id' => $request->loja_id,
            'user_id' => Auth::user()->id,
            'quantidade' => 1,
            'data_emissao' => date('Y-m-d'),
            'status' => 'em processo',
            'code' =>  $request->code,
            'entidade_id' => $entidade->empresa->id,
        ]);

        if($items->save()){
            return redirect()->back();
        }else{
            Alert::error("Erro", "Ocorreu um erro ao tentar adicionar este produto");
            return redirect()->back();
        }

    }

    public function removerProduto($id)
    {
        $delete = ItensRequisicao::findOrFail($id);
        if($delete->delete()){
            return redirect()->back();
        }            
    }

    public function rascunho($id)
    {
        $requisicao = Requisicao::findOrFail($id);
        $requisicao->status = "rascunho";
        $requisicao->update();

        $items = ItensRequisicao::where('code', '=', $requisicao->code)->get();

        if($items){
            foreach ($items as $item) {
                $updated = ItensRequisicao::findOrFail($item->id);
                $updated->status = 'rascunho';
                $updated->update();
            }
        }

        Alert::success('Sucesso', 'Encomenda Entregue com sucesso!');
        return redirect()->route('requisacoes.show', $requisicao->id);
    }

    public function rejeitar($id)
    {
        $requisicao = Requisicao::findOrFail($id);
        $requisicao->status = "rejeitada";
        $requisicao->update();

        $items = ItensRequisicao::where('code', $requisicao->code)->get();

        if($items){
            foreach ($items as $item) {
                $updated = ItensRequisicao::findOrFail($item->id);
                $updated->status = 'rejeitada';
                $updated->update();
            }
        }

        Alert::success('Sucesso', 'Requisição Rejeitada com sucesso!');
        return redirect()->route('requisacoes.show', $requisicao->id);
    }

    public function aprovada($id)
    {
        $requisicao = Requisicao::findOrFail($id);

        $items = ItensRequisicao::where([
            ['code', '=', $requisicao->code]
        ])->get();

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);
        
        $requisicao = Requisicao::with(['items.produto.taxa_imposto', 'items.produto.categoria', 'items.produto.variacao', 'items.produto.marca', 'aprovador', 'user', 'loja'])->findOrFail($id);

        $items = ItensRequisicao::where([
            ['code', '=', $requisicao->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->with(['produto.estoque'])
        ->with('loja')
        ->get();


        $head = [
            "titulo" => "Receber Ecomenda ou Produto",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "requisicao" => $requisicao,
            "items" => $items,
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.requisacoes.receber', $head); 
    }

    public function aprovadaStore(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $requisicao = Requisicao::findOrFail($request->requisicao_id);
        
        try {
            // Inicia a transação
            DB::beginTransaction();
            foreach($request->ids as $id){
                $update = ItensRequisicao::findOrFail($id);
    
                $produto = Produto::findOrFail($update->produto_id);
                $loja = Loja::findOrFail($requisicao->loja_id);
    
                $actualizarEstoque = Estoque::where([
                    ['produto_id', '=', $produto->id],
                    ['loja_id', '=', $loja->id],
                ])->first();
                
                if($actualizarEstoque){
                    $actualizar = Estoque::findOrFail($actualizarEstoque->id);
                    $actualizar->stock = $actualizar->stock - $request->input("quantidade{$id}");
                    $actualizar->update();
                }
    
                Registro::create([
                    "registro" => "Saída de Produtos Requisição",
                    "data_registro" => date('Y-m-d'),
                    "quantidade" => $request->input("quantidade{$id}"),
                    "observacao" => $requisicao->numero,
                    "requisicao_id" => $requisicao->id,
                    "produto_id" => $produto->id,
                    "preco_unitario" => $produto->preco_venda,
                    "loja_id" => $requisicao->loja_id,
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);
            }  
           
            $requisicao->status = "aprovada";
            $requisicao->update();
    
            $itemRequisicoes = ItensRequisicao::where('code', '=', $requisicao->code)->get();
    
            foreach ($itemRequisicoes as $item) {
                $up = ItensRequisicao::findOrFail($item->id);
                $up->status = "aprovada";
                $up->update();
            }
                
            $requisicao->user_aprovador_id = Auth::user()->id;
            $requisicao->update();
        
            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            Alert::warning('Error', $e->getMessage());
        }

        return redirect()->route('requisacoes.show', $requisicao->id);
    }
    
    public function imprimir($code)
    {
        $requisicao = Requisicao::with(['items.produto.taxa_imposto', 'items.produto.categoria', 'items.produto.variacao', 'items.produto.marca', 'aprovador', 'user', 'loja'])->findOrFail($code);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);

        $items = ItensRequisicao::where([
            ['code', '=', $requisicao->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->with(['produto'])
        ->get();
 
        $head = [
            "titulo" => "Factura Pro-forma",
            "descricao" => env('APP_NAME'),
            "requisicao" => $requisicao,
            "empresa" => $empresa,
            "items" => $items,
            
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.requisacoes.imprimir', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();

    }
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function imprimir_colectiva(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);    
        
        $requisicoes = Requisicao::with(['items'])->when($request->tipo_documento, function($query, $value){
            $query->where('status', '=', $value);
        })
        ->when($request->data_inicio, function($query, $value){
            $query->whereDate('created_at', '>=', Carbon::parse($value));
        })
        ->when($request->data_final, function($query, $value){
            $query->whereDate('created_at', '<=',Carbon::parse($value));
        })
        ->where('entidade_id', '=', $entidade->empresa->id)
        ->orderBy('created_at', 'desc')
        ->get();

        $head = [
            "titulo" => "Requisições",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "requisicoes" => $requisicoes,
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
            "requests" => $request->all("tipo_documento", "data_inicio", "data_final"),
        ];
            
        $pdf = PDF::loadView('dashboard.requisacoes.imprimir-colectiva', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function imprimir_individual($id)
    {
        $requisicao = Requisicao::with(['items.produto.taxa_imposto', 'items.produto.categoria', 'items.produto.variacao', 'items.produto.marca', 'aprovador', 'user', 'loja'])->findOrFail($id);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);

        $items = ItensRequisicao::where([
            ['code', '=', $requisicao->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->with(['produto'])
        ->get();

        $head = [
            "titulo" => "Requisição: {$requisicao->numero}",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "requisicao" => $requisicao,
            "items" => $items,
            "loja" => $entidade,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.requisacoes.imprimir-individual', $head);
        $pdf->setPaper('A4', 'portrait');
    
        return $pdf->stream();
    }
    
    

}
