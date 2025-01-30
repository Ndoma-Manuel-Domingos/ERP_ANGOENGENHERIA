<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Imports\ClienteImport;
use App\Models\Cliente;
use App\Models\Conta;
use App\Models\ContaCliente;
use App\Models\Distrito;
use App\Models\Entidade;
use App\Models\EstadoCivil;
use App\Models\Itens_venda;
use App\Models\Municipio;
use App\Models\Provincia;
use App\Models\Seguradora;
use App\Models\Subconta;
use App\Models\User;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

use PDF;

class ClienteController extends Controller
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
        
        if(!$user->can('listar cliente')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
 
        $clientes = Cliente::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito'])->where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])->orderBy('conta', 'asc')->get();
            
        $empresa = Entidade::with("variacoes")->with('clientes')->with("marcas")->with("categorias")->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Clientes",
            "descricao" => env('APP_NAME'),
            "clientes" => $clientes,
            "empresa" => $empresa,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.index', $head);
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
    
        if(!$user->can('criar cliente')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

    
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with('clientes')->with("marcas")->with("categorias")->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Cadastrar clientes",
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

        return view('dashboard.clientes.create-import', $head);
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
        
        if(!$user->can('criar cliente')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);
        
        try {
            Excel::import(new ClienteImport, $request->file('file'));
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
    
        if(!$user->can('criar cliente')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with('clientes')->with("marcas")->with("categorias")->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Cadastrar clientes",
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

        return view('dashboard.clientes.create', $head);
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
        
        if(!$user->can('criar cliente')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'nome' => 'required|string',
            'nif' => 'required|string',
        ],[
            'nome.required' => 'Nome do cliente é obrigatório',
            'nif.required' => 'O Nif ou B.I do cliente é obrigatório',
        ]);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
    
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            $code = uniqid(time());
            $nova_conta = "";
            
            $conta = Conta::where('conta', '31')->first();
            
            if($request->tipo_cliente == "C"){
                $numero_inicial = ENV('CLIENTES_CORRENTES');
            }
            if($request->tipo_cliente == "TR"){
                $numero_inicial = ENV('CLIENTES_TITULOS_A_RECEBER');
            }
            if($request->tipo_cliente == "TD"){
                $numero_inicial = ENV('CLIENTES_TITULOS_DESCONTADOS');
            }
            if($request->tipo_cliente == "CD"){
                $numero_inicial = ENV('CLIENTES_COBRANCA_DUVIDOS');
            }
            if($request->tipo_cliente == "SC"){
                $numero_inicial = ENV('CLIENTES_SALDO_CREDOR');
            }
            
            if($conta){
                $subc_ = Subconta::where('numero', 'like', $numero_inicial. "%")->where('entidade_id', $entidade->empresa->id)->count();
                
                $numero =  $subc_ + 1;
                $nova_conta = "{$numero_inicial}{$numero}";
                
                $subconta = Subconta::create([
                    'entidade_id' => $entidade->empresa->id, 
                    'numero' => $nova_conta,
                    'nome' => $request->nome,
                    'tipo_conta' => 'M',
                    'code' => $code,
                    'status' => $conta->status,
                    'conta_id' => $conta->id,
                    'user_id' => Auth::user()->id,
                ]);
            }else{
                ######################
                ## depois vamos dar o tratamento
            }
            
            
            $clientes = Cliente::create([
                "nif" => $request->nif,
                "nome" => $request->nome,
                "conta" => $nova_conta,
                "tipo_cliente" => $request->tipo_cliente,
                "code" => $code,
                "pais" => $request->pais,
                "status" => true,
                "gestor_conta" => $request->gestor_conta,
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
                
                "vencimento" => $request->vencimento,
                "email" => $request->email,
                "website" => $request->website,
                "referencia_externa" => $request->referencia_externa,
                "observacao" => $request->observacao,               
                "user_id" => Auth::user()->id,    
                'entidade_id' => $entidade->empresa->id,      
            ]);
            
            $saldo = ContaCliente::create([
                'user_id' => Auth::user()->id,
                'divida_corrente' => 0,
                'divida_vencida' => 0,
                'saldo' => 0,
                'cliente_id' => $clientes->id,
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

        
        return redirect()->route('clientes.index')->with("success", "Dados Cadastrar com Sucesso!");
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
    
        if(!$user->can('listar cliente')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
    
        $cliente = Cliente::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito'])->findOrFail($id);
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with('clientes')->with("marcas")->with("categorias")->findOrFail($entidade->empresa->id);

        $conta = ContaCliente::where([
            ['cliente_id', '=', $cliente->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])->first();

        $facturas = Venda::where([
            ['status_factura', '=','por pagar'],
            ['cliente_id', '=', $cliente->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->with('cliente')
        ->orderby('created_at', 'desc')
        ->get();
        
        $valorTotalCompras = Venda::where([
            ['status_factura', '=','pago'],
            ['cliente_id', '=', $cliente->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->sum('valor_total');

        // dividas vencidas
        $facturasVencidas = Venda::where([
            ['cliente_id', '=', $cliente->id],
            ['status_factura', '=','por pagar'],
            ['data_vencimento', '<=', date("Y-m-d")],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->sum('valor_total');

        //dividas corrente
        $facturasVencidasCorrente = Venda::where([
            ['cliente_id', '=', $cliente->id],
            ['status_factura', '=','por pagar'],
            ['data_emissao', '<=', date("Y-m-d")],
            ['data_vencimento', '>=', date("Y-m-d")],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->sum('valor_total');

        $head = [
            "titulo" => "Cliente",
            "descricao" => env('APP_NAME'),
            "conta" => $conta,
            "empresa" => $empresa,
            "cliente" => $cliente,
            "facturas" => $facturas,
            "facturasVencidas" => $facturasVencidas,
            "facturasVencidasCorrente" => $facturasVencidasCorrente,
            "valorTotalCompras" => $valorTotalCompras,
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.show', $head);    
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
    
        if(!$user->can('editar cliente')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
    
        $cliente = Cliente::findOrFail($id);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with('clientes')->with("marcas")->with("categorias")->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Cliente",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "cliente" => $cliente,
            "estados_civils" => EstadoCivil::get(),
            "provincias" => Provincia::get(),
            "municipios" => Municipio::get(),
            "distritos" => Distrito::get(),
            "seguradores" => Seguradora::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.edit', $head);    
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
    
       if(!$user->can('editar cliente')){
           Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
           return redirect()->back();
       }
    
        $request->validate([
            'nome' => 'required|string',
            'nif' => 'required|string',
            // 'email' => 'email|string',
        ],[
            'nome.required' => 'O nome é um campo obrigatório',
            'nif.required' => 'O nif é um campo obrigatório',
        ]);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
    
            $clientes = Cliente::findOrFail($id);
            
            if($request->tipo_cliente == "C"){
                $numero_inicial = ENV('CLIENTES_CORRENTES');
            }
            if($request->tipo_cliente == "TR"){
                $numero_inicial = ENV('CLIENTES_TITULOS_A_RECEBER');
            }
            if($request->tipo_cliente == "TD"){
                $numero_inicial = ENV('CLIENTES_TITULOS_DESCONTADOS');
            }
            if($request->tipo_cliente == "CD"){
                $numero_inicial = ENV('CLIENTES_COBRANCA_DUVIDOS');
            }
            if($request->tipo_cliente == "SC"){
                $numero_inicial = ENV('CLIENTES_SALDO_CREDOR');
            }
            
            $code = uniqid(time());
            $nova_conta = "";
            
            $conta = Conta::where('conta', '31')->first();
            
            if($clientes->code == NULL){
                if($conta){
                                        
                    $subc_ = Subconta::where('numero', 'like', $numero_inicial."%")->where('entidade_id', $entidade->empresa->id)->count();
                  
                    $numero =  $subc_ + 1;
                    $nova_conta = "{$numero_inicial}{$numero}";
                    
                    $subconta = Subconta::create([
                        'entidade_id' => $entidade->empresa->id, 
                        'numero' => $nova_conta,
                        'nome' => $request->nome,
                        'tipo_conta' => 'M',
                        'code' => $code,
                        'status' => $conta->status,
                        'conta_id' => $conta->id,
                        'user_id' => Auth::user()->id,
                    ]);
                }else{
                    ######################
                    ## depois vamos dar o tratamento
                }
            }else {
                
                if($request->tipo_cliente == $clientes->tipo_cliente){
                
                    $subc_ = Subconta::where('code', $clientes->code)->where('entidade_id', $entidade->empresa->id)->first();
                    $nova_conta = $clientes->conta;
                    
                    if($subc_){
                        $subc_up = Subconta::findOrFail($subc_->id);
                        $subc_up->numero = $nova_conta;
                        $subc_up->code = $code;
                        $subc_up->nome = $request->nome;
                        $subc_up->update();
                    }
                }else {
                    $subc_ = Subconta::where('numero', 'like', $numero_inicial."%")->where('entidade_id', $entidade->empresa->id)->count();
                  
                    $numero =  $subc_ + 1;
                    $nova_conta = "{$numero_inicial}{$numero}";
                
                    if($subc_){
                        $subc_up = Subconta::findOrFail($subc_->id);
                        $subc_up->numero = $nova_conta;
                        $subc_up->code = $code;
                        $subc_up->nome = $request->nome;
                        $subc_up->update();
                    }
                }
            }
            
            
            
            
            $clientes->nif = $request->nif;
            $clientes->nome = $request->nome;
            $clientes->tipo_cliente = $request->tipo_cliente;
            $clientes->conta = $nova_conta;
            $clientes->code = $code;
            $clientes->pais = $request->pais;
            $clientes->gestor_conta = $request->gestor_conta;
            $clientes->codigo_postal = $request->codigo_postal;
            $clientes->localidade = $request->localidade;
            $clientes->telefone = $request->telefone;
            $clientes->telemovel = $request->telemovel;
            
            $clientes->nome_do_pai = $request->nome_do_pai;
            $clientes->nome_da_mae = $request->nome_da_mae;
            $clientes->data_nascimento = $request->data_nascimento;
            $clientes->genero = $request->genero;
            $clientes->estado_civil_id = $request->estado_civil_id;
            $clientes->seguradora_id = $request->seguradora_id;
            $clientes->provincia_id = $request->provincia_id;
            $clientes->municipio_id = $request->municipio_id;
            $clientes->distrito_id = $request->distrito_id;
            
            $clientes->vencimento = $request->vencimento;
            $clientes->email = $request->email;
            $clientes->website = $request->website;
            $clientes->referencia_externa = $request->referencia_externa;
            $clientes->observacao = $request->observacao;           
   
        
            $clientes->update();
            
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return redirect()->route('clientes.index')->with("success", "Dados Actualizados com Sucesso!");
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
        
        if(!$user->can('eliminar cliente')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $cliente = Cliente::findOrFail($id);
            $cliente->delete();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
       
        return response()->json(['message' => 'Dados Excluido com sucesso!'], 200);
     
    }
    

    public function compras_clientes(Request $request, $id)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        
        $cliente = Cliente::findOrFail($id);
        
        $vendas = Itens_venda::with(['factura', 'produto'])->whereHas('factura', function ($query) use ($cliente) {
            $query->where('cliente_id', $cliente->id);
        })
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('created_at', '>=', Carbon::parse($value));
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('created_at', '<=', Carbon::parse($value));
        })
        ->where('entidade_id', $entidade->empresa->id)
        ->get();
        
        $total_venda = Itens_venda::with(['factura', 'produto'])->whereHas('factura', function ($query) use ($cliente) {
            $query->where('cliente_id', $cliente->id);
        })
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('created_at', '>=', Carbon::parse($value));
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('created_at', '<=', Carbon::parse($value));
        })
        ->where('entidade_id', $entidade->empresa->id)
        ->sum('valor_pagar');
        
        $empresa = Entidade::with(["caixas", "users", "lojas"])->findOrFail($entidade->empresa->id);
        
        $head = [
            "titulo" => "Compras do cliente",
            "descricao" => env('APP_NAME'),
            "vendas" => $vendas,
            "total_venda" => $total_venda,
            "empresa" => $empresa,
            "cliente" => $cliente,
            "entidade" => $entidade,
            "requests" => $request->all('data_inicio', 'data_final','cliente_id'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.compras', $head);
    }    
    
    public function compras_pdf(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
       
        $cliente = Cliente::findOrFail($request->cliente_id);
      
        $vendas = Itens_venda::with(['factura', 'produto'])->whereHas('factura', function ($query) use ($cliente) {
            $query->where('cliente_id', $cliente->id);
        })
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('created_at', '>=', Carbon::parse($value));
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('created_at', '<=', Carbon::parse($value));
        })
        ->where('entidade_id', $entidade->empresa->id)
        ->get();
        
        $total_venda = Itens_venda::with(['factura', 'produto'])->whereHas('factura', function ($query) use ($cliente) {
            $query->where('cliente_id', $cliente->id);
        })
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('created_at', '>=', Carbon::parse($value));
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('created_at', '<=', Carbon::parse($value));
        })
        ->where('entidade_id', $entidade->empresa->id)
        ->sum('valor_pagar');
        
        
        $head = [
            'titulo' => "COMPRAR DO CLIENTE: {$cliente->nome}",
            'descricao' => env('APP_NAME'),
            "vendas" => $vendas,
            "total_venda" => $total_venda,
            "cliente" => $cliente,
            "requests" => $request->all('data_inicio', 'data_final', 'cliente_id'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.clientes.pdf', $head);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream();
        // return $pdf->download('test.pdf');
    }
    
    

}
