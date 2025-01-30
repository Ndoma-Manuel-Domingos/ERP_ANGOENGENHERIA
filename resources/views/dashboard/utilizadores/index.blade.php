@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Utilizadores</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
            <li class="breadcrumb-item active">utilizadores</li>
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
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <a href="{{ route('utilizadores.create') }}" class="btn btn-sm btn-primary">Nova Utilizador</a>
              </h3>

              <div class="card-tools">
                <a class="btn btn-sm btn-danger" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                <a class="btn btn-sm btn-success" href="#"><i class="fas fa-file-excel"></i> EXCEL</a>
              </div>
            </div>

            @if ($utilizadores)
            <!-- /.card-header -->
            <div class="card-body table-responsive">
              <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Perfil</th>
                    <th>Estado</th>
                    <th class="text-right">Acções</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($utilizadores as $util)
                  <tr>
                    <td>{{ $util->id }}</td>
                    <td><a href="{{ route('utilizadores.edit', $util->id) }}">{{ $util->name }}</a></td>
                    <td>{{ $util->email }}</td>
                    @foreach ($util->roles as $item)
                      <td>{{ $item->name }}</td> 
                    @endforeach
                    <td>{{ $util->status == 1 ? 'Activo' : 'Desactivo' }}</td>
                    
                    <td class="text-right">
                      <div class="btn-group">
                          <button type="button" class="btn btn-default">Ações</button>
                          <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                              <span class="sr-only">Toggle Dropdown</span>
                          </button>
                          <div class="dropdown-menu" role="menu">
                              <a class="dropdown-item" href="{{ route('utilizadores.show', $util->id) }}"><i class="fas fa-eye text-info"></i> Detalhes</a>
                              <a class="dropdown-item" href="{{ route('utilizadores.edit', $util->id) }}"><i class="fas fa-edit text-success"></i> Editar</a>
                              <div class="dropdown-divider"></div>
                              <form action="{{ route('utilizadores.destroy', $util->id ) }}" method="post">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-sm btn-danger dropdown-item" onclick="return confirm('Tens Certeza que Desejas excluir esta Província?')">
                                      <i class="fas fa-trash text-danger"></i> Eliminar
                                  </button>
                              </form>
                          </div>
                  </td>
       
                  </tr>
                  @endforeach

                </tbody>
              </table>
            </div>
            <!-- /.card-body -->

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
@section('scripts')
  <script>
    $(function() {
      $("#carregar_tabela").DataTable({
        language: {
          url: "{{ asset('plugins/datatables/pt_br.json') }}"
        },
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');
    });
  </script>
@endsection
