@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Detalhe Tipo Contrato</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('tipos-contratos.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Tipo Contrato</li>
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
            @if ($tipo_contrato)
            <!-- /.card-header -->
            <div class="card-body table-responsive">
              <table class="table table-hover text-nowrap">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Designação</th>
                    <th>Estado</th>
                    <th>Create At</th>
                    <th>Update At</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ $tipo_contrato->id }}</td>
                    <td>{{ $tipo_contrato->nome }}</td>
                    <td>{{ $tipo_contrato->status }}</td>
                    <td>{{ $tipo_contrato->created_at }}</td>
                    <td>{{ $tipo_contrato->updated_at }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="card-footer clearfix d-flex">
              <a href="{{ route('tipos-contratos.edit', $tipo_contrato->id) }}" class="btn btn-sm btn-success mx-1">
                <i class="fas fa-edit"></i>
              </a>
              <form action="{{ route('tipos-contratos.destroy', $tipo_contrato->id ) }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger mx-1"
                  onclick="return confirm('Tens Certeza que Desejas excluir esta Tipo Contrato?')">
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
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection