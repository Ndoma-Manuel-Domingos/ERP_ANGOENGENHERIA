@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Conta Corrente</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Conta</li>
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
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="far fa-user"></i></span>

                        <div class="info-box-content">
                            <h4 class="info-box-text">Conta Corrente</h4>
                            <h1 class="info-box-number">KZ</h1>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>


                <div class="col-md-3 col-sm-6 col-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>
        
                    <div class="info-box-content">
                      <span class="info-box-text">Saldo Total</span>
                      {{-- <h5 class="info-box-number">{{ number_format($facturasVencidas + $facturasVencidasCorrente , 2, ',', '.')  }}  {{ $tipo_entidade_logado->empresa->moeda }}</h5> --}}
                      <h5 class="info-box-number">{{ number_format(0)  }}  {{ $tipo_entidade_logado->empresa->moeda }}</h5>
                      {{-- @if (($facturasVencidas + $facturasVencidasCorrente) > 0)
                        <span class="info-box-text text-success">Existe dívida</span>
                      @else --}}
                        <span class="info-box-text">----------------</span>
                        {{-- <span class="info-box-text">Não existem dívidas</span> --}}
                      {{-- @endif --}}
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="far fa-flag"></i></span>
        
                    <div class="info-box-content">
                      <span class="info-box-text">Dívida Corrente</span>
                      <h5 class="info-box-number">{{ number_format($facturasVencidasCorrente, 2, ',', '.')  }}  {{ $tipo_entidade_logado->empresa->moeda }}</h5>
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
                <div class="col-md-3 col-sm-6 col-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span>
        
                    <div class="info-box-content">
                      <span class="info-box-text">Dívida Vencida</span>
                      <h5 class="info-box-number">{{ number_format($facturasVencidas, 2, ',', '.') }}  {{ $tipo_entidade_logado->empresa->moeda }}</h5>
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
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <a href="{{ route('clientes-liquidar-factura', $cliente->id) }}" class="btn btn-primary">Liquidar Factura</a>
                                <a href="{{ route('clientes-actualizar-conta', $cliente->id) }}" class="btn btn-outline-primary">Regularizar</a>
                            </h3>

                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($facturas)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Documento</th>
                                        <th>Tipo</th>
                                        <th>Data</th>
                                        <th>Data Vencimento</th>
                                        <th class="text-right">Montante</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($facturas as $movimento)
                                    <tr>
                                        <td>{{ $movimento->factura_next }} </td>
                                        <td>Regularização <br> <small>{{ $movimento->observacao }}</small></td>
                                        <td>{{ $movimento->data_emissao }}</td>
                                        <td>{{ $movimento->data_vencimento }}</td>
                                        <td class="text-right text-danger">{{ number_format($movimento->valor_total, 2, ',', '.') }} {{ $empresa->empresa->moeda }}</td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
               
                        @endif

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
