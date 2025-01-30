@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Cadastrar Ano Lectivo</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('anos-lectivos.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Ano Lectivo</li>
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
            <form action="{{ route('anos-lectivos.store') }}" method="post" class="">
              @csrf
              <div class="card-body row">
                <div class="col-12 col-md-6">
                 
                  <label for="nome" class="form-label">Ano Lectivo</label>
                
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text"id="nome" class="form-control" name="nome" value="{{ old('nome') }}"
                      placeholder="Informe a turno">
                  </div>
                  <p class="text-danger">
                    @error('nome')
                    {{ $message }}
                    @enderror
                  </p>
                </div>
                
                <div class="col-12 col-md-6">
                  <label for="sigla" class="form-label">Sigla</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control" name="sigla" value="{{ old('sigla') }}"
                      placeholder="Informe a Sigla">
                  </div>
                  <p class="text-danger">
                    @error('sigla')
                    {{ $message }}
                    @enderror
                  </p>
                </div>
                
                <div class="col-12 col-md-3">
                 
                  <label for="" class="form-label">Data Inicio</label>
                
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="date" class="form-control" name="data_inicio" value="{{ old('data_inicio') }}"
                      placeholder="Informe a data inicio">
                  </div>
                  <p class="text-danger">
                    @error('data_inicio')
                    {{ $message }}
                    @enderror
                  </p>
                </div>
                
                <div class="col-12 col-md-3">
                 
                  <label for="" class="form-label">Data Final</label>
                
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="date" class="form-control" name="data_final" value="{{ old('data_final') }}"
                      placeholder="Informe a data final">
                  </div>
                  <p class="text-danger">
                    @error('data_final')
                    {{ $message }}
                    @enderror
                  </p>
                </div>
    
                <div class="col-12 col-md-6">
                  <label for="" class="form-label">Estado</label>
                
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <select type="text" class="form-control" name="status">
                        <option value="activo">Activo</option>
                        <option value="desactivo">Desactivo</option>
                    </select>
                  </div>
                </div>
              </div>
    
              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <button type="reset" class="btn btn-danger">Cancelar</button>
              </div>
            </form>
          </div>
          <!-- /.row -->
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection