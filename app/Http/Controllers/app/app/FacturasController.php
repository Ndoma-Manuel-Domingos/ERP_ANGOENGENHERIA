<?php

namespace App\Http\Controllers\app\app;

use App\Http\Controllers\Controller;
use App\Models\Caixa;
use App\Models\Cliente;
use App\Models\Itens_venda;
use App\Models\Loja;
use App\Models\Produto;
use App\Models\User;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;


use PDF;

class FacturasController extends Controller
{
    public function facturaSemPagamentos(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        if($request->tipo_documento == "todas" && $request->factura == ""){
            $facturas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();

             // dividas vencidas
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


        }else if($request->tipo_documento == "todas" && $request->factura != ""){
            $facturas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['factura_next', 'like' ,"%{$request->factura}%"],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();

             // dividas vencidas
             $facturasVencidas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_vencimento', '<', date("Y-m-d")],
                ['factura_next', 'like' ,"%{$request->factura}%"],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->sum('valor_total');

            //dividas corrente
            $facturasVencidasCorrente = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_emissao', '<', date("Y-m-d")],
                ['data_vencimento', '>', date("Y-m-d")],
                ['factura_next', 'like' ,"%{$request->factura}%"],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->sum('valor_total');



        }else if($request->tipo_documento == "dividas_corrente" && $request->factura != ""){
            $facturas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_emissao', '<', date("Y-m-d")],
                ['data_vencimento', '>', date("Y-m-d")],
                ['factura_next', 'like' ,"%{$request->factura}%"],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();

            // dividas vencidas
            $facturasVencidas = 0;
            //dividas corrente
            $facturasVencidasCorrente = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_emissao', '<', date("Y-m-d")],
                ['data_vencimento', '>', date("Y-m-d")],
                ['factura_next', 'like' ,"%{$request->factura}%"],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->sum('valor_total');


        }else if($request->tipo_documento == "dividas_corrente" && $request->factura == ""){
            $facturas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_emissao', '<', date("Y-m-d")],
                ['data_vencimento', '>', date("Y-m-d")],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();

            // dividas vencidas
            $facturasVencidas = 0;

            //dividas corrente
            $facturasVencidasCorrente = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_emissao', '<', date("Y-m-d")],
                ['data_vencimento', '>', date("Y-m-d")],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->sum('valor_total');


        }else if($request->tipo_documento == "dividas_vencidas" && $request->factura != ""){
            $facturas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_vencimento', '<', date("Y-m-d")],
                ['factura_next', 'like' ,"%{$request->factura}%"],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();

            // dividas vencidas
            $facturasVencidas = Venda::where([
                ['status_factura', '=','por pagar'],['data_vencimento', '<', date("Y-m-d")],
                ['factura_next', 'like' ,"%{$request->factura}%"],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->sum('valor_total');

            //dividas corrente
            $facturasVencidasCorrente = 0;


        }else if($request->tipo_documento == "dividas_vencidas" && $request->factura == ""){
            $facturas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_vencimento', '<', date("Y-m-d")],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();

            // dividas vencidas
            $facturasVencidas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_vencimento', '<', date("Y-m-d")],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->sum('valor_total');

            //dividas corrente
            $facturasVencidasCorrente = 0;

        }else{
            ####################### PADRÃO

            $facturas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();        
            
            // dividas vencidas
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
        }

        $head = [
            "titulo" => "Facturas sem Pagamentos",
            "descricao" => env('APP_NAME'),
            "caixa" => Caixa::where([
                ['active', true]
            ])->first(),
            "facturas" => $facturas,
            "facturasVencidas" => $facturasVencidas,
            "facturasVencidasCorrente" => $facturasVencidasCorrente,
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.pronto-venda.facturas.sem_pagamentos', $head);
    }

    public function facturaFacturacao(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        if($request->factura != ""){
            
            $facturas = Venda::where([
                ['factura', '=','FR'],
                ['factura_next', 'like' ,"%{$request->factura}%"],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->orWhere('factura', 'FT')
            ->orWhere('factura', 'FG')
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();

        }else{
            $facturas = Venda::where([
                ['factura', '=','FR'],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->orWhere('factura', 'FT')
            ->orWhere('factura', 'FG')
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();            
        }

        $head = [
            "titulo" => "Facturação",
            "descricao" => env('APP_NAME'),
            "caixa" => Caixa::where([
                ['active', true],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->first(),
            "facturas" => $facturas,
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.pronto-venda.facturas.facturacao', $head);
    }

    public function facturaInformativo(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        if($request->factura != ""){
            
            $facturas = Venda::where([
                ['factura', '=','OT'],
                ['entidade_id', '=', $entidade->empresa->id], 
                ['factura_next', 'like' ,"%{$request->factura}%"],
            ])
            ->orWhere('factura', 'EC')
            ->orWhere('factura', 'PF')
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();

        }else{
            ####################### PADRÃO
            $facturas = Venda::where([
                ['factura', '=','OT'],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->orWhere('factura', 'EC')
            ->orWhere('factura', 'PF')
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();
        }

        $head = [
            "titulo" => "Facturas Informativo",
            "descricao" => env('APP_NAME'),
            "caixa" => Caixa::where([
                ['active', true],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->first(),
            "facturas" => $facturas,
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.pronto-venda.facturas.informativos', $head);
    }

    public function facturaTodas(Request $request)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        if($request->factura != ""){
            
            $facturas = Venda::where([
                ['entidade_id', '=', $entidade->empresa->id], 
                ['factura_next', 'like' ,"%{$request->factura}%"],
            ])
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();

        }else{
            ####################### PADRÃO
            $facturas = Venda::where([
                ['entidade_id', '=', $entidade->empresa->id], 
            ])
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();
        }

        $head = [
            "titulo" => "Todas Facturas",
            "descricao" => env('APP_NAME'),
            "caixa" => Caixa::where([
                ['active', true],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->first(),
            "facturas" => $facturas,
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.pronto-venda.facturas.todas', $head);
    }

    public function facturaOperacaoes()
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $head = [
            "titulo" => "Todas Facturas",
            "descricao" => env('APP_NAME'),
            "caixa" => Caixa::where([
                ['active', true],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->first(),
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.pronto-venda.facturas.operacoes', $head);
    }


    public function facturaTrocarItens()
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $head = [
            "titulo" => "Todas Facturas",
            "descricao" => env('APP_NAME'),
            "caixa" => Caixa::where([
                ['active', true],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->first(),
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->get(),
            "facturas" => Venda::where([
                ['code', '!=', NULL],
                ['status_venda', '=', 'realizado'],
            ])->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.pronto-venda.facturas.trocar-itens', $head);
    }


    public function facturaDevolucaoItens()
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $head = [
            "titulo" => "Todas Facturas",
            "descricao" => env('APP_NAME'),
            "caixa" => Caixa::where([
                ['active', true],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->first(),
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.pronto-venda.facturas.devolucao', $head);
    }


    public function facturaAnulacao()
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $head = [
            "titulo" => "Todas Facturas",
            "descricao" => env('APP_NAME'),
            "caixa" => Caixa::where([
                ['active', true],
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->first(),
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "lojas" => Loja::where([
                ['entidade_id', '=', $entidade->empresa->id], 
            ])->get(),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.pronto-venda.facturas.anulacao', $head);
    }

    public function facturaBuscar(Request $request)
    {
        $fact = Venda::findOrFail($request->factura);

        $movimentos = NULL;

        if($fact){
            $movimentos = Itens_venda::where([
                ['code', '=', $fact->code],
            ])->with('produto')->get();

            $totalPagar = Itens_venda::where([
                ['code', '=', $fact->code],
            ])->sum('valor_pagar');
        }else{
            Alert::warning("Atenção", "Nenhuma factura encontrada com Nome ou Referência: {$request->factura}");
            return redirect()->route('pronto-venda.facturas-trocarItens');
        }
        
        return response()->json([
            'status' => 200,
            'message' => 'Dados salvos com sucesso!',
            "movimentos" => $movimentos, 
            "totalPagar" => $totalPagar, 
        ]);

    }

    public function facturaTrocarItensCreate(Request $request)
    {
        dd($request->all());
    }

    public function facturaVisualizar($id)
    {
        $factura = Venda::with('cliente', 'caixa.loja', 'user')->findOrFail($id);
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $movimentos = Itens_venda::where([
            ['code','=', $factura->code],
            ['user_id','=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])->with('produto')->get();

        $total_pagar = Itens_venda::where([
            ['code','=', $factura->code],
            ['user_id','=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])->sum('valor_pagar');

        $empresa = User::with('empresa')->findOrFail(Auth::user()->id);

        $var_iva = "";
        if($empresa->empresa->taxa_iva == "ISE"){
            $var_iva = 0;
        }else if ($empresa->empresa->taxa_iva == "RED"){
            $var_iva = 2;
        }else if ($empresa->empresa->taxa_iva == "INT"){
            $var_iva = 5;
        }else if ($empresa->empresa->taxa_iva == "OUT"){
            $var_iva = 7;
        }else if ($empresa->empresa->taxa_iva == "NOR"){
            $var_iva = 14;
        }else{
            $var_iva = 0;
        }

        if($var_iva != 0){
            $valorBase = ($factura->valor_total) - (($factura->valor_total) * ($var_iva / 100));
            $valorIva = ($factura->valor_total) * ($var_iva / 100);    
        }else{
            $valorBase = $factura->valor_total;
            $valorIva = 0;    
        }

        $head = [
            "titulo" => "Factura {$factura->factura_next}",
            "descricao" => env('APP_NAME'),
            "factura" => $factura,
            "movimentos" => $movimentos,
            "total_pagar" => $total_pagar,
            "caixa" => Caixa::where([
                ['active', true],
                ['entidade_id', '=', $entidade->empresa->id],
            ])->first(),
            "clientes" => Cliente::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "produtos" => Produto::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "loja" => $empresa,
            "taxta" => $var_iva,
            "valorBase" => $valorBase,
            "valorIva" => $valorIva,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.pronto-venda.facturas.visualizar', $head);
    }

    public function facturaDocumento($id, $visualizar = null)
    {
        $factura = Venda::with('cliente', 'caixa.loja', 'user')->findOrFail($id);
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        $movimentos = Itens_venda::where([
            ['code','=', $factura->code],
            ['user_id','=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])->with('produto')->get();

        $total_pagar = Itens_venda::where([
            ['code','=', $factura->code],
            ['user_id','=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])->sum('valor_pagar');

        $empresa = User::with('empresa')->findOrFail(Auth::user()->id);

        $var_iva = "";
        if($empresa->empresa->taxa_iva == "ISE"){
            $var_iva = 0;
        }else if ($empresa->empresa->taxa_iva == "RED"){
            $var_iva = 2;
        }else if ($empresa->empresa->taxa_iva == "INT"){
            $var_iva = 5;
        }else if ($empresa->empresa->taxa_iva == "OUT"){
            $var_iva = 7;
        }else if ($empresa->empresa->taxa_iva == "NOR"){
            $var_iva = 14;
        }else{
            $var_iva = 0;
        }

        if($var_iva != 0){
            $valorBase = ($factura->valor_total) - (($factura->valor_total) * ($var_iva / 100));
            $valorIva = ($factura->valor_total) * ($var_iva / 100);    
        }else{
            $valorBase = $factura->valor_total;
            $valorIva = 0;    
        }

        $head = [
            "titulo" => "Factura {$factura->factura_next}",
            "descricao" => env('APP_NAME'),
            "factura" => $factura,
            "movimentos" => $movimentos,
            "total_pagar" => $total_pagar,
            "caixa" => Caixa::where([
                ['active', true],
                ['entidade_id', '=', $entidade->empresa->id],
            ])->first(),
            "clientes" => Cliente::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "produtos" => Produto::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "loja" => $empresa,
            "taxta" => $var_iva,
            "valorBase" => $valorBase,
            "valorIva" => $valorIva,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        if($visualizar == "sem-download"){
            $pdf = PDF::loadView('dashboard.pronto-venda.facturas.documento-pdf', $head);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
        }else if($visualizar == "download"){
            $pdf = PDF::loadView('dashboard.pronto-venda.facturas.documento-pdf', $head);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->download("{$factura->factura_next}.pdf");
        }

    }
    

}
