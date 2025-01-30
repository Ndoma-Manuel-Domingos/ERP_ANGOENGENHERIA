@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Registro de Compras de Produto</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('registros-compras-produtos.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Produto</li>
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
            <form action="{{ route('registros-compras-produtos.store') }}" method="post" class="">
              @csrf
              <div class="card-body row">
                                      
                <div class="col-12 col-md-4">
                  <label for="produto_id" class="form-label">Produtos</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <select type="text" class="form-control select2 @error('produto_id') is-invalid @enderror" id="produto_id" name="produto_id">
                        <option value="activo">Selecione</option>
                        @foreach ($produtos as $item)
                        <option value="{{ $item->id }}">{{ $item->nome }}</option>
                        @endforeach
                    </select>
                  </div>
                </div>
              
                <div class="col-12 col-md-4">
                  <label for="quantidade" class="form-label">Quantidade</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control  @error('quantidade') is-invalid @enderror" name="quantidade" id="quantidade" value="{{ old('quantidade') }}" placeholder="Informe a quantidade">
                  </div>
                </div>
              
                <div class="col-12 col-md-4">
                  <label for="valor_pago" class="form-label">Valor Pago</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control  @error('valor_pago') is-invalid @enderror" name="valor_pago" id="valor_pago" value="{{ old('valor_pago') }}" placeholder="Informe o Valor Pago">
                  </div>
                </div>
       
   
              </div>
              
    
              <div class="card-footer">
                @if (Auth::user()->can('criar produtos'))
                <button type="submit" class="btn btn-primary">Salvar</button>
                @endif
                <button type="reset" class="btn btn-danger">Cancelar</button>
              </div>
            </form>
          </div>
        </div>
        
        <div class="col-12 col-md-12">
          <div class="card">
              <div class="card-header">
                  <div class="card-tools">
                      <a class="btn btn-sm btn-danger" target="_blink" href="{{ route('pdf-registros-compras-produtos', ['data_inicio' => date("Y-m-d"), 'data_final' =>  date("Y-m-d")]) }}"><i class="fas fa-file-pdf"></i>  IMPRIMIR REGISTROS</a>
                  </div>
              </div>

              @if ($registros)
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                  <table class="table table-hover text-nowrap">
                      <thead>
                          <tr>
                              <th>#</th>
                              <th>Nome</th>
                              <th>Quantidade</th>
                              <th>Total Pago</th>
                              <th><span class="float-right">Acções</span></th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($registros as $item)
                          <tr>
                              <td>{{ $item->id }}</td>
                              <td>{{ $item->produto->nome ?? "" }}</td>
                              <td>{{ $item->quantidade ?? "" }}</td>
                              <td>{{ number_format($item->valor_pago ?? 0, 2, ',', '.') }}</td>

                              <td class="text-right">
                                  <div class="btn-group">
                                      <button type="button" class="btn btn-default">Ações</button>
                                      <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                          <span class="sr-only">Toggle Dropdown</span>
                                      </button>
                                      <div class="dropdown-menu" role="menu">
                                          <a class="dropdown-item" href="{{ route('registros-compras-produtos.show', $item->id) }}"><i class="fas fa-eye text-info"></i> Detalhes</a>
                                          <a class="dropdown-item" href="{{ route('registros-compras-produtos.edit', $item->id) }}"><i class="fas fa-edit text-success"></i> Editar</a>
                                          <div class="dropdown-divider"></div>
                                          <form action="{{ route('registros-compras-produtos.destroy', $item->id ) }}" method="post">
                                              @csrf
                                              @method('DELETE')
                                              <button type="submit" class="btn btn-sm btn-danger dropdown-item" onclick="return confirm('Tens Certeza que Desejas excluir esta Produto?')">
                                                  <i class="fas fa-trash text-danger"></i> Eliminar
                                              </button>
                                          </form>
                                      </div>
                                  </div>
                              </td>

                          </tr>
                          @endforeach

                      </tbody>
                  </table>
              </div>
              @endif

          </div>
          <!-- /.card -->
      </div>
        
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection