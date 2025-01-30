<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Conta;
use App\Models\Subconta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class SubcontaController extends Controller
{
    //
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = auth()->user();

        if(!$user->can('listar subconta')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $subcontas = Subconta::with(['user','entidade', 'conta'])->where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->orderBy('numero', 'asc')->get();
        
        $head = [
            "titulo" => "Sub Contas",
            "descricao" => env('APP_NAME'),
            "subcontas" => $subcontas,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.subcontas.index', $head);
    }

        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $user = auth()->user();
        
        if(!$user->can('criar subconta')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $contas = Conta::where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Cadastrar Subontas",
            "descricao" => env('APP_NAME'),
            "contas" => $contas,
            "requests" => $request->all('subconta_id'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.subcontas.create', $head);
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
        
        if(!$user->can('criar subconta')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'nome' => 'required|string',
            'conta_id' => 'required|string',
            'tipo_operacao' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
            'conta_id.required' => 'A conta é um campo obrigatório',
            'tipo_operacao.required' => 'O tipo de operação é um campo obrigatório',
        ]);
        
        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
    
            $code = uniqid(time());
    
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            $conta = Conta::findOrFail($request->conta_id);
            
            $numero_conta = $conta->conta . "." . $request->numero;
            
            $subconta_ = Subconta::where('conta_id', $conta->id)->where('numero', $numero_conta)->where('entidade_id', $entidade->empresa->id)->first();
            
            if($subconta_){
                return redirect()->back()->with("warning", "Este número da subconta já existe!");
            }
            
            $subconta = Subconta::create([
                'entidade_id' => $entidade->empresa->id, 
                'numero' => $numero_conta,
                'nome' => $request->nome,
                'tipo_conta' => $request->tipo_conta,
                'code' => $code,
                'status' => $request->status,
                'conta_id' => $request->conta_id,
                'user_id' => Auth::user()->id,
            ]);
    
            $subconta->save();
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
        $user = auth()->user();
        
        if(!$user->can('listar subconta')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $subconta = Subconta::with(['conta'])->findOrFail($id);
        
        $head = [
            "titulo" => "Detalhe da Subconta",
            "descricao" => env('APP_NAME'),
            "subconta" => $subconta,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
        
        return view('dashboard.subcontas.show', $head);

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
        
        if(!$user->can('editar subconta')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $subconta = Subconta::with(['entidade', 'user', 'conta'])->findOrFail($id);
        $contas = Conta::where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => "Editar Subconta",
            "descricao" => env('APP_NAME'),
            "subconta" => $subconta,
            "contas" => $contas,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.subcontas.edit', $head);
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
        
        if(!$user->can('editar subconta')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'nome' => 'required|string',
            'conta_id' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
            'conta_id.required' => 'A conta é um campo obrigatório',
        ]);
        
        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
         
            $subconta = Subconta::findOrFail($id);
          
            $subconta->entidade_id = $entidade->empresa->id; 
            $subconta->numero = $request->numero;
            $subconta->nome = $request->nome;
            $subconta->tipo_conta = $request->tipo_conta;
            $subconta->status = $request->status;
            $subconta->conta_id = $request->conta_id;
            $subconta->update();
    
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
        
        if(!$user->can('eliminar subconta')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $subconta = Subconta::findOrFail($id);
            $subconta->delete();
            
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
