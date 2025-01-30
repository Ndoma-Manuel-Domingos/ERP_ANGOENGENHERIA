@extends('layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">INVENTÁRIO</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Inventário</li>
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
                    <div class="col-12 bg-light">
                        <div class="card">
                            <form action="{{ route('contabilidade-inventario') }}" method="get" class="mt-3">
                                @csrf
                                <div class="card-body row">
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Categoria</label>
                                        <select type="text" class="form-control select2" name="categoria_id">
                                            <option value="">Todas</option>
                                            @foreach ($empresa->categorias as $categoria)
                                                <option value="{{ $categoria->id }}"
                                                    {{ $requests['categoria_id'] == $categoria->id ? 'selected' : '' }}>
                                                    {{ $categoria->categoria }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Marca</label>
                                        <select type="text" class="form-control select2" name="marca_id">
                                            <option value="">Todas</option>
                                            @foreach ($empresa->marcas as $marca)
                                                <option value="{{ $marca->id }}"
                                                    {{ $requests['marca_id'] == $categoria->id ? 'selected' : '' }}>
                                                    {{ $marca->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Produto</label>
                                        <input type="search" class="form-control" name="nome_referencia"
                                            placeholder="Pesquisar por Nome ou Referência">
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn-sm btn-primary"> Pesquisar <i
                                            class="fa fa-search"></i> </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <a href="{{ route('contabilidade-inventario-exportar-pdf') }}" target="_blink"
                                    class="bg-danger btn btn-sm float-right">Exportar PDF</a>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
                                    <thead class="">
                                        <tr>
                                            <th>Descrição do Activo</th>
                                            <th>Categoria</th>
                                            <th>Marca</th>
                                            <th>Qtd</th>
                                            <th>P.unit</th>
                                            <th class="text-right">Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $precos = 0;
                                            $quantidades = 0;
                                            $total = 0;
                                        @endphp
                                        @foreach ($produtos as $item)
                                            @php
                                                $precos = $precos + $item->preco_venda;
                                                $quantidades = $quantidades + $item->quantidade_sum_quantidade;
                                                $total = $total + $item->quantidade_sum_quantidade * $item->preco_venda;
                                            @endphp
                                            <tr>
                                                <td>{{ $item->nome }}</td>
                                                <td>{{ $item->categoria->categoria }}</td>
                                                <td>{{ $item->marca->nome }}</td>
                                                <td>{{ number_format($item->quantidade_sum_quantidade, 1, ',', '.') }}</td>
                                                <td>{{ number_format($item->preco_venda, 2, ',', '.') }} <small>{{ $empresa->moeda }}</small></td>
                                                <td class="text-right"> {{ number_format($item->quantidade_sum_quantidade * $item->preco_venda, 2, ',', '.') }} <small>{{ $empresa->moeda }}</small></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3">Total de Activo</th>
                                            <th>{{ number_format($quantidades, 1, ',', '.') }}</th>
                                            <th>{{ number_format($precos, 2, ',', '.') }}</th>
                                            <th class="text-right">{{ number_format($total, 2, ',', '.') }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                        </div>
                    </div>

                </div>
                <!-- /.row -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    @include('dashboard.config.modal.dados-empresa')
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

