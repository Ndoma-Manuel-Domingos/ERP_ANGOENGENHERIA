@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Renovações de Contratos</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard-recurso-humanos') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Contratos</li>
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
            <div class="card-header">
              <h3 class="card-title">
                @if (Auth::user()->can('criar contrato'))
                <a href="{{ route('renovacoes-contratos.create') }}" class="btn btn-sm btn-primary">Renovar Contrato</a>
                @endif
              </h3>

              <div class="card-tools">
                <a class="btn btn-sm btn-danger" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                <a class="btn btn-sm btn-success" href="#"><i class="fas fa-file-excel"></i> EXCEL</a>
              </div>
            </div>

            @if ($contratos)
            <!-- /.card-header -->
            <div class="card-body table-responsive">
              <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Nº</th>
                    <th>Nº Mec.</th>
                    <th>Funcionário</th>
                    <th>Cargo</th>
                    <th>Categoria</th>
                    <th>Tipo Contrato</th>
                    <th>Data Inicio</th>
                    <th>Data Final</th>
                    <th>Nova Data Final</th>
                    <th>Reno. Efectuadas</th>
                    <th>Sit. Após Renovação</th>
                    <th>Antiguidade</th>
                    <th>Duração Proxima Renovação</th>
                    <th>Estado</th>
                    <th class="text-right">Acções</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($contratos as $item)
                  <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->numero }}</td>
                    <td>{{ $item->funcionario->numero_mecanografico ?? "" }}</td>
                    <td>{{ $item->funcionario->nome ?? "" }}</td>
                    <td>{{ $item->cargo->nome ?? "" }}</td>
                    <td>{{ $item->categoria->nome ?? "" }}</td>
                    <td>{{ $item->tipo_contrato->nome ?? "" }}</td>
                    <td>{{ $item->data_inicio ?? "" }}</td>
                    <td>{{ $item->data_final ?? "" }}</td>
                    <td>{{ $item->nova_data_final ?? "" }}</td>
                    <td class="text-center">{{ $item->renovacoes_efectuadas ?? "" }}</td>
                    <td class="text-center">{{ $item->situacao_apos_renovacao ?? "" }}</td>
                    <td class="text-center">{{ $item->antiguidade ?? "" }}</td>
                    <td class="text-center">{{ $item->duracao_renovacao ?? "" }}</td>
                    <td>{{ $item->status }}</td>
                                        
                    <td class="text-right">
                      <div class="btn-group">
                        <button type="button" class="btn btn-default">Ações</button>
                        <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu">
                          @if (Auth::user()->can('listar contrato'))
                          <a class="dropdown-item" href="{{ route('contratos.show', $item->id) }}"><i class="fas fa-eye text-info"></i> Detalhes</a>
                          @endif
                          @if (Auth::user()->can('editar contrato'))
                          <a class="dropdown-item" href="{{ route('contratos.edit', $item->id) }}"><i class="fas fa-edit text-success"></i> Editar</a>
                          @endif
                          <div class="dropdown-divider"></div>
                          @if (Auth::user()->can('eliminar contrato'))
                          <form action="{{ route('contratos.destroy', $item->id ) }}" method="post">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-danger dropdown-item" onclick="return confirm('Tens Certeza que Desejas excluir esta contrato?')">
                                  <i class="fas fa-trash text-danger"></i> Eliminar
                              </button>
                          </form>
                          @endif
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
