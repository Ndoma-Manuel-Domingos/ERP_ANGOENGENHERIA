@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Cadastrar Classe</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Classe</li>
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
            <form action="{{ route('classes.store') }}" method="post" class="">
              @csrf
              <div class="card-body row">
              
                <div class="col-12 col-md-3">
                  <label for="nome" class="form-label">Designação</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control  @error('nome') is-invalid @enderror" name="nome" id="nome" value="{{ old('nome') }}" placeholder="Informe a Classe">
                  </div>
                </div>
                
                <div class="col-12 col-md-3">
                  <label for="conta" class="form-label">Número</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control @error('conta') is-invalid @enderror" name="conta" id="conta" value="{{ old('conta') }}"
                      placeholder="Informe a classe">
                  </div>
                </div>
                
                <div class="col-12 col-md-3">
                  <label for="sigla" class="form-label">Sigla</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control @error('sigla') is-invalid @enderror" name="sigla" id="sigla" value="{{ old('sigla') }}"
                      placeholder="Informe uma sigla">
                  </div>
                </div>
    
                <div class="col-12 col-md-3">
                  <label for="status" class="form-label">Estado</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <select type="text" class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="activo">Activo</option>
                        <option value="desactivo">Desactivo</option>
                    </select>
                  </div>
                </div>
                
              </div>
              
    
              <div class="card-footer">
                @if (Auth::user()->can('criar classe'))
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