@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Equipamentos/Activos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('inventarios.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Equipamentos</li>
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
                                @if (Auth::user()->can('criar exercicio'))
                                <a href="{{ route('equipamentos-activos.create') }}" class="btn btn-sm btn-primary">Novos Equipamentos/Activos</a>
                                @endif
                            </h3>

                            <div class="card-tools">
                                <a class="btn btn-sm btn-danger" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                                <a class="btn btn-sm btn-success" href="#"><i class="fas fa-file-excel"></i> EXCEL</a>
                            </div>
                        </div>

                        @if ($equipamentos_activos)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Conta</th>
                                        <th>Designação</th>
                                        <th class="text-right">Quantidade</th>
                                        <th class="text-right">Preço Unitário</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totais = 0;
                                        $qtds = 0;
                                    @endphp
                                    @foreach ($equipamentos_activos as $item)
                                    <tr>
                                        <td><a href="{{ route('equipamentos-activos.show', $item->id) }}">{{ $item->conta->numero }}</a></td>
                                        <td><a href="{{ route('equipamentos-activos.show', $item->id) }}">{{ $item->nome }}</a></td>
                                        <td class="text-right">{{ $item->quantidade }}</td>
                                        <td class="text-right">{{ number_format($item->base_incidencia, 2, ',', '.') }}</td>
                                        <td class="text-right">{{ number_format($item->base_incidencia * $item->quantidade, 2, ',', '.') }}</td>
                                        @php
                                            $totais += $item->base_incidencia * $item->quantidade;
                                            $qtds += $item->quantidade;
                                        @endphp
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th class="text-right">{{ number_format($qtds, 2, ',', '.') }}</th>
                                        <th></th>
                                        <th class="text-right">{{ number_format($totais, 2, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        @endif
                        
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
