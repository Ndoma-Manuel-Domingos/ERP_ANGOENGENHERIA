<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use App\Models\CategoriaCargo;
use App\Models\Desconto;
use App\Models\DescontoPacote;
use App\Models\PacoteSalarial;
use App\Models\Subsidio;
use App\Models\SubsidioPacote;
use App\Models\TipoProcessamento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class PacoteSalarialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = auth()->user();

        if(!$user->can('listar pacote')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $pacotes = PacoteSalarial::with(['categoria', 'cargo', 'desconto_pacotes.desconto', 'subsidios_pacotes.subsidio', 'subsidios_pacotes.processamento'])->where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => "Pacotes Salarial",
            "descricao" => env('APP_NAME'),
            "pacotes" => $pacotes,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.pacotes-salarial.index', $head);
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
        
        if(!$user->can('criar pacote')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $categorias = CategoriaCargo::where('entidade_id', $entidade->empresa->id)->get();
        $cargos = Cargo::where('entidade_id', $entidade->empresa->id)->get();
        $subsidios = Subsidio::where('entidade_id', $entidade->empresa->id)->get();
        $descontos = Desconto::where('entidade_id', $entidade->empresa->id)->get();
        $processamentos = TipoProcessamento::where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Cadastrar Pacotes Salarial",
            "descricao" => env('APP_NAME'),
            "categorias" => $categorias,
            "cargos" => $cargos,
            "subsidios" => $subsidios,
            "descontos" => $descontos,
            "processamentos" => $processamentos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.pacotes-salarial.create', $head);
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
        
        if(!$user->can('criar pacote')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'cargo_id' => 'required|string',
            'categoria_id' => 'required|string',
            'salario_base' => 'required|string',
        ],[
            'cargo_id.required' => 'O cargo é um campo obrigatório',
            'categoria_id.required' => 'A categoria é um campo obrigatório',
            'salario_base.required' => 'O salário é um campo obrigatório',
        ]);

        //
        try {
            DB::beginTransaction();
            
            // Realizar operações de banco de dados aqui
                        
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            $verificar_pacote = PacoteSalarial::where('entidade_id', $entidade->empresa->id)->where('cargo_id', $request->cargo_id)->where('categoria_id', $request->categoria_id)->first();
            
            if($verificar_pacote){
                return redirect()->back()->with("danger", "Este pacote salárial já esta cadastrado!");
            }
            
            $pacote = PacoteSalarial::create([
                'cargo_id' => $request->cargo_id,
                'categoria_id' => $request->categoria_id,
                'salario_base' => $request->salario_base,
                'status' => $request->status,
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);
          
            $pacote->save();
            
            foreach ($request->subsidio_id as $index => $subsidioId) {
                SubsidioPacote::create([
                    'subsidio_id' => $subsidioId,
                    'pacote_id' => $pacote->id,
                    'salario' => $request->salario_subsidio[$index],
                    'processamento_id' => $request->processamento_id[$index],
                    'limite_isencao' => $request->limite_isencao[$index],
                    'irt' => $request->irt[$index],
                    'inss' => $request->inss[$index],
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
            }
        
            foreach ($request->desconto_id as $index => $descontoId) {
                DescontoPacote::create([
                    'desconto_id' => $descontoId,
                    'pacote_id' => $pacote->id,
                    'salario' => $request->salario_desconto[$index],
                    'processamento_id' => $request->processamento_desconto_id[$index],
                    'tipo_valor' => $request->tipo_valor[$index],
                    'irt' => $request->irt_desconto[$index],
                    'inss' => $request->inss_desconto[$index],
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
        
        if(!$user->can('listar pacote')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $pacote = PacoteSalarial::with(['categoria', 'cargo', 'subsidios_pacotes.subsidio', 'desconto_pacotes.desconto' ])->findOrFail($id);
        
        $head = [
            "titulo" => "Detalhe Pacote Salarial",
            "descricao" => env('APP_NAME'),
            "pacote" => $pacote,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.pacotes-salarial.show', $head);

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
        
        if(!$user->can('editar pacote')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $pacote = PacoteSalarial::with(['categoria', 'cargo', 'subsidios_pacotes.subsidio', 'desconto_pacotes.desconto' ])->findOrFail($id);
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
                
        $categorias = CategoriaCargo::where('entidade_id', $entidade->empresa->id)->get();
        $cargos = Cargo::where('entidade_id', $entidade->empresa->id)->get();
        $subsidios = Subsidio::where('entidade_id', $entidade->empresa->id)->get();
        $descontos = Desconto::where('entidade_id', $entidade->empresa->id)->get();
        $processamentos = TipoProcessamento::where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Editar Pacotes Salarial",
            "descricao" => env('APP_NAME'),
            "pacote" => $pacote,
            "categorias" => $categorias,
            "cargos" => $cargos,
            "subsidios" => $subsidios,
            "descontos" => $descontos,
            "processamentos" => $processamentos,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.pacotes-salarial.edit', $head);
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
        
        if(!$user->can('editar pacote')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'cargo_id' => 'required|string',
            'categoria_id' => 'required|string',
            'salario_base' => 'required|string',
        ],[
            'cargo_id.required' => 'O cargo é um campo obrigatório',
            'categoria_id.required' => 'A categoria é um campo obrigatório',
            'salario_base.required' => 'O salário é um campo obrigatório',
        ]);
        
        //
        try {
            DB::beginTransaction();
            
            // Realizar operações de banco de dados aqui
                
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            $pacote = PacoteSalarial::findOrFail($id);
                   
            $pacote->update($request->all());
    
            $pacote->update();
            
            // Deletar os registros atuais do funcionário para recriar os novos
            SubsidioPacote::where('pacote_id', $pacote->id)->delete();
            DescontoPacote::where('pacote_id', $pacote->id)->delete();
            
            // Recriar os registros com os novos dados
            foreach ($request->subsidio_id as $index => $subsidioId) {
                SubsidioPacote::create([
                    'subsidio_id' => $subsidioId,
                    'pacote_id' => $pacote->id,
                    'salario' => $request->salario_subsidio[$index],
                    'processamento_id' => $request->processamento_id[$index],
                    'inss' => $request->inss[$index],
                    'irt' => $request->irt[$index],
                    'limite_isencao' => $request->limite_isencao[$index],
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
            }
            
            foreach ($request->desconto_id as $index => $descontoId) {
                DescontoPacote::create([
                    'desconto_id' => $descontoId,
                    'pacote_id' => $pacote->id,
                    'salario' => $request->salario_desconto[$index],
                    'processamento_id' => $request->processamento_desconto_id[$index],
                    'tipo_valor' => $request->tipo_valor[$index],
                    'irt' => $request->irt_desconto[$index],
                    'inss' => $request->inss_desconto[$index],
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
        
        if(!$user->can('eliminar pacote')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $pacote = PacoteSalarial::findOrFail($id);
        
        // Deletar os registros atuais do funcionário para recriar os novos
        SubsidioPacote::where('pacote_id', $pacote->id)->delete();
    
        $pacote->delete();
        
        return redirect()->back()->with("success", "Dados Excluído com Sucesso!");
    
    }

}
