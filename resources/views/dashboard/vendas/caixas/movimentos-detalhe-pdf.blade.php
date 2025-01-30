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
            <th style="text-transform: uppercase">Operador</th>
            <th style="text-transform: uppercase">{{ $movimento->user->name }}</th>
        </tr>
        <tr>
            <th style="text-transform: uppercase">Caixa</th>
            <th style="text-transform: uppercase">{{ $movimento->caixa->nome }}</th>
        </tr>
    </thead>
</table>

<table>
    <thead>
        
        <tr>
            <th style="text-align: right;text-transform: uppercase;background-color: #f7f7f7">Data Abertura</th>
            <th style="text-align: right;text-transform: uppercase;background-color: #f7f7f7">Data Fecho</th>
            <th style="text-align: right;text-transform: uppercase;background-color: #f7f7f7">Hora Abertura</th>
            <th style="text-align: right;text-transform: uppercase;background-color: #f7f7f7">Hora Fecho</th>
        </tr>
        <tr>
            <th style="text-align: right;text-transform: uppercase">{{ $movimento->data_abertura }}</th>
            <th style="text-align: right;text-transform: uppercase">{{ $movimento->data_fecho }}</th>
            <th style="text-align: right;text-transform: uppercase">{{ $movimento->hora_abertura }}</th>
            <th style="text-align: right;text-transform: uppercase">{{ $movimento->hora_fecho }}</th>
        </tr>
        
        <tr>
            <th style="text-align: right;text-transform: uppercase;background-color: #f7f7f7">Valor de Abertura</th>
            <th style="text-align: right;text-transform: uppercase;background-color: #f7f7f7">Valor de Entrada</th>
            <th style="text-align: right;text-transform: uppercase;background-color: #f7f7f7">Valor de Saída</th>
            <th style="text-align: right;text-transform: uppercase;background-color: #f7f7f7"></th>
        </tr>
        <tr>
            <th style="text-align: right">{{ number_format($movimento->valor_abertura, 2, ',', '.') }}</th>
            <th style="text-align: right">{{ number_format($movimento->valor_entrada, 2, ',', '.') }}</th> 
            <th style="text-align: right">{{ number_format($movimento->valor_saida, 2, ',', '.') }}</th> 
          
            <th style="text-align: right"></th>
        </tr>
        
        {{-- ------------------------------------------------------------------------------------------------------------------ --}}
        
        <tr>
            <th style="text-align: right;text-transform: uppercase;background-color: #f7f7f7">Valor de Abertura</th>
            <th style="text-align: right;text-transform: uppercase;background-color: #f7f7f7">Valor Multicaixa</th>
            <th style="text-align: right;text-transform: uppercase;background-color: #f7f7f7">Estado</th>
            <th style="text-align: right;text-transform: uppercase;background-color: #f7f7f7">VALOR NUMERÁRIO</th>
        </tr>
        
        <tr>
            <th style="text-align: right">{{ number_format($movimento->valor_abertura, 2, ',', '.') }}</th>
            <th style="text-align: right">{{ number_format($movimento->valor_multicaixa, 2, ',', '.') }}</th>
            @if ($movimento->status == false)
            <th style="text-align: right">FECHADO</th>    
            @else
            <th style="text-align: right">ABERTO</th>    
            @endif
            <th style="text-align: right">{{ number_format($movimento->valor_cash, 2, ',', '.') }}</th>
        </tr>
        
        {{-- ------------------------------------------------------------------------------------------------------------------ --}}
        
        <tr>
            <th style="text-align: left;text-transform: uppercase"></th>
            <th style="text-align: left;text-transform: uppercase"></th>
            <th style="text-align: left;text-transform: uppercase"></th>
            <th style="text-align: right;text-transform: uppercase;background-color: #f7f7f7">Total</th>
        </tr>

        <tr>
            <th class="text-success" style="text-align: left"></th> 
            <th class="text-success" style="text-align: left"></th> 
            <th class="text-success" style="text-align: left"></th> 
            
            @if (($movimento->valor_valor_fecho) < 0)
            <th style="text-align: right">{{ number_format(($movimento->valor_valor_fecho), 2, ',', '.') }}</th>    
            @endif
            @if (($movimento->valor_valor_fecho) == 0)
            <th style="text-align: right">{{ number_format(($movimento->valor_valor_fecho), 2, ',', '.') }}</th>    
            @endif
            @if (($movimento->valor_valor_fecho) > 0)
            <th style="text-align: right">{{ number_format(($movimento->valor_valor_fecho), 2, ',', '.') }}</th>    
            @endif
        </tr>
        
        {{-- ------------------------------------------------------------------------------------------------------------------ --}}
        
    </thead>

</table>

@endsection