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
            <th colspan="2" style="text-transform: uppercase">Estado</th>
        </tr>

        <tr>
            <th colspan="2">{{ $requests['data_inicio'] ?? 'TODOS' }}</th>
            <th colspan="2">{{ $requests['data_final'] ?? 'TODOS' }}</th>
            <th colspan="2">{{ $requests['status'] ?? 'TODOS' }}</th>
        </tr>
    </thead>
</table>


<table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
    <thead>
      <tr>
        <th>Nº Encomenda</th>
        <th>Fornecedor</th>
        <th>Data</th>
        <th>Estado</th>
        <th style="text-align: right">Qtds</th>
        <th style="text-align: right">Qtds Recebida</th>
        <th style="text-align: right">Total S/IVA</th>
        <th style="text-align: right">Total C/IVA</th>
        <th style="text-align: right">Total</th>
      </tr>
    </thead>
    <tbody>
        @php
            $quantidade = 0;
            $quantidade_recebida = 0;
            $total_sIva = 0;
            $total_cIVa = 0;
            $total = 0;
        @endphp 
    
      @foreach ($encomendas as $item)
        <tr>
          <td>{{ $item->factura }}</td>
          <td>{{ $item->fornecedor->nome }}</td>
          <td>{{ $item->data_emissao }}</td>
          @if ($item->status == 'pendente')
            <td><span style="text-transform: uppercase;color: #cf9a08;">{{ $item->status }}</span></td>
          @endif

          @if ($item->status == 'entregue')
            <td><span style="text-transform: uppercase;color: #0e83c7;">{{ $item->status }}</span></td>
          @endif

          @if ($item->status == 'cancelada')
            <td><span style="text-transform: uppercase;color: #961313;">{{ $item->status }}</span></td>
          @endif
          
          <td style="text-align: right">{{ number_format($item->quantidade, 2, ',', '.') }}</td>
          <td style="text-align: right">{{ number_format($item->quantidade_recebida, 2, ',', '.') }}</td>
          
          <td style="text-align: right">{{ number_format($item->total_sIva, 2, ',', '.') }}</td>
          <td style="text-align: right">{{ number_format($item->total_cIVa, 2, ',', '.') }}</td>
          
          <td style="text-align: right">{{ number_format($item->total, 2, ',', '.') }}</td>
            @php
                
                $quantidade += $item->quantidade;
                $quantidade_recebida += $item->quantidade_recebida;
                $total_sIva += $item->total_sIva;
                $total_cIVa += $item->total_cIVa;
                $total += $item->total;
                
            @endphp
        </tr>    
      @endforeach
      
      <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        
        <th style="text-align: right;padding: 3px">{{ number_format($quantidade, 2, ',', '.') }}</th>
        <th style="text-align: right;padding: 3px">{{ number_format($quantidade_recebida, 2, ',', '.') }}</th>
        <th style="text-align: right;padding: 3px">{{ number_format($total_sIva, 2, ',', '.') }}</th>
        <th style="text-align: right;padding: 3px">{{ number_format($total_cIVa, 2, ',', '.') }}</th>
        <th style="text-align: right;padding: 3px">{{ number_format($total, 2, ',', '.') }}</th>
      </tr>
    </tbody>
 </table>

@endsection
