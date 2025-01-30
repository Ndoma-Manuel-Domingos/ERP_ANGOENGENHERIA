<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Distrito;
use App\Models\Enfermeiro;
use App\Models\Entidade;
use App\Models\EstadoCivil;
use App\Models\Municipio;
use App\Models\Provincia;
use App\Models\Seguradora;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnfermeiroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
 
        $enfermeiros = Enfermeiro::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito'])->where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])->orderBy('created_at', 'desc')->get();
            
        $empresa = Entidade::with(['variacoes', 'clientes', 'marcas','categorias'])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "enfermeiros",
            "descricao" => env('APP_NAME'),
            "enfermeiros" => $enfermeiros,
            "empresa" => $empresa,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.enfermeiros.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(['variacoes', 'clientes', 'marcas', 'categorias'])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Cadastrar Enfermeiros",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "user" => Auth::user(),
            "estados_civils" => EstadoCivil::get(),
            "provincias" => Provincia::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "municipios" => Municipio::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "distritos" => Distrito::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "seguradores" => Seguradora::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.enfermeiros.create', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string',
            'nif' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
            'nif.required' => 'O nif é um campo obrigatório',
        ]);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $enfermeiros = Enfermeiro::create([
            "nif" => $request->nif,
            "nome" => $request->nome,
            "pais" => $request->pais,
            "status" => true,
            "codigo_postal" => $request->codigo_postal,
            "localidade" => $request->localidade,
            "telefone" => $request->telefone,
            "telemovel" => $request->telemovel,
            
            'nome_do_pai' => $request->nome_do_pai,
            'nome_da_mae' => $request->nome_da_mae,
            'data_nascimento' => $request->data_nascimento,
            'genero' => $request->genero,
            'estado_civil_id' => $request->estado_civil_id,
            'seguradora_id' => $request->seguradora_id,
            'provincia_id' => $request->provincia_id,
            'municipio_id' => $request->municipio_id,
            'distrito_id' => $request->distrito_id,
            
            "email" => $request->email,
            "website" => $request->website,
            "referencia_externa" => $request->referencia_externa,
            "observacao" => $request->observacao,         
            "user_id" => Auth::user()->id,    
            'entidade_id' => $entidade->empresa->id,      
        ]);
        
        if($enfermeiros->save()){
            return redirect()->route('enfermeiros.index')->with("success", "Dados Cadastrar com Sucesso!");
        }else{
            return redirect()->route('enfermeiros.create')->with("warning", "Erro ao tentar cadastrar enfermeiro");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $enfermeiro = Enfermeiro::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito'])->findOrFail($id);
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(['variacoes', 'clientes' ,'marcas', 'categorias'])->findOrFail($entidade->empresa->id);


        $head = [
            "titulo" => "Enfermeiro",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "enfermeiro" => $enfermeiro,
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.enfermeiros.show', $head);    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $enfermeiro = Enfermeiro::findOrFail($id);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(['variacoes', 'clientes', 'marcas', 'categorias'])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Enfermeiro",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "enfermeiro" => $enfermeiro,
            "estados_civils" => EstadoCivil::get(),
            "provincias" => Provincia::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "municipios" => Municipio::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "distritos" => Distrito::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "seguradores" => Seguradora::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.enfermeiros.edit', $head);    
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
        $request->validate([
            'nome' => 'required|string',
            'nif' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
            'nif.required' => 'O nif é um campo obrigatório',
        ]);

        $enfermeiro = Enfermeiro::findOrFail($id);
            
        $enfermeiro->update($request->all());

        if($enfermeiro->save()){
            return redirect()->route('enfermeiros.index')->with("success", "Dados Actualizados com Sucesso!");
        }else{
            return redirect()->route('enfermeiros.create')->with("warning", "Erro ao tentar Actualizar Enfermeiro");
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
        $enfermeiro = Enfermeiro::findOrFail($id);
        if($enfermeiro->delete()){
            return redirect()->route('enfermeiros.index')->with("success", "Dados Excluído com Sucesso!");
        }else{
            return redirect()->route('enfermeiros.index')->with("warning", "Erro ao tentar Excluir enfermeiro");
        }
    }

}
