@extends('layouts.app')

@section('content')

<!-- Content Wrapper. quartoins page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Detalhe do Tipo Quarto</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('tipo-quartos.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Tipo de Quarto</li>
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
            @if ($tipo)
            <!-- /.card-header -->
            <div class="card-body table-responsive">
              <table class="table table-hover text-nowrap">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Designação</th>
                    <th>Estado</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ $tipo->id }}</td>
                    <td>{{ $tipo->nome }}</td>
                    <td>{{ $tipo->status }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="card-footer clearfix d-flex">
              <a href="{{ route('tipo-quartos.edit', $tipo->id) }}" class="btn btn-sm btn-success mx-1">
                <i class="fas fa-edit"></i>
              </a>
              <form action="{{ route('tipo-quartos.destroy', $tipo->id ) }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger mx-1"
                  onclick="return confirm('Tens Certeza que Desejas excluir este tipo quarto?')">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </div>
            @endif

          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.quartoiner-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection