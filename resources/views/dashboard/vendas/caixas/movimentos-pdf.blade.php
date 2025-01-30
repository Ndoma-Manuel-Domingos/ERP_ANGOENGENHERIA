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
            <th colspan="10" style="text-transform: uppercase"> {{ $titulo }}</th>
        </tr>
    </thead>
</table>

<table>
    <thead>
        <tr>
            <th style="text-transform: uppercase" colspan="2">Data Inicio: {{ $requests['data_inicio'] ?? 'TODOS' }}</th>
            <th style="text-transform: uppercase" colspan="2">Data Final: {{ $requests['data_final'] ?? 'TODOS'  }}</th>
            <th style="text-transform: uppercase" colspan="2">Operador: {{ $user->name ?? 'TODOS'  }}</th>
            <th style="text-transform: uppercase" colspan="2">Caixa: {{ $caixa->nome ?? 'TODOS'  }}</th>
            <th style="text-transform: uppercase" colspan="2">Moeda: {{ $empresa->moeda }}</th>
        </tr>
    </thead>
</table>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Número</th>
            <th>Operador</th>
            <th>Pagamento</th>
            <th>Movimento</th>
            <th style="text-align: right">Credito</th>
            <th style="text-align: right">Debito</th>         
        </tr>
    </thead>

    <tbody>
        @php
            $credito = 0;
            $debito = 0;
        @endphp
        @foreach ($movimentos as $item)
            <tr>
                <td class="text-left">{{ $item->id ?? "" }}</td>
                <td class="text-left">{{ $item->numero ?? "" }}</td>
                <td class="text-left">{{ $item->user->name ?? "" }}</td>
                
                @if ($item->forma_movimento == "NU")
                <td class="text-left">NUMÉRARIO</td>
                @else
                <td class="text-left">MULTICAIXA</td>
                @endif
                
                @if ($item->movimento == "E")
                    <td class="text-left">Entrada</td>
                @else
                    <td class="text-left">Saída</td>
                @endif
                
                <td style="text-align: right;color: red">{{ number_format($item->credito ??0, 2, ',', '.')  }}</td>
                <td style="text-align: right;color: blue">{{ number_format($item->debito ??0, 2, ',', '.')  }}</td>
                
                @php
                    $credito += $item->credito;
                    $debito += $item->debito;
                @endphp
                
            </tr>
        @endforeach
        
        <tr>
            <td style="text-align: center">----</td>
            <td style="text-align: center">----</td>
            <td style="text-align: center">----</td>
            <td style="text-align: center">----</td>
            <td style="text-align: center">----</td>
            <td style="text-align: right;color: red">{{ number_format($credito ?? 0, 2, ',', '.')  }}</td>
            <td style="text-align: right;color: blue">{{ number_format($debito ?? 0, 2, ',', '.')  }}</td>
        </tr>
        <tr>
            <td style="text-align: center">----</td>
            <td style="text-align: center">----</td>
            <td style="text-align: center">----</td>
            <td style="text-align: center">----</td>
            <td style="text-align: center">----</td>
            <td style="text-align: right">SALDO FINAL</td>
            
            @php
                $saldo = ($debito ?? 0) - ($credito ?? 0);
            @endphp
            @if ($saldo >= 0)
            <td style="text-align: right;color: blue">{{ number_format($saldo, 2, ',', '.')  }}</td>
            @else     
            <td style="text-align: right;color: red">{{ number_format($saldo, 2, ',', '.')  }}</td>
            @endif
        </tr>
    </tbody>
    
    <tfoot>
        <tr>
            <th style="padding: 7px">TOTAL REGISTRO: {{ count($movimentos)  }}</th>
        </tr>
    </tfoot>
</table>

@endsection