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
            <th colspan="4" style="text-transform: uppercase">Loja</th>
        </tr>

        <tr>
            <th colspan="2">{{ $requests['data_inicio'] ?? 'TODOS' }}</th>
            <th colspan="2">{{ $requests['data_final'] ?? 'TODOS' }}</th>
            <th colspan="4">{{ $loja ? $loja->nome : 'TODOS' }}</th>
        </tr>
    </thead>
</table>

<table>
    <thead>
        
        <tr>
            <th style="width: 90px">ID</th>
            <th>Produto</th>
            <th style="text-align: right">Preço</th>
            <th style="text-align: right">Imposto</th>
            <th style="text-align: right">Desconto</th>
            @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão de Stock"))
            <th class="text-right">Quantidade Consumidas</th>
            @else
            <th class="text-right">Quantidade Vendidas</th>
            @endif
            <th style="text-align: right">Quantidade no Stock</th>
            
            <th style="text-align: right">Total Liquido Vendido</th>
            <th style="text-align: right">Total Liquido Stock</th>
        </tr>

    </thead>

    <tbody>
        
        @php
            $total_liquido_vendido_valor = 0;
            $total_liquido_restante_valor = 0;
            $total_liquido_geral_valor = 0;
        @endphp     
    
        @foreach ($dados as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->produto }}</td>
            <td style="text-align: right">{{ number_format($item->preco, 2, ',', '.') }}</td>
            <td style="text-align: right">{{ number_format($item->imposto, 2, ',', '.') }}</td>
            <td style="text-align: right">{{ number_format($item->desconto, 2, ',', '.') }}</td>
            <td style="text-align: right">{{ number_format($item->quantidade_vendida, 2, ',', '.') }}</td>
            <td style="text-align: right">{{ number_format($item->quantidade_estoque, 2, ',', '.') }}</td>
            
            <td style="text-align: right">{{ number_format($item->total_liquido_vendido, 2, ',', '.') }}</td>
            <td style="text-align: right">{{ number_format($item->preco * $item->quantidade_estoque, 2, ',', '.') }}</td>
            
            @php
                $total_liquido_vendido_valor += $item->total_liquido_vendido;
                $total_liquido_restante_valor += $item->preco * $item->quantidade_estoque;
                $total_liquido_geral_valor += $item->total_liquido_geral;
            @endphp 
        </tr>
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <th>TOTAL</th>
            <th style="text-align: right">---</th>
            <th style="text-align: right">---</th>
            <th style="text-align: right">---</th>
            <th style="text-align: right">---</th>
            <th style="text-align: right">---</th>
            <th style="text-align: right">---</th>
            
            <th style="text-align: right">{{ number_format($total_liquido_vendido_valor, 2, ',', '.') }}</th>
            <th style="text-align: right">{{ number_format($total_liquido_restante_valor, 2, ',', '.') }}</th>
        </tr>
    </tfoot>
</table>

@endsection
