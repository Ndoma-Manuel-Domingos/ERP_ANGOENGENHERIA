@extends('layouts.alunos')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Actualizar as credenciais</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="card">
        <form action="{{ route('alunos-privacidade-store') }}" method="post" class="">
          @csrf
          <div class="card-body row">
          
            <div class="col-12">
                <label for="">Senha actual</label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                  </div>
                  <input type="password" class="form-control" name="senha"
                    placeholder="Informe a senha actual">
                </div>
                <p class="text-danger">
                  @error('senha')
                  {{ $message }}
                  @enderror
                </p>
            </div>
          
            <div class="col-12">
                <label for="">Nova Senha</label>
                
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="password" class="form-control" name="nova_senha"
                      placeholder="Informe a Nova Senha">
                </div>
                <p class="text-danger">
                    @error('nova_senha')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            
            <div class="col-12">
                <label for="">Confirmar Senha</label>
                
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="password" class="form-control" name="confirmar_senha"
                      placeholder="Informe a Nova Senha">
                </div>
                <p class="text-danger">
                    @error('confirmar_senha')
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