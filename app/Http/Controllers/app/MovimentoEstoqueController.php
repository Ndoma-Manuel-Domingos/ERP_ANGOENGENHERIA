<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Estoque;
use App\Models\Loja;
use App\Models\Lote;
use App\Models\Produto;
use App\Models\Registro;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class MovimentoEstoqueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        // buscas loja
        
        $movimentos = Registro::when($request->loja_id, function($query, $value){
            $query->where('loja_id', $value);
        })
        ->when($request->produto_id, function($query, $value){
            $query->where('produto_id', $value);
        })
        ->when($request->data_inicio, function($query, $value){
            $query->whereDate('created_at', '>=', Carbon::parse($value));
        })
        ->when($request->data_final, function($query, $value){
            $query->whereDate('created_at', '<=',Carbon::parse($value));
        })
        ->with('produto', 'user', 'loja')
        ->where('entidade_id', $entidade->empresa->id)
        ->orderBy('created_at', 'desc')
        ->get();

        $head = [
            "titulo" => "Movimentos do Stock",
            "descricao" => env('APP_NAME'),
            "movimentos" => $movimentos,
            "lojas" => Loja::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "produtos" => Produto::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
            "requests" => $request->all('loja_id', 'produto_id', 'data_inicio', 'data_final')
        ];

        return view('dashboard.estoques-movimentos.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
 
        $estoque = Estoque::where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->with('loja', 'produto')
        ->findOrFail($id);

        $totalStock = Estoque::where([
            ['produto_id', $estoque->produto->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])->sum('stock');
        
        $lotes = Lote::where('produto_id', $estoque->produto->id)->where('status', 'activo')->get();
        
        $registros = Registro::where([
            ['loja_id', '=', $estoque->loja->id],
            ['produto_id', '=', $estoque->produto->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->with('user')
        ->orderBy('created_at', 'desc')
        ->get();

        $head = [
            "titulo" => "Detalhe Marca",
            "descricao" => env('APP_NAME'),
            "registros" => $registros,
            "estoque" => $estoque,
            "totalStock" => $totalStock,
            "lotes" => $lotes,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.estoques-movimentos.show', $head);
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
        $request->validate([
            "operacao" => "required",
            "stock" => "required",
        ],[
            'operacao.required' => 'Operação é um campo obrigatório',
            'stock.required' => 'O stock é um campo obrigatório',
        ]);

        try {
            DB::beginTransaction();
            //
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
    
            $estoque = Estoque::findOrFail($id);
            $estoque->lote_id =  $request->lote_id;
    
            if($request->operacao == "alterar_minimo"){
                $estoque->stock_minimo = $request->stock;
                $estoque->update();
            }
    
            if($request->operacao == "entrada_stock"){
                
                $registro = Registro::create([
                    "registro" => "Entrada de Stock",
                    "data_registro" => date('Y-m-d'),
                    "quantidade" => $request->stock,
                    "produto_id" => $estoque->produto_id,
                    "observacao" => $request->justificativo,
                    "loja_id" =>$estoque->loja_id,
                    "lote_id" => $request->lote_id,
                    "user_id" => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
    
                $registro->save();
                $estoque->stock = $estoque->stock + $request->stock;
                $estoque->update();
            }
    
            if($request->operacao == "saida_stock"){
    
                $registro = Registro::create([
                    "registro" => "Saída de Stock",
                    "data_registro" => date('Y-m-d'),
                    "quantidade" => $request->stock,
                    "observacao" => $request->justificativo,
                    "produto_id" => $estoque->produto_id,
                    "loja_id" =>$estoque->loja_id,
                    "lote_id" => $request->lote_id,
                    "user_id" => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
    
                $registro->save();
                $estoque->stock = $estoque->stock - $request->stock;
                $estoque->update();
            }
    
            if($request->operacao == "actualizar_stock"){
                
                $registro = Registro::create([
                    "registro" => "Actualizar de Stock",
                    "data_registro" => date('Y-m-d'),
                    "quantidade" => $request->stock,
                    "observacao" => $request->justificativo,
                    "produto_id" => $estoque->produto_id,
                    "loja_id" => $estoque->loja_id,
                    "lote_id" => $request->lote_id,
                    "user_id" => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
    
                $registro->save();
                $estoque->stock = $request->stock;
                $estoque->update();
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
