@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Novo Processamento</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('processamentos.index') }}">Voltar</a></li>
              <li class="breadcrumb-item active">Processamentos</li>
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
                  <form action="{{ route('processamentos.store') }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-body row">
                            <div class="col-12 col-md-2">
                                <label for="processamento_id" class="form-label">Tipo Processamento</label>
                                <select type="text" class="form-control select2 @error('processamento_id') is-invalid @enderror" id="processamento_id" name="processamento_id">
                                    <option value="">Selecione</option>
                                    @foreach ($tipo_processamentos as $item)
                                    <option value="{{ $item->id }}"> - {{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-2">
                                <label for="exercicio_id" class="form-label">Exercícios</label>
                                <select type="text" class="form-control select2 @error('exercicio_id') is-invalid @enderror" id="exercicio_id" name="exercicio_id">
                                    <option value="">Selecione</option>
                                    @foreach ($exercicios as $item)
                                    <option value="{{ $item->id }}"> - {{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-2">
                                <label for="periodo_id" class="form-label">Perídos</label>
                                <select type="text" class="form-control select2 @error('periodo_id') is-invalid @enderror" id="periodo_id" name="periodo_id">
                                    <option value="">Selecione</option>
                                    @foreach ($periodos as $item)
                                    <option value="{{ $item->id }}"> - {{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-2">
                                <label for="data_inicio" class="form-label">Data Inicio</label>
                                <div class="input-group mb-3">
                                    <input type="date" class="form-control @error('data_inicio') is-invalid @enderror" value="" id="data_inicio" name="data_inicio" placeholder="Data Inicio">
                                </div>
                            </div>

                            <div class="col-12 col-md-2">
                                <label for="data_final" class="form-label">Data Final</label>
                                <div class="input-group mb-3">
                                    <input type="date" class="form-control @error('data_final') is-invalid @enderror" value="" id="data_final" name="data_final" placeholder="Data final">
                                </div>
                            </div>

                            <div class="col-12 col-md-2">
                                <label for="dias_processados" class="form-label">Dias processados</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control @error('dias_processados') is-invalid @enderror" value="22" name="dias_processados" id="dias_processados" placeholder="Dias processados">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            {{-- @if (Auth::user()->can('criar subsidio')) --}}
                            <button type="submit" class="btn btn-primary">Processar</button>
                            {{-- @endif --}}
                        </div>
                    </div>
                  </form>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    </div>
    <!-- /.content-wrapper -->

    @endsection
    @section('scripts')
    <script>
    
        $("#exercicio_id").change(() => {
            let id = $("#exercicio_id").val();
            $.get('../carregar-periodos/' + id, function(data) {
                $("#periodo_id").html("")
                $("#periodo_id").html(data)
            })
        })

    </script>
    @endsection