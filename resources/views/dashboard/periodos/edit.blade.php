@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Editar Exercício</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('periodos.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Exercício</li>
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
            <form action="{{ route('periodos.update', $periodo->id) }}" method="post" class="">
              @csrf
              @method('put')
              <div class="card-body row">
                <div class="col-12 col-md-3">
                  <label for="nome" class="form-label">Designação</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" value="{{ $periodo->nome }}"
                      placeholder="Informe a Exercício">
                  </div>
                </div>
                
                <div class="col-12 col-md-3">
                  <label for="inicio" class="form-label">Data Início</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="date" class="form-control @error('inicio') is-invalid @enderror" name="inicio" id="inicio" value="{{ $periodo->inicio ?? old('inicio') }}"
                      placeholder="Informe a data ínicio">
                  </div>
                </div>
                
                <div class="col-12 col-md-3">
                  <label for="final" class="form-label">Data Final</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="date" class="form-control @error('final') is-invalid @enderror" name="final" id="final" value="{{ $periodo->final ?? old('final') }}"
                      placeholder="Informe a data final">
                  </div>
                </div>
    
                <div class="col-12 col-md-3">
                  <label for="status" class="form-label">Estado</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <select type="text" class="form-control @error('status') is-invalid @enderror" name="status">
                        <option value="activo" {{ $periodo->status == "activo" ? 'selected' : '' }}>Activo</option>
                        <option value="desactivo" {{ $periodo->status == "desactivo" ? 'selected' : '' }}>Desactivo</option>
                    </select>
                  </div>
                </div>
                
                
                <div class="col-12 col-md-3">
                  <label for="exercicio_id" class="form-label">Exercícios</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <select type="text" class="form-control @error('exercicio_id') is-invalid @enderror" id="exercicio_id" name="exercicio_id">
                        <option value="activo">Selecione</option>
                        @foreach ($exercicios as $item)
                        <option value="{{ $item->id }}" {{ $item->id == $periodo->exercicio_id ? 'selected' : '' }}>{{ $item->nome }}</option>
                        @endforeach
                    </select>
                  </div>
                </div>
                
                <div class="col-12 col-md-3">
                  <label for="mes_processamento" class="form-label">Mês Processamento</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control  @error('mes_processamento') is-invalid @enderror" name="mes_processamento" id="mes_processamento" value="{{ $periodo->mes_processamento ?? old('mes_processamento') }}" placeholder="Informe Mês Processamento">
                  </div>
                </div>
                
                <div class="col-12 col-md-3">
                  <label for="dias_uteis" class="form-label">Dias uteis</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control  @error('dias_uteis') is-invalid @enderror" name="dias_uteis" id="dias_uteis" value="{{ $periodo->dias_uteis ?? old('dias_uteis') }}" placeholder="Informe dias uteis">
                  </div>
                </div>
                
                <div class="col-12 col-md-3">
                  <label for="dias_fixo" class="form-label">Dias Fixo</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control  @error('dias_fixo') is-invalid @enderror" name="dias_fixo" id="dias_fixo" value="{{ $periodo->dias_fixo ?? old('dias_fixo') }}" placeholder="Informe dias fixos">
                  </div>
                </div>
                
              </div>
    
              <div class="card-footer">
                @if (Auth::user()->can('editar periodo'))
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