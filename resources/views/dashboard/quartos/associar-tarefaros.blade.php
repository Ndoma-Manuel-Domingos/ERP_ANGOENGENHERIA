@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Associar o Quarto <a href="{{ route('quartos.show', $quarto->id) }}">{{ $quarto->nome }}</a></h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('quartos.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Tarifário</li>
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
            <form action="{{ route('quartos.associar_tarefario_store') }}" method="post" class="">
              @csrf
              <div class="card-body row">
               
                <div class="col-12 col-md-12">
                  <label for="tarefario_id" class="form-label">Tarifários</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <select type="text" class="form-control select2 @error('tarefario_id') is-invalid @enderror" multiple id="tarefario_id" name="tarefario_id[]">
                      <option value="">Escolher</option>
                      @foreach ($tarefarios as $item)
                      <option value="{{ $item->id }}">{{ $item->nome }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                
                <input type="hidden" name="quarto_id" value="{{ $quarto->id }}">
                
              </div>
              
    
              <div class="card-footer">
                @if (Auth::user()->can('criar andar'))
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