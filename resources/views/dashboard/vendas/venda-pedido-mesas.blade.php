@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Escolher Mesa</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
        
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
                        <div class="card-body">
                            <div class="row">
                                @foreach ($mesas as $item)
                                <div class="col-6 col-md-3 col-lg-2">
                                    <a href="{{ route('pronto-venda-mesas-pedidos', Crypt::encrypt($item->id)) }}">
                                        @if ($item->solicitar_ocupacao == "OCUPADA")
                                        <div class="card bg-info">
                                        @endif
                                        @if ($item->solicitar_ocupacao == "LIVRE")
                                            <div class="card bg-success">
                                        @endif
                                        @if ($item->solicitar_ocupacao == "RESERVADA")
                                            <div class="card bg-warning">
                                        @endif
                                                    @if ($item->solicitar_ocupacao == "OCUPADA")
                                                    <div class="card-body bg-info">
                                                        @endif
                                                        @if ($item->solicitar_ocupacao == "LIVRE")
                                                        <div class="card-body bg-success">
                                                            @endif
                                                            @if ($item->solicitar_ocupacao == "RESERVADA")
                                                            <div class="card-body bg-warning">
                                                                @endif
                                                                <div class="col-12 col-md-12 col-sm-12">
                                                                    <h6 class="text-uppercase">{{ $item->nome }}</h6>
                                                                    <p class="">ESTADO: {{ $item->solicitar_ocupacao }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="card-footer p-1 px-4 bg-light">
                                                                <a href="{{ route('mudar-status-mesa', $item->id) }}" style="display: block;font-size: 11pt">Mudar o Estado para livre</a>
                                                            </div>
                                                        </div>
                                    </a>
                                    <!-- /.card -->
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
