@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detalhe do pagamento</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('contabilidade-diarios') }}">Diários</a></li>
                        <li class="breadcrumb-item active">Detalhes</li>
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
                <div class="col-12">
                    <div class="invoice p-3 mb-3">

                        <div class="row">
                            <div class="col-12">
                                <h4>
                                    <i class="fas fa-globe"></i> {{ $empresa->nome }}.
                                    <small class="float-right">Data: {{ $relatorios->created_at }}</small>
                                </h4>
                            </div>
                        </div>

                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                                Empresa
                                <address>
                                    <strong>{{ $empresa->nome }}</strong><br>
                                    {{ $empresa->nif }}<br>
                                    {{ $empresa->cidade }}<br>
                                    Telefone: {{ $empresa->telefone }}<br>
                                    E-mail | Website: <a href="" class="__cf_email__" data-cfemail="3d54535b527d5c51505c4e5c5858594e4948595452135e5250">{{ $empresa->website }}</a>
                                </address>
                            </div>

                            <div class="col-sm-4 invoice-col">
                                Cliente
                                <address>
                                    <strong>{{ $relatorios->cliente->nome }}</strong><br>
                                    {{ $relatorios->cliente->nif }}<br>
                                    {{ $relatorios->cliente->localidade }}<br>
                                    Telefone: {{ $relatorios->cliente->telefone }}<br>
                                    E-mail: <a href="" class="__cf_email__" data-cfemail="8ce6e3e4e2a2e8e3e9cce9f4ede1fce0e9a2efe3e1">{{ $relatorios->cliente->email }}</a>
                                </address>
                            </div>

                            <div class="col-sm-4 invoice-col">
                                <b>{{ $relatorios->factura_next }}</b><br>
                                <br>
                                <b>Pagamento:</b> {{ $relatorios->forma_pagamento($relatorios->pagamento) }}<br>
                                <b>Operador:</b> {{ $relatorios->user->name }}<br>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Descrição</th>
                                            <th>P.Unitário</th>
                                            <th>Quantidade</th>
                                            <th>Taxa IVA</th>
                                            <th class="text-right">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($relatorios->items as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->produto->nome }}</td>
                                            <td>{{ number_format($item->preco_unitario, 2, ',', '.')  }}</td>
                                            <td>{{ number_format($item->quantidade, 2, ',', '.')  }}</td>
                                            <td>{{ number_format($item->iva_taxa, 1, ',', '.')  }}%</td>
                                            <td class="text-right">{{ number_format($item->valor_pagar, 2, ',', '.')  }} <small class="text-secondary">{{ $empresa->moeda }}</small></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-12 col-md-6">

                            </div>

                            <div class="col-12 col-md-6">
                                {{-- <p class="lead">Amount Due 2/22/2014</p> --}}
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th style="width:50%">Valor Entregue:</th>
                                            <td>{{ number_format($relatorios->valor_entregue, 2, ',', '.')  }} <small class="text-secondary">{{ $empresa->moeda }}</small></td>
                                        </tr>
                                        <tr>
                                            <th style="width:50%">Subtotal:</th>
                                            <td>{{ number_format($relatorios->valor_total - ($relatorios->total_iva + $relatorios->desconto), 2, ',', '.')  }} <small class="text-secondary">{{ $empresa->moeda }}</small></td>
                                        </tr>
                                        <tr>
                                            <th>Iva: </th>
                                            <td>{{ number_format($relatorios->total_iva, 2, ',', '.')  }} <small class="text-secondary">{{ $empresa->moeda }}</small></td>
                                        </tr>
                                        <tr>
                                            <th>Desconto:</th>
                                            <td>{{ number_format($relatorios->desconto, 2, ',', '.')  }} <small class="text-secondary">{{ $empresa->moeda }}</small></td>
                                        </tr>
                                        <tr>
                                            <th>Total:</th>
                                            <td>{{ number_format($relatorios->valor_total, 2, ',', '.')  }} <small class="text-secondary">{{ $empresa->moeda }}</small></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                        </div>

                        <div class="row no-print">
                            <div class="col-12 col-md-12">
                                
                                @if ($item->factura->factura == "FT")
                                <a href="{{ route('factura-factura', $item->code) }}" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Imprimir</a>
                                @endif
                                @if ($item->factura->factura == "FR")
                                <a href="{{ route('factura-recibo', $item->code) }}" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Imprimir</a>
                                @endif
                                @if ($item->factura->factura == "PP" || $item->factura->factura == "FP")
                                <a href="{{ route('factura-proforma', $item->code) }}" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Imprimir</a>
                                @endif
                                @if ($item->factura->factura == "RG")
                                <a href="{{ route('factura-recibo-recibo', $item->code) }}" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Imprimir</a>
                                @endif
                                @if ($item->factura->factura != "FT" && $item->factura->factura != "FR" && $item->factura->factura != "PP" && $item->factura->factura != "RG" && $item->factura->factura != "FP" )
                                <a href="{{ route('factura-nota-credito', $item->code) }}" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Imprimir</a>
                                @endif
                                                            
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

@include('dashboard.config.modal.dados-empresa')
@endsection
