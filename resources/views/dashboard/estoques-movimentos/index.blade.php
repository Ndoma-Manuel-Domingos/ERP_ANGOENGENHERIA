@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Movimentos do Stock</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Stock</li>
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
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('movimento-estoques.index') }}" method="get" id="form_pesquisa">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <label class="form-label">Produto</label>
                                        <select type="text" class="form-control select2" name="produto_id">
                                            <option value="">Todos</option>
                                            @foreach ($produtos as $item)
                                                <option value="{{ $item->id }}" {{ $item->id == $requests['produto_id'] ? 'selected' : "" }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label class="form-label">Loja</label>
                                        <select type="text" class="form-control select2" name="loja_id">
                                            <option value="">Todos</option>
                                            @foreach ($lojas as $item)
                                                <option value="{{ $item->id }}" {{ $item->id == $requests['loja_id'] ? 'selected' : "" }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-12 col-md-3">
                                        <label class="form-label">Data Inicio</label>
                                        <input type="date" class="form-control" name="data_inicio" value="{{ old('data_inicio') ?? $requests['data_inicio'] ?? "" }}">
                                    </div>
                                    
                                    <div class="col-12 col-md-3">
                                        <label class="form-label">Data Final</label>
                                        <input type="date" class="form-control" name="data_final" value="{{ old('data_final') ?? $requests['data_final'] ?? "" }}">
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="card-footer">
                            <button type="submit" form="form_pesquisa" class="btn-sm btn-primary ml-2 text-right"> <i class="fas fa-search"></i>Pesquisar</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <a href="{{ route('estoques.create') }}" class="btn btn-sm btn-primary">Actualizar Stock</a>
                            </h3>
                            <a href="{{ route('pdf-movimento-estoque', ['loja_id' => $_GET['loja_id'] ?? '', 'produto_id' => $_GET['produto_id'] ?? '', 'data_inicio' => $_GET['data_inicio'] ?? '', 'data_final' => $_GET['data_final'] ?? '']) }}" target="_blink" class="btn btn-sm btn-info float-right"><i class="fas fa-file-pdf"></i> Exportar PDF</a>
                        </div>

                        @if ($movimentos)
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 10px"></th>
                                        <th>Produto</th>
                                        <th>Data</th>
                                        <th>Operação</th>
                                        <th>Loja</th>
                                        <th>Observação</th>
                                        <th><span class="float-right">Qtd </span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($movimentos as $movimento)
                                    <tr>
                                        <td class="bg-light text-center">
                                            @if ($movimento->registro == "Entrada de Stock")
                                            <span class="text-success"><i class="fas fa-plus-circle"></i></span>
                                            @endif

                                            @if ($movimento->registro == "Saída de Stock")
                                            <span class="text-danger"><i class="fas fa-minus"></i></span>
                                            @endif

                                            @if ($movimento->registro == "Actualizar de Stock")
                                            <span class="text-secondary"><i class="far fa-edit"></i></span>
                                            @endif
                                        </td>

                                        <td><a href="{{ route('produtos.show', $movimento->produto ? $movimento->produto->id : "") }}">{{ $movimento->produto ? $movimento->produto->nome : "" }}</a></td>
                                        <td>{{ date_format($movimento->created_at, "Y-m-d") }} <br>
                                            <small>{{ date_format($movimento->created_at, "h:i:s") }}</small></td>
                                        <td>{{ $movimento->registro }} <br>
                                            <small class="text-secondary">{{ $movimento->user->name }}</small>
                                        </td>
                                        <td>{{ $movimento->loja->nome }}</td>
                                        @if ($movimento->registro == "Receção de Encomenda" && $movimento->encomenda_id != NULL)
                                        <td><a href="{{ route('fornecedores-encomendas.show', $movimento->encomenda_id) }}">{{ $movimento->observacao }}</a></td>
                                        @else
                                        <td>{{ $movimento->observacao }}</td>
                                        @endif

                                        <td><span class="float-right text-success">{{ $movimento->quantidade }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
           
                        @endif

                    </div>
                </div>
            </div>

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

