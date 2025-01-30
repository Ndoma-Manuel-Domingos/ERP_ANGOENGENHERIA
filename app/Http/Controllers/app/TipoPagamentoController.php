<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Loja;
use App\Models\TipoPagamento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class TipoPagamentoController extends Controller
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
        
        $tiposPagamentos = TipoPagamento::orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => "Tipo de Pagamentos",
            "descricao" => env('APP_NAME'),
            "tipoPagamentos" => $tiposPagamentos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('admin.tipo-pagamentos.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $head = [
            "titulo" => "Cadastrar Tipo de Pagamento",
            "descricao" => env('APP_NAME'),
            "lojas" => Loja::where([
                ['status', '=','activo'],
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('admin.tipo-pagamentos.create', $head);
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
            'titulo' => 'required|string',
            "troco" => 'required',
            "tipo" => 'required',
        ],[
            'titulo.required' => 'O titulo é um campo obrigatório',
            'troco.required' => 'O troco é um campo obrigatório',
            'tipo.required' => 'O tipo é um campo obrigatório',
        ]);
        
        
                
        try {
            DB::beginTransaction();
    
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
           
            $tiposPagamentos = TipoPagamento::create([
                'titulo' => $request->titulo,
                'status' => $request->status,
                "tipo" => $request->tipo,
                "troco" => $request->troco,
            ]);   
            $tiposPagamentos->save();
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
        try {
            DB::beginTransaction();
            
            $tiposPagamentos = TipoPagamento::findOrFail($id);
    
            if($tiposPagamentos->status == true){
                $tiposPagamentos->status = false;
            }else{
                $tiposPagamentos->status = true;
            }
            
            $tiposPagamentos->update();
            
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
        $tiposPagamentos = TipoPagamento::findOrFail($id);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $head = [
            "titulo" => "Editar Tipo Pagamento",
            "descricao" => env('APP_NAME'),
            "tipoPagamento" => $tiposPagamentos,
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id],
                ['status', '=','activo'],
            ])->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('admin.tipo-pagamentos.edit', $head);
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
            'titulo' => 'required|string',
            'tipo' => 'required|string',
        ],[
            'titulo.required' => 'O titulo é um campo obrigatório',
            'tipo.required' => 'O tipo é um campo obrigatório',
        ]);
        
                
        try {
            DB::beginTransaction();
            
            $tiposPagamentos = TipoPagamento::findOrFail($id);
            $tiposPagamentos->update($request->all());
            $tiposPagamentos->update();
            
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
        try {
            DB::beginTransaction();
            
            $tiposPagamentos = TipoPagamento::findOrFail($id);
            $tiposPagamentos->delete();
            
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
}
