@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Editar Tarifário</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('tarefarios.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Tarifário</li>
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

                <div class="col-12 col-md-12">
                    <div class="card">
                        <form action="{{ route('tarefarios.update', $tarefario->id) }}" method="post" class="">
                            @csrf
                            @method('put')
                            <div class="card-body row">

                                <div class="col-12 col-md-6">
                                    <label for="nome" class="form-label">Designação</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" value="{{ $tarefario->nome ?? old('nome') }}" placeholder="Informe a Conta">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="valor" class="form-label">Valor</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control  @error('valor') is-invalid @enderror" name="valor" id="valor" value="{{ $tarefario->valor ?? old('valor') }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="modo_tarefario" class="form-label">Modo de Tarifário</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="form-control @error('modo_tarefario') is-invalid @enderror" id="modo_tarefario" name="modo_tarefario">
                                            <option value="">Activo</option>
                                            <option value="Por Minutos" {{ $tarefario->modo_tarefario == "Por Minutos" ? 'selected' : '' }}>Por Minutos</option>
                                            <option value="Por Hora" {{ $tarefario->modo_tarefario == "Por Hora" ? 'selected' : '' }}>Por Hora</option>
                                            <option value="Por Dia" {{ $tarefario->modo_tarefario == "Por Dia" ? 'selected' : '' }}>Por Dia</option>
                                            <option value="Por Semana" {{ $tarefario->modo_tarefario == "Por Semana" ? 'selected' : '' }}>Por Semana</option>
                                            <option value="Por Quizena" {{ $tarefario->modo_tarefario == "Por Quizena" ? 'selected' : '' }}>Por Quizena</option>
                                            <option value="Por Mes" {{ $tarefario->modo_tarefario == "Por Mes" ? 'selected' : '' }}>Por Mês</option>
                                            <option value="Por Ano" {{ $tarefario->modo_tarefario == "Por Ano" ? 'selected' : '' }}>Por Ano</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="tipo_cobranca" class="form-label">Tipo Cobrança</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="form-control @error('tipo_cobranca') is-invalid @enderror" id="tipo_cobranca" name="tipo_cobranca">
                                            <option value="">Escolher</option>
                                            <option value="Por Comodo" {{ $tarefario->tipo_cobranca == "Por Comodo" ? 'selected' : '' }}>Por Comodo</option>
                                            <option value="Por Pessoa" {{ $tarefario->tipo_cobranca == "Por Pessoa" ? 'selected' : '' }}>Por Pessoa</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="status" class="form-label">Estado</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                            <option value="activo" {{ $tarefario->status == "activo" ? 'selected' : '' }}>Activo</option>
                                            <option value="desactivo" {{ $tarefario->status == "desactivo" ? 'selected' : '' }}>Desactivo</option>
                                        </select>
                                    </div>
                                </div>


                            </div>

                            <div class="card-footer">
                                @if (Auth::user()->can('editar andar'))
                                <button type="submit" class="btn btn-primary">Salvar</button>
                                @endif
                                <button type="reset" class="btn btn-danger">Cancelar</button>
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
