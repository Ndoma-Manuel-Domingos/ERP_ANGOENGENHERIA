@extends('layouts.admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tipo de Pagamentos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Pagamento</li>
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
                                {{-- <a href="#" data-toggle="modal" data-target="#modalCreateCaixa" class="btn btn-sm btn-primary">Nova Caixa</a> --}}
                                <a href="{{ route('tipo-pagamentos.create') }}" class="btn btn-sm btn-primary">Novo Tipo Pagamento</a>
                            </h3>

                            <div class="card-tools">
                                <a class="btn btn-sm btn-danger" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                                <a class="btn btn-sm btn-success" href="#"><i class="fas fa-file-excel"></i> EXCEL</a>
                            </div>
                        </div>

                        @if ($tipoPagamentos)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Troco</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                        <th class="text-right">Acções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tipoPagamentos as $tipo)
                                    <tr>
                                        <td>{{ $tipo->titulo }}</td>
                                        <td>{{ $tipo->booleano($tipo->troco) }}</td>
                                        <td>{{ $tipo->tipo }}</td>
                                        @if ($tipo->status == true)
                                        <td><a href="#" data-id="{{ $tipo->id }}" class="btn-sm btn-default change-record"><i class="fas fa-close"></i> Suspender</a></td>
                                        @else
                                        <td><a href="#" data-id="{{ $tipo->id }}" class="btn-sm btn-default change-record"><i class="fas fa-check"></i> Activar</a></td>
                                        @endif

                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default">Ações</button>
                                                <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <a class="dropdown-item change-record" data-id="{{ $tipo->id }}" href="#"><i class="fas fa-eye text-info"></i> Detalhes</a>
                                                    <a class="dropdown-item" href="{{ route('tipo-pagamentos.edit', $tipo->id) }}"><i class="fas fa-edit text-success"></i> Editar</a>
                                                    <div class="dropdown-divider"></div>
                                                    <button class="btn btn-sm btn-danger dropdown-item delete-record" data-id="{{ $tipo->id }}">
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

{{-- @include('dashboard.caixas.modal.create') --}}

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
                    url: `{{ route('tipo-pagamentos.destroy', ':id') }}`.replace(':id', recordId)
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

    $(document).on('click', '.change-record', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro
        // const url = `{{ route('clientes.destroy', ':id') }}`.replace(':id', recordId);

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação não poderá ser alterada!"
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
                    url: `{{ route('tipo-pagamentos.show', ':id') }}`.replace(':id', recordId)
                    , method: 'GET'
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
