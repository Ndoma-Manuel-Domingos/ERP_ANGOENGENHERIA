@extends('layouts.pdf')

@section('pdf-content')

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
            <th colspan="2" style="text-transform: uppercase">Estado</th>
            <th colspan="2" style="text-transform: uppercase">Cliente</th>
        </tr>

        <tr>
            <th colspan="2">{{ $requests['data_inicio'] ?? 'TODOS' }}</th>
            <th colspan="2">{{ $requests['data_final'] ?? 'TODOS' }}</th>
            <th colspan="2" style="text-transform: uppercase">{{ $requests['status'] ?? 'TODOS' }}</th>
            <th colspan="2">{{ $cliente ? $cliente->nome : 'TODOS' }}</th>
        </tr>
    </thead>
</table>

<table>
    <thead>
        <tr>
            <th style="width: 90px">Nº</th>
            <th style="text-align: left">Cliente</th>
            <th style="text-align: left">Serviço/Produto</th>
            <th style="text-align: right">Hora</th>
            <th style="text-align: right">Data</th>
            <th style="text-align: left">Estado</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($agendas as $item)
        <tr>
            <td>{{ $item->numero }}</td>
            <td>{{ $item->cliente ? $item->cliente->nome : "" }}</td>
            <td>{{ $item->produto ? $item->produto->nome : "" }}</td>
            <td style="text-align: right">{{ $item->hora }}</td>
            <td style="text-align: right">{{ $item->data_at }}</td>
            <td style="text-transform: uppercase">{{ $item->status }}</td>
        </tr>
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <th style="padding: 5px;text-align: left" colspan="5">TOTAL DE REGISTRO: {{ count($agendas) }}</th>
        </tr>
    </tfoot>
</table>

@endsection
