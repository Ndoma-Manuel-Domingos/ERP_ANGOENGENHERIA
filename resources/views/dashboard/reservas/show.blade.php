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
                    <h1 class="m-0">{{ $titulo }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('reservas.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Reservas</li>
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
                <div class="card" style="border: {{ $reserva->status == "CANCELADO" ? '1px solid red' : '' }}">
                    @if ($reserva->status == "CANCELADO")
                    <div class="card-header">
                        <h5 class="text-danger">Reserva Anulada</h5>
                    </div>
                    @endif
                    <div class="card-body">
                        <div class="row">
        
                            <div class="col-12 col-md-4">
                                <table class="table text-nowrap">
                                    <tbody>
                                        <tr>
                                            <th>Nome</th>
                                            <td class="text-right">{{ $reserva->cliente->nome ?? '-------------' }}</td>
                                        </tr>
        
                                        <tr>
                                            <th>Gênero</th>
                                            <td class="text-right">{{ $reserva->cliente->genero ?? '-------------' }}</td>
                                        </tr>
        
                                        <tr>
                                            <th>Data Nascimento</th>
                                            <td class="text-right">{{ $reserva->cliente->data_nascimento ?? '-------------' }}</td>
                                        </tr>
        
                                    </tbody>
                                </table>
                            </div>
        
                            <div class="col-12 col-md-4">
                                <table class="table text-nowrap">
                                    <tbody>
        
                                        <tr>
                                            <th>País</th>
                                            <td class="text-right">{{ $reserva->cliente->pais ?? '-------------' }}</td>
                                        </tr>
        
                                        <tr>
                                            <th>Estado Cívil</th>
                                            <td class="text-right">{{ $reserva->cliente->estado_civil->nome ?? '-------------' }}</td>
                                        </tr>
        
                                        <tr>
                                            <th>NIF/Bilhete</th>
                                            <td class="text-right">{{ $reserva->cliente->nif ?? '-------------' }}</td>
                                        </tr>
        
                                    </tbody>
                                </table>
                            </div>
        
                            <div class="col-12 col-md-4">
                                <table class="table text-nowrap">
                                    <tbody>
        
                                        <tr>
                                            <th>Quarto</th>
                                            <td class="text-right">{{ $reserva->quarto->nome ?? '-------------' }}</td>
                                        </tr>
        
                                        <tr>
                                            <th>Descrição do Quarto</th>
                                            <td class="text-right">{{ $reserva->quarto->descricao ?? '-------------' }}</td>
                                        </tr>
        
                                        <tr>
                                            <th>Estado</th>
                                            <td class="text-right">{{ $reserva->status ?? '-------------' }}</td>
                                        </tr>
        
                                    </tbody>
                                </table>
                            </div>
        
                            <div class="col-12 col-md-12">
                                <table class="table text-nowrap">
                                    <tbody>
                                        {{-- -------------------------------------------- --}}
                                        <tr>
                                            <th colspan="4">Reserva</th>
                                        </tr>
                                        <tr>
                                            <th>Data Entrada</th>
                                            <th>Data Saída</th>
                                            <th>Data Registro</th>
                                            <th>Total Dias</th>
                                        </tr>
                                        <tr>
                                            <td>{{ $reserva->data_inicio ?? '-------------' }}</td>
                                            <td>{{ $reserva->data_final ?? '-------------' }}</td>
                                            <td>{{ $reserva->data_registro ?? '-------------' }}</td>
                                            <td>{{ $reserva->total_dias ?? '-------------' }}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th>Data Check IN</th>
                                            <th>Hora Check IN</th>
                                            <th>Operador Check IN</th>
                                            <th>Exercícios</th>
                                        </tr>
                                        <tr>
                                            <td>{{ $reserva->data_check_in ?? '-------------' }}</td>
                                            <td>{{ $reserva->hora_check_in ?? '-------------' }}</td>
                                            <td>{{ $reserva->user_ckeck_in ? $reserva->user_ckeck_in->name : '-------------' }}</td>
                                            <td>{{ $reserva->exercicio->nome ?? '-------------' }}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th>Data Check OUT</th>
                                            <th>Hora Check OUT</th>
                                            <th>Operador Check OUT</th>
                                            <th>Período</th>
                                        </tr>
                                        <tr>
                                            <td>{{ $reserva->data_check_out ?? '-------------' }}</td>
                                            <td>{{ $reserva->hora_check_out ?? '-------------' }}</td>
                                            <td>{{ $reserva->user_ckeck_out ? $reserva->user_ckeck_out->name : '-------------' }}</td>
                                            <td>{{ $reserva->periodo->nome ?? '-------------' }}</td>
                                        </tr>
                                       
                                    </tbody>
                                </table>
                            </div>
        
        
                            <div class="col-12 col-md-12">
                                <table class="table text-nowrap">
                                    <tbody>
                                        {{-- -------------------------------------------- --}}
                                        <tr>
                                            <th colspan="4">Informações Financeiras</th>
                                        </tr>
                                        <tr>
                                            <th>Valor Total</th>
                                            <th>Valor Divída</th>
                                            <th>Valor Pago</th>
                                            <th>Estado Pagamento</th>
                                        </tr>
                                        <tr>
                                            <td>{{ number_format($reserva->valor_total ?? 0, 2, ',', '.') }}</td>
                                            <td>{{ number_format($reserva->valor_divida ?? 0, 2, ',', '.') }}</td>
                                            <td>{{ number_format($reserva->valor_pago ?? 0, 2, ',', '.') }}</td>
                                            <td>{{ $reserva->pagamento }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tarífario</th>
                                            <th>Valor do Tarífario</th>
                                            <th>Modo de Pagamento</th>
                                            <th>Tipo de Cobrança</th>
                                        </tr>
                                        <tr>
                                            <td>{{ $reserva->tarefario->nome }}</td>
                                            <td>{{ number_format($reserva->tarefario->valor ?? 0, 2, ',', '.') }}</td>
                                            <td>{{ $reserva->tarefario ? $reserva->tarefario->modo_tarefario : '-------------' }}</td>
                                            <td>{{ $reserva->tarefario->tipo_cobranca ?? '-------------' }}</td>
                                        </tr>
                                      
                                    </tbody>
                                </table>
                            </div>
        
                        </div>
                    </div>
                    <div class="card-footer">
                        @if (Auth::user()->can('editar reserva'))
                            @if ($reserva->pagamento == "NAO EFECTUADO" && $reserva->status != "CANCELADO")
                            <a class="btn btn-success" href="{{ route('reservas-fazer-pagamento', $reserva->id) }}"><i class="fas fa-pager"></i> Efecturar Pagamento</a>
                            @endif
                            @if ($reserva->status != "CANCELADO")
                            <a class="btn btn-danger" href="{{ route('reservas-anulacao', $reserva->id) }}"><i class="fas fa-cancel"></i> Anular</a>
                            @endif
                            @if ($reserva->check == "PENDENTE")
                            <a class="btn btn-success" href="{{ route('reservas.check_in', $reserva->id) }}"><i class="fas fa-check"></i> Check In</a>
                            @endif
                            @if ($reserva->check == "IN")
                            <a class="btn btn-danger" href="{{ route('reservas.check_out', $reserva->id) }}"><i class="fas fa-times"></i> Check Out</a>
                            @endif
                        @endif
        
                        @if (Auth::user()->can('listar reserva'))
                            @if ($reserva->check == "IN")
                                <a class="btn btn-info"
                                  href="{{ route('pronto-venda-mesas-quartos', Crypt::encrypt($reserva->quarto_id)) }}"><i
                                  class="fas fa-eye"></i> Fazer Pedidos
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
       
    </section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
