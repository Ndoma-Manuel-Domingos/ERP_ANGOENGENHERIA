@extends('layouts.formadores')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Conteúdos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-formadores') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Conteúdos</li>
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
                <div class="col-12 col-md-12">
                    <form action="{{ route('formadores-videos.conteudo') }}" method="GET">
                        @csrf
                        <div class="card">
                            <div class="card-body row">
                                <div class="col-12 col-md-4">
                                    <label for="modulo_id" class="form-label">Modulos</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="form-control @error('record') is-invalid @enderror" id="modulo_id" name="modulo_id">
                                            <option value="">Todos</option>
                                            @foreach ($modulos as $item)
                                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-4">
                                    <label for="data_inicio" class="form-label">Data Inicio</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="date" class="form-control @error('data_inicio') is-invalid @enderror" id="data_inicio" name="data_inicio" value="{{ old('data_inicio') }}" placeholder="Informe">
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-4">
                                    <label for="data_final" class="form-label">Data Final</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="date" class="form-control @error('data_final') is-invalid @enderror" id="data_final" name="data_final" value="{{ old('data_final') }}" placeholder="Informe">
                                    </div>
                                </div>
                                
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Pesquisar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('formadores-videos.create-conteudo') }}" class="btn-primary btn">Novos Conteúdos</a>
                        </div>
                        @foreach ($uploads as $upload)
                            <embed src="{{ asset('videos/' . $upload->arquivo) }}" type="application/pdf" width="100%" height="200">
                            <div class="card-body">
                                <div>
                                    <h4>{{ $upload->nome }}</h4>
                                    <h5>TURMA: <strong>{{ $upload->turma ? $upload->turma->nome : "" }}</strong> | FORNECEDOR: <strong>{{ $upload->formador ? $upload->formador->nome : "Sem formador Selecionado" }}</strong></h5>
                                </div>
                                <p>{{ $upload->descricao }}</p>
                            </div>
                            <div class="card-footer mb-4">
                                <a href="{{ route('videos.conteudo-eliminar', $upload->id) }}" class="btn-sm btn-danger"><i class="fas fa-trash"></i> ELIMINAR</a>
                            </div>
                        @endforeach
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
