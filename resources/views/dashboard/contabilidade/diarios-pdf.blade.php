@extends('layouts.pdf')

@section('pdf-content')


<style>
    tr, td{
        font-size: 11px;
    }
</style>

<table style="border: 0">
    <tr>
        <td style="border: 0;">
            <img src="images/empresa/{{ $empresa->logotipo }}" style="height: 100px;width: 100px">
        </td>
    </tr>
    
    <tr style="border: 0">
        <td style="border: 0">{{ $empresa->nome }}</td>
    </tr>
    <tr style="border: 0">
        <td style="border: 0"><strong>NIF: </strong>{{ $empresa->nif }}</td>
    </tr>
    <tr style="border: 0">
        <td style="border: 0"><strong>Endereço: </strong>{{ $empresa->morada }}</td>
    </tr>
    <tr style="border: 0">
        <td style="border: 0"><strong>{{ $empresa->cidade }} - {{ $empresa->pais }}</strong></td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th colspan="10" style="text-transform: uppercase"> {{ $titulo }} <span style="float: right">Data: {{ date("Y-m-d") }}</span></th>
        </tr>
    </thead>
</table>

<table>
    <thead>
        <tr>
            <th colspan="2" style="text-transform: uppercase;text-align: right">TOTAL Arrecadado CASH</th>
            <th colspan="2" style="text-transform: uppercase;text-align: right">TOTAL Arrecadado MULTICAIXA</th>
            <th colspan="2" style="text-transform: uppercase;text-align: right">TOTAL Arrecadado DUPLO</th>
            <th colspan="2" style="text-transform: uppercase;text-align: right">Total Arrecadado</th>
        </tr>    
        
        <tr>
            <th colspan="2" style="text-align: right">{{ number_format($total_arrecadado_cash, 2, ',', '.') }} </th>
            <th colspan="2" style="text-align: right">{{ number_format($total_arrecadado_multicaixa, 2, ',', '.') }} </th>
            <th colspan="2" style="text-align: right">{{ number_format($total_arrecadado_duplo, 2, ',', '.') }} </th>
            <th colspan="2" style="text-align: right">{{ number_format(($total_arrecadado_cash + $total_arrecadado_multicaixa + $total_arrecadado_duplo) , 2, ',', '.') }} </th>
        </tr>
    </thead>
</table>

<table>
    <thead>
        <tr>
            <th style="width: 10px">#</th>
            <th>Nº de Registo</th>
            <th>Descrição</th>
            <th>Data</th>
            <th>Forma Pagamento</th>
            <th>Cliente</th>
            <th>Operador</th>
            <th>Caixa</th>
            <th style="text-align: right">Total</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($resultadoUnificado as $contador => $item)
        <tr>
            <td>{{ $contador + 1 }}</td>
            <td>{{ $item->id }}</td>
            <td>{{ $item->factura_next }}</td>
            <td>{{ date('Y-m-d', strtotime($item->created_at)) }} ÁS {{ date('H:i:s', strtotime($item->created_at)) }}</td>
            <td>{{ $item->forma_pagamento($item->pagamento) }}</td>
            <td>{{ $item->cliente->nome }}</td>
            <td>{{ $item->user->name ?? "" }}</td>
            <td>{{ $item->caixa->nome ?? "" }}</td>
            <td style="text-align: right">{{ number_format($item->valor_total, 2, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    
    <tfoot>
        <tr>
            <th colspan="9" style="padding: 5px;text-align: left">TOTAL DE REGISTRO: {{ count($resultadoUnificado) }}</th>
        </tr>
    </tfoot>
</table>


@endsection