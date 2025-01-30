@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Funcionários</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-recurso-humanos') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Funcionários</li>
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
                        <div class="card-header">
                            <h3 class="card-title">
                                @if (Auth::user()->can('criar funcionario'))
                                <a href="{{ route('funcionarios.create') }}" class="btn btn-sm btn-primary">Novo Funcionário</a>
                                <a href="{{ route('create_import.funcionarios') }}" class="btn btn-sm btn-success">Importar Excel</a>
                                @endif
                            </h3>

                            <div class="card-tools">
                                <a class="btn btn-sm btn-danger" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                                <a class="btn btn-sm btn-success" href="#"><i class="fas fa-file-excel"></i> EXCEL</a>
                            </div>
                        </div>

                        @if ($funcionarios)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Número Mec.</th>
                                        <th>Tipo Pessoal</th>
                                        <th>Nome</th>
                                        <th>Gênero</th>
                                        <th>Estado Civil</th>
                                        <th>Data Nascimento</th>
                                        <th>NIF/Bilhete</th>
                                        <th>Codigo Postal</th>
                                        <th>Telelefone/Telemóvel</th>
                                        <th>Estado</th>
                                        <th class="text-right">Acções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($funcionarios as $item)
                                    <tr>
                                        <td><a href="{{ route('funcionarios.show', $item->id) }}">{{ $item->numero_mecanografico }} </a></td>
                                        <td>{{ $item->categoria ?? '------' }}</td>
                                        <td><a href="{{ route('funcionarios.show', $item->id) }}">{{ $item->nome }} </a></td>
                                        <td>{{ $item->genero ?? '------' }}</td>
                                        <td>{{ $item->estado_civil->nome ?? '------' }}</td>
                                        <td>{{ $item->data_nascimento ?? '------' }}</td>
                                        <td>{{ $item->nif ?? '------' }}</td>
                                        <td>{{ $item->codigo_postal ?? '------' }}</td>
                                        <td>{{ $item->telefone ?? '--- --- ---' }} / {{ $item->telemovel ?? '--- --- --- ---' }}</td>
                                        @if ($item->status == true)
                                        <td>Activo</td>
                                        @else
                                        <td>Inactivo</td>
                                        @endif

                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default">Ações</button>
                                                <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">

                                                    @if (Auth::user()->can('listar funcionario'))
                                                    <a class="dropdown-item" href="{{ route('funcionarios.show', $item->id) }}"><i class="fas fa-eye text-info"></i> Detalhes</a>
                                                    @endif
                                                    @if (Auth::user()->can('editar funcionario'))
                                                    <a class="dropdown-item" href="{{ route('funcionarios.edit', $item->id) }}"><i class="fas fa-edit text-success"></i> Editar</a>
                                                    @endif

                                                    <div class="dropdown-divider"></div>
                                                    @if (Auth::user()->can('eliminar funcionario'))
                                                    <form action="{{ route('funcionarios.destroy', $item->id ) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger dropdown-item" onclick="return confirm('Tens Certeza que Desejas excluir esta Funcionário?')">
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
