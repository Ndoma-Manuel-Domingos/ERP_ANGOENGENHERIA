@extends('layouts.admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Empresas</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-admin') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Empresas</li>
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
            </div>
            
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <a href="{{ route('empresas.create') }}" class="btn btn-sm btn-primary">Nova Licença</a>
                            </h3>
    
                            <div class="card-tools">
                                <a class="btn btn-sm btn-danger" target="_blink" href="{{ route('nossa-empresas-pdf') }}"><i class="fas fa-file-pdf"></i> PDF</a>
                            </div>
                        </div>
    
                        @if ($empresas)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Código</th>
                                        <th>NIF</th>
                                        <th>Nome</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                        <th>Telefone</th>
                                        <th>Data Inicio</th>
                                        <th>Data Final</th>
                                        <th>Licença</th>
                                        @if ($user->level == '3')
                                        <th>Controlo</th>
                                        @endif
                                        <th class="text-right">Acções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($empresas as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->nif }}</td>
                                        <td>{{ $item->nome }}</td>
                                        @if ($item->tipo_entidade)
                                            <td><span class="badge badge-primary">{{ $item->tipo_entidade ? $item->tipo_entidade->tipo : '"' }}</span></td>
                                        @else
                                            <td><span class="badge badge-info">Comerciante</span></td>
                                        @endif
                                        <td class="text-uppercase">{{ $item->status }}</td>
                                        <td>{{ $item->telefone ?? '000 000 000' }}</td>
                                        <td>{{ $item->controle->inicio }}</td>
                                        <td>{{ $item->controle->final }}</td>
                                        
                                        @if ($item->dias_licencas($item->id) > 30)
                                        <td class="text-success">Faltam {{ $item->dias_licencas($item->id) }} dias </td>
                                        @else
                                        <td class="text-danger">Faltam {{ $item->dias_licencas($item->id) }} dias </td>
                                        @endif
                                        
                                        @if ($user->level == '3')
                                        <td><a href="{{ route('empresas.controlo', $item->id) }}" title="Mudar para o controlo de Ndoma" class="text-info">{{ $item->level == 2 ? 'Eluwidy' : 'Ndoma' }}</a></td>
                                        @endif
                                        
                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default">Ações</button>
                                                <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <a class="dropdown-item" href="{{ route('empresas.show', $item->id) }}"><i class="fas fa-eye text-info"></i> Detalhes</a>
                                                    <a class="dropdown-item" href="{{ route('empresas.edit', $item->id) }}"><i class="fas fa-cog text-success"></i> Configurar Licença</a>
                                                    
                                                    @if ($item->status == "activo")
                                                    <a class="dropdown-item" href="{{ route('empresas.desactivar', $item->id) }}"><i class="fas fa-close text-danger"></i> Desactivar</a>
                                                    @endif
                                                    @if ($item->status == "desactivo")
                                                    <a class="dropdown-item" href="{{ route('empresas.actvar', $item->id) }}"><i class="fas fa-check text-success"></i> Activar</a>
                                                    @endif
                                                    <a class="dropdown-item" href="{{ route('empresas.destroys', $item->id) }}"><i class="fas fa-trash text-danger"></i> ELiminar</a>
                                                    
                                                <div class="dropdown-divider"></div>
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

