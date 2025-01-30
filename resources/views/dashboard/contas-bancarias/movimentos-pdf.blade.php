@extends('layouts.pdf')

@section('pdf-content')

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

<table>
    <thead>
        <tr>
            <th colspan="10" style="text-transform: uppercase"> {{ $titulo??"" }}</th>
        </tr>
    </thead>
</table>

<table>
    <thead>
        <tr>
            <th style="text-transform: uppercase" colspan="2">Data Inicio: {{ $requests['data_inicio'] ?? 'TODOS' }}</th>
            <th style="text-transform: uppercase" colspan="2">Data Final: {{ $requests['data_final'] ?? 'TODOS'  }}</th>
            <th style="text-transform: uppercase" colspan="2">Operador: {{ $user->name ?? 'TODOS'  }}</th>
            <th style="text-transform: uppercase" colspan="2">Conta Bancária: {{ $banco->nome ?? 'TODOS'  }}</th>
            <th style="text-transform: uppercase" colspan="1">Moeda: {{ $empresa->moeda }}</th>
        </tr>
    </thead>
</table>

<table>
    <thead>
        <tr>
            <th>Operador</th>
            <th>Conta Bancária</th>
            <th>Data Abertura</th>
            <th>Data Fecho</th>
            <th style="text-align: right">V. Abertura</th>
            <th style="text-align: right">Valor</th>
            {{-- <th style="text-align: right">CASH</th> --}}
            <th style="text-align: right">Total</th>
            <th class="text-right">Estado</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($movimentos as $item)
            <tr>
                <td>{{ $item->user->name ?? "" }}</td>
                <td>{{ $item->banco->nome ?? "" }}</td>
                <td>{{ $item->data_abertura ?? "" }}</td>
                <td>{{ $item->data_fecho ?? "" }}</td>
                <td style="text-align: right">{{ number_format($item->valor_abertura??0, 2, ',', '.') }}</td>
                <td style="text-align: right">{{ number_format($item->valor_multicaixa??0, 2, ',', '.') }}</td>
                {{-- <td style="text-align: right">{{ number_format($item->valor_cash, 2, ',', '.') }}</td> --}}
                
                @if (($item->valor_valor_fecho??0) < 0)
                <td class="text-danger" style="text-align: right">{{ number_format(($item->valor_valor_fecho??0), 2, ',', '.') }}</td>    
                @endif
                
                @if (($item->valor_valor_fecho??0) == 0)
                <td class="text-warning" style="text-align: right">{{ number_format(($item->valor_valor_fecho??0), 2, ',', '.') }}</td>    
                @endif
                
                @if (($item->valor_valor_fecho??0) > 0)
                <td class="text-success" style="text-align: right">{{ number_format(($item->valor_valor_fecho??0), 2, ',', '.') }}</td>    
                @endif
                
                @if ($item->status == false)
                <td class="text-danger text-right">FECHADO</td>    
                @else
                <td class="text-success text-right">ABERTO</td>    
                @endif
            </tr>
        @endforeach
    </tbody>
    
    <tfoot>
        <tr>
            <th style="padding: 6px">TOTAL REGISTRO: {{ count($movimentos)  }}</th>
        </tr>
    </tfoot>
</table>

@endsection