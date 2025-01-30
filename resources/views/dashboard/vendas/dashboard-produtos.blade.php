@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Listagem de vendas Por Produtos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
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
                <div class="col-12 bg-light">
                    <div class="card">
                        <form action="{{ route('vendas_produtos') }}" method="get" class="mt-3">
                            @csrf
                            <div class="card-body row">

                                <div class="col-3">
                                    <label for="" class="form-label">Data Inicio</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" value="{{ $requests['data_inicio'] ?? '' }}" name="data_inicio" placeholder="Data Inicio">
                                    </div>
                                </div>

                                <div class="col-3">
                                    <label for="" class="form-label">Data Final</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" value="{{ $requests['data_final'] ?? '' }}" name="data_final" placeholder="Data final">
                                    </div>
                                </div>

                                <div class="col-3">
                                    <label for="" class="form-label">Categoria</label>
                                    <select type="text" class="form-control select2" name="caixa_id">
                                        <option value="">Selecione Caixa</option>
                                        @foreach ($empresa->caixas as $caixa)
                                        <option value="{{ $caixa->id }}" {{ $requests['caixa_id'] == $caixa->id ? 'selected' : ''}}>{{ $caixa->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-3">
                                    <label for="" class="form-label">Operador</label>
                                    <select type="text" class="form-control select2" name="user_id">
                                        <option value="">Selecione Operador</option>
                                        @foreach ($empresa->users as $user)
                                        <option value="{{ $user->id }}" {{ $requests['user_id'] == $user->id ? 'selected' : ''}}>{{ $user->name }}</option>
                                        @endforeach
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-tools">
                                <a href="{{ route('pdf-vendas', ['data_inicio' => $requests['data_inicio'] ?? '', 'data_final' => $requests['data_final'] ?? '', 'caixa_id' => $requests['caixa_id'], 'user_id' => $requests['user_id']]) }}" target="_blink" class="btn btn-sm btn-info float-right"><i class="fas fa-file-pdf"></i> Exportar PDF</a>
                            </div>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Ref</th>
                                        <th>Factura</th>
                                        <th>Operador</th>
                                        <th>Cliente</th>
                                        <th>Data</th>
                                        <th class="text-right">Preço Unitário</th>
                                        <th class="text-right">Quantidade</th>
                                        <th class="text-right">IVA</th>
                                        <th class="text-right">Total</th>
                                        <th class="text-right">Acções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vendas as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->factura->factura_next }}</td>
                                        <td>{{ $item->user->name ?? "" }}</td>
                                        <td>{{ $item->factura->cliente->nome }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td class="text-right">{{ number_format($item->preco_unitario, 2, ',', '.') }} <span class="text-secondary">{{ $empresa->moeda }}</span></td>
                                        <td class="text-right">{{ number_format($item->quantidade, 2, ',', '.') }} <span class="text-secondary">{{ $empresa->moeda }}</span></td>
                                        <td class="text-right">{{ number_format($item->iva_taxa, 2, ',', '.') }} <span class="text-secondary">%</span></td>
                                        <td class="text-right">{{ number_format($item->valor_pagar, 2, ',', '.') }} <span class="text-secondary">{{ $empresa->moeda }}</span></td>
                                        <td class="text-right">
                                            <a href="{{ route('contabilidade-diarios-detalhe', $item->factura_id) }}" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr class="bg-dark">
                                        <td class="text-right" colspan="8">TOTAL</td>
                                        <td class="text-right">{{ number_format($total_venda ?? 0, 2, ',', '.') }} <span class="text-secondary">{{ $empresa->moeda ?? "AOA" }}</span></td>
                                        <td class="text-right"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
             
                    </div>
                    <!-- /.card -->
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
