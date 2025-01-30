@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Cadastrar Permissão</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('permissoes.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Permissão</li>
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
          <div class="card">
            <form action="{{ route('permissoes.store') }}" method="post" class="">
              @csrf
              <div class="card-body row">
                <div class="col-12 col-md-12">
                  <label for="permission" class="form-label">Designação</label>
                  <input type="text" id="permission" class="form-control" name="permission" value="{{ old('permission') }}" placeholder="Informe a Permissão">
                  <p class="text-danger">
                    @error('permission')
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
        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection