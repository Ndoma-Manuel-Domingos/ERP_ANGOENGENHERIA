@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Editar Lojas</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('lojas.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">AGT</li>
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
            <form action="{{ route('lojas.update', $loja->id) }}" method="post" class="">
              @csrf
              @method('put')
              <div class="card-body row">
                <div class="col-12">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control" name="nome" value="{{ $loja->nome }}"
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
                        <option value="activo" {{ $loja->status == "activo" ? 'selected' : '' }}>Activo</option>
                        <option value="desactivo" {{ $loja->status == "desactivo" ? 'selected' : '' }}>Desactivo</option>
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
                    <textarea class="form-control" rows="2" name="descricao"  placeholder="Informe a descricao da Loja ...">{{ $loja->descricao }}</textarea>
                  </div>
                  <p class="text-danger">
                    @error('descricao')
                    {{ $message }}
                    @enderror
                  </p>
                </div>
    
    
                <div class="col-12">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control" name="codigo_postal" value="{{ $loja->codigo_postal }}"
                      placeholder="Informe o Codigo Postal">
                  </div>
                  <p class="text-danger">
                    @error('codigo_postal')
                    {{ $message }}
                    @enderror
                  </p>
                </div>
    
    
                <div class="col-12">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control" name="morada" value="{{ $loja->morada }}"
                      placeholder="Informe a morada da Loja">
                  </div>
                  <p class="text-danger">
                    @error('morada')
                    {{ $message }}
                    @enderror
                  </p>
                </div>
    
    
                <div class="col-12">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control" name="localidade" value="{{ $loja->localidade }}"
                      placeholder="Informe a Localidade da Loja">
                  </div>
                  <p class="text-danger">
                    @error('localidade')
                    {{ $message }}
                    @enderror
                  </p>
                </div>
    
    
                <div class="col-12">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control" name="telefone" value="{{ $loja->telefone }}"
                      placeholder="Informe o Telefone">
                  </div>
                  <p class="text-danger">
                    @error('telefone')
                    {{ $message }}
                    @enderror
                  </p>
                </div>
    
    
                <div class="col-12">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control" name="email" value="{{ $loja->email }}"
                      placeholder="Informe o E-mail">
                  </div>
                  <p class="text-danger">
                    @error('email')
                    {{ $message }}
                    @enderror
                  </p>
                </div>
    
                <div class="col-12">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control" name="cae" value="{{ $loja->cae }}"
                      placeholder="Informe o CAE">
                  </div>
                  <p>Código CAE que será associado aos documentos emitidos nesta loja - se não for preenchido, será assumido o CAE da empresa (se indicado)</p>
                  <p class="text-danger">
                    @error('cae')
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