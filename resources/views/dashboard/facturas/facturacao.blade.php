@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Facturação</h1>
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
     <!-- /.row -->
     <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              <a href="{{ route('facturas.create') }}" class="btn btn-sm btn-primary">Criar Factura</a>
            </h3>
            @if (isset($_GET['factura']))
              <a href="{{ route('pdf-facturas-facturacao', $_GET['factura']) }}" target="_blink" class="btn btn-sm btn-info float-right"><i class="fas fa-file-pdf"></i> Exportar PDF</a>
            @else
              <a href="{{ route('pdf-facturas-facturacao') }}" target="_blink" class="btn btn-sm btn-info float-right"><i class="fas fa-file-pdf"></i> Exportar PDF</a>
            @endif
          </div>
          <!-- /.card-header -->
          <div class="card-body">

            <div class="row">
              <div class="col-12 bg-light">
                <form action="{{ route('facturas-facturacao') }}" method="get" class="mt-3">
                  @csrf
                  <div class="card-body row">
        
                    <div class="col-6">
                      <div class="input-group mb-3">
                        <input type="search" class="form-control" name="factura" placeholder="Pesquisar Por Factura">
                      </div>
                      <p class="text-danger">
                        @error('factura')
                        {{ $message }}
                        @enderror
                      </p>
                    </div>
                    
                    <div class="col-12 col-md-6">
                      <div class="row">
                        <div class="col-sm-12 col-md-10">
                          <div class="input-group">
                            <button type="submit" class="btn btn-primary ml-2 text-right"> <i class="fas fa-search"></i>
                              Pesquisar</button>
                          </div>
                        </div>
                      </div>
                    </div>
    
                  </div>
        
                </form>
              </div>
            </div>

            <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
              <thead>
                <tr>
                  <th>Factura</th>
                  <th>Cliente</th>
                  <th>Operador</th>
                  <th>Data</th>
                  <th>Vencimento</th>
                  <th>Estado</th>
                  <th class="text-right">Dívida</th>
                </tr>
              </thead>
              <tbody>
                @if ($facturas)
                  @foreach ($facturas as $item)
                    <tr>
                      <td><a href="{{ route('facturas.show', $item->id) }}">{{ $item->factura_next}}</a> </td>
                      <td><a href="{{ route('clientes.show', $item->cliente->id) }}">{{ $item->cliente->nome }}</a></td>
                      <td>{{ $item->user->name ?? "" }}</td>
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
                      <td class="text-right">{{ number_format($item->valor_total, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
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

