<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Conta;
use App\Models\EquipamentoActivo;
use App\Models\Fornecedore;
use App\Models\Subconta;
use App\Models\TabelaTaxaReintegracaoAmortizacaoItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class EquipamentoActivoController extends Controller
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

        if(!$user->can('listar exercicio')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $equipamentos_activos = EquipamentoActivo::with(['user', 'classificacao', 'fornecedor', 'conta', 'entidade'])
        ->where( 'entidade_id', '=', $entidade->empresa->id )
        ->get();

        $head = [
            "titulo" => "Equipamentos e Activos",
            "descricao" => env('APP_NAME'),
            "equipamentos_activos" => $equipamentos_activos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.equipamentos-activos.index', $head);
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
        
        if(!$user->can('criar exercicio')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $classe = Classe::where('conta', 'Classe 1')->pluck('id');
        $contaI = Conta::whereIn('classe_id', $classe)->pluck('id'); 
        
        $contas = Subconta::whereIn('tipo_conta', ['E', 'G'])->whereIn('conta_id', $contaI)->where('entidade_id', '=', $entidade->empresa->id)->orderBy('numero', 'asc')->get();
        
        $fornecedores = Fornecedore::where('entidade_id', '=', $entidade->empresa->id)->get();
        $classificacoes = TabelaTaxaReintegracaoAmortizacaoItem::get();
        
        $head = [
            "titulo" => "Cadastrar Equipamento Activo",
            "descricao" => env('APP_NAME'),
            "fornecedores" => $fornecedores,
            "contas" => $contas,
            "classificacoes" => $classificacoes,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.equipamentos-activos.create', $head);
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
        
        if(!$user->can('criar exercicio')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        
        $request->validate([
            'nome' => 'required|string',
            'base_incidencia' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
            'base_incidencia.required' => 'A base de incidência é um campo obrigatório',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            if($request->hasFile('anexo') && $request->file('anexo')->isValid()){
                $requestImage = $request->anexo;
                $extension = $requestImage->extension();
    
                $imageName = md5($requestImage->getClientOriginalName() . strtotime("now") . "." . $extension);
    
                $request->anexo->move(public_path('images/imobilizados'), $imageName);
            }else{
                $imageName = NULL;
            }
            
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            if($request->iva <= -1 AND $request->iva >= 101){
                return redirect()->back()->with('danger', "Valor da Taxa do IVA Invalido!");
            }
            if($request->iva_nd <= -1 AND $request->iva_nd >= 101){
                return redirect()->back()->with('danger', "Valor da Taxa do IVA Não Dedutível Invalido!");
            }
            if($request->iva_d <= -1 AND $request->iva_d >= 101){
                return redirect()->back()->with('danger', "Valor da Taxa do IVA Dedutível Invalido!");
            }
            
            $iva_total = ($request->base_incidencia * $request->quantidade) * ($request->iva / 100);
            
            $iva_dedutivel =  $iva_total * ($request->iva_d / 100);
            $iva_n_dedutivel =  $iva_total * ($request->iva_nd / 100);
            
            $total = $iva_total + $request->base_incidencia;
                        
            $valor_desconto = $total * ($request->desconto / 100);
            
            $custo_aquisicao = $total -  $valor_desconto;
            
            $subconta = Subconta::findOrFail($request->conta_id);
            
            $total_subconta = Subconta::where('numero', 'like', $subconta.".%")->where('entidade_id', '=', $entidade->empresa->id)->count() + 1;
            $numero = $subconta->numero . ".{$total_subconta}";
                       
            $code = uniqid(time());
            
            $equipamento_activo = EquipamentoActivo::create([
                'nome' => $request->nome,
                'numero_serie' => $request->numero_serie,
                'codigo_barra' => $request->codigo_barra,
                'quantidade' => $request->quantidade,
                'data_aquisicao' => $request->data_aquisicao,
                'data_utilizacao' => $request->data_utilizacao,
                'conta_id' => $request->conta_id,
                'classificacao_id' => $request->classificacao_id,
                'code' => $code,
                'status' => $request->status,
                'staus_financeiro' => $request->staus_financeiro,
                'base_incidencia' => $request->base_incidencia,
                'iva' => $request->iva,
                'iva_nd' => $request->iva_nd,
                'iva_d' => $request->iva_d,
                'desconto' => $request->desconto,
                'fornecedor_id' => $request->fornecedor_id,
                'numero_factura' => $request->numero_factura,
                'descricao' => $request->descricao,
                'anexo' => $imageName,
                
                'total' =>  $total,
                'iva_total' =>  $iva_total,
                'valor_desconto' => $valor_desconto,
                'iva_dedutivel' => $iva_dedutivel,
                'iva_n_dedutivel' => $iva_n_dedutivel,
                'custo_aquisicao' => $custo_aquisicao,
                'valor_contabilistico' => $custo_aquisicao,
                    
                'entidade_id' => $entidade->empresa->id, 
                'user_id' => Auth::user()->id,
            ]);
            
            $subconta = Subconta::create([
                'numero' => $numero,
                'nome' => $request->nome,
                'tipo_conta' => 'M',
                'code' => $code,
                'status' => $request->status,
                'conta_id' => $subconta->conta_id,
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activar($id)
    {
        $user = auth()->user();
        
        if(!$user->can('listar exercicio')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
 
        $equipamento_activo = EquipamentoActivo::findOrFail($id);
        $equipamento_activo->status = 'activo';
        $equipamento_activo->update();
        
        return redirect()->back()->with("success", "Exercício activado com sucesso!");
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function desactivar($id)
    {
        $user = auth()->user();
        
        if(!$user->can('listar exercicio')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $equipamento_activo = EquipamentoActivo::findOrFail($id);
        $equipamento_activo->status = 'desactivo';
        $equipamento_activo->update();
        
        return redirect()->back()->with("success", "Exercício desactivado com sucesso!!");

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
        
        if(!$user->can('listar exercicio')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $equipamento_activo = EquipamentoActivo::with(['user', 'classificacao', 'fornecedor', 'conta', 'entidade'])->findOrFail($id);
        
     
        $head = [
            "titulo" => "Detalhe do Equipamento Activo",
            "descricao" => env('APP_NAME'),
            "equipamento_activo" => $equipamento_activo,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.equipamentos-activos.show', $head);

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
        
        if(!$user->can('editar exercicio')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $equipamento_activo = EquipamentoActivo::findOrFail($id);

        $head = [
            "titulo" => "Editar Equipamento Activo",
            "descricao" => env('APP_NAME'),
            "equipamento_activo" => $equipamento_activo,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.equipamentos-activos.edit', $head);
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
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $user = auth()->user();
            
            if(!$user->can('editar exercicio')){
                Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
                return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            }
            
            $request->validate([
                'nome' => 'required|string',
            ],[
                'nome.required' => 'O nome é um campo obrigatório',
            ]);
    
            $equipamento_activo = EquipamentoActivo::findOrFail($id);
            $equipamento_activo->update($request->all());
            
            $equipamento_activo->update();
            
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
        $user = auth()->user();
        
        if(!$user->can('eliminar exercicio')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $equipamento_activo = EquipamentoActivo::findOrFail($id);
            $equipamento_activo->delete();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        return redirect()->back()->with("success", "Dados Excluído com Sucesso!");

    }

}
