@extends('layouts.vendas')

@section('section')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Facturas Sem pagamentos</h1>
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
        <div class="col-12 col-md-1"></div>
        <div class="col-12 col-md-10">
          <div class="card">
            <!-- /.card-header -->
            <div class="card-body">

              <div class="row">
                <div class="col-12 bg-light">
                  <form action="{{ route('pronto-venda.facturas-sem-pagamento') }}" method="get" class="mt-3">
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
                    <th style="width: 10px;"></th>
                    <th>Factura</th>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th class="text-right">Valor</th>
                  </tr>
                </thead>
                <tbody>
                  @if ($facturas)
                    @foreach ($facturas as $item)
                      <tr>
                        @if ($item->factura == "RG")
                            <td><span style="background-color: #ccced4;" class="p-1 text-white">{{ $item->factura }}</span></td>    
                        @endif
                        @if ($item->factura == "EC")
                            <td><span style="background-color: #cfd90c;" class="p-1 text-white">{{ $item->factura }}</span></td>    
                        @endif
                        @if ($item->factura == "OT")
                            <td><span style="background-color: #939917;" class="p-1 text-white">{{ $item->factura }}</span></td>    
                        @endif
                        @if ($item->factura == "FR")
                            <td><span style="background-color: #103be5;" class="p-1 text-white">{{ $item->factura }}</span></td>    
                        @endif
                        @if ($item->factura == "FT")
                            <td><span style="background-color: #1ab6e9;" class="p-1 text-white">{{ $item->factura }}</span></td>    
                        @endif
                        <td><a href="{{ route('pronto-venda.facturas-visualizar', $item->id) }}">{{ $item->factura_next}}</a> </td>
                        <td>{{ $item->cliente->nome }}</td>
                        <td>{{ $item->data_emissao }}</td>
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
