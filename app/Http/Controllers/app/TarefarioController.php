<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Quarto;
use App\Models\QuartoTarefario;
use App\Models\Tarefario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class TarefarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        if(!$user->can('listar tarefario')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $tarefarios = Tarefario::with(['tarefarios.quarto'])->where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => "Tarifários",
            "descricao" => env('APP_NAME'),
            "tarefarios" => $tarefarios,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.tarefarios.index', $head);
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
        
        if(!$user->can('criar tarefario')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $quartos = Quarto::where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Cadastrar Tarifários",
            "descricao" => env('APP_NAME'),
            "quartos" => $quartos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.tarefarios.create', $head);
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
        
        if(!$user->can('criar tarefario')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'valor' => 'required|string',
            'modo_tarefario' => 'required|string',
            'tipo_cobranca' => 'required|string',
        ],[
            'valor.required' => 'O valor é um campo obrigatório',
            'modo_tarefario.required' => 'O modo de tarefario é um campo obrigatório',
            'tipo_cobranca.required' => 'O tipo de cobrança é um campo obrigatório',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            $code = uniqid(time());
            
            $tarefario = Tarefario::create([
                'entidade_id' => $entidade->empresa->id, 
                'valor' => $request->valor,
                'nome' => $request->nome,
                'modo_tarefario' => $request->modo_tarefario,
                'tipo_cobranca' => $request->tipo_cobranca,
                'status' => $request->status,
                'code' => $code,
                'user_id' => Auth::user()->id,
            ]);
            
            if (!empty($request->quarto_id) && isset($request->quarto_id)) {
                foreach ($request->quarto_id as $key) {
                    $verificar = QuartoTarefario::where('quarto_id', $key)->where('tarefario_id', $tarefario->id)->first();
                    if(!$verificar){
                        QuartoTarefario::create([
                            'quarto_id' => $key,
                            'tarefario_id' => $tarefario->id,
                            'entidade_id' => $entidade->empresa->id, 
                            'user_id' => Auth::user()->id,
                        ]);
                    }
                }
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
        
        return redirect()->back()->with("success", "Dados Cadastrar com Sucesso!");
      
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function associar($id)
    {
        //
        $user = auth()->user();
        
        if(!$user->can('criar tarefario')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $quartos = Quarto::where('entidade_id', $entidade->empresa->id)->get();
             
        $tarefario = Tarefario::with(['tarefarios.quarto'])->findOrFail($id);
        
        $head = [
            "titulo" => "Associar Quartos",
            "descricao" => env('APP_NAME'),
            "tarefario" => $tarefario,
            "quartos" => $quartos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.tarefarios.associar-quartos', $head);
    }
    

        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function associar_store(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('criar tarefario')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'tarefario_id' => 'required|string',
        ],[
            'tarefario_id.required' => 'O tarefario é um campo obrigatório',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            $tarefario = Tarefario::with(['tarefarios.quarto'])->findOrFail($request->tarefario_id);
            
            if (!empty($request->quarto_id) && isset($request->quarto_id)) {
                foreach ($request->quarto_id as $key) {
                    $verificar = QuartoTarefario::where('quarto_id', $key)->where('tarefario_id', $tarefario->id)->first();
                    if(!$verificar){
                        QuartoTarefario::create([
                            'quarto_id' => $key,
                            'tarefario_id' => $tarefario->id,
                            'entidade_id' => $entidade->empresa->id, 
                            'user_id' => Auth::user()->id,
                        ]);
                    }
                }
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
        
        return redirect()->back()->with("success", "Dados Cadastrar com Sucesso!");
      
    }
        
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function desassociar($id)
    {
        $user = auth()->user();
        
        if(!$user->can('listar tarefario')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $tarefario = QuartoTarefario::findOrFail($id);
        $tarefario->delete();
        
        return redirect()->back()->with("success", "Tarifário desassociado com sucesso!");
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
        
        if(!$user->can('listar tarefario')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        
        $tarefario = Tarefario::findOrFail($id);
        $tarefario->status = 'activo';
        $tarefario->update();
        
        return redirect()->back()->with("success", "tarefario activado com sucesso!");
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
        
        if(!$user->can('listar tarefario')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $tarefario = Tarefario::findOrFail($id);
        $tarefario->status = 'desactivo';
        $tarefario->update();
        
        return redirect()->back()->with("success", "tarefario desactivado com sucesso!!");

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
        
        if(!$user->can('listar tarefario')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $tarefario = Tarefario::with(['tarefarios.quarto'])->findOrFail($id);
        
        $head = [
            "titulo" => "Detalhe do Tarifário",
            "descricao" => env('APP_NAME'),
            "tarefario" => $tarefario,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.tarefarios.show', $head);

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
        
        if(!$user->can('editar tarefario')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $tarefario = Tarefario::findOrFail($id);

        $head = [
            "titulo" => "Editar Tarifário",
            "descricao" => env('APP_NAME'),
            "tarefario" => $tarefario,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.tarefarios.edit', $head);
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
            
            if(!$user->can('editar tarefario')){
                Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
                return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            }
            
            $request->validate([
                'valor' => 'required|string',
                'modo_tarefario' => 'required|string',
                'tipo_cobranca' => 'required|string',
            ],[
                'valor.required' => 'O valor é um campo obrigatório',
                'modo_tarefario.required' => 'O mode de tarefario é um campo obrigatório',
                'tipo_cobranca.required' => 'O tipo de cobrança é um campo obrigatório',
            ]);
    
            $tarefario = Tarefario::findOrFail($id);
            $tarefario->update($request->all());
            
            $tarefario->update();
            
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
        
        if(!$user->can('eliminar tarefario')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $tarefario = Tarefario::findOrFail($id);
            $tarefario->delete();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        return redirect()->route('tarefarios.index')->with("success", "Dados Excluído com Sucesso!");

    }

}
