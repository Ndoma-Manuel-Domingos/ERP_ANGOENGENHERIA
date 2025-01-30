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
            <th colspan="10" style="text-transform: uppercase"> {{ $titulo }}</th>
        </tr>
    </thead>
</table>

<table>
    <thead>
        <tr>
            <th colspan="2" style="text-transform: uppercase">Data Inicio</th>
            <th colspan="2" style="text-transform: uppercase">Data Final</th>
        </tr>

        <tr>
            <th colspan="2">{{ $requests['data_inicio'] ?? 'TODOS' }}</th>
            <th colspan="2">{{ $requests['data_final'] ?? 'TODOS' }}</th>
        </tr>
    </thead>
</table>


<section>
    <table class="table table-hover text-nowrap">
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Quantidade</th>
                <th style="text-align: right">Total Pago</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($registros as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->produto->nome ?? "" }}</td>
                <td>{{ $item->quantidade ?? "" }}</td>
                <td style="text-align: right">{{ number_format($item->valor_pago ?? 0, 2, ',', '.') }}</td>

            </tr>
            @endforeach

        </tbody>
    </table>
</section>

@endsection