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
            <th colspan="4" style="text-transform: uppercase">Marca: {{ $marca ? $marca->nome : 'TODOS' }}</th>
            <th colspan="4" style="text-transform: uppercase">Categoria: {{ $categoria ? $categoria->nome : 'TODOS' }}</th>
            <th colspan="2" style="text-transform: uppercase">MOEDA: {{ $empresa->moeda }}</th>
        </tr>

    </thead>
</table>

<table>
    <thead>
        <tr>
            <th>Ref</th>
            <th>Produto</th>
            <th style="text-align: right">Qtd</th>
            <th style="text-align: right">P.Venda</th>
            <th style="text-align: right">P.Custo</th>
            <th style="text-align: right">IVA</th>
            <th style="text-align: right">Total C/IVA</th>
            <th style="text-align: right">Total S/IVA</th>
        </tr>

    </thead>
    @php
        $quantidade = 0;
        $total_compra = 0;
        $total_venda = 0;
    @endphp
    <tbody>
        @foreach ($produtos as $produto)
            @php
                $quantidade += $produto->quantidade_sum_quantidade;
                $total_venda += $produto->preco_venda * $produto->quantidade_sum_quantidade;
                $total_compra += $produto->preco * $produto->quantidade_sum_quantidade;
            @endphp
            <tr>
                <td>{{ $produto->referencia }} </td>
                <td>{{ $produto->nome }} <br><small>{{ $produto->categoria->categoria }}</small></td>
                <td style="text-align: right">{{ number_format($produto->quantidade_sum_quantidade, 1, ',', '.') }}</td>
                <td style="text-align: right">{{ number_format($produto->preco_venda, 2, ',', '.') }}<br> <small>S/IVA: {{ number_format($produto->preco, 2, ',', '.') }}</td>
                <td style="text-align: right">{{ number_format($produto->preco_custo, 2, ',', '.') }}</td>
                <td style="text-align: right">{{ $produto->taxa_imposto->valor }} %</td>
                <td style="text-align: right">{{ number_format($produto->preco_venda * $produto->quantidade_sum_quantidade, 2, ',', '.') }}</td>
                <td style="text-align: right">{{ number_format($produto->preco * $produto->quantidade_sum_quantidade, 2, ',', '.') }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="2">Total</th>
            <th>{{ number_format($quantidade, 2, ',', '.') }}</th>
            <th style="text-align: right;padding: 4px"></th>
            <th style="text-align: right;padding: 4px"></th>
            <th style="text-align: right;padding: 4px"></th>
            <th style="text-align: right;padding: 4px">{{ number_format($total_compra, 2, ',', '.') }}</th>
            <th style="text-align: right;padding: 4px">{{ number_format($total_venda, 2, ',', '.') }}</th>
        </tr>
    </tbody>
</table>


@endsection