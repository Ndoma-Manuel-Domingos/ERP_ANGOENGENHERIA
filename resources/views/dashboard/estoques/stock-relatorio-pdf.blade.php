@extends('layouts.pdf')

@section('pdf-content')
<header>
    {{-- <h1>{{ $titulo }}</h1> --}}
</header>

<table>
    <thead>
        <tr>
            <th colspan="8" style="text-align: center">{{ $titulo }}</th>
        </tr>
        <tr>
            <th colspan="4">ESTADO</th>
            <th style="text-align: left;text-transform: uppercase" colspan="4">{{ $requests['status'] ?? 'TODOS' }}</th>
        </tr>
    </thead>
</table>

<table>
    <thead>
        <tr>
            <th>Lote</th>
            <th>Codigo Barra(Lote)</th>
            <th>Estado(Lote)</th>
            <th>Codigo Barra(Produto)</th>
            <th>Produto</th>
            {{-- <th>Marca</th> --}}
            {{-- <th>Categoria</th> --}}
            <th>Stock</th>
            <th><span class="float-right">Preço </span></th>
            <th><span class="float-right">Valor Acumulado </span></th>
        </tr>
    </thead>

    <tbody>
        @foreach ($estoques as $item)
        <tr>
            <td>{{ $item->lote->lote }}</td>
            <td>{{ $item->lote->codigo_barra }}</td>
            <td class="text-success text-uppercase">{{ $item->lote->status }}</td>
            <td>{{ $item->produto->codigo_barra }}</td>
            <td>{{ $item->produto->nome }}</td>
            {{-- <td>{{ $item->produto->marca->nome }}</td> --}}
            {{-- <td>{{ $item->produto->categoria->categoria }}</td> --}}
            <td>{{ $item->stock }}</td>
            <td><span class="float-right">{{ number_format($item->produto->preco, '2', ',', '.')  }}</span></td>
            <td>
                <span class="float-right">{{ number_format($item->produto->preco * $item->stock, '2', ',', '.')  }}</span>
            </td>
        </tr>
        @endforeach
    </tbody>

</table>

<table>
    <tfoot>
        <tr>
            <th style="text-align: left;text-transform: uppercase" colspan="4">TOTAL DE REGISTRO</th>
            <th colspan="4">{{ $estoques->total() }}</th>
        </tr>
    </tfoot>    
</table>

{{-- <footer>
    <p>Lorem ipsum dolor sit amet.</p>
</footer> --}}
@endsection