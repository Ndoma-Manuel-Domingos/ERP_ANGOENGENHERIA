@extends('layouts.pdf')

@section('pdf-content')

<table style="border: 0">
    <tr>
        <td style="border: 0;">
            <img src="images/empresa/{{ $tipo_entidade_logado->empresa->logotipo }}" style="height: 100px;width: 100px">
        </td>
    </tr>
    <tr style="border: 0">
        <td style="border: 0">{{ $tipo_entidade_logado->empresa->nome }}</td>
    </tr>
    <tr style="border: 0">
        <td style="border: 0"><strong>NIF: </strong>{{ $tipo_entidade_logado->empresa->nif }}</td>
    </tr>
    <tr style="border: 0">
        <td style="border: 0"><strong>Endereço: </strong>{{ $tipo_entidade_logado->empresa->morada }}</td>
    </tr>
    <tr style="border: 0">
        <td style="border: 0"><strong>{{ $tipo_entidade_logado->empresa->cidade }} - {{ $tipo_entidade_logado->empresa->pais }}</strong></td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th colspan="10" style="text-transform: uppercase"> {{ $titulo }}</th>
        </tr>
    </thead>
</table>


<table>
    <thead>
        <tr>
            <th colspan="2" style="text-transform: uppercase">Data Inicio</th>
            <th colspan="2" style="text-transform: uppercase">Data Final</th>
            <th colspan="2" style="text-transform: uppercase">Estado</th>
            <th colspan="2" style="text-transform: uppercase">Tipo Movimento</th>
        </tr>

        <tr>
            <th colspan="2">{{ $requests['data_inicio'] ?? 'TODOS' }}</th>
            <th colspan="2">{{ $requests['data_final'] ?? 'TODOS' }}</th>
            <th colspan="2">{{ $requests['status'] ?? 'TODOS' }}</th>
            <th colspan="2">{{ $requests['tipo_movimento'] ?? 'TODOS' }}</th>
        </tr>
    </thead>
</table>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Referência</th>
            <th>Estado</th>
            <th>Dispesa/Receita</th>
            <th>Forne./Clie.</th>
            <th style="text-align: right">Data</th>
            <th>Pag./Rec.</th>
            <th style="text-align: right">Motante</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalReceita = 0;
            $totalDespesa = 0;
            $totalSaldo = 0;
        @endphp
        @foreach ($operacoes as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->nome }}</td>
            <td>{{ $item->status }}</td>
            <td>{{ $item->type == "D" ? $item->dispesa->nome : $item->receita->nome }}</td>
            <td>{{ $item->type == "D" ? $item->fornecedor->nome : $item->cliente->nome }}</td>
            <td style="text-align: right">{{ $item->date_at }}</td>
            <td>{{ $item->type == "D" ? $item->forma_pagamento->titulo : $item->forma_recebimento->titulo }}</td>
            @if ($item->type == "D")
                @php
                    $totalDespesa += $item->motante;
                @endphp
            <td style="color: red;text-align: right">-{{ number_format($item->motante, 2, ',', '.')  }}</td>
            @else
                @php
                    $totalReceita += $item->motante;
                @endphp
            <td style="color: green;text-align: right">+{{ number_format($item->motante, 2, ',', '.')  }}</td>
            @endif
        </tr>
        @endforeach
        <tr>
            <td colspan="7" style="color: white;text-align: right;background-color: green"><strong>TOTAL RECEITAS</strong></td>
            <td colspan="1" style="color: white;text-align: right;background-color: green"><strong>{{ number_format($totalReceita, 2, ',', '.') }}</strong></td>
        </tr>
        <tr>
            <td colspan="7" style="color: white;text-align: right;background-color: red"><strong>TOTAL DESPESAS</strong></td>
            <td colspan="1" style="color: white;text-align: right;background-color: red"><strong>{{ number_format($totalDespesa, 2, ',', '.') }}</strong></td>
        </tr>
        @php $totalSaldo = $totalReceita - $totalDespesa ; @endphp
        <tr>
            <td colspan="7" style="color: white;text-align: right;background-color: #2c2c2c"><strong>SALDO FINAL</strong></td>
            <td colspan="1" style="color: white;text-align: right;background-color: #2c2c2c"><strong>{{ number_format($totalSaldo, 2, ',', '.') }}</strong></td>
        </tr>
    </tbody>
    
    <tfoot>
    </tfoot>
</table>

@endsection
