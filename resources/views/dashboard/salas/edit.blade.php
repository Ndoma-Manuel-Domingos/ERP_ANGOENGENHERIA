@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Editar Sala</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('salas.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">sala</li>
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
        <form action="{{ route('salas.update', $sala->id) }}" method="post" class="">
          @csrf
          @method('put')
          <div class="card-body row">
            <div class="col-12">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <input type="text" class="form-control" name="nome" value="{{ $sala->nome }}"
                  placeholder="Informe a nome">
              </div>
              <p class="text-danger">
                @error('nome')
                {{ $message }}
                @enderror
              </p>
            </div>

            <div class="col-12">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <select type="text" class="form-control" name="status">
                    <option value="activo" {{ $sala->status == "activo" ? 'selected' : '' }}>Activo</option>
                    <option value="desactivo" {{ $sala->status == "desactivo" ? 'selected' : '' }}>Desactivo</option>
                </select>
              </div>
              <p class="text-danger">
                @error('status')
                {{ $message }}
                @enderror
              </p>
            </div>

            <div class="col-12">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <select type="text" class="form-control" name="solicitar_ocupacao">
                  <option value="">Solicitar ocupação</option>
                  <option value="1" {{ $sala->solicitar_ocupacao == "1" ? 'selected' : '' }}>Sim</option>
                  <option value="0" {{ $sala->solicitar_ocupacao == "0" ? 'selected' : '' }}>Não</option>
                </select>
              </div>
              <p class="text-danger">
                @error('solicitar_ocupacao')
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