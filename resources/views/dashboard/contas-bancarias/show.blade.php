@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><span class="text-uppercase">{{ $banco->conta }} - {{ $banco->nome }}</span> - Movimentos da Conta Bancária
                    </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('contas-bancarias.index') }}">Voltar</a></li>
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

                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-sm-8">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-4">
                                          <form action="{{ route('contas-bancarias.show', $banco->id) }}" method="GET">
                                            @csrf
                                            @method('get')
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Data </span>
                                                </div>
                                                <input type="date" class="form-control" name="data_inicio" value="{{ old('data_inicio') ?? $requests['data_inicio'] }}">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> Até </span>
                                                </div>
                                                <input type="date" class="form-control" name="data_final" value="{{ old('data_final') ?? $requests['data_final'] }}">
                                                <button type="submit" class="btn btn-primary ml-2"> <i class="fas fa-search"></i> Filtar</button>
                                            </div>
                                          </form>
                                        </div>

                                        <div class="col-sm-12 col-md-8">
                                            <a href="{{ route('contas-bancarias.movimentos_banco', ['data_inicio' => $requests['data_inicio'] ?? "",'data_final' => $requests['data_final'] ?? "", 'banco_id' => $banco->id, 'operador_id' => $requests['operador_id'] ?? "", 'documento_pdf' => 'exportar_pdf']) }}" target="_blink" class="float-right btn btn-primary">Exportar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    @if ($banco)
                    <!-- /.card-header -->
                    @if ($movimentos)
                    @foreach ($movimentos as $item)
                    <div class="card">
                        <div class="card-body table-responsive mb-4">
                            <table class="table text-nowrap">
                                <tbody>
                                    <tr>
                                        @php
                                            $diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado');
                                            $meses = array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
                                            $diasemana_numero = date('w', strtotime($item->data_abertura));
                                            $meses_numero = date('w', strtotime($item->data_abertura));
                                        @endphp
                                        <td rowspan="2" class="text-center">
                                            <span> {{ $diasemana[$diasemana_numero] }} </span><br>
                                            <small>{{ date('d', strtotime($item->data_abertura)) }} de {{ $meses[$meses_numero] }} {{ date('Y', strtotime($item->data_abertura)) }}</small> <br>
                                            <span>0,00 {{ $dados->empresa->moeda }}</span>
                                        </td>
                                        <td class="text-right">Abertura</td>
                                        <td class="text-left"><strong>{{ $item->hora_abertura ?? "" }}</strong></td>
                                        <td class="text-right">Valor</td>
                                        <td class="text-left"><strong>{{ number_format($item->valor_abertura??0, 2, ',', '.')  }} {{ $dados->empresa->moeda }}</strong></td>
                                        <td class="text-right">Utilizador</td>
                                        @if (!empty($item->user_id))
                                        <td class="text-left"><strong> {{ $item->user->name ?? "" }} </strong> </td>
                                        @else
                                        <td class="text-left"><strong>N/A</strong></td>
                                        @endif
                                        <td rowspan="2" class="text-center">
                                            <br>
                                            <a href="{{ route('contas-bancarias.detalhe', $item->id) }}" class="btn btn-sm btn-outline-primary float-right mr-2">Detalhe</a>
                                            <a href="{{ route('contas-bancarias.movimentos_bancos_imprimir', ['id_imprimir' => $item->id]) }}" target="_blink" class="btn btn-sm btn-outline-primary float-right mr-2">Exportar</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">Fecho</td>
                                        <td class="text-left"><strong>{{ $item->hora_fecho }}</strong></td>
                                        <td class="text-right">Valor</td>
                                        <td class="text-left"><strong>{{ number_format($item->valor_valor_fecho??0, 2, ',', '.')  }} {{ $dados->empresa->moeda }}</strong></td>
                                        <td class="text-right">Utilizador</td>
                                        @if (!empty($item->user_fecho))
                                        <td class="text-left"><strong>
                                                {{ $item->user->name ?? "" }}
                                            </strong>
                                        </td>
                                        @else
                                        <td class="text-left"><strong>N/A</strong></td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                    @endif
                    @endif
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
