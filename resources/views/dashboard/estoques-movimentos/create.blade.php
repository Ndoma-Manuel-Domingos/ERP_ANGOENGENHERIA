@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Actualizar Stock</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('estoques.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Stock</li>
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
            <form action="{{ route('estoques.store') }}" method="post" class="">
              @csrf
              <div class="card-body row">
    
                <div class="col-12">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <select type="text" class="form-control" name="loja_id">
                      <option value="">Selecione uma Loja [Armazém]</option>
                      @if ($lojas)
                          @foreach ($lojas as $item)
                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                          @endforeach
                      @endif
                    </select>
                  </div>
                  <p class="text-danger">
                    @error('loja_id')
                    {{ $message }}
                    @enderror
                  </p>
                </div>
    
    
                <div class="col-4">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <select type="text" class="form-control" name="produto_id">
                      <option value="">Selecione o Produto</option>
                      @if ($produtos)
                          @foreach ($produtos as $item2)
                            <option value="{{ $item2->id }}">{{ $item2->nome }}</option>
                          @endforeach
                      @endif
                    </select>
                  </div>
                  <p class="text-danger">
                    @error('produto_id')
                    {{ $message }}
                    @enderror
                  </p>
                </div>
    
                <div class="col-3">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control" name="stock" value="{{ old('stock') }}"
                      placeholder="Informe uma Quantidade">
                  </div>
                  <p class="text-danger">
                    @error('stock')
                    {{ $message }}
                    @enderror
                  </p>
                </div>
    
                <div class="col-3">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <select type="text" class="form-control" name="operacao">
                      {{-- <option value="activo">Selecione a Operação</option> --}}
                      <option value="Entrada de Stock">Entrada de Stock</option>
                      <option value="Saída de Stock">Saída de Stock</option>
                      <option value="Actualizar de Stock">Actualizar de Stock</option>
                    </select>
                  </div>
                  <p class="text-danger">
                    @error('operacao')
                    {{ $message }}
                    @enderror
                  </p>
                </div>
    
                <div class="col-2">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control" name="observacao" value="{{ old('observacao') }}"
                      placeholder="Observação">
                  </div>
                  <p class="text-danger">
                    @error('observacao')
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
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection