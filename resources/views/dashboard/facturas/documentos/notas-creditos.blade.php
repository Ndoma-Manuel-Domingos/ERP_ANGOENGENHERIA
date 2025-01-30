
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

<table class="table" style="margin: 20px 0">
    <thead>
        <tr>
            <th>Factura</th>
            <th>Referente</th>
            <th>Cliente</th>
            <th>Data</th>
            <th>Vencimento</th>
            <th style="text-align: right">Dívida</th>
        </tr>
    </thead>
    <tbody>
        @if ($facturas)
        @foreach ($facturas as $item)
        <tr>
            <td>{{ $item->factura_next}}</td>
            <td>{{ $item->facturas->factura_next}}</td>
            <td>{{ $item->cliente->nome }}</td>
            <td>{{ $item->data_emissao }}</td>
            <td>{{ $item->data_vencimento }}</td>
            <td style="text-align: right">{{ number_format($item->valor_total, 2, ',', '.') }} {{ $loja->empresa->moeda ?? 'KZ' }}</td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>


@endsection
