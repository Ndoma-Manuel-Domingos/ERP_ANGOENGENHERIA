<?php

namespace App\Http\Controllers\config;

use App\Http\Controllers\Controller;
use App\Models\Entidade;
use App\Models\TipoEntidade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IdentidadeEmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $head = [
            "titulo" => "Identidade e Actividade da Empresa",
            "descricao" => env('APP_NAME'),
            "user" => User::with('empresa')->findOrFail(Auth::user()->id),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
            "tipos_entidade" => TipoEntidade::where('status', 'activo')->get(),
        ];
            
        return view('dashboard.config.identidade', $head);
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
        
        $request->validate(
            [
                'nif' => 'required',
                'empresa' => 'required',
                'tipo_negocio' => 'required',
                'nome_dono' => 'required',
            ],
            [
                'nif.required' => "O nif é obrigatório",
                'empresa.required' => "O nome da empresa é obrigatório",
                'tipo_negocio.required' => "O tipo de negócio é obrigatório",
                'nome_dono.required' => "O tipo de negócio é obrigatório",
            ]
        );
        
        $entidade = Entidade::findOrFail($id);
        
        if($request->tipo_empresa == "Fisica"){
            $request->tipo_negocio = NULL;
        }else{
            $request->tipo_negocio = $request->tipo_negocio;
        }
    
        $entidade->update([
            "nif" => $request->nif,
            "nome" => $request->empresa,
            "tipo_id" => $request->tipo_negocio,
            "promocoes_email" => $request->promocao_email,
            "novidade_email" => $request->promocao_novidade_email,
        ]);

        if($entidade->save()){

            $user = User::findOrFail(Auth::user()->id);
            $user->update([
                "name" => $request->nome_dono,
            ]);
            
            $user->save();

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
