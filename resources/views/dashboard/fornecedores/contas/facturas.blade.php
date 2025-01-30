@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Facturas Por Pagar</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
            <li class="breadcrumb-item active">{{ $cliente->nome }}</li>
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
          <!-- /.card-header -->
          <div class="card-body">

            <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
              <thead>
                <tr>
                  <th>Factura</th>
                  <th>Emissão Data</th>
                  <th>Vencimento</th>
                  <th>Estado</th>
                  <th>Condição</th>
                  <th class="text-right">Valor Dívida</th>
                  <th class="text-right"></th>
                </tr>
              </thead>
              <tbody>
                @if ($facturas)
                  @foreach ($facturas as $item)
                    <tr>
                      <td><a href="{{ route('facturas.show', $item->id) }}">{{ $item->factura_next}}</a> </td>
                      <td>{{ $item->data_emissao }}</td>
                      <td>{{ $item->data_vencimento }}</td>
                      <td class="text-uppercase">
                        @if ($item->status_factura == "por pagar")
                          <span class="bg-warning p-1"><i class="fas fa-exclamation-triangle"></i></span> {{ $item->status_factura }}
                        @else
                          @if ($item->status_factura == "anulada")
                            <span class="bg-danger p-1"><i class="fas fa-cancel"></i></span> {{ $item->status_factura }}
                          @else
                            @if ($item->status_factura == "pago")
                              <span class="bg-success p-1"><i class="fas fa-check"></i></span> {{ $item->status_factura }}
                            @endif
                          @endif
                        @endif
                      </td>
                      <td class="text-uppercase">
                        @if ($item->data_vencimento < date('Y-m-d'))
                            <span class="text-danger">Vencida</span>    
                        @endif

                        @if ($item->data_emissao < date('Y-m-d') && $item->data_vencimento > date('Y-m-d'))
                            <span class="text-success">Corrente</span>    
                        @endif
                      </td>
                      <td class="text-right">{{ number_format($item->valor_total, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                      <td class="text-right">

                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-info">Opção</button>
                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                              <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu">
                              <a href="{{ route('facturas.show', $item->id) }}" class="dropdown-item">Liquidar<i class="fas fa-file-invoice float-right
                                "></i> </a>
                              <a href="{{ route('anular-factura', $item->id) }}" class="dropdown-item">Anular<i class="fas fa-cancel float-right
                                "></i> </a>
                              <a href="{{ route('facturas.edit', $item->id) }}" class="dropdown-item">Editar<i class="fas fa-edit float-right
                                "></i> </a>
                              <a href="{{ route('facturas.edit', $item->id) }}" class="dropdown-item">Imprimir<i class="fas fa-print float-right
                                "></i> </a>
                              <div class="dropdown-divider"></div>
                              <a class="dropdown-item" href="#"></a>
                            </div>
                        </div>

                      </td>
                    </tr>
                  @endforeach
                @endif
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
