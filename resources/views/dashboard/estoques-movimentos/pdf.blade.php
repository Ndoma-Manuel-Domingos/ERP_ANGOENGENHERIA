@extends('layouts.pdf')

@section('pdf-content')

<table style="border: 0">
    <tr>
        <td style="border: 0;">
            <img src="images/empresa/{{ $empresa->empresa->logotipo }}" style="height: 100px;width: 100px">
        </td>
    </tr>
    <tr style="border: 0">
        <td style="border: 0">{{ $empresa->empresa->nome }}</td>
    </tr>
    <tr style="border: 0">
        <td style="border: 0"><strong>NIF: </strong>{{ $empresa->empresa->nif }}</td>
    </tr>
    <tr style="border: 0">
        <td style="border: 0"><strong>Endereço: </strong>{{ $empresa->empresa->morada }}</td>
    </tr>
    <tr style="border: 0">
        <td style="border: 0"><strong>{{ $empresa->empresa->cidade }} - {{ $empresa->empresa->pais }}</strong></td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th colspan="2" style="text-transform: uppercase">Data Inicio</th>
            <th colspan="2" style="text-transform: uppercase">Data Final</th>
            <th colspan="2" style="text-transform: uppercase">Loja</th>
            <th colspan="2" style="text-transform: uppercase">Produto</th>
        </tr>

        <tr>
            <th colspan="2">{{ $requests['data_inicio'] ?? 'TODOS' }}</th>
            <th colspan="2">{{ $requests['data_final'] ?? 'TODOS' }}</th>
            <th colspan="2">{{ $loja ? $loja->nome : 'TODOS' }}</th>
            <th colspan="2">{{ $produto ? $produto->nome : 'TODOS' }}</th>
        </tr>
    </thead>
</table>

<table>
    <thead>
        <tr>
            <th colspan="8" style="text-transform: uppercase"> {{ $titulo }}</th>
        </tr>
        <tr>
            <th style="width: 2px">Codigo</th>
            <th>Produto</th>
            <th style="text-align: right"><span class="float-right">Quant.</span></th>
            <th>Data</th>
            <th>Operação</th>
            <th>Loja</th>
            <th colspan="2">Observação</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($movimentos as $key => $movimento)
        <tr>
          <td>{{ $key + 1 }}</td>
          <td>{{ $movimento->produto->nome }}</td>
          <td style="text-align: right"><span class="float-right text-success">{{ number_format($movimento->quantidade, 2, ',', '.')  }}</span></td>
          <td>{{ date_format($movimento->created_at, "Y-m-d") }} <br>
             <small>{{ date_format($movimento->created_at, "h:i:s") }}</small></td>
          <td>{{ $movimento->registro }} <br> 
            <small class="text-secondary">{{ $movimento->user->name }}</small> 
          </td>
          <td>{{ $movimento->loja->nome }}</td>
          <td colspan="2">{{ $movimento->observacao }}</td>
        </tr>
        @endforeach
    </tbody>
    
    <tfoot>
        <tr>
            <th style="padding: 6px 0;text-align: left" colspan="7">TOTAL REGISTROS: {{ count($movimentos) }}</th>
        </tr>
    </tfoot>
</table>


@endsection