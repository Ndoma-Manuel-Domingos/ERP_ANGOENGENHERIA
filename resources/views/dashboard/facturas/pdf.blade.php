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
            <th colspan="2" style="text-transform: uppercase">Loja</th>
            {{-- <th colspan="2" style="text-transform: uppercase">Caixa</th> --}}
        </tr>

        <tr>
            <th colspan="2">{{ $requests['data_inicio'] ?? 'TODOS' }}</th>
            <th colspan="2">{{ $requests['data_final'] ?? 'TODOS' }}</th>
            <th colspan="2">{{ $loja ? $loja->nome : 'TODOS' }}</th>
            {{-- <th colspan="2">{{ $caixa ? $caixa->nome : 'TODOS' }}</th> --}}
        </tr>
    </thead>
</table>

<table>
    <thead>
        <tr>
            <th style="width: 90px">Nº</th>
            <th>Factura</th>
            <th style="text-align: center">Forma Pagamento</th>
            <th style="text-align: right">Qtd</th>
            <th style="text-align: right">Total de Incidência</th>
            <th style="text-align: right">Total de Imposto</th>
            <th style="text-align: center">Data</th>
            <th style="text-align: right">Total</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($vendas as $key => $item)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $item->factura_next }}</td>
            <td style="text-align: center">{{ $item->pagamento == 'NU' ? 'NUMERÁRIO' : ($item->pagamento == 'MB' ? 'MULTICAIXA' : "DUPLO") }}</td>
            <td style="text-align: right">{{ number_format($item->quantidade, 1, ',', '.') }}</td>
            <td style="text-align: right">{{ number_format($item->total_incidencia, 2, ',', '.') }}</td>
            <td style="text-align: right">{{ number_format($item->total_iva, 2, ',', '.') }}</td>
            <td style="text-align: center">{{ date('Y-m-d', strtotime($item->created_at)) }}</td>
            <td style="text-align: right">{{ number_format($item->valor_total, 2, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <th style="padding: 5px;text-align: left" colspan="5">TOTAL DE REGISTRO: {{ count($vendas) }}</th>
        </tr>
    </tfoot>
</table>

{{-- <footer>
    <p>Lorem ipsum dolor sit amet.</p>
</footer> --}}
@endsection
