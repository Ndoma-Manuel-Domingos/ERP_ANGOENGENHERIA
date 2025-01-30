@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Adicionar Aluno á Turma</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('alunos.show', $aluno->id ?? "") }}">Voltar</a></li>
            <li class="breadcrumb-item active">Aluno</li>
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
        <form action="{{ route('turma-adicionar-aluno-store') }}" method="post" class="">
          @csrf
          <div class="card-body row">
            <div class="col-12 col-md-4">
             
              <label for="" class="form-label">Aluno</label>
            
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <select type="text" class="form-control select2" name="aluno_id">
                  @foreach ($alunos as $item)
                  <option value="{{ $item->id }}" {{ $aluno->id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                  @endforeach
                </select>
              </div>
              <p class="text-danger">
                @error('aluno_id')
                {{ $message }}
                @enderror
              </p>
            </div>
            
            
            <div class="col-12 col-md-4">
              <label for="" class="form-label">Ficha Matrícula</label>
            
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <select type="text" class="form-control select2" name="matricula_id">
                  @foreach ($matriculas as $item)
                  <option value="{{ $item->id }}">{{ $item->numero }} - Curso: {{ $item->curso->nome }} - Turno: {{ $item->turno->nome }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            
            <div class="col-12 col-md-4">
              <label for="" class="form-label">Turma</label>
            
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <select type="text" class="form-control select2" name="turma_id">
                  @foreach ($turmas as $item)
                  <option value="{{ $item->id }}">{{ $item->nome }} - Curso: {{ $item->curso->nome }} - Turno: {{ $item->turno->nome }}</option>
                  @endforeach
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