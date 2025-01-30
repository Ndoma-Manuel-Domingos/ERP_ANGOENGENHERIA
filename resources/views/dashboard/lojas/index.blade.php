@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Lojas/Armazém</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">
                            @if (Auth::user()->can('criar loja/armazem'))
                            <a href="{{ route('lojas.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Adicionar Loja</a>
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
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    @if ($lojas)
                    @foreach ($lojas as $loja)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <a href="{{ route('lojas.show', $loja->id) }}" class="text-info text-uppercase">{{ $loja->nome }}</a>
                            </h3>
                            @if (Auth::user()->can('editar loja/armazem'))
                            @if ($loja->status == "activo")
                            <a href="{{ route('lojas.show', $loja->id) }}" class="btn btn-sm btn-info float-right ml-2"> <i class="fas fa-close"></i> Supender</a>
                            @endif

                            @if ($loja->status == "desactivo")
                            <a href="{{ route('lojas.show', $loja->id) }}" class="btn btn-sm btn-info float-right ml-2"> <i class="fas fa-check"></i> Activar</a>
                            @endif

                            <a href="{{ route('lojas.edit', $loja->id) }}" class="btn btn-sm btn-info float-right ml-2">
                                Editar</a>
                            @endif
                            @if (Auth::user()->can('eliminar loja/armazem'))
                            <form action="{{ route('lojas.destroy', $loja->id ) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger float-right ml-2" onclick="return confirm('Tens Certeza que Desejas excluir esta loja?')">
                                    Excluir
                                </button>
                            </form>
                            @endif
                        </div>

                        <div class="row">

                            <div class="col-12 col-md-6">
                                <!-- /.card-header -->
                                <div class="card-body table-responsive">
                                    <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>Caixa</th>
                                                <th>Estado</th>
                                                <th class="text-right">Data Criação</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($loja->caixas as $item)
                                            <tr>
                                                <td>{{ $item->conta }} - {{ $item->nome }}</td>
                                                <td>{{ $item->status }}</td>
                                                <td class="text-right">{{ $item->created_at }}</td>

                                                <td class="text-right">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default">Ações</button>
                                                        <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <div class="dropdown-menu" role="menu">
                                                            @if (Auth::user()->can('listar caixa'))
                                                            <a class="dropdown-item" href="{{ route('caixas.show', $item->id) }}"><i class="fas fa-eye text-info"></i> Detalhes</a>
                                                            @endif
                                                            @if (Auth::user()->can('editar caixa'))
                                                            <a class="dropdown-item" href="{{ route('caixas.edit', $item->id) }}"><i class="fas fa-edit text-success"></i> Editar</a>
                                                            @endif
                                                            <div class="dropdown-divider"></div>
                                                            @if (Auth::user()->can('eliminar caixa'))
                                                            <button class="btn btn-sm btn-danger dropdown-item delete-record-caixa" data-id="{{ $item->id }}">
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
                                <!-- /.card-body -->
                                <div class="card-footer clearfix">
                                    @if (Auth::user()->can('criar caixa'))
                                    <a href="{{ route('caixas.create', ['createLoja' => $loja->id] ) }}" class="btn btn-md btn-info">Adicionar Caixa</a>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="card-body table-responsive">
                                    <table class="table table-hover text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>Conta Bancária</th>
                                                <th>Estado</th>
                                                <th class="text-right">Data Criação</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($loja->bancos as $item)
                                            <tr>
                                                <td>{{ $item->conta }} - {{ $item->nome }}</td>
                                                <td>{{ $item->status }}</td>
                                                <td class="text-right">{{ $item->created_at }}</td>

                                                <td class="text-right">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default">Ações</button>
                                                        <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <div class="dropdown-menu" role="menu">
                                                            @if (Auth::user()->can('listar banco'))
                                                            <a class="dropdown-item" href="{{ route('contas-bancarias.show', $item->id) }}"><i class="fas fa-eye text-info"></i> Detalhes</a>
                                                            @endif
                                                            @if (Auth::user()->can('editar banco'))
                                                            <a class="dropdown-item" href="{{ route('contas-bancarias.edit', $item->id) }}"><i class="fas fa-edit text-success"></i> Editar</a>
                                                            @endif
                                                            <div class="dropdown-divider"></div>
                                                            @if (Auth::user()->can('eliminar banco'))
                                                            <button class="btn btn-sm btn-danger dropdown-item delete-record-banco" data-id="{{ $item->id }}">
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
                                <!-- /.card-body -->
                                <div class="card-footer clearfix">
                                    @if (Auth::user()->can('criar banco'))
                                    <a href="{{ route('contas-bancarias.create', ['createLoja' => $loja->id] ) }}" class="btn btn-md btn-info">Adicionar Conta Bancária</a>
                                    @endif
                                </div>
                            </div>
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
    $(document).on('click', '.delete-record-banco', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro
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
                    url: `{{ route('contas-bancarias.destroy', ':id') }}`.replace(':id', recordId)
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


    $(document).on('click', '.delete-record-caixa', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro
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
                    url: `{{ route('caixas.destroy', ':id') }}`.replace(':id', recordId)
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
