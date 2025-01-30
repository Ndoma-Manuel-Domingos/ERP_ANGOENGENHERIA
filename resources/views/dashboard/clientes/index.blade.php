@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Hotelaria"))
                            Hospodes
                        @else
                            Clientes
                        @endif
                    </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">
                            @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Hotelaria"))
                                Hospodes
                            @else
                                Clientes
                            @endif
                        </li>
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
                                @if (Auth::user()->can('criar cliente'))
                                <a href="{{ route('clientes.create') }}" class="btn btn-sm btn-primary">Novo 
                                    @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Hotelaria"))
                                        Hospode
                                    @else
                                        Cliente
                                    @endif
                                </a>
                                <a href="{{ route('create_import.clientes') }}" class="btn btn-sm btn-success">Importar Excel</a>
                                @endif
                            </h3>

                            <div class="card-tools">
                                <a class="btn btn-sm btn-danger" target="_blink" href="{{ route('pdf-clientes') }}"><i class="fas fa-file-pdf"></i> PDF</a>
                                <a class="btn btn-sm btn-success" href="#"><i class="fas fa-file-excel"></i> EXCEL</a>
                            </div>
                        </div>

                        @if ($clientes)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
                                <thead>
                                    <tr>
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
                                    @foreach ($clientes as $item)
                                    <tr>
                                        <td><a href="{{ route('clientes.show', $item->id) }}">{{ $item->conta }} </a></td>
                                        <td><a href="{{ route('clientes.show', $item->id) }}">{{ $item->nome }} </a></td>
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

                                                    @if (Auth::user()->can('listar cliente'))
                                                    <a class="dropdown-item" href="{{ route('clientes.show', $item->id) }}"><i class="fas fa-eye text-info"></i> Detalhes</a>
                                                    @endif
                                                    @if (Auth::user()->can('editar cliente'))
                                                    <a class="dropdown-item" href="{{ route('clientes.edit', $item->id) }}"><i class="fas fa-edit text-success"></i> Editar</a>
                                                    @endif
                                                    @if ($tipo_entidade_logado->empresa->tipo_empresa != "Fisica")
                                                    @if ($tipo_entidade_logado->empresa->tipo_entidade->sigla == 'HOSP')
                                                    @if (Auth::user()->can('marcar consulta'))
                                                    <a class="dropdown-item" href="{{ route('consultas.create', $item->id) }}"><i class="fas fa-user-nurse text-info"></i> Marcar Consulta</a>
                                                    @endif
                                                    @if (Auth::user()->can('marcar exame'))
                                                    <a class="dropdown-item" href="{{ route('marcar_exame', $item->id) }}"><i class="fas fa-user-nurse text-info"></i> Marcar Exame</a>
                                                    @endif

                                                    @endif
                                                    @endif

                                                    <div class="dropdown-divider"></div>
                                                    @if (Auth::user()->can('eliminar cliente'))
                                                        <button class="btn btn-sm btn-danger dropdown-item delete-record" data-id="{{ $item->id }}">
                                                            <i class="fas fa-trash text-danger"></i> Eliminar
                                                        </button>
                                                    @endif
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
    
        $(document).on('click', '.delete-record', function (e) {
            
            e.preventDefault();
            let recordId = $(this).data('id'); // Obtém o ID do registro
            
            // const url = `{{ route('clientes.destroy', ':id') }}`.replace(':id', recordId);
    
            Swal.fire({
                title: 'Você tem certeza?',
                text: "Esta ação não poderá ser desfeita!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Envia a solicitação AJAX para excluir o registro
                    $.ajax({
                        url: `{{ route('clientes.destroy', ':id') }}`.replace(':id', recordId),
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}', // Inclui o token CSRF
                        },
                        beforeSend: function() {
                            // Você pode adicionar um loader aqui, se necessário
                            progressBeforeSend();
                        }, 
                        success: function (response) {
                            Swal.close();
                            // Exibe uma mensagem de sucesso
                            showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                            window.location.reload();
                        },
                        error: function (xhr) {
                            Swal.close();
                            showMessage('Erro!', 'Ocorreu um erro ao excluir o registro. Tente novamente.', 'error');
                        },
                    });
                }
            });
        });
    
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
