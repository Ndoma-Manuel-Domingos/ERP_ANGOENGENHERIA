<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracaoRecursoHumano;
use App\Models\Departamento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ConfiguracaoHRController extends Controller
{
    //
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        
        $entidade = User::with('empresa')->findOrFail($user->id);
        
        $configuracao = ConfiguracaoRecursoHumano::where('entidade_id', $entidade->empresa->id)->first();

        $head = [
            "titulo" => "Configurações Recursos Humanos",
            "descricao" => env('APP_NAME'),
            "configuracao" => $configuracao,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.configuracao-rh.create', $head);
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
        $entidade = User::with('empresa')->findOrFail($user->id);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            if($request->configuracao_id != null){
                $configuracao = ConfiguracaoRecursoHumano::findOrFail($request->configuracao_id);
                $configuracao->horas_diarias = $request->horas_diarias;
                $configuracao->horas_semanais = $request->horas_semanais;
                $configuracao->update();
                
            }else {
                
                ConfiguracaoRecursoHumano::create([
                    "horas_diarias" => $request->horas_diarias,
                    "horas_semanais" => $request->horas_semanais,
                    "entidade_id" => $entidade->empresa->id,
                    "user_id" => Auth::user()->id,
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
        
        return response()->json(['success' => true, 'message' => "Dados salvos com sucesso!"], 200);
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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
        
        // if(!$user->can('editar departamento')){
        //     Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        //     return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }
        
        $request->validate([
            'nome' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
      
            $departamento = Departamento::findOrFail($id);
            $departamento->update($request->all());
    
            $departamento->update();      
            
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

    }

}
