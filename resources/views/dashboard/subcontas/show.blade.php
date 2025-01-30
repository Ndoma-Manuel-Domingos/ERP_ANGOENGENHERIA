@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Detalhe da Subconta</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('subcontas.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Subcontas</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <!-- /.row -->
      <div class="row">
        <div class="col-12">
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
        <div class="col-12">
          <div class="card">
            @if ($subconta)
            <!-- /.card-header -->
            <div class="card-body table-responsive">
              <table class="table table-hover text-nowrap">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Conta</th>
                    <th>Numero</th>
                    <th>Designação</th>
                    <th>Tipo Conta</th>
                    <th>Estado</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    
                    <td>{{ $subconta->id }}</td>
                    <td>{{ $subconta->conta->conta ?? '' }}</td>
                    <td>{{ $subconta->numero ?? '' }}</td>
                    <td>{{ $subconta->nome ?? '' }}</td>
                    <td>{{ $subconta->tipo_conta == "M" ? "Movimento" : 'Entregadora' }}</td>
                    <td>{{ $subconta->status }}</td>
                    
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="card-footer clearfix d-flex">
            
              @if (Auth::user()->can('editar subconta'))
              <a href="{{ route('subcontas.edit', $subconta->id) }}" class="btn btn-sm btn-success mx-1">
                <i class="fas fa-edit"></i>
              </a>
              @endif
              @if (Auth::user()->can('eliminar subconta'))
              <form action="{{ route('subcontas.destroy', $subconta->id ) }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger mx-1"
                  onclick="return confirm('Tens Certeza que Desejas excluir esta subconta?')">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
              @endif
            
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