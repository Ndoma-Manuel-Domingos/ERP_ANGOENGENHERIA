@extends('layouts.admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tipos Entidade</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Tipos Entidade</li>
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
                                <a href="{{ route('tipos-entidade.create') }}" class="btn btn-sm btn-primary">Novo Tipo Entidade</a>
                            </h3>

                            <div class="card-tools">
                                <a class="btn btn-sm btn-danger" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                                <a class="btn btn-sm btn-success" href="#"><i class="fas fa-file-excel"></i> EXCEL</a>
                            </div>
                        </div>
                        @if ($tipos_entidade)
                          <!-- /.card-header -->
                          <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 20px">#</th>
                                        <th>Tipo Entidade</th>
                                        <th>Estado</th>
                                        <th>Sigla</th>
                                        <th class="text-left">Modulos</th>
                                        <th style="width: 10px" class="text-left">Acções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tipos_entidade as $tipo_entidade)
                                    <tr>
                                        <td>{{ $tipo_entidade->id }}</td>
                                        <td>{{ $tipo_entidade->tipo }}</td>
                                        <td class="text-uppercase">{{ $tipo_entidade->status }}</td>
                                        <td class="text-uppercase">{{ $tipo_entidade->sigla }}</td>
                                        <td>
                                            @foreach ($tipo_entidade->modulos as $item)
                                            <a href="" class="btn btn-sm btn-info" title="Clica para eliminar">{{ $item->modulo }}</a>
                                            @endforeach
                                        </td>
  
                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default">Ações</button>
                                                <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <a class="dropdown-item" href="{{ route('tipos-entidade.show', $tipo_entidade->id) }}"><i class="fas fa-eye text-info"></i> Detalhes</a>
                                                    <a class="dropdown-item" href="{{ route('tipos-entidade.edit', $tipo_entidade->id) }}"><i class="fas fa-edit text-success"></i> Editar</a>
                                                    <div class="dropdown-divider"></div>
                                                    <button class="btn btn-sm btn-danger dropdown-item delete-record" data-id="{{ $tipo_entidade->id }}">
                                                      <i class="fas fa-trash text-danger"></i> Eliminar
                                                    </button>
                                              
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
    $(document).on('click', '.delete-record', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        // const url = `{{ route('clientes.destroy', ':id') }}`.replace(':id', recordId);

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação não poderá ser desfeita!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#d33'
            , cancelButtonColor: '#3085d6'
            , confirmButtonText: 'Sim, excluir!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('tipos-entidade.destroy', ':id') }}`.replace(':id', recordId)
                    , method: 'DELETE'
                    , data: {
                        _token: '{{ csrf_token() }}', // Inclui o token CSRF
                    }
                    , beforeSend: function() {
                        // Você pode adicionar um loader aqui, se necessário
                        progressBeforeSend();
                    }
                    , success: function(response) {
                        Swal.close();
                        // Exibe uma mensagem de sucesso
                        showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                        window.location.reload();
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!', 'Ocorreu um erro ao excluir o registro. Tente novamente.', 'error');
                    }
                , });
            }
        });
    });


    $(function() {
        $("#carregar_tabela").DataTable({
            language: {
                url: "{{ asset('plugins/datatables/pt_br.json') }}"
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');
    });

</script>
@endsection
