@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Cadastrar Turma</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('turmas.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Turmas</li>
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
        <form action="{{ route('turmas.store') }}" method="post" class="">
          @csrf
          <div class="card-body row">
            <div class="col-12 col-md-6">
              <label for="nome" class="form-label">Turma</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome') }}"
                  placeholder="Informe a Turma">
              </div>
              <p class="text-danger">
                @error('nome')
                {{ $message }}
                @enderror
              </p>
            </div>
            
            <div class="col-12 col-md-3">
              <label for="" class="form-label">Curso</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <select type="text" class="form-control select2" name="curso_id">
                  @foreach ($cursos as $item)
                  <option value="{{ $item->id }}">{{ $item->nome }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            
            <div class="col-12 col-md-3">
              <label for="preco" class="form-label">Preço</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <input type="text" class="form-control" id="preco" name="preco" value="{{ old('preco') }}"
                  placeholder="Informe o preço">
              </div>
              <p class="text-danger">
                @error('preco')
                {{ $message }}
                @enderror
              </p>
            </div>
            
            
            <div class="col-12 col-md-6">
              <label for="" class="form-label">Turno</label>
            
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <select type="text" class="form-control select2" name="turno_id">
                  @foreach ($turnos as $item)
                  <option value="{{ $item->id }}">{{ $item->nome }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            
            <div class="col-12 col-md-6">
              <label for="" class="form-label">Sala</label>
            
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <select type="text" class="form-control select2" name="sala_id">
                  @foreach ($salas as $item)
                  <option value="{{ $item->id }}">{{ $item->nome }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            
            <div class="col-12 col-md-6">
              <label for="" class="form-label">Ano Lectivo</label>
            
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <select type="text" class="form-control select2" name="ano_lectivo_id">
                  @foreach ($anos_lectivos as $item)
                  <option value="{{ $item->id }}">{{ $item->nome }}</option>
                  @endforeach
                </select>
              </div>
            </div>


            <div class="col-12 col-md-6">
              <label for="" class="form-label">Estado</label>
            
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <select type="text" class="form-control select2" name="status">
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
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection