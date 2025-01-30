@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Produto no Stock</h1>
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

                <div class="col-12">
                    <div class="card">
                        <form action="{{ route('estoques-produtos') }}" method="get">
                            <div class="card-body">
                                @csrf
                                <div class="col-12 col-md-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Pesquisar</span>
                                        </div>
                                        <select type="text" class="form-control select2" name="status">
                                            <option value="">Todos</option>
                                            <option value="activo">Activos</option>
                                            <option value="expirado">Expirados</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-sm btn-primary">Pesquisar <i class="fas fa-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <a class="float-left"> Total de Registro: {{ $estoques->total() }}</a>
                            <a href="{{ route('imprimir-estoques-produtos', ['status' => $requests['status'] ?? '']) }}" target="_blink" class="btn btn-sm btn-info float-right"><i class="fas fa-file-pdf"></i> Exportar PDF</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body  p-0">

                            <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Lote</th>
                                        <th>Codigo Barra(Lote)</th>
                                        <th>Estado(Lote)</th>
                                        <th>Codigo Barra(Produto)</th>
                                        <th>Produto</th>
                                        <th>Marca</th>
                                        <th>Categoria</th>
                                        <th>Stock Minimo</th>
                                        <th>Stock</th>
                                        <th><span class="float-right">Preço </span></th>
                                        <th><span class="float-right">Valor Acumulado </span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($estoques as $item)
                                    <tr>
                                        <td>{{ $item->lote->lote }}</td>
                                        <td>{{ $item->lote->codigo_barra }}</td>
                                        @if ($item->lote->status == 'activo')
                                        <td class="text-success text-uppercase">{{ $item->lote->status }}</td>
                                        @else
                                        <td class="text-danger text-uppercase">{{ $item->lote->status }}</td>
                                        @endif
                                        <td>{{ $item->produto->codigo_barra }}</td>
                                        <td><a href="{{ route('produtos.show', $item->produto->id) }}">{{ $item->produto->nome }}</a></td>
                                        <td>{{ $item->produto->marca->nome }}</td>
                                        <td>{{ $item->produto->categoria->categoria }}</td>
                                        <td>{{ $item->stock_minimo }}</td>
                                        <td>{{ $item->stock }}</td>
                                        <td><span class="float-right">{{ number_format($item->produto->preco, '2', ',', '.')  }}</span></td>
                                        <td>
                                            <span class="float-right">{{ number_format($item->produto->preco * $item->stock, '2', ',', '.')  }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

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
