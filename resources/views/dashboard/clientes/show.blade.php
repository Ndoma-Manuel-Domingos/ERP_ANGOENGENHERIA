@extends('layouts.app')

@section('content')

@php
$meuSaldo = 5000;
@endphp

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detalhe 
                        @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Hotelaria"))
                            Hospode
                        @else
                            Cliente
                        @endif
                    </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">
                            @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Hotelaria"))
                                Hospode
                            @else
                                Cliente
                            @endif
                        </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a class="btn btn-primary" href="{{ route('compras.clientes', $cliente->id) }}">Compras do 
                            @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Hotelaria"))
                                Hospode
                            @else
                                Clientes
                            @endif
                        </a>
                    </div>
                    <div class="card-body">
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
        
                            <div class="col-12 col-md-4">
                                <table class="table text-nowrap">
                                    <tbody>
                                        <tr>
                                            <th>Nome</th>
                                            <td class="text-right">{{ $cliente->nome ?? '-------------' }}</td>
                                        </tr>
        
                                        <tr>
                                            <th>Gênero</th>
                                            <td class="text-right">{{ $cliente->genero ?? '-------------' }}</td>
                                        </tr>
        
                                        <tr>
                                            <th>Data Nascimento</th>
                                            <td class="text-right">{{ $cliente->data_nascimento ?? '-------------' }}</td>
                                        </tr>
        
                                    </tbody>
                                </table>
                            </div>
        
                            <div class="col-12 col-md-4">
                                <table class="table text-nowrap">
                                    <tbody>
        
                                        <tr>
                                            <th>País</th>
                                            <td class="text-right">{{ $cliente->pais ?? '-------------' }}</td>
                                        </tr>
        
                                        <tr>
                                            <th>Estado Cívil</th>
                                            <td class="text-right">{{ $cliente->estado_civil->nome ?? '-------------' }}</td>
                                        </tr>
        
                                        <tr>
                                            <th>NIF/Bilhete</th>
                                            <td class="text-right">{{ $cliente->nif ?? '-------------' }}</td>
                                        </tr>
        
                                    </tbody>
                                </table>
                            </div>
        
                            <div class="col-12 col-md-4">
                                <table class="table text-nowrap">
                                    <tbody>
        
                                        <tr>
                                            <th>Nome do Pai</th>
                                            <td class="text-right">{{ $cliente->nome_do_pai ?? '-------------' }}</td>
                                        </tr>
        
                                        <tr>
                                            <th>Nome da Mãe</th>
                                            <td class="text-right">{{ $cliente->nome_da_mae ?? '-------------' }}</td>
                                        </tr>
        
                                        <tr>
                                            <th>Seguradora</th>
                                            <td class="text-right">{{ $cliente->seguradora->nome ?? '-------------' }}</td>
                                        </tr>
        
                                    </tbody>
                                </table>
                            </div>
        
                            <div class="col-12 col-md-12">
                                <table class="table text-nowrap">
                                    <tbody>
                                        <tr>
                                            <th>Morada</th>
                                            <th>Províncias</th>
                                            <th>Município</th>
                                            <th>Distrito</th>
                                        </tr>
                                        <tr>
                                            <td>{{ $cliente->morada ?? '-------------' }} <br>{{ $cliente->codigo_postal ?? '-------------' }}</td>
                                            <td>{{ $cliente->provincia->nome ?? '-------------' }}</td>
                                            <td>{{ $cliente->municipio->nome ?? '-------------' }}</td>
                                            <td>{{ $cliente->distrito->nome ?? '-------------' }}</td>
                                        </tr>
                                        {{-- -------------------------------------------- --}}
                                        <tr>
                                            <th colspan="4">Contactos</th>
                                        </tr>
                                        <tr>
                                            <td colspan="2">Telefone</td>
                                            <td colspan="2">Telemóvel</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">{{ $cliente->telefone ?? '-------------' }}</td>
                                            <td colspan="2">{{ $cliente->telemovel ?? '-------------' }}</td>
                                        </tr>
                                        {{-- -------------------------------------------- --}}
                                        <tr>
                                            <th colspan="4">Contactos</th>
                                        </tr>
                                        <tr>
                                            <td colspan="2">E-mail</td>
                                            <td colspan="2">Website</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">{{ $cliente->email ?? '-------------' }}</td>
                                            <td colspan="2">{{ $cliente->website ?? '-------------' }}</td>
                                        </tr>
        
                                        <tr>
                                            <th colspan="4">Observação</th>
                                        </tr>
        
                                        <tr>
                                            <td colspan="4">{{ $cliente->observacao ?? '-------------' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
        
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('clientes-movimentos-conta', $cliente->id) }}" class="btn btn-primary"><i class="fas fa-list"></i> Movimentos da conta Corrente</a>
                        <a href="{{ route('clientes-liquidar-factura',  $cliente->id) }}" class="btn btn-outline-primary"><i class="fas fa-file"></i> Liquidar Facturas</a>
                        <a href="{{ route('clientes-actualizar-conta', $cliente->id) }}" class="btn btn-outline-primary"><i class="fas fa-file"></i> Regularizar</a>
                        <a href="{{ route('clientes-extrato-conta', $cliente->id) }}" class="btn btn-outline-primary"><i class="fas fa-file"></i> Extrato</a>
                    </div>
                    <div class="card-body">
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
                                        <h5 class="info-box-number">{{ number_format(0)  }} {{ $tipo_entidade_logado->empresa->moeda }}</h5>
                                        <span class="info-box-text">----------------</span>
                                    </div>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                          
                            <!-- /.col -->
                            <div class="col-md-3 col-sm-6 col-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success"><i class="far fa-flag"></i></span>
            
                                    <div class="info-box-content">
                                        <span class="info-box-text">Dívida Corrente</span>
                                        <h5 class="info-box-number">{{ number_format($facturasVencidasCorrente, 2, ',', '.')  }} {{ $tipo_entidade_logado->empresa->moeda }}</h5>
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
                                        <h5 class="info-box-number">{{ number_format($facturasVencidas, 2, ',', '.') }} {{ $tipo_entidade_logado->empresa->moeda }}</h5>
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
                        <!-- /.info-box -->
                    </div>
                </div>
            </div>
        </div>
    
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            
                            <div class="col-12 col-md-4 text-center">
                                <h1><i class="fas fa-shopping-cart"></i></h1>
                                <h2 class="h4">Compras</h2>
                            </div>
                
                            <div class="col-12 col-md-4 text-right">
                                <h6>Total</h6>
                                <h2 class="h4">{{ number_format($valorTotalCompras, 2, ',', '.') }} <span class="text-secondary"> {{ $tipo_entidade_logado->empresa->moeda }} </span></h2>
                            </div>
                
                            <div class="col-12 col-md-4 text-right">
                                <h6>Total últimos 30 dias</h6>
                                <h2 class="h4">{{ number_format($valorTotalCompras, 2, ',', '.') }} <span class="text-secondary"> {{ $tipo_entidade_logado->empresa->moeda }} </span></h2>
                            </div>
                
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div><!-- /.container-fluid -->

    </section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
