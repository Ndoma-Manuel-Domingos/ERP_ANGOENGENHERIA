@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Diário de movimetos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Diários</li>
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
                <div class="col-lg-2 col-md-2 col-12">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h4>{{ number_format($debito, 2, ',', '.') }} <small>kz</small></h4>
                            <p class="text-uppercase">ENTRADA</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <p href="" class="small-box-footer"> <i class="fas fa-money-check"></i></p>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-2 col-12">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h4>{{ number_format($credito, 2, ',', '.') }} <small>kz</small></h4>
                            <p class="text-uppercase">SAÍDA</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <p href="" class="small-box-footer"> <i class="fas fa-money-check"></i></p>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-2 col-12">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h4>{{ number_format($numerorio_debito, 2, ',', '.') }} <small>kz</small></h4>
                            <p class="text-uppercase">TOTAL FACTURAÇÃO NUMERÁRIO</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <p href="" class="small-box-footer"> <i class="fas fa-money-check"></i></p>
                    </div>
                </div>
            
                <div class="col-lg-2 col-md-2 col-12">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h4>{{ number_format($multicaixa_debito, 2, ',', '.') }} <small>kz</small></h4>
                            <p class="text-uppercase">TOTAL FACTURAÇÃO TPA</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <p href="" class="small-box-footer"> <i class="fas fa-money-check"></i></p>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-2 col-12">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h4>{{ number_format($duplo_debito, 2, ',', '.') }} <small>kz</small></h4>
                            <p class="text-uppercase">TOTAL FACTURAÇÃO DUPLO</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <p href="" class="small-box-footer"> <i class="fas fa-money-check"></i></p>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-2 col-12">
                    <div class="small-box bg-success">
                        <div class="inner">
                            @php
                                $saldo = $debito - $credito;
                            @endphp
                            <h4>{{ number_format($saldo, 2, ',', '.') }} <small>kz</small></h4>
                            <p class="text-uppercase">SALDO</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <p href="" class="small-box-footer"> <i class="fas fa-money-check"></i></p>
                    </div>
                </div>
                
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="callout callout-danger">
                        <h5>Informação!</h5>
                        <p>O Total arrecadado é a soma das vendas realizadas por CASH e MULTICAIXA</p>
                    </div>
                </div>
                
            </div>
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-tools">
                                <a href="{{ route('contabilidade-diarios-pdf') }}" target="_blink" class="btn btn-sm btn-info float-right"><i class="fas fa-file-pdf"></i> Exportar PDF</a>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
                                <thead class="">
                                    <tr>
                                        <th>#</th>
                                        <th>Nº de Registo</th>
                                        <th>Descrição</th>
                                        <th>Data</th>
                                        <th>Forma Pagamento</th>
                                        <th>Cliente</th>
                                        <th>Operador</th>
                                        <th>Caixa</th>
                                        <th>Total</th>
                                        <th class="text-right">Acções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($resultadoUnificado as $contador => $item)
                                    <tr>
                                        <td>{{ $contador + 1 }}</td>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->factura_next }}</td>
                                        <td>{{ date('Y-m-d', strtotime($item->created_at)) }} ÁS {{ date('H:i:s', strtotime($item->created_at)) }}</td>
                                        <td>{{ $item->forma_pagamento($item->pagamento) }}</td>
                                        <td>{{ $item->cliente->nome }}</td>
                                        <td>{{ $item->user->name ?? "" }}</td>
                                        <td>{{ $item->caixa->nome }}</td>
                                        <td>{{ number_format($item->valor_total, 2, ',', '.') }}</td>
                                        <td>
                                            <a href="{{ route('contabilidade-diarios-detalhe', $item->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
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

