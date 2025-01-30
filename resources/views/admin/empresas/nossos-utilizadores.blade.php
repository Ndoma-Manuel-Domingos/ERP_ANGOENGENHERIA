@extends('layouts.pdf')

@section('pdf-content')

<header>
    <table style="border: 0">
        <tr style="border: 0">
            <td style="border: 0">EA VIEGAS - COMERCIO GERAL E PRESTAÇAO DE SERVIÇOS , LDA</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>NIF: </strong>5000987670</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>Endereço: </strong>Rua Cónego Manuel das Neves</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>Luanda - ANGOLA</strong></td>
        </tr>
    </table>
</header>

<table>
    <thead>
        <tr>
            <th style="text-transform: uppercase">Código</th>
            <th style="text-transform: uppercase">Nome</th>
            <th style="text-transform: uppercase">E-mail</th>
            <th style="text-transform: uppercase">Empresa</th>
            <th style="text-transform: uppercase">NIF</th>
            <th style="text-transform: uppercase">Telefone</th>
            <th style="text-transform: uppercase">Estado</th>
            <th style="text-transform: uppercase">Modulo</th>
        </tr>
    </thead>
    @php $total = 0; @endphp
    <tbody>
        @foreach ($users as $key => $item)
        @if ($item->company)
        @php $total++; @endphp
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->email }}</td>
            <td>{{ $item->company->nome ?? "" }}</td>
            <td>{{ $item->company->nif ?? "" }}</td>
            <td>{{ $item->company->telefone ?? "" }}</td>
            <td>{{ $item->company->status ?? "" }}</td>
            <td>{{ $item->company->tipo_entidade->tipo ?? "" }}</td>
        </tr>
        @endif
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th style="padding: 4px">TOTAL: {{ $total }}</th>
        </tr>
    </tfoot>
</table>


@endsection
