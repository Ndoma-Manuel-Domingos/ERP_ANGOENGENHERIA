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
            <th colspan="10" style="text-transform: uppercase"> {{ $titulo }}</th>
        </tr>
    </thead>
</table>


<table>
    <thead>
        <tr>
            <th colspan="2" style="text-transform: uppercase">Data Inicio</th>
            <th colspan="2" style="text-transform: uppercase">Data Final</th>
            <th colspan="2" style="text-transform: uppercase">Operador</th>
            <th colspan="2" style="text-transform: uppercase">Caixa</th>
        </tr>

        <tr>
            <th colspan="2">{{ $requests['data_inicio'] ?? 'TODOS' }}</th>
            <th colspan="2">{{ $requests['data_final'] ?? 'TODOS' }}</th>
            <th colspan="2">{{ $user ? $user->name : 'TODOS' }}</th>
            <th colspan="2">{{ $caixa ? $caixa->nome : 'TODOS' }}</th>
        </tr>
    </thead>
</table>

<table>
    <thead>
        <tr>
            <th style="width: 90px">Factura</th>
            <th style="text-align: right">V.Entregue</th>
            <th style="text-align: right">Qtd</th>
            <th style="text-align: right">Troco</th>
            <th style="text-align: right">Desc.</th>
            <th style="text-align: left">Pagamento</th>
            <th>Data</th>
            <th style="text-align: left">Operador</th>
            <th style="text-align: left">Cliente</th>
            <th style="text-align: right">Total</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($vendas as $item)
        <tr>
            {{-- <td>{{ $item->id }}</td> --}}
            <td>{{ $item->factura_next }}</td>
            <td style="text-align: right">{{ number_format($item->valor_entregue, 2, ',', '.') }}</td>
            <td style="text-align: right">{{ number_format($item->quantidade, 2, ',', '.') }}</td>
            <td style="text-align: right">{{ number_format($item->valor_troco, 2, ',', '.') }}</td>
            <td style="text-align: right">{{ number_format($item->desconto, 2, ',', '.') }}</td>
            <td style="text-align: left">{{ $item->forma_pagamento($item->pagamento) }}</td>
            <td>{{ date('Y-m-d', strtotime($item->created_at)) }}</td>
            <td style="text-align: left">{{ $item->user->name ?? "" }}</td>
            <td style="text-align: left">{{ $item->cliente->nome }}</td>
            <td style="text-align: right">{{ number_format($item->valor_total, 2, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <th style="padding: 5px;text-align: left" colspan="9">TOTAL DE REGISTRO: {{ count($vendas ?? 0) }}</th>
            <th style="padding: 5px;text-align: right">{{ number_format($total_venda ?? 0, 2, ',', '.') }}</th>
        </tr>
    </tfoot>
</table>

{{-- <footer>
    <p>Lorem ipsum dolor sit amet.</p>
</footer> --}}
@endsection
