@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detalhe do movimento da Conta Bancária</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Conta Bancária</li>
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
                <div class="col-6">
                    <div class="card card-secondary card-outline">
                        <div class="card-header bg-light">
                            <h3 class="card-title">
                                Abertura <br><small>{{ number_format($movimento->valor_abertura ?? 0, 2, ',', '.') }} {{
                                    $empresa->moeda }}</small>
                            </h3>
                        </div>

                        <div class="card-body">
                            <h6><strong>Utilizador:</strong> <span class="float-right">{{ $movimento->user->name ?? ""
                                    }}</span></h6>
                            <h6><strong>Data:</strong> <span class="float-right">{{ date_format($movimento->created_at,
                                    'Y/m/d') }}</span></h6>
                            <h6><strong>Hora:</strong> <span class="float-right">{{ date_format($movimento->created_at,
                                    'H:i:s') }}</span></h6>
                            <h6><strong>Valor:</strong> <span class="float-right">{{
                                    number_format($movimento->valor_abertura ?? 0, 2, ',', '.') }} {{ $empresa->moeda
                                    }} <br><small>Abertura</small></span> </h6>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card card-secondary card-outline">
                        <div class="card-header bg-light">
                            <h3 class="card-title">
                                Abertura <br><small>{{ number_format($movimento->valor_abertura ?? 0, 2, ',', '.') }} {{
                                    $empresa->moeda }}</small>
                            </h3>
                        </div>

                        <div class="card-body">
                            <h6><strong>Utilizador:</strong> <span class="float-right">{{ $movimento->user->name ?? ""
                                    }}</span></h6>
                            <h6><strong>Data:</strong> <span class="float-right">{{ date_format($movimento->updated_at,
                                    'Y/m/d') }}</span></h6>
                            <h6><strong>Hora:</strong> <span class="float-right">{{ date_format($movimento->updated_at,
                                    'H:i:s') }}</span></h6>
                            <h6><strong>Valor:</strong> <span class="float-right">{{ $movimento->user->name ?? "" }}</span>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-12" id="accordion">
                    <div class="card">
                        <a class="d-block w-100" data-toggle="collapse" href="#resumoPagamento">
                            <div class="card-header bg-light">
                                <h4 class="card-title w-100 text-secondary">
                                    Resumo dos movimentos
                                    <span class="float-right">{{ number_format($movimento->valor_valor_fecho ?? 0, 2, ',', '.') }} {{ $empresa->moeda }} </span>
                                </h4>
                            </div>
                        </a>
                        <div id="resumoPagamento" class="collapse show" data-parent="#accordion">
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Movimento</th>
                                            <th>Total</th>
                                            <th>s/IVA</th>
                                            <th>IVA</th>
                                            <th style="width: 40px">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Entrada</td>
                                            <td>0</td>
                                            <td>{{ number_format(0, 2, ',', '.') }} {{ $empresa->moeda ?? "" }}</td>
                                            <td>{{ number_format(0, 2, ',', '.') }} {{ $empresa->moeda ?? "" }}</td>
                                            <td><span class="badge">{{ number_format(0, 2, ',', '.') }} {{ $empresa->moeda ?? "" }}</span></td>
                                        </tr>

                                        <tr>
                                            <td>Saída</td>
                                            <td>0</td>
                                            <td>{{ number_format(0, 2, ',', '.') }} {{ $empresa->moeda ?? "" }}</td>
                                            <td>{{ number_format(0, 2, ',', '.') }} {{ $empresa->moeda ?? "" }}</td>
                                            <td><span class="badge">{{ number_format(0, 2, ',', '.') }} {{ $empresa->moeda ?? "" }}</span></td>
                                        </tr>

                                        <tr>
                                            <td>Venda Cancelada</td>
                                            <td>0</td>
                                            <td>{{ number_format(0, 2, ',', '.') }} {{ $empresa->moeda ?? "" }}</td>
                                            <td>{{ number_format(0, 2, ',', '.') }} {{ $empresa->moeda ?? "" }}</td>
                                            <td><span class="badge">{{ number_format(0, 2, ',', '.') }} {{ $empresa->moeda ?? "" }}</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <a class="d-block w-100" data-toggle="collapse" href="#facturaPrazo">
                            <div class="card-header bg-light">
                                <h4 class="card-title w-100 text-secondary">
                                    Facturas a Prazo
                                </h4>
                            </div>
                        </a>
                        <div id="facturaPrazo" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                                Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur
                                ridiculus mus.
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <a class="d-block w-100" data-toggle="collapse" href="#listaMovimentos">
                            <div class="card-header bg-light">
                                <h4 class="card-title w-100 text-secondary">
                                    Lista dos movimentos
                                </h4>
                            </div>
                        </a>
                        <div id="listaMovimentos" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Movimento</th>
                                            <th>Data</th>
                                            <th>Utilizador</th>
                                            <th>Documento</th>
                                            <th>s/IVA</th>
                                            <th>IVA</th>
                                            <th style="width: 40px">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Abertura</td>
                                            <td>{{ date_format($movimento->created_at, 'Y-m-d H:i:s') }}</td>
                                            <td>{{ $movimento->user->name ?? "" }}</td>
                                            <td>N/A</td>
                                            <td></td>
                                            <td></td>
                                            <td><span class="badge">{{ number_format(0, 2, ',', '.') }} {{ $empresa->moeda ?? "" }}</span></td>
                                        </tr>

                                        <tr>
                                            <td>Saída <br>Abertura de Gaveta</td>
                                            <td>{{ date_format($movimento->created_at, 'Y-m-d H:i:s') }}</td>
                                            <td>{{ $movimento->user->name ?? "" }}</td>
                                            <td>N/A</td>
                                            <td></td>
                                            <td></td>
                                            <td><span class="badge">{{ number_format(0, 2, ',', '.') }} {{ $empresa->moeda }}</span></td>
                                        </tr>

                                        <tr>
                                            <td>Fecho</td>
                                            <td>{{ date_format($movimento->created_at, 'Y-m-d H:i:s') }}</td>
                                            <td>{{ $movimento->user->name ?? "" }}</td>
                                            <td>N/A</td>
                                            <td></td>
                                            <td></td>
                                            <td><span class="badge">{{ number_format(0, 2, ',', '.') }} {{ $empresa->moeda ?? "" }}</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
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