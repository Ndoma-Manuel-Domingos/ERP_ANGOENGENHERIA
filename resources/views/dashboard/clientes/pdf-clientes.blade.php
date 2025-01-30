@extends('layouts.pdf')

@section('pdf-content')

<header>
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
</header>

<table>
    <thead>
        <tr>
            <th colspan="9" style="text-transform: uppercase"> {{ $titulo }}</th>
        </tr>
    
    </thead>
</table>

<table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
    <thead>
        <tr>
            <th>Conta</th>
            <th>Nome</th>
            <th>Gênero</th>
            <th>Estado Civil</th>
            <th>Data Nascimento</th>
            <th>NIF/Bilhete</th>
            <th>Codigo Postal</th>
            <th>Telelefone/Telemóvel</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($clientes as $item)
        <tr>
            <td>{{ $item->conta }}</td>
            <td>{{ $item->nome }}</td>
            <td>{{ $item->genero ?? '------' }}</td>
            <td>{{ $item->estado_civil->nome ?? '------' }}</td>
            <td>{{ $item->data_nascimento ?? '------' }}</td>
            <td>{{ $item->nif ?? '------' }}</td>
            <td>{{ $item->codigo_postal ?? '------' }}</td>
            <td>{{ $item->telefone ?? '--- --- ---' }} / {{ $item->telemovel ?? '--- --- --- ---' }}</td>
            @if ($item->status == true)
            <td>Activo</td>
            @else
            <td>Inactivo</td>
            @endif
        </tr>
        @endforeach
        
        <tr>
            <th colspan="9" style="text-transform: uppercase;padding: 10px;text-align: left">TOTAL DE REGISTROS: {{ count($clientes) }}</th>
        </tr>
    </tbody>
</table>
@endsection