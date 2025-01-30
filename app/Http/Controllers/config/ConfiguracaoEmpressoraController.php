<?php

namespace App\Http\Controllers\config;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracaoEmpressora;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfiguracaoEmpressoraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
   
        $dados = ConfiguracaoEmpressora::where('entidade_id', Auth::user()->entidade_id)->first();
  
        $head = [
            "titulo" => "Configuração da Impressão",
            "descricao" => env('APP_NAME'),
            "dados" => $dados,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
        

        return view('dashboard.config.configurar-empressao', $head);
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
        //
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
        $dados = ConfiguracaoEmpressora::findOrFail($id);
        
        $dados->update([
            'empressao' => $request->empressao,
            'funcionamento' => $request->funcionamento,
            'metodo_empressao' => $request->metodo_empressao,
        ]);

        if($dados->save()){
            return redirect()->route('dashboard')->with("success", "Dados Actualizados com Sucesso!");
        }else{
            return redirect()->route('dashboard')->with("warning", "Erro ao Actualizar os dados da empresa");
        }
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
