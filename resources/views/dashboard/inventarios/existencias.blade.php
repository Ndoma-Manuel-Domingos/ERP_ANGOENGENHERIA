@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Existências</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('inventarios.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Existências</li>
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
                <div class="col-12 col-md-12">
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
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">

                            <h3 class="card-title">
                                @if (Auth::user()->can('criar produtos'))
                                <a href="{{ route('produtos.create') }}" class="btn btn-sm btn-primary">Novas ExistÊncias</a>
                                @endif
                            </h3>

                            <div class="card-tools">
                                <a class="btn btn-sm btn-danger" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                                <a class="btn btn-sm btn-success" href="#"><i class="fas fa-file-excel"></i> EXCEL</a>
                            </div>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Conta</th>
                                        <th rowspan="2">Produto</th>
                                        <th rowspan="2" class="text-right">Preço Unitário</th>
                                        <th class="text-center" colspan="{{ 1 * count($lojas) }}">Quantidades</th>
                                        <th class="text-center" colspan="{{ 1 * count($lojas) }}">Totais</th>
                                        <th rowspan="2" class="text-right">Total Geral</th>
                                    </tr>
                                    <tr>
                                        @if ($lojas)
                                            @foreach ($lojas as $loja)
                                            <th class="text-right">{{ $loja->nome }}</th>
                                            @endforeach
                                            @foreach ($lojas as $loja)
                                            <th class="text-right">{{ $loja->nome }}</th>
                                            @endforeach
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>

                                    @php
                                        $totais = 0;
                                    @endphp
                                    @foreach ($produtos as $produto)
                                    <tr>
                                        <td><a href="{{ route('produtos.show', $produto->id) }}">{{ $produto->conta }} </a></td>
                                        <td><a href="{{ route('produtos.show', $produto->id) }}">{{ $produto->nome }} </a></td>
                                        <td class="text-right">{{ number_format($produto->preco_venda ?? 0, 2, ',', '.') }}</td>
                                        
                                        @if ($lojas)
                                            @foreach ($lojas as $loja)
                                                <td class="text-right">{{ number_format($produto->total_produto_por_loja($produto->id, $loja->id) ?? 0, 1, ',', '.') }}</td>
                                            @endforeach
                                        @endif
                                        @if ($lojas)
                                            @foreach ($lojas as $loja)
                                                <td class="text-right">{{ number_format($produto->total_produto_por_loja($produto->id, $loja->id) * $produto->preco_venda ?? 0, 2, ',', '.') }}</td>
                                            @endforeach
                                        @endif
                                        <td class="text-right">{{ number_format($produto->total_produto($produto->id) * $produto->preco_venda ?? 0, 2, ',', '.') }}</td>
                                        @php
                                            $totais += $produto->total_produto($produto->id) * $produto->preco_venda;
                                        @endphp
                                    </tr>
                                    @endforeach
                                </tbody>
                                
                                <tr>
                                    <th colspan="7"></th>
                                    <th class="text-right">{{ number_format($totais, 2, ',', '.') }}</th>
                                </tr>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        
                        <div class="card-footer">
                        </div>

                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
