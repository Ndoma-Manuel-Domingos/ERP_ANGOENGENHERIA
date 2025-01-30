@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Cadastrar Utilizador</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('utilizadores.create') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Utilizadores</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="card">
        <form action="{{ route('utilizadores.store') }}" method="post" class="">
          @csrf
          <div class="card-body">
            <div class="row">
              <div class="col-12 col-md-6 mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome') }}" placeholder="Informe a Nome">
                <p class="text-danger">
                  @error('nome')
                  {{ $message }}
                  @enderror
                </p>
              </div>
  
              <div class="col-12 col-md-6 mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Informe a Email">
                <p class="text-danger">
                  @error('email')
                  {{ $message }}
                  @enderror
                </p>
              </div>
  
              <div class="col-12 col-md-6 mb-3">
                <label for="email" class="form-label">Perfil</label>
                <select type="text" id="roles" class="form-control select2" name="roles">
                    @foreach ($roles as $item)
                      <option value="{{ $item->id }}">{{ $item->name }}</option>    
                    @endforeach
                </select>
              </div>
  
              <div class="col-12 col-md-6 mb-3">
                <label for="status" class="form-label">Estado</label>
                <select type="text" class="form-control select2" id="status" name="status">
                    <option value="1">Activo</option>
                    <option value="0">Desactivo</option>
                </select>
              </div>
  
              <div class="col-12 col-md-6 mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" id="password" class="form-control" name="password" value="{{ old('password') }}" placeholder="Informe a Senha">
                <p class="text-danger">
                  @error('password')
                  {{ $message }}
                  @enderror
                </p>
              </div>
  
              <div class="col-12 col-md-6 mb-3">
                <label for="password_r" class="form-label">Repetir Senha</label>
                <input type="password" id="password_r" class="form-control" name="password_r" value="{{ old('password_r') }}" placeholder="Informe Repetir a Senha">
                <p class="text-danger">
                  @error('password_r')
                  {{ $message }}
                  @enderror
                </p>
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
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection