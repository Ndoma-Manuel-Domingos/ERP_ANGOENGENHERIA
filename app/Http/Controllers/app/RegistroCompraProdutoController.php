<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Entidade;
use App\Models\Loja;
use App\Models\ProdutoCompra;
use App\Models\RegistroCompraProduto;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use PDF;

class RegistroCompraProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if(!$user->can('listar produtos')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->data_inicio = $request->data_inicio ?? date("Y-m-d");
        $request->data_final = $request->data_final ?? date("Y-m-d");

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $registros = RegistroCompraProduto::with(['produto', 'user', 'entidade'])->where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('created_at', '>=', Carbon::createFromDate($value));
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('created_at', '<=', Carbon::createFromDate($value));
        })
        ->orderBy('created_at', 'desc')
        ->get();

        $head = [
            "titulo" => "Registro compras",
            "descricao" => env('APP_NAME'),
            "registros" => $registros,
            "requests" => $request->all('data_inicio', 'data_final'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.registros-compras-produtos.index', $head);
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
        
        if(!$user->can('criar produtos')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $produtos = ProdutoCompra::where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])
        ->get();
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
              
        $data_inicio = $data_inicio ?? date("Y-m-d");
        $data_final = $data_final ?? date("Y-m-d");
        
        $registros = RegistroCompraProduto::with(['produto', 'user', 'entidade'])->where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])
        ->when($data_inicio, function ($query, $value) {
            $query->whereDate('created_at', '>=', Carbon::createFromDate($value));
        })
        ->when($data_final, function ($query, $value) {
            $query->whereDate('created_at', '<=', Carbon::createFromDate($value));
        })
        ->orderBy('created_at', 'desc')
        ->get();
        
        $head = [
            "titulo" => "Registro de Compras de Produtos",
            "descricao" => env('APP_NAME'),
            "produtos" => $produtos,
            "registros" => $registros,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.registros-compras-produtos.create', $head);
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
        
        if(!$user->can('criar produtos')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'produto_id' => 'required|string',
        ],[
            'produto_id.required' => 'O produto é um campo obrigatório',
        ]);
        
        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
    
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            $categoria = RegistroCompraProduto::create([
                'entidade_id' => $entidade->empresa->id, 
                'quantidade' => $request->quantidade,
                'produto_id' => $request->produto_id,
                'valor_pago' => $request->valor_pago,
                'user_id' => Auth::user()->id,
            ]);
    
            $categoria->save();
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
        
        if(!$user->can('listar produtos')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $periodo = RegistroCompraProduto::findOrFail($id);
        
        $head = [
            "titulo" => "Detalhe Registro",
            "descricao" => env('APP_NAME'),
            "periodo" => $periodo,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.registros-compras-produtos.show', $head);

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
        
        if(!$user->can('editar produtos')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $produto = RegistroCompraProduto::findOrFail($id);
        
        $produtos = ProdutoCompra::where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])
        ->get();

        
        $head = [
            "titulo" => "Editar Registro compras",
            "descricao" => env('APP_NAME'),
            "produtos" => $produtos,
            "produto" => $produto,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.registros-compras-produtos.edit', $head);
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
        
        if(!$user->can('editar produtos')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'produto_id' => 'required|string',
        ],[
            'produto_id.required' => 'O produto é um campo obrigatório',
        ]);
        
        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $periodo = RegistroCompraProduto::findOrFail($id);
            $periodo->update($request->all());
    
            $periodo->update();
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
        
        if(!$user->can('eliminar produtos')){
            Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $periodo = RegistroCompraProduto::findOrFail($id);
            $periodo->delete();
            
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        
        return redirect()->back()->with("success", "Dados Excluído com Sucesso!");
    
    }

    public function imprimir(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $request->data_inicio = $request->data_inicio ?? date("Y-m-d");
        $request->data_final = $request->data_final ?? date("Y-m-d");

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $registros = RegistroCompraProduto::with(['produto', 'user', 'entidade'])->where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('created_at', '>=', Carbon::createFromDate($value));
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('created_at', '<=', Carbon::createFromDate($value));
        })
        
        ->orderBy('created_at', 'desc')
        ->get();
        
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);
        
        $head = [
            'titulo' => "LISTA DE REGISTRO DE COMPRAS DIÁRIO",
            'descricao' => env('APP_NAME'),
            'registros' => $registros,
            "empresa" => $empresa,
            "requests" => $request->all('data_inicio', 'data_final'),
            "lojas" => Loja::where('entidade_id', $entidade->empresa->id)->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];
        

        $pdf = PDF::loadView('dashboard.registros-compras-produtos.pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
        // return $pdf->download('test.pdf');
    }


}
