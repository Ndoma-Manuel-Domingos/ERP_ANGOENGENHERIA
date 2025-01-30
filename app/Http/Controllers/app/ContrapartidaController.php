<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Contrapartida;
use App\Models\Subconta;
use App\Models\TipoCredito;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

use PDF;

class ContrapartidaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        
        if(!$user->can('listar banco')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
    
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $contrapartidas = Contrapartida::with(['subconta', 'tipo_credito'])->where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => "Contrapartidas",
            "descricao" => env('APP_NAME'),
            "contrapartidas" => $contrapartidas,
            "entidade" => $entidade,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contrapartidas.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('criar banco')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $subcontas = Subconta::where('entidade_id', $entidade->empresa->id)->get();
        $tipos_creditos = TipoCredito::where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Cadastrar Contrapartida",
            "descricao" => env('APP_NAME'),
            "subcontas" => $subcontas,
            "tipos_creditos" => $tipos_creditos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contrapartidas.create', $head);
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
        
        if(!$user->can('criar banco')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'tipo_credito_id' => 'required|string',
        ],[
            'tipo_credito_id.required' => 'O Tipo de crédito é um campo obrigatório'
        ]);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            
            foreach ($request->subconta_id as $item) {
                Contrapartida::create([
                    'subconta_id' => $item,
                    'tipo_credito_id' => $request->tipo_credito_id,
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
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


        Alert::success("Sucesso!", "Dados Cadastrar com Sucesso!");
        return redirect()->route('contrapartidas.index');
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {

        $user = auth()->user();
        
        if(!$user->can('listar banco')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $contrapartida = Contrapartida::with(['subconta', 'tipo_credito',' user', 'entidade'])->findOrFail($id);

        $head = [
            "titulo" => "Detalhe da Contrapartida",
            "descricao" => env('APP_NAME'),
            "contrapartida" => $contrapartida,
            "requests" => $request->all('data_inicio', 'data_final'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contrapartidas.show', $head);
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
        
        if(!$user->can('editar banco')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $contrapartida = Contrapartida::with(['subconta', 'tipo_credito',' user', 'entidade'])->findOrFail($id);
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $subcontas = Subconta::where('entidade_id', $entidade->empresa->id)->get();
        $tipos_creditos = TipoCredito::where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Editar Contrapartida",
            "descricao" => env('APP_NAME'),
            "subcontas" => $subcontas,
            "tipos_creditos" => $tipos_creditos,
            "contrapartida" => $contrapartida,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contrapartidas.edit', $head);
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
        $user = auth()->user();
        
        if(!$user->can('editar banco')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'tipo_credito_id' => 'required|string',
            'subconta_id' => 'required|string',
        ],[
            'tipo_credito_id.required' => 'O tipo de crédito é um campo obrigatório',
            'subconta_id.required' => 'A subconta é um campo obrigatório',
        ]);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
                                  
            $contrapartida = Contrapartida::findOrFail($id);
            
            $contrapartida->tipo_credito_id = $request->tipo_credito_id;
            $contrapartida->subconta_id = $request->subconta_id;
            
            $contrapartida->update();
            
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }


        Alert::success("Sucesso!", "Dados Actualizados com Sucesso!");
        return redirect()->route('contrapartidas.index');
       
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
        
        if(!$user->can('eliminar banco')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $contrapartida = Contrapartida::findOrFail($id);
            $contrapartida->delete();
           // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        
        return redirect()->route('contrapartidas.index')->with("success", "Dados Excluído com sucesso!");
    }

    
}
