<?php

namespace App\Http\Controllers\pdf;

use App\Http\Controllers\Controller;
use App\Models\NotaCredito;
use App\Models\Recibo;
use App\Models\User;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use PDF;

class FacturasInformativoController extends Controller
{
    public function pdfFacturaInformativo($factura = null)
    {

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $facturas = Venda::when($factura, function($query, $value){
            $query->where('factura_next', 'like' ,"%{$value}%");
        })->where([
            ['factura', '=','OT'],
        ])
        ->orWhere('factura', 'EC')
        ->orWhere('factura', 'PF')
        ->with('cliente')
        ->where('entidade_id', '=', $entidade->empresa->id)
        ->orderby('created_at', 'desc')
        ->get();

        $head = [
            'titulo' => "Facturas Informativo",
            'descricao' => env('APP_NAME'),
            "facturas" => $facturas,
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.facturas.documentos.factura-informativo', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function pdfNotaCredito()
    {

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $facturas = NotaCredito::with('cliente')
        ->with('facturas')
        ->where('entidade_id', '=', $entidade->empresa->id)
        ->orderby('created_at', 'desc')
        ->get();

        $head = [
            'titulo' => "Nota Creditos",
            'descricao' => env('APP_NAME'),
            "facturas" => $facturas,
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.facturas.documentos.notas-creditos', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function pdfRecibos()
    {

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $facturas = Recibo::with('cliente')
        ->with('facturas')
        ->where('entidade_id', '=', $entidade->empresa->id)
        ->orderby('created_at', 'desc')
        ->get();
 

        $head = [
            'titulo' => "Recibos",
            'descricao' => env('APP_NAME'),
            "facturas" => $facturas,
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.facturas.documentos.recibos', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }




    
}
