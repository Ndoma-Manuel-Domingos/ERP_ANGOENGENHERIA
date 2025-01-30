<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\ContaCliente;
use App\Models\Entidade;
use App\Models\Loja;
use App\Models\MovimentoContaCliente;
use App\Models\User;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ContaClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $movimentos = ContaCliente::where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->with('cliente')
        ->get();
        
        $facturasVencidas = Venda::where([
            ['status_factura', '=','por pagar'],
            ['data_vencimento', '<', date("Y-m-d")],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->sum('valor_total');

        //dividas corrente
        $facturasVencidasCorrente = Venda::where([
            ['status_factura', '=','por pagar'],
            ['data_emissao', '<', date("Y-m-d")],
            ['data_vencimento', '>', date("Y-m-d")],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->sum('valor_total');
        
        
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id);
        $head = [
            "titulo" => "Regularização Conta Corrente",
            "descricao" => env('APP_NAME'),
            "facturasVencidas" => $facturasVencidas,
            "facturasVencidasCorrente" => $facturasVencidasCorrente,
            "empresa" => $empresa,
            "movimentos" => $movimentos,
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.contas.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id); 

        $head = [
            "titulo" => "Regularização Conta Corrente",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.contas.create', $head);
    }

    public function movimentosConta($id)
    {
        
        $cliente = Cliente::findOrFail($id);
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

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

        $movimentos = MovimentoContaCliente::where([
            ['cliente_id', '=', $cliente->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->get();
        
        $empresa = User::with("empresa")->findOrFail(Auth::user()->id);
        $head = [
            "titulo" => "Regularização Conta Corrente",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "cliente" => $cliente,
            "movimentos" => $movimentos,
            "facturas" => $facturas,
            "valorTotalCompras" => $valorTotalCompras,
            "facturasVencidas" => $facturasVencidas,
            "facturasVencidasCorrente" => $facturasVencidasCorrente,
            "conta" => $conta,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.contas.movimentos', $head);
    }

    public function actualizarConta($id)
    {
        $cliente = Cliente::findOrFail($id);
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id); 

        $head = [
            "titulo" => "Regularização Conta Corrente",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "clienteSaldo" => $cliente,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.contas.create', $head);
    }

    
    public function liquidarfactura($id)
    {
        $cliente = Cliente::findOrFail($id);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $conta = ContaCliente::where([
            ['entidade_id', '=', $entidade->empresa->id],
            ['cliente_id', '=', $cliente->id]
        ])->first();
        
        $facturas = Venda::where([
            ['status_factura', '=','por pagar'],
            ['cliente_id', '=',$cliente->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->with('cliente')
        ->orderby('created_at', 'desc')
        ->get();  

        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id); 

        $head = [
            "titulo" => "Regularização Conta Corrente",
            "descricao" => env('APP_NAME'),
            "loja" => $empresa,
            "cliente" => $cliente,
            "facturas" => $facturas,
            "conta" => $conta,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.contas.facturas', $head);
    }
    
    public function extratoConta(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $facturas = Venda::where([
            ['cliente_id', '=', $cliente->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
        ->with('cliente')
        ->orderby('created_at', 'desc')
        ->get();  

        $empresa = Entidade::with("variacoes")->with("categorias")->with("marcas")->findOrFail($entidade->empresa->id); 

        $head = [
            "titulo" => "Extrato de conta do Cliente",
            "descricao" => env('APP_NAME'),
            "loja" => $empresa,
            "cliente" => $cliente,
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            'requests' => $request->all('data_inicio', 'data_final', 'loja_id', 'tipo_documento'),
            "facturas" => $facturas,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.contas.extrato', $head);
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
            'observacao' => 'required',
            'montante' => 'required',
            'tipo_movimento' => 'required',
        ],[
            'observacao.required' => 'Observação é um campo obrigatório',
            'montante.required' => 'O motante é um campo obrigatório',
            'tipo_movimento.required' => 'O tipo de movimento é um campo obrigatório',
        ]);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $conta = ContaCliente::where([
            ['entidade_id', '=', $entidade->empresa->id], 
            ['cliente_id', '=', $request->cliente_id],
        ])->first();

        if($conta){

            $create = MovimentoContaCliente::create([
                'user_id' => Auth::user()->id,
                'observacao' => $request->observacao,
                'montante' => $request->montante,
                'cliente_id' => $request->cliente_id,
                'data_emissao' => date("Y-m-d"),
                'tipo_movimento' => $request->tipo_movimento,
                'entidade_id' => $entidade->empresa->id,
            ]);  

            if($create->save()){

                if($request->tipo_movimento == "-1"){
                    $actualizarConta = ContaCliente::findOrFail($conta->cliente_id);
                    $actualizarConta->saldo = $actualizarConta->saldo - $request->montante;
                    $actualizarConta->update();
                }else{
                    $actualizarConta = ContaCliente::findOrFail($conta->cliente_id);
                    $actualizarConta->saldo = $actualizarConta->saldo + $request->montante;
                    $actualizarConta->update();
                }

                Alert::success('Successo', 'Regularização Conta Corrente!');
                return redirect()->route('conta-clientes.index');
            }else{
                Alert::warning('Atenção', 'Não foi possível Regularizar Conta Corrente!');
                return redirect()->route('conta-clientes.create');
            }            
            
        }else{

            if($request->tipo_movimento == "-1"){
                $saldo = (0 - $request->montante);
            }else{
                $saldo = (0 + $request->montante);
            }

            $create = MovimentoContaCliente::create([
                'user_id' => Auth::user()->id,
                'observacao' => $request->observacao,
                'montante' => $request->montante,
                'cliente_id' => $request->cliente_id,
                'data_emissao' => date("Y-m-d"),
                'tipo_movimento' => $request->tipo_movimento,
                'entidade_id' => $entidade->empresa->id,
            ]);  

            $create->save();

            $create2 = ContaCliente::create([
                'user_id' => Auth::user()->id,
                'divida_corrente' => 0,
                'divida_vencida' => 0,
                'saldo' => $saldo,
                'cliente_id' => $request->cliente_id,
                'entidade_id' => $entidade->empresa->id,
            ]); 

            $create2->save();
        }

        Alert::success('Sucesso', 'Regularizar Conta Corrente!');
        return redirect()->route('clientes-movimentos-conta', $request->cliente_id);
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
