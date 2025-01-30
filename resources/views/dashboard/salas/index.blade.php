@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Salas</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">Voltar</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('salas.create') }}"
                class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Adicionar Salas</a></li>
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

          @if ($salas)
          @foreach ($salas as $sala)
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <a href="{{ route('salas.edit', $sala->id) }}" class="text-info text-uppercase">{{ $sala->nome }}</a>
              </h3>
              @if ($sala->status == "activo")
                <a href="{{ route('salas.show', $sala->id) }}" class="btn btn-sm btn-info float-right ml-2"> <i class="fas fa-close"></i> Supender</a>
              @endif

              @if ($sala->status == "desactivo")
              <a href="{{ route('salas.show', $sala->id) }}" class="btn btn-sm btn-info float-right ml-2"> <i class="fas fa-check"></i> Activar</a>
              @endif
              
              <a href="{{ route('salas.edit', $sala->id) }}" class="btn btn-sm btn-info float-right ml-2">
                Editar</a>
              <form action="{{ route('salas.destroy', $sala->id ) }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger float-right ml-2"
                  onclick="return confirm('Tens Certeza que Desejas excluir esta loja?')">
                  Excluir
                </button>
              </form>
            </div>
            <!-- /.card-header -->
            
            @if (!$tipo_entidade_logado->empresa->tem_permissao("Tabela Apoio Centro Formação"))
            <div class="card-body table-responsive">
              <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
                <thead>
                  <tr>
                    <th>Mesas</th>
                    <th class="text-right">Ocupação</th>
                    <th class="text-right">Acções</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($sala->mesas as $mesa)
                  <tr>
                    <td>{{ $mesa->nome }}</td>
                    <td class="text-right">{{ $mesa->ocupacao }}</td>
                    <td class="text-right">
                      <div class="btn-group">
                          <button type="button" class="btn btn-default">Ações</button>
                          <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                              <span class="sr-only">Toggle Dropdown</span>
                          </button>
                          <div class="dropdown-menu" role="menu">
                              <a class="dropdown-item" href="{{ route('mesas.show', $mesa->id) }}"><i class="fas fa-eye text-info"></i> Detalhes</a>
                              <a class="dropdown-item" href="{{ route('mesas.edit', $mesa->id) }}"><i class="fas fa-edit text-success"></i> Editar</a>
                              <div class="dropdown-divider"></div>
                              <form action="{{ route('mesas.destroy', $mesa->id ) }}" method="post">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-sm btn-danger dropdown-item" onclick="return confirm('Tens Certeza que Desejas excluir esta Mesa?')">
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
            @endif
            
            <!-- /.card-body -->
            <div class="card-footer clearfix">
              
              @if ($tipo_entidade_logado->empresa->tem_permissao("Tabela Apoio Centro Formação"))
              <a href="{{ route('mesas.create', ['createLoja' => $sala->id] ) }}" class="btn btn-md btn-info">Adicionar Salas</a>
              @else
              <a href="{{ route('mesas.create', ['createLoja' => $sala->id] ) }}" class="btn btn-md btn-info">Adicionar Mesa</a>
              @endif
            
            </div>
          </div>
          @endforeach
          <!-- /.card -->

          @endif
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
