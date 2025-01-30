@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Facturas Sem Pagamentos</h1>
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
        <div class="col-md-4 col-sm-6 col-12">
          <div class="info-box">
            <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Saldo Total</span>
              <h5 class="info-box-number">{{ number_format($facturasVencidas + $facturasVencidasCorrente, 2, ',', '.')  }}</h5>
              @if (($facturasVencidas + $facturasVencidasCorrente) > 0)
                <span class="info-box-text text-success">Existem dívidas</span>
              @else
                <span class="info-box-text">Não existem dívidas</span>
              @endif
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-4 col-sm-6 col-12">
          <div class="info-box">
            <span class="info-box-icon bg-success"><i class="far fa-flag"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Dívida Corrente</span>
              <h5 class="info-box-number">{{ number_format($facturasVencidasCorrente, 2, ',', '.')  }}</h5>
              @if ($facturasVencidasCorrente > 0)
                <span class="info-box-text text-success">Existem pagamentos pendentes</span>
              @else
                <span class="info-box-text">Não existem pagamentos pendentes</span>
              @endif
             
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-4 col-sm-6 col-12">
          <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Dívida Vencida</span>
              <h5 class="info-box-number">{{ number_format($facturasVencidas, 2, ',', '.') }}</h5>
              @if ($facturasVencidas > 0)
                <span class="info-box-text text-success">Existem pagamentos fora do prazo</span>
              @else
                <span class="info-box-text">Não existem pagamentos fora do prazo</span>
              @endif
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>

        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- /.row -->
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <a href="{{ route('facturas.create') }}" class="btn btn-sm btn-primary">Criar Documento</a>
              </h3>
              @if (isset($_GET['tipo_documento']) || isset($_GET['factura']))
                <a href="{{ route('pdf-facturas-sem-pagamento', [$_GET['tipo_documento'], $_GET['factura']]) }}" target="_blink" class="btn btn-sm btn-info float-right"><i class="fas fa-file-pdf"></i> Exportar PDF</a>
              @else
                <a href="{{ route('pdf-facturas-sem-pagamento') }}" target="_blink" class="btn btn-sm btn-info float-right"><i class="fas fa-file-pdf"></i> Exportar PDF</a>
              @endif
            </div>
            <!-- /.card-header -->
            <div class="card-body">

              <div class="row">
                <div class="col-12 bg-light">
                  <form action="{{ route('facturas-sem-pagamento') }}" method="get" class="mt-3">
                    @csrf
                    <div class="card-body row">
          
                      <div class="col-3">
                        <div class="input-group mb-3">
                          <select type="text" class="form-control select2" name="tipo_documento">
                            <option value="todas">Todas</option>
                            <option value="dividas_corrente">Dívidas Corrente</option>
                            <option value="dividas_vencidas">Dívidas Vencidas</option>
                          </select>
                        </div>
                        <p class="text-danger">
                          @error('produto')
                          {{ $message }}
                          @enderror
                        </p>
                      </div>
          
                      <div class="col-3">
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
                    <th style="width: 5px"></th>
                    <th>Factura</th>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Vencimento</th>
                    <th class="text-right">Dívida</th>
                  </tr>
                </thead>
                <tbody>
                  @if ($facturas)
                    @foreach ($facturas as $item)
                      <tr>
                        <td>
                          @if (date("Y-m-d") > $item->data_vencimento)
                            <span class="bg-warning p-2"><i class="fas fa-file"></i></span> 
                          @endif
                          @if (date("Y-m-d") < $item->data_vencimento && date("Y-m-d") > $item->data_emissao)
                            <span class="bg-success p-2"><i class="fas fa-file"></i></span> 
                          @endif
                        </td>
                        <td><a href="{{ route('facturas.show', $item->id) }}">{{ $item->factura_next}}</a> </td>
                        <td>{{ $item->cliente->nome }}</td>
                        <td>{{ $item->data_emissao }}</td>
                        <td>{{ $item->data_vencimento }}</td>
                        <td class="text-right">{{ number_format($item->valor_total, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                      </tr>
                    @endforeach
                  @endif
                </tbody>
              </table>
            </div>

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
