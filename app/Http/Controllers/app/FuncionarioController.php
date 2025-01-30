<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Imports\FuncionarioImport;
use App\Models\Conta;
use App\Models\Contrato;
use App\Models\Funcionario;
use App\Models\Distrito;
use App\Models\Entidade;
use App\Models\EstadoCivil;
use App\Models\Municipio;
use App\Models\Provincia;
use App\Models\Seguradora;
use App\Models\Subconta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class FuncionarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $user = auth()->user();
        
        if(!$user->can('listar funcionario')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
 
        $funcionarios = Funcionario::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito'])->where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])->orderBy('created_at', 'desc')->get();
            
        $empresa = Entidade::with("variacoes")->with("marcas")->with("categorias")->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "funcionarios",
            "descricao" => env('APP_NAME'),
            "funcionarios" => $funcionarios,
            "empresa" => $empresa,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.funcionarios.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_import()
    {
        //
        $user = auth()->user();
    
        if(!$user->can('criar funcionario')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

    
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("marcas")->with("categorias")->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Cadastrar funcionarios",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "user" => Auth::user(),
            "estados_civils" => EstadoCivil::get(),
            "provincias" => Provincia::get(),
            "municipios" => Municipio::get(),
            "distritos" => Distrito::get(),
            "seguradores" => Seguradora::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.funcionarios.create-import', $head);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store_import(Request $request)
    {
    
        //
        $user = auth()->user();
        
        if(!$user->can('criar funcionario')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);
        
        try {
            Excel::import(new FuncionarioImport, $request->file('file'));
            return redirect()->back()->with('success', 'Dados importados com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao importar dados: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao importar dados: ' . $e->getMessage());
        }
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
    
        if(!$user->can('criar funcionario')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("marcas")->with("categorias")->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Cadastrar Funcionário",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "user" => Auth::user(),
            "estados_civils" => EstadoCivil::get(),
            "provincias" => Provincia::get(),
            "municipios" => Municipio::get(),
            "distritos" => Distrito::get(),
            "seguradores" => Seguradora::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.funcionarios.create', $head);
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
        $user = auth()->user();
        
        if(!$user->can('criar funcionario')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'nome' => 'required|string',
            'nif' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
            'nif.required' => 'O nif é um campo obrigatório',
        ]);
                    
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $verificar_numero_mecanografico = Funcionario::where('entidade_id', $entidade->empresa->id)->where('numero_mecanografico', $request->numero_mecanografico)->first();
        
        if($verificar_numero_mecanografico){
            return redirect()->back()->with("warning", "Número Mecanografico já existe!");
        }
        
        $verificar_numero_nif = Funcionario::where('entidade_id', $entidade->empresa->id)->where('nif', $request->nif)->first();
        
        if($verificar_numero_nif){
            return redirect()->back()->with("warning", "Número NIF já existe!");
        }

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
                
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
                           
            $code = uniqid(time());
            $nova_conta = "";
            
            $conta = Conta::where('conta', '36')->first();
            
            if($conta){
                $c_ = Conta::findOrFail($conta->id);
                $subc_ = Subconta::where('conta_id', $c_->id)->where('entidade_id', $entidade->empresa->id)->count();
                
                $numero =  $subc_ + 1;
                
                if($request->categoria == "Orgão Sociais"){
                    $nova_conta = $c_->conta. "." . $c_->serie . ".1." . $numero;
                }
                if($request->categoria == "Empregados"){
                    $nova_conta = $c_->conta. "." . $c_->serie . ".2." . $numero;
                }
                if($request->categoria == "Pessoal"){
                    $nova_conta = $c_->conta. "." . $c_->serie . ".3." . $numero;
                }
                
                $subconta = Subconta::create([
                    'entidade_id' => $entidade->empresa->id, 
                    'numero' => $nova_conta,
                    'nome' => $request->nome,
                    'tipo_conta' => 'M',
                    'code' => $code,
                    'status' => $c_->status,
                    'conta_id' => $c_->id,
                    'user_id' => Auth::user()->id,
                ]);
            }else{
                ######################
                ## depois vamos dar o tratamento
            }
                   
            
            $funcionario = Funcionario::create([
                'conta' => $nova_conta,
                "nif" => $request->nif,
                "code" => $code,
                "numero_mecanografico" => $request->numero_mecanografico,
                "nome" => $request->nome,
                "pais" => $request->pais,
                "status" => true,
                "gestor_conta" => $request->gestor_conta,
                "codigo_postal" => $request->codigo_postal,
                "localidade" => $request->localidade,
                "telefone" => $request->telefone,
                "telemovel" => $request->telemovel,
                
                'numero_bilhete' => $request->numero_bilhete,
                'local_emissao_bilhete' => $request->local_emissao_bilhete,
                'data_emissao_bilhete' => $request->data_emissao_bilhete,
                'validade_bilhete' => $request->validade_bilhete,
                'numero_passaporte' => $request->numero_passaporte,
                'local_emissao_passaporte' => $request->local_emissao_passaporte,
                'data_emissao_passaporte' => $request->data_emissao_passaporte,
                'validade_passaporte' => $request->validade_passaporte,
                
                'nome_do_pai' => $request->nome_do_pai,
                'nome_da_mae' => $request->nome_da_mae,
                'data_nascimento' => $request->data_nascimento,
                'genero' => $request->genero,
                'estado_civil_id' => $request->estado_civil_id,
                'seguradora_id' => $request->seguradora_id,
                'provincia_id' => $request->provincia_id,
                'municipio_id' => $request->municipio_id,
                'distrito_id' => $request->distrito_id,
                
                "vencimento" => $request->vencimento,
                "email" => $request->email,
                "website" => $request->website,
                "referencia_externa" => $request->referencia_externa,
                "categoria" => $request->categoria,         
                "user_id" => Auth::user()->id,    
                'entidade_id' => $entidade->empresa->id,      
            ]);
            
            
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        return redirect()->route('funcionarios.index')->with("success", "Dados Cadastrar com Sucesso!");

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
        $user = auth()->user();
    
        if(!$user->can('listar funcionario')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
    
        $funcionario = Funcionario::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito'])->findOrFail($id);
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("marcas")->with("categorias")->findOrFail($entidade->empresa->id);
                
        $contrato = Contrato::with(['categoria', 'pacote_salarial.subsidios_pacotes.subsidio', 'funcionario', 'cargo', 'tipo_contrato', 'user', 'forma_pagamento'])->where('funcionario_id', $funcionario->id)->first();
        
        $head = [
            "titulo" => "Editar Funcionário",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "funcionario" => $funcionario,
            "contrato" => $contrato,
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.funcionarios.show', $head);    
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
        $user = auth()->user();
    
        if(!$user->can('editar funcionario')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
    
        $funcionario = Funcionario::findOrFail($id);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("marcas")->with("categorias")->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Funcionários",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "funcionario" => $funcionario,
            "estados_civils" => EstadoCivil::get(),
            "provincias" => Provincia::get(),
            "municipios" => Municipio::get(),
            "distritos" => Distrito::get(),
            "seguradores" => Seguradora::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.funcionarios.edit', $head);    
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
    
       if(!$user->can('editar funcionario')){
           Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
           return redirect()->back();
       }
    
        $request->validate([
            'nome' => 'required|string',
            'nif' => 'required|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
            'nif.required' => 'O nif é um campo obrigatório',
        ]);
        
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $funcionario = Funcionario::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            
            $code = uniqid(time());
            $nova_conta = "";
            
            $conta = Conta::where('conta', '36')->first();
                        
            if($funcionario->code == NULL){
                if($conta){
                
                    $c_ = Conta::findOrFail($conta->id);
                    $subc_ = Subconta::where('conta_id', $c_->id)->where('entidade_id', $entidade->empresa->id)->count();
                    $numero =  $subc_ + 1;
                    
                    if($request->categoria == "Orgão Sociais"){
                        $nova_conta = $c_->conta. "." . $c_->serie . ".1." . $numero;
                    }
                    if($request->categoria == "Empregados"){
                        $nova_conta = $c_->conta. "." . $c_->serie . ".2." . $numero;
                    }
                    if($request->categoria == "Pessoal"){
                        $nova_conta = $c_->conta. "." . $c_->serie . ".3." . $numero;
                    }
                    
                    $subconta = Subconta::create([
                        'entidade_id' => $entidade->empresa->id, 
                        'numero' => $nova_conta,
                        'nome' => $request->nome,
                        'tipo_conta' => 'M',
                        'code' => $code,
                        'status' => $c_->status,
                        'conta_id' => $c_->id,
                        'user_id' => Auth::user()->id,
                    ]);
                }else{
                    ######################
                    ## depois vamos dar o tratamento
                }
            }else {
                $subc_ = Subconta::where('code', $funcionario->code)->where('entidade_id', $entidade->empresa->id)->first();
                $nova_conta = $funcionario->conta;
                if($subc_){
                    $subc_up = Subconta::findOrFail($subc_->id);
                    $subc_up->numero = $nova_conta;
                    $subc_up->code = $code;
                    $subc_up->nome = $request->nome;
                    $subc_up->update();
                }
                
                ## continuição para edição das categorias
            }
                        
            $funcionario->conta = $nova_conta;
            $funcionario->code = $code;
            $funcionario->nif = $request->nif;
            $funcionario->numero_mecanografico = $request->numero_mecanografico;
            $funcionario->nome = $request->nome;
            
            $funcionario->pais = $request->pais;
            $funcionario->gestor_conta = $request->gestor_conta;
            $funcionario->codigo_postal = $request->codigo_postal;
            $funcionario->localidade = $request->localidade;
            $funcionario->telefone = $request->telefone;
            $funcionario->telemovel = $request->telemovel;
            
            $funcionario->numero_bilhete = $request->numero_bilhete;
            $funcionario->local_emissao_bilhete = $request->local_emissao_bilhete;
            $funcionario->data_emissao_bilhete = $request->data_emissao_bilhete;
            $funcionario->validade_bilhete = $request->validade_bilhete;
            $funcionario->numero_passaporte = $request->numero_passaporte;
            $funcionario->local_emissao_passaporte = $request->local_emissao_passaporte;
            $funcionario->data_emissao_passaporte = $request->data_emissao_passaporte;
            $funcionario->validade_passaporte = $request->validade_passaporte;
            
            $funcionario->nome_do_pai = $request->nome_do_pai;
            $funcionario->nome_da_mae = $request->nome_da_mae;
            $funcionario->data_nascimento = $request->data_nascimento;
            $funcionario->genero = $request->genero;
            $funcionario->estado_civil_id = $request->estado_civil_id;
            $funcionario->seguradora_id = $request->seguradora_id;
            $funcionario->provincia_id = $request->provincia_id;
            $funcionario->municipio_id = $request->municipio_id;
            $funcionario->distrito_id = $request->distrito_id;
            
            $funcionario->vencimento = $request->vencimento;
            $funcionario->email = $request->email;
            $funcionario->website = $request->website;
            $funcionario->referencia_externa = $request->referencia_externa;
            $funcionario->categoria = $request->categoria;         
      
            $funcionario->update();
            
            
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        
        return redirect()->route('funcionarios.index')->with("success", "Dados Actualizados com Sucesso!");
 
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
        $user = auth()->user();
        
        if(!$user->can('eliminar funcionario')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $funcionario = Funcionario::findOrFail($id);
        if($funcionario->delete()){
            return redirect()->route('funcionarios.index')->with("success", "Dados Excluído com Sucesso!");
        }else{
            return redirect()->route('funcionarios.index')->with("warning", "Erro ao tentar Excluir Funcionários");
        }
    }

}
