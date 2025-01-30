<?php

namespace App\Http\Controllers\config;

use App\Http\Controllers\Controller;
use App\Models\Entidade;
use App\Models\Imposto;
use App\Models\Motivo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DadosEmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $dados = User::with("empresa")->findOrFail(Auth::user()->id);
        
        $head = [
            "titulo" => "Dados da Empresa",
            "descricao" => env('APP_NAME'),
            "dados" => $dados,
            "motivos" => Motivo::get(),
            "impostos" => Imposto::get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.config.dados-empresa', $head);
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
        $motivo = Motivo::findOrFail($request->motivo_isencao);
        
        $imposto = Imposto::findOrFail($request->taxa_iva);


        $dados = Entidade::findOrFail($id);
        $dados->update([
            'morada' => $request->morada,
            'codigo_postal' => $request->codigo_postal,
            'cidade' => $request->cidade,
            'conservatoria' => $request->conservatoria,
            'capital_social' => $request->capital_social,
            'nome_comercial' => $request->nome_comercial,
            'slogan' => $request->slogan,
            'pais' => $request->pais,
            'moeda' => $request->moeda,
            'tipo_inventario' => $request->tipo_inventario,
         
            "imposto_id" => $imposto->id,
            "tipo_regime_id" => $request->tipo_regime_id,
            "taxa_iva" => $imposto->codigo,
            "tipo_retencao_fonte" => $request->tipo_retencao_fonte,
            "taxa_retencao_fonte" => $request->taxa_retencao_fonte,

            "motivo_isencao" => $motivo->codigo,
            "motivo_id" => $motivo->id,
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
