@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Ficheiro SAF-T AO</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('agt.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">AGT</li>
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

            <form action="{{ route('agt.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <h5>Aqui pode exportar o ficheiro SAF-T</h5>
                            <p>Este ficheiro, atualmente na versão 1.01, serve para comunicar à Administração Geral Tributária, os documentos emitidos pela sua Empresa, tendo que o submeter no Portal da AGT até ao dia 15 do mês seguinte àquele a que diz respeito. Poderá ainda exportar a informação relativa a todos os meses de determinado ano, se tal lhe for solicitado por um agente da Inspeção Tributária.</p>

                            <div class="row">
                                <div class="col-md-7 offset-md-3 my-5">
                                    <div class="form-group row mb-3">
                                        <label for="" class="col-12 col-md-2">Data Inicio</label>
                                        <input type="date" name="data_inicio" id="data_inicio" class="form-control col-12 col-md-10" required>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label for="" class="col-12 col-md-2">Data Final</label>
                                        <input type="date" name="data_final" id="data_final" class="form-control col-12 col-md-10" required>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer text-center">
                            <button type="submit" class="btn-lg btn-primary">Descarregar ss</button>
                        </div>
                    </div>
                </div>
            </form>

        </div><!-- /.container-fluid -->
    </div>
    
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
