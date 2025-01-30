<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\ModuloEntidade;
use App\Models\TipoEntidade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class TipoEntidadeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipo_entidade = TipoEntidade::orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => "Tipo Entidade",
            "descricao" => env('APP_NAME'),
            "tipos_entidade" => $tipo_entidade,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('admin.tipos_entidade.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $head = [
            "titulo" => "Cadastrar Tipo Entidade",
            "descricao" => env('APP_NAME'),
            "modulos_entidade" => ModuloEntidade::get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('admin.tipos_entidade.create', $head);
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
        $request->validate([
            'tipo' => 'required',
        ],[
            'tipo.required' => 'O tipo é um campo obrigatório',
        ]);
        
        try {
            DB::beginTransaction();
      
            $tipo_entidade = TipoEntidade::create($request->all());
    
            $tipo_entidade->modulos()->attach($request->modulo_id);
            
            $tipo_entidade->save();
            
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
        $tipo_entidade = TipoEntidade::findOrFail($id);
        
        $head = [
            "titulo" => "Detalhe Tipo Entidade",
            "descricao" => env('APP_NAME'),
            "tipo_entidade" => $tipo_entidade,
            "modulos_entidade" => ModuloEntidade::get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('admin.tipos_entidade.show', $head);
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
        $tipo_entidade = TipoEntidade::with(['modulos'])->findOrFail($id);
        
        $entidade_permissions = $tipo_entidade->modulos->pluck('id')->toArray();

        $head = [
            "titulo" => "Tipo Entidade",
            "descricao" => env('APP_NAME'),
            "tipo_entidade" => $tipo_entidade,
            "entidade_permissions" => $entidade_permissions,
            "modulos_entidade" => ModuloEntidade::get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('admin.tipos_entidade.edit', $head);
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
            'tipo' => 'required|string',
        ],[
            'tipo.required' => 'O tipo é um campo obrigatório',
        ]);

        try {
            DB::beginTransaction();
            
            $tipo_entidade = TipoEntidade::findOrFail($id);
            $tipo_entidade->tipo = $request->tipo;
            $tipo_entidade->status = $request->status;
            $tipo_entidade->sigla = $request->sigla;
            $tipo_entidade->descricao = $request->descricao;
            
            $tipo_entidade->modulos()->sync($request->input('modulo_id', []));
    
            $tipo_entidade->update();
            
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
        try {
            DB::beginTransaction();
            //
            $tipo_entidade = TipoEntidade::findOrFail($id);
            $tipo_entidade->delete();
            
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
