<?php

namespace App\Http\Controllers\pdf;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class FacturasFacturacaoController extends Controller
{
    public function pdfFacturaFacturacao(Request $request)
    {
        if($request->factura != ""){

            $facturas = Venda::where([
                ['factura', '=','FR'],
                ['factura_next', 'like' ,"%{$request->factura}%"],
            ])
            ->orWhere('factura', 'FT')
            ->orWhere('factura', 'FG')
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();

        }else{
            $facturas = Venda::where([
                ['factura', '=','FR'],
            ])
            ->orWhere('factura', 'FT')
            ->orWhere('factura', 'FG')
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();            
        }

        $head = [
            'titulo' => "Facturas com Facturação",
            'descricao' => env('APP_NAME'),
            "facturas" => $facturas,
            "loja" => User::with('empresa')->findOrFail(Auth::user()->id),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.facturas.documentos.factura-facturacao', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }
}
