@extends('layouts.pdf')

@section('pdf-content')

<header>
    <table style="border: 0">
        <tr>
            <td style="border: 0;">
                <img src= "images/empresa/{{ $empresa->logotipo }}" style="height: 100px;width: 100px">
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
</header>

<table>
    <thead>
        <tr>
            <th colspan="11" style="text-transform: uppercase"> {{ $titulo }}</th>
        </tr>
        <tr>
            <th colspan="3">Cliente</th>
            <th colspan="2">{{ $cliente ? $cliente->nome : "TODOS" }}</th>
            <th colspan="3">Quarto</th>
            <th colspan="3">{{ $quarto ? $quarto->nome : "TODAS" }}</th>
        </tr>
        <tr>
            <th colspan="3">Estado Pagamento</th>
            <th colspan="2">{{ $requests['status_pagamento'] ?? "TODOS" }}</th>
            <th colspan="3">Estado Reserva</th>
            <th colspan="3">{{ $requests['status_reserva'] ?? "TODAS" }}</th>
        </tr>
        <tr>
            <th colspan="3">Data de Entrada</th>
            <th colspan="2">{{ $requests['data_inicio'] ?? "TODAS" }}</th>
            <th colspan="3">Data de Saída</th>
            <th colspan="3">{{ $requests['data_final'] ?? "TODAS" }}</th>
        </tr>
  
        <tr>
            <th colspan="3">Hora Entrada</th>
            <th colspan="2">{{ $requests['hora_entrada'] ?? "TODAS" }}</th>
            <th colspan="3">Hora Saída</th>
            <th colspan="3">{{ $requests['hora_saida'] ?? "TODAS" }}</th>
        </tr>
    </thead>
</table>

<table>
    <thead>
        <tr>
            <th rowspan="2">#</th>
            <th rowspan="2">Nome Completo</th>
            <th rowspan="2">Quarto</th>
        
            <th class="text-center" colspan="2">Previsão Entrada/Saída</th>
            <th class="text-center" colspan="2">Check IN/OUT</th>
            <th rowspan="2">Estado</th>

            <th rowspan="2">Dias</th>
            <th rowspan="2">Pagamento</th>
            <th rowspan="2">Total Factura</th>
        </tr>
        
        <tr>
            <th class="text-center">Data/Hora</th>
            <th class="text-center">Data/Hora</th>
            
            <th class="text-center">Data/Hora</th>
            <th class="text-center">Data/Hora</th>
        </tr>
        
    </thead>
    <tbody>
        @foreach ($reservas as $item)
            <tr style="background-color: {{ $item->status == 'CANCELADO' ? 'rgba(138, 39, 39, .3)' : '' }}">
                <td>{{ $item->id }}</td>
                <td>{{ $item->cliente->nome }}</td>
                <td>{{ $item->quarto->nome }}</td>
         
                <td>{{ $item->data_inicio }} - {{ $item->hora_entrada }}</td>
                <td>{{ $item->data_final }} - {{ $item->hora_saida }}</td>
                
                <td>{{ $item->data_check_in }} - {{ $item->hora_check_in }}</td>
                <td>{{ $item->data_check_out }} - {{ $item->hora_check_out }}</td>
                <td>{{ $item->status }}</td>
     
                <td>{{ $item->total_dias }}</td>
                @if ($item->pagamento == "EFECTUADO")
                <td class="text-success">{{ $item->pagamento }}</td>
                @endif
                @if ($item->pagamento == "NAO EFECTUADO")
                <td class="text-danger">{{ $item->pagamento }}</td>
                @endif
                <td>{{ number_format($item->valor_total ??0, 2, ',', '.')  }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="11" style="text-transform: uppercase;padding: 10px;text-align: left"> TOTAL REGISTRO: {{ count($reservas) }}</th>
        </tr>
    </tbody>
</table>
@endsection