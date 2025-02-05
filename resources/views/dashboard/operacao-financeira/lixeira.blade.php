@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Lixeira - Operações Financeiras</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-financeiro') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Financeiras</li>
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
                <div class="col-12 bg-light">
                    <div class="card">
                        <form action="{{ route('operacaoes-financeiras.lixeira') }}" method="get" class="mt-3">
                            @csrf
                            <div class="card-body row">

                                <div class="col-12 col-md-3">
                                    <label for="data_inicio" class="form-label">Data Inicio</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" value="{{ $requests['data_inicio'] ?? '' }}" name="data_inicio" placeholder="Data Inicio">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="data_final" class="form-label">Data Final</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" value="{{ $requests['data_final'] ?? '' }}" name="data_final" placeholder="Data final">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="tipo_movimento" class="form-label">Tipo movimento</label>
                                    <select type="text" class="form-control select2" name="tipo_movimento">
                                        <option value="">Todos</option>
                                        <option value="R" {{ $requests['tipo_movimento'] == "R" ? 'selected' : ''}}>Receitas</option>
                                        <option value="D" {{ $requests['tipo_movimento'] == "D" ? 'selected' : ''}}>Despesas</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="status" class="form-label">Estado</label>
                                    <select type="text" class="form-control select2" name="status">
                                        <option value="">Todos</option>
                                        <option value="pendente" {{ $requests['status'] == "pendente" ? 'selected' : ''}}>Pendente</option>
                                        <option value="pago" {{ $requests['status'] == "pago" ? 'selected' : ''}}>Pago</option>
                                        <option value="atrasado" {{ $requests['status'] == "atrasado" ? 'selected' : ''}}>Atrasado</option>
                                    </select>
                                </div>

                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary btn-sm ml-2 text-right"> <i class="fas fa-search"></i> Pesquisar</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        @if ($operacoes)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Referência</th>
                                        <th>Estado</th>

                                        @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Contabilidade"))
                                        <th>Subconta</th>
                                        @else
                                        <th>Caixa/Conta Bancária</th>
                                        @endif

                                        <th>Dispesa/Receita</th>
                                        <th>Fornecedor/Cliente</th>
                                        <th class="text-right">Data</th>
                                        <th class="text-right">Motante</th>
                                        <th><span class="float-right">Acções</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($operacoes as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->nome }}</td>
                                        <td>{{ $item->status }}</td>

                                        @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Contabilidade"))
                                        <td>{{ $item->subconta->numero ?? "" }} - {{ $item->subconta->nome ?? "" }}</td>
                                        @else
                                        @if ($item->formas == "C")
                                        <td>{{ $item->caixa->conta ?? "" }} - {{ $item->caixa->nome ?? "" }}</td>
                                        @else
                                        @if ($item->formas == "B")
                                        <td>{{ $item->contabancaria->conta ?? "" }} - {{ $item->contabancaria->nome ?? "" }}</td>
                                        @else
                                        <td>Outras</td>
                                        @endif
                                        @endif
                                        @endif

                                        <td>{{ $item->type == "D" ? $item->dispesa->nome : $item->receita->nome }}</td>
                                        <td>{{ $item->type == "D" ? ($item->fornecedor ? $item->fornecedor->nome : "") : ($item->cliente ? $item->cliente->nome : "") }}</td>
                                        <td class="text-right">{{ $item->date_at }}</td>
                                        @if ($item->type == "D")
                                        <td class="text-right text-danger">- {{ number_format($item->motante, 2, ',', '.')  }}</td>
                                        @else
                                        <td class="text-right text-success">+ {{ number_format($item->motante, 2, ',', '.')  }}</td>
                                        @endif

                                        <td class="text-right">
                                            <button type="button" class="btn btn-default">Ações</button>
                                            <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if (Auth::user()->can('editar dispesa'))
                                                <a class="dropdown-item recuperar-record" href="{{ route('operacaoes-financeiras.lixeira-recuperar', $item->id) }}" data-id="{{ $item->id }}"><i class="fas fa-undo-alt text-success"></i> Recuperar</a>
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
    $(document).on('click', '.recuperar-record', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro
        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Deseja restaurar esta informação!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#d33'
            , cancelButtonColor: '#3085d6'
            , confirmButtonText: 'Sim, desejo!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('operacaoes-financeiras.lixeira-recuperar', ':id') }}`.replace(':id', recordId)
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
