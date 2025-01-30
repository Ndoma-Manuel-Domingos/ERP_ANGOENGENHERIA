<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Entidade;
use App\Models\Estoque;
use App\Models\Loja;
use App\Models\Lote;
use App\Models\Produto;
use App\Models\Registro;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use RealRashid\SweetAlert\Facades\Alert;

class EstoqueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $estoque = Estoque::where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->orderBy('created_at', 'desc')
        ->get();

        $head = [
            "titulo" => "Stock",
            "descricao" => env('APP_NAME'),
            "estoques" => $estoque,
            'lojas' => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id]
            ])->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.estoques.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $lojas = Loja::where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])->get();

        $produtos = Produto::where('tipo', 'P')->where('entidade_id', '=', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Actualizar Stock",
            "descricao" => env('APP_NAME'),
            "lojas" => $lojas,
            "produtos" => $produtos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
    
        return view('dashboard.estoques.create', $head);
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
            'produto_id' => 'required',
            'loja_id' => 'required',
            'stock' => 'required',
            'operacao' => 'required',
        ],[
            'produto_id.required' => 'O produto é um campo obrigatório',
            'loja_id.required' => 'A loja é um campo obrigatório',
            'stock.required' => 'O stock é um campo obrigatório',
            'operacao.required' => 'Operação é um campo obrigatório',
        ]);
                
        try {
            DB::beginTransaction();
            //
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            $registro = Registro::create([
                "registro" => $request->operacao,
                "data_registro" => date('Y-m-d'),
                "quantidade" => $request->stock,
                "produto_id" => $request->produto_id,
                "observacao" => $request->observacao,
                "loja_id" =>$request->loja_id,
                "lote_id" => $request->lote_id,
                "user_id" => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);     
            
            $verificarEstoque = Estoque::where("lote_id", $request->lote_id)
                ->where("entidade_id", $entidade->empresa->id)
                ->where("produto_id", $request->produto_id)
                ->where("loja_id", $request->loja_id)
                ->first();
            
            if($request->operacao == "Saída de Stock"){
                if($verificarEstoque){
                    
                    // Nao informou o lote
                    if(!$request->lote_id == NULL) {
                        
                        $produtos_lotes = Lote::findOrFail("lote_id", $request->lote_id);
                        
                        if($request->stock > $produtos_lotes->stock_total){
                            $produtos_lotes->stock_total = $produtos_lotes->stock_total - $request->stock;
                            $produtos_lotes->saida = $produtos_lotes->saida - $request->stock;
                            $produtos_lotes->update();
                        }else {
                            return response()->json(['message' => "Não foi possível fazer saída de produto porque não existe nehuma quantidade stock para saída!"], 404);
                        }
                    
                    }
                    
                    $saida =  Estoque::findOrFail($verificarEstoque->id);
                    $saida->stock = $saida->stock - $request->stock;
                    $saida->update();
                }else {
                    return response()->json(['message' => "Não foi possível fazer saída de produto porque não existe nehuma quantidade stock para saída!"], 404);
                }
            }
            
            if($request->operacao == "Entrada de Stock"){
                // Nao informou o lote
                if(!$request->lote_id == NULL) {
                    
                    $produtos_lotes = Lote::findOrFail("lote_id", $request->lote_id);
                    $produtos_lotes->stock_total = $produtos_lotes->stock_total + $request->stock;
                    $produtos_lotes->entrada = $produtos_lotes->entrada + $request->stock;
                    $produtos_lotes->update();
                
                }
                
                $estoque = Estoque::create([
                    "loja_id" => $request->loja_id,
                    "lote_id" => $request->lote_id,
                    "produto_id" => $request->produto_id,
                    "user_id" => Auth::user()->id,
                    "data_operacao" => date('Y-m-d'),
                    "stock" => $request->stock,
                    "operacao" => $request->operacao,
                    "observacao" => $request->observacao,
                    'entidade_id' => $entidade->empresa->id,
                ]);
    
                $estoque->save();
            }
            
            
            if($request->operacao == "Actualizar de Stock"){
                // Nao informou o lote
                if(!$request->lote_id == NULL) {
                    
                    $produtos_lotes = Lote::findOrFail("lote_id", $request->lote_id);
                    $produtos_lotes->stock_total = $request->stock;
                    $produtos_lotes->entrada = $request->stock - $saida->stock;
                    $produtos_lotes->update();
                
                }
                
                if($verificarEstoque){
                    $saida =  Estoque::findOrFail($verificarEstoque->id);
                    $saida->stock = $request->stock - $saida->stock;
                    $saida->update();
                } else {
                    return response()->json(['message' => "Não foi possível fazer saída de produto porque não existe nehuma quantidade stock para saída!"], 404);
                }
            
            }
            
      
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
        //
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
    }

    public function resumoRelatorio(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $dataActual = date("Y-m-d");

        if($request->periodo == "1_mes"){
            $data_create = date("Y-m-d", strtotime($dataActual . "-1months"));
        }else if($request->periodo == "7_dias"){
            $data_create = date("Y-m-d", strtotime($dataActual . "-7days"));
        }else if($request->periodo == "21_dias"){
            $data_create = date("Y-m-d", strtotime($dataActual . "-21days"));
        }else if($request->periodo == "2_meses"){
            $data_create = date("Y-m-d", strtotime($dataActual . "-2months"));   
        }else if($request->periodo == "3_meses"){
            $data_create = date("Y-m-d", strtotime($dataActual . "-3months"));
        }else if($request->periodo == "6_meses"){
            $data_create = date("Y-m-d", strtotime($dataActual . "-6months"));
        }else if($request->periodo == "1_ano"){
            $data_create = date("Y-m-d", strtotime($dataActual . "-1years"));
        }

        $loja = Loja::where('nome', 'Loja Principal')->first();

        if($request->loja_id == null){
            $estoque = Estoque::where('entidade_id', '=', $entidade->empresa->id)
                ->where('loja_id', '=', $loja->id)
                ->where('data_operacao', '>=', $data_create)
                ->where('data_operacao', '<=', date('Y-m-d'))
                ->with('produto')
                ->orderBy('created_at', 'desc')
                ->get();
        }else{
            $estoque = Estoque::where('entidade_id', '=', $entidade->empresa->id)
                ->where('loja_id', '=', $request->loja_id)
                ->where('data_operacao', '>=', $data_create)
                ->where('data_operacao', '<=', date('Y-m-d'))
                ->with('produto')
                ->orderBy('created_at', 'desc')
                ->get(); 
        }

        $head = [
            "titulo" => "Relatório de Análise de Stock",
            "descricao" => env('APP_NAME'),
            "resultados" => $estoque,
            "empresa" => $entidade,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.estoques.resumo', $head);
    }

    public function imprimirResumoRelatorio()
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $dataActual = date("Y-m-d");

        $data_create = date("Y-m-d", strtotime($dataActual . "-7days"));

        $estoque = Estoque::where('entidade_id', '=', $entidade->empresa->id)
            ->where('data_operacao', '>=', $data_create)
            ->where('data_operacao', '<=', date('Y-m-d'))
            ->with('produto')
            ->orderBy('created_at', 'desc')
            ->get();

        $head = [
            "titulo" => "Relatório de Análise de Stock",
            "descricao" => env('APP_NAME'),
            "resultados" => $estoque,
            "loja" => $entidade,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];   

        $pdf = PDF::loadView('dashboard.estoques.resumo-relatorio-pdf', $head);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->stream();
    }
    
    
    public function estoqueProduto(Request $request) 
    {
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["caixas", "users"])->findOrFail($entidade->empresa->id);

        $estoques = Estoque::with(['lote', 'produto'])->whereHas('lote', function ($query) use ($request) {
            $query->when($request->status, function($query, $value){
                $query->where('status', $value);
            });
        })
        ->where('entidade_id', $entidade->empresa->id)
        ->orderBy('created_at', 'desc')
        ->get();
        
        $head = [
            "titulo" => "Produto de Stock",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "estoques" => $estoques,
            "requests" => $request->all('status'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.estoques.dashboard', $head);
    }
        
    public function imprimirEstoqueProduto(Request $request) 
    {
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["caixas", "users"])->findOrFail($entidade->empresa->id);

        $estoques = Estoque::with(['lote', 'produto'])->whereHas('lote', function ($query) use ($request) {
            $query->when($request->status, function($query, $value){
                $query->where('status', $value);
            });
        })
        ->where('entidade_id', $entidade->empresa->id)
        ->orderBy('created_at', 'desc')
        ->get();
        
        $head = [
            "titulo" => "Produto de Stock",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "estoques" => $estoques,
            "requests" => $request->all('status'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
        

        $pdf = PDF::loadView('dashboard.estoques.stock-relatorio-pdf', $head);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->stream();
    }
    
}
