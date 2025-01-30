@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detalhe Produto</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('produtos.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Produto</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

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
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-3">
                                    @if ($produto->imagem == null)
                                    <img class="img-fluid mb-3" src="../../dist/img/sem-imagem.jpg" style="height: 120px" alt="{{ $produto->nome }}">
                                    @else
                                    <img class="img-fluid mb-3" src='{{ asset("images/produtos/$produto->imagem") }}' style="height: 120px" alt="{{ $produto->nome }}">
                                    {{-- <img class="img-fluid mb-3" src='{{ asset("images/produtos/$produto->imagem") }}' style="height: 120px" alt="{{ $produto->nome }}"> --}}
                                    @endif
                                </div>
                                <div class="col-12 col-md-9">
                                    <div class="row">
                                        <div class="col-12 col-sm-12">
                                            <h2 class="h6 text-info">{{ $produto->nome }}

                                                @if (Auth::user()->can('editar produtos'))
                                                <a href="{{ route('produtos.edit', $produto->id) }}" class="float-right btn btn-sm btn-primary mb-2 mx-1">
                                                    <i class="fas fa-edit"></i> Editar Produto
                                                </a>
                                                @endif

                                                @if (Auth::user()->can('criar produtos'))
                                                @if ($produto->lote_valicidade === 'Sim')
                                                <a href="{{ route('lotes.index', ['produto_id' => $produto->id]) }}" class="float-right btn btn-sm btn-success mb-2 mx-1">
                                                    <i class="fas fa-cog"></i> Gestão de Lotes
                                                </a>
                                                @endif
                                                @endif

                                                @if (Auth::user()->can('criar grupo preco'))
                                                <a href="{{ route('grupos_preco.produtos', $produto->id) }}" class="float-right btn btn-sm btn-primary mb-2 mx-1">
                                                    <i class="fas fa-edit"></i> Grupos de Preços
                                                </a>
                                                @endif

                                            </h2>
                                            <table class="table text-nowrap">
                                                <tbody>
                                                    <tr>
                                                        <th>Referência</th>
                                                        <th>Categoria</th>
                                                        <th>Marca</th>
                                                        <th>Variação</th>
                                                        <th>Tipo</th>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ $produto->referencia }}</td>
                                                        <td><i class="fas fa-ticket-alt"></i> {{ $produto->categoria->categoria }}</td>
                                                        <td><i class="fas fa-ticket-alt"></i> {{ $produto->marca->nome }}</td>
                                                        <td><i class="fas fa-ticket-alt"></i> {{ $produto->variacao->nome }}</td>

                                                        @if($produto->tipo == 'P')
                                                        <td><i class="fas fa-ticket-alt"></i> Produto</td>
                                                        @endif
                                                        @if($produto->tipo == 'S')
                                                        <td><i class="fas fa-ticket-alt"></i> Serviço</td>
                                                        @endif
                                                        @if($produto->tipo == 'O')
                                                        <td><i class="fas fa-ticket-alt"></i> Outro (portes, adiantamentos, etc.)</td>
                                                        @endif
                                                        @if($produto->tipo == 'I')
                                                        <td><i class="fas fa-ticket-alt"></i> Imposto (excepto IVA e IS) ou Encargo Parafiscal</td>
                                                        @endif
                                                        @if($produto->tipo == 'E')
                                                        <td><i class="fas fa-ticket-alt"></i> Imposto Especial de Consumo (IABA, ISP e IT)</td>
                                                        @endif

                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-12 col-sm-12">
                                            <h2 class="h6">{{ $produto->descricao }}</h2>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            @if (Auth::user()->can('eliminar produtos'))
                                <button class="btn btn-sm btn-danger mx-1 float-right delete-record" data-id="{{ $produto->id }}">
                                    <i class="fas fa-trash text-danger"></i> Apagar Este produto
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    @if ($produto->controlo_stock == "Sim")
                    <div class="card">
                        <div class="card-header">
                            <h1><span class="h4">Stock </span><i class="fas fa-database"></i></h1>
                        </div>
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Loja / Armazém</th>
                                    <th>Alert Stock</th>
                                    <th>Stock Minimo</th>
                                    <th>Stock</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($lojas)
                                @foreach ($lojas as $item)
                                <tr>
                                    <td>{{ $item->loja->nome }}</td>
                                    @if ($item->stock > 50)
                                    <td class="text-warning">Excesso</td>
                                    @endif

                                    @if ($item->stock <= 10) <td class="text-danger">Alerta</td>
                                        @endif

                                        @if ($item->stock > 10 AND $item->stock <= 50) <td class="text-success">Normal</td>
                                            @endif
                                            <td>{{ $item->stock_minimo }}</td>
                                            <td>{{ $item->stock }}</td>
                                            <td style="width: 50px;">
                                                @if (Auth::user()->can('criar stock'))
                                                <a href="{{ route('movimento-estoques.show', $item->id) }}" class="btn btn-sm btn-default"><i class="fas fa-database"></i> Gerir Stock</a>
                                                @endif
                                            </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>

                            <tfoot>
                                <th></th>
                                <th></th>
                                <th>Total</th>
                                <th>{{ $totalStock }}</th>
                                <th></th>
                            </tfoot>
                        </table>
                    </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                <div class="col-12 col-sm-3">
                                    <h2>KZ</h2>
                                    <h4>{{ number_format($produto->preco_venda, 2, ',', '.') }} <span class="text-secondary">{{ $empresa->moeda }}</h4>
                                </div>

                                <div class="col-12 col-sm-3">
                                    <h6>PVP</h6>
                                    <h2 class="h4">{{ number_format($produto->preco_venda, 2, ',', '.') }} <span class="text-secondary">{{ $empresa->moeda }}</h2>
                                    <h6 class="h6 text-secondary">
                                        @if ($produto->imposto == "")
                                        Automático
                                        @endif

                                        @if ($produto->imposto == "ISE")
                                        IVA - Isento (0%)
                                        @endif

                                        @if ($produto->imposto == "RED")
                                        IVA - Taxa Reduzida (2%)
                                        @endif

                                        @if ($produto->imposto == "INT")
                                        IVA - Taxa Intermédia (5%)
                                        @endif

                                        @if ($produto->imposto == "OUT")
                                        IVA - Taxa 7% (7%)
                                        @endif

                                        @if ($produto->imposto == "NOR")
                                        IVA - Taxa Normal (14%)
                                        @endif
                                    </h6>
                                </div>

                                <div class="col-12 col-sm-3">
                                    <h6>S/IVA</h6>
                                    <h2 class="h4">{{ number_format($produto->preco, 2, ',', '.') }} <span class="text-secondary">{{ $empresa->moeda }}</h2>
                                    @if ($produto->margem <= 0) <span class="text-danger">projuizo {{ $produto->margem }}</span>
                                        @endif

                                        @if ($produto->margem >= 1)
                                        <span class="text-success"><i class="fas fa-circle-check"></i> Margem {{ $produto->margem }} %</span>
                                        @endif
                                </div>

                                <div class="col-12 col-sm-3">
                                    <h6>Fornecedor</h6>
                                    <h2 class="h4">{{ number_format($produto->preco_custo, 2, ',', '.') }} <span class="text-secondary">{{ $empresa->moeda }}</h2>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            @if (Auth::user()->can('listar grupo preco'))
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h1><span class="h4">Grupo de Preços </span><i class="fas fa-database"></i></h1>
                        </div>
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Preço</th>
                                    <th>Preço S/IVA</th>
                                    <th>Preço Fornecedor</th>
                                    <th>IVA</th>
                                    <th>Margem de Lucro</th>
                                    <th>Estado</th>
                                    <th class="text-right">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($grupo_precos)
                                @foreach ($grupo_precos as $item)
                                <tr>
                                    <td>{{ number_format($item->preco_venda??0, 2, ',', '.') }} <span class="text-secondary">{{ $empresa->moeda ?? "" }}</span></td>
                                    <td>{{ number_format($item->preco??0, 2, ',', '.') }} <span class="text-secondary">{{ $empresa->moeda ?? "" }}</span></td>
                                    <td>{{ number_format($item->preco_custo??0, 2, ',', '.')  }} <span class="text-secondary">{{ $empresa->moeda ?? "" }}</span></td>
                                    <td>{{ $item->produto->taxa_imposto->valor??0 }} %</td>
                                    <td>{{ number_format($item->margem??0, 2, ',', '.')  }} <span class="text-secondary">%</span></td>
                                    <td>{{ $item->status??"" }}</td>


                                    <td style="width: 50px;">
                                        @if ($item->status == "desactivo")
                                        <a href="{{ route('definir_preco.produtos', $item->id) }}" class="btn btn-sm btn-primary status-record" data-id="{{ $item->id }}"><i class="fas fa-database"></i> Activar</a>
                                        @endif
                                        <a href="{{ route('grupos_preco.delete', $item->id) }}" class="btn btn-sm btn-danger delete-record-preco" data-id="{{ $item->id }}"><i class="fas fa-trash"></i> Eliminar</a>
                                    </td>


                                </tr>
                                @endforeach
                                @endif
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </section>
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
                    url: `{{ route('produtos.destroy', ':id') }}`.replace(':id', recordId)
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
                        window.location.href = `{{ route('produtos.index') }}`;
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!', 'Ocorreu um erro ao excluir o registro. Tente novamente.', 'error');
                    }
                , });
            }
        });
    });

    $(document).on('click', '.delete-record-preco', function(e) {

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
                    url: `{{ route('grupos_preco.delete', ':id') }}`.replace(':id', recordId)
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
    
    $(document).on('click', '.status-record', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro
        
        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Deseja activar este preçario para o produto!"
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
                    url: `{{ route('definir_preco.produtos', ':id') }}`.replace(':id', recordId)
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
    
</script>
@endsection
