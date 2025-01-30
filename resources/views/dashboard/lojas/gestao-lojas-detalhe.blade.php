@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Lojas/Armazém - {{ $loja->nome }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('gestao-lojas-armazem') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Gestão</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <!-- /.row -->
            <div class="row">
                <div class="col-12">
                    @if (Session::has('success'))
                    <div class="alert alert-success">
                        {{ Session::get('success') }}
                    </div>
                    @endif

                    @if (Session::has('danger'))
                    <div class="alert alert-danger">
                        {{ Session::get('danger') }}
                    </div>
                    @endif

                    @if (Session::has('warning'))
                    <div class="alert alert-warning">
                        {{ Session::get('warning') }}
                    </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Produto</th>
                                        <th class="text-right">Stock Minimo</th>
                                        <th class="text-right">Stock Alerta</th>
                                        <th class="text-right">Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $total_stock_minimo = 0;
                                    $total_stock_alerta = 0;
                                    $total_stock = 0;
                                    @endphp

                                    @foreach ($estoques as $item)

                                    @php
                                    $total_stock_minimo += $item->stock_minimo;
                                    $total_stock_alerta += $item->stock_alerta;
                                    $total_stock += $item->stock;
                                    @endphp

                                    <tr>
                                        <td>{{ $item->produto->id }}</td>
                                        <td><a href="{{ route('produtos.show', $item->produto->id) }}">{{ $item->produto->nome }}</a></td>
                                        <td class="text-right">{{ $item->stock_minimo }}</td>

                                        @if ($item->stock > 50)
                                        <td class="text-right text-warning">Excesso</td>
                                        @endif

                                        @if ($item->stock <= 10) <td class="text-right text-danger">Alerta</td>
                                            @endif

                                            @if ($item->stock > 10 AND $item->stock <= 50) <td class="text-right text-success">Normal</td>
                                                @endif

                                                <td class="text-right">{{ $item->stock }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th class="text-right" colspan="2">TOTAL</th>
                                        <th class="text-right">{{ $total_stock_minimo }}</th>
                                        <th class="text-right">{{ $total_stock_alerta }}</th>
                                        <th class="text-right">{{ $total_stock }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="card-footer">
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
