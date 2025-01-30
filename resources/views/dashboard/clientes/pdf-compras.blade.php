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
            <th colspan="2" style="text-transform: uppercase">Cliente</th>
        </tr>

        <tr>
            <th colspan="2">{{ $requests['data_inicio'] ?? 'TODOS' }}</th>
            <th colspan="2">{{ $requests['data_final'] ?? 'TODOS' }}</th>
            <th colspan="2">{{ $cliente ? $cliente->nome : 'TODOS' }}</th>
        </tr>
    </thead>
</table>

@if ($dadosClientes)
<!-- /.card-header -->
<table>
    <tbody>
        @foreach ($dadosClientes as $clienteData)
        <tr>
            <td style="text-align: left;text-transform: uppercase;background-color: #aeaeae" colspan="7">{{ $clienteData->codigo }} - {{ $clienteData->cliente }}</td>
        </tr>
        <tr>
            <td style="text-align: left"><strong>Artigo</strong></td>
            <td style="text-align: left"><strong>Descrição</strong></td>
            <td style="text-align: right"><strong>Quantidade</strong></td>
            <td style="text-align: right"><strong>Total</strong></td>
            <td style="text-align: right"><strong>Total Descontos</strong></td>
            <td style="text-align: right"><strong>Custo</strong></td>
            <td style="text-align: right"><strong>Lucro</strong></td>
        </tr>
            @php
                $quantidade = 0;
                $valor_pagar = 0;
                $desconto_aplicado_valor = 0;
                $custo = 0;
                $custo_ganho = 0;
            @endphp
            @foreach ($clienteData->produtos as $produto)
              <tr>
                  <td>#</td>
                  <td style="text-transform: uppercase;">{{ $produto['produto'] }}</td>
                  <td style="text-align: right">{{ number_format($produto['quantidade'], 2, ',', '.')  }}</td>
                  <td style="text-align: right">{{ number_format($produto['valor_pagar'], 2, ',', '.')  }}</td>
                  <td style="text-align: right">{{ number_format($produto['desconto_aplicado_valor'], 2, ',', '.')  }}</td>
                  <td style="text-align: right">{{ number_format($produto['custo'] * $produto['quantidade'], 2, ',', '.')  }}</td>
                  <td style="text-align: right">{{ number_format($produto['custo_ganho'] ?? 0, 2, ',', '.')  }}</td>
              </tr>
              
                @php
                    $quantidade += $produto['quantidade'];
                    $valor_pagar += $produto['valor_pagar'];
                    $desconto_aplicado_valor += $produto['desconto_aplicado_valor'];
                    $custo += ($produto['custo'] * $produto['quantidade']);
                    $custo_ganho += $produto['custo_ganho'];
                @endphp
              
            @endforeach
            
            <tr>
                <td style="text-align: right" colspan="2"><strong>Totais</strong></td>
                <td style="text-align: right"><strong>{{ number_format($quantidade, 2, ',', '.') }}</strong></td>
                <td style="text-align: right"><strong>{{ number_format($valor_pagar, 2, ',', '.') }}</strong></td>
                <td style="text-align: right"><strong>{{ number_format($desconto_aplicado_valor, 2, ',', '.') }}</strong></td>
                <td style="text-align: right"><strong>{{ number_format($custo, 2, ',', '.') }}</strong></td>
                <td style="text-align: right"><strong>{{ number_format($custo_ganho, 2, ',', '.') }}</strong></td>
            </tr>
        @endforeach
    </tbody>
</table>

@endif

@endsection
