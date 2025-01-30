@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Editar Consulta</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('consultas.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Consulta</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
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
      <div class="card">
        <form action="{{ route('consultas.update', $consulta->id) }}" method="post" class="">
          @csrf
          @method('put')
          <div class="card-body row">

            <div class="col-12 col-md-12">
              <label for="" class="form-label">Selecionar Consulta</label>
            
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <select type="text" class="form-control select2" name="consulta_id">
                  <option value="">Selecionar a Consulta</option>
                  @foreach ($produtos as $item)
                  <option value="{{ $item->id }}" {{ $item->id == $consulta->consulta_id ? 'selected' : ''  }}>{{ $item->nome }}</option>
                  @endforeach
                </select>
              </div>
              <p class="text-danger">
                @error('consulta_id')
                {{ $message }}
                @enderror
              </p>
            </div>
            
            <div class="col-12 col-md-12">
              <label for="" class="form-label">Selecionar Pacientes</label>
            
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <select type="text" class="form-control select2" name="paciente_id">
                  <option value="">Selecionar a Pacientes</option>
                  @foreach ($pacientes as $item)
                  <option value="{{ $item->id }}" {{ $item->id == $consulta->paciente_id ? 'selected' : ''  }}>{{ $item->nome }}</option>
                  @endforeach
                </select>
              </div>
              <p class="text-danger">
                @error('paciente_id')
                {{ $message }}
                @enderror
              </p>
            </div>
            
            <div class="col-12 col-md-12">
              <label for="" class="form-label">Selecionar Médico</label>
            
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <select type="text" class="form-control select2" name="medico_id">
                  <option value="">Selecionar a Médico</option>
                  @foreach ($medicos as $item)
                  <option value="{{ $item->id }}" {{ $item->id == $consulta->medico_id ? 'selected' : ''  }}>{{ $item->nome }}</option>
                  @endforeach
                </select>
              </div>
              <p class="text-danger">
                @error('medico_id')
                {{ $message }}
                @enderror
              </p>
            </div>
            
            <div class="col-12 col-md-12">
             
              <label for="" class="form-label">Data da Consulta</label>
            
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <input type="date" class="form-control" name="data_consulta" value="{{ $consulta->data_consulta ?? old('data_consulta') }}"
                  placeholder="Informe a Data da consulta">
              </div>
              <p class="text-danger">
                @error('data_consulta')
                {{ $message }}
                @enderror
              </p>
            </div>
            
            <div class="col-12 col-md-12">
              <label for="" class="form-label">Hora da Consulta</label>
            
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <input type="time" class="form-control" name="hora_consulta" value="{{ $consulta->hora_consulta ?? old('hora_consulta') }}"
                  placeholder="Informe a hora da consulta">
              </div>
              <p class="text-danger">
                @error('hora_consulta')
                {{ $message }}
                @enderror
              </p>
            </div>
            
            <div class="col-12 col-md-12">
              <label for="" class="form-label">Observação(opcional)</label>
            
              <div class="input-group mb-3">
                <textarea name="observacao" class="form-control" id="" cols="30" rows="6" placeholder="Descrever um Observação opcional">{{ $consulta->observacao }}</textarea>
              </div>
              <p class="text-danger">
                @error('observacao')
                {{ $message }}
                @enderror
              </p>
            </div>

            
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <button type="reset" class="btn btn-danger">Cancelar</button>
          </div>
        </form>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection