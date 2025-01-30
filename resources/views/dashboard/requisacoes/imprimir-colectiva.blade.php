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
            <th colspan="2">{{ $requests['tipo_documento'] ?? 'TODOS' }}</th>
        </tr>
    </thead>
</table>

<table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
    <thead>
        <tr>
            <th>Nº Requisição</th>
            <th>Requisitante</th>
            <th>Data</th>
            <th>Estado</th>
            <th style="text-align: right">Qtd Produtos</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($requisicoes as $item)
        <tr>
            <td>REQ Nº {{ $item->id }}</td>
            <td>{{ $item->user->name }}</td>
            <td>{{ $item->data_emissao }}</td>

            @if ($item->status == 'pendente')
            <td><span style="text-transform: uppercase;color: #166c9e;">{{ $item->status }}</span></td>
            @endif

            @if ($item->status == 'aprovada')
            <td><span style="text-transform: uppercase;color: #609b39;">{{ $item->status }}</span></td>
            @endif

            @if ($item->status == 'rejeitada')
            <td><span style="text-transform: uppercase;color: #991010;">{{ $item->status }}</span></td>
            @endif

            @if ($item->status == 'rascunho')
            <td><span style="text-transform: uppercase;color: #d1a207;">{{ $item->status }}</span></td>
            @endif

            <td style="text-align: right">{{ count($item->items) }}</td>

        </tr>
        @endforeach
    </tbody>
</table>
@endsection
