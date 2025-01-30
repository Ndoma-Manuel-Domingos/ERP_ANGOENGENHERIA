@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">FACTURAÇÃO</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Starter Page</li>
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
                            <h4>{{ number_format($total_arrecadado_cash, 2, ',', '.') }} <small>kz</small></h4>
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
                            <h4>{{ number_format($total_arrecadado_multicaixa, 2, ',', '.') }} <small>kz</small></h4>
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
                            <h4>{{ number_format($total_arrecadado_transferencias ?? 0, 2, ',', '.') }} <small>kz</small></h4>
                            <p class="text-uppercase">TOTAL FACTURAÇÃO TRANSFERÊNCIAS</p>
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
                            <h4>{{ number_format($total_arrecadado_depositos ?? 0, 2, ',', '.') }} <small>kz</small></h4>
                            <p class="text-uppercase">TOTAL FACTURAÇÃO DEPOSITOS</p>
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
                            <h4>{{ number_format(($total_arrecadado_cash + $total_arrecadado_multicaixa + $total_arrecadado_transferencias + $total_arrecadado_depositos), 2, ',', '.') }} <small>kz</small></h4>
                            <p class="text-uppercase">FACTURAÇÃO</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <p href="" class="small-box-footer"> <i class="fas fa-money-check"></i></p>
                    </div>
                </div>
                
                <div class="col-lg-12 col-md-12 col-12">
                   <div class="card">
                        <form action="{{ route('contabilidade-balancete') }}">
                            @csrf
                            <div class="card-body">
                                <div class="row">
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
                                        <label for="" class="form-label">Caixa</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Caixa</span>
                                            </div>
                                            <select type="text" class="form-control select2" name="caixa_id">
                                                <option value="">Selecione Caixa</option>
                                                @foreach ($empresa->caixas as $caixa)
                                                <option value="{{ $caixa->id }}" {{ $requests['caixa_id'] == $caixa->id ? 'selected' : ''}}>{{ $caixa->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
    
                                    <div class="col-3">
                                        <label for="" class="form-label">Operador</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Operador</span>
                                            </div>
                                            <select type="text" class="form-control" name="user_id">
                                                <option value="">Selecione Operador</option>
                                                @foreach ($empresa->users as $user)
                                                <option value="{{ $user->id }}" {{ $requests['user_id'] == $user->id ? 'selected' : ''}}>{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
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
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-tools">
                                <a href="{{ route('pdf-vendas', ['data_inicio' => $requests['data_inicio'] ?? '', 'data_final' => $requests['data_final'] ?? '', 'caixa_id' => $requests['caixa_id'], 'user_id' => $requests['user_id']]) }}" target="_blink" class="btn btn-sm btn-info float-right"><i class="fas fa-file-pdf"></i> Exportar PDF</a>
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
                                    @foreach ($relatorios as $contador => $item)
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

