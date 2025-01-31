<?php

namespace App\Http\Controllers\config;

use App\Http\Controllers\Controller;
use App\Models\Entidade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonalizarEmpressoraController extends Controller
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
            "titulo" => "Personalizar Impressora",
            "descricao" => env('APP_NAME'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.config.personalizar-impressao', $head);
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
        if($request->hasFile('logotipo') && $request->file('logotipo')->isValid()){
            $requestImage = $request->logotipo;
            $extension = $requestImage->extension();

            $imageName = $requestImage->getClientOriginalName() . strtotime("now") . "." . $extension;

            $request->logotipo->move(public_path('images/empresa'), $imageName);
        }else{
            $imageName = $request->logotipo_guardado;
        }
        
        $dados = Entidade::findOrFail($id);
        
        $dados->update([
            'logotipo' => $imageName,
            
            'telefone' => $request->telefone,
            'telemovel' => $request->telemovel,
            'fax' => $request->fax,
            'website' => $request->website,
            'email' => $request->email,
            
            'tipo_factura' => $request->tipo_factura,
            
            'cabecalho' => $request->cabecalho,
            'rodape' => $request->rodape,
            
            'banco' => $request->banco,
            'conta' => $request->conta,
            'iban' => $request->iban,
            
            'banco1' => $request->banco1,
            'conta1' => $request->conta1,
            'iban1' => $request->iban1,
            
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
