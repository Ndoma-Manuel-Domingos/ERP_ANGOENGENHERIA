<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\AnoLectivo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AnoLectivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        
        if(!$user->can('listar ano lectivo')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $anos_lectivos = AnoLectivo::where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => "Ano Lectivos",
            "descricao" => env('APP_NAME'),
            "anos_lectivos" => $anos_lectivos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.anos-lectivos.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        
        if(!$user->can('criar ano lectivo')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
                
        $head = [
            "titulo" => "Cadastrar Ano Lectivo",
            "descricao" => env('APP_NAME'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.anos-lectivos.create', $head);
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
        
        if(!$user->can('criar ano lectivo')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        
        $request->validate([
            'nome' => 'required|string',
            'sigla' => 'required|string',
            'data_inicio' => 'required',
            'data_final' => 'required',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
            'sigla.required' => 'A sigla é um campo obrigatório',
            'data_inicio.string' => 'A data de início é um campo obrigatório',
            'data_final.string' => 'A data final é um campo obrigatório',
        ]);
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        // Verificar se esta entidade já tem um ano lectivo activo
        
        $verificar = AnoLectivo::where('status', 'activo')->where('entidade_id', $entidade->empresa->id)->first();      
        
        if($verificar && $request->status == "activo"){
            return redirect()->back()->with("danger", "Não pode ter dois anos lectivo activo no momento!");
        }else{
            $ano_lectivo = AnoLectivo::create([
                'nome' => $request->nome,
                'sigla' => $request->sigla,
                'status' => $request->status,
                'data_inicio' => $request->data_inicio,
                'data_final' => $request->data_final,
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);
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
        
        if(!$user->can('listar ano lectivo')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        
        $ano_lectivo = AnoLectivo::findOrFail($id);
        
        $head = [
            "titulo" => "Detalhe Ano Lectivo",
            "descricao" => env('APP_NAME'),
            "ano_lectivo" => $ano_lectivo,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.anos-lectivos.show', $head);

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
        
        if(!$user->can('editar ano lectivo')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        
        $ano_lectivo = AnoLectivo::findOrFail($id);

        $head = [
            "titulo" => "Ano Lectivo",
            "descricao" => env('APP_NAME'),
            "ano_lectivo" => $ano_lectivo,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.anos-lectivos.edit', $head);
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
        
        if(!$user->can('editar ano lectivo')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
                
        $request->validate([
            'nome' => 'required|string',
            'data_inicio' => 'required',
            'data_final' => 'required',
            'sigla' => 'required',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
            'data_inicio.string' => 'A data de início é um campo obrigatório',
            'data_final.string' => 'A data final é um campo obrigatório',
            'sigla.string' => 'A sigla é um campo obrigatório',
        ]);

        $ano_lectivo = AnoLectivo::findOrFail($id);
        
        $ano_lectivo->nome = $request->nome;
        $ano_lectivo->sigla = $request->sigla;
        $ano_lectivo->data_inicio = $request->data_inicio;
        $ano_lectivo->data_final = $request->data_final;
        $ano_lectivo->status = $request->status;
        
        $ano_lectivo->update();
        
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
        
        if(!$user->can('eliminar ano lectivo')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        
        $ano_lectivo = AnoLectivo::findOrFail($id);
        if($ano_lectivo->delete()){
            return redirect()->route('anos-lectivos.index')->with("success", "Dados Excluído com Sucesso!");
        }else{
            return redirect()->route('anos-lectivos.index')->with("warning", "Erro ao tentar Excluir turno");
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mudar_status($id)
    {
        $user = auth()->user();
        
        if(!$user->can('listar ano lectivo')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
                
        $ano_lectivo = AnoLectivo::findOrFail($id);
        
        if($ano_lectivo->status == "activo"){
            $ano_lectivo->status = 'desactivo';
            $ano_lectivo->update();
        }else {
            
            $ano_lectivos = AnoLectivo::where('status', 'activo')->where('entidade_id', $entidade->empresa->id)->get();   
            
            foreach($ano_lectivos as $item) {
                $update = AnoLectivo::findOrFail($item->id);
                $update->status = 'desactivo';
                $update->update();
            }
            
            $ano_lectivo->status = 'activo';
            $ano_lectivo->update();
            
        }
        
        return redirect()->back()->with("success", "Dados Actualizados com Sucesso!");

    }
}
