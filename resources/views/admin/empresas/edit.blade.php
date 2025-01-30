@extends('layouts.admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $empresa->nome }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('empresas.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Empresa</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row col-12 col-md-12">
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

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <form action="{{ route('empresas.update', $empresa->controle->id) }}" method="post" class="">
                            @csrf
                            @method('put')

                            <div class="card-header">
                                <h5>Configurar Contrato da Licença</h5>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <label for="" class="form-label">Data Inicio</label>

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="date" class="form-control" name="inicio" value="{{ $empresa->controle->inicio }}">
                                        </div>
                                        <p class="text-danger">
                                            @error('inicio')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="" class="form-label">Data Final</label>

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="date" class="form-control" name="final" value="{{ $empresa->controle->final }}">
                                        </div>
                                        <p class="text-danger">
                                            @error('final')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-sm">Salvar Configuração</button>
                            </div>
                        </form>
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
