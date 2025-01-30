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
        </tr>

        <tr>
            <th colspan="2">{{ $requests['data_inicio'] ?? 'TODOS' }}</th>
            <th colspan="2">{{ $requests['data_final'] ?? 'TODOS' }}</th>
        </tr>
    </thead>
</table>

<table>
    <thead>
        
        <tr>
            <th style="width: 90px">ID</th>
            <th>Produto</th>
            <th style="text-align: right">Preço</th>
            <th style="text-align: right">Quantidade</th>
            <th style="text-align: right">Total</th>
        </tr>

    </thead>

    <tbody>
    
        @foreach ($vendas as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->produto->nome }}</td>
            <td style="text-align: right">{{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
            <td style="text-align: right">{{ number_format($item->quantidade, 2, ',', '.') }}</td>
            <td style="text-align: right">{{ number_format($item->valor_pagar, 2, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <th>TOTAL</th>
            <th colspan="4" style="text-align: right">{{ number_format($total_venda, 2, ',', '.') }}</th>
        </tr>
    </tfoot>
</table>

@endsection
