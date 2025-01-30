@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Produtos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Variação</li>
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
                <div class="col-12 bg-light">
                    <div class="card">
                        <form action="{{ route('produtos.index') }}" method="get" class="mt-3">
                            @csrf
                            <div class="card-body row">

                                <div class="col-3">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="nome_referencia" placeholder="Pesquisar por Nome ou Referência">
                                    </div>
                                    <p class="text-danger">
                                        @error('nome_referencia')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-3">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Tipo</span>
                                        </div>
                                        <select type="text" class="form-control" name="tipo">
                                            <option value="">Todos</option>
                                            <option value="P" {{ $requests['tipo'] == "P" ? 'selected' : '' }}>Produtos</option>
                                            <option value="S" {{ $requests['tipo'] == "S" ? 'selected' : '' }}>Serviços</option>
                                            <option value="O" {{ $requests['tipo'] == "O" ? 'selected' : '' }}>Outro (portes, adiantamentos, etc.)</option>
                                            <option value="I" {{ $requests['tipo'] == "I" ? 'selected' : '' }}>Imposto (excepto IVA e IS) ou Encargo Parafiscal</option>
                                            <option value="E" {{ $requests['tipo'] == "E" ? 'selected' : '' }}>Imposto Especial de Consumo (IABA, ISP e IT)</option>
                                        </select>
                                    </div>
                                    <p class="text-danger">
                                        @error('tipo')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-3">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Categoria</span>
                                        </div>
                                        <select type="text" class="form-control" name="categoria_id">
                                            <option value="">Selecione Categoria</option>
                                            @foreach ($categorias as $categoria)
                                            <option value="{{ $categoria->id }}" {{ $requests['categoria_id'] == $categoria->id ? 'selected' : '' }}>{{ $categoria->categoria }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <p class="text-danger">
                                        @error('categoria_id')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-3">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Marca</span>
                                        </div>
                                        <select type="text" class="form-control" name="marca_id">
                                            <option value="">Selecione Marca</option>
                                            @foreach ($marcas as $marca)
                                            <option value="{{ $marca->id }}" {{ $requests['marca_id'] == $marca->id ? 'selected' : '' }}>{{ $marca->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <p class="text-danger">
                                        @error('marca_id')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                            </div>

                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary btn-sm ml-2 text-right"> <i class="fas fa-search"></i> Pesquisar</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            @if ($produtos)

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
                                @if (Auth::user()->can('criar produtos'))
                                <a href="{{ route('produtos.create') }}" class="btn btn-sm btn-primary">Novo Produto</a>
                                <a href="{{ route('store_import.produtos') }}" class="btn btn-sm btn-success">Importar Excel</a>
                                @endif
                            </h3>

                            <div class="card-tools">
                                <a href="{{ route('pdf-produto', ['categoria_id' => $requests['categoria_id'] ?? "",'tipo' => $requests['tipo'] ?? "", 'marca_id' => $requests['marca_id'] ?? ""]) }}" target="_blink" class="btn btn-sm btn-info float-right"><i class="fas fa-file-pdf"></i> Exportar PDF</a>
                            </div>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Ref</th>
                                        <th>Produto</th>
                                        <th>Categoria</th>
                                        <th>Tipo</th>
                                        <th>Preço</th>
                                        <th>Preço S/IVA</th>
                                        <th>Preço Fornecedor</th>
                                        <th>IVA</th>
                                        @if ($lojas)
                                        @foreach ($lojas as $loja)
                                        <th class="text-center">{{ $loja->nome }}</th>
                                        @endforeach
                                        @endif
                                        <th>Estado</th>
                                        <th>Lote</th>
                                        <th class="text-right">Acções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($produtos as $produto)
                                    <tr>
                                        <td><a href="{{ route('produtos.show', $produto->id) }}">{{ $produto->referencia }} </a></td>
                                        <td><a href="{{ route('produtos.show', $produto->id) }}">{{ $produto->nome }} </a></td>
                                        <td>{{ $produto->categoria->categoria }}</td>

                                        @if($produto->tipo == 'P')
                                        <td>Produto</td>
                                        @endif
                                        @if($produto->tipo == 'S')
                                        <td>Serviço</td>
                                        @endif
                                        @if($produto->tipo == 'O')
                                        <td>Outro (portes, adiantamentos, etc.)</td>
                                        @endif
                                        @if($produto->tipo == 'I')
                                        <td>Imposto (excepto IVA e IS) ou Encargo Parafiscal</td>
                                        @endif
                                        @if($produto->tipo == 'E')
                                        <td>Imposto Especial de Consumo (IABA, ISP e IT)</td>
                                        @endif

                                        <td>{{ number_format($produto->preco_venda??0, 2, ',', '.') }} <span class="text-secondary">{{ $empresa->moeda??"" }}</span></td>
                                        <td>{{ number_format($produto->preco??0, 2, ',', '.') }} <span class="text-secondary">{{ $empresa->moeda??"" }}</span></td>
                                        <td>{{ number_format($produto->preco_custo??0, 2, ',', '.')  }} <span class="text-secondary">{{ $empresa->moeda??"" }}</span></td>
                                        {{-- <td>{{ $produto->exibir_imposto($produto->imposto) }}</td> --}}
                                        <td>{{ $produto->taxa_imposto->valor??0 }} %</td>

                                        @if ($lojas)
                                        @foreach ($lojas as $loja)
                                        <td class="text-center"> <span class="bg-info p-1">{{ $produto->total_produto_por_loja($produto->id, $loja->id) }} </span></td>
                                        @endforeach
                                        @endif

                                        <td>{{ $produto->status ?? 0 }}</td>
                                        <td>{{ $produto->lote_valicidade ?? 0 }}</td>

                                        <td class="text-right">
                                          <div class="btn-group">
                                              <button type="button" class="btn btn-default">Ações</button>
                                              <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                  <span class="sr-only">Toggle Dropdown</span>
                                              </button>
                                              <div class="dropdown-menu" role="menu">
                                                  @if (Auth::user()->can('listar produtos'))
                                                  <a class="dropdown-item" href="{{ route('produtos.show', $produto->id) }}"><i class="fas fa-eye text-info"></i> Detalhes</a>
                                                  @endif
                                                  @if (Auth::user()->can('editar produtos'))
                                                  <a class="dropdown-item" href="{{ route('produtos.edit', $produto->id) }}"><i class="fas fa-edit text-success"></i> Editar</a>
                                                  @endif
                                                  <div class="dropdown-divider"></div>
                                                  @if (Auth::user()->can('eliminar produtos'))
                                                  <button class="btn btn-sm btn-danger dropdown-item delete-record" data-id="{{ $produto->id }}">
                                                    <i class="fas fa-trash text-danger"></i> Eliminar
                                                  </button>
                                                  @endif
                                              </div>
                                          </div>
                                        </td>

                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->

                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
            @else

            <div class="row">
                <div class="col-12">
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center d-flex align-items-center justify-content-center">
                                <div class="" style="padding: 70px 0;">
                                    <svg class="w-6 h-6" width="100px" height="100px" ; fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5">
                                        </path>
                                    </svg>

                                    <h2 class="h4">Não existem registos</h2>
                                    <p class="lead mb-5">Ainda não adicionou nenhum registo em Produto? </p>

                                    <a href="{{ route('produtos.create') }}" class="btn btn-primary btn-lg">&plus; ADICIONAR PRODUTO</a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
@section('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Seleciona todos os campos de entrada na página
        const inputs = document.querySelectorAll('input');
    
        // Itera sobre cada campo de entrada
        inputs.forEach(input => {
            // Garante que o campo esteja focado quando necessário (opcional)
            input.addEventListener('focus', function () {
                console.log(`Campo ${input.name || input.id} está focado.`);
            });
    
            // Adiciona evento de keydown para bloquear atalhos específicos
            input.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || (e.ctrlKey && e.key === 'j')) {
                    e.preventDefault(); // Impede o comportamento padrão
                    
                    
                }
            });
        });
    });

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
