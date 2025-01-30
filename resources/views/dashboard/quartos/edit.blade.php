@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Editar Quarto</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('quartos.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Quarto</li>
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
            <form action="{{ route('quartos.update', $quarto->id) }}" method="post" class="">
              @csrf
              @method('put')
              <div class="card-body row">
              
                <div class="col-12 col-md-3">
                  <label for="nome" class="form-label">Designação</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" value="{{ $quarto->nome }}"
                      placeholder="Informe a Conta">
                  </div>
                </div>
                
                <div class="col-12 col-md-3">
                  <label for="capacidade" class="form-label">Capacidade</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control  @error('capacidade') is-invalid @enderror" name="capacidade" id="capacidade" value="{{ old('capacidade') }}" placeholder="Informe a capacidade">
                  </div>
                </div>
             
                
                
                <div class="col-12 col-md-3">
                  <label for="solicitar_ocupacao" class="form-label">Ocupação</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <select type="text" class="form-control @error('solicitar_ocupacao') is-invalid @enderror" id="solicitar_ocupacao" name="solicitar_ocupacao">
                        <option value="LIVRE" {{ $quarto->solicitar_ocupacao == "LIVRE" ? 'selected' : '' }}>LIVRE</option>
                        <option value="OCUPADA" {{ $quarto->solicitar_ocupacao == "OCUPADA" ? 'selected' : '' }}>OCUPADA</option>
                        <option value="RESERVADA" {{ $quarto->solicitar_ocupacao == "RESERVADA" ? 'selected' : '' }}>RESERVADA</option>
                        <option value="MANUTEÇÃO" {{ $quarto->solicitar_ocupacao == "MANUTEÇÃO" ? 'selected' : '' }}>MANUTEÇÃO</option>
                    </select>
                  </div>
                </div>
          
                
                <div class="col-12 col-md-3">
                  <label for="status" class="form-label">Estado</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <select type="text" class="form-control @error('status') is-invalid @enderror" name="status">
                        <option value="activo" {{ $quarto->status == "activo" ? 'selected' : '' }}>Activo</option>
                        <option value="desactivo" {{ $quarto->status == "desactivo" ? 'selected' : '' }}>Desactivo</option>
                    </select>
                  </div>
                </div>
                
                            
                <div class="col-12 col-md-3">
                  <label for="tipo_id" class="form-label">Tipo de Quarto</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <select type="text" class="form-control @error('tipo_id') is-invalid @enderror" id="tipo_id" name="tipo_id">
                      @foreach ($tipos as $k => $v)
                      <option value="{{ $v->id }}" {{ $quarto->tipo_id == $v->id ? 'selected' : '' }}>{{ $v->nome }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                
                <div class="col-12 col-md-3">
                  <label for="andar_id" class="form-label">Andares</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <select type="text" class="form-control @error('andar_id') is-invalid @enderror" id="andar_id" name="andar_id">
                      @foreach ($andares as $k => $v)
                      <option value="{{ $v->id }}" {{ $quarto->andar_id == $v->id ? 'selected' : '' }}>{{ $v->nome }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                
                
                <div class="col-12 col-md-3">
                  <label for="descricao" class="form-label">Descrição</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control  @error('descricao') is-invalid @enderror" name="descricao" id="descricao" value="{{ $quarto->descricao ?? old('descricao') }}" placeholder="Informe a Descrição do quarto">
                  </div>
                </div>
                
                
                
              </div>
    
              <div class="card-footer">
                @if (Auth::user()->can('editar quarto'))
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